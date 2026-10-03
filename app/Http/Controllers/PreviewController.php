<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * صفحه‌های پیش‌نمایش رابط کاربری با داده نمونه (فقط محیط local و UI_PREVIEW=true).
 * منطق کسب‌وکار ندارد و با ماژول‌های واقعی جایگزین می‌شود.
 */
class PreviewController extends Controller
{
    private const SAMPLE_STATUS_COLORS = [
        'active' => 'success',
        'pending' => 'warning',
        'suspended' => 'error',
        'closed' => 'gray',
    ];

    public function kit(Request $request): View
    {
        return view('pages.preview.kit', $this->sampleTableData($request));
    }

    public function sampleTable(Request $request): View
    {
        return view('pages.preview.partials.sample-table', $this->sampleTableData($request));
    }

    /**
     * @return array<string, mixed>
     */
    private function sampleTableData(Request $request): array
    {
        $filters = $request->only(['search', 'status', 'category', 'sort_by', 'sort_order']);
        $sortBy = in_array($filters['sort_by'] ?? null, ['code', 'name', 'amount', 'created_at'], true) ? $filters['sort_by'] : 'code';
        $descending = ($filters['sort_order'] ?? 'asc') === 'desc';

        $rows = $this->sampleRecords()
            ->when($filters['search'] ?? null, fn (Collection $c, string $q) => $c->filter(
                fn (array $row) => str_contains($row['name'].$row['mobile'].$row['code'], $q),
            ))
            ->when($filters['status'] ?? null, fn (Collection $c, string $status) => $c->where('status', $status))
            ->when($filters['category'] ?? null, fn (Collection $c, string $category) => $c->where('category', $category))
            ->sortBy($sortBy, SORT_REGULAR, $descending)
            ->values();

        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $records = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => route('preview.kit'), 'query' => $request->query()],
        );

        return [
            'records' => $records,
            'sampleStatusColors' => self::SAMPLE_STATUS_COLORS,
            'statusOptions' => __('ui.sample.statuses'),
            'categoryOptions' => __('ui.sample.categories'),
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function sampleRecords(): Collection
    {
        $names = ['علی رضایی', 'مریم احمدی', 'حسین کریمی', 'زهرا موسوی', 'رضا نجفی', 'سارا حسینی', 'محمد قاسمی', 'فاطمه صادقی',
            'امیر جعفری', 'نگار رحیمی', 'مهدی اکبری', 'الهام باقری', 'پویا شریفی', 'شرکت آسمان شرق', 'شرکت پارس نت', 'کسب‌وکار سبز'];
        $categories = array_keys(__('ui.sample.categories'));
        $statuses = array_keys(self::SAMPLE_STATUS_COLORS);

        return collect(range(1, 48))->map(fn (int $i) => [
            'code' => 'S-'.(1000 + $i),
            'name' => $names[$i % count($names)],
            'mobile' => '09'.(120000000 + $i * 7391217 % 800000000),
            'category' => $categories[$i % count($categories)],
            'status' => $statuses[($i * 3) % count($statuses)],
            'amount' => (($i * 37) % 19 - 6) * 1_250_000,
            'created_at' => now()->subDays($i * 3),
        ]);
    }
}
