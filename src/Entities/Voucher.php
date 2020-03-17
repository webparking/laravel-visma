<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

class Voucher extends BaseEntity
{
    /** @var string */
    protected $endpoint = '/vouchers';

    public function index(string $fiscalYearId): collection
    {
        $this->endpoint .= '/' . $fiscalYearId;

        return $this->baseIndex();
    }
}
