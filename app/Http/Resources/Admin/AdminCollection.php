<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Base\BaseCollection;

class AdminCollection extends BaseCollection
{
    public function __construct($resource)
    {
        $this->setResourceClass(AdminResource::class);
        parent::__construct($resource);
    }
}
