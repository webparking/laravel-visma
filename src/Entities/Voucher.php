<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

/**
 * @property string       $VoucherDate
 * @property string       $VoucherText
 * @property VoucherRow[] $Rows
 * @property string       $ModifiedUtc
 * @property int          $VoucherType
 * @property string       $NumberSeries
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

    public function save(): object
    {
        $queryParams = [];
        if (isset($this->NumberSeries)) {
            $queryParams['useDefaultVoucherSeries'] = 'false';
        }

        return $this->basePost($this, $queryParams);
    }
}
