<?php
class Region_helper extends MY_Controller
{
	private $app_id="MST0002";

	public function __construct()
	{
		parent::__construct(FALSE);
		$this->load->model('mastercfg/Region_model');
		$this->load->helper(array('url','notice','object'));
		$this->load->library('input');
		$this->title = 'Region Information';
		$this->country_list = $this->Region_model->get_country_list(array(), array("orderby"=>"name"));
		$this->load->library('service/log_service');
	}

	public function js_courier_region(){
		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		$offset = 60 * 60 * 24;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);
		$objlist = $this->Region_model->region_service->get_list(array("type"=>"C"), array("orderby"=>"region_name ASC", "limit"=>-1));
		foreach ($objlist as $obj)
		{
			$sid = str_replace("'", "\'", $obj->get_id());
			$name = str_replace("'", "\'", $obj->get_region_name());
			$slist[] = "'".$sid."':'".$name."'";
		}
		$js = "courier_region_list = {".implode(", ", $slist)."};";
		$js .= "
			function ChangeCourierReg(val, obj)
			{
				obj.value = val == '' ? '' :val;
			}

			function InitCourierReg(obj)
			{
				for (var i in courier_region_list){
					obj.options[obj.options.length]=new Option(courier_region_list[i], i);
				}
			}";
		echo $js;
	}

	public function js_sourcing_region(){
		header("Content-type: text/javascript; charset: UTF-8");
		header("Cache-Control: must-revalidate");
		$offset = 60 * 60 * 24;
		$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
		header($ExpStr);
		$objlist = $this->Region_model->region_service->get_list(array("type"=>"S"), array("orderby"=>"region_name ASC", "limit"=>-1));
		foreach ($objlist as $obj)
		{
			$sid = str_replace("'", "\'", $obj->get_id());
			$name = str_replace("'", "\'", $obj->get_region_name());
			$slist[] = "'".$sid."':'".$name."'";
		}
		$js = "src_region_list = {".implode(", ", $slist)."};";
		$js .= "
			function ChangeSrcReg(val, obj)
			{
				obj.value = val == '' ? '' :val;
			}

			function InitSrcReg(obj)
			{
				for (var i in src_region_list){
					obj.options[obj.options.length]=new Option(src_region_list[i], i);
				}
			}";
		echo $js;
	}

	public function _get_app_id(){
		return $this->app_id;
	}
}
