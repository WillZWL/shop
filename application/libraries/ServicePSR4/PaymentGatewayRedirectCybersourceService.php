<?php
namespace ESG\Panther\Service;

class PaymentGatewayRedirectCybersourceService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
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



