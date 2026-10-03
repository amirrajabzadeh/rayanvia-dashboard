/**
 * جدول AJAX بدون کد صفحه‌ای.
 *
 *  <form id="filterForm" data-route="..."> … </form>
 *  <div id="tableData">@include('…partial')</div>
 *
 * نام فیلدها باید با کلیدهای only() در کنترلر یکی باشد. مرتب‌سازی و صفحه‌بندی
 * در همان فرم (فیلد مخفی sort_by / sort_order) نگه‌داری می‌شود.
 */

import { t } from './i18n';

const DEBOUNCE_MS = 350;

export function initTables() {
    const form = document.getElementById('filterForm');
    const target = document.getElementById('tableData');

    if (!form || !target) {
        return;
    }

    const route = form.dataset.route || form.getAttribute('action') || window.location.pathname;
    let timer = null;
    let controller = null;

    const load = async (page = null) => {
        const params = new URLSearchParams(new FormData(form));

        [...params.keys()].forEach((key) => {
            if (params.get(key) === '') {
                params.delete(key);
            }
        });

        if (page) {
            params.set('page', page);
        }

        controller?.abort();
        controller = new AbortController();
        target.setAttribute('aria-busy', 'true');

        try {
            const { data } = await window.axios.get(route, {
                params,
                signal: controller.signal,
                headers: { Accept: 'text/html' },
            });

            target.innerHTML = data;
            window.history.replaceState(null, '', `${window.location.pathname}?${params}`);
            target.dispatchEvent(new CustomEvent('ajax-content:loaded', { bubbles: true }));
            window.Alpine?.initTree(target);
        } catch (error) {
            if (error.code !== 'ERR_CANCELED') {
                window.showToast?.(t('table_failed'), 'error');
            }
        } finally {
            target.removeAttribute('aria-busy');
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        load();
    });

    form.addEventListener('input', (event) => {
        if (event.target.matches('input[type="search"], input[type="text"]')) {
            clearTimeout(timer);
            timer = setTimeout(() => load(), DEBOUNCE_MS);
        }
    });

    form.addEventListener('change', (event) => {
        if (event.target.matches('select, input[data-select], input[type="checkbox"], input[type="radio"], input[type="date"]')) {
            load();
        }
    });

    target.addEventListener('click', (event) => {
        const pageLink = event.target.closest('a[href*="page="]');
        if (pageLink && target.contains(pageLink)) {
            event.preventDefault();
            load(new URL(pageLink.href, window.location.origin).searchParams.get('page'));
            return;
        }

        const sortButton = event.target.closest('[data-sort]');
        if (sortButton) {
            event.preventDefault();
            const by = form.elements.sort_by;
            const order = form.elements.sort_order;

            if (by && order) {
                order.value = by.value === sortButton.dataset.sort && order.value === 'asc' ? 'desc' : 'asc';
                by.value = sortButton.dataset.sort;
                load();
            }
        }
    });
}

/**
 * برچسب خانه‌ها در حالت کارت (جدول .rtable): متن عنوان هر ستون در data-label خانه‌های همان ستون.
 * خانه‌ای که خودش label دارد دست نمی‌خورد.
 */
export function labelTableCells(root = document) {
    root.querySelectorAll('.rtable table').forEach((table) => {
        const labels = [...table.querySelectorAll('thead th')].map((th) => th.textContent.replace(/\s+/g, ' ').trim());

        table.querySelectorAll('tbody > tr').forEach((row) => {
            [...row.children].forEach((cell, index) => {
                if (!cell.hasAttribute('data-label') && labels[index]) {
                    cell.setAttribute('data-label', labels[index]);
                }
            });
        });
    });
}

document.addEventListener('ajax-content:loaded', (event) => labelTableCells(event.target ?? document));
