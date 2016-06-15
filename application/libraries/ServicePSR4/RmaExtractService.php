<?php  
namespace ESG\Panther\Service;


class RmaExtractService extends BaseService
{
	//  private $config;
	// // private $_ftp;
	// // private $_ftp_info_dao;
 // //    private $username = 'clwms';
 // //    private $password = 'CLUUWMS56';
 // //    private $url = "http://remote.eservicesgroup.com:8080/WMS.Server.Web/Service.asmx/AutoStockOutBySalesOrdersByFile";

	// public function Rma_extract_service()
	// {
	// 	parent::Base_service();
	// 	include_once(APPPATH."libraries/dao/so_dao.php");
	// 	$this->set_dao(new So_dao());
	// 	include_once(APPPATH."libraries/service/so_service.php");
	// 	$this->set_so_service(new so_service());
	// 	include_once(APPPATH."libraries/dao/so_extend_dao.php");
	// 	$this->set_se_dao(new So_extend_dao());
	// 	include_once(APPPATH."libraries/service/context_config_service.php");
	// 	$this->set_config(new Context_config_service());
	// 	include_once(APPPATH."libraries/service/ftp_connector.php");
	// 	$this->_ftp = new Ftp_connector();
	// 	include_once(APPPATH."libraries/dao/ftp_info_dao.php");
	// 	$this->_ftp_info_dao = new Ftp_info_dao();
	// }

	public function getRmaOrderDetails($where, $option)
	{
		//so must have a status of at least 1. so must also be allocated and with shipment before it can work
		$so = $this->getDao('So')->getRmaOrder($where, $option);
		//$so = $this->get_dao()->get_rma_order($where, $option);
		foreach($so[0] as $key => $val)
		{
			$so_list[0][$key] = utf8_encode(str_replace("'", "\u0027", $val));
		}	


//var_dump($so_list ); die();
		if($so_list){
			//get client fill details
			$where3['id'] = $so_list[0]['client_id'];
			$option3['limit'] = '';
			$client = $this->getDao('So')->getRmaClient($where3, $option3);
			//$client = $this->get_dao()->get_rma_client($where3, $option3);
				foreach($client[0] as $key => $val)
				{
					$so_client[0][$key] = utf8_encode(str_replace("'", "\u0027", $val));
				}	

			//get item details
			$where2['soi.so_no'] = $so_list[0]['so_no'];
			$where2['pc.lang_id'] = 'en';
			$where2['sp.order_default'] = 1;
			$option2['limit'] = -1;
			$so_items_obj = $this->getDao('So')->getRmaItemDetails($where2, $option2);
			//$so_items_obj = $this->get_dao()->get_rma_item_details($where2, $option2);
			
			$data['orders'] = $so_list;
			$data['items'] = $so_items_obj;
			$data['client'] = $so_client;

			return $data;			
		}

		return FALSE;
	}

	public function getMasterskuDetails($where, $option)
	{
		$itemdate = $this->getDao('So')->getMasterskuData($where, $option);
		//$itemdata = $this->get_dao()->get_mastersku_data($where, $option);

		 if($itemdata){
			return $itemdata;			
		 }

		return FALSE; 
	}

	public function getRefundDetails($where, $option)
	{
		$refund_obj = $this->getDao('So')->getRmaRefundDetails($where, $option);
		//$refund_obj = $this->get_dao()->get_rma_refund_details($where, $option);

		if($refund_obj){
			return $refund_obj;
		}

		return FALSE;
	}

	public function get_so_service($value)
	{
		return $this->so_service;
	}

	public function set_so_service($value)
	{
		$this->so_service = $value;
	}

	public function get_se_dao()
    {
        return $this->se_dao;
    }

    public function set_se_dao(Base_dao $dao)
    {
        $this->se_dao = $dao;
    }

	public function get_config()
	{
		return $this->config;
	}

	public function set_config($value)
	{
		$this->config = $value;
	}

    public function get_username()
    {
        return $this->username;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function get_url()
    {
        return $this->url;
    }
}