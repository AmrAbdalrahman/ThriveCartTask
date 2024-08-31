<?php

namespace App\Services;

use App\Repositories\BaseRepository;

abstract class BaseService
{
    /**
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    /**
     * @var string
     */
    protected string $entity;

    /**
     * Create a new controller instance.
     * @param BaseRepository $baseRepository
     */
    public function __construct(BaseRepository $baseRepository)
    {
        $this->repository = $baseRepository;
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
        return $this->repository->findAllByWhereIn($colum, $ids, $selectedColumns, $orderBy, $direction);
    }

}
