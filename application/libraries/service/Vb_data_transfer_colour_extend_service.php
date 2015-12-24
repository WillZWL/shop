<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_colour_extend_service extends Vb_data_transfer_service
{

	public function __construct($debug = 0)
	{
		parent::__construct($debug);

		include_once(APPPATH . 'libraries/dao/Colour_extend_dao.php');
		$this->colour_extend_dao = new Colour_extend_dao();
	}

	public function get_dao()
	{
		return $this->colour_extend_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->colour_extend_dao = $dao;
	}

	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the colour table
	***********************************************************************/
	public function process_vb_data ($feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<colours task_id="' . $task_id . '">';

		$c = count($xml_vb->colour);
		foreach($xml_vb->colour as $colour)
		{
			$c--;

			try
			{
				//Get the external (VB) colour id to search the corresponding id in atomv2 database
				$id = $colour->colour_id;
				$lang_id = "";

				if($colour_ext_atomv2 = $this->get_dao()->get(array("colour_id"=>$id, "lang_id"=>$colour->lang_id)))
				{
					$lang_id = $colour_ext_atomv2->get_lang_id();
				}
				//if extend content exists, update
				if ($lang_id != "" && $lang_id != null)
				{
					//Update the AtomV2 colour extend data
					$where = array("colour_id"=>$id, "lang_id"=>$lang_id);

					$new_colour_obj = array();

					$new_colour_obj["name"] = $colour->name;

					$this->get_dao()->q_update($where, $new_colour_obj);

					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->colour_id . '</id>';
					$xml[] = '<lang_id>' . $colour->lang_id . '</lang_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '</colour>';
				}
				//if not exists, insert
				else
				{
					$new_colour_obj = array();

					$new_colour_obj = $this->get_dao()->get();
					$new_colour_obj->set_colour_id($id);
					$new_colour_obj->set_lang_id($colour->lang_id);
					$new_colour_obj->set_name($colour->name);

					$this->get_dao()->insert($new_colour_obj);

					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->colour_id . '</id>';
					$xml[] = '<lang_id>' . $colour->lang_id . '</lang_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '</colour>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<colour>';
				$xml[] = '<id>' . $colour->colour_id . '</id>';
				$xml[] = '<lang_id>' . $colour->lang_id . '</lang_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
				$xml[] = '</colour>';
			}
		 }

		$xml[] = '</colours>';


		$return_feed = implode("", $xml);

		return $return_feed;
	}
}