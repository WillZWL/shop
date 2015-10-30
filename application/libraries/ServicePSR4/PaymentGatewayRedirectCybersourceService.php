<?php
namespace ESG\Panther\Service;
use ESG\Panther\Service\Cybersource\CybersourceIntegrator;

class PaymentGatewayRedirectCybersourceService extends PaymentGatewayRedirectAdapter
{
    private $_cybersourceIntegrator;
    public function __construct() {
        parent::__construct();
        $this->_cybersourceIntegrator = new CybersourceIntegrator();
    }

    public function getPaymentGatewayName() {
        return "cybersource";
    }

	public function sendOrderToDm($debug = 0)
	{
		$where = ["so.create_on >" => "2015-10-28 00:00:00"];
		$options = ["limit" => -1];
		$orders = $this->soFactoryService->getDao()->getOrdersForDm($where, $options);
		$this->debug = $debug;
		$possibleObj = "";
		$j = 0;

		foreach($orders as $order)
		{
			if ($possibleObj != "")
			{
				if ($possibleObj->getSoNo() != $order->getSoNo())
				{
					$this->sendRequestToDm($possibleObj, $possibleObj->getSoNo());
					$possibleObj = $order;
					$j = 0;
				}
			}
			else if ($possibleObj == "")
			{
				$possibleObj = $order;
			}

			$possibleObj->so_item_detail[$j] = new \SoItemVo();
			$possibleObj->so_item_detail[$j]->setSoNo($order->getSoNo());
			$possibleObj->so_item_detail[$j]->setLineNo($order->getLineNo());
			$possibleObj->so_item_detail[$j]->setProdSku($order->getProdSku());
			$possibleObj->so_item_detail[$j]->setProdName($order->getProdName());
			$possibleObj->so_item_detail[$j]->setUnitPrice($order->getUnitPrice());
			$possibleObj->so_item_detail[$j]->setQty($order->getQty());
			if ($possibleObj->getPaymentGatewayId() == 'paypal')
			{
				$possibleObj->setEmail($possibleObj->getPayerEmail());
				if (($possibleObj->getSurname() == '') || is_null($possibleObj->getSurname()))
				{
//separate the forename and surname
					$originalName = $possibleObj->getForename();
					$separateName = explode(' ', $originalName);
					if (sizeof($separateName) > 1)
					{
						$possibleObj->setForename($separateName[0]);
						$possibleObj->setSurname(str_replace($separateName[0] . " ", "", $originalName));
					}
				}
			}
			$j++;			
		}
		if ($possibleObj != "")
		{
			$this->sendRequestToDm($possibleObj, $possibleObj->getSoNo());
		}
	}

