<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

/**
 * Class Attachment
 *
 * @property string $Id
 * @property string $ContentType - either image/jpeg, image/png, image/tif or application/pdf
 * @property string $FileName
 * @property string $Data - Optional. Base64 encoded data.
 * @property string $Url - Optional (can be used as alternative to $Data). Public URL to the asset.
 * @property string $DocumentId
 * @property integer $AttachedDocumentType - 0 = None, 1 = SupplierInvoice, 2 = Receipt, 3 = Voucher, 4 = SupplierInvoiceDraft, 5 = AllocationPeriod, 6 = Transfer
 * @property string $TemporaryUrl
 * @property string $Comment
 * @property string $SupplierName
 * @property double $AmountInvoiceCurrency
 * @property integer $Type 0 = Invoice, 1 = Receipt, 2 = Document
 * @property integer $AttachmentStatus 0 = Matched, 1 = Unmatched
 * @property string $UploadedBy
 * @property string $ImageDate
 *
 * @package Webparking\LaravelVisma\Entities
 */
class Attachment extends BaseEntity
{
    /** @var string */
    protected $endpoint = '/attachments';

    public function index(bool $includeMatched, bool $includeTemporaryUrl): collection
    {
        $queryParams = [];
        if(isset($includeMatched)) {
            $queryParams['includeMatched'] = $includeMatched;
        }
        if(isset($includeTemporaryUrl)) {
            $queryParams['includeTemporaryUrl'] = $includeTemporaryUrl;
        }
        return $this->baseIndex($queryParams);
    }

    public function save()
    {
        $queryParams = [];
        return $this->basePost($this, $queryParams);
    }

    public function get(string $attachmentId): Attachment
    {
        $originatingEndpoint  = $this->endpoint;
        $this->endpoint .= '/' . $attachmentId;
        $attachmentData = $this->baseGet();
        $this->endpoint = $originatingEndpoint;
        foreach($attachmentData as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
}
