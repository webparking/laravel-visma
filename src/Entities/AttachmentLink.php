<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

/**
 * Class AttachmentLink
 *
 * @property string $DocumentId
 * @property integer $DocumentType 0 = None, 1 = SupplierInvoice, 2 = Receipt, 3 = Voucher, 4 = SupplierInvoiceDraft, 5 = AllocationPeriod, 6 = Transfer
 * @property string[] $AttachmentIds
 *
 * @package Webparking\LaravelVisma\Entities
 */
class AttachmentLink extends BaseEntity
{
    /** @var string */
    protected $endpoint = '/attachmentlinks';

    public function save()
    {
        $queryParams = [];
        return $this->basePost($this, $queryParams);
    }
}
