<?php defined('BASEPATH') OR exit('No direct script access allowed');

class rma_grid extends gridbase
{
	protected $app_id="ORD0028";
	private $lang_id="en";

/*
	private $from_mail = "Admin <admin@valuebasket.net>";

	//for multiple mail
	// array("name" => "mail")
	private $to_mail = array("RMA@eservicesgroup.net" , "csmanager@eservicesgroup.net");
	private $cc_mail = array("jesslyn@eservicesgroup.net" , "ping@eservicesgroup.net");

*/
	function __construct()
	{
		global $_dbprefix;
		parent::__construct("RMA<br><a href='javascript:DG_Do(\"add;\")'>Add</a>", "order_rma");
	}

	function setup_columns()
	{
		extract($this->var);

		$objGrid->keyfield("id");
        $objGrid->searchby("id");

		$objGrid->buttons(false,true,false,false,-1,"");


		$otheroptions = array(
				"1==1" =>
				"<a href='javascript:DG_Do(\"rma_email;['id']\")'>Email</a>",
			);

		$objGrid->keyfield ("id");
		$objGrid->searchby("id");
		$objGrid->orderby("id" , "desc");

		$objGrid->FormatColumn("id","ID", 									"0", "20", 1, "1", "right", "text");
		$objGrid->FormatColumn("saleorderno" , "Order No", 					"0", "50", 0, "10", "right", "text");
		$objGrid->FormatColumn("receiveddate","Received Date",				"0", "50", 0, "5", "left", "date:ymd:-");
		$objGrid->FormatColumn("unit" , "Unit" , 							"0" , "50", 0 , "10", "left" , "text");
		$objGrid->FormatColumn("awb","Tracking No. from returns", 			"0", "50", 0, "5", "left", "text");
		$objGrid->FormatColumn("firstname" , "First Name",					"0", "50", 0, "10", "left", "text");
		$objGrid->FormatColumn("surname" , "Sur Name" ,						"0", "50", 0, "10", "left", "text");

		$objGrid->FormatColumn("model","Model", 							"0", "50", 0, "10", "left", "text");
		$objGrid->FormatColumn("reasonforreturn","Reason for return", 		"0", "50", 0, "10", "left", "text");

		//drop down box
		$objGrid->FormatColumn("actionid","Action Requested",				"0", "50", 0, "5", "left", "selected:select id, name from order_rma_lookup_action_request");
		$objGrid->FormatColumn("descriptionoffault","Description of fault",	"0", "50", 2, "10", "left", "text");
		$objGrid->FormatColumn("email" , "Email" , 							"0" ,"50", 0, "50", "left", $otheroptions );

		$objGrid->chField("email", "X-N-", true);
	}

	function create_criteria_from_post()
    {
    	return "";
	}

	function ajax_handler()
	{
		extract($this->var);

		$param = explode(";", $objGrid->getAjaxID());
		switch ($param[0])
		{
			case "add":
				$now = date('Y-m-d');
				$strSQL = sprintf("
					INSERT INTO $table (`model`, `saleorderno`,
										 `reasonforreturn` , `actionid`, `receiveddate`)
							values ('Please enter model of sent back item', 'Please enter a SO#',
										'Please enter the reason for return' , '1' , '{$now}')
				");
                $arrData = $objGrid->SQL_query($strSQL);
				break;
			case "rma_email":


				if(isset($param[1])){

					$rma_id = $param[1];

					//send email
					$this->notify_rma_mail($rma_id);
				}
				else{
					//missing id for rma
				}


				//$this->sendmail();
				break;
		}

		switch ($objGrid->getAjaxID())
		{
			case DG_IsDelete: // case 3:	// Delete Rowa / Borrar Registro
				break;

			case 4: // updated
				break;
		}
	}

	function process_row_data($arrData = array())
    {
    	return $arrData;
    }

    //for sending email
    public function notify_rma_mail($rma_id)
	{
		// {$_SESSION["user"]["email"]}
		$get_msg = $this->get_rma_message($rma_id);
		$subject = $get_msg['subject'];
		$message = $get_msg['message'];

		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");

		$phpmail = new phpmailer();
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.net>";

		//add address

		// $phpmail->AddAddress("itsupport@eservicesgroup.net");

		$phpmail->AddAddress("sweetparents4@hotmail.com");
		$phpmail->AddAddress("RMA@eservicesgroup.net");
		$phpmail->AddAddress("csmanager@eservicesgroup.net");

		$phpmail->AddCC("jesslyn@eservicesgroup.net");
		$phpmail->AddCC("ping@eservicesgroup.net");
		$phpmail->AddCC("jerry.lim@eservicesgroup.com");

		$path = $get_msg["csvPath"];
		$phpmail->AddAttachment($path);

		$phpmail->Subject = "$subject";
		$phpmail->IsHTML(false);
		$phpmail->Body = $message;

		$result = $phpmail->Send();

		//remove exported csv file after attaching to the mail
		unlink($path);

		// $handle = fopen($path, "rb");
		// $contents = fread($handle, 100);
		// fclose($handle);
		// echo "<script>alert('$contents')</script>";

		// mail("$to, {$_SESSION["user"]["email"]}", $subject, $message);
		// error_log($subject);
	}

	public function get_rma_message($rma_id){
		//connect to the model and get message
		//how to connect model
		extract($this->var);

		$strSQL = sprintf("
					SELECT * FROM $table WHERE `id` = '$rma_id'
				");

        $arrData = $objGrid->SQL_query($strSQL);
        $rmaData = isset($arrData[0]) ? $arrData[0] : array();

		$subject = "RMA for sales order " . $rmaData["saleorderno"];

		$message =<<<email
Hi,

Please note that there will be a new process for RMA from Singapore Returns Collection Point (RCP).
Returned item (for Action: Repair) will be sent to a local repair house for assessment first.
The local repair house will respond with a quotation for repair fees if they are able to repair it. Otherwise, we will request for shipment back to HK.
With the quotation, Kenneth will decide whether to proceed with the repair or to ship it back to HK. The item needs to be returned to the RCP first before shipment can be requested.

For local repairs, items will be shipped back to customer directly after it is returned to the RCP.

RMA No./Order No. : {$rmaData["saleorderno"]} received.
Received Date : {$rmaData['receiveddate']}
Unit : {$rmaData["unit"]}
Tracking no from return : {$rmaData["awb"]}
First Name : {$rmaData["firstname"]}
Sur Name : {$rmaData["surname"]}
Model : {$rmaData["model"]}

Action Requested : {$rmaData['actionid']}
Reason for return : {$rmaData['reasonforreturn']}

-----------------
System generated.
email;

	// $message = str_ireplace("\r\n", '\r\n', $message);
		$csvPath = $this->export_csv($rmaData);
		return array("subject" => $subject , "message" => $message , "csvPath" => $csvPath) ;
	}

	public function export_csv($rmaData){
		$path = "rmexport_{$rmaData["saleorderno"]}.csv";

		$list = array (
		    array("RMA No / Order No", "Recieved Date", "Unit",
		    		"Tracking No. from returns" , "First Name" , "Sur Name" , "Model" , "Reason for return"),
		    array("{$rmaData["saleorderno"]}", $rmaData["receiveddate"] , $rmaData["unit"],
		    		$rmaData["awb"] , $rmaData["firstname"] , $rmaData["surname"] , $rmaData["model"] , $rmaData["reasonforreturn"])
		);

		$fp = fopen($path, 'w');

		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);

		return $path;
	}
}