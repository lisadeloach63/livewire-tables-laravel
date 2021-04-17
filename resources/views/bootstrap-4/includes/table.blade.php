<x-livewire-tables::bs4.table>
    <x-slot name="head">
        @if (count($bulkActions))
            <x-livewire-tables::bs4.table.heading>
                <input
                    wire:model="selectPage"
                    type="checkbox"
                />
            </x-livewire-tables::bs4.table.heading>
        @endif

        @foreach($columns as $column)
            @if ($column->isBlank())
                <x-livewire-tables::bs4.table.heading />
            @else
                <x-livewire-tables::bs4.table.heading
                    :sortable="$column->isSortable()"
                    :column="$column->column()"
                    :direction="$column->column() ? $sorts[$column->column()] ?? null : null"
                    :text="$column->text() ?? ''"
                    :class="$column->class() ?? ''"
                />
            @endif
        @endforeach
    </x-slot>

    <x-slot name="body">
        @if (count($bulkActions) && $selectPage && $rows->total() > $rows->count())
            <x-livewire-tables::bs4.table.row wire:key="row-message">
                <x-livewire-tables::bs4.table.cell colspan="{{ count($bulkActions) ? count($columns) + 1 : count($columns) }}">
                    @unless ($selectAll)
                        <div>
                            <span>{!! __('You have selected <strong>:count</strong> users, do you want to select all <strong>:total</strong>?', ['count' => $rows->count(), 'total' => number_format($rows->total())]) !!}</span>

                            <button
                                wire:click="selectAll"
                                type="button"
                                class="btn btn-primary btn-sm"
                            >
                                @lang('Select All')
                            </button>
                        </div>
                    @else
                        <div>
                            <span>{!! __('You are currently selecting all <strong>:total</strong> users.', ['total' => number_format($rows->total())]) !!}</span>

                            <button
                                wire:click="resetBulk"
                                type="button"
                                class="btn btn-primary btn-sm"
                            >
                                @lang('Unselect All')
                            </button>
                        </div>
                    @endif
                </x-livewire-tables::bs4.table.cell>
            </x-livewire-tables::bs4.table.row>
        @endif

        @forelse ($rows as $index => $row)
            <x-livewire-tables::bs4.table.row
                wire:loading.class.delay="text-muted"
                wire:key="table-row-{{ $row->getKey() }}"
                :url="method_exists($this, 'getTableRowUrl') ? $this->getTableRowUrl($row) : null"
            >
                @if (count($bulkActions))
                    <x-livewire-tables::bs4.table.cell>
                        <input
                            wire:model="selected"
                            value="{{ $row->getKey() }}"
                            onclick="event.stopPropagation();return true;"
                            type="checkbox"
                        />
                    </x-livewire-tables::bs4.table.cell>
                @endif

                @include($rowView, ['row' => $row])
            </x-livewire-tables::bs4.table.row>
        @empty
            <x-livewire-tables::bs4.table.row>
                <x-livewire-tables::bs4.table.cell colspan="{{ count($bulkActions) ? count($columns) + 1 : count($columns) }}">
                    @lang('No items found. Try narrowing your search.')
                </x-livewire-tables::bs4.table.cell>
            </x-livewire-tables::bs4.table.row>
        @endforelse
    </x-slot>
</x-livewire-tables::bs4.table>
