@extends('layouts.app')

@section('title', __('ui.kit.title'))

@section('content')
    <div class="space-y-6">
        <x-common.page-header :title="__('ui.kit.title')" :desc="__('ui.kit.desc')"
                              :breadcrumbs="[['label' => __('ui.kit.title')]]" />

        {{-- رنگ‌ها --}}
        <x-common.component-card title="رنگ‌ها" desc="برند آبی، تاکید کهربایی و رنگ‌های معنایی جدا از رنگ برند">
            <div class="space-y-5">
                @foreach (['brand' => 'برند', 'gray' => 'خاکستری با ته‌رنگ آبی سرد'] as $ramp => $rampLabel)
                    <div>
                        <p class="mb-2 text-theme-xs font-medium text-gray-500">{{ $rampLabel }}</p>
                        <div class="grid grid-cols-6 gap-2 sm:grid-cols-12">
                            @foreach ([25, 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950] as $shade)
                                <div class="text-center">
                                    <div class="h-12 rounded-md border border-gray-200 dark:border-gray-800 bg-{{ $ramp }}-{{ $shade }}"></div>
                                    <span class="num mt-1 block text-[11px] text-gray-500">{{ $shade }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="grid grid-cols-3 gap-2 sm:grid-cols-6">
                    @foreach (['accent-500' => 'bg-accent-500', 'success-500' => 'bg-success-500', 'error-500' => 'bg-error-500', 'warning-500' => 'bg-warning-500', 'info-500' => 'bg-info-500', 'shell / canvas' => 'bg-shell border border-shell-line ring-8 ring-inset ring-canvas'] as $colorName => $class)
                        <div class="text-center">
                            <div class="h-12 rounded-md {{ $class }}"></div>
                            <span class="mt-1 block text-[11px] text-gray-500">{{ $colorName }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-common.component-card>

        {{-- رنگ‌های وضعیت --}}
        <x-common.component-card title="رنگ‌های وضعیت" desc="۱۵ رنگ جدا برای وضعیت‌ها؛ در یک ماشین حالت دو وضعیت هم‌رنگ نشوند. نام رنگ همان خروجی color() در Enum است.">
            @php
                $statusColors = ['gray' => 'پیش‌نویس', 'brand' => 'ثبت‌شده', 'cyan' => 'در انتظار بررسی', 'indigo' => 'ارجاع شده', 'purple' => 'در حال طراحی', 'teal' => 'در حال انجام', 'lime' => 'آماده تحویل', 'success' => 'تکمیل‌شده', 'warning' => 'در انتظار پرداخت', 'orange' => 'تسویه آتی', 'accent' => 'ویژه', 'pink' => 'نیازمند مدارک', 'brown' => 'معلق', 'info' => 'اطلاع‌رسانی', 'error' => 'لغو / رد'];
            @endphp
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach ($statusColors as $color => $statusLabel)
                        <x-table.status-badge :type="$color">{{ $statusLabel }}</x-table.status-badge>
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($statusColors as $color => $statusLabel)
                        <x-ui.badge :color="$color" variant="solid" size="sm">{{ $color }}</x-ui.badge>
                    @endforeach
                </div>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400">نام‌های وضعیت بالا فقط نمونه‌اند؛ نگاشت واقعی در Enum هر وضعیت تعیین می‌شود.</p>
            </div>
        </x-common.component-card>

        {{-- نقشه --}}
        <div class="space-y-2">
            <div>
                <h2 class="text-theme-sm font-semibold text-gray-800 dark:text-gray-100">نقشه</h2>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400">در موبایل: نقشه بدون قاب با کنترل‌های شناور و حالت تمام‌صفحه. از md به بالا: کارت با سربرگ.</p>
            </div>
            <div class="grid gap-6 lg:grid-cols-2">
                <x-form.map name="k_location" label="انتخاب موقعیت" required />
                <x-form.map name="k_fixed" label="موقعیت ثابت (فقط نمایش)" lat="36.3326" lng="59.5452" :zoom="15" readonly />
            </div>
        </div>

        {{-- دکمه‌ها --}}
        <x-common.component-card title="دکمه‌ها" desc="واریانت، اندازه، آیکون و حالت‌ها">
            <div class="space-y-5">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (['primary', 'secondary', 'outline', 'ghost', 'danger', 'success', 'warning', 'accent'] as $variant)
                        <x-ui.button :variant="$variant">{{ $variant }}</x-ui.button>
                    @endforeach
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (['xs', 'sm', 'md', 'lg'] as $size)
                        <x-ui.button :size="$size" icon="plus">{{ $size }}</x-ui.button>
                    @endforeach
                    <x-ui.button icon="arrow-start" icon-position="end" variant="outline">آیکون انتها</x-ui.button>
                    <x-ui.button icon="trash" icon-only variant="danger" aria-label="حذف" />
                    <x-ui.button icon="edit" icon-only variant="ghost" aria-label="ویرایش" />
                    <x-ui.button loading>در حال ارسال</x-ui.button>
                    <x-ui.button disabled>غیرفعال</x-ui.button>
                </div>
            </div>
        </x-common.component-card>

        {{-- بج و هشدار --}}
        <div class="grid gap-6 lg:grid-cols-2">
            <x-common.component-card title="بج‌ها">
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach (['brand', 'success', 'error', 'warning', 'info', 'accent', 'gray'] as $color)
                            <x-ui.badge :color="$color" dot>{{ $color }}</x-ui.badge>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['brand', 'success', 'error', 'warning', 'info', 'accent', 'gray'] as $color)
                            <x-ui.badge :color="$color" variant="solid" size="sm">{{ $color }}</x-ui.badge>
                        @endforeach
                    </div>
                </div>
            </x-common.component-card>

            <x-common.component-card title="هشدارها">
                <div class="space-y-3">
                    <x-ui.alert variant="success" title="ذخیره شد">تغییرات با موفقیت ثبت شد.</x-ui.alert>
                    <x-ui.alert variant="error" title="خطا">ثبت اطلاعات انجام نشد.</x-ui.alert>
                    <x-ui.alert variant="warning" dismissible>مهلت این مرحله رو به پایان است.</x-ui.alert>
                    <x-ui.alert variant="info">پیام اطلاع‌رسانی ساده.</x-ui.alert>
                </div>
            </x-common.component-card>
        </div>

        {{-- فرم‌ها --}}
        <x-common.component-card title="فرم‌ها" desc="خطای هر فیلد زیر خودش نمایش داده می‌شود">
            <form class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" onsubmit="return false">
                <x-form.input name="k_name" label="نام" placeholder="مثلاً علی رضایی" required />
                <x-form.input name="k_mobile" label="موبایل" type="tel" hint="با ۰۹ شروع شود" icon="phone" />
                <x-form.input name="k_mobile2" label="موبایل با آیکون راست" type="tel" icon="phone" icon-position="right" placeholder="09121234567" />
                <x-form.input name="k_search" label="جستجو با آیکون" icon="search" placeholder="فارسی یا English تایپ کنید" />
                <x-form.ip name="k_ip" label="آدرس IP" value="192.168.10.1" :mask="24" hint="ماسک بعد از «/» قابل تغییر است" />
                <x-form.input name="k_error" label="فیلد دارای خطا" value="abc" error="مقدار واردشده معتبر نیست." />
                <x-form.input name="k_disabled" label="غیرفعال" value="فقط خواندنی" disabled />
                <x-form.input name="k_pass" label="رمز عبور" type="password" />
                <x-form.select name="k_select" label="دسته" :options="__('ui.sample.categories')" placeholder="انتخاب کنید" />
                <x-form.combobox name="k_combo" label="جستجو در گزینه‌ها (بدون select2)"
                                 :options="['t' => 'تهران', 'm' => 'مشهد', 'i' => 'اصفهان', 's' => 'شیراز', 'z' => 'تبریز', 'a' => 'اهواز']" />
                <x-form.money name="k_money" label="مبلغ" value="12500000" hint="نمایش فارسی، ارسال لاتین به ریال" />
                <x-form.file-upload name="k_file" label="فایل‌ها" multiple />
                <div class="md:col-span-2 xl:col-span-3">
                    <x-form.textarea name="k_note" label="توضیحات" placeholder="توضیح تکمیلی..." />
                </div>
                <div class="space-y-3">
                    <x-form.checkbox name="k_c1" label="گزینه انتخاب‌شده" checked />
                    <x-form.checkbox name="k_c2" label="گزینه با توضیح" hint="توضیح کوتاه زیر گزینه" />
                    <x-form.checkbox name="k_c3" label="غیرفعال" disabled />
                </div>
                <div class="space-y-3">
                    <x-form.radio name="k_r" value="a" label="گزینه اول" checked />
                    <x-form.radio name="k_r" value="b" label="گزینه دوم" />
                    <x-form.radio name="k_r" value="c" label="غیرفعال" disabled />
                </div>
                <div class="space-y-3">
                    <x-form.switch name="k_s1" label="فعال" checked />
                    <x-form.switch name="k_s2" label="اعلان پیامکی" />
                </div>
            </form>
        </x-common.component-card>

        {{-- نمودارها --}}
        <div class="grid gap-6 lg:grid-cols-3">
            <x-common.component-card class="lg:col-span-2" :title="__('ui.sample.chart_trend_title')" :desc="__('ui.sample.chart_trend_desc')">
                <div dir="ltr" data-chart="{{ json_encode([
                    'chart' => ['type' => 'area', 'height' => 290],
                    'series' => [['name' => __('ui.sample.chart_series'), 'data' => [18, 26, 22, 34, 29, 41, 37]]],
                    'xaxis' => ['categories' => ['1', '2', '3', '4', '5', '6', '7'], 'axisBorder' => ['show' => false], 'axisTicks' => ['show' => false]],
                    'colors' => ['#034ea2'],
                    'stroke' => ['curve' => 'smooth', 'width' => 2.5],
                    'fill' => ['type' => 'gradient', 'gradient' => ['opacityFrom' => 0.32, 'opacityTo' => 0.02]],
                    'dataLabels' => ['enabled' => false],
                    'yaxis' => ['opposite' => true],
                    'legend' => ['show' => false],
                ]) }}"></div>
            </x-common.component-card>

            <x-common.component-card :title="__('ui.sample.chart_share_title')" :desc="__('ui.sample.chart_share_desc')">
                <div dir="ltr" data-chart="{{ json_encode([
                    'chart' => ['type' => 'donut', 'height' => 290],
                    'series' => [44, 31, 15, 10],
                    'labels' => array_values(__('ui.sample.categories')),
                    'colors' => ['#034ea2', '#f59e0b', '#7a5af8', '#8d9caf'],
                    'legend' => ['position' => 'bottom'],
                    'stroke' => ['width' => 0],
                    'dataLabels' => ['enabled' => false],
                    'plotOptions' => ['pie' => ['donut' => ['size' => '68%']]],
                ]) }}"></div>
            </x-common.component-card>
        </div>

        {{-- جدول AJAX: فیلتر، مرتب‌سازی و صفحه‌بندی؛ در موبایل هر ردیف کارت است --}}
        <div class="space-y-3">
            <div>
                <h2 class="text-theme-sm font-semibold text-gray-800 dark:text-gray-100">{{ __('ui.sample.table_title') }}</h2>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400">{{ __('ui.sample.table_desc') }}</p>
            </div>

            <x-table.filter-bar :route="route('preview.table')" :reset-url="route('preview.kit')">
                <x-form.input name="search" type="search" :label="__('ui.sample.search')"
                              :placeholder="__('ui.sample.search_placeholder')" icon="search" :value="request('search')" />
                <x-form.select name="status" :label="__('ui.sample.status')" :options="$statusOptions"
                               :placeholder="__('ui.labels.all')" :value="request('status')" />
                <x-form.select name="category" :label="__('ui.sample.category')" :options="$categoryOptions"
                               :placeholder="__('ui.labels.all')" :value="request('category')" />
            </x-table.filter-bar>

            <div id="tableData">
                @include('pages.preview.partials.sample-table')
            </div>
        </div>


        {{-- آمار و تب‌ها --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-domain.stat-card label="سفارش جدید" value="۳۷" icon="clipboard" :trend="12" hint="نسبت به دیروز" />
            <x-domain.stat-card label="فاکتور در انتظار" value="۱۸" icon="receipt" tone="warning" :trend="-6" />
            <x-domain.stat-card label="پرداخت تایید‌شده" value="۹" icon="wallet" tone="success" href="#" />
            <x-domain.stat-card label="پیام خوانده‌نشده" value="۴۱" icon="lifebuoy" tone="error" />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-common.component-card title="تب‌ها">
                <x-ui.tabs :tabs="['a' => 'مشخصات', 'b' => 'اسناد', 'c' => 'تاریخچه']">
                    <p x-show="tab === 'a'" class="text-theme-sm text-gray-600 dark:text-gray-300">محتوای تب مشخصات.</p>
                    <p x-show="tab === 'b'" x-cloak class="text-theme-sm text-gray-600 dark:text-gray-300">محتوای تب اسناد.</p>
                    <p x-show="tab === 'c'" x-cloak class="text-theme-sm text-gray-600 dark:text-gray-300">محتوای تب تاریخچه.</p>
                </x-ui.tabs>
            </x-common.component-card>

            <x-common.component-card title="کارت جمع‌شونده با خلاصه" collapsible>
                <x-slot:summary>
                    <div class="flex items-center gap-3 text-theme-sm">
                        <x-ui.badge color="success" dot>فعال</x-ui.badge>
                        <span class="text-gray-500">موجودی:</span>
                        <span class="num font-semibold">۱۲٬۵۰۰٬۰۰۰ ریال</span>
                    </div>
                </x-slot:summary>
                <p class="text-theme-sm text-gray-600 dark:text-gray-300">جزئیات کامل اینجا نمایش داده می‌شود؛ خلاصه همیشه دیده می‌شود.</p>
            </x-common.component-card>
        </div>

        {{-- مودال، اعلان و تولتیپ --}}
        <x-common.component-card title="مودال، اعلان و تولتیپ">
            <div class="flex flex-wrap items-center gap-2">
                <x-ui.button variant="outline" x-on:click="$dispatch('open-modal', 'kit-modal')">باز کردن مودال</x-ui.button>
                <x-ui.button variant="outline" x-on:click="showToast('عملیات با موفقیت انجام شد', 'success')">توست موفق</x-ui.button>
                <x-ui.button variant="outline" x-on:click="showToast('خطایی رخ داد', 'error')">توست خطا</x-ui.button>
                <x-ui.button variant="danger" x-on:click="showConfirm('این مورد حذف شود؟')">دیالوگ تایید</x-ui.button>
                <x-ui.tooltip text="نمونه راهنما">
                    <x-ui.button variant="ghost" icon="info" icon-only aria-label="راهنما" />
                </x-ui.tooltip>
            </div>
        </x-common.component-card>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-common.component-card title="تایم‌لاین">
                <x-domain.timeline>
                    <x-domain.timeline-item title="ثبت سفارش" time="۰۸:۴۰" tone="gray">توسط کاربر</x-domain.timeline-item>
                    <x-domain.timeline-item title="صدور فاکتور" time="۰۹:۱۵" tone="brand">شماره P-1042</x-domain.timeline-item>
                    <x-domain.timeline-item title="تایید پرداخت" time="۱۰:۲۴" tone="success" />
                </x-domain.timeline>
            </x-common.component-card>

            <x-common.component-card title="حالت خالی">
                <x-ui.empty-state title="هنوز موردی ثبت نشده" text="با دکمه زیر اولین مورد را ایجاد کنید." compact>
                    <x-slot:action>
                        <x-ui.button size="sm" icon="plus">ایجاد</x-ui.button>
                    </x-slot:action>
                </x-ui.empty-state>
            </x-common.component-card>
        </div>
    </div>

    <x-ui.modal name="kit-modal" title="نمونه مودال" size="md">
        <p class="text-theme-sm text-gray-600 dark:text-gray-300">محتوای مودال. با Esc یا کلیک بیرون بسته می‌شود.</p>
        <x-slot:footer>
            <x-ui.button variant="outline" x-on:click="$dispatch('close-modal', 'kit-modal')">{{ __('ui.actions.cancel') }}</x-ui.button>
            <x-ui.button x-on:click="$dispatch('close-modal', 'kit-modal')">{{ __('ui.actions.confirm') }}</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
@endsection
