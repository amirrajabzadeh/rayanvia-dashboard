/**
 * کامپوننت‌های Alpine که در Blade استفاده می‌شوند.
 * (تعریف در یک جا؛ نه پراکنده در ویوها)
 */

const faNumber = new Intl.NumberFormat('fa-IR');
const latinDigits = (value) =>
    String(value)
        .replace(/[۰-۹]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
        .replace(/[٠-٩]/g, (d) => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));

/** واژه‌های عدد از فایل زبان (lang/fa/ui.php → js.number_words) */
const numberWords = () => window.rayan?.lang?.number_words ?? null;

function threeDigitWords(n, w) {
    const parts = [];
    const rest = n % 100;
    if (n >= 100) {
        parts.push(w.hundreds[Math.floor(n / 100)]);
    }
    if (rest >= 10 && rest < 20) {
        parts.push(w.teens[rest - 10]);
    } else {
        if (rest >= 20) {
            parts.push(w.tens[Math.floor(rest / 10)]);
        }
        if (rest % 10) {
            parts.push(w.ones[rest % 10]);
        }
    }
    return parts.join(w.and);
}

/** عدد صحیح (رشته رقم لاتین) به حروف فارسی؛ برای بیش از ۱۵ رقم رشته خالی */
export function numberToWords(digits) {
    const w = numberWords();
    const clean = String(digits).replace(/^0+/, '');
    if (!w || clean === '') {
        return '';
    }
    if (clean.length > 15) {
        return '';
    }
    const groups = [];
    for (let end = clean.length; end > 0; end -= 3) {
        groups.unshift(Number(clean.slice(Math.max(0, end - 3), end)));
    }
    return groups
        .map((group, index) => (group ? threeDigitWords(group, w) + w.scales[groups.length - 1 - index] : ''))
        .filter(Boolean)
        .join(w.and);
}

