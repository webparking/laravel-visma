<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;
use VoucherRow;

/**
 * Class Voucher
 *
 * @property string $VoucherDate
 * @property string $VoucherText
 * @property VoucherRow[] $Rows
 * @property string $ModifiedUtc
 * @property integer $VoucherType
 *
 * @package Webparking\LaravelVisma\Entities
 */
class Voucher extends BaseEntity
{
    /** @var string */
    protected $endpoint = '/vouchers';

    public function index(string $fiscalYearId): collection
    {
        $this->endpoint .= '/' . $fiscalYearId;

        return $this->baseIndex();
    }

    public function save()
    {
        return $this->basePost($this);
    }
}
