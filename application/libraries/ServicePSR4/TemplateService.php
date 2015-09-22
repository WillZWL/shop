<?php
namespace ESG\Panther\Service;

use ESG\Panther\Service\PdfRenderingService;

class TemplateService extends BaseService
{
    function __construct()
    {
        parent::__construct();
        $this->pdfRenderingService = new PdfRenderingService;
    }

    public function get_tpl_list($where = null)
    {
        if ($where == null)
            $a = $this->getDao('Template')->getList();
        else
            // this will read the template from message_alt column
            $a = $this->getDao('Template')->getList($where);

        if ($a)
            return $a;
        else
            return FALSE;
    }

    public function get_tpl_w_att($where = [])
    {
        $tmp["attachment"] = "";
        if ($tmp["template"] = $this->getDao('Template')->get($where)) {
            if ($obj_att = $this->getDao('Attachment')->getList(array("tpl_id" => $tmp["template"]->getTemplateId())))
                $tmp["attachment"] = $obj_att;
            return $obj = (object)$tmp;
        } else
            return FALSE;
    }

    public function getMsgTplWithAtt($where = [], $replace = [])
    {
        $tmp["attachment"] = $pdf_html_str = $pdf_attachment = $pdf_attachment_filepath = "";

        /* check if the template is by language or platform id */
        if ($obj_tpl = $this->getDatabaseTemplate($where)) {
            $tmp["template"] = $obj_tpl;

            /* construct search terms for variables embedded in [::] */
            $value = $search = [];
            $lang_id = $tmp["template"]->getLangId();
            if (!empty($replace)) {
                # SBF #4020 - moving to dynamic order status, so for some orders, these may be empty
                # these days below were original days (will be obsolete once shift is complete)
                if (array_key_exists("expect_ship_days", $replace)) {
                    if (empty($replace["expect_ship_days"])) {
                        switch ($lang_id) {
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

                if (array_key_exists("expect_del_days", $replace)) {
                    if (empty($replace["expect_del_days"])) {
                        switch ($lang_id) {
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
            $tmp["template"]->set_subject(str_replace($search, $value, $tmp["template"]->getSubject()));

            /* previous codes for adding attachment using attachment table */
            if ($obj_att = $this->getDao('Attachment')->getList(array("tpl_id" => $tmp["template"]->getTemplateId(), "lang_id" => $tmp["template"]->getLangId()))) {
                foreach ($obj_att as $obj) {
                    $obj->setAttFile(str_replace($search, $value, $obj->getAttFile()));
                }
                $tmp["attachment"] = $obj_att;
            }
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
        /* ============================================================================= /
            This function checks whether we have template by language or platform_id.
            Most cases by default goes by language. We check for platform template
            first as users usually move from language template to platform (thus platform
            will the latter / more updated).
        / ============================================================================= */

        # template by platform_id
        $this->db->from('template_by_platform');
        $this->db->where(array(
                "status" => 1,
                "template_by_platform_id" => $where["id"],
                "platform_id" => $where["platform_id"]
            )
        );
        $this->db->order_by("modify_on", "desc");
        $this->db->limit(1);

        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                $this->filter = $where["platform_id"];
                $obj = $query->result($classname);
                return $obj[0];
            }
        }

        # template by lang_id
        $this->db->from('template');
        $this->db->where(array(
                "status" => 1,
                "template_id" => $where["id"],
                "lang_id" => $where["lang_id"]
            )
        );
        $this->db->order_by("modify_on", "desc");
        $this->db->limit(1);

        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                $this->filter = $where["lang_id"];
                $obj = $query->result($classname);
                return $obj[0];
            }
        }

        return FALSE;
    }

    private function getDatabasePdfTemplate($where = [], $classname = "TplMsgWithAttDto")
    {
        /* ============================================================================= /
            This function checks whether we have pdf template by language or platform_id.
            Most cases by default goes by language. We check for platform template
            first as users usually move from language template to platform (thus platform
            will the latter / more updated).

            *** name your pdf template id as (event_id)_pdf
        / ============================================================================= */
        $this->include_dto($classname);
        $pdf_template_id = $where["id"] . "_pdf";

        # template by platform_id
        $this->db->from('template_by_platform');
        $this->db->where(array(
                "status" => 1,
                "template_by_platform_id" => $pdf_template_id,
                "platform_id" => $where["platform_id"]
            )
        );
        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                $obj = $query->result($classname);
                return $obj[0];
            }
        }

        # template by lang_id
        $this->db->from('template');
        $this->db->where(array(
                "status" => 1,
                "template_id" => $pdf_template_id,
                "lang_id" => $where["lang_id"]
            )
        );

        if ($query = $this->db->get()) {
            if ($query->num_rows() > 0) {
                $obj = $query->result($classname);
                return $obj[0];
            }
        }
        return FALSE;
    }

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
                    if ($att->get_id() == 0)
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

