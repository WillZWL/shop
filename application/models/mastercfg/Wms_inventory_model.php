<?php

class Wms_inventory_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/context_config_service');
		$this->load->library('service/communication_framework_service');
		$this->load->library('service/wms_inventory_service');
		$this->load->library('service/wms_warehouse_service');
	}

	public function get_inventory()
	{
		// Get retailer inv
		$url = $this->context_config_service->value_of('wms_base_url') . $this->context_config_service->value_of('wms_retailer_inventory_url');
		$htaccess = $this->context_config_service->value_of('wms_htaccess_username') . ':' . $this->context_config_service->value_of('wms_htaccess_password');

		if ($retailer_list = $this->wms_warehouse_service->get_retailer_list())
		{
			$counter = 0;
			$post_data = array();
			foreach ($retailer_list as $retailer)
			{
				$post_data['retailer[' . $counter++ . ']'] = $retailer->get_warehouse_id();
			}

			$this->communication_framework_service->set_remote_url($url);
			$this->communication_framework_service->set_htaccess($htaccess);
			$this->communication_framework_service->set_post_para($post_data);
			$this->communication_framework_service->call_remote_url();

			if ($this->communication_framework_service->get_remote_result())
			{
				$retailer_inv_xml = $this->communication_framework_service->get_remote_result_xml();
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}

		// Get warehouse inv
		$url = $this->context_config_service->value_of('wms_base_url') . $this->context_config_service->value_of('wms_warehouse_inventory_url');
		$this->communication_framework_service->clear_post_para();

		if ($warehouse_list = $this->wms_warehouse_service->get_warehouse_list())
		{
			$counter = 0;
			$post_data = array();
			foreach ($warehouse_list as $warehouse)
			{
				$post_data['warehouse_id[' . $counter++ . ']'] = $warehouse->get_warehouse_id();
			}

			$this->communication_framework_service->set_remote_url($url);
			$this->communication_framework_service->set_post_para($post_data);
			$this->communication_framework_service->call_remote_url();

			if ($this->communication_framework_service->get_remote_result())
			{
				$warehouse_inv_xml = $this->communication_framework_service->get_remote_result_xml();
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}

		// Process both inv xml
		$this->wms_inventory_service->get_dao()->trans_start();

		$this->wms_inventory_service->empty_table();
		if ($this->insert_inventory($retailer_inv_xml) && $this->insert_inventory($warehouse_inv_xml))
		{
			$this->wms_inventory_service->get_dao()->trans_complete();
			return TRUE;
		}
		else
		{
			$this->wms_inventory_service->get_dao()->trans_rollback();
			return FALSE;
		}
	}

	private function insert_inventory($xml_obj)
	{
		$inv = array();

		foreach ($xml_obj as $node)
		{
			if (strtolower($node->getName()) == 'warehouse_info')
			{
				foreach ($node->children() as $warehouse_info)
				{
					if (strtolower($warehouse_info->getName()) == 'dest_wh_code')
					{
						$warehouse_id = (string) $warehouse_info;
						$inv[$warehouse_id] = array();
					}
					elseif (strtolower($warehouse_info->getName()) == 'item')
					{
						foreach ($warehouse_info->children() as $item)
						{
							if (strtolower($item->getName()) == 'sku')
							{
								$sku = (string) $item;
								$inv[$warehouse_id][$sku] = array();
								$inv[$warehouse_id][$sku]['git'] = 0;
							}
							elseif(strtolower($item->getName()) == 'goods_in_warehouse')
							{
								$inv[$warehouse_id][$sku]['inventory'] = intval($item);
							}
							elseif(strtolower($item->getName()) == 'transit')
							{
								foreach ($item->children() as $transit)
								{
									if (strtolower($transit->getName()) == 'goods_in_transit')
									{
										if ((string) $transit != '')
										{
											$inv[$warehouse_id][$sku]['git'] += intval($transit);
										}
									}
								}
							}
						}
					}
				}
			}
		}

		if (sizeof($inv) > 0)
		{
			return $this->wms_inventory_service->renew_inventory($inv);
		}

		return TRUE;
	}
}
?>