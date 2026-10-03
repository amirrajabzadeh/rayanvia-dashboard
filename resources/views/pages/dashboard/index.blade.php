@extends('layouts.app')

@section('title', __('ui.dashboard.title'))

@section('content')
    <div class="space-y-6">
        {{-- بنر اصلی: عدد مهم ماه و روند آن --}}
        <section class="relative overflow-hidden rounded-2xl bg-ink text-ink-text">
            <div class="pointer-events-none absolute -top-24 start-[-5rem] size-72 rounded-full bg-brand-500/40 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 end-10 size-64 rounded-full bg-accent-500/15 blur-3xl" aria-hidden="true"></div>

            <div class="relative grid gap-6 p-6 sm:p-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.2fr)] lg:items-end">
                <div>
                    <p class="text-theme-sm text-ink-muted">{{ __('ui.dashboard.greeting', ['name' => auth()->user()?->name ?? __('ui.labels.user_name')]) }}</p>
                    <p class="mt-0.5 text-theme-xs text-ink-muted">{{ $today }}</p>

                    <p class="mt-6 text-theme-sm text-ink-text">{{ __('ui.dashboard.revenue_title') }}</p>
                    <div class="mt-1 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                        <span class="num text-[40px] leading-[48px] font-bold tracking-tight text-white sm:text-[48px] sm:leading-[56px]">{{ $revenue['value'] }}</span>
                        <span class="text-theme-sm text-ink-muted">{{ __('ui.labels.currency') }}</span>
                    </div>
                    <span class="mt-3 inline-flex items-center gap-1 rounded-full bg-success-500/15 px-2.5 py-1 text-theme-xs font-medium text-success-400">
                        <x-ui.icon name="trending-up" :size="3.5" />
                        <span class="num">{{ \App\Support\Fa::digits($revenue['trend']) }}٪</span>
                        <span class="text-ink-text">{{ __('ui.dashboard.vs_last_month') }}</span>
                    </span>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <x-ui.button variant="accent" icon="plus">{{ __('ui.dashboard.new_order') }}</x-ui.button>
                        <x-ui.button variant="outline" icon="download" class="!border-white/20 !bg-white/5 !text-white hover:!bg-white/10">{{ __('ui.dashboard.download_report') }}</x-ui.button>
                    </div>
                </div>

                <div dir="ltr" class="-mx-2 -mb-2 h-[170px]" data-chart="{{ json_encode([
                    'chart' => ['type' => 'area', 'height' => 170, 'sparkline' => ['enabled' => true]],
                    'series' => [['name' => __('ui.dashboard.revenue_title'), 'data' => $revenue['spark']]],
                    'colors' => ['#fbbf24'],
                    'stroke' => ['curve' => 'smooth', 'width' => 2.5],
                    'fill' => ['type' => 'gradient', 'gradient' => ['opacityFrom' => 0.35, 'opacityTo' => 0]],
                    'tooltip' => ['theme' => 'dark'],
                ]) }}"></div>
            </div>
        </section>

        {{-- شاخص‌های کلیدی --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $kpi)
                <x-domain.kpi-card :label="$kpi['label']" :value="$kpi['value']" :trend="$kpi['trend']" :icon="$kpi['icon']"
                                   :color="$kpi['color']" :spark="$kpi['spark']" :hint="__('ui.dashboard.vs_last_month')" />
            @endforeach
        </div>

        {{-- درآمد و هدف --}}
        <div class="grid gap-6 xl:grid-cols-3">
            <x-common.component-card class="xl:col-span-2" :title="__('ui.dashboard.income_title')" :desc="__('ui.dashboard.income_desc')">
                <div dir="ltr" data-chart="{{ json_encode([
                    'chart' => ['type' => 'line', 'height' => 320],
                    'series' => [
                        ['name' => __('ui.dashboard.income'), 'type' => 'column', 'data' => $revenueChart['income']],
                        ['name' => __('ui.dashboard.target'), 'type' => 'line', 'data' => $revenueChart['target']],
                    ],
                    'colors' => ['#034ea2', '#f59e0b'],
                    'stroke' => ['width' => [0, 3], 'curve' => 'smooth'],
                    'plotOptions' => ['bar' => ['columnWidth' => '42%', 'borderRadius' => 5, 'borderRadiusApplication' => 'end']],
                    'xaxis' => ['categories' => $revenueChart['months'], 'axisBorder' => ['show' => false], 'axisTicks' => ['show' => false]],
                    'yaxis' => ['opposite' => true],
                    'dataLabels' => ['enabled' => false],
                    'legend' => ['position' => 'top', 'horizontalAlign' => 'right'],
                ]) }}"></div>
            </x-common.component-card>

            <x-common.component-card :title="__('ui.dashboard.goal_title')" :desc="__('ui.dashboard.goal_desc')">
                <div dir="ltr" data-chart="{{ json_encode([
                    'chart' => ['type' => 'radialBar', 'height' => 270],
                    'series' => [$goal],
                    'labels' => [__('ui.dashboard.goal_label')],
                    'colors' => ['#f59e0b'],
                    'plotOptions' => ['radialBar' => [
                        'startAngle' => -120, 'endAngle' => 120,
                        'hollow' => ['size' => '66%'],
                        'track' => ['background' => 'rgba(141,156,175,.18)'],
                        'dataLabels' => ['name' => ['offsetY' => 24, 'fontSize' => '13px'], 'value' => ['offsetY' => -16, 'fontSize' => '34px', 'fontWeight' => 700]],
                    ]],
                    'stroke' => ['lineCap' => 'round'],
                ]) }}"></div>
                <p class="mt-1 text-center text-theme-sm text-gray-500 dark:text-gray-400">{{ __('ui.dashboard.goal_text') }}</p>
            </x-common.component-card>
        </div>

        {{-- محصولات، منطقه‌ها، کانال‌ها --}}
        <div class="grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
            <x-common.component-card :title="__('ui.dashboard.products_title')" :desc="__('ui.dashboard.products_desc')">
                <ul class="space-y-4">
                    @foreach ($products as $product)
                        <li>
                            <div class="mb-1.5 flex items-center justify-between text-theme-sm">
                                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $product['name'] }}</span>
                                <span class="num text-gray-500 dark:text-gray-400">{{ $product['sales'] }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                                <div class="h-full rounded-full {{ $product['color'] }}" style="width: {{ $product['share'] }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-common.component-card>

            <x-common.component-card :title="__('ui.dashboard.regions_title')" :desc="__('ui.dashboard.regions_desc')">
                <ul class="divide-y divide-gray-100 dark:divide-white/10">
                    @foreach ($regions as $region)
                        <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="num flex size-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-theme-xs font-semibold text-brand-600 dark:bg-brand-500/15 dark:text-brand-300">{{ \App\Support\Fa::digits($region['share']) }}٪</span>
                                <span class="truncate text-theme-sm font-medium text-gray-700 dark:text-gray-200">{{ $region['name'] }}</span>
                            </div>
                            <span class="num shrink-0 text-theme-sm text-gray-500 dark:text-gray-400">{{ $region['value'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-common.component-card>

            <x-common.component-card class="lg:col-span-2 xl:col-span-1" :title="__('ui.dashboard.channels_title')" :desc="__('ui.dashboard.channels_desc')">
                <div dir="ltr" data-chart="{{ json_encode([
                    'chart' => ['type' => 'donut', 'height' => 280],
                    'series' => $channels['series'],
                    'labels' => $channels['labels'],
                    'colors' => ['#034ea2', '#f59e0b', '#7a5af8', '#8d9caf'],
                    'legend' => ['position' => 'bottom'],
                    'stroke' => ['width' => 0],
                    'dataLabels' => ['enabled' => false],
                    'plotOptions' => ['pie' => ['donut' => ['size' => '68%']]],
                ]) }}"></div>
            </x-common.component-card>
        </div>

        {{-- سفارش‌ها و فعالیت‌ها --}}
        <div class="grid gap-6 xl:grid-cols-3">
            <div class="min-w-0 xl:col-span-2">
                <x-common.component-card :title="__('ui.dashboard.orders_title')" class="overflow-hidden">
                    <x-slot:headerActions>
                        <x-ui.button href="#" variant="ghost" size="sm" icon="arrow-start" icon-position="end">{{ __('ui.dashboard.all_orders') }}</x-ui.button>
                    </x-slot:headerActions>

                    <div class="px-5 pb-5">
                        <x-table.table>
                            <x-slot:header>
                                <x-table.th :label="__('ui.dashboard.columns.code')" />
                                <x-table.th :label="__('ui.dashboard.columns.customer')" />
                                <x-table.th :label="__('ui.dashboard.columns.product')" />
                                <x-table.th :label="__('ui.dashboard.columns.amount')" />
                                <x-table.th :label="__('ui.dashboard.columns.status')" />
                                <x-table.th :label="__('ui.dashboard.columns.date')" />
                            </x-slot:header>

                            @foreach ($orders as $order)
                                <x-table.row>
                                    <x-table.cell mono>{{ $order['code'] }}</x-table.cell>
                                    <x-table.cell primary>
                                        <div class="flex items-center gap-2.5">
                                            <x-ui.avatar :name="$order['customer']" size="sm" />
                                            <span class="font-medium">{{ $order['customer'] }}</span>
                                        </div>
                                    </x-table.cell>
                                    <x-table.cell muted>{{ $order['product'] }}</x-table.cell>
                                    <x-table.cell number>{{ $order['amount'] }}</x-table.cell>
                                    <x-table.cell><x-table.status-badge :type="$order['color']">{{ $order['status'] }}</x-table.status-badge></x-table.cell>
                                    <x-table.cell number muted>{{ $order['date'] }}</x-table.cell>
                                </x-table.row>
                            @endforeach
                        </x-table.table>
                    </div>
                </x-common.component-card>
            </div>

            <x-common.component-card :title="__('ui.dashboard.activity_title')">
                <x-domain.timeline>
                    @foreach ($activities as $activity)
                        <x-domain.timeline-item :title="$activity['title']" :time="$activity['time']" :tone="$activity['tone']">{{ $activity['text'] }}</x-domain.timeline-item>
                    @endforeach
                </x-domain.timeline>
            </x-common.component-card>
        </div>
    </div>
@endsection
