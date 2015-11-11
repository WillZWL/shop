<?php
class ProfitVarHelper extends MY_Controller
{
    private $appId = "MST0004";

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function jsPlatformlist()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->sc['profitVarModel']->getSellingPlatformList();
        foreach ($objlist as $obj) {
            $sid = str_replace("'", "\'", $obj->getSellingPlatformId());
            $name = str_replace("'", "\'", $obj->getName());
            $slist[] = "'" . $sid . "':'" . $name . "'";
        }
        $js = "platformlist = {" . implode(", ", $slist) . "};";
        $js .= "
            function InitPlatform(obj)
            {
                for (var i in platformlist){
                    obj.options[obj.options.length]=new Option(platformlist[i], i);
                }
            }";
        echo $js;
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
