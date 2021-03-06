<?php
class FreightHelper extends MY_Controller
{
    private $appId = "MST0009";

    public function __construct()
    {
        parent::__construct(FALSE);
    }

    public function js_freight_cat()
    {
        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control: must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);
        $objlist = $this->sc['freightModel']->getFreightCatList([], ["orderby" => "weight ASC", "limit" => -1]);
        foreach ($objlist as $obj) {
            $sid = str_replace("'", "\'", $obj->getId());
            $name = str_replace("'", "\'", $obj->getName());
            $weight = str_replace("'", "\'", $obj->getWeight());
            $slist[] = "'" . $sid . "':['" . $name . "', '" . $weight . "']";
        }
        $js = "fcatlist = {" . implode(", ", $slist) . "};";
        $js .= "
            function ChangeFCat(val, obj)
            {
                obj.value = val == '' ? '' :fcatlist[val][1];
            }

            function InitFCat(obj)
            {
                for (var i in fcatlist){
                    obj.options[obj.options.length]=new Option(fcatlist[i][0], i);
                }
            }";
        echo $js;
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
