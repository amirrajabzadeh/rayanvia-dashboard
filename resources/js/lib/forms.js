/**
 * ارسال فرم‌های #createForm / #updateForm با axios.
 * پاسخ استاندارد: { success, message, redirect } — خطا: { success:false, message, errors }.
 * خطاهای ۴۲۲ زیر همان فیلد و در عنصر [data-error-for] نمایش داده می‌شوند.
 */

import { t } from './i18n';

function clearErrors(form) {
    form.querySelectorAll('[data-error-for]').forEach((el) => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    form.querySelectorAll('[aria-invalid="true"]').forEach((el) => el.removeAttribute('aria-invalid'));
}

function showErrors(form, errors) {
    Object.entries(errors).forEach(([field, messages]) => {
        const key = field.replace(/\.(\w+)/g, '[$1]');
        const slot = form.querySelector(`[data-error-for="${CSS.escape(key)}"]`);
        const input = form.querySelector(`[name="${CSS.escape(key)}"]`);

        if (slot) {
            slot.textContent = messages[0];
            slot.classList.remove('hidden');
        }
        input?.setAttribute('aria-invalid', 'true');
    });

    form.querySelector('[aria-invalid="true"]')?.focus();
}

function setBusy(form, busy) {
    form.querySelectorAll('[type="submit"]').forEach((button) => {
        button.disabled = busy;
        button.dataset.loading = busy ? 'true' : 'false';
    });
}

async function submit(form) {
    clearErrors(form);
    setBusy(form, true);

    try {
        const { data } = await window.axios.post(form.dataset.route, new FormData(form));

        if (data.message) {
            window.showToast?.(data.message, 'success');
        }
        if (data.redirect) {
            window.location.href = data.redirect;
            return;
        }
    } catch (error) {
        if (error.response?.status === 422) {
            const { errors = {} } = error.response.data;
            showErrors(form, errors);
            window.showToast?.(t('form_invalid'), 'error');
        }
    } finally {
        setBusy(form, false);
    }
}

export function initForms() {
    document.querySelectorAll('#createForm, #updateForm').forEach((form) => {
        if (!form.dataset.route) {
            return;
        }
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            submit(form);
        });
    });

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('.delete-btn');
        if (!button) {
            return;
        }

        event.preventDefault();
        const ok = await window.showConfirm?.(button.dataset.confirm || t('delete_confirm'));
        if (!ok || !button.dataset.route) {
            return;
        }

        try {
            const { data } = await window.axios.delete(button.dataset.route);
            window.showToast?.(data.message || t('deleted'), 'success');
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } catch (error) {
            //
        }
    });
}
