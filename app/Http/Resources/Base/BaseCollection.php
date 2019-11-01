<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseCollection extends ResourceCollection
{
    private $pagination;
    private $resourceClass;

    /**
     * BaseCollection constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->setPagination($resource);
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'items' => $this->collection->transform(function ($data) {
                return new $this->resourceClass($data);
            }),
            'pagination' => $this->getPagination()
        ];
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }

    /**
     * @param $resource
     */
    public function setPagination($resource): void
    {
        $hasPagination = $resource instanceof LengthAwarePaginator;
        $this->pagination = [
            'totalItems' => $hasPagination ? $resource->total() : $resource->count(),
            'perPage' => $hasPagination ? $resource->perPage() : $resource->count(),
            'page' => $hasPagination ? $resource->currentPage() : 1,
            'totalPages' => $hasPagination ? $resource->lastPage() : 1,
        ];
    }

    /**
     * @param mixed $resourceClass
     */
    public function setResourceClass($resourceClass): void
    {
        $this->resourceClass = $resourceClass;
    }
}
