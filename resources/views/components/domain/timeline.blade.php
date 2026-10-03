{{-- تایم‌لاین عمودی. آیتم‌ها: <x-domain.timeline-item> --}}
<ol {{ $attributes->class('relative space-y-5 ps-6 before:absolute before:inset-y-1 before:start-[7px] before:w-px before:bg-gray-200 dark:before:bg-gray-800') }}>
    {{ $slot }}
</ol>