export function registerComponents(Alpine) {
    /** منوی کشویی */
    Alpine.data('dropdown', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
    }));

    /** ورودی مبلغ: نمایش فارسی با جداکننده، مقدار ارسالی لاتین و بدون جداکننده */
    Alpine.data('moneyInput', (initial = '') => ({
        raw: initial === null ? '' : String(initial),
        get display() {
            return this.raw === '' ? '' : faNumber.format(Number(this.raw));
        },
        get words() {
            const text = numberToWords(this.raw);
            return text ? `${text} ${window.rayan.lang.currency}` : '';
        },
        onInput(event) {
            this.raw = latinDigits(event.target.value).replace(/\D/g, '').replace(/^0+(?=\d)/, '');
            event.target.value = this.display;
        },
        /** فقط رقم (لاتین، فارسی، عربی) و کلیدهای ویرایش؛ چسباندن هم در onInput پاک‌سازی می‌شود */
        onKeydown(event) {
            if (event.ctrlKey || event.metaKey || event.altKey || event.key.length > 1) {
                return;
            }
            if (!/[0-9۰-۹٠-٩]/.test(event.key)) {
                event.preventDefault();
            }
        },
    }));

    /** آدرس IP با ماسک قابل تغییر: فقط رقم و نقطه؛ «/» به فیلد ماسک می‌رود؛ ماسک بین ۰ تا ۳۲ */
    const editingKey = (event) => event.ctrlKey || event.metaKey || event.altKey || event.key.length > 1;
    Alpine.data('ipInput', ({ mask = 24 } = {}) => ({
        mask: mask === null ? '' : String(mask),
        onIpKeydown(event) {
            if (editingKey(event)) {
                return;
            }
            if (event.key === '/' && this.$refs.mask) {
                event.preventDefault();
                this.$refs.mask.focus();
                this.$refs.mask.select();
                return;
            }
            if (!/[0-9۰-۹٠-٩.]/.test(event.key)) {
                event.preventDefault();
            }
        },
        onIpInput(event) {
            const [ip, pastedMask] = latinDigits(event.target.value).split('/');
            event.target.value = ip.replace(/[^0-9.]/g, '');
            if (pastedMask !== undefined && this.$refs.mask) {
                this.mask = pastedMask.replace(/\D/g, '').slice(0, 2);
                this.clampMask();
            }
        },
        onMaskKeydown(event) {
            if (event.key === 'Backspace' && this.mask === '') {
                this.$refs.ip.focus();
                return;
            }
            if (!editingKey(event) && !/[0-9۰-۹٠-٩]/.test(event.key)) {
                event.preventDefault();
            }
        },
        onMaskInput(event) {
            this.mask = latinDigits(event.target.value).replace(/\D/g, '').slice(0, 2);
        },
        clampMask() {
            if (this.mask !== '') {
                this.mask = String(Math.min(32, Number(this.mask)));
            }
        },
    }));

    /** انتخابگر ساده بدون جستجو (ظاهر select2)؛ کلیدهای جهت‌دار، Home/End، Enter و Escape */
    Alpine.data('select', ({ items = [], value = '', allowEmpty = false } = {}) => ({
        open: false,
        active: -1,
        items,
        value,
        get selectedLabel() {
            return this.items.find((item) => item.value === this.value)?.label ?? null;
        },
        toggle() {
            this.open ? this.close() : this.show();
        },
        show() {
            this.open = true;
            const current = this.items.findIndex((item) => item.value === this.value);
            this.active = current === -1 && !allowEmpty ? 0 : current;
            this.$nextTick(() => this.scrollToActive());
        },
        close() {
            this.open = false;
        },
        choose(next) {
            const changed = next !== this.value;
            this.value = next;
            this.close();
            if (changed) {
                // فرم‌های فیلتر AJAX روی change گوش می‌دهند
                this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('change', { bubbles: true })));
            }
        },
        move(step) {
            if (!this.items.length) {
                return;
            }
            const min = allowEmpty ? -1 : 0;
            const span = this.items.length - min;
            this.active = min + ((this.active - min + step + span) % span);
            this.scrollToActive();
        },
        scrollToActive() {
            this.$el.querySelector(`[data-index="${this.active}"]`)?.scrollIntoView({ block: 'nearest' });
        },
        onKeydown(event) {
            switch (event.key) {
                case 'ArrowDown':
                case 'ArrowUp':
                    event.preventDefault();
                    this.open ? this.move(event.key === 'ArrowDown' ? 1 : -1) : this.show();
                    break;
                case 'Home':
                case 'End':
                    if (this.open) {
                        event.preventDefault();
                        this.active = event.key === 'Home' ? 0 : this.items.length - 1;
                        this.scrollToActive();
                    }
                    break;
                case 'Enter':
                case ' ':
                    event.preventDefault();
                    if (this.open && this.active >= (allowEmpty ? -1 : 0)) {
                        this.choose(this.items[this.active]?.value ?? '');
                    } else {
                        this.show();
                    }
                    break;
                case 'Tab':
                    this.close();
                    break;
            }
        },
    }));

    /** انتخابگر جستجوپذیر (جایگزین select2) */
    Alpine.data('combobox', ({ options = {}, value = '', placeholder = '' } = {}) => ({
        open: false,
        query: '',
        value,
        options,
        placeholder,
        get label() {
            return this.options[this.value] ?? '';
        },
        get filtered() {
            const q = this.query.trim();
            return Object.entries(this.options).filter(([, text]) => !q || text.includes(q));
        },
        choose(key) {
            this.value = key;
            this.query = '';
            this.open = false;
        },
        clear() {
            this.value = '';
            this.query = '';
        },
    }));

    /** آپلود فایل با نمایش نام و حجم و امکان حذف فایل انتخاب‌شده */
    Alpine.data('fileUpload', ({ multiple = false } = {}) => ({
        files: [],
        dragging: false,
        sizeLabel(file) {
            const kb = file.size / 1024;
            return kb < 1024 ? `${faNumber.format(Math.max(1, Math.round(kb)))} KB` : `${faNumber.format(Math.round(kb / 102.4) / 10)} MB`;
        },
        pick(fileList) {
            const picked = [...fileList];
            this.files = multiple ? [...this.files, ...picked] : picked.slice(0, 1);
            this.sync();
        },
        drop(event) {
            this.dragging = false;
            this.pick(event.dataTransfer.files);
        },
        remove(index) {
            this.files.splice(index, 1);
            this.sync();
        },
        clear() {
            this.files = [];
            this.sync();
        },
        /** فایل‌های input واقعی را با فهرست هماهنگ می‌کند تا همان‌ها ارسال شوند */
        sync() {
            const transfer = new DataTransfer();
            this.files.forEach((file) => transfer.items.add(file));
            this.$refs.input.files = transfer.files;
        },
    }));
}
