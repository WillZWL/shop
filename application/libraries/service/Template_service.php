<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Template_service extends Base_service
{

    private $tpl_dao;
    private $att_dao;

    function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Template_dao.php");
        $this->tpl_dao = new Template_dao();
        $this->set_dao($this->tpl_dao);
        include_once(APPPATH."libraries/dao/Attachment_dao.php");
        $this->att_dao = new Attachment_dao();
    }

    public function get_tpl_list($where=null){
        if ($where == null)
            $a = $this->tpl_dao->get_list();
        else
            // this will read the template from message_alt column
            $a = $this->tpl_dao->get_list($where);

        if ($a)
            return $a;
        else
            return FALSE;
    }

    public function get_tpl_w_att($where=array()){
        $tmp["attachment"]="";
        if ($tmp["template"] = $this->tpl_dao->get($where)){
            if ($obj_att = $this->att_dao->get_list(array("tpl_id"=>$tmp["template"]->get_id())))
                $tmp["attachment"] = $obj_att;
            return $obj = (object)$tmp;
        }
        else
            return FALSE;
    }

    public function get_msg_tpl_w_att($where=array(), $replace=array())
    {
        $tmp["attachment"] = $pdf_html_str = $pdf_attachment = $pdf_attachment_filepath = "";

        /* check if the template is by language or platform id */
        if($obj_tpl = $this->get_database_template($where))
        {
            $tmp["template"] = $obj_tpl;
            include_once(APPPATH."libraries/service/Context_config_service.php");
            $cconfig = new Context_config_service();

            /* construct search terms for variables embedded in [::] */
            $value = $search = array();
            $lang_id = $tmp["template"]->get_lang_id();
            if (!empty($replace))
            {
                # SBF #4020 - moving to dynamic order status, so for some orders, these may be empty
                # these days below were original days (will be obsolete once shift is complete)
                if(array_key_exists("expect_ship_days", $replace))
                {
                    if(empty($replace["expect_ship_days"]))
                    {
                        switch ($lang_id)
                        {
                            case 'en':
                            case 'fr':
                            case 'it':
                                $replace["expect_ship_days"] = '2 - 4';
                                break;

                            case 'es':
                                $replace["expect_ship_days"] = '3 - 6';
                                break;

                            case 'ru':
                                $replace["expect_ship_days"] = '3 - 5';
                                break;

                            default:
                                $replace["expect_ship_days"] = '2 - 4';
                                break;
                        }
                    }
                }

                if(array_key_exists("expect_del_days", $replace))
                {
                    if(empty($replace["expect_del_days"]))
                    {
                        switch ($lang_id)
                        {
                            case 'en':
                            case 'es':
                            case 'fr':
                            case 'it':
                                $replace["expect_del_days"] = '6 - 9';
                                break;

                            case 'ru':
                                $replace["expect_del_days"] = '6 - 26';
                                break;

                            default:
                                $replace["expect_del_days"] = '6 - 9';
                                break;
                        }
                    }
                }

                foreach ($replace as $rskey=>$rsvalue)
                {
                    $search[] = "[:".$rskey.":]";
                    $value[] = $rsvalue;
                }
            }

            /* if html template not in database, get from html file */
            if( !($msg = $obj_tpl->get_message_html()))
            {
                $msg = @file_get_contents(APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_tpl_file());
            }
            if($msg)
            {
                $tmp["template"]->set_message(str_replace($search, $value, $msg));

                // // ping: get template in html to move to db
                // BUT FIRST, go to event_email.dto > _get_email_content_arr() and uncomment line $this->replace = $data_arr;
                // if(!($filter = $where["platform_id"])) $filter = $where["lang_id"];
                // $template_path = APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_id()."_{$this->filter}_temp.html";
                // $htmlcontent = str_replace($search, $value, $msg);   #includes client's info
                // $fp = fopen($template_path, 'w');
                // fwrite($fp, $htmlcontent);
                // echo $template_path;
                // echo "<pre><hr></hr>$htmlcontent</pre>";
                // // die();
            }

            /* if alt text file not in database AND has alt text filepath, get the file */
            if( !($msg = $obj_tpl->get_message_alt()))
            {
                if($tmp["template"]->get_tpl_alt_file())
                {
                    $msg = @file_get_contents(APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_tpl_alt_file());
                }
            }
            if($msg)
            {
                $tmp["template"]->set_alt_message(str_replace($search, $value, $msg));

                // // ping: get template in html to move to db
                // BUT FIRST, go to event_email.dto > _get_email_content_arr() and uncomment line $this->replace = $data_arr;
                // if(!($filter = $where["platform_id"])) $filter = $where["lang_id"];
                // $template_path = APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_id()."_alt_{$this->filter}_temp.txt";
                // $htmlcontent = str_replace($search, $value, $msg);   #includes client's info
                // $fp = fopen($template_path, 'w');
                // fwrite($fp, $htmlcontent);
                // echo $template_path;
                // echo "<pre><hr></hr>$htmlcontent</pre>";
                // die();
            }

            /* set the subject with variables */
            $tmp["template"]->set_subject(str_replace($search, $value, $tmp["template"]->get_subject()));

            /* previous codes for adding attachment using attachment table */
            if ($obj_att = $this->att_dao->get_list(array("tpl_id"=>$tmp["template"]->get_id(), "lang_id"=>$tmp["template"]->get_lang_id())))
            {
                foreach ($obj_att as $obj)
                {
                    $obj->set_att_file(str_replace($search, $value, $obj->get_att_file()));
                }
                $tmp["attachment"] = $obj_att;
            }
            /*
                SBF #3315 - if need to attach pdf, use html template, name file as [event_id]_pdf.html
                17/03/14: we are moving towards putting template in database, if needs pdf attachment,
                put template.id / template_by_platform.id  as [event_id]_pdf
            */
            if($pdf_tpl = $this->get_database_pdf_template($where))
            {
                if( !($pdf_attachment = $pdf_tpl->get_message_html()))
                {
                    $pdf_attachment = @file_get_contents(APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_id()."_pdf.html");
                }
                if ($pdf_attachment)
                {
                    $pdf_obj_att = $this->att_dao->get();
                    $pdf_obj_att_array = array();
                    for($i=0;$i<sizeof($search);$i++)
                    {
                        if ($search[$i] == "[:image_url:]")
                        {
                            $value[$i] = "/var/www/html/valuebasket.com/public_html";
                            break;
                        }
                    }
                    $tmp["template"]->set_pdf_attachment(str_replace($search, $value, $pdf_attachment));
                    if(empty($replace["email_attachment_name"]))
                    {
                        $replace["email_attachment_name"] = "attachment";
                    }

                    if($pdf_html_str = $tmp["template"]->get_pdf_attachment())
                    {
                        include_once(APPPATH."libraries/service/Pdf_rendering_service.php");
                        $pdf_attachment = array();
                        $pdf_service = new Pdf_rendering_service();
                        $pdf_attachment_filepath = $pdf_service->convert_html_to_pdf($pdf_html_str, $replace["save_invoice_path"].$replace["email_attachment_name"].".pdf", "F");
                        if($pdf_attachment_filepath)
                        {
                            $pdf_obj_att->set_att_file($pdf_attachment_filepath);
                            $pdf_obj_att->set_lang_id($tmp["template"]->get_lang_id());

                            $pdf_obj_att_array[] = $pdf_obj_att; # put to array so it can merge into correct format of $tmp["attachment"]

                            if(!empty($obj_att))
                            {
                                $merged_att = (object)array_merge((array) $obj_att, (array) $pdf_obj_att_array);
                            }
                            else
                            {
                                $merged_att = (object)$pdf_obj_att;
                            }

                            $tmp["attachment"] = $merged_att;
                        }
                    }
                }
            }
            return $obj = (object)$tmp;

        }
        else
        {
            return FALSE;
        }


        /* ===================================================================
            OLD CODES, we are moving towards storing html in database so users can have UI to edit
           =================================================================== */
        // if ($obj_tpl = $this->tpl_dao->get_tpl_w_msg($where))
        // {
        //  $tmp["template"] = $obj_tpl;
        //  include_once(APPPATH."libraries/service/Context_config_service.php");
        //  $cconfig = new Context_config_service();

        //  $value = $search = array();

        //  if (!empty($replace))
        //  {
        //      foreach ($replace as $rskey=>$rsvalue){
        //          $search[] = "[:".$rskey.":]";
        //          $value[] = $rsvalue;
        //      }
        //  }
        //  if ($msg = @file_get_contents(APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_tpl_file()))
        //  {
        //      $tmp["template"]->set_message(str_replace($search, $value, $msg));
        //  }
        //  if ($tmp["template"]->get_tpl_alt_file() && ($msg = @file_get_contents(APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmp["template"]->get_tpl_alt_file())))
        //  {
        //      $tmp["template"]->set_alt_message(str_replace($search, $value, $msg));
        //  }
        //  $tmp["template"]->set_subject(str_replace($search, $value, $tmp["template"]->get_subject()));

        //  if ($obj_att = $this->att_dao->get_list(array("tpl_id"=>$tmp["template"]->get_id(), "lang_id"=>$tmp["template"]->get_lang_id())))
        //  {
        //      foreach ($obj_att as $obj)
        //      {
        //          $obj->set_att_file(str_replace($search, $value, $obj->get_att_file()));
        //      }
        //      $tmp["attachment"] = $obj_att;
        //  }

        //  # SBF #3315
        //  # if need to attach pdf, use html template, name file as [event_id]_pdf.html
        //  $tmpl_filename_arr = explode('.', $tmp["template"]->get_tpl_file()); # [0] - filename / [1] - file extension
        //  if ($pdf_attachment = @file_get_contents(APPPATH.$cconfig->value_of("tpl_path").$tmp["template"]->get_id()."/".$tmpl_filename_arr[0]."_pdf.".$tmpl_filename_arr[1]))
        //  {
        //      $pdf_obj_att = $this->att_dao->get();
        //      $pdf_obj_att_array = array();
        //      $tmp["template"]->set_pdf_attachment(str_replace($search, $value, $pdf_attachment));

        //      if(empty($replace["email_attachment_name"]))
        //      {
        //          $replace["email_attachment_name"] = "attachment";
        //      }

        //      if($pdf_html_str = $tmp["template"]->get_pdf_attachment())
        //      {
        //          include_once(APPPATH."libraries/service/Pdf_rendering_service.php");
        //          $pdf_attachment = array();
        //          $pdf_service = new Pdf_rendering_service();
        //          $pdf_attachment_filepath = $pdf_service->convert_html_to_pdf($pdf_html_str, $replace["save_invoice_path"].$replace["email_attachment_name"].".pdf", "F");
        //          if($pdf_attachment_filepath)
        //          {
        //              $pdf_obj_att->set_att_file($pdf_attachment_filepath);
        //              $pdf_obj_att->set_lang_id($tmp["template"]->get_lang_id());

        //              $pdf_obj_att_array[] = $pdf_obj_att; # put to array so it can merge into correct format of $tmp["attachment"]

        //              if(!empty($obj_att))
        //              {
        //                  $merged_att = (object)array_merge((array) $obj_att, (array) $pdf_obj_att_array);
        //              }
        //              else
        //              {
        //                  $merged_att = (object)$pdf_obj_att;
        //              }

        //              $tmp["attachment"] = $merged_att;
        //          }
        //      }
        //  }

        //  return $obj = (object)$tmp;
        // }
        // else
        // {
        //  return FALSE;
        // }
    }

    private function get_database_pdf_template($where = array(), $classname = "Tpl_msg_w_att_dto")
    {
        /* ============================================================================= /
            This function checks whether we have pdf template by language or platform_id.
            Most cases by default goes by language. We check for platform template
            first as users usually move from language template to platform (thus platform
            will the latter / more updated).

            *** name your pdf template id as (event_id)_pdf
        / ============================================================================= */
        $this->include_dto($classname);
        $pdf_template_id = $where["id"]."_pdf";

        # template by platform_id
        $this->db->from('template_by_platform');
        $this->db->where(array(
                            "status"=>1,
                            "id"=>$pdf_template_id,
                            "platform_id"=>$where["platform_id"]
                            )
                        );
        if($query = $this->db->get())
        {
            if ($query->num_rows() > 0)
            {
                $obj = $query->result($classname);
                return $obj[0];
            }
        }

        # template by lang_id
        $this->db->from('template');
        $this->db->where(array(
                            "status"=>1,
                            "id"=>$pdf_template_id,
                            "lang_id"=>$where["lang_id"]
                            )
                        );

        if($query = $this->db->get())
        {
            if ($query->num_rows() > 0)
            {
                $obj = $query->result($classname);
                return $obj[0];
            }
        }
        return FALSE;
    }

    private function get_database_template($where = array(), $classname = "Tpl_msg_w_att_dto")
    {
        /* ============================================================================= /
            This function checks whether we have template by language or platform_id.
            Most cases by default goes by language. We check for platform template
            first as users usually move from language template to platform (thus platform
            will the latter / more updated).
        / ============================================================================= */
        $this->include_dto($classname);

        # template by platform_id
        $this->db->from('template_by_platform');
        $this->db->where(array(
                            "status"=>1,
                            "id"=>$where["id"],
                            "platform_id"=>$where["platform_id"]
                            )
                        );
        $this->db->order_by("modify_on", "desc");
        $this->db->limit(1);

        if($query = $this->db->get())
        {
            if ($query->num_rows() > 0)
            {
                $this->filter = $where["platform_id"];
                $obj = $query->result($classname);
                return $obj[0];
            }
        }

        # template by lang_id
        $this->db->from('template');
        $this->db->where(array(
                            "status"=>1,
                            "id"=>$where["id"],
                            "lang_id"=>$where["lang_id"]
                            )
                        );
        $this->db->order_by("modify_on", "desc");
        $this->db->limit(1);

        if($query = $this->db->get())
        {
            if ($query->num_rows() > 0)
            {
                $this->filter = $where["lang_id"];
                $obj = $query->result($classname);
                return $obj[0];
            }
        }

        return FALSE;
    }

    public function insert($obj=NULL){
        if (!empty($obj)){
            if ($obj_tpl = $this->tpl_dao->insert($obj->template)){
                $obj->template = $obj_tpl;
                foreach ($obj->attachment as $att){
                    $obj_att[] = $this->att_dao->insert($att);
                }
                $obj->attachment = (object) $obj_att;
            }
            return $obj;
        }
        else
            return FALSE;
    }

    public function update($obj=NULL){
        if (!empty($obj)){
            if ($obj_tpl = $this->tpl_dao->update($obj->template)){
                $obj->template = $obj_tpl;
                foreach ($obj->attachment as $att){
                    if ($att->get_id() == 0)
                        $obj_att[] = $this->att_dao->insert($att);
                    else
                        $obj_att[] = $this->att_dao->update($att);
                }
                $obj->attachment = (object) $obj_att;
            }
            return $obj;
        }
        else
        {
            return FALSE;
        }
    }

    public function delete($where=array())
    {
        if (!empty($where))
        {
            return $this->tpl_dao->q_delete($where);
        }
        else
        {
            return FALSE;
        }
    }

}
/* End of file template_service.php */
/* Location: ./system/application/libraries/service/Template_service.php */