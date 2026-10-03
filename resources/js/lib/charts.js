/**
 * نمودارها با بارگذاری تنبل ApexCharts.
 * <div dir="ltr" data-chart="json ...گزینه‌های ApexCharts"></div>  (dir=ltr الزامی است؛ Apex در RTL جابه‌جا رندر می‌شود)
 * رنگ‌ها و تم از توکن‌های برند خوانده می‌شوند.
 */

const charts = new Set();

function isDark() {
    return document.documentElement.classList.contains('dark');
}

function themed(options) {
    return {
        ...options,
        theme: { mode: isDark() ? 'dark' : 'light' },
        chart: {
            fontFamily: 'Vazirmatn, sans-serif',
            width: '100%',
            toolbar: { show: false },
            background: 'transparent',
            ...options.chart,
        },
        grid: { borderColor: isDark() ? '#202b3a' : '#dae2eb', strokeDashArray: 4, ...options.grid },
    };
}

export async function initCharts() {
    const nodes = [...document.querySelectorAll('[data-chart]')];
    if (!nodes.length) {
        return;
    }

    const { default: ApexCharts } = await import('apexcharts');

    nodes.forEach((node) => {
        if (node.dataset.chartReady) {
            return;
        }

        const options = JSON.parse(node.dataset.chart);
        const chart = new ApexCharts(node, themed(options));
        chart.render();
        node.dataset.chartReady = 'true';
        charts.add({ chart, options });
    });

    window.addEventListener('theme-changed', () => {
        charts.forEach(({ chart, options }) => chart.updateOptions(themed(options)));
    });
}
