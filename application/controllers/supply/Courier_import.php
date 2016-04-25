<?php

class Courier_import extends MY_Controller
{
	private $appId = "SUP0017";
	private $lang_id = "en";
	private $error = "";

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$sub_app_id = $this->getAppId() . "00";
		include_once(APPPATH . "language/" . $sub_app_id . "_" . $this->getLangId() . ".php");
		$data["lang"] = $lang;
		define('DATAPATH', $this->sc["ContextConfig"]->getDao("Config")->valueOf("data_path"));
		$this->thisYear = date('Y');
		$this->thisMonth = date('M');

		if ($this->input->post('posted')) {
			$result = $this->importDataProcess();

			if ($result === FALSE) {
				$_SESSION["NOTICE"] = "Problem with import: {$this->error}";
			} else {
				if ($content = file_get_contents(DATAPATH . "/import/recommended_courier/{$this->thisYear}/{$this->thisMonth}/processed/courier_import_record_{$this->timestamp}.csv")) {
					header("Content-type: text/csv");
					header("Cache-Control: no-store, no-cache");
					header("Content-Disposition: attachment; filename=\"record_{$this->timestamp}.csv\"");
					echo $content;
					die();
				} else {
					$_SESSION["NOTICE"] = "Cannot retrieve courier_import_record_{$this->timestamp}.csv";
				}
			}
			$data["notice"] = notice($lang);
		}

		$this->load->view('supply/courier_import/index', $data);
	}

	private function importDataProcess($uploadFileFieldName = "")
	{
		if (!$uploadFileFieldName) {
			$uploadFileFieldName = "courier_process_file";
		}

		$upload_file = $_FILES[$uploadFileFieldName]["name"];
		$mainUploadPath = DATAPATH . "/import/recommended_courier";
		$config['upload_path'] = $mainUploadPath . "/" . $this->thisYear . "/" . $this->thisMonth;
		$config['allowed_types'] = 'csv';

		//firefox will put "" to the mime type, so remove it
		$_FILES[$uploadFileFieldName]["type"] = trim($_FILES[$uploadFileFieldName]["type"], '"');
		if (strpos($_FILES[$uploadFileFieldName]["type"], "csv")
			|| $_FILES[$uploadFileFieldName]["type"] == "application/x-octet-stream"
			|| $_FILES[$uploadFileFieldName]["type"] == "application/x-download"
		) {
			$_FILES[$uploadFileFieldName]["type"] = 'application/csv';
		}

		$this->timestamp = date('Ymd_His');
		$config['file_name'] = $this->timestamp;
		$config['overwrite'] = FALSE;
		$config['is_image'] = FALSE;
		$this->load->library('upload', $config);

		# check if folder exists; if not, create
		$this->createFolder($mainUploadPath);

		if (!empty($upload_file)) {
			if ($this->upload->do_upload($uploadFileFieldName)) {
				$res = $this->upload->data();

				$result = $this->importCourierData($res["full_path"]);
				return $result;
			} else {
				if ($this->upload->error_msg) {
					$this->error = "\n" . implode("\n", $this->upload->error_msg);
				} else {
					$this->error = "\n" . __LINE__ . " cannot upload file";
				}
			}
		} else {
			$this->error = "\n" . __LINE__ . " Upload file is empty";
		}

		return FALSE;

	}

	private function importCourierData($filepath)
	{
		if (!file_exists($filepath)) {
			$this->error = "courier_import.php " . __LINE__ . " No file exists for $filepath";

			return FALSE;
		} else {
			if ($fp = fopen($filepath, 'r')) {
				$i = 0;
				// we record each so_no's success status in a file
				$fp_record = fopen(DATAPATH . "/import/recommended_courier/{$this->thisYear}/{$this->thisMonth}/processed/courier_import_record_{$this->timestamp}.csv", 'w');
				fwrite($fp_record, "so_no, rec_courier, processing_remarks\r\n");      #headers
				while (($content = fgetcsv($fp)) !== FALSE) {
					$so_no = $content[0];
					$rec_courier = $content[1]; // recommended courier

					# row is blank, skip
					if (empty($so_no) && empty($rec_courier) || $so_no=="so_no" || $rec_courier=="rec_courier_id")
						continue;

					if ($rec_courier == null || $rec_courier == "") {
						// if no courier input, no point to continue
						$record_string = "$so_no, $rec_courier, N.A.\r\n";
						fwrite($fp_record, $record_string);
						continue;
					}

					if (!($obj = $this->sc["So"]->getDao("So")->get(array("so_no" => $so_no)))) {
						$record_string = "$so_no, $rec_courier, so_no does not exist\r\n";
						fwrite($fp_record, $record_string);
					} else {
						$original_rec_courier = $obj->getRecCourier();
						if (!$original_rec_courier) {
							$obj->setRecCourier($rec_courier);
							if ($this->sc["So"]->getDao("So")->update($obj) === false) {
								$dberror = __LINE__ . "courier_import.php db error " . $this->sc["So"]->getDao("So")->db->display_error();
								$record_string = "$so_no, $rec_courier, " . str_replace(',', ' ', $dberror) . "\r\n";
								fwrite($fp_record, $record_string);
							} else {
								// totally no problem
								$record_string = "$so_no, $rec_courier, success\r\n";
								fwrite($fp_record, $record_string);
							}
						} else {
							// if rec_courier has been filled, check if same as current uploaded
							if ($original_rec_courier == $rec_courier) {
								// same as before; no updates
								$record_string = "$so_no, $rec_courier, same courier has been recorded before\r\n";
								fwrite($fp_record, $record_string);
							} else {
								$obj->setRecCourier($rec_courier);
								if ($this->sc["So"]->getDao("So")->update($obj) === false) {
									$dberror = __LINE__ . "courier_import.php db error " . $this->sc["So"]->getDao("So")->db->display_error();
									$record_string = "$so_no, $rec_courier, " . str_replace(',', ' ', $dberror) . "\r\n";
									fwrite($fp_record, $record_string);
								} else {
									// inform user recommended courier changed
									$record_string = "$so_no, $rec_courier, courier updated from $original_rec_courier\r\n";
									fwrite($fp_record, $record_string);
								}
							}
						}
					}

					$i++;
				}

				fwrite($fp_record, "Total order# processed, $i\r\n");
				fclose($fp_record);
				fclose($fp);

				return TRUE;
			} else {
				$this->error = "courier_import.php " . __LINE__ . " Could not get content for $filepath";

				return FALSE;
			}
		}
	}

	protected function createFolder($upload_path)
	{
		if (!file_exists($upload_path . "/" . $this->thisYear)) {
			mkdir($upload_path . "/" . $this->thisYear, 0775, true);
		}

		if (!file_exists($upload_path . "/" . $this->thisYear . "/" . $this->thisMonth)) {
			mkdir($upload_path . "/" . $this->thisYear . "/" . $this->thisMonth, 0775);
			mkdir($upload_path . "/" . $this->thisYear . "/" . $this->thisMonth . "/processed", 0775);
		}
	}

	public function getAppId()
	{
		return $this->appId;
	}
}
