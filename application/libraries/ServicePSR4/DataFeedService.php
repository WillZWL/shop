<?php
namespace ESG\Panther\Service;
use ESG\Panther\Dao\FtpInfoDao;
use ESG\Panther\Dao\ScheduleJobDao;
use ESG\Panther\Dao\TransmissionLogDao;

abstract class DataFeedService extends BaseService
{
    protected $lang;
    protected $delimiter;

    public function __construct()
    {
        parent::__construct();
        $this->productService = new ProductService;
        $this->soService = new SoService;
        $this->dataExchangeService = new DataExchangeService;
        $this->configService = new ContextConfigService;
        $this->loadSiteParameterService = new LoadSiteParameterService;
        $this->ftpConnector = new FtpConnector;
        $this->ftpInfoDao = new FtpInfoDao;
        $this->scheduleJobDao = new ScheduleJobDao;
        $this->transmissionLogDao = new TransmissionLogDao;

        $this->vo2XmlMapping = $this->getDefaultVo2XmlMapping();
        $this->xml2CsvMapping = $this->getDefaultXml2CsvMapping();
    }

    abstract protected function getDefaultVo2XmlMapping();
    abstract protected function getDefaultXml2CsvMapping();
    abstract protected function genDataList($where = array(), $option = array());

    public function setOutputDelimiter($str = '')
    {
        if (is_object($str)) {
            return; // Nothing should be set.
        }

        $this->delimiter = $str;
        settype($this->delimiter, 'string');
    }

    public function genDataFeed()
    {
        $data_feed = $this->getDataFeed();
    }

    public function getDataFeed($first_line_headling = TRUE, $where = array(), $option = array())
    {
        $arr = $this->genDataList($where, $option);

        if (!$arr) {
            return;
        }

        $new_list = array();

        foreach ($arr as $row) {
            $new_list[] = $this->processDataRow($row);
        }

        $content = $this->convert($new_list, $first_line_headling);

        return $content;
    }

    public function processDataRow($data = NULL)
    {
        if (!is_object($data)) {
            return NULL;
        }

        return $data;
    }

    public function convert($list = array(), $first_line_headling = TRUE)
    {
        $out_xml = new Vo_to_xml($list, $this->getVo2XmlMapping());
        $out_csv = new Xml_to_csv("", $this->getXml2CsvMapping(), $first_line_headling, $this->getOutputDelimiter());

        return $this->dataExchangeService->convert($out_xml, $out_csv);
    }

    public function getVo2XmlMapping()
    {
        return $this->vo2XmlMapping;
    }

    public function setVo2XmlMapping($mapping = '')
    {
        $this->vo2XmlMapping = $mapping;
    }

    public function getXml2CsvMapping()
    {
        return $this->xml2CsvMapping;
    }

    public function setXml2CsvMapping($mapping = '')
    {
        $this->xml2CsvMapping = $mapping;
    }

    public function getOutputDelimiter()
    {
        return $this->delimiter;
    }

    function ftpFeeds($local_file, $remote_file, $ftp_name)
    {
        $encrypt = new \CI_Encrypt();
        $ftp = $this->getFtp();
        if ($ftp_obj = $this->get_fi_dao()->get(array("name" => $ftp_name))) {
            $ftp->set_remote_site($server = $ftp_obj->get_server());
            $ftp->set_username($ftp_obj->get_username());
            $ftp->set_password($encrypt->decode($ftp_obj->get_password()));
            $ftp->set_port($ftp_obj->get_port());
            $ftp->set_is_passive($ftp_obj->get_pasv());
            $tlog_dao = $this->get_tlog_dao();
            $tlog_vo = $tlog_dao->get();

            if ($ftp->connect() !== FALSE) {
                if ($ftp->login() !== FALSE) {
                    if ($ftp->putfile($local_file, $remote_file)) {
                        $this->updateScheduleJobRecord();
                    } else {
                        $this->logErrorMessage("file can not be uploaded");
                        return FALSE;
                    }
                } else {
                    $this->logErrorMessage("unable_to_login_to_server '" . $server . "'");
                    return FALSE;
                }
            } else {
                $this->logErrorMessage("connot_connect_to_server '" . $server . "'");
                return FALSE;
            }
        } else {
            $this->logErrorMessage("ftp_info not found '" . $server . "'");
            return FALSE;
        }
    }

