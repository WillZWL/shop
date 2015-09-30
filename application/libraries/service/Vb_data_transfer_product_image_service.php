<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_product_image_service extends Vb_data_transfer_service
{

	public function __construct($debug = 0)
	{
		parent::__construct($debug);

		include_once(APPPATH . 'libraries/dao/Product_image_dao.php');
		$this->product_image_dao = new Product_image_dao();

		include_once(APPPATH . 'libraries/service/Context_config_service.php');
		$this->context_config_service = new Context_config_service();

        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");
		$this->sku_mapping_service = new Sku_mapping_service();

        //$this->load->library('service/context_config_service');
	}

	public function get_dao()
	{
		return $this->product_image_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->product_image_dao = $dao;
	}

	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
	public function process_vb_data ($feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<product_images task_id="' . $task_id . '">';

		$current_sku = "";

		$c = count($xml_vb->product_image);
		foreach($xml_vb->product_image as $pc)
		{
			$c--;

			$min_priority = $pc->min_priority;
			$img_priority = $pc->priority;

			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $pc->master_sku;

			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);

			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";

			$id = 0;
			$new_id = 0;
			if($pc_obj_atomv2 = $this->get_dao()->get(array("sku"=>$sku, "priority"=>$pc->priority)))
			{
				$id = $pc_obj_atomv2->get_id();
			}

			try
			{
				if ($fail_reason == "")
				{
					if($sku != $current_sku)
					{
						//First, we delete the AtomV2 product data
						$where = array("sku"=>$sku);//, "priority"=>$priority);
						$this->get_dao()->q_delete($where);

						$current_sku = $sku;
					}

					$new_pc_obj = array();

					$new_id = $this->get_dao()->seq_next_val();

					$new_pc_obj["id"] = $new_id;
					$new_pc_obj["sku"] = $sku;
					$new_pc_obj["priority"] = $pc->priority;
					$new_pc_obj["image"] = $pc->image;
					$new_pc_obj["alt_text"] = $sku . "_" . $new_id . "." . $pc->image; //$pc->alt_text;
					$new_pc_obj["status"] = $pc->status;

					if ($this->get_dao()->q_insert($new_pc_obj))
					{
						$this->get_dao()->update_seq($new_id);

						//save VB images
						$img_size = array("l", "m", "s");

						//$file_exist = file_exists($pc->imgurl . $pc->sku . "_" . $pc->id . "." . $pc->image);

						$file = $pc->imgurl . $pc->sku . "_" . $pc->id . "." . $pc->image;
						//$file = "http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg";
						$file_headers = @get_headers($file);
						if($file_headers[0] == 'HTTP/1.1 404 Not Found')
							$file_exist = false;
						else
							$file_exist = true;

						if ($file_exist)
						{
							$imgpath = $this->context_config_service->value_of("prod_img_path");

							//delete old images
							//print $imgpath . $sku . "_" . $id . "." . $pc->image;
							$file_old = file_exists( $imgpath . $sku . "_" . $id . "." . $pc->image);
							if ($file_old)
								@unlink($imgpath . $sku . "_" . $id . "." . $pc->image);

							//save VB image in AtomV2
							$image_content = file_get_contents($pc->imgurl . $pc->sku . "_" . $pc->id . "." . $pc->image);
							//$image_content = file_get_contents("http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg");
							if (file_put_contents($imgpath . $sku . "_" . $new_id . "." . $pc->image, $image_content) === FALSE)
							{
								continue;
							}

							list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
							thumbnail($imgpath . $sku . "_" . $new_id . "." . $pc->image, $width, $height, $imgpath . $sku . "_" . $new_id . "." . $pc->image);

							foreach ($img_size as $size)
							{
								//delete old images
								$img_old = is_file($imgpath . $sku . "_" . $id  . "_{$size}." . $pc->image);
								if ($img_old)
								{
									@unlink($imgpath . $sku . "_" . $id  . "_{$size}." . $pc->image);
								}

								list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
								thumbnail($imgpath . $sku . "_" . $new_id . "." . $pc->image, $width, $height, $imgpath . $sku . "_" . $new_id . "_{$size}." . $pc->image);
							}

							//if it is the image with the minimun priority, we also save the image without the id
							if ($min_priority == $img_priority)
							{
								//delete old images
								$file_old = file_exists( $imgpath . $sku . "." . $pc->image);
								if ($file_old)
									@unlink($imgpath . $sku . "." . $pc->image);

								//save VB image in AtomV2
								$image_content = file_get_contents($pc->imgurl . $pc->sku . "." . $pc->image);
								//$image_content = file_get_contents("http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg");
								if (file_put_contents($imgpath . $sku . "." . $pc->image, $image_content) === FALSE)
								{
									continue;
								}

								list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
								thumbnail($imgpath . $sku . "." . $pc->image, $width, $height, $imgpath . $sku . "." . $pc->image);

								foreach ($img_size as $size)
								{
									//delete old images
									$img_old = is_file($imgpath . $sku  . "_{$size}." . $pc->image);
									if ($img_old)
									{
										@unlink($imgpath . $sku  . "_{$size}." . $pc->image);
									}

									list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
									thumbnail($imgpath . $sku . "." . $pc->image, $width, $height, $imgpath . $sku . "_{$size}." . $pc->image);
								}
							}
						}

						//return result
						$xml[] = '<product_image>';
						$xml[] = '<sku>' . $pc->sku . '</sku>';
						$xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
						$xml[] = '</product_image>';

					}
				}
				elseif ($sku == "" || $sku == null)
				{
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product_image>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product_image>';
				}
				else
				{
					$xml[] = '<product_image>';
					$xml[] = '<sku>' . $pc->sku . '</sku>';
					$xml[] = '<master_sku>' . $pc->master_sku. '</master_sku>';
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product_image>';
				}

			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '</product>';
			}
		 }

		$xml[] = '</product_images>';

		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}
