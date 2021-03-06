<?php
namespace ESG\Panther\Service;

class TemplateService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
        $this->pdfRenderingService = new PdfRenderingService;
    }

    public function getEmail($where, array $replace = [])
    {
        $tpl_obj = $this->getDao('Template')->get($where);

        if (empty($tpl_obj)) {
            // TODO
            // info IT no template
            // write a common function,
            // contains $this->getDao('Template')->db->last_query();
            return false;
        }

        //add email logo
        $siteConfigObj = $this->getService("LoadSiteParameter")->loadSiteByPlatform($where["platform_id"]);
        $replace["logo"] = "http://" . $siteConfigObj->getDomain() . "/images/logo/" . $siteConfigObj->getLogo();
        $replace["site_name"] = $siteConfigObj->getSiteName();
        $replace["site_url"] = "http://" . $siteConfigObj->getDomain();

        if (!empty($replace)) {
            foreach ($replace as $key => $value) {
                $search[] = '[:' . $key . ':]';
                $replace_value[] = $value;
            }

            $tpl_obj->setSubject(str_replace($search, $replace_value, $tpl_obj->getSubject()));
            $tpl_obj->setMessageHtml(str_replace($search, $replace_value, $tpl_obj->getMessageHtml()));
            $tpl_obj->setMessageAlt(str_replace($search, $replace_value, $tpl_obj->getMessageAlt()));
        }

        return $tpl_obj;
    }

    public function getVariablesInTemplate($template_string = "", $start_delimiter = "[:", $end_delimiter = ":]")
    {
        /* ======================================================================
            This function gets all the variables in a template string, usually
            encapsulated by [::]
            e.g. [:client_id:], [:so_no:]
        ====================================================================== */

        $var_with_count_arr = $var_arr = $search_var_start = $search_var_end = array();
        $count_of_var = array();

        if ($template_string && $start_delimiter && $end_delimiter) {
            if ($search_var_start = explode($start_delimiter, $template_string)) {
                unset($search_var_start[0]);    # the array before the first "[:" is unwanted
                foreach ($search_var_start as $key => $value) {
                    # any array without ":]" in should not be a variable
                    if (strpos($value, "$end_delimiter")) {
                        $search_var_end = explode("$end_delimiter", trim($value));
                        $var_arr[] = trim($search_var_end[0]); # anything after ":]" is unwanted
                    }
                }

                # count number of occurances for each variable
                if ($count_of_var = array_count_values($var_arr)) {
                    foreach ($count_of_var as $key => $value) {
                        $var_with_count_arr[] = "$key::$value";
                    }
                }
            }
        }

        return $var_with_count_arr;
    }


    public function getTplList($where = null)
    {
        return $this->getDao('Template')->getList($where);;
    }

    public function getFileTempalte($where = [], $replace = [])
    {
        $where['type'] = 2; //file
        $tpl_id = $where['tpl_id'];
        $file_obj = $this->getDao('Template')->get($where);
        if ($file_obj) {
            $filepath = APPPATH . $this->getDao('Config')->valueOf("tpl_path").$tpl_id. "/";
            $filename = $file_obj->getTplFileName();
            $tpl_file = $filepath.$filename;
            $file_content = file_get_contents($tpl_file);
            $value = $search = [];
            if (!empty($replace)) {
                foreach ($replace as $rskey => $rsvalue) {
                    $search[] = "[:" . $rskey . ":]";
                    $value[] = $rsvalue;
                }
            }
            $file_content = str_replace($search, $value, $file_content);
            return $file_content;
        } else {
            return false;
        }
    }





    public function getEmailTemplate($where = [], $option = [])
    {
        $where['type'] = 1;//email
        $mail_obj = $this->getDao('Tempalte')->get($where);
        if ($template_obj) {

        }


        $value = $search = [];
        if (!empty($replace)) {
            foreach ($replace as $rskey => $rsvalue) {
                $search[] = "[:" . $rskey . ":]";
                $value[] = $rsvalue;
            }
        }
    }


    public function getTplWithAtt($where = [])
    {
        $tmp["attachment"] = "";
        if ($tmp["template"] = $this->getDao('Template')->get($where)) {
            if ($obj_att = $this->getDao('Attachment')->getList(array("tpl_id" => $tmp["template"]->getTemplateId())))
                $tmp["attachment"] = $obj_att;
            return $obj = (object)$tmp;
        } else {
            return FALSE;
        }
    }


    public function getMsgTplWithAtt($where = [], $replace = [])
    {
        $tmp["attachment"] = $pdf_html_str = $pdf_attachment = $pdf_attachment_filepath = "";

        /* check if the template is by language or platform id */
        if ($obj_tpl = $this->getDatabaseTemplate($where)) {
            $tmp["template"] = $obj_tpl;

            /* construct search terms for variables embedded in [::] */
            $value = $search = [];
            if (!empty($replace)) {
                foreach ($replace as $rskey => $rsvalue) {
                    $search[] = "[:" . $rskey . ":]";
                    $value[] = $rsvalue;
                }
            }

            /* if html template not in database, get from html file */
            if (!($msg = $obj_tpl->getMessageHtml())) {
                $msg = @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tmp["template"]->getTemplateId() . "/" . $tmp["template"]->getTplFile());
            }

            if ($msg) {
                $tmp["template"]->setMessage(str_replace($search, $value, $msg));
            }

            /* if alt text file not in database AND has alt text filepath, get the file */
            if (!($msg = $obj_tpl->getMessageAlt())) {
                if ($tmp["template"]->getTplAltFile()) {
                    $msg = @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tmp["template"]->getTemplateId() . "/" . $tmp["template"]->getTplAltFile());
                }
            }

            if ($msg) {
                $tmp["template"]->setAltMessage(str_replace($search, $value, $msg));
            }

            /* set the subject with variables */
            $tmp["template"]->setSubject(str_replace($search, $value, $tmp["template"]->getSubject()));

            /* previous codes for adding attachment using attachment table */
