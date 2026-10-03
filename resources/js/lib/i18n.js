/** متن‌های JS از فایل زبان (lang/fa/ui.php → کلید js) و از طریق window.rayan.lang تزریق می‌شوند. */
export function t(key) {
    return window.rayan?.lang?.[key] ?? key;
}
