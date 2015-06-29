<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Contact extends PUB_Controller
{
    protected $data;

    public function Contact()
    {
        parent::PUB_Controller();
        $this->load->library('template');
        $this->load->helper(array('url','directory','tbswrapper'));
        $this->load->model("website/website_model");
        $this->data = array();
    }

    public function index()
    {
//load the data first
        $data = $this->data;

        $data['data']['lang_text'] = $this->_get_language_file('', '', 'index');
        $this->template->add_js('/js/checkform.js');

        $countryid = PLATFORMCOUNTRYID;
        if (PLATFORMCOUNTRYID == "MY") $countryid = "SG";

        // moving towards a per country contact us page
        // $data['contact_info'] = $this->website_model->get_cs_contact_list_by_country(array("type"=>"WEBSITE", "lang_id"=> get_lang_id()));
        $data['contact_info'] = $this->website_model->get_cs_contact_list_by_country(array("type"=>"WEBSITE", "platform_country_id"=>$countryid));

        #SBF 2200 to get respective contact info from db according to browser lang
        $contact_info_list = $data['contact_info'];
        foreach ($contact_info_list as $contact_info_row)
        {
            $trim_lang_id = substr(lang_part(), 0, stripos(lang_part(), "_") );
            if ($contact_info_row["lang_id"] == $trim_lang_id)
            {
                $contact_info[] = $contact_info_row;
            }
        }

        if (count($contact_info_list) > 1)
            $data['contact_info'] = $contact_info;

        $this->load_tpl('content', 'tbs_contact', $data, TRUE, TRUE);
    }


    public function show_enquiry($enquiry_type='', $question_id='')
    {

        switch (strtoupper($enquiry_type))
        {
            case 'GENERAL' : $this->data['show_enquiry'] = 'Client Support'; break;
            case 'SALES' : $this->data['show_enquiry'] = 'Pre-Sales'; break;
            default : $this->data['show_enquiry'] = 'Returns';
        }

        if(is_numeric($question_id)) $this->data['question_id'] = $question_id;

        $this->index();
    }

    public function process_enquiry()
    {
        $data = array();

        if ($this->input->post('enquiry_box'))
        {
            $result = $this->website_model->process_enquiry($this->input->post('enquiry_box'), $_POST, $_FILES, get_lang_id());

            if (is_array($result))
            {
                $data['enquiry_error'] = '';
                $data['fullname'] = $result['fullname'];
                $data['email'] = $result['email'];
                $data['subject'] = $result['subject'];
                $data['contents'] = $result['contents'];

                if ($result['update_custom_field'] === TRUE)
                {
                    $data['custom_field_error'] = '';
                }
                else
                {
                    $data['custom_field_error'] = $result['update_custom_field'];
                }
            }
            else
            {
                $data['enquiry_error'] = $result;
            }
        }
        else
        {
            $data['enquiry_error'] = 'Unknown enquiry box';
        }

        $this->load_view('enquiry_box_en.php', $data);
    }

    public function generate_webform_js($enquiry_service)
    {

        $language = $this->_get_language_file('', '', 'index');

        header("Content-type: text/javascript; charset: UTF-8");
        header("Cache-Control:must-revalidate");
        $offset = 60 * 60 * 24;
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr);

        $js = '';
        if (strtolower($enquiry_service) == 'kayako')
        {
            $title = array("Client Support" => $language['iframe_form_title_client_support'],
                           "Pre-Sales"=>$language['iframe_form_title_pre_sales'],
                           "Returns"=>$language['iframe_form_title_returns'],
                           "GE PH"=>$language['iframe_field_title_ge_ph'],
                           "Bulk"=>$language['iframe_field_title_bulk_sales']
                          );
            $js_enquiry = $this->website_model->get_js_kayako_enquiry_question($title, get_lang_id());

            $base_url = base_url();
            $text_select_question = $language['iframe_message_select_a_question'];
            $text_select_country = $language['iframe_message_select_a_country'];

            $js = <<<webform_javascript
                    $js_enquiry

                    function SetAvailableEnquiry(arrEnquiry)
                    {
                        for(index in arr_data)
                        {
                            if (!(index in arrEnquiry))
                            {
                                delete arr_data[index];
                            }
                        }
                    }

                    function ShowEnquiryBoxByQuestionId(ebid,qid)
                    {
                        var enquiry_box_question = document.getElementById('enquiry_box_question')
                        ShowEnquiryBox(ebid);
                        for(var i = 0; i < enquiry_box_question.options.length; i++)
                        {
                            if(enquiry_box_question.options[i].value == qid && qid.length > 0 )
                            {
                                enquiry_box_question.options.selectedIndex = i;
                                break;
                            }
                        }
                    }

                    function ShowEnquiryBoxByCountryId(ebid,qid)
                    {
                        var enquiry_box_country = document.getElementById('enquiry_box_country');

                        if(enquiry_box_country)
                        {
                            ShowEnquiryBox(ebid);
                            for(var i = 0; i < enquiry_box_country.options.length; i++)
                            {
                                if(enquiry_box_country.options[i].value == qid && qid.length > 0 )
                                {
                                    enquiry_box_country.options.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                    }

                    function ShowEnquiryBox(id)
                    {
                        if (!(id in arr_data))
                            return false;

                        // Build enquiry type select box
                        var enquiry_type_selection = document.getElementById('enquiry_type_selection');
                        for (var i = enquiry_type_selection.length - 1; i >= 0; i--)
                        {
                            enquiry_type_selection.remove(i);
                        }

                        enquiry_type_selection.options[0] = new Option('', '');
                        for(index in arr_data)
                        {
                            if (index != id)
                            {
                                enquiry_type_selection.options[enquiry_type_selection.options.length] = new Option(arr_data[index]['title'], index);
                            }
                        }

                        // Build enquiry question select box
                        var enquiry_box_question = document.getElementById('enquiry_box_question');
                        for (var i = enquiry_box_question.length - 1; i >= 0; i--)
                        {
                            enquiry_box_question.remove(i);
                        }

                        enquiry_box_question.options[0] = new Option('$text_select_question', '');
                        for(index in arr_data[id]['question'])
                        {
                            enquiry_box_question.options[enquiry_box_question.options.length] = new Option(arr_data[id]['question'][index], index);
                        }


                        var enquiry_box_country = document.getElementById('enquiry_box_country');
                        if(enquiry_box_country)
                        {
                            for (var i = enquiry_box_country.length - 1; i >= 0; i--)
                            {
                                enquiry_box_country.remove(i);
                            }

                            enquiry_box_country.options[0] = new Option('$text_select_country', '');
                            for(index in arr_data[id]['country'])
                            {
                                enquiry_box_country.options[enquiry_box_country.options.length] = new Option(arr_data[id]['country'][index], index);
                            }
                        }
                        // Define other value
                        document.getElementById('enquiry_type').value = id;
                        document.getElementById('enquiry_box_title').innerHTML = arr_data[id]['title'];

                        if ((id == 'Pre-Sales') || (id == 'GE PH'))
                        {
                            document.getElementById('enquiry_box_tr_order_number').style.display = 'none';
                            document.getElementById('enquiry_box_order_number').removeAttribute('notEmpty');
                            document.getElementById('enquiry_box_order_number').value = '';

                            document.getElementById('enquiry_box_tr_attachment').style.display = 'none';
                            document.getElementById('enquiry_box_attachment1').value = '';
                            document.getElementById('enquiry_box_attachment2').value = '';

                            document.getElementById('enquiry_box_phone_no_required_field').style.display = '';  // Use empty rather than 'block' in order to keep it in one line
                            document.getElementById('enquiry_box_phone_no').setAttribute('notEmpty', '');

                            if (lang_id == 'EN')
                            {
                                document.getElementById('enquiry_box_tr_item_country').style.display = 'table-row';
                                document.getElementById('enquiry_box_item_country').value = '';
                            }
                        }
                        else if (id == 'Bulk')
                        {
                            document.getElementById('enquiry_box_tr_order_number').style.display = 'none';
                            document.getElementById('enquiry_box_order_number').removeAttribute('notEmpty');
                            document.getElementById('enquiry_box_order_number').value = '';

                            document.getElementById('enquiry_box_tr_attachment').style.display = 'table-row';

                            document.getElementById('enquiry_box_phone_no_required_field').style.display = '';  // Use empty rather than 'block' in order to keep it in one line
                            document.getElementById('enquiry_box_phone_no').setAttribute('notEmpty', '');

                            if (lang_id == 'EN')
                            {
                                document.getElementById('enquiry_box_tr_item_country').style.display = 'table-row';
                                document.getElementById('enquiry_box_item_country').value = '';
                            }
                        }
                        else
                        {
                            document.getElementById('enquiry_box_tr_order_number').style.display = 'table-row';
                            document.getElementById('enquiry_box_order_number').setAttribute('notEmpty', '');

                            document.getElementById('enquiry_box_tr_attachment').style.display = 'table-row';

                            document.getElementById('enquiry_box_phone_no_required_field').style.display = 'none';
                            document.getElementById('enquiry_box_phone_no').removeAttribute('notEmpty');
                            document.getElementById('enquiry_box_phone_no').value = '';

                            document.getElementById('enquiry_box_tr_item_country').style.display = 'none';
                            document.getElementById('enquiry_box_item_country').value = '';
                        }

                        document.getElementById('cover').style.display = 'block';
                        document.getElementById('enquiry_box').style.display = 'block';
                    }

                    function ChangeEnquiryType()
                    {
                        var type = document.getElementById('enquiry_type_selection').value;
                        if (type != '')
                        {
                            ShowEnquiryBox(type);
                        }
                    }

                    function HideEnquiryBox()
                    {
                        document.getElementById('cover').style.display = 'none';
                        document.getElementById('enquiry_box').style.display = 'none';
                    }

                    function HideEnquiryResult()
                    {
                        document.getElementById('cover').style.display = 'none';
                        document.getElementById('enquiry_result').style.display = 'none';
                    }

                    function SubmitEnquiry(form)
                    {
                        document.getElementById('enquiry_box').style.display = 'none';
                        document.getElementById('enquiry_processing').style.display = 'block';

                        var url = '$base_url' + 'contact/process_enquiry';
                        form.action = url;
                        form.subject.value = arr_data[form.enquiry_type.value]['question'][form.question.value];
                        form.submit();
                    }

                    function EnquiryResultError(msg)
                    {
                        document.getElementById('enquiry_processing').style.display = 'none';
                        document.getElementById('enquiry_box').style.display = 'block';
                        alert('(' + msg + ')\\n\\n Please try to resubmit again or call our national support phone line.');
                    }

                    function EnquiryResultSuccess(result)
                    {
                        document.getElementById('enquiry_result_name').innerHTML = result['fullname'];
                        document.getElementById('enquiry_result_email').innerHTML = result['email'];
                        document.getElementById('enquiry_result_enquiry_type').innerHTML = arr_data[document.getElementById('enquiry_type').value]['title'];
                        document.getElementById('enquiry_result_subject').innerHTML = result['subject'];
                        document.getElementById('enquiry_result_contents').innerHTML = result['contents'];

                        document.getElementById('enquiry_processing').style.display = 'none';
                        document.getElementById('enquiry_result').style.display = 'block';
                        document.forms['fm_enquiry'].reset();
                    }

                    function ShowEnquiryResult()
                    {
                        document.getElementById('enquiry_result_enquiry_type').innerHTML = arr_data[document.getElementById('enquiry_type').value]['title'];
                    }
webform_javascript;
        }

        echo $js;
    }
}
?>