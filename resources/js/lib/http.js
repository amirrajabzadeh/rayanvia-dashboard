import { t } from './i18n';
import axios from 'axios';

window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common.Accept = 'application/json, text/html';

const csrf = document.querySelector('meta[name="csrf-token"]');
if (csrf) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf.content;
}

/**
 * ۴۰۱ → صفحه ورود، ۴۱۹ → رفرش، ۴۰۳ → پیام دسترسی.
 * ۴۲۲ را به فراخواننده می‌سپاریم تا خطا را زیر فیلدها نشان بدهد.
 * درخواستی که خودش خطا را نشان می‌دهد با { silentErrors: true } توست سراسری را خاموش می‌کند.
 */
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.config?.silentErrors) {
            return Promise.reject(error);
        }

        const status = error.response?.status;
        const message = error.response?.data?.message;

        if (status === 401) {
            window.location.href = '/login';
        } else if (status === 419) {
            window.showError?.(t('session_expired'));
            setTimeout(() => window.location.reload(), 1200);
        } else if (status === 403) {
            window.showToast?.(message || t('forbidden'), 'error');
        } else if (status >= 500) {
            window.showToast?.(t('server_error'), 'error');
        }

        return Promise.reject(error);
    },
);
