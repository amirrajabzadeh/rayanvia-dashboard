@php
    use App\Support\Fa;
    use App\Support\Jalali;
@endphp

<x-table.table :empty="$records->isEmpty()">
    <x-slot:header>
        <x-table.th :label="__('ui.sample.columns.code')" field="code" sortable />
        <x-table.th :label="__('ui.sample.columns.name')" field="name" sortable />
        <x-table.th :label="__('ui.sample.columns.mobile')" />
        <x-table.th :label="__('ui.sample.columns.category')" />
        <x-table.th :label="__('ui.sample.columns.status')" />
        <x-table.th :label="__('ui.sample.columns.amount')" field="amount" sortable />
        <x-table.th :label="__('ui.sample.columns.created_at')" field="created_at" sortable />
        <x-table.th class="text-end">{{ __('ui.table.row_actions') }}</x-table.th>
    </x-slot:header>

    @foreach ($records as $record)
        <x-table.row>
            <x-table.cell mono>{{ $record['code'] }}</x-table.cell>
            <x-table.cell primary>
                <div class="flex items-center gap-2.5">
                    <x-ui.avatar :name="$record['name']" size="sm" />
                    <span class="font-medium">{{ $record['name'] }}</span>
                </div>
            </x-table.cell>
            <x-table.cell mono muted>{{ $record['mobile'] }}</x-table.cell>
            <x-table.cell muted>{{ __('ui.sample.categories.'.$record['category']) }}</x-table.cell>
            <x-table.cell>
                <x-table.status-badge :type="$sampleStatusColors[$record['status']]">
                    {{ __('ui.sample.statuses.'.$record['status']) }}
                </x-table.status-badge>
            </x-table.cell>
            <x-table.cell number>
                <span @class(['text-error-600 dark:text-error-400' => $record['amount'] < 0])>{{ Fa::number($record['amount']) }}</span>
            </x-table.cell>
            <x-table.cell number muted>{{ Fa::digits(Jalali::format($record['created_at'])) }}</x-table.cell>
            <x-table.cell actions>
                <x-table.actions>
                    <x-table.action icon="eye" :tooltip="__('ui.actions.view')" href="#" />
                    <x-table.action icon="edit" :tooltip="__('ui.actions.edit')" href="#" />
                    <x-table.action icon="trash" tone="danger" :tooltip="__('ui.actions.delete')" class="delete-btn" />
                </x-table.actions>
            </x-table.cell>
        </x-table.row>
    @endforeach

    <x-slot:footer>
        <x-table.pagination :paginator="$records" />
    </x-slot:footer>
</x-table.table>
