<?php

class BannerManagement extends MY_Controller
{
    private $appId = "MKT0001";
    private $lang_id = "en";
    private $type_list = [ 1 => 'Home Page', 2 => 'Category Page', 3 => 'Product Page'];
    private $home_location_list = [ 1 => 'Top Banner', 2 => 'Collections Left', 3 => 'Collections Right'];
    const BASE_UPLOAD_PATH = '../public_html';

    public function __construct()
    {
        parent::__construct();
    }

    public function index($platform_id = '', $type = '', $location = '')
    {
        include_once APPPATH . "language/" . $this->getAppId() . "01_" . $this->getLangId() . ".php";
        $data["lang"] = $lang;
        $data['platform_list'] = $this->sc['SellingPlatform']->getDao('SellingPlatform')->getList(['status' => 1], ['limit' => -1]);
        $data['type_list'] = $this->getTypeList();
        $data['home_location_list'] = $this->getHomeLocationList();
        $data['platform_id'] = $platform_id;
        if (!$location) {
            $location = $this->input->get('location');
        }
        $data['location'] = $location;
        $data['type'] = $type;

        $data['breadcrumb'] = $platform_id .'  >  '. $data['type_list'][$type] .'  >  '. $data['home_location_list'][$location];
        $total_nums = $this->sc['Banner']->getDao('Banner')->getNumRows(['platform_id' => $platform_id, 'type' => $type, 'location' => $location]);
        $data['nums'] = $total_nums;

        $banner_list = $this->sc['Banner']->getDao('Banner')->getList(['platform_id' => $platform_id, 'type' => $type, 'location' => $location], ['limit' => -1]);

        $data['banner_list'] = $banner_list;
        $this->load->view('marketing/banner_manage/index', $data);
    }

    public function handle()
    {
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        @set_time_limit(5 * 60);
        usleep(5000);

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }

        if ( !empty($_REQUEST[ 'debug' ]) ) {
            $random = rand(0, intval($_REQUEST[ 'debug' ]) );
            if ( $random === 0 ) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }

        $platform_id = $_REQUEST["platform_id"];
        $banner_type = $_REQUEST["banner_type"];
        $location = $_REQUEST["location"];
        $link = $_REQUEST["link"];
        $target_type = $_REQUEST["target_type"];
        $image_alt = $_REQUEST["image_alt"];

        $targetDir = self::BASE_UPLOAD_PATH. DIRECTORY_SEPARATOR .'images/banner_tmp';
        $banner_img_path = $this->sc['ContextConfig']->valueOf('banner_img_path');
        $bannerDir = self::BASE_UPLOAD_PATH .DIRECTORY_SEPARATOR. $banner_img_path;

        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Create target dir
        if (!file_exists($bannerDir .DIRECTORY_SEPARATOR. $platform_id)) {
            mkdir($bannerDir .DIRECTORY_SEPARATOR. $platform_id);
            chmod($bannerDir .DIRECTORY_SEPARATOR. $platform_id, 0775);

            if (!file_exists($bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type )) {
                mkdir($bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type);
                chmod($bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type, 0775);

                if (!file_exists($bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type .DIRECTORY_SEPARATOR. $location )) {
                    mkdir($bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type .DIRECTORY_SEPARATOR. $location);
                    chmod($bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type .DIRECTORY_SEPARATOR. $location, 0775);
                }
            }
        }

        $uploadDir = $bannerDir .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type .DIRECTORY_SEPARATOR. $location;

        // var_dump($uploadDir);
        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }

        $md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $md5File = $md5File ? $md5File : array();

        if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
            die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        $uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for( $index = 0; $index < $chunks; $index++ ) {
            if ( !file_exists("{$filePath}_{$index}.part") ) {
                $done = false;
                break;
            }
        }
        if ( $done ) {
            if (!$out = @fopen($uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if ( flock($out, LOCK_EX) ) {
                for( $index = 0; $index < $chunks; $index++ ) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }

                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }

                flock($out, LOCK_UN);
            }
            @fclose($out);
        }
        // Return Success JSON-RPC response
        $file_path =  $banner_img_path .DIRECTORY_SEPARATOR. $platform_id .DIRECTORY_SEPARATOR. $banner_type .DIRECTORY_SEPARATOR. $location .DIRECTORY_SEPARATOR. $fileName;
        $total_nums = $this->sc['Banner']->getDao('Banner')->getNumRows(['platform_id' => $platform_id, 'type' => $banner_type, 'location' => $location]);
        $line_no = $total_nums + 1;
        $banner_obj = $this->sc['Banner']->getDao('Banner')->get();
        $banner_obj->setType($banner_type);
        $banner_obj->setLocation($location);
        $banner_obj->setPlatformId($platform_id);
        $banner_obj->setImage($file_path);
        $banner_obj->setImageAlt($image_alt);
        $banner_obj->setLink($link);
        $banner_obj->setTargetType($target_type);
        $banner_obj->setLineNo($line_no);

        $this->sc['Banner']->getDao('Banner')->insert($banner_obj);
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function getTypeList()
    {
        return $this->type_list;
    }

    public function getHomeLocationList()
    {
        return $this->home_location_list;
    }
}