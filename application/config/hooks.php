<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

if (!defined('ENTRYPOINT') OR (defined('ENTRYPOINT') && (ENTRYPOINT !== 'ADMINCENTRE'))) {
    $hook['pre_system'][] = array(
        'class' => 'DomainSelection',
        'function' => 'checkDomain',
        'filename' => 'DomainSelection.php',
        'filepath' => 'hooks'
    );
/*
    $hook['pre_controller'][] = array(
        'class' => 'LoadSiteParameter',
        'function' => 'initSite',
        'filename' => 'LoadSiteParameter.php',
        'filepath' => 'hooks',
    );
*/
    //
    // $hook['pre_controller'][] = array(
    //     'class' => 'Currency',
    //     'function' => 'load_currency',
    //     'filename' => 'Currency.php',
    //     'filepath' => 'hooks',
    // );
    //
    // $hook['pre_controller'][] = array(
    //     'class' => 'Lang',
    //     'function' => 'update_lang_id',
    //     'filename' => 'Lang.php',
    //     'filepath' => 'hooks',
    // );
}
