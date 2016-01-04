<?php
namespace ESG\Panther\Service;

class PhoneSalesService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addSoForPhoneSales($post_data, $platform_id)
    {
        $this->getService("SoFactory")->createSaleOrder();
    }

    public function processDataForOnHold($post_data)
    {
        if ($so_obj = $this->getDao('So')->get(["so_no"=>$post_data['so_no']])) {
            if ($post_data['status'] == 2) {
                $platform_obj = $this->getDao('PlatformBizVar')->get(["selling_platform_id"=>$so_obj->getPlatformId()]);
                $so_obj->setExpectDeliveryDate(date("Y-m-d H:i:s", time()+$platform_obj->getLatencyInStock()*86400));

                $action = "update";
                $socc_obj = $this->getDao('SoCreditChk')->get(["so_no" => $post_data['so_no']]);
                if (!$socc) {
                    $socc_obj = $this->getDao('SoCreditChk')->get();
                    $action = "insert";
                    $socc_obj->setSoNo($post_data['so_no']);
                    $socc_obj->setFdProcStatus(0);
                    $socc_obj->setFdStatus(0);
                }

                $sops_obj = $this->getDao('SoPaymentStatus')->get(["so_no" => $post_data['so_no']]);
                if (!$sops_obj) {
                    $sops_obj = $this->getDao('SoPaymentStatus')->get();
                    $sops_obj->setSoNo($post_data['so_no']);
                    $sops_action = 'insert';
                } else {
                    $sops_action = 'update';
                }

                $sops_obj->setPayToAccount($post_data['pay_to_account']);
                $sops_obj->setPaymentGatewayId($post_data['payment_gateway']);
                $sops_obj->setPaymentStatus("S");
                $sops_obj->setPayDate(date("Y-m-d H:i:s"));
                $this->getDao('SoPaymentStatus')->$sops_action($sops_obj);

                if ($post_data['offline_fee'] !== "" && is_numeric($post_data['offline_fee'])) {
                    $soext_obj = $this->getDao('SoExtend')->get(["so_no" => $post_data['so_no']]);
                    if ($soext_obj) {
                        $old_offline_fee = $soext_obj->getOfflineFee();
                        $new_offline_fee = trim($post_data['offline_fee']);

                        $soext_obj->setOfflineFee($new_offline_fee);
                        $this->getDao('SoExtend')->update($soext_obj);
                        $original_amount = $so_obj->getAmount();
                        $offline_fee_changed = $new_offline_fee - $old_offline_fee;

                        $new_amount = $original_amount + $offline_fee_changed;
                        $so_obj->setAmount($new_amount);

                        $son_obj = $this->getDao('OrderNotes')->get();
                        $son_obj->setSoNo($post_data['so_no']);

                        $new_amount = number_format($new_amount, 2, '.', '');
                        $note = "original amount: $original_amount, amend amount: {$new_amount}";
                        $son_obj->setNote($note);
                        $son_obj = $this->getDao('OrderNotes')->insert($son_obj);
                    }
                }
            } else {
                $this->getDao('SoCreditChk')->qDelete(["so_no" => $post_data['so_no']]);
            }

            $so_obj->setTxnId($post_data['txn_id']);
            $so_obj->setStatus($post_data['status']);

            if (!$this->getDao('So')->update($so_obj)) {
                $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->display_error();
            } else {
                if ($post_data['status'] == 2) {
                    $this->pmgw->so = $so_obj;
                    // $this->pmgw->fire_success_event();
                    // mail("compliance@valuebasket.com", '[VB] phone sales order move to cc so_no - ' . $so_obj->getSoNo(), $so_obj->getSoNo(), 'From: website@valuebasket.com');
                }
                if ($action != "" && !$this->getDao('SoCreditChk')->$action($socc_obj)) {
                    $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->display_error();
                } else {
                    redirect($_SESSION["LISTPAGE"]);
                }
            }
        } else {
            $_SESSION["NOTICE"] = "ERROR " . __LINE__ . " : " . $this->db->display_error();
        }
    }

    public function postDataToCart($post_data)
    {
            if ($sku = $post_data['add']) {
                $this->getService('CartSession')->setCartDetailInfo(true);
                 $this->getService('CartSession')->add($sku, 1, $post_data['lang'], $post_data['platform'], $post_data['currency']);
            }

            if ($post_data['qty']) {
                foreach ($post_data['qty'] as $sku => $qty) {
                    $this->getService('CartSession')->modifyItem('SET', $sku, $qty, $post_data['platform']);
                }
            }
    }

    public function checkCartByPlatform($platform)
    {
        if (isset($_SESSION["cart"])) {
            $check_cart = unserialize($_SESSION["cart"]);

            if ($check_cart->getPlatformId() !== $platform) {
                unset($_SESSION["cart"]);
            }
        }
    }

    public function getCart()
    {
        return $this->getService('CartSession')->getCart('OFFLINE');
    }
}