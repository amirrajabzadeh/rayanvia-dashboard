@props([
    'mono' => false, // شناسه (کد، موبایل، سریال): فونت مونو و چپ‌به‌راست
    'number' => false, // عدد یا مبلغ نمایشی
    'muted' => false,
    'nowrap' => false,
    'label' => null,    // برچسب در حالت کارت؛ خالی = از عنوان ستون
    'primary' => false, // در حالت کارت عنوان کارت می‌شود
    'actions' => false, // در حالت کارت پایین کارت و بدون برچسب
])

<td @if ($label) data-label="{{ $label }}" @endif @if ($primary) data-primary @endif @if ($actions) data-actions @endif
    {{ $attributes->class([
    'px-5 py-2.5 align-middle',
    'text-gray-500 dark:text-gray-400' => $muted,
    'text-gray-800 dark:text-gray-200' => ! $muted,
    'whitespace-nowrap' => $nowrap || $mono || $number,
]) }}>
    @if ($mono)
        <span class="ident">{{ $slot }}</span>
    @elseif ($number)
        <span class="num">{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</td>
