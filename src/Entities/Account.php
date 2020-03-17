<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

class Account extends BaseEntity
{
    /** @var string */
    protected $endpoint = '/accounts';

    public function index(): collection
    {
        return $this->baseIndex();
    }
}
