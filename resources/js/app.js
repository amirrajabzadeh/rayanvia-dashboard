import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

import './lib/http';
import { registerStores } from './lib/stores';
import { registerNotify } from './lib/notify';
import { initTables, labelTableCells } from './lib/table';
import { initForms } from './lib/forms';
import { initTooltips } from './lib/tooltip';
import { initCharts } from './lib/charts';
import { registerComponents } from './lib/components';
import { registerMap } from './lib/map';
import { initAutoDir, registerAutoDir } from './lib/bidi';

window.Alpine = Alpine;

Alpine.plugin(collapse);
registerStores(Alpine);
registerComponents(Alpine);
registerMap(Alpine);
registerAutoDir();
registerNotify();

document.addEventListener('DOMContentLoaded', () => {
    initTables();
    labelTableCells();
    initForms();
    initTooltips();
    initCharts();
    initAutoDir();
    document.getElementById('page-loading')?.remove();
});

Alpine.start();
