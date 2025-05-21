<?php

namespace RBFraphael\LaravelBoilerplate\Http\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

abstract class Repository
{
    protected $searchable = [];

    protected $filterable = [];

    protected $related = [];

    protected Builder $query;

    protected Model $model;

    protected ?string $resource = null;

    /**
     * Create a new class instance.
     */
    public function __construct(string $modelClass, ?string $resource = null)
    {
        $this->model = new $modelClass;
        $this->query = $this->model->query();
        $this->resource = $resource;
    }

    /**
     * Get all records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(?int $page = 1, ?int $perPage = 10, ?string $search = null, ?string $orderBy = 'id', ?string $order = 'asc', ?array $with = [], ?bool $paginate = true, ?array $filters = [], ?array $columns = ['*'])
    {
        $search = request()->has('search') ? request()->get('search') : $search;
        if (count($this->searchable) > 0 && $search) {
            $this->query->where(function ($query) use ($search) {
                foreach ($this->searchable as $field) {
                    if (str_contains($field, '.')) {
                        $data = explode('.', $field);
                        $key = array_pop($data);
                        $relation = implode('.', $data);
                        $query->orWhereHas($relation, function ($query) use ($key, $search) {
                            if (str_contains(strtolower($key), 'concat')) {
                                $query->where(DB::raw($key), 'like', "%$search%");
                            } else {
                                $query->where($key, 'like', "%$search%");
                            }
                        });
                    } else {
                        if (str_contains(strtolower($field), 'concat')) {
                            $query->where(DB::raw($field), 'like', "%$search%");
                        } else {
                            $query->orWhere($field, 'like', "%$search%");
                        }
                    }
                }
            });
        }

        $orderBy = request()->has('order_by') ? request()->get('order_by') : $orderBy;
        $order = request()->has('order') ? request()->get('order') : $order;
        $this->query->orderBy($orderBy, $order);

        $with = request()->has('with') ? request()->get('with') : $with;
        $this->with($with);

        foreach ($this->filterable as $field) {
            if (request()->has($field)) {
                $filters[$field] = request()->get($field);
            }
        }
        if (count($filters) > 0) {
            foreach ($filters as $key => $value) {
                $this->query->where($key, $value);
            }
        }

        $paginate = request()->has('no_paginate') ? ! boolval(request()->get('no_paginate')) : $paginate;
        if (! $paginate) {
            if (! is_null($this->resource) && class_exists($this->resource)) {
                return $this->query->get($columns)->toResourceCollection($this->resource);
            }

            return $this->query->get($columns);
        }

        $page = request()->has('page') ? request()->get('page') : $page;
        $perPage = request()->has('per_page') ? request()->get('per_page') : $perPage;
        $results = $this->query->paginate($perPage, $columns, 'page', $page);

        return $this->removePathsFromResult($results);
    }

    public function get()
    {
        return $this->query->get();
    }

    /**
     * Create a new model instance
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $instance = $this->query->create($data);
        if (! is_null($this->resource) && class_exists($this->resource)) {
            return $instance->toResource($this->resource);
        }

        return $instance;
    }

    /**
     * Update a model instance
     *
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function update(mixed $id, array $data)
    {
        $instance = $this->find($id);
        if ($instance) {
            $instance->update($data);
            if (! is_null($this->resource) && class_exists($this->resource)) {
                return $instance->toResource($this->resource);
            }

            return $instance;
        }

        return false;
    }

    /**
     * Delete a model instance
     *
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function delete(mixed $id)
    {
        $instance = $this->find($id);
        if ($instance) {
            $instance->delete();
            if (! is_null($this->resource) && class_exists($this->resource)) {
                return $instance->toResource($this->resource);
            }

            return $instance;
        }

        return false;
    }

    /**
     * Find a model instance
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(mixed $id, ?array $with = [])
    {
        $key = $this->model->getKeyName();
        $with = request()->has('with') ? request()->get('with') : $with;

        return $this->findBy($key, $id, $with);
    }

    public function findBy($key, $value, $with = [])
    {
        $this->with($with);
        $instance = $this->query->where($key, $value)->first();

        if (! is_null($this->resource) && class_exists($this->resource)) {
            return $instance->toResource($this->resource);
        }

        return $instance;
    }

    /**
     * Return the current query object
     *
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Load passed relations
     *
     * @return \App\Repositories\Repository
     */
    public function with(array|string $relations = [])
    {
        if (is_string($relations)) {
            $relations = explode(',', $relations);
        }

        $modelRelations = [];
        foreach ($relations as $relation) {
            $modelRelation = explode('.', $relation)[0];
            if (in_array($modelRelation, $this->related)) {
                $modelRelations[] = $relation;
            }
        }

        foreach ($modelRelations as $relation) {
            $this->query = $this->query->with($relation);
        }

        return $this;
    }

    public function query()
    {
        return $this;
    }

    protected function removePathsFromResult(LengthAwarePaginator $queryResult)
    {
        if (! is_null($this->resource) && class_exists($this->resource)) {
            $queryResult->toResourceCollection($this->resource);
        }

        $result = $queryResult->toArray();

        $keys = [
            'first_page_url',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'prev_page_url',
        ];

        foreach ($keys as $key) {
            unset($result[$key]);
        }

        return $result;
    }
}
