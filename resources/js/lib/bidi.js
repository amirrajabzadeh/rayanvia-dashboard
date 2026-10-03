/**
 * جهت خودکار فیلدهای متنی ([data-auto-dir]):
 * اگر اولین حرف جهت‌دار فارسی/عربی باشد راست‌چین، اگر لاتین یا فقط عدد باشد چپ‌چین،
 * و فیلد خالی راست‌چین می‌ماند تا placeholder فارسی درست دیده شود.
 */

const RTL_CHAR = /[֐-ࣿיִ-﷿ﹰ-﻿]/;
const LTR_CHAR = /[A-Za-zÀ-ɏ]/;

export function directionOf(text) {
    for (const char of text) {
        if (RTL_CHAR.test(char)) {
            return 'rtl';
        }
        if (LTR_CHAR.test(char)) {
            return 'ltr';
        }
    }

    return text.trim() === '' ? 'rtl' : 'ltr';
}

function apply(field) {
    field.dir = directionOf(field.value ?? '');
}

export function initAutoDir(root = document) {
    root.querySelectorAll('[data-auto-dir]').forEach(apply);
}

export function registerAutoDir() {
    document.addEventListener('input', (event) => {
        if (event.target instanceof HTMLElement && event.target.hasAttribute('data-auto-dir')) {
            apply(event.target);
        }
    });

    document.addEventListener('ajax-content:loaded', (event) => initAutoDir(event.target ?? document));
}
