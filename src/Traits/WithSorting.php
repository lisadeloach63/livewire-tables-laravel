<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait WithSorting.
 */
trait WithSorting
{
    public bool $showSorting = true;
    public bool $singleColumnSorting = false;
    public array $sorts = [];
    public array $sortNames = [];
    public array $sortDirectionNames = [];

    public function sortBy(string $field): ?string
    {
        if ($this->singleColumnSorting && count($this->sorts) && ! isset($this->sorts[$field])) {
            $this->sorts = [];
        }

        if (! isset($this->sorts[$field])) {
            return $this->sorts[$field] = 'asc';
        }

        if ($this->sorts[$field] === 'asc') {
            return $this->sorts[$field] = 'desc';
        }

        unset($this->sorts[$field]);

        return null;
    }

    public function applySorting(Builder $query): Builder
    {
        foreach ($this->sorts as $field => $direction) {
            if (optional($this->getColumn($field))->hasSortCallback()) {
                $query = app()->call($this->getColumn($field)->getSortCallback(), ['query' => $query, 'direction' => $direction]);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    public function removeSort(string $field): void
    {
        if (isset($this->sorts[$field])) {
            unset($this->sorts[$field]);
        }
    }

    public function resetSorts(): void
    {
        $this->sorts = [];
    }
}
