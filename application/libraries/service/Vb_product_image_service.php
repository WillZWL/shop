<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Vb_product_image_service extends Base_service
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
	*	transfer_images, get the VB images to save them in atomv2 folder
	********************************************************************/
	public function transfer_images ()
	{
		//get the pending images
		$image_list = $this->get_dao()->get_pending_images();

		//var_dump($image_list);exit;
		if ($image_list !== FALSE)
		{
			foreach ($image_list as $img)
			{
				$min_priority = $img->min_priority;
				$img_priority = $img->priority;

				$id = $img->id;

				//save VB images
				$img_size = array("l", "m", "s");

				//get the image from VB
				$file = "http://www.valuebasket.com/images/product/" . $img->vb_alt_text;
				//print $file;
				//$file = "http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg";
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found')
					$file_exist = false;
				else
					$file_exist = true;

				if ($file_exist)
				{
            		//$website_domain = $this->context_config_service->value_of("website_domain");
					$imgpath = FCPATH . "../public_html/" . $this->context_config_service->value_of("prod_img_path");

					//delete old images
					$file_old = file_exists( $imgpath . $img->alt_text);
					if ($file_old)
						@unlink($imgpath . $img->alt_text);

					//save VB image in AtomV2
					//print $file;
					$image_content = file_get_contents($file);
					//$image_content = file_get_contents("http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg");
					if (file_put_contents($imgpath . $img->alt_text, $image_content) === FALSE)
					{
						continue;
					}

					list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
					//thumbnail($imgpath . $sku . "_" . $new_id . "." . $pc->image, $width, $height, $imgpath . $sku . "_" . $new_id . "." . $pc->image);
					thumbnail($imgpath . $img->alt_text, $width, $height, $imgpath . $img->alt_text);

					foreach ($img_size as $size)
					{
						//delete old images
						$img_old = is_file($imgpath . $img->sku . "_" . $img->id . "_{$size}." . $img->image);
						//print "   " . $imgpath . $img->sku . "_" . $img->id . "_{$size}." . $img->image . "   ";
						if ($img_old)
						{
							@unlink($imgpath . $img->sku . "_" . $img->id  . "_{$size}." . $img->image);
						}

						list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
						thumbnail($imgpath . $img->sku . "_" . $img->id . "." . $img->image, $width, $height, $imgpath . $img->sku . "_" . $img->id . "_{$size}." . $img->image);
					}

					//if it is the image with the minimun priority, we also save the image without the id
					if ($min_priority == $img_priority)
					{
						//delete old images
						$file_old = file_exists( $imgpath . $img->sku . "." . $img->image);
						if ($file_old)
							@unlink($imgpath . $img->sku . "." . $img->image);

						//save VB image in AtomV2
						$vars = explode("_", $img->vb_alt_text);
						$VB_sku = $vars[0];

						$vars2 = explode(".", $img->vb_alt_text);
						$ext = $vars2[count($vars2)-1];

						$image_content = file_get_contents("http://www.valuebasket.com/images/product/" . $VB_sku . "." . $ext);
						//$image_content = file_get_contents("http://www.valuebasket.fr/images/product/20233-AA-SL_29859.jpg");
						if (file_put_contents($imgpath . $img->sku . "." . $img->image, $image_content) === FALSE)
						{
							continue;
						}

						list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_w_x_h"));
						thumbnail($imgpath . $img->sku . "." . $img->image, $width, $height, $imgpath . $img->sku . "." . $img->image);

						foreach ($img_size as $size)
						{
							//delete old images
							$img_old = is_file($imgpath . $img->sku  . "_{$size}." . $img->image);
							if ($img_old)
							{
								@unlink($imgpath . $img->sku  . "_{$size}." . $img->image);
							}

							list($width, $height) = explode("x", $this->context_config_service->value_of("thumb_{$size}_w_x_h"));
							thumbnail($imgpath . $img->sku. "." . $img->image, $width, $height, $imgpath . $img->sku . "_{$size}." . $img->image);
						}
					}
				}


				$pi_obj = $this->get_dao()->get(array("id"=>$id));

				//update the product_image table
				$pi_obj->set_image_saved(1);
				$pi_obj->set_vb_alt_text("");
				$this->get_dao()->update($pi_obj);
			}
		}

		return count($image_list);
	}
}
