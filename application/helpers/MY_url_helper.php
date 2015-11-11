<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function validate_lang_country_input($input)
{
    if (!preg_match("/^[a-z]{2}_[A-Za-z]{2}$/", $input))
        return false;
    else
        return $input;
}

function uri_string_without_lang_country($router)
{
    $class_and_method = $router->class . "/" . $router->method;
    $current_url = current_url();
    list(, $controller_parameter) = explode($class_and_method, $current_url);
//		var_dump($controller_parameter);
    if (!empty($controller_parameter))
        return $class_and_method . $controller_parameter;
    else
        return $class_and_method;
}

function lang_part()
{
    $lang_part = "";
    list(, $language_country_id) = explode('/', $_SERVER['REQUEST_URI']);
//		print $language_country_id;
    if (!empty($language_country_id)) {
        if (validate_lang_country_input($language_country_id)) {
            $lang_part = "/" . $language_country_id . "/";
            $CI->config->config['lang_country'] = $language_country_id;
        }
    }
    return $lang_part;
}

// function base_url($need_lang_part=TRUE)
// {
// 	$CI =& get_instance();
// 	$base = $CI->config->slash_item('base_url');
// 	// print $base . "<br>";
// 	// var_dump($CI->config->config['lang_country']);
// 	// var_dump($base . $lang_part);
// 	if ($need_lang_part)
// 	{
// 		$url = str_ireplace("//", "/", $base . lang_part());
// 		$url = str_ireplace(":/", "://", $url);
// 	}
// 	else
// 	{
// 		$url = $base;
// 	}

// 	return $url;
// }

function cdn_purge($url)
{
    $account = "8AA1";
    $token = "557c0d62-eadd-47b2-b20b-b12dda881487";

    if (strpos($url, "http://") === false) {
        $url = "http://cdn.valuebasket.com/808AA1/vb/" . $url;
    }

    $content = array
    (
        "MediaPath" => $url, #$_GET['url'],#"http://cdn.valuebasket.com/808AA1/vb//images/product/12099-AA-NA_2858_l.jpg",
        "MediaType" => "8"
    );

    $update_json = json_encode($content);

    $header = array
    (
        "Authorization: TOK:$token",
        "Accept: application/json",
        "Content-Type: application/json",
        'Content-Length: ' . strlen($update_json)
    );

    $url = "https://api.edgecast.com/v2/mcc/customers/$account/edge/purge";

    $chlead = curl_init();
    curl_setopt($chlead, CURLOPT_URL, $url);
    curl_setopt($chlead, CURLOPT_USERAGENT, 'SugarConnector/1.4');
    curl_setopt($chlead, CURLOPT_HTTPHEADER, $header);
    curl_setopt($chlead, CURLOPT_VERBOSE, 1);
    curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chlead, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($chlead, CURLOPT_POSTFIELDS, $update_json);
    curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);

    $chleadresult = curl_exec($chlead);

    $httpCode = curl_getinfo($chlead, CURLINFO_HTTP_CODE);

    $chleadapierr = curl_errno($chlead);
    $chleaderrmsg = curl_error($chlead);
    curl_close($chlead);

    if ($httpCode != "200") return false;
    return true;

    mail('tslau@eservicesgroup.net', "[VBCDN] $url", "$httpCode<br>\r\n$chleadresult<br>\r\n$chleadapierr<br>\r\n$chleaderrmsg<br>\r\n");

    if ($httpCode != "200") {
        echo "FAIL";# {$_GET['url']} failed!<br>";
        var_dump($httpCode);
        var_dump($chleadresult);
        var_dump($chleadapierr);
        var_dump($chleaderrmsg);
    } else {
        echo "PASS";
    }
}

function base_cdn_url()
{
    # replace the following host if we want to switch to another CDN
    # cdn_host should always end with /
    $cdn_host = "wac.8aa1.edgecastcdn.net/808AA1/vb";
    $support_https = false;

    $cdn_host = "cdn.valuebasket.com/808AA1/vb";
    $support_https = true;

    $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");

    if (!$support_https && $base_url == "https")
        $base_url = "";    # our CDN does not support https, too bad :(
    else {

        $base_url .= "://" . $cdn_host;
        $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
    }
    return $base_url;
}

function add_css_helper($css_path, $need_path_only = FALSE, $media = "screen, print")
{
    $new_css_path = str_replace('.css', '', $css_path);
    $new_css_path = "/" . $new_css_path;
    $check_file = PUBLIC_HTML_PATH . $new_css_path . "_" . $_SESSION["lang_id"] . ".css";
    if (file_exists($check_file))
        $return_path = $new_css_path . "_" . $_SESSION["lang_id"] . ".css";
    else
        $return_path = $new_css_path . ".css";
    if ($need_path_only)
        return substr($return_path, 1, (strlen($return_path) - 1));
    else
        return "<link href=\"" . $return_path . "\" rel=\"stylesheet\" type=\"text/css\" media=\"" . $media . "\" />\n";
}

function check_app_feature_access_right($app_id, $feature_name)
{
    if (isset($_SESSION['user']['app_feature'][$app_id]))
        return in_array($feature_name, $_SESSION['user']['app_feature'][$app_id]);
    else
        return false;
}
