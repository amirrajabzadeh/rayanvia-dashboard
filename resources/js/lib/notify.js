import { t } from './i18n';
import rayan from 'rayan-alert';

/**
 * اعلان‌ها و دیالوگ‌ها با rayan-alert (جایگزین SweetAlert2).
 * نام و امضای توابع همان قبلی است تا کد صفحه‌ها تغییر نکند.
 */

const isDark = () => document.documentElement.classList.contains('dark');

const syncTheme = () => rayan.configure({ theme: isDark() ? 'dark' : 'light' });

let loadingDialog = null;

export function showToast(message, type = 'success') {
    const show = rayan.toast[type] ?? rayan.toast.info;

    return show(message);
}

export function showSuccess(message, title = t('success_title')) {
    return rayan.alert({ icon: 'success', title, text: message, confirmText: t('ok') });
}

export function showError(message, title = t('error_title')) {
    return rayan.alert({ icon: 'error', title, text: message, confirmText: t('ok') });
}

/** @returns {Promise<boolean>} */
export function showConfirm(text = t('confirm_text'), options = {}) {
    return rayan.confirm({
        title: options.title ?? t('confirm_title'),
        text,
        danger: options.danger ?? false,
        confirmText: options.confirmText ?? t('confirm_yes'),
        cancelText: options.cancelText ?? t('cancel'),
    });
}

export function showLoading(title = t('loading')) {
    loadingDialog?.close();
    loadingDialog = rayan.loading({ title });

    return loadingDialog;
}

export function closeDialog() {
    loadingDialog?.close();
    loadingDialog = null;
}

export function registerNotify() {
    rayan.configure({
        locale: 'fa',
        dir: 'auto',
        zIndex: 100000, // بالاتر از سایدبار و هدر
        tokens: { accent: '#034ea2', 'r-panel': '16px', 'r-ctl': '8px', 'r-toast': '12px' },
        toast: { position: 'top-end', duration: 3500 }, // سمت محتوا، نه روی سایدبار
    });
    syncTheme();
    window.addEventListener('theme-changed', syncTheme);

    Object.assign(window, { showToast, showSuccess, showError, showConfirm, showLoading, closeDialog });
}