    public function sendRequestToDm($possibleOrderObjToXml, $soNo) {
        $this->_cybersourceIntegrator->sendDmRequest($this->debug, $possibleOrderObjToXml, $request, $response);
        if ($request != null) {
            $this->getSoPaymentQueryLogService()->addLog($soNo, "O", $request);
        }
        if ($response != null) {
            $paymentResult = (array)$response;
            $afsReply = (array)$paymentResult['afsReply'];
            $decisionReplyRuleResult = $paymentResult["decisionReply"]->activeProfileReply->rulesTriggered->ruleResultItem;
            $ruleResultItem = $this->_extractAndFormatRuleResultItem($decisionReplyRuleResult);
            $saveText = $this->stdObjToString($response);
            $this->getSoPaymentQueryLogService()->addLog($soNo, "I", $saveText);
            $smartId = "";
            $deviceFingerprint = (array)$afsReply["deviceFingerprint"];
            if ($deviceFingerprint) {
                if (!empty($deviceFingerprint["smartID"])) {
                    $smartId = $deviceFingerprint["smartID"];
                }
            }
            if ($this->so =  $this->soFactoryService->getDao()->get(["so_no" => $soNo])) {
                $needCreditChecks = TRUE;
                if ($paymentResult['merchantReferenceCode'] == $soNo) {
                    $sorData = ["risk_requested" => 1,
                                "risk_var_1" => ($paymentResult['decision'] . "|" . $paymentResult['reasonCode']),
                                "risk_var_4" => $afsReply['afsFactorCode'],
                                "risk_var_5" => $afsReply['afsResult'],
                                "risk_var_6" => $afsReply['suspiciousInfoCode'],
                                "risk_var_7" => $afsReply['velocityInfoCode'],
                                "risk_var_8" => $afsReply['internetInfoCode'] . "|" . $afsReply['ipCountry'],
                                "risk_var_9" => $smartId,
                                "risk_var_10" => $ruleResultItem
                            ];
                    $this->createSor($sorData);
                    if ($paymentResult['decision'] == "REJECT") {
                        $this->so->setStatus(2);
                        if ($this->so->getHoldStatus() != 1)
                            $this->so->setHoldStatus(0);
                        $this->soFactoryService->getDao()->update($this->so);
                        mail("compliance@digitaldiscount.co.uk", 'DM REJECT [Panther]:' . $soNo, $saveText, 'From: website@digitaldiscount.co.uk');
                    } else {
                        $needCreditChecks = $this->_needCreditCheckAfterDm($paymentResult['decision'], $afsReply['afsResult']);
                    }
                    if ($needCreditChecks === FALSE) {
                        $this->so->setStatus(3);
                        if ($this->so->getHoldStatus() != 1)
                            $this->so->setHoldStatus(0);
                        $this->soFactoryService->getDao()->update($this->so);
                    }
                } else {
                    $sorData = ["risk_requested" => 2];
                    $this->createSor($sorData);
                    $this->_alertIt('No such SKU DM [Panther]:' . $soNo, $saveText);
                }
            }
            if ($paymentResult['decision'] == "ERROR") {
                $this->_alertIt('ERROR IN DM [Panther]:' . $soNo, $saveText);
            }
        }
    }

    public function getTechnicalSupportEmail()
    {
        return "oswald-alert@eservicesgroup.com";
    }

    public function _alertIt($subject, $message)
    {
        mail($this->getTechnicalSupportEmail(), $subject, $message, 'From: website@valuebasket.com');
    }

    private function _needCreditCheckAfterDm($decision, $score)
    {
        return true;
    }

    private function _extractAndFormatRuleResultItem($resultItems)
    {
        $outputString = "";
        if ($resultItems->name) {
            $outputString .= $resultItems->name . "||" . $resultItems->decision . "||" . $resultItems->evaluation;
        } else {
            if ($resultItems) {
                foreach ($resultItems as $item) {
                    $outputString .= $item->name . "||" . $item->decision . "||" . $item->evaluation . "&&";
                }
                if ($outputString != "")
                    $outputString = substr($outputString, 0, (strlen($outputString) - 2));
            }
        }
        return $outputString;
    }

    public function riskIndictorRisk1($input)
    {
        $array_review = ['REVIEW'];
        $array_bad = ['REJECT'];
        $array_good = ['ACCEPT'];
        $array_unknown = ['ERROR'];
        $result = [];
        $decision = explode("|", $input);
        $result[0] = $this->getGeneralColorStyle($decision[0], $array_review, $array_bad, $array_good, $array_unknown);
        $result[0]["value"] = $input;
        return $result;
    }

    private function getGeneralColorStyle($input, $array_review, $array_bad, $array_good, $array_unknown)
    {
        if (is_null($input) || empty($input)) {
            return null;
        }

        $result = ["value" => $input, "style" => "normal"];

        if (in_array($input, $array_bad)) {
            $result["style"] = "bad";
        } elseif (in_array($input, $array_review)) {
            $result["style"] = "review";
        } elseif (in_array($input, $array_good)) {
            $result["style"] = "good";
        } elseif (in_array($input, $array_unknown)) {
            $result["style"] = "unknown";
        }

        return $result;
    }

