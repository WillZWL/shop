<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> MyController/index
|		my-controller/my-method	-> MyController/myMethod
*/
$route['default_controller'] = 'redirect_controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = true;

/*
$valid_lang_arr = $this->config->item("valid_lang");

foreach ($valid_lang_arr as $value) {
    $regex = $value . "_(:any)";
    $segment_path = "(:any)/(:any)/(:any)/(:any)/(:any)";
    $segment_path_value = "$2/$3/$4/$5/$6";
    $segment_path_arr = explode('/', $segment_path);
    for ($segment_count = 0, $len = count($segment_path_arr); $segment_count < $len; $segment_count++) {
        $inside_regex = $regex . "/" . substr($segment_path, 0, (strlen($segment_path) - ($segment_count * 7)));
        $index_segment_path_value = substr($segment_path_value, 0, (strlen($segment_path_value) - ($segment_count * 3)));
        $route[$inside_regex] = $index_segment_path_value;
    }
    $route[$regex] = "redirect_controller";
}

if ($_SERVER["HTTP_HOST"] == "v2.valuebasket.com:8000")
{
    $route["cat/(:any)/(:any)"] = "digitaldiscount/cat/view/1";
}
*/