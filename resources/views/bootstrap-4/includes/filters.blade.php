@if ($filtersView || count($customFilters))
    <div class="btn-group d-block d-md-inline">
        <button type="button" class="btn dropdown-toggle d-block w-100 d-md-inline" data-toggle="dropdown">
            {{ __('Filters') }}

            @if (count(array_filter($filters)) && !(count(array_filter($filters)) === 1 && isset($filters['search'])))
                <span class="badge badge-info">
                   {{ isset($filters['search']) ? count(array_filter($filters)) - 1 : count(array_filter($filters)) }}
                </span>
            @endif

            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu w-100" role="menu">
            <li>
                @if ($filtersView)
                    @include($filtersView)
                @elseif (count($customFilters))
                    @foreach ($customFilters as $key => $filter)
                        <div class="p-2">
                            @if ($filter->isSelect())
                                <label for="filter-{{ $key }}" class="mb-2">
                                    {{ $filter->name() }}
                                </label>

                                <select
                                    onclick="event.stopPropagation();"
                                    wire:model="filters.{{ $key }}"
                                    id="filter-{{ $key }}"
                                    class="form-control"
                                >
                                    @foreach($filter->options() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach
                @endif

                @if (count(array_filter($filters)) && !(count(array_filter($filters)) === 1 && isset($filters['search'])))
                    <div class="dropdown-divider"></div>

                    <a
                        href="#"
                        wire:click.prevent="resetFilters"
                        class="dropdown-item"
                    >
                        @lang('Clear')
                    </a>
                @endif
            </li>
        </ul>
    </div>
@endif
