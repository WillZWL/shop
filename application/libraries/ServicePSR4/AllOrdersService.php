<?php

namespace ESG\Panther\Service;

class AllOrdersService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $CI->load->library('encryption');
        $this->encryption = $CI->encryption;

        $this->deliveryOptionService = new DeliveryOptionService;
        $this->dataExchangeService = new DataExchangeService;

        $this->voToXml = new VoToXml;
        $this->xmlToCsv = new XmlToCsv;
    }

    public function getReportHeading()
    {
        $fieldTitle = "Order No., Hold Reason, Hold Date, Hold Time, Hold Staff, Release Date, Release Time, Release Staff";
        $fieldTitle .= ", Order Create Date, Order Create Time, PSP Transaction ID, PSP Hold Alert, PSP Gateway, Item Ordered, Category";
        $fieldTitle .= ", Currency, Item Value, Item Quantity, Order Quantity, Order Value, Paid, MB Status, Client Forename, Client Surname, Client ID, Email Address, Billing Forename, Billing Surname, Billing Company Name";
        $fieldTitle .= ", BillingAddressLine1, BillingAddressLine2, BillingAddressLine3, BillingCity, BillingState, BillingPostalCode";
        $fieldTitle .= ", BillingCountry, DeliveryClientName, DeliveryCompanyName, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryAddressLine3";
        $fieldTitle .= ", DeliveryCity, DeliveryState, DeliveryPostalCode, DeliveryCountry, Password, Telephone, Mobile, OrderType";
        $fieldTitle .= ", ShipServiceLevel, DeliveryCost, PromotionCode, PaymentType, CardType, risk1, risk2, risk3, risk4, risk5, risk6, risk7, risk8, risk9, risk10, BIN, VerificationLevel, FraudResult";
        $fieldTitle .= ", AVSResult, ProtectionEligibility, ProtectionEligibilityType, AddressStatus, PayerStatus, IPAddress, OrderStatus";
        $fieldTitle .= ", ShippedDate, RefundStatus, RefundDate, RefundReason";
        return $fieldTitle;
    }

    public function getExportFilename($start_date, $end_date)
    {
        return "Order_screening_" . $start_date . "-" . $end_date . ".csv";
    }

    public function getAllOrdersReport($start_date, $end_date, $so_number, $order_type = "", $psp_gateway = "", $hold_reason = "", $currency = "")
    {
        return $this->getAllOrders($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
    }

    public function getAllOrdersExportReport($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency)
    {
        $report = $this->getAllOrders($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);

        $this->voToXml->VoToXml($report);
        $this->xmlToCsv->XmlToCsv('', APPPATH . 'data/all_orders_report.txt', TRUE, ',');

        $data = $this->dataExchangeService->convert($this->voToXml, $this->xmlToCsv);
        return $data;
    }

    private function getAllOrders($start_date, $end_date, $so_number, $order_type = "", $psp_gateway = "", $hold_reason = "", $currency = "")
    {
        $report = $this->getDao('So')->getAllOrdersReport($start_date, $end_date, $so_number, $order_type, $psp_gateway, $hold_reason, $currency);
        $delivery_data = end($this->deliveryOptionService->getListWithKey(["lang_id"=>"en"]));
        for ($i=0;$i<sizeof($report);$i++)
        {
            $report[$i]->setShipServiceLevel($delivery_data[$report[$i]->getDeliveryMode()]->getDisplayName());
            $report[$i]->setPassword($this->encryption->decrypt($report[$i]->getPassword()));
            $report[$i]->setBillAddress($report[$i]->getBillAddress());
            $report[$i]->setDeliveryAddress($report[$i]->getDeliveryAddress());
            $report[$i]->setPaymentStatus($report[$i]->getPaymentStatus());
            $report[$i]->setOrderCreateDateTime($report[$i]->getOrderCreateDateTime());
            $report[$i]->setHoldDateTime($report[$i]->getHoldDateTime());
        }
        return $report;
    }
}