/*
            if ($obj_att = $this->getDao('Attachment')->getList(array("tpl_id" => $tmp["template"]->getTemplateId(), "lang_id" => $tmp["template"]->getLangId()))) {
                foreach ($obj_att as $obj) {
                    $obj->setAttFile(str_replace($search, $value, $obj->getAttFile()));
                }
                $tmp["attachment"] = $obj_att;
            }
*/
            /*
                SBF #3315 - if need to attach pdf, use html template, name file as [event_id]_pdf.html
                17/03/14: we are moving towards putting template in database, if needs pdf attachment,
                put template.id / template_by_platform.template_by_platform_id  as [event_id]_pdf
            */
            if ($pdf_tpl = $this->getDatabasePdfTemplate($where)) {
                if (!($pdf_attachment = $pdf_tpl->getMessageHtml())) {
                    $pdf_attachment = @file_get_contents(APPPATH . $this->getDao('Config')->valueOf("tpl_path") . $tmp["template"]->getTemplateId() . "/" . $tmp["template"]->getTemplateId() . "_pdf.html");
                }
                if ($pdf_attachment) {
                    $pdf_obj_att = $this->getDao('Attachment')->get();
                    $pdf_obj_att_array = [];
                    for ($i = 0; $i < sizeof($search); $i++) {
                        if ($search[$i] == "[:image_url:]") {
                            $value[$i] = "/var/www/html/valuebasket.com/public_html";
                            break;
                        }
                    }
                    $tmp["template"]->set_pdf_attachment(str_replace($search, $value, $pdf_attachment));
                    if (empty($replace["email_attachment_name"])) {
                        $replace["email_attachment_name"] = "attachment";
                    }

                    if ($pdf_html_str = $tmp["template"]->getPdfAttachment()) {
                        $pdf_attachment_filepath = $this->pdfRenderingService->convertHtmlToPdf($pdf_html_str, $replace["save_invoice_path"] . $replace["email_attachment_name"] . ".pdf", "F");
                        if ($pdf_attachment_filepath) {
                            $pdf_obj_att->setAttFile($pdf_attachment_filepath);
                            $pdf_obj_att->setLangId($tmp["template"]->getLangId());

                            $pdf_obj_att_array[] = $pdf_obj_att; # put to array so it can merge into correct format of $tmp["attachment"]

                            if (!empty($obj_att)) {
                                $merged_att = (object)array_merge((array)$obj_att, (array)$pdf_obj_att_array);
                            } else {
                                $merged_att = (object)$pdf_obj_att;
                            }

                            $tmp["attachment"] = $merged_att;
                        }
                    }
                }
            }

            return $obj = (object)$tmp;

        } else {
            return FALSE;
        }
    }

    private function getDatabaseTemplate($where = [], $classname = "TplMsgWithAttDto")
    {


        if ($obj = $this->getDao('TemplateByPlatform')->getList([
                                                        "status" => 1,
                                                        "template_by_platform_id" => $where["id"],
                                                        "platform_id" => $where["platform_id"]
                                                     ],
                                                     [
                                                        'limit'=>1,
                                                        'orderby'=> 'modify_on desc'
                                                     ], $classname))
        {
            return $obj;
        }

        return FALSE;
    }

    // private function getDatabasePdfTemplate($where = [], $classname = "TplMsgWithAttDto")
    // {
    //     /* ============================================================================= /
    //         This function checks whether we have pdf template by language or platform_id.
    //         Most cases by default goes by language. We check for platform template
    //         first as users usually move from language template to platform (thus platform
    //         will the latter / more updated).

    //         *** name your pdf template id as (event_id)_pdf
    //     / ============================================================================= */

    //     $pdf_template_id = $where["id"] . "_pdf";

    //     # template by platform_id
    //     // $this->db->from('template_by_platform');
    //     // $this->db->where(array(
    //     //         "status" => 1,
    //     //         "template_by_platform_id" => $pdf_template_id,
    //     //         "platform_id" => $where["platform_id"]
    //     //     )
    //     // );
    //     // if ($query = $this->db->get()) {
    //     //     if ($query->num_rows() > 0) {
    //     //         $obj = $query->result($classname);
    //     //         return $obj[0];
    //     //     }
    //     // }

    //     // # template by lang_id
    //     // $this->db->from('template');
    //     // $this->db->where(array(
    //     //         "status" => 1,
    //     //         "template_id" => $pdf_template_id,
    //     //         "lang_id" => $where["lang_id"]
    //     //     )
    //     // );

    //     // if ($query = $this->db->get()) {
    //     //     if ($query->num_rows() > 0) {
    //     //         $obj = $query->result($classname);
    //     //         return $obj[0];
    //     //     }
    //     // }


    //     if ($obj = $this->getDao('TemplateByPlatform')->getList([
    //                                                     "status" => 1,
    //                                                     "template_by_platform_id" => $pdf_template_id,
    //                                                     "platform_id" => $where["platform_id"]
    //                                                  ],
    //                                                  [
    //                                                     'limit'=>1,
    //                                                     'orderby'=> 'modify_on desc'
    //                                                  ]))
    //     {
    //         return $obj;
    //     }

    //     if ($obj = $this->getDao('Template')->getList([
    //                                                     "status" => 1,
    //                                                     "template_id" => $pdf_template_id,
    //                                                     "lang_id" => $where["lang_id"]
    //                                                  ],
    //                                                  [
    //                                                     'limit'=>1,
    //                                                     'orderby'=> 'modify_on desc'
    //                                                  ]))
    //     {
    //         return $obj;
    //     }

    //     return FALSE;
    // }

    public function insert($obj = NULL)
    {
        if (!empty($obj)) {
            if ($obj_tpl = $this->getDao('Template')->insert($obj->template)) {
                $obj->template = $obj_tpl;
                foreach ($obj->attachment as $att) {
                    $obj_att[] = $this->getDao('Attachment')->insert($att);
                }
                $obj->attachment = (object)$obj_att;
            }
            return $obj;
        } else
            return FALSE;
    }

    public function update($obj = NULL)
    {
        if (!empty($obj)) {
            if ($obj_tpl = $this->getDao('Template')->update($obj->template)) {
                $obj->template = $obj_tpl;
                foreach ($obj->attachment as $att) {
                    if ($att->getId() == 0)
                        $obj_att[] = $this->getDao('Attachment')->insert($att);
                    else
                        $obj_att[] = $this->getDao('Attachment')->update($att);
                }
                $obj->attachment = (object)$obj_att;
            }
            return $obj;
        } else {
            return FALSE;
        }
    }

    public function delete($where = [])
    {
        if ($obj = $this->getDao('Template')->get($where)) {
            return $this->getDao('Template')->delete($obj);
        } else {
            return FALSE;
        }
    }

}
