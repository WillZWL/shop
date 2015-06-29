<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kayako_service
{
    const HELP_DESK_URL = 'valuebasket.kayako.com';
    const API_URL = 'http://valuebasket.kayako.com/api/';
    const API_KEY = 'f557961d-08c3-cc44-5d4d-e3ea48fb5cd1';
    const SECRET_KEY = 'ZmZiMmZmN2YtZjlhMy04N2E0LTc1NzUtY2U1ZDg5NDZhNTUwMjExYzFlYjUtNjNlNy03NzQ0LTBkMTgtN2FjOTY1Y2VlZmNk';
    const REST_GET = 'GET';
    const REST_PUT = 'PUT';
    const REST_POST = 'POST';
    const REST_DELETE = 'DELETE';

    private $salt;
    private $signature;

    protected $default_ticket_priority_id;
    protected $default_ticket_status_id;
    protected $default_ticket_type_id;
    protected $rest_error = '';

    protected $department = '';
    protected $department_id = '';
    protected $lang_id = '';
    protected $template_group_id = '';

    public function __construct()
    {
        $this->salt = mt_rand();
        $this->signature = base64_encode(hash_hmac('sha256', $this->salt, Kayako_service::SECRET_KEY, true));
    }

    public function get_rest_error()
    {
        return $this->rest_error;
    }

    public function set_rest_error($error)
    {
        $this->rest_error = $error;
    }

    private function run_rest($rest_controller, $rest_command, $data = array())
    {
        $data['apikey'] = Kayako_service::API_KEY;
        $data['salt'] = $this->salt;
        $data['signature'] = $this->signature;
        $data = http_build_query($data, '', '&');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        switch ($rest_command)
        {
            case Kayako_service::REST_GET    : curl_setopt($curl, CURLOPT_HTTPGET, true);
                                               curl_setopt($curl, CURLOPT_URL, Kayako_service::API_URL . 'index.php?e=/' . $rest_controller . '&' . $data);
                                               break;

            case Kayako_service::REST_POST   : curl_setopt($curl, CURLOPT_POST, true);
                                               curl_setopt($curl, CURLOPT_URL, Kayako_service::API_URL . $rest_controller);
                                               curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                                               break;
            // case Kayako_service::REST_PUT    : curl_setopt($curl, CURLOPT_PUT, true); break;
            // case Kayako_service::REST_DELETE : curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE'); break;
            default                          : return FALSE;
        }

        // Network connection is bad......
        $retry_counter = 2;
        do
        {
            $response = curl_exec($curl);
        } while (($response === FALSE) && ($retry_counter-- > 0));

        if ($response === FALSE)
        {
            //error_log(curl_error($curl));
            $this->set_rest_error('Error code:' . curl_errno($curl));
            $result = FALSE;
        }
        else
        {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$xml)
            {
                $this->set_rest_error($response);
                $result = FALSE;
            }
            else
            {
                $this->set_rest_error('');
                $result = $xml;
            }
        }

        curl_close($curl);
        return $result;
    }

    public function get_department()
    {
        return $this->department;
    }

    public function set_department($title)
    {
        $rest_controller = 'Base/Department';
        $rest_command = Kayako_service::REST_GET;

        // Get default department from Kayako
        $response = $this->run_rest($rest_controller, $rest_command);
        if ($response !== FALSE)
        {
            foreach ($response as $department)
            {
                if (strpos($department->title, $title) !== FALSE)
                {
                    $this->department = (string) $department->title;
                    $this->department_id = (string) $department->id;
                    break;
                }
            }
        }
    }

    public function get_department_id()
    {
        return $this->department_id;
    }

    public function set_department_id($id)
    {
        $rest_controller = 'Base/Department/' . $id;
        $rest_command = Kayako_service::REST_GET;

        // Get default department from Kayako
        $response = $this->run_rest($rest_controller, $rest_command);
        if ($response !== FALSE)
        {
            $this->department = (string) $response->department->title;
            $this->department_id = (string) $response->department->id;
        }
    }

    public function get_all_ticket_priority_xml()
    {
        $rest_controller = 'Tickets/TicketPriority';
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_ticket_priority_xml($id)
    {
        $rest_controller = 'Tickets/TicketPriority/' . $id;
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_default_ticket_priority_xml()
    {
        $all = $this->get_all_ticket_priority_xml();

        // We define the first node as the default
        if ($all !== FALSE)
        {
            return $all->ticketpriority[0];
        }
        else
        {
            return FALSE;
        }
    }

    public function get_default_ticket_priority_id()
    {
        return $this->default_ticket_priority_id;
    }

    public function set_default_ticket_priority_id($id)
    {
        $this->default_ticket_priority_id = $id;
    }

    public function get_all_ticket_status_xml()
    {
        $rest_controller = 'Tickets/TicketStatus';
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_ticket_status_xml($id)
    {
        $rest_controller = 'Tickets/TicketStatus/' . $id;
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_default_ticket_status_xml()
    {
        $all = $this->get_all_ticket_status_xml();

        // We define the first node as the default
        if ($all !== FALSE)
        {
            return $all->ticketstatus[0];
        }
        else
        {
            return FALSE;
        }
    }

    public function get_default_ticket_status_id()
    {
        return $this->default_ticket_status_id;
    }

    public function set_default_ticket_status_id($id)
    {
        $this->default_ticket_status_id = $id;
    }

    public function get_all_ticket_type_xml()
    {
        $rest_controller = 'Tickets/TicketType';
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_ticket_type_xml($id)
    {
        $rest_controller = 'Tickets/TicketType/' . $id;
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_default_ticket_type_xml()
    {
        $all = $this->get_all_ticket_type_xml();

        // We define the first node as the default
        if ($all !== FALSE)
        {
            return $all->tickettype[0];
        }
        else
        {
            return FALSE;
        }
    }

    public function get_default_ticket_type_id()
    {
        return $this->default_ticket_type_id;
    }

    public function set_default_ticket_type_id($id)
    {
        $this->default_ticket_type_id = $id;
    }

    public function set_default_ticket_info()
    {
        $priority = $this->get_default_ticket_priority_xml();
        $this->set_default_ticket_priority_id((string) $priority->id);

        $status = $this->get_default_ticket_status_xml();
        $this->set_default_ticket_status_id((string) $status->id);

        $type = $this->get_default_ticket_type_xml();
        $this->set_default_ticket_type_id((string) $type->id);
    }

    public function get_custom_field()
    {
        $rest_controller = 'Base/CustomField';
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function get_custom_field_option_xml($custom_field_id)
    {
        $rest_controller = 'Base/CustomField/ListOptions/' . $custom_field_id;
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function create_ticket($data, $file)
    {
        $rest_controller = 'Tickets/Ticket';
        $rest_command = Kayako_service::REST_POST;

        $extra_rule = array();
        foreach ($file as $attachment)
        {
            if ($attachment['name'] != '')
            {
                $extra_rule['hasAttachment'] = 1;
                break;
            }
        }

        $department_id = $this->get_question_department_mapping($data['question'], $data['department_id'], $extra_rule);

        $post_data = array('subject' => $data['subject'],
                           'fullname' => $data['fullname'],
                           'email' => $data['email'],
                           'contents' => $data['contents'],
                           'country' => $data['country'],
                           'departmentid' => $department_id,
                           'ticketstatusid' => (isset($data['ticket_status_id']) ? $data['ticket_status_id'] : $this->get_default_ticket_status_id()),
                           'ticketpriorityid' => (isset($data['ticket_priority_id']) ? $data['ticket_priority_id'] : $this->get_default_ticket_priority_id()),
                           'tickettypeid' => (isset($data['ticket_type_id']) ? $data['ticket_type_id'] : $this->get_default_ticket_type_id()),
                           'autouserid' => '1',
                           'templategroup' => $this->get_template_group_id()
                          );

        $retry_counter = 2;
        do
        {
            $response = $this->run_rest($rest_controller, $rest_command, $post_data);
        } while (($response === FALSE) && ($retry_counter-- > 0));

        if ($response !== FALSE)
        {
            $result = array();
            foreach ($response->ticket as $ticket)
            {
                $result['ticket_id'] = (string) $ticket['id'];
                $result['department_id'] = (string) $ticket->departmentid;
                $result['status_id'] = (string) $ticket->statusid;
                $result['priority_id'] = (string) $ticket->priorityid;
                $result['user_id'] = (string) $ticket->userid;
                $result['fullname'] = (string) $ticket->fullname;
                $result['email'] = (string) $ticket->email;
                $result['subject'] = (string) $ticket->subject;
                $result['contents'] = (string) $ticket->posts->post[0]->contents;

                $ticket_id = (string) $ticket['id'];
                $ticket_post_id = (string) $ticket->posts->post[0]->ticketpostid;
                $department_id = (string) $ticket->departmentid;

                $response = $this->update_ticket_custom_field($ticket_id, $ticket_post_id, $department_id, $data, $file);

                if (($response !== FALSE) && (!is_object($response)))
                {
                    $result['update_custom_field'] = $this->get_rest_error();
                }
                else
                {
                    $result['update_custom_field'] = TRUE;
                }
            }

            return $result;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_ticket_custom_field($ticket_id)
    {
        $rest_controller = 'Tickets/TicketCustomField/' . $ticket_id;
        $rest_command = Kayako_service::REST_GET;

        return $this->run_rest($rest_controller, $rest_command);
    }

    public function create_ticket_attachment($ticket_id, $ticket_post_id, $file_name, $file_server_path)
    {
        $rest_controller = 'Tickets/TicketAttachment';
        $rest_command = Kayako_service::REST_POST;

        if ($ticket_id && $ticket_post_id && $file_name && $file_server_path && is_file($file_server_path))
        {
            $post_data = array('ticketid' => $ticket_id,
                               'ticketpostid' => $ticket_post_id,
                               'filename' => $file_name,
                               'contents' => base64_encode(file_get_contents($file_server_path))
                              );

            $response = $this->run_rest($rest_controller, $rest_command, $post_data);
            if ($response !== FALSE)
            {
                return $response;
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
    }

    public function access_help_desk_website($url)
    {
        $http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_REFERER, $http . Kayako_service::HELP_DESK_URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        $url = $http . Kayako_service::HELP_DESK_URL . $url;
        curl_setopt($curl, CURLOPT_URL, $url);

        return curl_exec($curl);
    }

    public function get_help_desk_url($url)
    {
        //$http = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://');
        $http = 'http://';
        $url = $http . Kayako_service::HELP_DESK_URL . $url;

        return $url;
    }

    public function get_language()
    {
        return $this->lang_id;
    }

    public function set_language($lang_id)
    {
        $this->lang_id = $lang_id;
        $this->set_template_group_id();
    }

    public function get_template_group_id()
    {
        return $this->template_group_id;
    }

    public function set_template_group_id()
    {
        $this->template_group_id = $this->get_template_group_lang_mapping();
    }

    ///////////////////////////////////////////////////////
    // Include hardcode value in the following functions
    ///////////////////////////////////////////////////////
    public function get_template_group_lang_mapping()
    {
        switch (strtoupper($this->lang_id))
        {
            case 'EN' : $template_group_id = 2; break;
            case 'FR' : $template_group_id = 4; break;
            case 'ES' : $template_group_id = 7; break;
            case 'IT' : $template_group_id = 8; break;
            case 'RU' : $template_group_id = 9; break;

            default   : $template_group_id = 2;
        }

        return $template_group_id;
    }

    public function get_question_department_mapping($question_id, $default_department_id, $extra_rule=array())
    {
        switch ($question_id)
        {
            // EN
            case '60'  : $department_id = '17';  break;
            case '61'  : $department_id = '18';  break;
            case '62'  : $department_id = '20';  break;
            case '63'  : $department_id = '19';  break;
            case '65'  : $department_id = '21';  break;
            case '66'  : $department_id = '22';  break;
            case '67'  : $department_id = '23';  break;
            case '69'  : $department_id = '16';  break;
            case '70'  : $department_id = '26';  break;
            case '71'  : $department_id = '11';  break;
            case '72'  : $department_id = '25';  break;
            case '73'  : $department_id = '27';  break;
            case '74'  : $department_id = '27';  break;
            case '75'  : $department_id = '9';   break;
            case '92'  : $department_id = '58';  break;
            case '104' : $department_id = '59';  break;
            case '105' : $department_id = '60';  break;
            case '106' : $department_id = '61';  break;
            case '107' : $department_id = '62';  break;
            case '108' : $department_id = '63';  break;
            case '110' : $department_id = '64';  break;
            case '131' : $department_id = '90';  break;

            // PH
            case '133' : $department_id = '88';  break;

            // FR
            case '76'  : $department_id = '105'; break;
            case '77'  : $department_id = '94';  break;
            case '78'  : $department_id = '95';  break;
            case '79'  : $department_id = '96';  break;
            case '80'  : $department_id = '70';  break;
            case '81'  : $department_id = '97';  break;
            case '82'  : $department_id = '94';  break;
            case '83'  : $department_id = '98';  break;
            case '84'  : $department_id = '99';  break;
            case '85'  : $department_id = '94';  break;
            case '87'  : $department_id = '103'; break;
            case '88'  : $department_id = '101'; break;
            case '89'  : $department_id = '102'; break;
            case '90'  : $department_id = '103'; break;
            case '91'  : $department_id = '104'; break;
            case '132' : $department_id = '92';  break;
            case '134' : $department_id = '93';  break;
            case '157' : $department_id = '173'; break;

            // ES
            case '111' : $department_id = '108'; break;
            case '112' : $department_id = '109'; break;
            case '113' : $department_id = '110'; break;
            case '114' : $department_id = '111'; break;
            case '115' : $department_id = '112'; break;
            case '116' : $department_id = '113'; break;
            case '117' : $department_id = '114'; break;
            case '118' : $department_id = '115'; break;
            case '119' : $department_id = '75';  break;
            case '120' : $department_id = '116'; break;
            case '121' : $department_id = '117'; break;
            case '122' : $department_id = '118'; break;
            case '123' : $department_id = '119'; break;
            case '124' : $department_id = '120'; break;
            case '125' : $department_id = '121'; break;
            case '126' : $department_id = '122'; break;
            case '127' : $department_id = '123'; break;
            case '128' : $department_id = '123'; break;
            case '129' : $department_id = '124'; break;

            // IT
            case '138' : $department_id = '149'; break;
            case '139' : $department_id = '151'; break;
            case '140' : $department_id = '145'; break;
            case '141' : $department_id = '150'; break;
            case '142' : $department_id = '152'; break;
            case '143' : $department_id = '154'; break;
            case '144' : $department_id = '138'; break;
            case '145' : $department_id = '158'; break;
            case '146' : $department_id = '156'; break;
            case '147' : $department_id = '155'; break;
            case '148' : $department_id = '159'; break;
            case '149' : $department_id = '160'; break;
            case '150' : $department_id = '161'; break;
            case '151' : $department_id = '166'; break;
            case '152' : $department_id = '165'; break;
            case '153' : $department_id = '167'; break;
            case '154' : $department_id = '168'; break;
            case '155' : $department_id = '168'; break;
            case '156' : $department_id = '169'; break;

            // RU
            case '158' : $department_id = '181'; break;
            case '159' : $department_id = '182'; break;
            case '160' : $department_id = '183'; break;
            case '161' : $department_id = '184'; break;
            case '162' : $department_id = '185'; break;
            case '163' : $department_id = '186'; break;
            case '164' : $department_id = '187'; break;
            case '165' : $department_id = '188'; break;
            case '166' : $department_id = '189'; break;
            case '167' : $department_id = '190'; break;
            case '168' : $department_id = '191'; break;
            case '169' : $department_id = '192'; break;
            case '170' : $department_id = '193'; break;
            case '171' : $department_id = '194'; break;
            case '172' : $department_id = '195'; break;
            case '173' : $department_id = '196'; break;
            case '174' : $department_id = '198'; break;
            case '175' : $department_id = '197'; break;
            case '176' : $department_id = '215'; break;

            // PL
            case '177' : $department_id = '221'; break;
            case '178' : $department_id = '222'; break;
            case '179' : $department_id = '223'; break;
            case '180' : $department_id = '224'; break;
//          case '181' : $department_id = '62'; break;
            case '182' : $department_id = '225'; break;
            case '183' : $department_id = '226'; break;
            case '184' : $department_id = '135'; break;
            case '185' : $department_id = '227'; break;
            case '186' : $department_id = '228'; break;
            case '187' : $department_id = '229'; break;
            case '188' : $department_id = '230'; break;
            case '189' : $department_id = '231'; break;
            case '190' : $department_id = '232'; break;

            case '191' : $department_id = '233'; break;
            case '192' : $department_id = '234'; break;
            case '193' : $department_id = '235'; break;
            case '194' : $department_id = '237'; break;
            case '195' : $department_id = '236'; break;
            case '196' : $department_id = '238'; break;

            // Bulk (EN)
            case '135' : $department_id = '90';  break;

            // Bulk (FR)
            case '136' : $department_id = '173'; break;

            // Bulk (ES)
            case '137' : $department_id = '174'; break;

            default    : if (isset($default_department_id))
                             $department_id = $default_department_id;
                         else
                             $department_id = $this->get_department_id();
        }

        // Due to too many OOS orders, Rachel requests that all the enquiries without attachment (should be OOS enquiry, and should be handled by CS) go to other department
        if (($question_id == '60') || ($question_id == '78'))
        {
            if (isset($extra_rule['hasAttachment']))
            {
                if ($question_id == '60')
                {
                    $department_id = '17';
                }
                else
                {
                    $department_id = '95';
                }
            }
            else
            {
                if ($question_id == '60')
                {
                    $department_id = '9';
                }
                else
                {
                    $department_id = '94';
                }
            }
        }

        return $department_id;
    }

    public function get_custom_field_question_mapping($question_id)
    {
        switch ($question_id)
        {
            // Pre-Sales
            case '69'  :
            case '104' :
            case '105' :
            case '106' :
            case '107' :
            case '108' :
            case '131' : $question_custom_field_id = 'mkd72avtj7rz'; break;

            // Client Support
            case '60'  :
            case '61'  :
            case '62'  :
            case '63'  :
            case '65'  :
            case '66'  :
            case '67'  :
            case '75'  :
            case '110' : $question_custom_field_id = 'ich5q5tjyhzg'; break;

            // Returns
            case '70'  :
            case '71'  :
            case '72'  :
            case '73'  :
            case '74'  :
            case '92'  : $question_custom_field_id = '3erqzmrdx24n'; break;

            // Pre-Sales (FR)
            case '76'  :
            case '132' :
            case '134' : $question_custom_field_id = '2yin2mln4t6v'; break;

            // Client Support (FR)
            case '77'  :
            case '78'  :
            case '79'  :
            case '80'  :
            case '81'  :
            case '82'  :
            case '83'  :
            case '84'  :
            case '85'  : $question_custom_field_id = '9qwztlna3a3i'; break;

            // Returns (FR)
            case '87'  :
            case '88'  :
            case '89'  :
            case '90'  :
            case '91'  : $question_custom_field_id = '8tj3z4t3znkg'; break;

            // Pre-Sales (ES)
            case '111' :
            case '112' :
            case '113' :
            case '114' :
            case '115' : $question_custom_field_id = 'vihpfoocyz38'; break;

            // Client Support (ES)
            case '116' :
            case '117' :
            case '118' :
            case '119' :
            case '120' :
            case '121' :
            case '122' :
            case '123' : $question_custom_field_id = 'j590lpei54hi'; break;

            // Returns (ES)
            case '124' :
            case '125' :
            case '126' :
            case '127' :
            case '128' :
            case '129' : $question_custom_field_id = 'la5k8pfkjezj'; break;

            // General Enquiry (PH)
            case '133' : $question_custom_field_id = 'dniq5x2mnulp'; break;

            // Bulk Sales (EN)
            case '135' : $question_custom_field_id = '5d5leff526hu'; break;

            // Bulk Sales (FR)
            case '136' : $question_custom_field_id = '01zt6rcfns0w'; break;

            // Bulk Sales (ES)
            case '137' : $question_custom_field_id = 'l4jcjqfqid33'; break;

            // Pre-Sales (IT)
            case '138' :
            case '139' :
            case '140' :
            case '141' :
            case '142' : $question_custom_field_id = 'i7oooiuzvrtw'; break;

            // Client Support (IT)
            case '143' :
            case '144' :
            case '145' :
            case '146' :
            case '147' :
            case '148' :
            case '149' :
            case '150' : $question_custom_field_id = '2oy897kuzawy'; break;

            // Returns (IT)
            case '151' :
            case '152' :
            case '153' :
            case '154' :
            case '155' :
            case '156' : $question_custom_field_id = '84m2y6rndg7c'; break;

            // Pre-Sales (RU)
            case '158' :
            case '159' :
            case '160' :
            case '161' :
            case '162' : $question_custom_field_id = '0lffao32ic5q'; break;

            // Client Support (RU)
            case '163' :
            case '164' :
            case '165' :
            case '166' :
            case '167' :
            case '168' :
            case '169' :
            case '170' : $question_custom_field_id = 'nasv474h57xd'; break;

            // Returns (RU)
            case '171' :
            case '172' :
            case '173' :
            case '174' :
            case '175' :
            case '176' : $question_custom_field_id = 'v6g4qvvvg7gx'; break;

            // Pre-Sales (PL)
            case '178' :
            case '178' :
            case '179' :
            case '180' :
            case '182' : $question_custom_field_id = 'omnb2i9ir2yk'; break;

            // Client Support (PL)
            case '183' :
            case '184' :
            case '185' :
            case '186' :
            case '187' :
            case '188' :
            case '189' :
            case '190' : $question_custom_field_id = 'voqvabpn6e5m'; break;

            // Returns (PL)
            case '191' :
            case '192' :
            case '193' :
            case '194' :
            case '195' :
            case '196' : $question_custom_field_id = 'k3iizo92tdje'; break;

            // Should not enter this case
            default : $question_custom_field_id = '';
        }

        return $question_custom_field_id;
    }

    public function update_ticket_custom_field($ticket_id, $ticket_post_id, $department_id, $data, $file)
    {
        $rest_controller = 'Tickets/TicketCustomField/' . $ticket_id;
        $rest_command = Kayako_service::REST_POST;

        if ($ticket_id && $ticket_post_id && $department_id && (isset($data['fullname']) || isset($data['email']) || isset($data['phone']) || isset($data['order_number']) || isset($data['question'])))
        {
            $question_custom_field_id = $this->get_custom_field_question_mapping($data['question']);

            $attachment_id = array();
            for ($file_counter = 1; $file_counter <= 2; $file_counter++)
            {
                $response = $this->create_ticket_attachment($ticket_id, $ticket_post_id, $file['attachment' . $file_counter]['name'], $file['attachment' . $file_counter]['tmp_name']);

                if ($response === FALSE)
                {
                    $attachment_id[$file_counter - 1] = '';
                }
                else
                {
                    $attachment_id[$file_counter - 1] = (string) $response->attachment->id;
                }
            }

            $file_counter = 0;
            $post_data = array('okyc3h0syejy' => $data['fullname'],  // Name
                               '5iu3dcfhbuzf' => $data['email'],  // Email Address
                               'xjaxqtecy3ky' => $data['phone'],  // Phone Number
                               'qvzvkjasizap' => $data['order_number'],  // Order Number
                               '8kgr9fdmlzzp' => $attachment_id[$file_counter++],  // Screen Capture File 1
                               'yu1nal6rn4sr' => $attachment_id[$file_counter++],  // Screen Capture File 2
                               '71vdfg82az1o' => $data['item_country'],  // Item or Country
                               'w432vwgs2cdq' => $data['country'], //country
                               $question_custom_field_id => $data['question']  // Question
                              );

            $response = $this->run_rest($rest_controller, $rest_command, $post_data);

            if ($response !== FALSE)
            {
                return $response;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return TRUE;
        }
    }
}

/* End of file Kayako_service.php */
/* Location: ./system/application/libraries/service/Kayako_service.php */