    public function riskIndictorAvsRisk2($input)
    {
        $array_review = ['A', 'B', 'F', 'H', 'K', 'L', 'O', 'P', 'T', 'W', 'Z'];
        $array_bad = ['C', 'I', 'N'];
        $array_good = ['D', 'J', 'M', 'V', 'X', 'Y'];
        $array_unknown = ['2'];
        $result = [];
        $result[0] = $this->getGeneralColorStyle($input, $array_review, $array_bad, $array_good, $array_unknown);
        return $result;
    }

    public function riskIndictorCvnRisk3($input)
    {
        $array_review = [];
        $array_bad = ['D', 'I', 'N'];
        $array_good = ['M'];
        $array_unknown = ['2'];
        $result = [];
        $result[0] = $this->getGeneralColorStyle($input, $array_review, $array_bad, $array_good, $array_unknown);
        return $result;
    }

    public function riskIndictorAfsFactorRisk4($input)
    {
        $array_review = ['B', 'C', 'D', 'G', 'H', 'I', 'N', 'U', 'Z'];
        $array_bad = ['A', 'F', 'O', 'Q', 'R', 'V', 'W'];
        $array_good = ['E'];
        $array_unknown = [];

        $values = explode('^', $input);
        $result = [];
        for ($i = 0; $i < sizeof($values); $i++) {
            $result[$i] = $this->getGeneralColorStyle($values[$i], $array_review, $array_bad, $array_good, $array_unknown);
        }
        return $result;
    }

    public function riskIndictorScoreRisk5($input)
    {
        $result[0] = array("value" => $input, "style" => "normal");
        return $result;
    }

    public function riskIndictorSuspiciousRisk6($input)
    {
        $array_review = ['MUL-EM', 'NON-BC', 'NON-FN', 'NON-LN'];
        $array_bad = ['BAD-FP', 'MM-TZTLO', 'OBS-BC', 'OBS-EM', 'RISK-AVS', 'RISK-BIN', 'RISK-DEV', 'RISK-PIP', 'RISK-TIP'];
        $array_good = ['E'];
        $array_unknown = [];
        $result = [];
        $result[0] = $this->getGeneralColorStyle($input, $array_review, $array_bad, $array_good, $array_unknown);
        return $result;
    }

    public function riskIndictorVelocityRisk7($input)
    {
        $array_review = ['VEL-NAME', 'VELI-CC', 'VELI-EM', 'VELI-IP', 'VELI-SA', 'VELI-TIP'];
        $array_bad = ['VEL-ADDR', 'VELS-CC', 'VELS-EM', 'VELS-IP', 'VELS-SA', 'VELS-TIP'];
        $array_good = [];
        $array_unknown = [];

        $values = explode('^', $input);
        $result = [];
        for ($i = 0; $i < sizeof($values); $i++) {
            $result[$i] = $this->getGeneralColorStyle($values[$i], $array_review, $array_bad, $array_good, $array_unknown);
        }
        return $result;
    }

    public function riskIndictorInternetRisk8($input)
    {
        $array_review = ['FREE-EM', 'INV-EM', 'MM-IPBC', 'UNV-EMBCO'];
        $array_bad = ['RISK-EM', 'UNV-NID', 'UNV-RISK'];
        $array_good = [];
        $array_unknown = [];

        $result = [];
        $separate_ip = explode('|', $input);
        if (sizeof($separate_ip) > 1) {
            $ip = $separate_ip[1];
        } else
            $ip = "";

        $values = explode('^', $separate_ip[0]);
        for ($i = 0; $i < sizeof($values); $i++) {
            $result[$i] = $this->getGeneralColorStyle($values[$i], $array_review, $array_bad, $array_good, $array_unknown);
        }
        if ($ip != "")
            $result[$i] = array("value" => $ip, "style" => "normal");
        return $result;
    }
}



