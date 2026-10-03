import tippy from 'tippy.js';

export function initTooltips(root = document) {
    root.querySelectorAll('[data-tooltip]').forEach((el) => {
        if (el._tippy) {
            return;
        }

        tippy(el, {
            content: el.dataset.tooltip,
            theme: 'rayan',
            placement: el.dataset.tooltipPlacement || 'top',
            arrow: true,
            delay: [150, 0],
        });
    });
}

document.addEventListener('ajax-content:loaded', (event) => {
    initTooltips(event.target instanceof Element ? event.target : document);
});
