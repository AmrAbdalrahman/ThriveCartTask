<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;


abstract class BaseRepository
{
    /**
     * @var Model|null
     */
    protected ?Model $model = null;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @return Model|null
     */
    protected function makeModel(): ?Model
    {
        if (is_null($this->model)) {
            $this->model = app($this->model());
        }
        return $this->model;
    }

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return DB::table($this->model->getTable());
    }

    /**
     * @param string $colum
     * @param array $ids
     * @param array $selectedColumns
     * @param string $orderBy
     * @param string $direction
     * @return mixed
     */
    public function findAllByWhereIn(string $colum, array $ids, array $selectedColumns = ['*'], string $orderBy = 'created_at', string $direction = 'DESC'): mixed
    {
        return $this->model
            ->whereIn($colum, $ids)
            ->select($selectedColumns)
            ->orderBy($orderBy, $direction)
            ->get();
    }
}
