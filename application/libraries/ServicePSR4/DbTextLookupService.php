<?php
namespace ESG\Panther\Service;
class DbTextLookupService extends BaseService
{
    /* =============================================================================================
    *   READ ME
        This service converts database values to text, usually for reports.
        As a rule, function name will be {db_table_name}_{column_name}, e.g. to find respective
        text for so_payment_status.payment_status, find function so_payment_status_payment_status()

    ============================================================================================= */

    public function __construct()
    {
        parent::__construct();
    }

    public function getSoPaymentStatus($input)
    {
        switch (strtoupper($input)) {
            case 'N':
                $text = 'New';
                break;
            case 'P':
                $text = 'Processing';
                break;
            case 'S':
                $text = 'Success';
                break;
            case 'C':
                $text = 'Cancelled';
                break;
            case 'F':
                $text = 'Failed';
                break;
            case 'B':
                $text = 'Chargeback';
                break;
            case 'CF':
                $text = 'Cancel Failed';
                break;
            default:
                $text = $input;
                break;
        }
        return $text;
    }

    public function getSoPaymentStatusPendingAction($input)
    {
        switch (strtoupper($input)) {
            case 'C':
                $text = "Cancel Payment";
                break;
            case 'R':
                $text = "Reject Challenged";
                break;
            case 'P':
                $text = "Process Challenged";
                break;
            case 'C':
                $text = "Cancel Payment";
                break;
            case 'R':
                $text = "Reject Challenged";
                break;
            case 'CC':
                $text = "Credit Check Pending";
                break;
            case 'NA':
                $text = "No more action";
                break;
            default:
                $text = $input;
        }
        return $text;
    }

    public function getSoStatus($input)
    {
        switch ($input) {
            case '0':
                $text = 'Inactive';
                break;
            case '1':
                $text = "New";
                break;
            case '2':
                $text = "Pending";
                break;
            case '3':
                $text = "Paid";
                break;
            case '4':
                $text = "Partial Allocated";
                break;
            case '5':
                $text = "Full Allocated";
                break;
            case '6':
                $text = "Shipped";
                break;

            default:
                $text = $input;
                break;
        }

        return $text;
    }

    public function getSoRefundStatus($input)
    {
        switch ($input) {
            case '0':
                $text = 'No';
                break;
            case '1':
                $text = 'Requested';
                break;
            case '2':
                $text = 'Logistic Approved';
                break;
            case '3':
                $text = 'CS Approved';
                break;
            case '4':
                $text = 'Refunded';
                break;

            default:
                $text = $input;
                break;
        }

        return $text;
    }

    public function getSoHoldStatus($input)
    {
        switch ($input) {
            case '0':
                $text = "No";
                break;
            case '1':
                $text = "Requested";
                break;
            case '2':
                $text = "Manager Requested";
                break;
            case '3':
                $text = "APS need Payment order in Sales - APS area";
                break;
            case '10':
                $text = "Permanent Hold";
                break;
            case '15':
                $text = "Has Split Child";
                break;

            default:
                $text = $input;
                break;
        }

        return $input;
    }

    public function getPaymentStatus($input) {
        switch (strtoupper($row["payment_status"])) {
            case 'N':
                $text = 'New';
                break;
            case 'P':
                $text = 'Processing';
                break;
            case 'S':
                $text = 'Success';
                break;
            case 'C':
                $text = 'Cancelled';
                break;
            case 'F':
                $text = 'Failed';
                break;
            case 'B':
                $text = 'Chargeback';
                break;
            case 'CF':
                $text = 'Cancel Failed';
                break;
            default:
                $text = $row["payment_status"];
                break;
        }
        return $text;
    }



}
