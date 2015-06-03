<?php

class Warehouse extends MY_Controller
{
	private $app_id = "";
	private $lang_id = "";

	public function __construct()
	{
		parent::__construct(FALSE);
		$this->load->model('mastercfg/warehouse_model');
	}

	public function js_warehouse()
	{
		$warehouse_list = $this->warehouse_model->get_warehouse_list(array(),array("order by"=>"id asc"));
		$js_array .= 'warehouse = {';
		$size = 0;
		foreach($warehouse_list as $key=>$obj)
		{
			$size++;
			$js_array .= "'".($key+1)."':['".$obj->get_id()."','".htmlentities(addslashes($obj->get_name()))."'],";
		}
		$js_array = ereg_replace(",$","};",$js_array);

		$js_array .= "\n\n".'warehouse_count = '.$size.';'."\n\n";
		echo $js_array;
	}

	public function js_whlist()
	{
		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		$offset = 60 * 60 * 24;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);
		$objlist = $this->warehouse_model->get_warehouse_list(array(),array("order by"=>"id asc", "limit"=>-1));
		foreach ($objlist as $obj)
		{
			$sid = str_replace("'", "\'", $obj->get_id());
			$name = str_replace("'", "\'", $obj->get_name());
			$slist[] = "'".$sid."':'".$name."'";
		}
		$js = "whlist = {".implode(", ", $slist)."};";
		$js .= "
			function InitWH(obj)
			{
				for (var i in whlist){
					obj.options[obj.options.length]=new Option(whlist[i], i);
				}
			}";
		echo $js;
	}

	public function _get_app_id(){
		return $this->app_id;
	}

	public function _get_lang_id()
	{
		return $this->lang_id;
	}
}

?>