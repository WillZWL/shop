<?php
class ExchangeRateHelper extends \MY_Controller
{
    private $appId = "MST0003";

    public function __construct()
    {
        parent::__construct(FALSE);
        $this->title = 'Region Information';
        $this->currency_list = $ccc = $this->sc['exchangeRateModel']->getActiveCurrencyList([], ["orderby" => "name ASC"]);
    }

    public function js_xratelist($to_currency = "")
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $xratelist = $this->sc['ExchangeRate']->getDao('ExchangeRate')->getList($to_currency == "" ? [] : ["to_currency_id" => $to_currency]);
        foreach ($xratelist as $obj) {
            $fid = str_replace("'", "\'", $obj->getFromCurrencyId());
            $tid = str_replace("'", "\'", $obj->getToCurrencyId());
            $rate = str_replace("'", "\'", $obj->getRate());
            $jsxratelist[$fid][] = "'" . $tid . "':" . $rate;
        }
        foreach ($jsxratelist as $jsfid => $jsrate) {
            $jsxrate[] = "'" . $jsfid . "': {" . (implode(", ", $jsrate)) . "}";
        }
        $js = "xratelist = {" . implode(", ", $jsxrate) . "};";
        $js .= "
            function exc(from, to, val, obj)
            {
                var new_val = val * xratelist[from][to];
                if (obj)
                {
                    obj.value = new_val.toFixed(4);
                }
                else
                {
                    return new_val.toFixed(4);
                }
            }";
        echo $js;
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