    public function getFtp()
    {
        return $this->ftp;
    }

    public function setFtp($value)
    {
        $this->ftp = $value;
    }

    public function updateScheduleJobRecord()
    {
        $update_time = date("Y-m-d H:i:s");
        if ($sjObj = $this->scheduleJobDao->get(["id" => $this->getSjId()])) {
            $sjObj->setLastAccessTime($update_time);
            if ($this->scheduleJobDao->update($sjObj) === FALSE) {
                $this->logErrorMessage("Schedule Job table Update Error\n" . $this->scheduleJobDao->db->last_query());
                return FALSE;
            }
        } else {
            $sjObj = $this->scheduleJobDao->get();
            $sjObj->setId($this->getSjId());
            $sjObj->setName($this->getSjName());
            $sjObj->setLastAccessTime($update_time);
            $sjObj->set_status(1);
            $sjObj->set_create_on($update_time);
            $sjObj->set_create_at('localhost');
            $sjObj->set_create_by('system');
            $sjObj->set_modify_on($update_time);
            $sjObj->set_modify_at('localhost');
            $sjObj->set_modify_by('system');
            if (!$this->scheduleJobDao->insert($sjObj)) {
                $this->logErrorMessage("Schedule Job table Insert Error\n" . $this->scheduleJobDao->db->last_query());
                return FALSE;
            }
        }
    }

    public function logErrorMessage($message)
    {
        $tlogVo = $this->transmissionLogDao->get();
        $tlogObj = clone $tlogVo;
        $tlogObj->set_func_name($this->getSjId());
        $tlogObj->set_message($message);
        $this->transmissionLogDao->insert($tlogObj);

        $title = empty($this->id) ? "Data Feed" : $this->id;

        mail($this->getContactEmail(), '[Panther]' . $title . ' error', $message);
    }

    // get last access time from scheduled job table

    public function getContactEmail()
    {
        return 'itsupport@eservicesgroup.net';
    }

    public function errorHandle($subject = '', $msg = '', $is_dead = false)
    {
        //echo $msg;
        $subject = $subject ? $subject : 'Data Feed Failed';

        if ($subject) {
            mail($this->getContactEmail(), $subject,
                $msg, 'From: itsupport@eservicesgroup.net');
        }

        if ($is_dead) {
            exit;
        }
    }

    public function createFolder($folder)
    {
		if (!file_exists($folder))
		{
			mkdir($folder, 0775, true);
		}
    }

    public function delDir($dir)
    {
        if (!is_readable($dir)) {
            is_file($dir) or mkdir($dir, 0777);
        }
        $dir_arr = scandir($dir);
        foreach ($dir_arr as $key => $val) {
            if ($val == '.' || $val == '..') {
            } else {
                if (is_dir($dir . '/' . $val)) {
                    if (@rmdir($dir . '/' . $val) == 'true') {
                    } else
                        $this->delDir($dir . '/' . $val);
                } else
                    unlink($dir . '/' . $val);
            }
        }
    }

    protected function loadLanguage($lang_id = 'en')
    {
        $this->lang = [];
        $this->lang["save"] = _("Save ");
        $this->lang["in_stock"] = _("In Stock");
        $this->lang["out_stock"] = _("Out Of Stock");
        $this->lang["pre_order"] = _("Pre-Order");
        $this->lang["arriving"] = _("Available Soon");
    }

    protected function getAffiliateIdPrefix()
    {
        return "";
    }
}


