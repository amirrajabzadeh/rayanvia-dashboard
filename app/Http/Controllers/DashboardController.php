<?php

namespace App\Http\Controllers;

use App\Support\Fa;
use App\Support\Jalali;
use Illuminate\Contracts\View\View;

/**
 * داشبورد با داده ساختگی؛ با داده واقعی سرویس‌های هر ماژول جایگزین می‌شود.
 */
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $months = __('ui.dashboard.months');

        return view('pages.dashboard.index', [
            'today' => Jalali::long(now()),
            'revenue' => [
                'value' => Fa::number(8_450_000_000),
                'trend' => 12.4,
                'spark' => [31, 40, 36, 52, 49, 63, 58, 72, 69, 84, 91, 98],
            ],
            'kpis' => $this->kpis(),
            'revenueChart' => [
                'months' => $months,
                'income' => [420, 510, 480, 610, 590, 720, 680, 790, 760, 880, 845, 960],
                'target' => [450, 480, 520, 560, 600, 640, 680, 720, 760, 800, 840, 880],
            ],
            'channels' => ['labels' => array_values(__('ui.dashboard.channels')), 'series' => [38, 27, 21, 14]],
            'goal' => 78,
            'products' => $this->products(),
            'regions' => $this->regions(),
            'orders' => $this->orders(),
            'activities' => $this->activities(),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function kpis(): array
    {
        return [
            ['label' => __('ui.dashboard.kpi.orders'), 'value' => Fa::number(1284), 'trend' => 8.2, 'icon' => 'clipboard', 'color' => '#034ea2', 'spark' => [12, 18, 15, 22, 19, 27, 24, 31, 29, 36]],
            ['label' => __('ui.dashboard.kpi.users'), 'value' => Fa::number(342), 'trend' => 3.1, 'icon' => 'users', 'color' => '#7a5af8', 'spark' => [8, 9, 14, 11, 16, 15, 19, 18, 22, 24]],
            ['label' => __('ui.dashboard.kpi.conversion'), 'value' => Fa::digits('۳٫۸٪'), 'trend' => -1.4, 'icon' => 'trending-up', 'color' => '#f59e0b', 'spark' => [30, 28, 33, 29, 27, 31, 26, 28, 25, 24]],
            ['label' => __('ui.dashboard.kpi.invoices'), 'value' => Fa::number(56), 'trend' => -6.0, 'icon' => 'receipt', 'color' => '#12b76a', 'spark' => [20, 26, 22, 18, 24, 19, 17, 21, 15, 14]],
        ];
    }

    /**
     * @return list<array{name: string, sales: string, share: int, color: string}>
     */
    private function products(): array
    {
        return [
            ['name' => 'بسته پایه', 'sales' => Fa::number(412), 'share' => 86, 'color' => 'bg-brand-500'],
            ['name' => 'بسته حرفه‌ای', 'sales' => Fa::number(318), 'share' => 66, 'color' => 'bg-accent-500'],
            ['name' => 'بسته سازمانی', 'sales' => Fa::number(204), 'share' => 42, 'color' => 'bg-purple-500'],
            ['name' => 'افزونه گزارش', 'sales' => Fa::number(137), 'share' => 28, 'color' => 'bg-success-500'],
            ['name' => 'پشتیبانی ویژه', 'sales' => Fa::number(89), 'share' => 18, 'color' => 'bg-gray-400'],
        ];
    }

    /**
     * @return list<array{name: string, value: string, share: int}>
     */
    private function regions(): array
    {
        return [
            ['name' => 'تهران', 'value' => Fa::number(3_120_000_000), 'share' => 37],
            ['name' => 'خراسان رضوی', 'value' => Fa::number(1_840_000_000), 'share' => 22],
            ['name' => 'اصفهان', 'value' => Fa::number(1_210_000_000), 'share' => 14],
            ['name' => 'فارس', 'value' => Fa::number(930_000_000), 'share' => 11],
            ['name' => 'سایر استان‌ها', 'value' => Fa::number(1_350_000_000), 'share' => 16],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    private function orders(): array
    {
        $rows = [
            ['O-4821', 'علی رضایی', 'بسته حرفه‌ای', 18_500_000, 'success', 'paid'],
            ['O-4820', 'شرکت پارس نت', 'بسته سازمانی', 96_000_000, 'warning', 'pending'],
            ['O-4819', 'مریم احمدی', 'بسته پایه', 4_200_000, 'success', 'paid'],
            ['O-4818', 'حسین کریمی', 'افزونه گزارش', 7_800_000, 'info', 'processing'],
            ['O-4817', 'زهرا موسوی', 'بسته حرفه‌ای', 18_500_000, 'error', 'failed'],
            ['O-4816', 'شرکت آسمان شرق', 'بسته سازمانی', 96_000_000, 'success', 'paid'],
        ];

        return array_map(fn (array $row, int $i) => [
            'code' => $row[0],
            'customer' => $row[1],
            'product' => $row[2],
            'amount' => Fa::number($row[3]),
            'color' => $row[4],
            'status' => __('ui.dashboard.order_statuses.'.$row[5]),
            'date' => Fa::digits(Jalali::format(now()->subHours($i * 7))),
        ], $rows, array_keys($rows));
    }

    /**
     * @return list<array{title: string, text: string, time: string, tone: string}>
     */
    private function activities(): array
    {
        return [
            ['title' => 'پرداخت تایید شد', 'text' => 'سفارش O-4821، ۱۸٬۵۰۰٬۰۰۰ ریال', 'time' => '۱۰:۲۴', 'tone' => 'success'],
            ['title' => 'کاربر جدید ثبت‌نام کرد', 'text' => 'شرکت آسمان شرق', 'time' => '۰۹:۵۸', 'tone' => 'brand'],
            ['title' => 'فاکتور در انتظار پرداخت', 'text' => 'F-2290، سررسید فردا', 'time' => '۰۹:۱۵', 'tone' => 'warning'],
            ['title' => 'پرداخت ناموفق', 'text' => 'سفارش O-4817، خطای بانک', 'time' => '۰۸:۴۰', 'tone' => 'error'],
            ['title' => 'گزارش ماهانه ساخته شد', 'text' => 'آماده دانلود', 'time' => '۰۸:۰۰', 'tone' => 'gray'],
        ];
    }
}
