<?php

$__gridclass = null;

function __processData($arrData=array())
{
    global $__gridclass;
    if ($__gridclass != null) return $__gridclass->process_row_data($arrData);
}

/* =========================================================================================
    READ ME
    This list contains things to look out for if you have trouble debugging.
    Feel free to add on.

    1. Always set primary table with alias (if using) ==> $objGrid->tabla("chargeback", "t");
    2. Always set the keyfield, usually the id. This is needed for updates ==> $objGrid->keyfield("id");
    3. If your keyfield contains "ID" in column header, don't use "ID" in another column header.
    4. Are any of your data fields NULL? This may cause problem for PHPMyDataGrid to detect ID. Check
        your data with IFNULL(...).
    5. If you are passing in parameters via a form, always link your parameters with
        $this->import_param("tfa_$x"). This is to ensure parameters are passed on in searches.

========================================================================================= */
class gridbase
{
    protected $var = null;
    static $_gridid = 1;

    function __construct($title, $table = "")
    {
        global $_dbprefix;
        global $__gridclass;

        error_reporting(0);

        $__gridclass = $this;

        $gridid = $this->_gridid;
        $this->_gridid++;

        $this->var["_dbprefix"] = $_dbprefix;
        $this->var["gridid"]    = $gridid;
        $this->var["objGrid"]   = $this->create_grid($gridid);
        $this->var["is_root"]   = true;
        $this->var["title"]     = $title;
        $this->var["table"]     = $table;
        $this->var["where"]     = "";

         $this->var["objGrid"]->pathtoimages("/images/");

        // $grid = $this->var["objGrid"];
    }

    function get_DG_Header()
    {
        extract($this->var);
        $objGrid->pathtoimages("/images/");
        return set_DG_Header('/js/', '/css/', "/", "/skins/%s/icons/");
    }

    function index()
    {
        extract($this->var);

        if($_GET["debug"])
            $objGrid->debug = 1;
        $this->config($title, $table);
        return $this->execute();
    }

    protected function execute($local_override_search = false)
    {
        extract($this->var);

        $subheader = $this->build_header();

        if ($override_search || $local_override_search) $this->search_handler();

        $this->setup_columns();

        $executeaftergrid = "";
        if ($objGrid->isAjaxRequest()) $executeaftergrid = $this->ajax_handler();

        $this->execute_custom_sql();

        $linkparam = "";
        foreach($this->var["linkparam"] as $key=>$value)
            $linkparam .= "&$key=" . urlencode($value);

        $objGrid->linkparam($linkparam);
        // var_dump("Link param $linkparam");

        $gridcontent = $objGrid->grid();

        if ($objGrid->isAjaxRequest())
        {
            echo $gridcontent . $executeaftergrid;
            die();
        }

        return $this->render_page($gridcontent);
    }

    function render_page($gridcontent)
    {
        return $gridcontent;
    }

    function config($mytitle, $table)
    {
        extract($this->var);

        $objGrid->TituloGrid($mytitle);
        $objGrid->tabla($table);

        $objGrid->processData = "__processData";
        $objGrid->retcode = true;

        $objGrid->checkable();
        $objGrid->liquidTable = true;
        $objGrid->AjaxChanged('#900');

        // default button setup
        $objGrid->buttons(true,true,false,false,-1,"");

        # allow export
        $objGrid->export(true, true, true, true, true, 'P');
        $objGrid->csvSeparator = ",";

        $this->var["table"] = $table;
    }

    function build_header()
    {
    }

    function process_row_data($arrData = array())
    {
        foreach($arrData as $key=>$row)
        {
            #en: Prepare new field value
            // $row['created_at'] .= " · " . time_since(strtotime($row['created_at']));
            // // $row['updated_at'] .= " · " . time_since(strtotime($row['updated_at']));
            // $row['updated_at'] = time_since(strtotime($row['updated_at']));
            // $row['utccorrectasof'] = time_since(strtotime($row['utccorrectasof']));
            $arrTmpData[$key] = $row;
        };
        return $arrTmpData;
    }

    protected function get_param($param_name)
    {
        return $this->var["linkparam"][$param_name];
    }

    protected function set_param($param_name, $param_value)
    {
        $this->var["linkparam"][$param_name] = $param_value;
    }

    protected function import_param($param_name)
    {
        $param_value = (isset($_GET[$param_name])?$_GET[$param_name]:(isset($_POST[$param_name])?$_POST[$param_name]:''));
        $this->set_param($param_name, $param_value);
        return $param_value;
    }

    function link_parent($key, $link_title = "", $hidden = false)
    {
        extract($this->var);

        if ($link_title == "") $link_title = "Parent ID";

        $this->var["is_root"] = false;

        $parentid         = $objGrid->setMasterRelation($key);
        $objGrid->toolbar = false;
        if (!$hidden)
            $objGrid->FormatColumn($key, $link_title, "150", "200", 0, "5", "left", "text", $parentid);
        else
            $objGrid->FormatColumn($key, $link_title, "150", "200", 2, "5", "left", "text", $parentid);

        $this->var["parentid"] = $parentid;
        return $parentid;
    }

    function add_child($key, $link)
    {
        extract($this->var);
        $objGrid->setDetailsGrid($link, $key);
    }

    function ajax_handler()
    {
        extract ($this->var);

        $param = explode(";", $objGrid->getAjaxID());
        switch ($param[0])
        {
            case "add":
                // $strSQL = sprintf("INSERT INTO $table (`description`, `status_id`, `customer_id`) values ('Please enter event details', 1, '$userid')");
                // $arrData = $objGrid->SQL_query($strSQL);
                break;
        }
    }

    function search_handler()
    {
        extract($this->var);

        $s = $this->import_param("s");
        $f = $this->import_param("f");
        $match = $this->import_param("match");

        if ($f != "" and $s != "")
        {
            switch ($match)
            {
                case "exact":
                    $w = " and $f = '$s' ";
                    break;
                default:
                    $w = " and $f like '%$s%' ";
                    break;
            }
            $this->var["where"] .= $w;
        }

        # search interceptor
        if (isset($_POST['dg_schrstr' . $objGrid->dgGridID]))
        {
            // Detect if if search action is being executed
            $value = $_POST['dg_schrstr' . $objGrid->dgGridID]; // Get field value (you'll like to sanitize it to avoid SQL injection)
            $field = $_POST['dg_ss' . $objGrid->dgGridID];

            $tempv = str_replace ("%_"  , " ", $value);
            $tempv = str_replace (" "  , "%_", $tempv); $origv = $tempv;

            $_POST['dg_schrstr' . $objGrid->dgGridID] = $tempv;
            $_POST['dg_schrstr' . $objGrid->dgGridID] = "";    // Set the new value search as empty (to avoid conflicts)

            if ($origv <> "")
            {
                $pp = "";
                if ($prefix_table_to_search_field) $pp = "`$prefix_table_to_search_field`.";
                switch ($field)
                {
                    default:
                        $where .= " and ($pp$field like '%{$origv}%')";   // Define the new condition to get any value including the passed by user canon % 60D
                        break;
                }
                $endScript = "<script>DG_Slide('DG_srchDIV' + ac(),{duration:.2}).down();DG_svv('dg_schrstr' + ac(),'$value');</script>";
                echo $endScript;  // Execute above request (empty if no search was made)
            }
            $this->var["where"] .= $where;
        }
    }

    function setup_columns()
    {
    }

    function execute_custom_sql()
    {
    }

    private function create_grid($gridid)
    {
        $objGrid = new datagrid($gridfilename, $gridid);

        $str = realpath("../application/config/database.php");
        include $str;

        // var_dump($db['default']['hostname']); die();

        #es: Realizar la conexión con la base de datos
        #en: Connect with database
        $objGrid->conectadb($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

        #strangely, must do here too, otherwise ajax updates track editorid
        $objGrid->SQL_query("SET time_zone = '+08:00'");
        $objGrid->SQL_query("SET @thiseditorid = '" . $_SESSION['editorid'] . "';");
        $objGrid->SQL_query("set autocommit=1");

        $thiseditorid = $_SESSION['editorid'];
        #$objGrid->SQL_query("SET @thiseditorid = 'teik';");

        $objGrid ->TituloGrid($title);

        #en: Define allowed actions
        $objGrid->reload = true;
        $objGrid->toolbar = true;

        //  add, update, delete, check
        $objGrid->buttons(true,true,false,false,-1,"");

        $objGrid->strExportInline = true;
        $objGrid->strSearchInline = true;

        $objGrid->liquidTable = false;
        $objGrid->width = "100%";

        $objGrid->ajax("silent",1);
        $objGrid->friendlyHTML = true;

        $objGrid->saveaddnew = true;
        $objGrid->useCalendar(true);
        $objGrid->closeTags(true);  #xhtml compatibility

        #$objGrid->useRightClickMenu("class/phpMyMenu.inc.php");

        #en: Define amount of records to display per page
        $objGrid->datarows(10);

        switch (strtolower($_SESSION['editorid']))
        {
            case "jesslyn":
            case "teik":
            case "fiona":
            case "simon":
            case "wayne":
                $isadmin = true;
                break;
            default:
                $isadmin = false;
                break;
        }

        #$objGrid->setDetailsGrid("qv6_1.php", "skuid");
        #$mastersku = substr("000000" . $objGrid->setMasterRelation("skuid"),-12);

        $objGrid->sqlcharset = "utf8";
        $objGrid->charset = 'UTF-8';

        $objGrid->retcode = true;

        # DO NOT CLOSE THE PHP with ? >
        # Closing can result in accidental whitespaces causing problems for other files

        // var_dump($objGrid);
        return $objGrid;
    }

}

class gridpage extends gridbase
{
    function render_page($gridcontent)
    {
        extract($this->var);

        if ($is_root)
        {
            require_once("template.php");
        }
        else
            echo $gridcontent;
    }
}

?>
<?php
/**
 * Copyright (c) 2005-2012, Guru Sistemas and/or Gustavo Adolfo Arcila Trujillo
 * All rights reserved.
 * www.gurusistemas.com
 *
 * phpMyDataGrid Professional IS NOT FREE, may not be re-sold or redistributed as a single library.
 *
 * If you want to use phpMyDataGrid Professional on any of your projects, you Must purchase a license.
 *
 * You can buy the full source code or encoded version at http://www.gurusistemas.com/
 * also can try the donationware version, which can be downloaded from http://www.gurusistemas.com/
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  "AS IS"  AND ANY EXPRESS  OR  IMPLIED WARRANTIES, INCLUDING,
 * BUT NOT LIMITED TO,  THE IMPLIED WARRANTIES  OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT
 * SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,  INDIRECT,  INCIDENTAL, SPECIAL, EXEMPLARY,  OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF  USE, DATA, OR PROFITS;  OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * For more info, samples, tips, screenshots, help, contact, support, please visit phpMyDataGrid site
 * http://www.gurusistemas.com/
 */
define("DG_IsDelete", 3);
define("DG_IsInline", 4);
define("DG_IsAdding", 5);
define("DG_IsSaving", 6);
class datagrid
{
    var $methodForm = "POST";
    var $imgpath = "images/";
    var $ButtonWidth = 30;
    var $backtick = "`";
    var $salt = "salt&pepper";
    var $liquidTable = false;
    var $width = "100%";
    var $conditionEdit = "";
    var $conditionDelete = "";
    var $condition = "";
    var $conditionalStyle = "";
    var $cellCondition = array();
    var $cellStyle = array();
    var $decimalsep = ".";
    var $decimalDigits = 2;
    var $moneySign = '$';
    var $checkable = false;
    var $titulo = "";
    var $footer = "";
    var $poweredby = false;
    var $zebraLines = 1;
    var $retcode = false;
    var $showToOf = true;
    var $AllowChangeNumRows = true;
    var $dgAjaxChanged = "#000";
    var $search = "";
    var $bolCalendar = false;
    var $debug = false;
    var $keyfield = "";
    var $strMailErrors = "";
    var $bolShowErrors = false;
    var $logfile = "logs/phpMyDGlogError.txt";
    var $logSQLError = true;
    var $tablename = "";
    var $defaultdateformat = "dmy";
    var $defaultdateseparator = "/";
    var $actHeader = array();
    var $actFooter = array();
    var $csvSeparator = ",";
    var $charset = "ISO-8859-1";
    var $sqlcharset = "";
    var $sqlDataCoding = "";
    var $sql = "";
    var $sqlCount = "";
    var $show404image = false;
    var $uploadDirectory = "/";
    var $renameUploads = false;
    var $getMyOwnButtons = false;
    var $cssPrinter = "css/b-w-print.css";
    var $PDForientation = "P";
    var $PDFfont = "Arial";
    var $PDFfontsize = 7;
    var $PDFfill = array("R" => 192, "G" => 192, "B" => 192);
    var $PDFdraw = array("R" => 0, "G" => 0, "B" => 0);
    var $lngPath = "";
    var $addonClic = "DG_addrow();";
    var $addonClicMenu = "DG_addrow();";
    var $edtonClic = "DG_editrow(\"%s\",\"%s\");";
    var $edtonClicMenu = 'DM_editrow("\'+MM_parameters+\'");';
    var $delonClic = "DG_deleterow(\"%s\",\"%s\")";
    var $delonClicMenu = 'DM_deleterow("\'+MM_parameters+\'")';
    var $multiDeleteonClic = 'DG_delete_selection()';
    var $srconClic = "DG_showSearchBox()";
    var $srconClicMenu = "DG_showSearchBox()";
    var $vieonClic = "DG_viewrow(\"%s\",\"%s\");";
    var $vieonClicMenu = 'DM_viewrow("\'+MM_parameters+\'");';
    var $detonClic = "DG_viewdetails(\"%s\",\"%s\",\"\");";
    var $detonClicMenu = 'DM_viewdetails("\'+MM_parameters+\'");';
    var $exportTo = "DG_showExportBox()";
    var $uponClic = "DG_Do(10,\"%s\")";
    var $dnonClic = "DG_Do(11,\"%s\")";
    var $saveAddAction = "DG_doSaveAdd(";
    var $saveAction = "DG_doSave(";
    var $actionCloseDiv = "DG_closeDiv();";
    var $actionCloseUpl = 'DG_closeDiv("uplDiv%s");';
    var $afterAction = "";
    var $afterOnlineEdition = "";
    var $dgGridID = "";
    var $dgVersion = "Evolution Pro";
    var $objMenu;
    var $returnEntities = false;
    var $numerics = "0-1-2-3-4-double-float-integer-signed-count-percentage-promille";
    var $fieldsArray = array();
    var $arrGridData = array();
    var $sl = "";
    var $br = "";
    var $tb = "";
    var $BtnsColWidth = 0;
    var $orderArrows = true;
    var $useRightClickMenu = false;
    var $orderColName = "";
    var $orderExpr = "";
    var $tableWidth = 0;
    var $totalize = array();
    var $columns = 0;
    var $countRecords = 0;
    var $totalRecords = 0;
    var $pagination = "mixed";
    var $pgInTable = false;
    var $linksperpage = 3;
    var $maxRec = 20;
    var $btnColumn = -1;
    var $recno = 0;
    var $ajaxEditable = "";
    var $parameters = "";
    var $validations = array();
    var $FormName = "";
    var $doForm = false;
    var $calendarStr = "";
    var $where = "";
    var $groupby = "";
    var $having = "";
    var $LimitAdd = 0;
    var $arrSQLFld = array();
    var $errorSQL = false;
    var $allConditions = "";
    var $hasCalcs = false;
    var $hasChart = false;
    var $nullDateFormat = "0000-00-00";
    var $toolbar = false;
    var $nowindow = false;
    var $template = false;
    var $templatePath = "";
    var $btnOrder = "[E][V][D][Up][Dn]";
    var $typeOfClick = 1;
    var $extraParameters = array();
    var $addBtn = false;
    var $updBtn = false;
    var $delBtn = false;
    var $chkBtn = false;
    var $exportosheet = false;
    var $exportopdf = false;
    var $exportocsv = false;
    var $exportoxml = false;
    var $printer = false;
    var $allExp = false;
    var $elements_tinyMCE = array();
    var $tinyMCE_theme = "simple";
    var $tinyMCE_options = "";
    var $validImgExtensions = array("gif", "jpg", "jpeg", "png");
    var $phpExcelOutput = 'Excel2007';
    var $images = array('add' => 'add.gif', 'ajax' => 'ajax.gif', 'arrup' => 'arrup.gif', 'arrdn' => 'arrdn.gif', 'ASC' => 'asc.gif', 'ascending' => 'ascending.gif', 'blank' => 'blank.gif', 'cancel' => 'cancel.gif', 'calendar' => 'calendar.gif', 'close' => 'close.gif', 'csv' => 'csv.gif', 'dascending' => 'aascending.gif', 'ddescending' => 'ddescending.gif', 'DESC' => 'desc.gif', 'descending' => 'descending.gif', 'down' => 'down.gif', 'down_off' => 'down_off.gif', 'edit' => 'edit.gif', 'editrows' => 'editrows.gif', 'erase' => 'erase.gif', 'erasechk' => 'erase_selected.gif', 'export' => 'export.gif', 'minidown' => 'minidown.gif', 'miniup' => 'miniup.gif', 'noimage' => '404.gif', 'pdf' => 'pdf.gif', 'printer' => 'printer.gif', 'refresh' => 'refresh.gif', 'save' => 'save.gif', 'search' => 'search.gif', 'sheet' => 'excel.gif', 'up' => 'up.gif', 'upload' => 'upload.gif', 'up_off' => 'up_off.gif', 'view' => 'view.gif', 'xml' => 'xml.gif', 'first' => 'first.gif', 'last' => 'last.gif', 'first_off' => 'first_off.gif', 'last_off' => 'last_off.gif', 'all_pages' => 'all_pages.gif', 'this_page' => 'this_page.gif', 'selected_rows' => 'selected_rows.gif', 'cancel_search' => 'cancel_search.gif', 'dnarrow' => 'dnarrow.gif', 'options' => 'options.gif', 'node' => 'node.gif', 'closenode' => 'closenode.gif', 'bck_type' => 'background_files.gif', 'icn_pdf' => 'icn_pdf.jpg', 'icn_rar' => 'icn_rar.jpg', 'icn_zip' => 'icn_zip.jpg', 'icn_doc' => 'icn_doc.jpg', 'icn_xls' => 'icn_xls.jpg', 'icn_ppt' => 'icn_ppt.jpg');
    var $message = array('cancel' => 'Cancel', 'close' => 'Close', 'save' => 'Save', 'savenew' => 'Save & New', 'saving' => 'Saving . . .', 'loading' => 'Loading . . .', 'edit' => 'Edit', 'delete' => 'Delete', 'deletechk' => 'Delete selected', 'add' => 'New', 'view' => 'View', 'addRecord' => 'Add record', 'edtRecord' => 'Edit record', 'chkRecord' => 'View record', 'false' => 'No', 'true' => 'Yes', 'prev' => 'Previous', 'next' => 'Next', 'confirm' => 'Delete this record?', 'confirms' => 'Delete selected records?', 'search' => 'Search', 'resetSearch' => 'Reset Search', 'doublefield' => 'Duplicate field definition', 'norecords' => 'No records found', 'errcode' => 'Error in Data [Incorrect verification code]', 'noinsearch' => 'Field not available for search', 'noformdef' => 'To use "checkable" feature you must define a FORM name by using Form Function', 'cannotadd' => 'Can not add records to this grid', 'cannotedit' => 'Can not edit records in this grid', 'cannotsearch' => 'Can not make searchs in this grid', 'cannotdel' => 'Can not delete records in this grid', 'sqlerror' => 'SQL Error found in query:', 'errormsg' => 'Error message:', 'errorscript' => 'SQL Error in script:', 'display' => 'Displaying rows', 'to' => 'to', 'of' => 'of', 'page' => 'Page', 'searchby' => 'Search by', 'qsearch' => 'Quick search', 'calendar' => 'Calendar', 'sa' => 'Sort ascending', 'sd' => 'Sort descending', 'ssa' => 'Attach sort ascending', 'ssd' => 'Attach sort descending', 'exportButton' => 'Export', 'export' => 'Export to ...', 'sheet' => 'Export to datasheet', 'exporP' => 'Export this page', 'exporA' => 'Export all pages', 'exporS' => 'Export selected rows', 'pdf' => 'Export to PDF', 'csv' => 'Export to CSV', 'xml' => 'Export to XML', 'printer' => 'Printer Friendly', 'refresh' => 'Refresh', 'editrows' => 'Number of rows to display per page', 'months' => 'January,February,March,April,May,June,July,August,September,October,November,December', 'days' => 'Sunday,Monday,Tuednesday,Wednesday,Thursday,Friday,Saturday', 'noimage' => 'Image not found!', 'upload' => 'Upload', 'onlyimages' => 'Only image files may be uploaded', 'selectimage' => 'Please select the image to upload', 'norecselect' => 'Please select at least one record', 'failedupl' => 'Failed to upload file', 'nokey' => 'You must define the Key Field for transactions like View, Edit, Delete or AJAX edition', 'ErrorInline' => 'Error saving data, please try again. If the error remains, please contact the site-admin', 'arrup' => 'Up', 'arrdn' => 'Down', 'first' => 'First page', 'last' => 'Last page', 'xportdetails' => 'Include detail lines', 'columns' => 'Columns', 'node' => 'View Details', 'closenode' => 'Hide Details', 'invalidfile' => 'File type not allowed');
    function datagrid($scriptName = "", $gridID = "")
    {
        if (empty($scriptName))
            $scriptName = basename($_SERVER["PHP_SELF"]);
        $this->scriptName = $scriptName;
        $this->validName  = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '0',
            "_"
        );
        $this->dgGridID   = "";
        for ($n = 0; $n <= strlen($gridID); $n++)
        {
            $c = substr($gridID, $n, 1);
            if (in_array($c, $this->validName))
                $this->dgGridID .= $c;
        }
    }
    function conectadb($strServer, $strUsername, $strPassword, $strDatabase, $useADOdb = false, $strType = "mysql", $intPort = 3306)
    {
        $strSQL = $this->sqlDataCoding;
        if ($this->isADO = $useADOdb)
        {
            if (strtolower($strType) != "mysql" and $this->backtick == "`")
                $this->backtick = "";
            $this->strDebug[] = "Connectiontype: ADOdb";
            $this->connectionHandler =& ADONewConnection($strType);
            $this->connectionHandler->Connect($strServer, $strUsername, $strPassword, $strDatabase);
            if ($this->debug)
                $this->connectionHandler->debug = true;
            if (!empty($strSQL))
                if ($this->connectionHandler->Execute($strSQL) === false)
                    $this->SQLerror($strSQL, $this->connectionHandler->ErrorMsg());
        }
        else
        {
            if (function_exists('mysqli_connect'))
            {
                $this->strDebug[] = "Connectiontype: MySQLi Native Drivers";
                $this->link       = new mysqli($strServer, $strUsername, $strPassword, $strDatabase, $intPort);
                if (mysqli_connect_errno())
                    $this->SQLerror("mysqli_connect('$strServer', $strUsername, ******, '$strDatabase', $intPort))", mysqli_connect_error());
                if (!empty($strSQL))
                    if (!$this->link->query($strSQL))
                        $this->SQLerror($strSQL, $this->link->error);
            }
            else
            {
                $this->strDebug[] = "Connectiontype: MySQL php Native Drivers";
                $this->link = mysql_connect($strServer . ":" . $intPort, $strUsername, $strPassword) or $this->SQLerror("mysql_connect('$strServer:$intPort', $strUsername, ******))", mysql_error());
                $resSelect = mysql_select_db($strDatabase);
                if (!$resSelect)
                    $this->SQLerror("mysql_select_db($strDatabase)", mysql_error());
                if (!empty($strSQL))
                    mysql_query($strSQL) or $this->SQLerror($strSQL, mysql_error());
                unset($resSelect);
            }
        }
        unset($strSQL);
    }
    function SQL_query($strQuery, $limit = -1, $offset = -1)
    {
        global $ADODB_FETCH_MODE;
        $arrResult = array();
        $numRows   = 0;

        $startok = (substr(trim(strtolower($strQuery)), 0, 6) == "select" || substr(trim(strtolower($strQuery)), 0, 4) == "show");

        if (isset($this->isADO) and $this->isADO)
        {
            $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
            if (substr(trim(strtolower($strQuery)), 0, 6) == "select")
            {
                if ($offset == -1 and $limit != -1)
                {
                    $offset = $limit;
                    $limit  = -1;
                }
                $objRes = $this->connectionHandler->SelectLimit($strQuery, $offset, $limit);
                if ($objRes === false)
                    $this->SQLerror($strQuery, $this->connectionHandler->ErrorMsg());
                while ($row = $objRes->FetchRow())
                {
                    if (!isset($row[0]))
                    {
                        $tmpRow = array();
                        $c      = 0;
                        foreach ($row as $key => $value)
                        {
                            $tmpRow[$c] = $value;
                            $c++;
                            $tmpRow[$key] = $value;
                        }
                        $row = $tmpRow;
                        unset($c);
                    }
                    $arrResult[] = $row;
                    $numRows++;
                }
            }
            else
            {
                $objRes = $this->connectionHandler->Execute($strQuery);
                if ($objRes === false)
                    $this->SQLerror($strQuery, $this->connectionHandler->ErrorMsg());
            }
        }
        else
        {
            $strQuery .= (($limit != -1) ? " LIMIT " . $limit : "");
            $strQuery .= (($limit != -1 and $offset != -1) ? "," . $offset : "");
            if (function_exists('mysqli_connect'))
            {
                // echo "<pre>"; echo($strQuery);
                if ($result = $this->link->query($strQuery))
                {
                    if ($startok)
                    {
                        $numRows = $result->num_rows;
                        while ($row = $result->fetch_array())
                            $arrResult[] = $row;
                        $result->close();
                    }
                    unset($result, $row);
                }
                else
                {
                    $this->SQLerror($strQuery, $this->link->error);
                }
                // var_dump($strQuery); die();
            }
            else
            {
                $objRes = mysql_query($strQuery) or $this->SQLerror($strQuery, mysql_error());
                if (substr(trim(strtolower($strQuery)), 0, 6) == "select")
                    while ($row = mysql_fetch_array($objRes))
                    {
                        $arrResult[] = $row;
                        $numRows++;
                    }

                unset($objRes, $row);
            }
        }
        $this->NR_lastQuery = $numRows;
        return $arrResult;
    }
    function desconectar()
    {
        if ($this->isADO)
        {
            $this->connectionHandler->close();
        }
        else
        {
            if (function_exists('mysqli_connect'))
            {
                $result = @mysqli_close($this->link);
                if (!$result)
                    $this->SQLerror("mysqli_close()", $this->link->error);
            }
            else
            {
                $result = @mysql_close($this->link);
                if (!$result)
                    $this->SQLerror("mysql_close()", mysql_error());
            }
        }
    }
    function SQLerror($strQuery, $strError)
    {
        $this->errorSQL = true;
        $errMsg         = "<br{$this->sl}>-------------------------------<br{$this->sl}>----- " . date("Y-m-d H:i:s") . " -----<br{$this->sl}>" . "-------------------------------<br{$this->sl}>" . $this->scriptName . " <br{$this->sl}>-------------------------------<br{$this->sl}>";
        $errMsgM        = "<div id='DG_sqlerror{$this->dgGridID}' class='dgError'><strong>" . $this->message["sqlerror"] . "</strong> " . "$strQuery<br{$this->sl}><br{$this->sl}><strong>" . $this->message["errormsg"] . " </strong>" . "$strError</div><br{$this->sl}>-------------------------------<br{$this->sl}>";
        if (isset($this->logSQLError) and $this->logSQLError)
        {
            $errMsgF = $errMsg . $this->message["sqlerror"] . " " . $strQuery . "<br{$this->sl}>" . $this->message["errormsg"] . " " . $strError . "<br{$this->sl}>-------------------------------<br{$this->sl}>";
            $gestor  = fopen($this->logfile, "a+");
            fwrite($gestor, str_replace("<br{$this->sl}>", "\r\n", $errMsgF));
        }
        if (!empty($this->strMailErrors))
            sendmail($this->strMailErrors, $this->message["errorscript"] . " " . $this->scriptName, $errMsgM);
        if ($this->bolShowErrors)
            die($errMsgM);
    }
    function getData()
    {
        if (isset($this->ArrayData))
        {
            $this->arrGridData  = $this->ArrayData;
            $this->strDebug[]   = "Obtaining data from array";
            $this->addBtn       = $this->updBtn = $this->delBtn = false;
            $this->ajaxEditable = $this->search = "";
            $this->totalRecords = 5;
            $this->recno        = ($this->recno > $this->totalRecords) ? $this->totalRecords : $this->recno;
            $recno              = $this->recno;
            $maxRec             = $this->maxRec;
            $this->countRecords = 5;
        }
        else
        {
            if (strtolower($this->sqlcharset) != '')
            {
                $this->SQL_query("SET character_set_results={$this->sqlcharset}");
                $this->SQL_query("SET character_set_client={$this->sqlcharset}");
                $this->SQL_query("SET character_set_connection={$this->sqlcharset}");
            }

            /* Build SELECT SQL String based on the where condition */
            $sqlFields = implode("{$this->backtick},{$this->backtick}", $this->arrSQLFld);

            if (empty($this->sql))
                $strSelect = "SELECT {$this->backtick}$sqlFields{$this->backtick} FROM {$this->backtick}" . (implode("{$this->backtick},{$this->backtick}", explode(",", $this->tablename))) . "{$this->backtick} {$this->where} {$this->groupby} {$this->orderby}";
            else
                $strSelect = $this->sql . " {$this->where} {$this->groupby} {$this->orderby}";

            // echo "<pre>"; var_dump($strSelect); die();

            if (1==1)
            {
                if (empty($this->sqlCount))
                {
                    // $pagSelect = "SELECT count(" . ((empty($this->keyfield)) ? "*" : "{$this->backtick}{$this->keyfield}{$this->backtick}") . ") FROM {$this->backtick}" . (implode("{$this->backtick},{$this->backtick}", explode(",", $this->tablename))) . "{$this->backtick} {$this->where} {$this->groupbysimple}";
                    $pagSelect = "select count(*) from ($strSelect) s1";
                }
                else
                {
                    $pagSelect = $this->sqlCount;
                }

                $this->strDebug[] = "Count SQL: " . $pagSelect;

                $countRows = $this->SQL_query($pagSelect);
                // var_dump($countRows); die();
                if (count($countRows) > 1)
                {
                    $countRows[0][0] = count($countRows);
                }
                else if (!isset($countRows[0][0]))
                {
                    if (!isset($countRows[0]))
                        $countRows[0][0] = 0;
                    foreach ($countRows[0] as $ct)
                        $countRows[0][0] = $ct;
                }

                $this->totalRecords = (empty($this->groupby)) ? $countRows[0][0] : $this->NR_lastQuery;
                $this->totalRecords = $countRows[0][0];

                if (($this->recno + 1) > $this->totalRecords)
                {
                    $this->strDebug[]                = "Resseting Page Count to 0";
                    $this->recno                     = 0;
                    $_GET['dg_r' . $this->dgGridID]  = 0;
                    $_POST['dg_r' . $this->dgGridID] = 0;
                }
            }

            /* $this->recno   = ($this->recno>$this->totalRecords) ?$this->totalRecords:$this->recno; */
            $recno  = $this->recno;
            $maxRec = $this->maxRec;

            if ($this->DG_ajaxid == 7 and $this->dgrtd == "A")
                $recno = $maxRec = -1;

            $this->arrGridData = $this->SQL_query($strSelect, $recno, $maxRec);
            $this->strDebug[]  = "SELECT SQL: " . $strSelect;
            $this->strDebug[]  = "RecNo: " . $recno;
            if (isset($this->processDataBefore))
                eval("\$this->arrGridData = {$this->processDataBefore}(\$this->arrGridData);");
            if (isset($this->processData))
                eval("$this->arrGridData = {$this->processData}(\$this->arrGridData);");
            if (isset($this->processDataAfter))
                eval("\$this->arrGridData = {$this->processDataAfter}(\$this->arrGridData);");
            $this->countRecords = $this->NR_lastQuery;
            if ($this->LimitAdd > 0 and $totalRecords >= $this->LimitAdd)
                $this->addBtn = false;
        }
    }
    function language($strLang)
    {
        switch ($strLang)
        {
            case 'english':
            case 'en':
                break;
            case 'espanol':
            case 'español':
            case 'es':
                require($this->lngPath . "languages/es.php");
                break;
            default:
                require($this->lngPath . "languages/{$strLang}.php");
                break;
        }
    }
    function chField($strfieldName, $permissions = "N+E+V+RUMX-", $overwrite = false)
    {
        if (!$overwrite and isset($this->fieldsArray["$strfieldName"]["permissions"]))
            $permissions .= $this->fieldsArray["$strfieldName"]["permissions"];
        $this->fieldsArray["$strfieldName"]["permissions"] = $permissions;
    }
    function setInputWidth($value, $width)
    {
        $this->fieldsArray["$value"]["inputwidth"] = $width;
    }
    function setImageSize($field, $width, $height)
    {
        $this->fieldsArray["$field"]["imageWidth"]  = $width;
        $this->fieldsArray["$field"]["imageHeight"] = $height;
    }
    function setFieldasBinary($field)
    {
        $this->fieldsArray["$field"]["isblob"] = true;
    }
    function noEntities($fieldName)
    {
        $this->noEntities[$fieldName] = true;
    }
    function FormatColumn($strfieldName, $strHeader, $fieldWidth = 0, $maxlength = 0, $inputtype = 0, $columnwidth = 0, $align = 'center', $Mask = 'text', $default = '', $cutChar = 0)
    {
        // strip the CRLF on the Mask
        if (is_array($Mask))
        {
            foreach ($Mask as $key=>$value)
            {
                $v = str_ireplace("\r", "", $value);
                $Mask[$key] = str_ireplace("\n", "", $v);
            }
        }
        else
        {
            $v = str_ireplace("\r", "", $Mask);
            $Mask = str_ireplace("\n", "", $v);
        }


        if ($strfieldName == "" or !$this->validField($strfieldName))
        {
            if (is_array($Mask))
            {
                $arrConditions = $Mask;
                $Mask          = "conditional";
            }
            $mask                                               = strtolower($Mask);
            $datatype                                           = 'text';
            $this->fieldsArray["$strfieldName"]["strfieldName"] = strtr($strfieldName, array(
                chr(32) => ""
            ));
            $this->fieldsArray["$strfieldName"]["strHeader"]    = $strHeader;
            $this->fieldsArray["$strfieldName"]["fieldWidth"]   = $fieldWidth;
            $this->fieldsArray["$strfieldName"]["maxlength"]    = $maxlength;

            // $this->fieldsArray["$strfieldName"]["columnwidth"]  = intval($columnwidth) . (($this->liquidTable) ? "%" : "px");
            if (intval($columnwidth) == $columnwidth)
                $this->fieldsArray["$strfieldName"]["columnwidth"]  = $columnwidth."%";
            else
                $this->fieldsArray["$strfieldName"]["columnwidth"]  = $columnwidth;

            $this->fieldsArray["$strfieldName"]["align"]        = $align;
            $this->fieldsArray["$strfieldName"]["mask"]         = $Mask;
            $this->fieldsArray["$strfieldName"]["default"]      = $default;
            $this->fieldsArray["$strfieldName"]["select"]       = '';
            $this->fieldsArray["$strfieldName"]["cutChar"]      = $cutChar;
            if (substr($mask, 0, 6) == 'custom')
            {
                $mask     = str_replace("custom:", "", $mask);
                $datatype = 'custom';
            }
            if ($mask == 'number')
            {
                $datatype = 'number';
            }
            if ($mask == 'textarea')
                $datatype = 'textarea';
            if ($mask == 'textarea_tinymce')
            {
                $datatype      = 'textarea_tinymce';
                $this->tinyMCE = true;
            }
            if (substr($mask, 0, 5) == 'image')
                $datatype = 'image';
            if (substr($mask, 0, 9) == 'imagelink')
            {
                $datatype = 'imagelink';
            }
            $pmask = !(strpos($this->numerics, trim($mask)) === false);
            if ($pmask)
            {
                $datatype                                   = 'number';
                $this->fieldsArray["$strfieldName"]["mask"] = $mask . ":x:" . $this->decimalsep;
            }
            if (substr($mask, 0, 5) == 'money')
            {
                $datatype                                   = 'number';
                $this->fieldsArray["$strfieldName"]["mask"] = $mask . ":" . $this->decimalsep;
            }
            if (substr($mask, 0, 4) == 'date' and substr($mask, 0, 8) != 'datetime')
            {
                $datatype = 'date';
                $mask     = strtolower($mask);
                list($nada, $format, $separator) = explode(":", $mask);
                if (empty($format))
                    $format = $this->defaultdateformat;
                if (empty($separator))
                    $separator = $this->defaultdateseparator;
                $mask = "$nada:$format:$separator";
            }
            if (substr($mask, 0, 8) == 'datetime')
            {
                $datatype = 'datetime';
                list($nada, $format, $separator, $timeformat) = explode(":", $mask);
                if (empty($format))
                    $format = $this->defaultdateformat;
                if (empty($separator))
                    $separator = $this->defaultdateseparator;
                list($timeformat, $timeseparator) = explode(",", $timeformat);
                if (empty($timeseparator))
                    $timeseparator = ";";
                $mask = "$nada:$format:$separator:$timeformat,$timeseparator";
            }
            if (substr($mask, 0, 4) == 'link')
                $datatype = 'link';
            if (substr($mask, 0, 4) == 'pass')
                $datatype = 'password';
            if (substr($mask, 0, 4) == 'calc')
            {
                $datatype       = 'calc';
                $this->hasCalcs = true;
                $inputtype      = 4;
            }
            if (substr($mask, 0, 5) == 'scalc')
            {
                $datatype       = 'calc';
                $this->hasCalcs = true;
                $inputtype      = 1;
            }
            if (substr($mask, 0, 5) == 'condi')
            {
                $datatype = 'conditional';
                if ($inputtype != 4)
                    $inputtype = 1;
                $this->fieldsArray["$strfieldName"]["select"] = $arrConditions;
                unset($arrConditions);
            }
            if (substr($mask, 0, 5) == 'chart')
            {
                $datatype       = 'chart';
                $this->hasChart = true;
                $inputtype      = 5;
                if (strpos($mask, ':') > 0)
                {
                    $arrMask = explode(':', $Mask);
                    $arrMask = array_slice($arrMask, 1);
                }
                else
                {
                    $arrMask = array(
                        "none:sum"
                    );
                }
                $this->fieldsArray["$strfieldName"]["select"] = $arrMask;
            }
            if (substr($mask, 0, 4) == 'bool' or substr($mask, 0, 5) == 'check')
            {
                $datatype = 'check';
                if (strpos($mask, ':') > 0)
                {
                    $arrMask = explode(':', $Mask);
                    $arrMask = array_slice($arrMask, 1);
                }
                else
                {
                    $arrMask = array(
                        $this->message['false'],
                        $this->message['true']
                    );
                }
                $this->fieldsArray["$strfieldName"]["select"] = $arrMask;
            }
            if (substr($mask, 0, 4) == 'rela')
            {
                $datatype  = 'related';
                $inputtype = 1;
            }
            $mask = strtolower($Mask);
            if (substr($mask, 0, 13) == 'select_nested')
            {
                $datatype = 'select';
                $maskData = array();
                if (strpos($mask, ':') > 0)
                {
                    $arrMask = array(
                        "_"
                    );
                    foreach ($arrMask as $ArrData)
                    {
                        $arrOptions = explode('_', $ArrData);
                        $rowID      = $arrOptions[0];
                        if (isset($arrOptions[1]))
                            $rowName = $arrOptions[1];
                        else
                            $rowName = $rowID;
                        $maskData[$rowID] = $rowName;
                    }
                    $this->fieldsArray["$strfieldName"]["select"] = $maskData;
                }
            }
            if (substr($mask, 0, 6) == 'select' and $datatype != "select")
            {
                $datatype = 'select';
                $maskData = array();
                if (strpos($mask, ':') > 0)
                {
                    $mask = explode(':', $Mask);
                    if (strtoupper(substr($mask[1], 0, 7)) == 'SELECT ')
                    {
                        /* Select data from Table. Format [SELECT key, value FROM table] */
                        if (strtolower($this->sqlcharset) != '')
                        {
                            $this->SQL_query("SET character_set_results={$this->sqlcharset}");
                            $this->SQL_query("SET character_set_client={$this->sqlcharset}");
                            $this->SQL_query("SET character_set_connection={$this->sqlcharset}");
                        }
                        $arrData = $this->SQL_query($mask[1]);
                        if (isset($this->SelectDefault))
                        {
                            foreach ($this->SelectDefault as $rowID => $rowName)
                                $maskData[$rowID] = $rowName;
                            unset($this->SelectDefault);
                        }
                        foreach ($arrData as $arrResult)
                        {
                            if (strtolower($this->sqlcharset) != '')
                            {
                                $maskData[$arrResult[0]] = utf8_decode(utf8_encode($arrResult[1]));
                            }
                            else
                            {
                                $maskData[$arrResult[0]] = htmlentities($arrResult[1]);
                            }
                        }
                    }
                    else
                    {
                        /* literal select: keyfield must be of the same datatype as the list */
                        $arrMask = array_slice($mask, 1);
                        foreach ($arrMask as $ArrData)
                        {
                            $arrOptions = explode('_', $ArrData);
                            $rowID      = $arrOptions[0];
                            if (isset($arrOptions[1]))
                                $rowName = $arrOptions[1];
                            else
                                $rowName = $rowID;
                            $maskData[$rowID] = $rowName;
                        }
                    }
                    $this->fieldsArray["$strfieldName"]["select"] = $maskData;
                }
            }

            $this->fieldsArray["$strfieldName"]["datatype"]  = $datatype;
            $this->fieldsArray["$strfieldName"]["inputtype"] = $inputtype;
            /* 0=text 1=readonly 2=hidden, 3=non-field relation Image or Calc, 4=field relation Image or Calc */
            if (in_array($inputtype, array(
                0,
                1,
                2,
                3,
                5
            )))
                if (!in_array($strfieldName, $this->arrSQLFld))
                    $this->arrSQLFld[] = $strfieldName;
            if (isset($_COOKIE["DG_columns" . str_replace(".php", "", $this->scriptName)]))
            {
                $cData = explode(",", $_COOKIE["DG_columns" . str_replace(".php", "", $this->scriptName)]);
                if (in_array($strfieldName, $cData))
                {
                    $this->fieldsArray["$strfieldName"]["inputtype"] = 2;
                }
            }
        }
        else
        {
            die("<div id='DG_sqlerror{$this->dgGridID}' class='dgError'>" . $this->message['doublefield'] . ":<strong> [$strfieldName]</strong></div>");
        }
    }
    function closeTags($bolStat = true)
    {
        $this->sl = ($bolStat) ? " /" : "";
    }
    function friendlyHTML($bolStat = true)
    {
        $this->br = ($bolStat) ? "\n" : "";
        $this->tb = ($bolStat) ? "\t" : "";
    }
    function buttons($bolAdd = true, $bolUpd = true, $bolDel = true, $bolChk = false, $intColumn = -1, $strColumnName = "")
    {
        $this->addBtn     = $bolAdd;
        $this->updBtn     = $bolUpd;
        $this->delBtn     = $bolDel;
        $this->chkBtn     = $bolChk;
        $this->btnColumn  = $intColumn;
        $this->btnColName = $strColumnName;
    }
    function noorderarrows()
    {
        $this->orderArrows = false;
    }
    function useRightClickMenu($classPath = "phpMyMenu.inc.php")
    {
        require_once($classPath);
        $this->useRightClickMenu = true;
        $this->objMenu           = new menuright();
        $this->objMenu->sl       = $this->sl;
        $this->objMenu->br       = $this->br;
        $this->objMenu->dgGridID = $this->dgGridID;
    }
    function orderby($fields, $style = "ASC")
    {
        $this->orderColName = strtr($fields, array(
            chr(32) => ""
        ));
        $this->orderExpr    = strtoupper((empty($style)) ? "ASC" : strtr($style, array(
            chr(32) => ""
        )));
    }
    function total($fields)
    {
        $fields         = strtr($fields, array(
            chr(32) => ""
        ));
        $this->totalize = explode(",", $fields);
    }
    function paginationmode($pgm = "mixed", $inTable = false)
    {
        $pgm = strtolower($pgm);
        if (!in_array($pgm, array(
            "links",
            "select",
            "mixed",
            "input"
        )))
            $pgm = "mixed";
        $this->pagination = $pgm;
    }
    function ajax($style = 'DEFAULT', $typeOfClick = "1")
    {
        if (!in_array($style = strtolower($style), array(
            "default",
            "silent"
        )))
            $style = "";
        $this->ajaxEditable = $style;
        if (!in_array($typeOfClick, array(
            1,
            2
        )))
            $typeOfClick = 1;
        $this->typeOfClick = $typeOfClick;
    }
    function jsValidate($strField, $strValidation, $strErrorMessage, $strDisplayMessage = "")
    {
        $this->validations["$strField"]["condition"] = $strValidation;
        $this->validations["$strField"]["errormsg"]  = $strErrorMessage;
        $this->validations["$strField"]["msgField"]  = $strDisplayMessage;
        if (!empty($strValidation))
            $this->allConditions .= "if (" . str_replace("this.", 'DG_goo("dgFld' . $strField . '").', $strValidation) . ") {" . "DG_hss(\"edtErr{$strField}\",\"none\");DG_hss(\"edtMsg{$strField}\",\"block\");} else " . "{DG_hss(\"edtErr{$strField}\",\"block\"); DG_hss(\"edtMsg{$strField}\",\"none\"); DG_goo(\"dgFld{$strField}\").focus(); return false;};" . $this->br;
    }
    function defineNestedSelect($sourceField, $targetField)
    {
        $this->nested["$sourceField"]["target"] = $targetField;
    }
    function fldComment($strField, $strDisplayMessage = "")
    {
        $this->validations["$strField"]["msgField"] = $strDisplayMessage;
    }
    function Form($formName, $doForm = true)
    {
        $this->doForm   = $doForm;
        $this->FormName = $formName;
    }
    function useCalendar($bolCalendar = true)
    {
        $this->bolCalendar = $bolCalendar;
        if ($bolCalendar)
        {
            $this->calendarStr = "<div id='DGdivCalendar{$this->dgGridID}' style='width:150px;z-index:50000;visibility:hidden;display:block;position:absolute;'></div>{$this->br}";
        }
        else
        {
            $this->calendarStr = "";
        }
    }
    function export($bolExportsheet = true, $bolExportCSV = true, $bolExportXML = true, $bolPrinter = true, $bolExportPDF = true, $pdfOrientation = 'P')
    {
        $this->exportosheet   = $bolExportsheet;
        $this->exportocsv     = $bolExportCSV;
        $this->exportoxml     = $bolExportXML;
        $this->printer        = $bolPrinter;
        $this->exportopdf     = $bolExportPDF;
        $this->PDForientation = $pdfOrientation;
        $this->allExp         = ($bolExportsheet or $bolExportCSV or $bolExportXML or $bolExportPDF);
    }
    function sqlstatement($strSQL, $strCount = "")
    {
        $this->sql      = $strSQL;
        $this->sqlCount = $strCount;
    }
    function addCellStyle($field, $condition, $style)
    {
        $this->cellCondition[$field][] = $condition;
        $this->cellStyle[$field][]     = $style;
    }
    function addRowStyle($condition, $style)
    {
        if (!isset($this->styleRowsCount)) $this->styleRowsCount = 0;
        $this -> condition[$this->styleRowsCount] = $condition;
        $this -> conditionalStyle[$this->styleRowsCount] = $style;
        $this->styleRowsCount++;
    }
    function skinimages($skin, $strPath = "skins/%s/icons/")
    {
        $skin          = (isset($_GET['dg_skin_i']) ? $_GET['dg_skin_i'] : (isset($_POST['dg_skin_i']) ? $_POST['dg_skin_i'] : $skin));
        $this->imgpath = sprintf($strPath, $skin);
        $this->linkparam("&dg_skin_i={$skin}");
    }
    function checkable($status = true)
    {
        $this->checkable = $status;
    }
    function TituloGrid($strTitle)
    {
        $this->titulo = $strTitle;
    }
    function FooterGrid($strFooter)
    {
        $this->footer = $strFooter;
    }
    function linksperpage($amount = 4)
    {
        $this->linksperpage = $amount;
    }
    function datarows($intLines)
    {
        $this->maxRec = $intLines;
    }
    function linkparam($parameters)
    {
        if (substr($parameters, 0, 1) != "&")
            $parameters = "&" . $parameters;
        $this->parameters .= $parameters;
    }
    function AjaxChanged($strColor)
    {
        $this->dgAjaxChanged = $strColor;
    }
    function searchby($listoffields = '')
    {
        $this->search = str_replace(" ", "", $listoffields);
    }
    function searchMethod($fieldName, $searchMethod)
    {
        $this->searchMethods[$fieldName] = strtolower($searchMethod);
    }
    function methodForm($strMethod)
    {
        if (!in_array($strMethod = strtoupper($strMethod), array(
            "POST",
            "GET"
        )))
            $strMethod = "GET";
        $this->methodForm = $strMethod;
    }
    function keyfield($strField)
    {
        $this->keyfield = $strField;
        if (!in_array($strField, $this->arrSQLFld))
            $this->arrSQLFld[] = $strField;
    }
    function where($strWhere)
    {
        $this->where = $strWhere;
    }
    function having($strHaving)
    {
        $this->having = $strHaving;
    }
    function groupby($strGroup)
    {
        $this->groupby = $strGroup;
    }
    function reportSQLErrorsTo($strMail, $bolShow = false)
    {
        $this->strMailErrors = $strMail;
        $this->bolShowErrors = $bolShow;
    }
    function tabla($strTable, $strAlias = "")
    {
        $this->tablename  = $strTable;
        $this->tableAlias = $strAlias;
    }
    function decimalDigits($amount)
    {
        $this->decimalDigits = $amount;
    }
    function decimalPoint($char)
    {
        $this->decimalsep = $char;
    }
    function salt($code)
    {
        $this->salt = $code;
    }
    function pathtoimages($strPath)
    {
        $this->imgpath = $strPath;
    }
    function setorderarrows($field)
    {
        $this->setOrderby = $field;
        if (!in_array($field, $this->arrSQLFld))
            $this->arrSQLFld[] = $field;
    }
    function setAction($button, $event)
    {
        $button = strtolower($button);
        if (in_array($button, array(
            "add",
            "edit",
            "delete",
            "search",
            "view"
        )))
        {
            switch ($button)
            {
                case "add":
                    $this->addonClic = $event;
                    break;
                case "edit":
                    $this->edtonClic = $event;
                    break;
                case "delete":
                    $this->delonClic = $event;
                    break;
                case "search":
                    $this->srconClic = $event;
                    break;
                case "view":
                    $this->vieonClic = $event;
                    break;
            }
        }
    }
    function onAjaxUpdate($js)
    {
        $this->afterOnlineEdition = $js;
    }
    function getResetSearch($icon = false)
    {
        if (!$icon)
        {
            return "<a href='javascript:DG_resetSearch();' class='dg10Bold' >{$this->message['resetSearch']}</a>{$this->br}";
        }
        else
        {
            return "<a href='javascript:void(0);' class='dgImgLink' onclick='DG_resetSearch()'><img border='0' src='" . (isset($this->img_path['cancel_search']) ? $this->img_path['cancel_search'] : $this->imgpath) . "{$this->images['cancel_search']}' alt='{$this->message['resetSearch']}' title='{$this->message['resetSearch']}' {$this->sl}></a>{$this->br}";
        }
    }
    function isAjaxRequest()
    {
        return (isset($_POST["DG_ajaxid{$this->dgGridID}"]) or isset($_GET["DG_ajaxid{$this->dgGridID}"]));
    }
    function setAjaxID($id)
    {
        $_POST["DG_ajaxid{$this->dgGridID}"] = $_GET["DG_ajaxid{$this->dgGridID}"] = $id;
    }
    function getAjaxID()
    {
        return ($this->isAjaxRequest() and isset($_POST['DG_ajaxid' . $this->dgGridID])) ? $_POST['DG_ajaxid' . $this->dgGridID] : (($this->isAjaxRequest() and isset($_GET['DG_ajaxid' . $this->dgGridID])) ? $_GET['DG_ajaxid' . $this->dgGridID] : 0);
    }
    function getCheckedBoxes()
    {
        $return = ($this->issetREQUEST('chksel' . $this->dgGridID)) ? explode(",", $this->REQUEST('chksel' . $this->dgGridID)) : array(
            69
        );
        if (count($return) == 1 and empty($return[0]))
            $return = array();
        return $return;
    }
    function isadding()
    {
        if ($this->isAjaxRequest() and $this->getAjaxID() == 6 and $this->issetREQUEST('dgrtd' . $this->dgGridID) and ($this->REQUEST('dgrtd' . $this->dgGridID) == -1 or $this->REQUEST('dgrtd' . $this->dgGridID) == ""))
            return true;
        else
            return false;
    }
    function getField($field)
    {
        return $this->REQUEST("dgFld" . $field);
    }
    function isOnlineEdition()
    {
        return ($this->isAjaxRequest() and $this->getAjaxID() == 4) ? true : false;
    }
    function getEditedData()
    {
        $arrResp = array();
        $this->requestData();

        $arrField= explode('.-.',$this->dgrtd);
        $arrResp['fieldname'] = substr($arrField[0], 0, strlen($arrField[0])-strlen($this->dgGridID));
        $arrResp['data'] = $this->nt;
        $arrResp['id'] = $arrField[1];

        return $arrResp;
    }
    function setNewInlineData($newData)
    {
        if (strtolower($this->methodForm) == 'post')
        {
            $_POST['dgnt' . $this->dgGridID] = $newData;
        }
        else
        {
            $_GET['dgnt' . $this->dgGridID] = $newData;
        }
    }
    function changeImage()
    {
        $arrResp = array();
        $this->requestData();
        $arrField             = explode('.-.', $this->dgrtd);
        $arrResp['fieldname'] = str_replace("icn_{$this->dgGridID}", "", $arrField[0]);
        $arrResp['newData']   = ($arrField[2] == 1) ? 0 : 1;
        $arrResp['id']        = $arrField[1];

        echo $this->getFieldData($arrResp['fieldname'],$arrResp['id'], array($arrResp['fieldname']=>$arrResp['newData']));
        $tablename = (isset($this->updateOther))?$this->updateOther:$this->tablename;
        $strSQL = sprintf("update {$this->backtick}{$tablename}{$this->backtick} set {$this->backtick}{$arrResp['fieldname']}{$this->backtick}=%s where {$this->backtick}{$this->keyfield}{$this->backtick}=%s",
                        magic_quote($arrResp['newData']),
                        magic_quote($arrResp['id'])
                        );
        $this->SQL_query($strSQL);

        die();
    }
    function setHeader($phpScriptFile = "", $jsFile = "js/dgscripts.js", $cssFile = "css/dgstyle.css", $jsCalFile = "js/dgcalendar.js", $cssCalFile = "css/dgcalendar.css", $jsmmenu = "js/mmscripts.js")
    {
        if (!empty($phpScriptFile))
            $this->scriptName = $phpScriptFile;
        $strOutput = "";
        if (!$this->issetREQUEST("DG_ajaxid{$this->dgGridID}"))
        {
            if (!empty($cssFile))
                $strOutput .= "<link type='text/css' rel='stylesheet' href='{$cssFile}'{$this->sl}>{$this->br}";
            if (!empty($cssFile))
                $strOutput .= "<!--[if IE ]><link type='text/css' rel='stylesheet' href='" . str_replace(".css", "IE.css", $cssFile) . "'{$this->sl}><![endif]-->{$this->br}";
            if (!empty($cssFile))
                $strOutput .= "<!--[if IE 6]><link type='text/css' rel='stylesheet' href='" . str_replace(".css", "IE6.css", $cssFile) . "'{$this->sl}><![endif]-->{$this->br}";
            $strOutput .= "<script type='text/javascript' src='{$jsFile}'></script>{$this->br}";
            if ($this->bolCalendar)
            {
                if (!empty($cssCalFile))
                    $strOutput .= "<link type='text/css' rel='stylesheet' href='{$cssCalFile}'{$this->sl}>{$this->br}";
                $strOutput .= "<script type='text/javascript' src='{$jsCalFile}'></script>{$this->br}";
                $strOutput .= "<script type='text/javascript' src='{$jsmmenu}'></script>{$this->br}";
            }
            if ($this->useRightClickMenu and !$this->bolCalendar)
                $this->objMenu->creascript();
        }
        if ($this->getAjaxID()!=7)
        {
            if ($this->retcode)
                return $strOutput;
            else
                echo $strOutput;
        }
    }
    function allowHide($fields, $inColumns = false)
    {
        $actCal = $this->bolCalendar;
        $this->useCalendar();
        $this->bolCalendar = $actCal;
        $this->hideFields  = explode(",", $fields);
        $this->inColumns   = $inColumns;
        foreach ($this->hideFields as $key => $FldOption)
        {
            $this->hideFields[$key] = trim($FldOption);
        }
        $this->hideFields = implode(",", $this->hideFields);
    }
    function addExportOption($variable, $type, $label = "", $options = array())
    {
        $this->ExportOptions[] = array(
            "label" => $label,
            "type" => strtolower($type),
            "options" => $options,
            "variable" => $variable
        );
    }
    function grid()
    {
        global $keyValue, $clAlt, $rowRes;
        if (!empty($this->tableAlias))
            $this->tableAlias = $this->backtick . $this->tableAlias . $this->backtick;
        if ($this->debug)
            $this->bolShowErrors = true;
        $fltypes = "0,1,3,4,5,7";
        if ($this->template)
            $this->nowindow = true;
        $this->fieldsTodisplay = $this->getFields($fltypes);
        $this->requestData();
        $this->buildOrderBy();
        $this->buildWhere();
        $this->buildGroupBy();
        $strOutput = $this->processData();
        $this->getData();
        $this->verifyUploads();
        /* Check if some image is being uploaded */

# teik
#        if (isset($this->isEditingOnly))
#        {
#            if ($this->retcode)
#               return $strOutput;
#        }
        {
            if ($this->nowindow)
            {
                if (!$this->retcode)
                    echo $strOutput;
                return $strOutput;
            }
        }
        $dgTitles = $dgHeadBtns = $dgCols = "";
        $curCol   = 0;
        $dgTitles .= $this->addNode("head");
        $dgCols .= $this->addNode("cols");
        $dgTitles .= $this->addCheckBox("head");
        $dgCols .= $this->addCheckBox("cols");
        if ($this->toolbar)
        {
            $this->getMyOwnButtons = true;
        }
        else
        {
            $this->strSearchInline = false;
            $this->strExportInline = false;
        }
        foreach ($this->fieldsTodisplay as $fldName)
        {
            $fldTitle = $this->setTitle("$fldName");
            $dgTitles .= $this->getButtonsHTML($curCol, "header");
            $dgCols .= $this->getButtonsHTML($curCol, "colhead");
            $dgTitles .= $this->tab(3) . "<td class='dgTitles'";
            if ($this->fieldsArray["$fldName"]["ordering"] and $this->useRightClickMenu)
                $dgTitles .= $this->objMenu->onclick("order", "$fldName");
            $dgTitles .= ">" . $fldTitle . $this->tab(3) . "</td>{$this->br}";
            $dgCols .= $this->getButtonsHTML(-2, "cols", $this->fieldsArray["$fldName"]["columnwidth"], $this->fieldsArray["$fldName"]["align"]);
            $curCol++;
        }
        $dgTitles .= $this->getButtonsHTML(-1, "header");
        $dgCols .= $this->getButtonsHTML(-1, "colhead");
        if (isset($this->hideColumnNames) and $this->hideColumnNames)
            $dgTitles = "";
        if ($this->liquidTable)
        {
            $this->nw     = $this->width;
            $this->tWidth = $this->nwd = '100%';
        }
        else
        {
            $this->tWidth = ($this->tableWidth) . "px";
            $addpx        = (isset($this->height)) ? 17 : 0;
            $this->nwd    = $this->nw = (intval($this->tWidth) + $addpx) . "px";
        }
        $dgTable = "";
        if (!empty($this->titulo) or $this->toolbar)
        {
            $dgTable .= "<div id='dgTable{$this->dgGridID}_Header_b' class='dgTable' style='width:{$this->nw};text-align:left;' onmouseup='DG_set_working_grid(\"{$this->dgGridID}\")'>{$this->br}";
            $dgTable .= $this->tab() . $this->getTitle();
            if ($this->toolbar)
            {
                $dgTable .= $this->tab() . "<div class='dgToolbar'>{$this->br}";
                if (!empty($this->strAddBtn))
                    $dgTable .= $this->addButton($this->strAddBtn, $this->addonClic, $this->message['add'], true);
                if (!empty($this->strSearchBtn))
                    $dgTable .= $this->addButton($this->strSearchBtn, $this->srconClic, $this->message['search'], true);
                $dgTable .= $this->addSeparator(true);
                if (!empty($this->strExportBtn))
                {
                    $dgTable .= $this->addButton($this->strExportBtn, $this->exportTo, $this->message['exportButton'], true);
                    $dgTable .= $this->addSeparator(true);
                }

                if (isset($this->delchkbtn) and $this->delchkbtn)
                    $dgTable .= $this->addButton((isset($this->img_path['erasechk']) ? $this->img_path['erasechk'] : $this->imgpath) . $this->images['erasechk'], $this->multiDeleteonClic, $this->message['deletechk'], true);
                if (isset($this->tButtons) and !empty($this->tButtons))
                {
                    foreach ($this->tButtons as $btnData)
                        $dgTable .= $btnData;
                }
                if (!empty($this->strExportBtn))
                {
                    if (isset($this->strExportInline) and $this->strExportInline)
                    {
                        $checked = true;
                        $dgTable .= $this->tab(2) . "<div id='DG_xportDIV{$this->dgGridID}' align='left' class='dgExportDivInline'>{$this->br}";
                        $dgTable .= $this->tab() . $this->addButton("<img src='" . (isset($this->img_path['blank']) ? $this->img_path['blank'] : $this->imgpath) . "{$this->images['blank']}'{$this->sl}>", "", $this->message['export'], true);
                        if ($this->exportosheet)
                        {
                            $dgTable .= $this->tab() . $this->addRadio("DG_ee{$this->dgGridID}", "exporta{$this->dgGridID}", "E", $checked, "DG_setExport(\"E\")", "<img border='0' src='" . (isset($this->img_path['sheet']) ? $this->img_path['sheet'] : $this->imgpath) . "{$this->images['sheet']}' alt='{$this->message['sheet']}' title='{$this->message['sheet']}' width='16' height='16'{$this->sl}>", true);
                            $checked = false;
                        }
                        if ($this->exportocsv)
                        {
                            $dgTable .= $this->tab() . $this->addRadio("DG_ec{$this->dgGridID}", "exporta{$this->dgGridID}", "C", $checked, "DG_setExport(\"C\")", "<img border='0' src='" . (isset($this->img_path['csv']) ? $this->img_path['csv'] : $this->imgpath) . "{$this->images['csv']}' alt='{$this->message['csv']}' title='{$this->message['csv']}' width='16' height='16'{$this->sl}>", true);
                            $checked = false;
                        }
                        if ($this->exportoxml)
                        {
                            $dgTable .= $this->tab() . $this->addRadio("DG_ex{$this->dgGridID}", "exporta{$this->dgGridID}", "X", $checked, "DG_setExport(\"X\")", "<img border='0' src='" . (isset($this->img_path['xml']) ? $this->img_path['xml'] : $this->imgpath) . "{$this->images['xml']}' alt='{$this->message['xml']}' title='{$this->message['xml']}' width='16' height='16'{$this->sl}>", true);
                            $checked = false;
                        }
                        if ($this->exportopdf)
                        {
                            $dgTable .= $this->tab() . $this->addRadio("DG_ep{$this->dgGridID}", "exporta{$this->dgGridID}", "P", $checked, "DG_setExport(\"P\")", "<img border='0' src='" . (isset($this->img_path['pdf']) ? $this->img_path['pdf'] : $this->imgpath) . "{$this->images['pdf']}' alt='{$this->message['pdf']}' title='{$this->message['pdf']}' width='16' height='16'{$this->sl}>", true);
                            $checked = false;
                        }
                        if ($this->printer)
                        {
                            $dgTable .= $this->tab() . $this->addRadio("DG_ei{$this->dgGridID}", "exporta{$this->dgGridID}", "I", $checked, "DG_setExport(\"I\")", "<img border='0' src='" . (isset($this->img_path['printer']) ? $this->img_path['printer'] : $this->imgpath) . "{$this->images['printer']}' alt='{$this->message['printer']}' title='{$this->message['printer']}' width='16' height='16'{$this->sl}>", true);
                            $checked = false;
                        }
                        $dgTable .= $this->tab() . $this->addSeparator(true);

                        if (isset($this->exportDetails))
                        {
                            $un_nivel     = true;
                            $opcionesMenu = array();
                            foreach ($this->exportDetails as $akey => $avalue)
                            {
                                if (is_array($avalue))
                                {
                                    $un_nivel            = false;
                                    $opcionesMenu[$akey] = $akey;
                                }
                            }
                            if ($un_nivel)
                            {
                                $dgTable .= $this->tab() . $this->addCheck("DG_exportadetails{$this->dgGridID}", "DG_exportadetails{$this->dgGridID}", false, "", $this->message['xportdetails'], true);
                            }
                            else
                            {
                                $arrData = array();
                                foreach ($opcionesMenu as $opt)
                                    $arrData[$opt] = $opt;
                                $dgTable .= $this->tab() . $this->addSelect($arrData, "", "", "DG_exportadetails{$this->dgGridID}", "", true);
                            }
                            $dgTable .= $this->tab() . $this->addSeparator(true);
                        }

                        $dgTable .= $this->tab() . $this->addRadio("DG_eea{$this->dgGridID}", "exportato{$this->dgGridID}", "A", true, "DG_setExport()", "<img border='0' src='" . (isset($this->img_path['all_pages']) ? $this->img_path['all_pages'] : $this->imgpath) . "{$this->images['all_pages']}' alt='{$this->message['exporA']}' title='{$this->message['exporA']}'{$this->sl}>", true);
                        $dgTable .= $this->tab() . $this->addRadio("DG_ees{$this->dgGridID}", "exportato{$this->dgGridID}", "P", false, "DG_setExport()", "<img border='0' src='" . (isset($this->img_path['all_pages']) ? $this->img_path['all_pages'] : $this->imgpath) . "{$this->images['this_page']}' alt='{$this->message['exporP']}' title='{$this->message['exporP']}'{$this->sl}>", true);
                        if ($this->checkable)
                            $dgTable .= $this->tab() . $this->addRadio("DG_eep{$this->dgGridID}", "exportato{$this->dgGridID}", "S", false, "DG_setExport()", "<img border='0' src='" . (isset($this->img_path['selected_rows']) ? $this->img_path['selected_rows'] : $this->imgpath) . "{$this->images['selected_rows']}' alt='{$this->message['exporS']}' title='{$this->message['exporS']}'{$this->sl}>", true);
                        $dgTable .= $this->tab() . $this->addSeparator(true);
                        $dgTable .= $this->tab(3) . "<input type='button' class='exportBtn toolbarItem' value='{$this->message['exportButton']}' onclick='DG_setExport();eval(DG_gvv(\"DGactExport{$this->dgGridID}\"));'{$this->sl}>{$this->br}";
                        if (isset($this->ExportOptions))
                            $dgTable .= $this->tab(3) . "<img src='" . (isset($this->img_path['options']) ? $this->img_path['options'] : $this->imgpath) . "{$this->images['options']}'{$this->sl} class='dgImgPags' onclick='DG_Slide(\"DG_xportMoreOptions\" + ac(),{duration:.2}).swap();' style='float:right'{$this->sl}>";
                        $dgTable .= $this->tab(2) . "</div>{$this->br}";

                        if (isset($this->ExportOptions))
                        {
                            $dgTable .= $this->tab(2) . "<div id='DG_xportMoreOptions{$this->dgGridID}' style='display:none; width:100%; clear:both; height:28px; padding:5px 0px 0px 10px; border-top:1px solid #ccc'>";
                            foreach ($this->ExportOptions as $option)
                            {
                                if (!empty($option['variable']))
                                    $this->extraParameters[] = $option['variable'];
                                switch ($option['type'])
                                {
                                    case 'checkbox':
                                        $dgTable .= $this->tab(3) . "<label><input type='checkbox' id='{$option['variable']}'{$this->sl}>{$option['label']}</label>";
                                        break;
                                    case 'separator':
                                        $dgTable .= $this->addSeparator(true);
                                        break;
                                    case 'select':
                                        $dgTable .= $this->tab(3) . "<select id='{$option['variable']}'{$this->sl}>";
                                        $dgTable .= $this->tab(4) . "<option value=''>{$option['label']}</option>";
                                        foreach ($option['options'] as $key => $opcion)
                                            $dgTable .= $this->tab(4) . "<option value='{$key}'>{$opcion}</option>";
                                        $dgTable .= $this->tab(3) . "</select>";
                                        break;
                                }
                            }
                            $dgTable .= $this->tab(2) . "</div>";
                        }
                    }
                }
                $dgTable .= $this->tab() . "</div>{$this->br}";
            }
            $dgTable .= "</div>{$this->br}";
        }
        $height = (isset($this->height)) ? (intval($this->height) - 100) . "px" : "100%";
        $dgTable .= "<div style='height:{$height};width:{$this->nw};overflow:auto;text-align:left;'>{$this->br}";
        $dgTable .= "<table id='dgTable{$this->dgGridID}' class='dgTable KeyTable' cellpadding='0' cellspacing='0' style='width:{$this->tWidth};'>{$this->br}";
        $thead  = $this->tab() . "<thead>{$this->br}" . $this->tab(2) . "<tr onmouseup='DG_set_working_grid(\"{$this->dgGridID}\")'>{$this->br}{$dgTitles}{$this->br}" . $this->tab(2) . "</tr>{$this->br}" . $this->tab() . "</thead>{$this->br}";
        $dgBody = (isset($this->trSubHead)) ? $this->trSubHead : "";
        $alt    = false;
        $rowNo  = 0;
        foreach ($this->arrGridData as $key => $rowRes)
        {
            $arrkField = explode(".", $this->keyfield);
            $kField    = (isset($arrkField[1])) ? $arrkField[1] : $arrkField[0];
            $keyValue  = (empty($this->keyfield)) ? $key : $rowRes["$kField"];
            if ($rowNo == $this->zebraLines)
            {
                $alt   = !$alt;
                $rowNo = 0;
            }
            $rowNo++;
            $curCol = 0;
            $clAlt  = ($alt) ? "alt" : "norm";
            $dgBody .= $this->tab(2) . "<tr class='dgRows{$clAlt}TR ";
            $claux = $stlaux = "";
            if (is_array($this->condition))
            {
                foreach ($this->condition as $key => $condition)
                {
                    $this->tmpCondition[$key] = (!empty($condition)) ? "if (" . strtr($condition, array(
                        "['" => "\$rowRes['"
                    )) . ") { \$claux = \$claux.' '." . magic_quote($this->conditionalStyle[$key]) . "; }" : "";
                    eval($this->tmpCondition[$key]);
                }
            }
            else
            {
                $this->tmpCondition[$key] = (!empty($this->condition)) ? "if (" . strtr($this->condition, array(
                    "['" => "\$rowRes['"
                )) . ") { \$stlaux .= ' '." . magic_quote($this->conditionalStyle) . "; }" : "";
                eval($this->tmpCondition[$key]);
            }
            $dgBody .= $claux . "' id='dg{$this->dgGridID}TR{$keyValue}' {$stlaux} ";
            if ($this->useRightClickMenu)
                $dgBody .= $this->objMenu->onclick("options", "$keyValue::" . md5($this->salt . $keyValue));
            $dgBody .= " onmouseup='DG_set_working_grid(\"{$this->dgGridID}\")' >{$this->br}";
            $edtCondition    = (!empty($this->conditionEdit)) ? "if (" . strtr($this->conditionEdit, array(
                "['" => "\$rowRes['"
            )) . ") \$this->edtResult = true; else \$this->edtResult = false;" : "";
            $delCondition    = (!empty($this->conditionDelete)) ? "if (" . strtr($this->conditionDelete, array(
                "['" => "\$rowRes['"
            )) . ") \$this->delResult = true; else \$this->delResult = false;" : "";
            $this->edtResult = $this->delResult = true;
            eval($edtCondition);
            eval($delCondition);
            $dgBody .= $this->addNode("row", $keyValue, $rowRes);
            $dgBody .= $this->addCheckBox("row", $keyValue, $claux);
            foreach ($this->fieldsTodisplay as $value)
            {
                $dgBody .= $this->getButtonsHTML($curCol, "body");
                $dgBody .= $this->getFieldData($value, $keyValue, $rowRes);
                $curCol++;
            }
            $dgBody .= $this->getButtonsHTML(-1, "body");
            $dgBody .= "{$this->br}" . $this->tab(2) . "</tr>{$this->br}";
            if (isset($this->subGrid))
            {
                $dgBody .= $this->tab(2) . "<tr class='dgRows{$clAlt}TR' ><td style='vertical-align:top;'><span id='DG_det_a_{$this->dgGridID}{$keyValue}'></span></td><td colspan='" . ($this->columns - 1) . "' id='DG_det_{$this->dgGridID}{$keyValue}'></td></tr>";
            }
        }
        if ($this->countRecords == 0)
            $dgBody = $this->tab(2) . "<tr>{$this->br}<td colspan='{$this->columns}' class='dgError' style='width:100%'>{$this->br}<strong>" . $this->message['norecords'] . "</strong>{$this->br}</td>{$this->br}</tr>{$this->br}";
        else
        {
            if (!empty($this->totalize))
            {
                $curCol = 0;
                $dgBody .= $this->tab(2) . "<tr class='dgTotRowsTR'>{$this->br}";
                $dgBody .= $this->addNode("total");
                $dgBody .= $this->addCheckBox("total");

                foreach ($this->fieldsTodisplay as $value)
                {
                    /* Draw each column data in the row */
                    $dgBody .= $this->getButtonsHTML($curCol, "total");
                    $dgBody .= $this->getFieldData($value, $keyValue, $rowRes, true);
                    $curCol++;
                }
                $dgBody .= $this->getButtonsHTML(-1, "total");
                $dgBody .= "{$this->br}</tr>{$this->br}";
            }
        }

        $dgBody = $this->tab() . "<tbody id='KeyTableBody'>{$this->br}{$dgBody}{$this->br}" . $this->tab() . "</tbody>{$this->br}";
        $tfoot  = "</table>{$this->br}</div>{$this->br}";
        if (!(isset($this->hidePaginationRow) and $this->hidePaginationRow))
        {
            $tfoot .= "<div id='dgTable{$this->dgGridID}_Header_a' class='dgTable' style='text-align:left; width:{$this->nw}; padding:0px;' onmouseup='DG_set_working_grid(\"{$this->dgGridID}\")'>{$this->br}";
            $tfoot .= $this->tab() . $this->getPagenumbers() . "{$this->br}";
            $dgEnd = "</div>{$this->br}";
        }
        else
        {
            $dgEnd = "";
        }
        if (!$this->isAjaxRequest())
        {
            /* Evitar recargas dentro del div */
            $dgDivsUpload  = $this->getDivs("upload");
            $dgStartForm   = $this->getForm("start");
            $dgRightMenu   = $this->drawMenu();
            $dgCalendarStr = $this->calendarStr;
            $dgSearchBox   = $this->getSearchBox();
            $dgExportBox   = $this->getExportBox();
            $dgDivsStart   = $this->getDivs("start");
            $dgDivsEnd     = $this->getDivs("end");
            $dgMainFooter  = $this->getFooter();
            $dgEndForm     = $this->getForm("end");
            $dgEndForm .= $this->br . "<script type='text/javascript' language='javascript'>function DG_validAll{$this->dgGridID}(){" . $this->allConditions . ";return true;}</script>{$this->br}";
        }
        else
        {
            $dgDivsUpload = $dgStartForm = $dgRightMenu = $dgCalendarStr = $dgSearchBox = $dgExportBox = $dgMainFooter = $dgEndForm = $dgDivsStart = $dgDivsEnd = "";
        }
        $dgMainTitle  = "";
        $dgHiddenFlds = $this->getHidden();
        $dgDebug      = $this->getDebug();
        $strOutput    = $dgRightMenu . $dgDivsUpload . $dgStartForm . $dgCalendarStr . $dgSearchBox . $dgExportBox . $dgDivsStart . $dgHiddenFlds . $dgTable . $dgMainTitle . $dgCols . $thead . $dgBody . $tfoot . $dgEnd . $dgDebug . $dgDivsEnd . $dgMainFooter . $dgEndForm . "";
        if ($this->retcode)
            return $strOutput;
        else
            echo $strOutput;
        $this->desconectar();
        if ($this->issetREQUEST("DG_ajaxid{$this->dgGridID}"))
            die();
    }
    function edit($value = "")
    {
        global $keyValue, $clAlt, $rowRes;
        if ($this->debug)
            $this->bolShowErrors = true;
        $fltypes               = "0,1,3,4,5";
        $this->fieldsTodisplay = $this->getFields($fltypes);
        $this->requestData();
        $this->buildOrderBy();
        $this->buildWhere();
        $this->buildGroupBy();
        $this->processData();
        $this->getData();
        $this->verifyUploads();
        /* Check if some image is being uploaded */
        if (!$this->isAjaxRequest())
        {
            $dgDivsUpload  = $this->getDivs("upload");
            $dgStartForm   = $this->getForm("start");
            $dgRightMenu   = $this->drawMenu();
            $dgCalendarStr = $this->calendarStr;
            $dgSearchBox   = $this->getSearchBox();
            $dgExportBox   = $this->getExportBox();
            $dgDivsStart   = $this->getDivs("start");
            $dgDivsEnd     = $this->getDivs("end");
            $dgMainFooter  = $this->getFooter();
            $dgEndForm     = $this->getForm("end");
            $dgEndForm .= $this->br . "<script type='text/javascript' language='javascript'>function DG_validAll{$this->dgGridID}(){" . $this->br . $this->allConditions . ";return true;}</script>{$this->br}";
        }
        else
        {
            $dgDivsUpload = $dgStartForm = $dgRightMenu = $dgCalendarStr = $dgSearchBox = $dgExportBox = $dgMainFooter = $dgEndForm = $dgDivsStart = $dgDivsEnd = "";
        }
        $dgMainTitle  = "";
        $dgHiddenFlds = $this->getHidden();
        $dgDebug      = $this->getDebug();
        $strOutput    = $dgRightMenu . $dgDivsUpload . $dgStartForm . $dgCalendarStr . $dgSearchBox . $dgExportBox . $dgDivsStart . $dgHiddenFlds . $dgMainTitle . $dgDebug . $dgDivsEnd . $dgMainFooter . $dgEndForm . "";
# teik
#        if ($this->retcode)
#            return $strOutput;
#        else
#            echo $strOutput;
        if ($this->issetREQUEST("DG_ajaxid{$this->dgGridID}"))
            die();
        if (strtolower($this->methodForm) == "get")
        {
            $_GET['DG_ajaxid' . $this->dgGridID] = (isset($_GET['DG_ajaxid' . $this->dgGridID])) ? $_GET['DG_ajaxid' . $this->dgGridID] : "5";
            $rtd                                 = $_GET['dgrtd' . $this->dgGridID] = (isset($_GET['dgrtd' . $this->dgGridID])) ? $_GET['dgrtd' . $this->dgGridID] : $value;
            $vc                                  = md5($this->salt . $rtd);
            $_GET['dgvcode' . $this->dgGridID]   = (isset($_GET['dgvcode' . $this->dgGridID])) ? $_GET['dgvcode' . $this->dgGridID] : $vc;
        }
        else
        {
            $_POST['DG_ajaxid' . $this->dgGridID] = (isset($_POST['DG_ajaxid' . $this->dgGridID])) ? $_POST['DG_ajaxid' . $this->dgGridID] : "5";
            $rtd                                  = $_POST['dgrtd' . $this->dgGridID] = (isset($_POST['dgrtd' . $this->dgGridID])) ? $_POST['dgrtd' . $this->dgGridID] : $value;
            $vc                                   = md5($this->salt . $rtd);
            $_POST['dgvcode' . $this->dgGridID]   = (isset($_POST['dgvcode' . $this->dgGridID])) ? $_POST['dgvcode' . $this->dgGridID] : $vc;
        }
        if (1 == 1) # teik
        {
            if ($this->retcode)
                return $strOutput;
            else
                echo $strOutput;
        }
    }
    function verifyUploads()
    {
        $this->requestData();
        if (isset($_POST["dg_uploading{$this->dgGridID}"]) and $_POST["dg_uploading{$this->dgGridID}"] == 1)
        {
            $fname     = $_FILES["DG_file{$this->dgGridID}"]["name"];
            $extension = strrchr($fname, '.');
            if (!in_array(strtolower($extension), array(
                ".gif",
                ".jpg",
                ".jpeg",
                ".bmp",
                ".png"
            )))
                die("<script type='text/javascript'>alert(\"{$this->message['onlyimages']}\");</script>");
            $imgname   = $_POST["imgname{$this->dgGridID}"];
            $imagedata = str_replace("%s", $fname, $imgname);
            $fldname   = $_POST["fldname{$this->dgGridID}"];
            $keyValue  = $_POST["keyvalue{$this->dgGridID}"];
            if (!copy($_FILES["DG_file{$this->dgGridID}"]['tmp_name'], $imagedata))
            {
                die("<script type='text/javascript'>alert(\"{$this->message['failedupl']}\");</script>");
            }
            else
            {
                $fldCond   = " ({$this->backtick}{$this->keyfield}{$this->backtick}=%s)";
                $updWhere  = sprintf((empty($this->where)) ? " WHERE {$fldCond}" : str_replace("WHERE", "WHERE ({$fldCond}) and ", strtoupper($this->where)), magic_quote($keyValue));
                $nt        = $this->GetSQLValueString($this->nt, $this->fieldsArray[$_POST['fldname' . $this->dgGridID]]["mask"]);
                $tablename = (isset($this->updateOther)) ? $this->updateOther : $this->tablename;
                $strUpdate = "UPDATE {$this->backtick}{$tablename}{$this->backtick} set {$this->backtick}{$fldname}{$this->backtick}=" . magic_quote($fname) . " {$updWhere}";
                $err       = false;
                $this->SQL_query($strUpdate);
                $w = (isset($this->fieldsArray["$fldname"]["imageWidth"])) ? " width='" . $this->fieldsArray["$fldname"]["imageWidth"] . "'" : "";
                $h = (isset($this->fieldsArray["$fldname"]["imageHeight"])) ? " height='" . $this->fieldsArray["$fldname"]["imageHeight"] . "'" : "";
                echo "<img id='DG_UploadedImage{$this->dgGridID}' alt='' title='' src='{$imagedata}' {$h} {$w} {$this->sl}>";
                echo "<script type='text/javascript'>DG_ai('{$imagedata}','{$fldname}','{$keyValue}');  function DG_ai(imagedata,fldname,keyValue){  parent.window.document.getElementById('icn_{$this->dgGridID}'+fldname+'.-.'+keyValue).src = document.getElementById('DG_UploadedImage{$this->dgGridID}').src;  setTimeout('parent.window.document.getElementById(\"uplDiv{$this->dgGridID}\").innerHTML=\"\"');  };  </script>{$this->br}";
            }
            die();
        }
    }
    function getForm($position)
    {
        if ($this->doForm)
        {
            if ($position == "start")
                return "<form method='" . strtolower($this->methodForm) . "' action='{$this->scriptName}' id='{$this->FormName}{$this->dgGridID}' onsubmit='return false'>{$this->br}";
            else
                return "</form>";
        }
    }
    function getDebug()
    {
        $dgDebug = ($this->debug and isset($this->strDebug)) ? $this->strDebug : "";
        return (empty($dgDebug)) ? "" : "<div align='left' class='dgError' style='text-align:left'><strong>DEBUG WINDOW:</strong><br{$this->sl}><br{$this->sl}><small>&nbsp;*&nbsp;" . implode("<br{$this->sl}>&nbsp;*&nbsp;", $dgDebug) . "</small></div>";
    }
    function getHidden($debug = false)
    {
        if (!isset($this->calcID))
            $this->calcID = array();
        $t         = $this->dgGridID;
        $strReturn = "<!-- Powered by phpMyDataGrid - www.gurusistemas.com - Version: {$this->dgVersion}--> {$this->br}";
        $strColumn = $this->PhpArrayToJsObject_Recurse($this->fieldsArray) . ";";
        $type      = ($debug or $this->debug) ? "hidden" : "hidden";
        $strReturn .= "<textarea id='dg_ta_columns{$t}' style='display:none'>{$strColumn}</textarea>{$this->br}";
        $strReturn .= "<textarea id='afterAction{$t}' style='display:none'>{$this->afterAction}</textarea>{$this->br}";
        $strReturn .= "<textarea id='afterOnlineEdition{$t}' style='display:none'>{$this->afterOnlineEdition}</textarea>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGajaxStyle{$t}' value='{$this->ajaxEditable}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGimgpath{$t}' value='{$this->imgpath}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGparams{$t}' value='{$this->parameters}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtDelete{$t}' value='{$this->message['confirm']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtDeletes{$t}' value='{$this->message['confirms']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtSave{$t}' value='{$this->message['save']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtCancel{$t}' value='{$this->message['cancel']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtextErrorInline{$t}' value='{$this->message['ErrorInline']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtCalendar{$t}' value='{$this->message['calendar']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGmethodForm{$t}' value='{$this->methodForm}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGdgAjaxChanged{$t}' value='{$this->dgAjaxChanged}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtSaving{$t}' value='{$this->message['saving']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtxtLoading{$t}' value='{$this->message['loading']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGdecimalPoint{$t}' value='{$this->decimalsep}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGdecimals{$t}' value='{$this->decimalDigits}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGimgSave{$t}' value='{$this->images['save']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGimgClose{$t}' value='{$this->images['close']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGimgCancel{$t}' value='{$this->images['cancel']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGimgAjax{$t}' value='{$this->images['ajax']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGimgCalendar{$t}' value='{$this->images['calendar']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGcamposearch{$t}' value='{$this->search}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGscrName{$t}' value='{$this->scriptName}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DivActiveDiv{$t}' value=''{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_posY{$t}' value=''{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_months{$t}' value='{$this->message['months']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_days{$t}' value='{$this->message['days']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_uac{$t}' value=''{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_nrpp{$t}' value='{$this->maxRec}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGdetails{$t}' value='" . (isset($this->subGrid) ? $this->subGrid : "") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGactExport{$t}' value=''{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_noselect{$t}' value='{$this->message['norecselect']}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGthereisCalc{$t}' value='" . (($this->hasCalcs) ? "true" : "") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGthereisTotal{$t}' value='" . (empty($this->totalize) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGtotalizar{$t}' value='" . (implode(",", $this->totalize)) . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGextraParameters{$t}' value='" . (implode(",", $this->extraParameters)) . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGbolCalendar{$t}' value='" . (($this->bolCalendar) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DGCalcRows{$t}' value='" . (implode(",", $this->calcID)) . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_nowindow{$t}' value='" . (($this->nowindow) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_toolbar{$t}' value='" . (($this->toolbar) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_toolbarsearch{$t}' value='" . ((isset($this->strSearchInline) and $this->strSearchInline) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_toolbarexport{$t}' value='" . ((isset($this->strExportInline) and $this->strExportInline) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_exportMagma{$t}' value='" . ((isset($this->exportMagma)) ? $this->exportMagma : "") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_allowhide{$t}' value='" . ((isset($this->hideFields)) ? $this->hideFields : "") . "'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_tinymce{$t}' value='" . ((isset($this->tinyMCE)) ? "1" : "0") . "'{$this->sl}>{$this->br}";
        $conditionalFields = "";
        foreach ($this->validations as $key => $value)
        {
            if (isset($value['condition']))
            {
                $strReturn .= "<input type='{$type}' id='DG_{$key}_C{$t}' value=\"{$value['condition']}\"{$this->sl}>{$this->br}";
                $strReturn .= "<input type='{$type}' id='DG_{$key}_E{$t}' value=\"{$value['errormsg']}\"{$this->sl}>{$this->br}";
                $conditionalFields .= "-[$key]-";
            }
        }
        $strReturn .= "<input type='{$type}' id='DG_cFields{$t}' value='{$conditionalFields}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_r{$t}' value='{$this->recno}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_order{$t}' value='{$this->reqOrder}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_oe{$t}' value='{$this->reqOe}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_editvalue{$t}' value=''{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='DG_editRecNo{$t}' value=''{$this->sl}>{$this->br}";
        if (!isset($this->isDetails))
            $strReturn .= "<input type='{$type}' id='ajaxDHTMLediting' value='0'{$this->sl}>{$this->br}";
        if (!isset($this->isDetails))
            $strReturn .= "<input type='{$type}' id='DG_dgactive' value='{$this->dgGridID}'{$this->sl}>{$this->br}";
        $strReturn .= "<input type='{$type}' id='dg_nocenter{$t}' value='" . (isset($this->nocenter) ? 1 : 0) . "'{$this->sl}>{$this->br}";
        $this->arrDebug = "<strong>JS Validate Fields:</strong> {$conditionalFields}<br{$this->sl}>{$this->br}";
        return $strReturn;
    }
    function drawMenu()
    {
        if ($this->useRightClickMenu)
        {
            $this->objMenu->retcode = true;
            $this->objMenu->addmenu("order", 180, 22, 0, 1, 1, '#c0c0c0', '#fff', '#ddd', '', $this->imgpath . 'bck-menuitems.gif');
            $this->objMenu->additem("order", $this->message['sa'], 'javascript:DM_orderasc("\'+MM_parameters+\'");', (isset($this->img_path['ascending']) ? $this->img_path['ascending'] : $this->imgpath) . $this->images["ascending"]);
            $this->objMenu->additem("order", $this->message['sd'], 'javascript:DM_orderdes("\'+MM_parameters+\'");', (isset($this->img_path['descending']) ? $this->img_path['descending'] : $this->imgpath) . $this->images["descending"]);
            $this->objMenu->addSeparator("order");
            $this->objMenu->additem("order", $this->message['ssa'], 'javascript:DM_orderasca("\'+MM_parameters+\'");', (isset($this->img_path['dascending']) ? $this->img_path['dascending'] : $this->imgpath) . $this->images["dascending"]);
            $this->objMenu->additem("order", $this->message['ssd'], 'javascript:DM_orderdesa("\'+MM_parameters+\'");', (isset($this->img_path['ddescending']) ? $this->img_path['ddescending'] : $this->imgpath) . $this->images["ddescending"]);

            $this->objMenu->addmenu("options", 180, 22, 0, 1, 1, '#c0c0c0', '#fff', '#ddd', '', $this->imgpath.'bck-menuitems.gif' );
            if ($this->addBtn) $this->objMenu->additem("options", $this->message['add'], "javascript:".$this->addonClicMenu,(isset($this->img_path['add'])?$this->img_path['add']:$this->imgpath).$this->images["add"]);
            if (!empty($this->search)) $this->objMenu->additem("options", $this->message['search'], "javascript:".$this->srconClicMenu,(isset($this->img_path['search'])?$this->img_path['search']:$this->imgpath).$this->images["search"]);
            if ($this->chkBtn) $this->objMenu->additem("options", $this->message['view'], "javascript:".$this->vieonClicMenu,(isset($this->img_path['view'])?$this->img_path['view']:$this->imgpath).$this->images["view"]);
            if ($this->updBtn) $this->objMenu->additem("options", $this->message['edit'], "javascript:".$this->edtonClicMenu,(isset($this->img_path['edit'])?$this->img_path['edit']:$this->imgpath).$this->images["edit"]);
            if ($this->delBtn) $this->objMenu->additem("options", $this->message['delete'], "javascript:".$this->delonClicMenu,(isset($this->img_path['erase'])?$this->img_path['erase']:$this->imgpath).$this->images["erase"]);
            /* ToDo: if (isset($this->subGrid)) $this->objMenu->additem("options", $this->message['node'], "javascript:".$this->detonClicMenu,(isset($this->img_path['node'])?$this->img_path['node']:$this->imgpath).$this->images["node"]);*/
            $this->objMenu->addSeparator("options");
            $this->objMenu->additem("options", $this->message['refresh'], "javascript:DG_Do()",(isset($this->img_path['refresh'])?$this->img_path['refresh']:$this->imgpath).$this->images["refresh"]);
            $this->objMenu->addSeparator("options");
            if ($this->allExp) $this->objMenu->additem("options", $this->message['export'], "javascript:".$this->exportTo,(isset($this->img_path['export'])?$this->img_path['export']:$this->imgpath).$this->images["export"]);
            $this->objMenu->addSeparator("options");

            foreach ($this->fieldsArray as $campo)
            {
                if ($campo["datatype"] == "imagelink" or $campo["datatype"] == "link")
                {
                    if ($campo["datatype"] == "imagelink")
                    {
                        list($type, $imagedata, $valuelist) = explode(':', $campo["mask"]);
                    }
                    if (!empty($imagedata))
                        $value = str_replace("%s", str_replace("&nbsp;", "", "fakeValue"), $imagedata);
                    $strHeader = $this->DGXtract($campo["strHeader"], "<em>", "</em>");
                }
            }
            return $this->objMenu->creadivs(0);
        }
    }
    function getDivs($position)
    {
        switch ($position)
        {
            case "start":
                $height    = (isset($this->height)) ? (intval($this->height)) . "px" : "100%";
                $strReturn = ($this->toolbar) ? "" : "<div id='ajaxDHTMLDiv{$this->dgGridID}' style='display:inline;position:absolute;'></div>{$this->br}";
                $style     = "height:{$height};";
                $strReturn .= "<div id='addDiv{$this->dgGridID}' style='" . (($this->nowindow) ? "display:none; width:{$this->tWidth};{$style}" : "display:inline;position:absolute;text-align:center;top:0px;left:0px;") . "'></div>{$this->br}";
                $strReturn .= "<div id='dgDiv{$this->dgGridID}' class='dgMainDiv' style='display:block;{$style}'>{$this->br}";
                return $strReturn;
                break;
            case "end":
                return "</div>";
                break;
            case "upload":
                if (!$this->isAjaxRequest())
                {
                    $hay_una_um = false;
                    foreach ($this->fieldsArray as $kname => $arrFld)
                    {
                        if (isset($arrFld["permissions"]))
                        {
                            if (strpos($arrFld["permissions"], "U") !== false or strpos($arrFld["permissions"], "M") !== false)
                            {
                                $hay_una_um = true;
                            }
                        }
                    }
                    if (!$hay_una_um)
                        $strReturn = "";
                    else
                    {
                        $strReturn = "<iframe src='about:blank' width='100px' height='100px' style='display:none;' id='DG_iframe{$this->dgGridID}' name='DG_iframe{$this->dgGridID}'></iframe>";
                        $strReturn .= "<form name='dgul{$this->dgGridID}' id='dgul{$this->dgGridID}' method='post' enctype='multipart/form-data' target='DG_iframe{$this->dgGridID}' action=''>{$this->br}";
                        $strReturn .= "<div id='uplDiv{$this->dgGridID}' style='display:inline;position:absolute;text-align:center;top:0px; left:0px;'></div>{$this->br}";
                        $strReturn .= "</form>{$this->br}";
                    }
                    return $strReturn;
                };
                break;
        }
    }
    function getPagenumbers()
    {
        $paginas  = ceil(($this->totalRecords / $this->maxRec));
        $strLinks = $strButton = $strImgTop = $strImgBot = $strTable = "";
        $colsUsed = $pActual = $recno = 0;
        if (empty($this->totalize) and $this->hasBtn and !(($this->toolbar)))
        {
            $colsUsed        = 1;
            $this->btnColumn = -1;
            $strButton       = $this->getButtonsHTML(-1, "pagination");
        }
        $class     = "dgPagRow";
        $strButton = trim($strButton, " ");
        if (($this->toolbar))
        {
            $class    = "dgToolbar";
            $colsUsed = 0;
        }

        $strOriginal = $strNuevo = $RecordsToOf = ""; $newinicial = 0;
        $pm = $this->pagination; $recno = $this->recno;
        $pinto=0; $pActual=-9999;
        for ($conteoPag=0; $conteoPag < $paginas; $conteoPag++)
            if (($recno>=$conteoPag * $this->maxRec) and ($recno < ( $conteoPag + 1 ) * $this->maxRec)) $pActual = $conteoPag;
        $pAnterior  = (($pActual - 1<0)?0:$pActual - 1) * $this->maxRec;
        $pSiguiente = (($pActual + 1>$paginas)?$paginas:$pActual + 1)*$this->maxRec;
        $imgTop = (($recno - $this->maxRec) < 0)?'_off':'';
        $imgBot = (($recno + $this->maxRec) >= $this->totalRecords)?'_off':'';

        $toolbarClass = "";
        if ($this->toolbar){
            $toolbarClass = "toolbarItem";
            $strImgTopp = "<img class='dgImgPags' src='" . (isset($this->img_path['first'.$imgTop])?$this->img_path['first'.$imgTop]:$this->imgpath) . "".$this->images['first'.$imgTop]."' alt='{$this->message['first']}' title='{$this->message['first']}' border='0'";
            if (!$this->toolbar and $imgTop!="_off") $strImgTopp.= " onclick='DG_chgpg(0)'";
            $strImgTopp.= "{$this->sl}>".(($this->toolbar)?"":"&nbsp;{$this->br}");
            $strImgTopp = $this->addButton($strImgTopp, 'DG_chgpg(0)', '', true);
        };

        if ($this->totalRecords > 0 and $this->showToOf)
        {
            $RecordsToOf = "{$this->message['display']} " . ($recno + 1) . " {$this->message['to']} " . ($recno + $this->countRecords) . " {$this->message['of']} " . number_format($this->totalRecords, 0);
        }
        $strImgTop = $this->tab(3) . "<img class='dgImgPags' src='" . (isset($this->img_path['up' . $imgTop]) ? $this->img_path['up' . $imgTop] : $this->imgpath) . "" . $this->images['up' . $imgTop] . "' alt='{$this->message['prev']}' title='{$this->message['prev']}' border='0'";
        if (!$this->toolbar and $imgTop != "_off")
            $strImgTop .= " onclick='DG_chgpg($pAnterior)'";
        $strImgTop .= "{$this->sl}>" . (($this->toolbar) ? "" : "&nbsp;{$this->br}");
        if ($this->toolbar)
            $strImgTop = $strImgTopp . $this->addButton($strImgTop, "DG_chgpg($pAnterior)", '', true);
        for ($conteoPag = 0; $conteoPag < $paginas; $conteoPag++)
        {
            $newinicial = $conteoPag * $this->maxRec;
            if ($pm == 'links' or $pm == 'mixed')
            {
                $dgLA = $this->linksperpage;
                if ((($conteoPag > $pActual - ($dgLA + 1)) and ($conteoPag <= $pActual + $dgLA)) or ($conteoPag < ($dgLA + 1) or $conteoPag >= $paginas - ($dgLA + 1)))
                {
                    if ($conteoPag == $pActual)
                    {
                        $strLink = "class='dgBold'";
                        $prn     = 0;
                    }
                    else
                    {
                        $strLink = "class='dgLinks' href='javascript:DG_chgpg($newinicial);'";
                        $prn     = 1;
                    }
                    if ($prn == 1 or ($prn == 0 and $pm == 'links'))
                    {
                        $strLinks .= $this->tab(3) . "<a $strLink>" . ($conteoPag + 1) . "</a>{$this->br}";
                    }
                    $pinto = 0;
                }
                else
                {
                    if ($pinto == 0)
                    {
                        $strLinks .= $this->tab(3) . "<span class='{$toolbarClass}'>...</span>{$this->br}";
                        $pinto = 1;
                    }
                }
            }
            if (($pm == 'select' or $pm == 'mixed') and $conteoPag == $pActual)
            {
                $strLinks .= $this->tab(3) . "<select class='dgSelectpages' name='pages' size='1' onchange='DG_chgpg(this.value);' >{$this->br}";
                for ($conteoSelect = 0; $conteoSelect < $paginas; $conteoSelect++)
                {
                    $newinselect = ($conteoSelect) * $this->maxRec;
                    $strLinks .= $this->tab(4) . '<option ';
                    if ($conteoSelect == $pActual)
                        $strLinks .= 'selected ';
                    $strLinks .= "value='{$newinselect}'>" . ($conteoSelect + 1) . "</option>{$this->br}";
                }
                $strLinks .= $this->tab(3) . "</select>{$this->br}";
            }

            if ($pm == 'input' and $conteoPag==$pActual)
            {
                $strLinks.= $this->tab(3) . $this->addSeparator(true)."{$this->br}".
                            $this->tab(3) . "<input type='hidden' id='aux_pg{$this->dgGridID}'{$this->sl}>{$this->br}".
                            $this->tab(3) . "<span class='{$toolbarClass}'>{$this->message['page']}</span> {$this->br}".
                            $this->tab(3) . "<input value='".($conteoPag+1)."' type='text' class='dgInputPage' name='pages' id='pages' {$this->br}".
                            $this->tab(4) . "onfocus='this.ov = this.value; DG_svv(\"aux_pg{$this->dgGridID}\",0);' {$this->br}".
                            $this->tab(4) . "onkeypress='return DG_kp_input(event) && DG_bl_esc(event,\"this.value=this.ov\");' {$this->br}".
                            $this->tab(4) . "onkeyup='if(DG_gvv(\"aux_pg{$this->dgGridID}\")==1 && parseInt(this.value)!=parseInt(this.ov) && this.value<={$paginas}) return DG_chgpg((this.value-1)*DG_gvv(\"DG_nrpp{$this->dgGridID}\")); else DG_svv(\"aux_pg{$this->dgGridID}\",0);'{$this->sl}>{$this->br} ".
                            $this->tab(3) . "<span class='{$toolbarClass}'>{$this->message['of']} {$paginas}</span> {$this->br}".
                            $this->tab(3) . $this->addSeparator(true) . $this->br;
            };
        }
        if ($this->toolbar)
        {
            $strImgBotp = "<img class='dgImgPags' src='" . (isset($this->img_path['last' . $imgBot]) ? $this->img_path['last' . $imgBot] : $this->imgpath) . "" . $this->images['last' . $imgBot] . "' alt='" . $this->message['last'] . "' title='" . $this->message['last'] . "' border='0' ";
            if (!$this->toolbar and $imgBot != "_off")
                $strImgBotp .= " onclick='DG_chgpg($newinicial)'";
            $strImgBotp .= "{$this->sl}>" . (($this->toolbar) ? "" : "&nbsp;") . "<br{$this->sl}>";
            $strImgBotp = $this->addButton($strImgBotp, "DG_chgpg($newinicial)", '', true) . $this->addSeparator(true);
            if (isset($this->reload) and $this->reload)
            {
                $strRefresh = "<img class='dgImgPags' src='" . (isset($this->img_path['refresh']) ? $this->img_path['refresh'] : $this->imgpath) . "" . $this->images['refresh'] . "' alt='" . $this->message['refresh'] . "' title='" . $this->message['refresh'] . "' border='0' {$this->sl}>";
                $strImgBotp .= $this->addButton($strRefresh, 'DG_Do()', '', true) . $this->addSeparator(true);
            }
            if ($this->totalRecords > 0 and $this->showToOf)
                $strImgBotp .= "<span class='toolbarItem'>{$RecordsToOf}</span>{$this->br}" . $this->addSeparator(true);
            if (isset($this->hideFields) and !$this->inColumns and !empty($this->hideFields))
            {
                $arrHideFields = explode(",", $this->hideFields);
                $offsetY       = 38;
                foreach ($arrHideFields as $value)
                    $offsetY += 21;
                $strImgBotp .= $this->addButton((isset($this->img_path['dnarrow']) ? $this->img_path['dnarrow'] : $this->imgpath) . $this->images['dnarrow'], 'void(0); \' onclick=\'viewColumnOptions("s' . $this->dgGridID . 'Div", event, "' . $this->message['columns'] . '", ' . $offsetY . '); DG_svv("DG_uac' . $this->dgGridID . '",this.id);', '', true);
            }
            $strImgBotp .= "<div id='ajaxDHTMLDiv{$this->dgGridID}' class='toolbarItem loadingItem'></div>{$this->br}";
        }
        $strImgBot = $this->tab(3) . "<img class='dgImgPags' src='" . (isset($this->img_path['down' . $imgBot]) ? $this->img_path['down' . $imgBot] : $this->imgpath) . "" . $this->images['down' . $imgBot] . "' alt='" . $this->message['next'] . "' title='" . $this->message['next'] . "' border='0' ";
        if (!$this->toolbar and $imgBot != "_off")
            $strImgBot .= " onclick='DG_chgpg({$pSiguiente})'";
        $strImgBot .= "{$this->sl}>" . (($this->toolbar) ? "" : "&nbsp;");
        if ($this->toolbar)
            $strImgBot = $this->addButton($strImgBot, "DG_chgpg({$pSiguiente})", '', true) . $strImgBotp;
        if ($this->toolbar)
        {
            $strOriginal .= "{$strImgTop}{$strLinks}{$strImgBot}";
        }
        else
        {
            if ($this->totalRecords <= $this->maxRec)
                $strImgTop = $strImgBot = $strLinks = "";
            $strOriginal .= "{$strImgTop}{$strLinks}{$strImgBot}";
        }
        $strReturn = $this->tab(0) . "<div class='{$class}'>{$this->br}%s" . $this->tab(1) . "</div>{$this->br}";
        if ($this->toolbar)
        {
            if (isset($this->strSearchInline) and $this->strSearchInline)
            {

                $fields4search=explode(",",$this->search);
                $fields = $selectFields = ""; $ActualIsSelect=false;
                foreach ($fields4search as $FldOption){
                    $hasSelect=strpos($FldOption, ':');
                    $FldOption = trim(str_replace(":select", "", $FldOption));
                    if ($hasSelect!==false and !$ActualIsSelect){
                        if (empty($this->ss)) $this->ss=$FldOption;
                        $ActualIsSelect=($FldOption==$this->ss);
                    };
                    foreach ($this->fieldsArray as $column)
                    {
                        if(isset($column["strfieldName"]))
                        if($column["strfieldName"]==$FldOption){
                            if ($hasSelect){
                                $coma=(!empty($selectFields) and !empty($column["strfieldName"]))?",":"";
                                $selectFields.=$coma.$column["strfieldName"];
                            };
                            $fields.="<option value='".$column["strfieldName"]."' ";
                            if ($this->ss==$column["strfieldName"]) $fields.="selected";
                            $fields.=">".$column["strHeader"]."</option>{$this->br}";
                        };
                    };
                };

                $fields  = "<select id='dg_ss{$this->dgGridID}' class='' " . ((substr_count($this->search, ':') > 0) ? "onchange='DG_setsearch(this.value,\"$this->schrstr\")'" : "") . ">{$this->br}{$fields}</select>";
                $display = (empty($this->schrstr)) ? "none" : "block";
                $strTable .= "<div id='DG_srchDIV{$this->dgGridID}' align='left' class='dgSearchDivInline' style='width:98%; display:{$display}; '>";
                $strTable .= "<span style='float:left; line-height:25px;'>{$this->message['qsearch']}:&nbsp;</span>

                <span style='float:left; line-height:25px;'>{$fields}</span>

                <span id='searchBox{$this->dgGridID}' style='float:left;margin-left:5px; line-height:25px;'>{$this->br}";
                $strTable .= "<input type='hidden' id='boxshr{$this->dgGridID}' value='0'{$this->sl}>{$this->br}";
                if ($ActualIsSelect)
                    $strTable .= $this->selectCombo($this->ss, $this->schrstr);
                else
                    $strTable .= "<input type='text' id='dg_schrstr{$this->dgGridID}' class='dgInput' size='35' value='$this->schrstr' onkeypress='return DG_bl_enter(event,\"DG_doSearch()\") && DG_bl_esc(event,\"DG_closeSearch()\")'{$this->sl}>{$this->br}";
                // $strTable .= "&nbsp;</span><span style='float:left; line-height:25px;'>{$fields}</span>" . "<span style='float:left; line-height:25px;'><img border='0' id='imgsearch{$this->dgGridID}' src='" . (isset($this->img_path['search']) ? $this->img_path['search'] : $this->imgpath) . "{$this->images['search']}' width='16' height='16' alt='{$this->message['search']}' title='{$this->message['search']}' class='dgImgLink imgSearch' onclick='DG_doSearch();'{$this->sl}></span>{$this->br}";
                $strTable .= "&nbsp;</span>" . "<span style='float:left; line-height:25px;'><img border='0' id='imgsearch{$this->dgGridID}' src='" . (isset($this->img_path['search']) ? $this->img_path['search'] : $this->imgpath) . "{$this->images['search']}' width='16' height='16' alt='{$this->message['search']}' title='{$this->message['search']}' class='dgImgLink imgSearch' onclick='DG_doSearch();'{$this->sl}></span>{$this->br}";
                $display = (empty($this->schrstr)) ? "none" : "inline";
                $strTable .= "<span id='rstsearch{$this->dgGridID}' style='display:{$display}; float:left; line-height:25px;'>{$this->br}";
                $strTable .= $this->getResetSearch(true);
                $strTable .= "</span>{$this->br}";
                $strTable .= "</div>{$this->br}";
            }
            if (!empty($this->strSearchBtn))
                $strTable .= $this->addButton($this->strSearchBtn, $this->srconClic, "", true) . $this->addSeparator(true);
            if ($this->totalRecords > 0 and $this->AllowChangeNumRows)
            {
                if (!isset($this->arrRows))
                    $this->arrRows = array(
                        5,
                        10,
                        15,
                        20,
                        25,
                        40
                    );
                $this->arrRows[] = $this->maxRec;
                $this->arrRows   = array_unique($this->arrRows);
                sort($this->arrRows);
                $strTable .= "<select id='DG_nrppAux{$this->dgGridID}' name='DG_nrppAux{$this->dgGridID}' onchange='DG_set_pg_rows()' class='SelectLines'>{$this->br}";
                foreach ($this->arrRows as $opc)
                    $strTable .= "<option value='{$opc}' " . (($this->maxRec == $opc) ? "selected='selected'" : "") . ">{$opc}</option>{$this->br}";
                $strTable .= "</select>{$this->br}";
                $strTable .= $this->addSeparator(true);
            }
            $strTable .= $strOriginal;
        }
        else
        {
            $strTable .= $this->tab(2) . "<div id='DG_pgGetRows{$this->dgGridID}' class='dgHeader' style='width:100%; display:none;'>{$this->br}";
            $strTable .= $this->tab(3) . $this->message['editrows'] . ": <input type='text' id='DG_nrppAux{$this->dgGridID}' name='DG_nrppAux{$this->dgGridID}' value='{$this->maxRec}' onkeypress='return DG_bl_enter(event,\"DG_set_page_rows()\") && DG_bl_esc(event,\"DG_cncl_page_rows()\")' class='dgInput' style='width:25px;'{$this->sl}>{$this->br}";
            $strTable .= $this->tab(3) . "<a href='javascript:void(0);' class='dgImgLink' onclick='DG_set_page_rows()'><img border='0' src='" . (isset($this->img_path['save']) ? $this->img_path['save'] : $this->imgpath) . $this->images["save"] . "' alt='" . $this->message['save'] . "' title='" . $this->message['save'] . "' {$this->sl}></a>{$this->br}";
            $strTable .= $this->tab(3) . "<a href='javascript:void(0);' class='dgImgLink' onclick='DG_cncl_page_rows()'><img border='0' src='" . (isset($this->img_path['cancel']) ? $this->img_path['cancel'] : $this->imgpath) . $this->images["cancel"] . "' alt='" . $this->message['close'] . "' title='" . $this->message['close'] . "' {$this->sl}></a>{$this->br}";
            $strTable .= $this->tab(2) . "</div>{$this->br}";
            $strTable .= $this->tab(2) . "<div style='width:100%;display:block;' id='DGpgTable{$this->dgGridID}' class='DGpgTable'>{$this->br}";
            $strTable .= $this->tab(3) . "<span style='float:left'>{$this->br}{$strOriginal}{$this->br}" . $this->tab(3) . "</span>{$this->br}";
            $strTable .= $strButton;
            if ($this->totalRecords > 0 and $this->AllowChangeNumRows)
                $strTable .= $this->tab(3) . "<span style='float:right'><a href='javascript:void(0);' class='dgImgLink' onclick='DG_act_page_rows();'><img border='0' src='" . (isset($this->img_path['editrows']) ? $this->img_path['editrows'] : $this->imgpath) . "{$this->images['editrows']}' alt='{$this->message['editrows']}' title='{$this->message['editrows']}' {$this->sl}></a></span>{$this->br}";
            if ($this->totalRecords > 0 and $this->showToOf)
                $strTable .= $this->tab(3) . "<span style='float:right'>{$this->br}" . $this->tab(4) . "{$RecordsToOf}{$this->br}" . $this->tab(3) . "</span>{$this->br}";
            $strTable .= $this->tab(2) . "</div>{$this->br}";
        }
        $strReturn = sprintf($strReturn, $strTable);
        return $strReturn;
    }
    function selectCombo($strCampo, $actual)
    {
        if (in_array($strCampo, explode(",", str_replace(":select", "", $this->search))))
        {
            $shrField  = $this->backtick . $strCampo . $this->backtick;
            $strSQL    =
            "
                SELECT
                    {$shrField}
                    #, count({$shrField}) total
                FROM {$this->backtick}{$this->tablename}{$this->backtick}
                GROUP BY {$shrField}
                ORDER BY {$shrField}
            ";
            $arrSelect = $this->SQL_query($strSQL);
            $strReturn = "<select size='1' id='dg_schrstr{$this->dgGridID}' class='dgSelectpage' style='width:240px' >";
            // create a blank selection
            $strReturn .= "<option value=''>&nbsp;</option>{$this->br}";
            // generate the rest of the selections
            $opt = array();
            foreach ($arrSelect as $rowRes)
            {
                $dataType = $this->fieldsArray["$strCampo"]['datatype'];
                $mask     = $this->fieldsArray["$strCampo"]['mask'];
                $selData  = $this->fieldsArray["$strCampo"]['select'];
                $rowValue = $this->putAcutes($rowRes[$strCampo]);
                $rowValue = (($rowValue == "" or is_null($rowValue)) and $this->DG_ajaxid != 5) ? "&nbsp;" : $rowValue;
                $vrField  = (isset($rowRes["$strCampo"]) ? $rowRes["$strCampo"] : "");
                switch ($dataType)
                {
                    case "date":
                    case "datetime":
                        $value = $this->maskdata($rowValue, $mask, $dataType, array(), $rowRes);
                        break;
                    default:
                        $value = $this->maskdata($rowValue, $mask, $dataType, $selData, $rowRes);
                }
                if (!empty($value))
                {
                    $opcValue = (isset($rowRes[0])) ? $rowRes[0] : $value;
                    $tempopt = "<option value='{$opcValue}' ";
                    if ($actual == $value)
                        $tempopt .= "selected='selected'";
                    $tempopt .= ">$value</option>{$this->br}";
                    // $tempopt .= ">$value (~{$rowRes["total"]})</option>{$this->br}";
                }
                $opt[strtoupper($value).$value] = $tempopt;
            }
            ksort($opt);
            $strReturn .= implode($opt, "");
            $strReturn .= "</select>";
            if (count($arrSelect) > 0)
                return $strReturn;
            else
                return "<input type='text' id='dg_schrstr{$this->dgGridID}' class='input' size='35' value='{$actual}' onkeypress='return DG_bl_enter(event,\"DG_doSearch()\") && DG_bl_esc(event,\"DG_closeSearch()\")'{$this->sl}>{$this->br}";
        }
        else
            die("<div id='DG_sqlerror{$this->dgGridID}' class='dgError'>" . $this->message['noinsearch'] . ":<strong> [$strCampo]</strong></div>");
    }
    function addSeparator($internal = false)
    {
        $strToReturn = ($this->toolbar) ? $this->tab(2) . "<div class='btnseparator'></div>{$this->br}" : "";
        if (!$internal)
            $this->tButtons[] = $strToReturn;
        else
            return $strToReturn;
    }
    function addRadio($id, $name, $value, $checked, $action, $label, $internal = false)
    {
        $this->toolbar = true;
        $checked       = ($checked) ? " checked='checked' " : "";
        $strToReturn   = $this->tab(2) . "<div class='fbutton'><label>";
        $strToReturn .= "<input type='radio' name='{$name}' id='{$id}' value='{$value}' {$checked} onchange='{$action}'{$this->sl}>";
        $strToReturn .= "$label</label></div>{$this->br}";
        if (!$internal)
            $this->tButtons[] = $strToReturn;
        else
            return $strToReturn;
    }
    function addCheck($id, $name, $checked, $action, $label, $internal = false)
    {
        $this->toolbar = true;
        $checked       = ($checked) ? " checked='checked' " : "";
        $strToReturn   = $this->tab(2) . "<div class='fbutton'><label>";
        $strToReturn .= "<input type='checkbox' name='{$name}' id='{$id}' {$checked} onchange='{$action}'{$this->sl}>";
        $strToReturn .= "$label</label></div>{$this->br}";
        if (!$internal)
            $this->tButtons[] = $strToReturn;
        else
            return $strToReturn;
    }
    function setButtonOption($id, $param = "", $button = "right")
    {
        $this->buttonClickID    = $id;
        $this->buttonClickParam = $param;
        $this->buttonWhich      = $button;
    }
    function addButton($img, $action, $message, $internal = false)
    {
        $this->toolbar = true;
        $img           = trim($img);
        $idBtn         = (isset($this->idbtn)) ? " id='{$this->idbtn}' " : "";
        if (substr(strtolower($img), 0, 4) != "<img")
            $img = "<img border='0' src='{$img}' alt='{$message}' title='{$message}' class='dgImgLink'{$this->sl}>";

        if (isset($this->buttonClickID)){
            if (strtolower($this->buttonWhich)=='right'){
                $strToReturn = $this->tab(2) . "<div onclick='javascript:{$action}' {$idBtn}";
                $strToReturn .= $this->objMenu->onclick($this->buttonClickID,$this->buttonClickParam);
            }else{
                $strToReturn = $this->tab(2) . "<div {$idBtn}";
                $strToReturn .= $this->objMenu->onleftclick($this->buttonClickID,$this->buttonClickParam);
            }
            unset($this->buttonClickID);
            unset($this->buttonClickParam);
            unset($this->buttonWhich);
        }else{
            $strToReturn = $this->tab(2) . "<div onclick='javascript:{$action}' {$idBtn}";
        };

        $strToReturn .= "><a class='fbutton' href='javascript:void(0)'><span class='buttonImage'>{$img}</span>";
        if (!empty($message))
            $strToReturn .= "{$message}";
        $strToReturn .= "</a></div>{$this->br}";
        unset($this->idbtn);
        if (!$internal)
            $this->tButtons[] = $strToReturn;
        else
            return $strToReturn;
    }
    function addSelect($arrData, $action, $message = "", $id = "", $default = "", $internal = false)
    {
        $this->toolbar = true;
        if (!isset($this->selectMessage))
            $this->selectMessage = "";
        $strToReturn         = $this->tab(2) . "<div class='fbutton'>" . $this->tab(4) . "<span style='float:left'>$this->selectMessage</span><select id='{$id}' onchange='{$action}'>";
        $this->selectMessage = "";
        if (!empty($message))
            $strToReturn .= $this->tab(4) . "<option value=''>{$message}</option>{$this->br}";
        foreach ($arrData as $key => $value)
        {
            $strToReturn .= $this->tab(4) . "<option value='{$key}' " . (($default == $key) ? "selected='selected' " : "") . ">{$value}</option>{$this->br}";
        }
        $strToReturn .= $this->tab(3) . "</select>" . $this->tab(3) . "</div>{$this->br}";
        if (!$internal)
            $this->tButtons[] = $strToReturn;
        else
            return $strToReturn;
    }
    function addInput($type, $action, $message = "", $id = "", $default = "", $internal = false)
    {
        $this->toolbar = true;
        $strToReturn   = $this->tab(2) . "<div >{$message}";
        $strToReturn .= $this->tab(4) . "<input id='{$id}' onblur='{$action}' value='{$default}' {$this->sl}>{$this->br}";
        $strToReturn .= $this->tab(3) . "</div>{$this->br}";
        if (!$internal)
            $this->tButtons[] = $strToReturn;
        else
            return $strToReturn;
    }
    function getSearchBox()
    {
        if (!(isset($this->strSearchInline) and $this->strSearchInline))
        {
            if (!empty($this->search))
            {
                $strReturn = "<div id='DG_srchDIV{$this->dgGridID}' align='left' class='dgSearchDiv' >{$this->br}";
                $strReturn .= "<span class='dgSearchTit' onmousedown='DG_clickCapa(event, this, \"DG_srchDIV{$this->dgGridID}\")' onmouseup='DG_liberaCapa(\"DG_srchDIV{$this->dgGridID}\")'>{$this->br}";
                $strReturn .= "<img border='0' src='" . (isset($this->img_path['search']) ? $this->img_path['search'] : $this->imgpath) . "{$this->images['search']}' alt='{$this->message['search']}' title='{$this->message['search']}' width='16' height='16' {$this->sl}>{$this->br}";
                $strReturn .= "{$this->message['search']}</span>{$this->br}";
                $strReturn .= "<img style='cursor:pointer; float:right' src='" . (isset($this->img_path['close']) ? $this->img_path['close'] : $this->imgpath) . "{$this->images['close']}' alt='{$this->message['close']}' title='{$this->message['close']}' onclick='DG_closeSearch();'{$this->sl}>{$this->br}";
                $strReturn .= "<div id='DG_subdiv{$this->dgGridID}' class='dgInnerDiv' style='text-align:center'>{$this->br}";
                $strReturn .= "<br{$this->sl}><strong>{$this->message['searchby']}:</strong>&nbsp;<select size='1' id='dg_ss{$this->dgGridID}' class='dgSelectpage' ";
                if (substr_count($this->search, ':') > 0)
                    $strReturn .= "onchange='DG_setsearch(this.value,\"$this->schrstr\")'";
                $strReturn .= ">{$this->br}";
                $fields4search  = explode(",", $this->search);
                $selectFields   = "";
                $ActualIsSelect = false;
                foreach ($fields4search as $FldOption)
                {
                    $hasSelect = strpos($FldOption, ':');
                    $FldOption = trim(str_replace(":select", "", $FldOption));
                    if ($hasSelect !== false and !$ActualIsSelect)
                    {
                        if (empty($this->ss))
                            $this->ss = $FldOption;
                        $ActualIsSelect = ($FldOption == $this->ss);
                    }
                    foreach ($this->fieldsArray as $column)
                    {
                        if ($column["strfieldName"] == $FldOption)
                        {
                            if ($hasSelect)
                            {
                                $coma = (!empty($selectFields) and !empty($column["strfieldName"])) ? "," : "";
                                $selectFields .= $coma . $column["strfieldName"];
                            }
                            $strReturn .= "<option value='" . $column["strfieldName"] . "' ";
                            if ($this->ss == $column["strfieldName"])
                                $strReturn .= "selected";
                            $strReturn .= ">" . $column["strHeader"] . "</option>{$this->br}";
                        }
                    }
                }
                $strReturn .= "</select><br{$this->sl}><br{$this->sl}>{$this->br}<span id='searchBox{$this->dgGridID}' style='padding-left:25px'>{$this->br}";
                $strReturn .= "<input type='hidden' id='boxshr{$this->dgGridID}' value='0'{$this->sl}>{$this->br}";
                if ($ActualIsSelect)
                    $strReturn .= $this->selectCombo($this->ss, $this->schrstr);
                else
                    $strReturn .= "<input type='text' id='dg_schrstr{$this->dgGridID}' class='dgInput' size='35' value='$this->schrstr' onkeypress='return DG_bl_enter(event,\"DG_doSearch()\") && DG_bl_esc(event,\"DG_closeSearch()\")'{$this->sl}>{$this->br}";
                $strReturn .= "</span><img border='0' id='imgsearch{$this->dgGridID}' src='" . (isset($this->img_path['search']) ? $this->img_path['search'] : $this->imgpath) . "{$this->images['search']}' width='16' height='16' alt='{$this->message['search']}' title='{$this->message['search']}' class='dgImgLink' onclick='DG_doSearch();'{$this->sl}>{$this->br}<br{$this->sl}><br{$this->sl}>{$this->br}";
                $display = (empty($this->schrstr)) ? "none" : "inline";
                $strReturn .= "<span id='rstsearch{$this->dgGridID}' style='display:$display'>{$this->br}";
                $strReturn .= $this->getResetSearch();
                $strReturn .= "</span>{$this->br}</div>{$this->br}</div>{$this->br}";
            }
            else
            {
                $strReturn = "<input type='hidden' id='dg_ss{$this->dgGridID}' value=''{$this->sl}>{$this->br}";
                $strReturn .= "<input type='hidden' id='dg_schrstr{$this->dgGridID}' value=''{$this->sl}>{$this->br}";
            }
            return $strReturn;
        }
    }
    function getExportBox()
    {
        if (!(isset($this->strExportInline) and $this->strExportInline))
        {
            if ($this->allExp)
            {
                $strReturn = "<div id='DG_xportDIV{$this->dgGridID}' align='left' class='dgSearchDiv DG_xportDIV'>{$this->br}";
                $strReturn .= "<span class='dgSearchTit' onmousedown='DG_clickCapa(event, this, \"DG_xportDIV{$this->dgGridID}\")' onmouseup='DG_liberaCapa(\"DG_xportDIV{$this->dgGridID}\")'>{$this->br}";
                $strReturn .= "<img border='0' src='" . (isset($this->img_path['export']) ? $this->img_path['export'] : $this->imgpath) . "{$this->images['export']}' alt='{$this->message['export']}' title='{$this->message['export']}' width='16' height='16'{$this->sl}>{$this->br}";
                $strReturn .= "{$this->message['export']}</span>{$this->br}";
                $strReturn .= "<img style='cursor:pointer; float:right' src='" . (isset($this->img_path['close']) ? $this->img_path['close'] : $this->imgpath) . "{$this->images['close']}' alt='{$this->message['close']}' title='{$this->message['close']}' onclick='DG_closeExport();'{$this->sl}>{$this->br}";
                $strReturn .= "<div id='DG_subdivxport{$this->dgGridID}' class='dgInnerDiv DG_subdivxport' style='text-align:left'>{$this->br}";
                $border = (isset($this->removeDottedBox)) ? "" : "style='border:1px dotted'";
                $strReturn .= "<br{$this->sl}><div align='center' style='width:100%'><table border='0' {$border}><tr>";
                $colspan = 0;
                $checked = "checked='checked'";
                if ($this->exportosheet)
                {
                    $colspan++;
                    $strReturn .= "<td class='dgRadioBg'><label><img border='0' src='" . (isset($this->img_path['sheet']) ? $this->img_path['sheet'] : $this->imgpath) . "{$this->images['sheet']}' alt='{$this->message['sheet']}' title='{$this->message['sheet']}' width='16' height='16'{$this->sl}>{$this->br}";
                    $strReturn .= "<br{$this->sl}><input type='radio' name='exporta{$this->dgGridID}' id='DG_ee{$this->dgGridID}' value=\"E\" $checked onchange='DG_setExport(\"E\")'{$this->sl}></label></td>{$this->br}";
                    $checked = "";
                }
                if ($this->exportocsv)
                {
                    $colspan++;
                    $strReturn .= "<td class='dgRadioBg'><label><img border='0' src='" . (isset($this->img_path['csv']) ? $this->img_path['csv'] : $this->imgpath) . "{$this->images['csv']}' alt='{$this->message['csv']}' title='{$this->message['csv']}' width='16' height='16'{$this->sl}>{$this->br}";
                    $strReturn .= "<br{$this->sl}><input type='radio' name='exporta{$this->dgGridID}' id='DG_ec{$this->dgGridID}' value=\"C\" $checked onchange='DG_setExport(\"C\")'{$this->sl}></label></td>{$this->br}";
                    $checked = "";
                }
                if ($this->exportoxml)
                {
                    $colspan++;
                    $strReturn .= "<td class='dgRadioBg'><label><img border='0' src='" . (isset($this->img_path['xml']) ? $this->img_path['xml'] : $this->imgpath) . "{$this->images['xml']}' alt='{$this->message['xml']}' title='{$this->message['xml']}' width='16' height='16'{$this->sl}>{$this->br}";
                    $strReturn .= "<br{$this->sl}><input type='radio' name='exporta{$this->dgGridID}' id='DG_ex{$this->dgGridID}' value=\"X\" $checked onchange='DG_setExport(\"X\")'{$this->sl}></label></td>{$this->br}";
                    $checked = "";
                }
                if ($this->exportopdf)
                {
                    $colspan++;
                    $strReturn .= "<td style='padding:15px;' class='dgRadioBg'><label><img border='0' src='" . (isset($this->img_path['pdf']) ? $this->img_path['pdf'] : $this->imgpath) . "{$this->images['pdf']}' alt='{$this->message['pdf']}' title='{$this->message['pdf']}' width='16' height='16'{$this->sl}>{$this->br}";
                    $strReturn .= "<br{$this->sl}><input type='radio' name='exporta{$this->dgGridID}' id='DG_ep{$this->dgGridID}' value=\"P\" $checked onchange='DG_setExport(\"P\")'{$this->sl}></label></td>{$this->br}";
                    $checked = "";
                }
                if ($this->printer)
                {
                    $colspan++;
                    $strReturn .= "<td style='padding:15px;' class='dgRadioBg'><label><img border='0' src='" . (isset($this->img_path['printer']) ? $this->img_path['printer'] : $this->imgpath) . "{$this->images['printer']}' alt='{$this->message['printer']}' title='{$this->message['printer']}' width='16' height='16'{$this->sl}>{$this->br}";
                    $strReturn .= "<br{$this->sl}><input type='radio' name='exporta{$this->dgGridID}' id='DG_ei{$this->dgGridID}' value=\"I\" $checked onchange='DG_setExport(\"I\")'{$this->sl}></label></td>{$this->br}";
                    $checked = "";
                }
                $border = (isset($this->removeDottedBox)) ? "" : "border-top:1px dotted'";
                $strReturn .= "</tr><tr><td colspan='$colspan' style='text-align:left;{$border}'>";
                $strReturn .= "<label class='dg10Bold'><input type='radio' name='exportato{$this->dgGridID}' id='DG_eea{$this->dgGridID}' value=\"A\" checked='checked' onchange='DG_setExport()'{$this->sl}>{$this->message['exporA']}</label><br{$this->sl}>{$this->br}";
                $strReturn .= "<label class='dg10Bold'><input type='radio' name='exportato{$this->dgGridID}' id='DG_eep{$this->dgGridID}' value=\"P\" onchange='DG_setExport()'{$this->sl}>{$this->message['exporP']}</label><br{$this->sl}>{$this->br}";
                if ($this->checkable)
                    $strReturn .= "<label class='dg10Bold'><input type='radio' name='exportato{$this->dgGridID}' id='DG_ees{$this->dgGridID}' value=\"S\" onchange='DG_setExport()'{$this->sl}>{$this->message['exporS']}</label>{$this->br}";
                $strReturn .= "</td></tr></table></div>";
                $strReturn .= "<p align='center'><input type='button' value='{$this->message['exportButton']}' onclick='DG_setExport();eval(DG_gvv(\"DGactExport{$this->dgGridID}\"));'{$this->sl}>&nbsp;{$this->br}";
                $strReturn .= "<input type='button' value='{$this->message['cancel']}' onclick='DG_closeExport(); '{$this->sl}></p>{$this->br}";
                $strReturn .= "</div></div>{$this->br}";
                return $strReturn;
            }
        }
    }
    function requestData()
    {
        if (empty($this->keyfield) and ($this->delBtn or $this->updBtn or $this->chkBtn or !empty($this->ajaxEditable)))
            die("<div class='dgError'>--{$this->keyfield}--{$this->message['nokey']}</div>");
        $n = explode(".", $this->scriptName);
        if (isset($_COOKIE['DG_parameters' . $n[0]]))
        {
            $cookieData = $_COOKIE['DG_parameters' . $n[0]];
            setcookie("DG_parameters" . $n[0], "", -1);
            $arrParameters = explode("&", $cookieData);
            if (strtolower($this->methodForm) == "post")
            {
                foreach ($arrParameters as $value)
                {
                    $arrValue            = explode("=", $value);
                    $_POST[$arrValue[0]] = $arrValue[1];
                }
            }
            else
            {
                foreach ($arrParameters as $value)
                {
                    $arrValue           = explode("=", $value);
                    $_GET[$arrValue[0]] = $arrValue[1];
                }
            }
        }
        $this->DG_ajaxid = $this->getAjaxID();
        if ($this->DG_ajaxid == 7 and isset($_GET['DG_ajaxid' . $this->dgGridID]))
            $this->methodForm = 'get';
        if (($this->issetREQUEST('dg_r' . $this->dgGridID)))
            $this->recno = $this->REQUEST("dg_r" . $this->dgGridID);
        $this->nt         = ($this->issetREQUEST('dgnt' . $this->dgGridID)) ? $this->REQUEST('dgnt' . $this->dgGridID) : "";
        $this->ss         = rtrim($this->issetREQUEST('dg_ss' . $this->dgGridID)) ? $this->REQUEST('dg_ss' . $this->dgGridID) : "";
        $this->recno      = intval($this->recno);
        $this->dgrtd      = ($this->issetREQUEST('dgrtd' . $this->dgGridID)) ? $this->REQUEST('dgrtd' . $this->dgGridID) : "";
        $this->vcode      = ($this->issetREQUEST('dgvcode' . $this->dgGridID)) ? $this->REQUEST('dgvcode' . $this->dgGridID) : "";
        $this->chksel     = ($this->issetREQUEST('chksel' . $this->dgGridID)) ? $this->REQUEST('chksel' . $this->dgGridID) : "";
        $this->schrstr    = ($this->issetREQUEST('dg_schrstr' . $this->dgGridID)) ? $this->REQUEST('dg_schrstr' . $this->dgGridID) : "";
        $this->dg_nrpp    = ($this->issetREQUEST('dg_nrpp' . $this->dgGridID)) ? $this->REQUEST('dg_nrpp' . $this->dgGridID) : 0;
        $this->dg_edt     = ($this->issetREQUEST('dg_edt' . $this->dgGridID)) ? $this->REQUEST('dg_edt' . $this->dgGridID) : 0;
        $this->setOrderby = (isset($this->setOrderby)) ? $this->setOrderby : "";
        if ($this->dg_nrpp > 0)
            $this->maxRec = $this->dg_nrpp;
    }
    function tpl($label, $text)
    {
        $this->plantilla = str_replace("[" . strtoupper($label) . "]", $text, $this->plantilla);
    }

    function processData()
    {
#       var_dump("$this->DG_ajaxid processData\r\n\r\n\r\n");
        $fldCond     = " ({$this->backtick}{$this->keyfield}{$this->backtick}=%s)";
        $updWhere    = (empty($this->where)) ? " WHERE ({$fldCond})" : str_replace("WHERE", "WHERE ({$fldCond}) and ", ($this->where));
        $isNumericID = (is_numeric($this->dgrtd)) ? true : false;
        switch ($this->DG_ajaxid)
        {
            case 2:
                if (!empty($this->search))
                {
                    $fs = (($this->issetREQUEST("dgfs{$this->dgGridID}"))) ? $this->REQUEST("dgfs{$this->dgGridID}") : "";
                    echo $this->selectCombo($fs, $this->schrstr);
                    die();
                }
                else
                    die("<div class='dgError'>" . $this->message['cannotsearch'] . "</div>");
                break;
            case 3:
                if ($this->delBtn)
                {
                    if ($this->vcode == md5($this->salt . $this->dgrtd))
                    {
                        if (function_exists("str_ireplace"))
                            $updWhere = str_ireplace(" like ", " LIKE ", $updWhere);
                        $updWhere  = str_replace(" like ", " LIKE ", $updWhere);
                        $tmp       = explode("LIKE", $updWhere);
                        $tmp[0]    = sprintf($tmp[0], magic_quote($this->dgrtd, $isNumericID));
                        $delWhere  = implode(" LIKE ", $tmp);
                        $strDelete = "DELETE FROM {$this->backtick}{$this->tablename}{$this->backtick} $delWhere";
                        $this->SQL_query($strDelete);
                        $this->strDebug[] = "DELETE: " . $strDelete;
                    }
                    else
                    {
                        die("<span class='dgError'>" . $this->message["errcode"] . "</span>");
                    }
                }
                else
                {
                    die("<div class='dgError'>" . $this->message['cannotdel'] . "</div>");
                };
                break;
            case 4:
                $dieMessage = "";
                if (!empty($this->ajaxEditable))
                {
                    list($value, $keyValue) = explode(".-.", $this->dgrtd);
                    $idLen  = strlen($this->dgGridID);
                    $keyLen = strlen($value);
                    $value  = $this->left($value, ($keyLen - $idLen));
                    if ($this->vcode == md5($this->salt . $value . ":toEdit:" . $keyValue))
                    {
                        $this->nt    = $this->GetSQLValueString($this->nt, $this->fieldsArray["$value"]["mask"]);
                        $isNumericID = (is_numeric($keyValue)) ? true : false;
                        $updWhere    = str_replace("%s)", magic_quote($keyValue, $isNumericID) . ")", $updWhere);

                        if (strtolower($this->sqlcharset) != '')
                        {
                            $newData = magic_quote($this->nt);
                            $this->SQL_query("SET character_set_results={$this->sqlcharset}");
                            $this->SQL_query("SET character_set_client={$this->sqlcharset}");
                            $this->SQL_query("SET character_set_connection={$this->sqlcharset}");
                        }
                        else
                        {
                            $newData = utf8_decode(magic_quote($this->nt));
                        }

                        $tablename = (isset($this->updateOther)) ? $this->updateOther : $this->tablename;
                        $strUpdate = "UPDATE {$this->backtick}{$tablename}{$this->backtick} set {$this->backtick}$value{$this->backtick}=" . $newData . " {$updWhere}";
                        $err       = false;
                        $dataType  = $this->fieldsArray["$value"]["datatype"];
                        $selData   = $this->fieldsArray["$value"]["select"];
                        $mask      = $this->fieldsArray["$value"]["mask"];
                        $this->SQL_query($strUpdate);

                        $error = $this->errorSQL;
                        if ($this->bypass_ajax_error) $error = false;

                        if ($error)
                        {
                            if (!$this->suppress_error)
                                echo "&lt;ERROR&gt;";
                        }
                        else
                        {
                            $strGetData = "SELECT {$this->backtick}$value{$this->backtick} FROM {$this->backtick}{$this->tablename}{$this->backtick} {$updWhere}";
                            $arrData    = $this->SQL_query($strGetData);
                            $value      = $this->getFieldData($value, $keyValue, $arrData[0]);
                            echo str_replace("&amp;nbsp;", "&nbsp;", $value);
                            /*$this->fldValue);*/
                            $strSelect = "SELECT {$this->backtick}" . implode("{$this->backtick},{$this->backtick}", $this->getFields("0,1,2,3,5")) . "{$this->backtick} FROM {$this->backtick}{$this->tablename}{$this->backtick} {$updWhere}";
                            $arrData   = $this->SQL_query($strSelect);
                            $rowRes    = (isset($arrData[0])) ? $arrData[0] : array();
                            $claux     = $stlaux = "";
                            if (isset($this->cellCondition["$value"]))
                            {
                                foreach ($this->cellCondition["$value"] as $key => $condition)
                                {
                                    $this->tmpCondition["$key"] = (!empty($condition)) ? "if (" . strtr($condition, array(
                                        "['" => "\$rowRes['"
                                    )) . ") { \$claux .= ' '. \"" . (strtr($this->cellStyle["$value"]["$key"], array(
                                        "['" => "\$rowRes['"
                                    ))) . "\"; }" : "";
                                    eval($this->tmpCondition["$key"]);
                                }
                            }
                            echo "
                            <script>
                                obj = document.getElementById('{$this->dgrtd}').parentNode;
                                cls = obj.className.split(' ');
                                obj.className = cls[0]+' {$claux}';
                            </script>";
                        }
                    }
                    else
                    {
                        $dieMessage = "<span class='dgError'>" . $this->message["errcode"] . "</span>";
                    }
                }
                else
                {
                    $dieMessage = "<div class='dgError'>" . $this->message["cannotedit"] . "</div>";
                };
                die($dieMessage);
                break;
            case 5:
                if (!$this->addBtn and !$this->updBtn and !$this->chkBtn)
                    die("<div class='dgError'>{$this->message['cannotadd']}</div>");
                $isadding   = ($this->dgrtd == '') ? true : false;
                $isediting  = $isviewing = false;
                $hiddenData = "";
                $campos     = array();
                if ($isadding)
                {
                    if (!$this->addBtn)
                        die("<div class='dgError'>{$this->message['cannotadd']}</div>");
                    $this->dgrtd = -1;
                    $img         = $this->images["add"];
                    $msg         = $this->message['addRecord'];
                    $access      = 'N';
                    $arrData     = array(
                        "0" => array()
                    );
                    $pathImg     = (isset($this->img_path['add']) ? $this->img_path['add'] : $this->imgpath);
                }
                else
                {
                    if (substr($this->vcode, 0, 4) == "view")
                    {
                        $md        = "view" . md5($this->salt . $this->dgrtd);
                        $isviewing = $this->isviewing = true;
                        if (!$this->chkBtn)
                            die("<div class='dgError'>{$this->message['cannotedit']}</div>");
                        $img     = $this->images["view"];
                        $msg     = $this->message["chkRecord"];
                        $access  = 'V';
                        $pathImg = (isset($this->img_path['view']) ? $this->img_path['view'] : $this->imgpath);
                    }
                    else
                    {
                        $md        = md5($this->salt . $this->dgrtd);
                        $isediting = true;
                        if (!$this->updBtn)
                            die("<div class='dgError'>{$this->message['cannotedit']}</div>");
                        $img     = $this->images["edit"];
                        $msg     = $this->message["edtRecord"];
                        $access  = 'E';
                        $pathImg = (isset($this->img_path['edit']) ? $this->img_path['edit'] : $this->imgpath);
                    }
                    if ($this->vcode != $md)
                        die("<span class='dgError'>{$this->message['errcode']}</span>");
                    if (function_exists("str_ireplace"))
                        $updWhere = str_ireplace(" like ", " LIKE ", $updWhere);
                    $updWhere = str_replace(" like ", " LIKE ", $updWhere);
                    $tmp      = explode("LIKE", $updWhere);
                    $tmp[0]   = sprintf($tmp[0], magic_quote($this->dgrtd, $isNumericID));
                    $addWhere = implode(" LIKE ", $tmp);
                    if (strtolower($this->sqlcharset) != '')
                    {
                        $this->SQL_query("SET character_set_results={$this->sqlcharset}");
                        $this->SQL_query("SET character_set_client={$this->sqlcharset}");
                        $this->SQL_query("SET character_set_connection={$this->sqlcharset}");
                    }
                    $strSelect = "SELECT {$this->backtick}" . implode("{$this->backtick},{$this->backtick}", $this->getFields("0,1,2,3,5")) . "{$this->backtick} FROM {$this->backtick}{$this->tablename}{$this->backtick} {$this->tableAlias} {$addWhere}";
                    $arrData   = $this->SQL_query($strSelect);
                    if (isset($this->processDataBefore))
                        eval("\$arrData = {$this->processDataBefore}(\$arrData);");
                    if (isset($this->processData))
                        eval("\$arrData = {$this->processData}(\$arrData);");
                    if (isset($this->processDataAfter))
                        eval("\$arrData = {$this->processDataAfter}(\$arrData);");
                };


                $firstField = "";
                if ($this->template)
                {
                    $filename = $this->templatePath . str_replace(".php", "_template.php", $this->scriptName);
                    if (!file_exists($filename))
                        $filename = $this->templatePath . str_replace(".php", "_template.html", $this->scriptName);
                    if (!file_exists($filename))
                    {
                        $this->template = false;
                    }
                    else
                    {
                        $handle          = fopen($filename, "rb");
                        $this->plantilla = fread($handle, filesize($filename));
                        fclose($handle);
                        if ($isadding and isset($this->actHeader["add"]))
                            $this->tpl("HEADER", $this->actHeader["add"]);
                        if ($isediting and isset($this->actHeader["edit"]))
                            $this->tpl("HEADER", $this->actHeader["edit"]);
                        if ($isviewing and isset($this->actHeader["view"]))
                            $this->tpl("HEADER", $this->actHeader["view"]);
                        if ($isadding and isset($this->actFooter["add"]))
                            $this->tpl("FOOTER", $this->actFooter["add"]);
                        if ($isediting and isset($this->actFooter["edit"]))
                            $this->tpl("FOOTER", $this->actFooter["edit"]);
                        if ($isviewing and isset($this->actFooter["view"]))
                            $this->tpl("FOOTER", $this->actFooter["view"]);
                        $tof             = "0,1,2,6,7";
                        $fieldsTodisplay = $this->getFields($tof, $access);
                        foreach ($fieldsTodisplay as $value)
                        {
                            $this->setTitle($value);
                            $this->isInternalEditing = true;
                            $this->getFieldData($value, $this->dgrtd, $arrData[0]);
                            $strHeader  = $this->columnTitle;
                            $dataType   = $this->fieldsArray["$value"]["datatype"];
                            $mask       = $this->fieldsArray["$value"]["mask"];
                            $fldLengt   = $this->fieldsArray["$value"]["maxlength"];
                            $fldname    = $this->fieldsArray["$value"]["strfieldName"];
                            $selData    = $this->fieldsArray["$value"]["select"];
                            $isreadonly = ($this->fieldsArray["$value"]["inputtype"] == 1) ? true : false;
                            $ishidden   = ($this->fieldsArray["$value"]["inputtype"] == 2) ? true : false;
                            $msgField   = (isset($this->validations["$value"]["msgField"])) ? $this->validations["$value"]["msgField"] : "";
                            $msgError   = (isset($this->validations["$value"]["errormsg"])) ? $this->validations["$value"]["errormsg"] : "";
                            $default    = ($isadding) ? $this->fieldsArray["$value"]["default"] : $this->fldValue;
                            $ischeck    = ($dataType == 'check') ? ":check" : "";
                            $inputFld   = $this->getInputField($value, $this->dgrtd, $arrData[0], $default);
                            if (!$isreadonly and !$ishidden and empty($firstField))
                                $firstField = $value;
                            if ($isviewing or $isreadonly or $ishidden)
                            {
                                $default = $this->putAcutes($default);
                                $hiddenData .= "<input id='dgFld{$fldname}' type='hidden' value='" . $default . "'{$this->sl}>{$this->br}";
                            }
                            else
                            {
                                $default = $inputFld . "<div id='edtMsg{$fldname}' class='dgMsgInput'>{$msgField}</div>{$this->br}" . "<div id='edtErr{$fldname}' class='dgErrorInput' style='display:none'>$msgError</div>{$this->br}";
                            }
                            $campos[] = "dgFld{$fldname}" . $ischeck;
                            $d        = (substr($strHeader, -1) == ":") ? "" : (!empty($strHeader) ? ":" : "");
                            $d        = (substr($strHeader, -1) == ":") ? "" : ":";
                            $this->tpl("INPUT_{$fldname}", $default);
                        }
                        if ($isediting or $isadding)
                        {
                            $strArrFields = "var arrFields = new Array(\"" . implode("\",\"", $campos) . "\")";
                            if (isset($this->saveaddnew))
                                $this->tpl("SAVE_ADD", "<input id='snButton' type='button' value='{$this->message['savenew']}' class='dgInput' onclick='if (!DG_validAll{$this->dgGridID}()) return false;$strArrFields;{$this->saveAddAction}arrFields,\"$this->dgrtd\");'{$this->sl}>&nbsp;&nbsp;{$this->br}");
                            $this->tpl("SAVE", "<input id='svButton' type='button' value='{$this->message['save']}' class='dgInput' onclick='if (!DG_validAll{$this->dgGridID}()) return false;$strArrFields;{$this->saveAction}arrFields,\"$this->dgrtd\")'{$this->sl}>&nbsp;&nbsp;{$this->br}");
                            $this->tpl("CANCEL", "<input id='clButton' type='button' value='{$this->message['cancel']}' class='dgInput' onclick='{$this->actionCloseDiv}'{$this->sl}>{$this->br}");
                        }
                        else
                            $this->tpl("CLOSE", "<input id='clButton' type='button' value='{$this->message['close']}' class='dgInput' onclick='{$this->actionCloseDiv}'{$this->sl}>{$this->br}");
                        $strReturn = $this->plantilla;
                        die($strReturn);
                    }
                }
                if (!$this->template)
                {
                    if ($this->nowindow)
                    {
                        $strReturn = "<div id='DG_subdiv{$this->dgGridID}' class='dgInnerDiv' style='text-align:left; height:auto;'>{$this->br}";
                        $strReturn .= "<table class='dg_dataTable'>{$this->br}";
                        $strReturnC = "<caption class='editTitle'>{$msg}</caption>";
                        if ($this->actionCloseDiv == "DG_closeDiv();")
                            $this->actionCloseDiv = "DG_closeAdd();";
                    }
                    else
                    {
                        $strReturn = "<div id='DG_addDIV{$this->dgGridID}' align='left' class='dgSearchDiv opaque dgAddWindow'>{$this->br}";
                        $strReturn .= "<span class='dgSearchTit' onmousedown='DG_clickCapa(event, this, \"DG_addDIV{$this->dgGridID}\")' onmouseup='DG_liberaCapa(\"DG_addDIV{$this->dgGridID}\")'>";
                        $strReturn .= "<img border='0' src='{$pathImg}{$img}' alt='{$msg}' title='{$msg}' width='16' height='16' {$this->sl}>{$msg}</span>{$this->br}";
                        $strReturn .= "<img style='cursor:pointer; float:right' src='" . (isset($this->img_path['close']) ? $this->img_path['close'] : $this->imgpath) . "{$this->images['close']}' alt='{$this->message['close']}' title='{$this->message['close']}' onclick='{$this->actionCloseDiv}'{$this->sl}>{$this->br}";
                        $strReturn .= "<div id='DG_subdiv{$this->dgGridID}' class='dgInnerDiv' style='text-align:left; height:auto;'>{$this->br}<table class='dg_dataTable'>{$this->br}";
                        $strReturnC = "";
                    }
                    $alt        = false;
                    $strReturnH = "<thead><tr><th colspan='2'>";
                    if ($isadding and isset($this->actHeader["add"]))
                        $strReturnH .= $this->putAcutes($this->actHeader["add"]);
                    if ($isediting and isset($this->actHeader["edit"]))
                        $strReturnH .= $this->putAcutes($this->actHeader["edit"]);
                    if ($isviewing and isset($this->actHeader["view"]))
                        $strReturnH .= $this->putAcutes($this->actHeader["view"]);
                    $strReturnH .= "</th></tr></thead>";
                    $strReturnF = "<tfoot><tr><th colspan='2'>";
                    if ($isadding and isset($this->actFooter["add"]))
                        $strReturnF .= $this->putAcutes($this->actFooter["add"]);
                    if ($isediting and isset($this->actFooter["edit"]))
                        $strReturnF .= $this->putAcutes($this->actFooter["edit"]);
                    if ($isviewing and isset($this->actFooter["view"]))
                        $strReturnF .= $this->putAcutes($this->actFooter["view"]);
                    $strReturnB      = "<tbody>";
                    $tof             = "0,1,2,6,7";
                    $fieldsTodisplay = $this->getFields($tof, $access);
                    foreach ($fieldsTodisplay as $value)
                    {
                        $this->setTitle($value);
                        $this->isInternalEditing = true;
                        $this->getFieldData($value, $this->dgrtd, $arrData[0]);
                        $strHeader  = $this->columnTitle;
                        $dataType   = $this->fieldsArray["$value"]["datatype"];
                        $mask       = $this->fieldsArray["$value"]["mask"];
                        $fldLengt   = $this->fieldsArray["$value"]["maxlength"];
                        $fldname    = $this->fieldsArray["$value"]["strfieldName"];
                        $selData    = $this->fieldsArray["$value"]["select"];
                        $isreadonly = ($this->fieldsArray["$value"]["inputtype"] == 1) ? true : false;
                        $ishidden   = ($this->fieldsArray["$value"]["inputtype"] == 2) ? true : false;
                        $msgField   = (isset($this->validations["$value"]["msgField"])) ? $this->validations["$value"]["msgField"] : "";
                        $msgError   = (isset($this->validations["$value"]["errormsg"])) ? $this->validations["$value"]["errormsg"] : "";
                        $default    = ($isadding) ? $this->fieldsArray["$value"]["default"] : $this->fldValue;
                        $ischeck    = ($dataType == 'check') ? ":check" : "";
                        $inputFld   = $this->getInputField($value, $this->dgrtd, $arrData[0], $default);
                        if (!$isreadonly and !$ishidden and empty($firstField))
                            $firstField = $value;
                        if ($isviewing or $isreadonly or $ishidden)
                        {
                            $default = $this->putAcutes($default);
                            $hiddenData .= "<input id='dgFld{$fldname}' type='hidden' value='" . $default . "'{$this->sl}>{$this->br}";
                        }
                        else
                        {
                            $default = $inputFld . "<div id='edtMsg{$fldname}' class='dgMsgInput'>{$msgField}</div>{$this->br}" . "<div id='edtErr{$fldname}' class='dgErrorInput' style='display:none'>$msgError</div>{$this->br}";
                        }
                        $campos[] = "dgFld{$fldname}" . $ischeck;
                        $d        = (substr($strHeader, -1) == ":") ? "" : ":";
                        if (!$ishidden)
                        {
                            $clAlt = ($alt = !$alt) ? "alt" : "norm";
                            $strReturnB .= "<tr class='dgRows{$clAlt}TR'>{$this->br}" . "<td class='dgAddNames' >{$strHeader}{$d}</td>{$this->br}" . "<td class='dgAddInputs'>{$default}{$this->br}" . "</td>{$this->br}</tr>{$this->br}";
                        }
                    }
                    $strReturnB .= "</tbody></table>{$this->br}";
                    $strReturnB .= $hiddenData;
                    $strReturnB .= "</div>{$this->br}</div>{$this->br}";
                    if (!$this->nowindow)
                        $strReturnB .= "<div id='dgAdd{$this->FormName}' class='dgAddDiv' style='width:" . $this->REQUEST('x') . "px;height:" . $this->REQUEST('y') . "px'>{$this->br}</div>";
                    $strReturnF .= "<div class='edtBtns'>{$this->br}";
                    if ($isediting or $isadding)
                    {
                        $strArrFields = "var arrFields = new Array(\"" . implode("\",\"", $campos) . "\")";
                        if (isset($this->saveaddnew))
                            $strReturnF .= "<input id='snButton' type='button' value='{$this->message['savenew']}' class='dgInput' onclick='if (!DG_validAll{$this->dgGridID}()) return false;$strArrFields;{$this->saveAddAction}arrFields,\"$this->dgrtd\");'{$this->sl}>&nbsp;&nbsp;{$this->br}";
                        $strReturnF .= "<input id='svButton' type='button' value='{$this->message['save']}' class='dgInput' onclick='if (!DG_validAll{$this->dgGridID}()) return false;$strArrFields;{$this->saveAction}arrFields,\"$this->dgrtd\")'{$this->sl}>&nbsp;&nbsp;{$this->br}";
                        $strReturnF .= "<input id='clButton' type='button' value='{$this->message['cancel']}' class='dgInput' onclick='{$this->actionCloseDiv}'{$this->sl}>{$this->br}";
                    }
                    else
                        $strReturnF .= "<input id='clButton' type='button' value='{$this->message['close']}' class='dgInput' onclick='{$this->actionCloseDiv}'{$this->sl}>{$this->br}";
                    $strReturnF .= "</div></th>{$this->br}</tr>{$this->br}</tfoot>{$this->br}";
                    if ($isadding)
                        $strReturnF .= "<script>document.getElementById('dgFld{$firstField}').focus();</script>";
                    if (isset($this->tinyMCE) and !empty($this->elements_tinyMCE) and !$isviewing)
                    {
                        $strReturnF .= "<script>";
                        $strReturnF .= "tinyMCE.init({ mode:'exact', theme:'" . $this->tinyMCE_theme . "', elements:'" . implode(",", $this->elements_tinyMCE) . "'" . $this->tinyMCE_options . "});";
                        $strReturnF .= "</script>";
                    }
                    unset($this->fieldsTMP);
                    $strOutput = $strReturn . $strReturnC . $strReturnH . $strReturnF . $strReturnB;
                    if (isset($this->replaceOutputCase5))
                        $strOutput = str_replace($this->replaceOutputCase5, $this->replaceOutputCase5With, $strOutput);
                    # die($strOutput); teik
                    {
                        if (!$this->nowindow) die($strOutput);
                        if ($this->retcode) return $strOutput; else echo $strOutput;
                    }
                };
                break;
            case 6:
                if (!$this->addBtn and !$this->updBtn)
                {
                    die("<div class='dgError'>{$this->message['cannotadd']}</div>");
                }
                else
                {
                    $isadding = ($this->dgrtd == -1) ? true : false;
                    if ($isadding and !$this->addBtn)
                        die("<div class='dgError'>{$this->message['cannotadd']}</div>");
                    else if (!$isadding and !$this->updBtn)
                        die("<div class='dgError'>{$this->message['cannotedit']}</div>");
                    if (function_exists("str_ireplace"))
                        $updWhere = str_ireplace(" like ", " LIKE ", $updWhere);
                    $updWhere   = str_replace(" like ", " LIKE ", $updWhere);
                    $tmp        = explode("LIKE", $updWhere);
                    $tmp[0]     = sprintf($tmp[0], magic_quote($this->dgrtd, $isNumericID));
                    $updWhere   = implode(" LIKE ", $tmp);
                    $sqlFields  = $comma = $sqlValues = "";
                    $calculated = $rowRes = array();
                    $cfields    = $this->getFields("0,2", "N");
                    $afields    = $this->getFields("0,2", "E");
                    $cfields    = array_merge($cfields, $afields);
                    $cfields    = array_unique($cfields);
                    foreach ($cfields as $fldName)
                    {
                        if ($this->fieldsArray["$fldName"]["datatype"] == "calc" and substr($this->fieldsArray["$fldName"]["mask"], 0, 5) == 'scalc')
                        {
                            $calculated[] = $fldName;
                        }
                        else
                        {
                            if (substr($this->fieldsArray["$fldName"]["mask"], 0, 5) == "image")
                            {
                                if ($this->REQUEST("dgFld" . $fldName) != "current")
                                {
                                    if (isset($this->fieldsArray["$fldName"]["isblob"]))
                                    {
                                        $tmp_name = $this->uploadDirectory . $this->REQUEST("dgFld" . $fldName);
                                        $fp       = fopen($tmp_name, "rb");
                                        $buffer   = fread($fp, filesize($tmp_name));
                                        fclose($fp);
                                        $rowRes[$fldName] = $vrField = "'" . addslashes($buffer) . "'";
                                    }
                                    else
                                    {
                                        $rowRes[$fldName] = $vrField = magic_quote($this->REQUEST("dgFld" . $fldName));
                                    }
                                    if (!($this->fieldsArray["$fldName"]["inputtype"] == 1 and $vrField == ''))
                                    {
                                        if ($isadding)
                                        {
                                            $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick;
                                            $sqlValues .= $comma . $vrField;
                                        }
                                        else
                                        {
                                            $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick . "=" . $vrField;
                                        }
                                        $comma = ",";
                                    }
                                }
                                else if ($this->REQUEST("dgFld" . $fldName) == "delete")
                                {
                                    if ($isadding)
                                    {
                                        $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick;
                                        $sqlValues .= $comma . "''";
                                    }
                                    else
                                    {
                                        $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick . "=" . "''";
                                    }
                                    $comma = ",";
                                }
                            }
                            else
                            {
                                if ($this->sqlcharset != '')
                                {
                                    $rowRes[$fldName] = $vrField = $this->GetSQLValueString($this->REQUEST("dgFld" . $fldName), $this->fieldsArray["$fldName"]["mask"]);
                                }
                                else
                                {
                                    $rowRes[$fldName] = $vrField = $this->GetSQLValueString(utf8_decode($this->REQUEST("dgFld" . $fldName)), $this->fieldsArray["$fldName"]["mask"]);
                                }
                                if (!($this->fieldsArray["$fldName"]["inputtype"] == 1 and $vrField == ''))
                                {
                                    if ($isadding)
                                    {
                                        $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick;
                                        $sqlValues .= $comma . magic_quote($vrField);
                                    }
                                    else
                                    {
                                        $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick . "=" . magic_quote($vrField);
                                    }
                                    $comma = ",";
                                }
                            }
                        }
                        foreach ($calculated as $fldName)
                        {
                            $this->getFieldData($fldName, 0, $rowRes);
                            $mask = $this->fieldsArray["$fldName"]["mask"];
                            if (strpos($mask, '//') !== false)
                                list($mask1, $mask) = explode('//', $mask);
                            $vrField = $this->GetSQLValueString($this->fldValue, $mask);
                            if (!empty($fldName))
                            {
                                if ($isadding)
                                {
                                    $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick;
                                    $sqlValues .= $comma . magic_quote($vrField);
                                }
                                else
                                {
                                    $sqlFields .= $comma . $this->backtick . $fldName . $this->backtick . "=" . magic_quote($vrField);
                                }
                                $comma = ",";
                            }
                        }
                    }
                    /*end foreach */
                    if (strtolower($this->sqlcharset) != '')
                    {
                        $this->SQL_query("SET character_set_results={$this->sqlcharset}");
                        $this->SQL_query("SET character_set_client={$this->sqlcharset}");
                        $this->SQL_query("SET character_set_connection={$this->sqlcharset}");
                    }
                    $tablename        = (isset($this->updateOther)) ? $this->updateOther : $this->tablename;
                    $strSQL           = ($isadding) ? "INSERT INTO {$this->backtick}{$tablename}{$this->backtick} ($sqlFields) VALUES ($sqlValues)" : "UPDATE {$this->backtick}{$tablename}{$this->backtick} {$this->tableAlias} SET {$sqlFields} {$updWhere}";
                    $this->strDebug[] = "SQL INSERT/UPDATE: " . $strSQL;
                    $this->SQL_query($strSQL);
                };
                break;
            case 7:
                if ($this->DG_ajaxid == 7 and $this->dgrtd == "S" and empty($this->chksel))
                {
                    echo "<script>alert('" . $this->message['norecselect'] . "');</script>";
                }
                else
                {
                    $lnBr        = "\n";
                    $sep         = $tab = "\t";
                    $exportTypes = "0,1,3,4,5";
                    $maskCell    = "%s{$sep}";
                    $maskRow     = "%s{$lnBr}";
                    foreach ($this->fieldsArray as $key => $value)
                    {
                        $permissions = (isset($this->fieldsArray["$key"]["permissions"])) ? $this->fieldsArray["$key"]["permissions"] : "";
                        if ($this->allowed("X+", $permissions))
                            $this->fieldsArray["$key"]["inputtype"] = 0;
                        else if ($this->allowed("X-", $permissions))
                            $this->fieldsArray["$key"]["inputtype"] = 2;
                    }
                    $fieldsTodisplay = $this->getFields($exportTypes, "X");
                    list($useg, $seg) = explode(" ", microtime());
                    $file = "DG{$this->vcode}" . ($seg . str_replace(".", "", $useg));
                    if ($this->vcode != 'I' and $this->vcode != 'P')
                    {
                        header('Content-type: text/html; charset=utf-8');
                        header("Pragma: public");
                        header("Expires: 0");
                        header("Cache-Control: must-revalidate, post-check=0, pre-check=0, max-age=0");
                        header('Last-Modified: ' . date('D, d M Y H:i:s'));
                        header("Content-Transfer-Encoding: binary");
                        header("Content-Type: application/force-download");
                    }
                    if (class_exists("PHPExcel") and !class_exists("FPDF") and $this->vcode == 'P')
                    {
                        $this->vcode               = 'E';
                        $this->phpExcelOutput      = "PDF";
                        $this->phpExcelContentType = "application/pdf";
                        $this->phpExcelExtension   = "pdf";
                    }
                    switch ($this->vcode)
                    {
                        case "P":
                            if (isset($this->chinesePDF))
                            {
                                if (!class_exists("PDF_Unicode"))
                                    die("PDF_Unicode not found! please include chinese-unicode.php");
                                $pdf = new PDF_Unicode();
                                $pdf->Open();
                                $pdf->FPDF($this->PDForientation);
                                $pdf->AddUniCNShwFont('uni', 'DFKai-SB');
                                $this->PDFfont = 'uni';
                            }
                            else
                            {
                                if (!class_exists("FPDF"))
                                    die("FPDF not found! please download it from www.fpdf.org and include fpdf.php");
                                $pdf = new FPDF($this->PDForientation);
                            };
                            $pdf->SetFont($this->PDFfont, '', $this->PDFfontsize);
                            $pdf->AddPage();
                            $pdf->SetFillColor($this->PDFfill['R'], $this->PDFfill['G'], $this->PDFfill['B']);
                            $pdf->SetTextColor(0);
                            $pdf->SetDrawColor($this->PDFdraw['R'], $this->PDFdraw['G'], $this->PDFdraw['B']);
                            $pdf->SetLineWidth(.1);
                            $pdf->SetFont('', 'B');
                            $pdf->SetX(3);
                            $w = array();
                            foreach ($fieldsTodisplay as $fldName)
                            {
                                $w["$fldName"] = $this->fieldsArray["$fldName"]["columnwidth"] * 0.20;
                                $pdf->Cell($w["$fldName"], 4, $this->fieldsArray["$fldName"]["strHeader"], 1, 0, "C", 1);
                            };
                            $pdf->Ln();
                            $pdf->SetFillColor(255, 255, 255);
                            $pdf->SetTextColor(0);
                            $pdf->SetFont('');
                            break;
                        case "I":
                            if (!isset($this->exportMagma))
                            {
                                header("Content-Disposition: inline; filename={$file}.html;");
                                $sep   = "";
                                $tdcsp = 0;
                                echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . $this->br;
                                echo "<html>{$this->br}<head>{$this->br}";
                                echo "<meta http-equiv='Content-Type' content='text/html; charset={$this->charset}'>";
                                echo "<link rel='stylesheet' type='text/css' href='{$this->cssPrinter}' title='b-w'>";
                                echo "<title>{$this->titulo}</title>{$this->br}</head>{$this->br}<body>{$this->br}";
                                echo "<h1>{$this->br}{$this->titulo}</h1>{$this->br}";
                                echo "<table class='dgTable' border='1' cellspacing='0' cellpadding='0'>{$this->br}<thead>{$this->br}<tr>{$this->br}";
                                foreach ($fieldsTodisplay as $fldName)
                                {
                                    $tdcsp++;
                                    echo "<th class='dgTitles' style='width:" . $this->fieldsArray["$fldName"]["columnwidth"] . "'>" . $this->fieldsArray["$fldName"]["strHeader"] . "</th>{$this->br}";
                                }
                                echo "</tr>{$this->br}</thead><tbody>{$this->br}";
                                $maskCell = "<td align='%s' class='dgCells'>%s</td>";
                                $maskRow  = "<tr class='dgRows'>%s</tr>";
                            };
                            break;
                        case "X":
                            $this->br = $lnBr;
                            header('Content-Type: application/x-octet-stream');
                            $sep = "";
                            header("Content-Disposition: attachment; filename={$file}.xml;");
                            echo "<?xml version=\"1.0\" encoding=\"{$this->charset}\" ?>{$this->br}";
                            echo "<{$this->tablename}>{$this->br}";
                            echo "<Header>{$this->br}{$tab}<Title>{$this->titulo}</Title>{$this->br}";
                            echo "{$tab}<ColumnNames>{$this->br}";
                            foreach ($fieldsTodisplay as $fldName)
                            {
                                echo "{$tab}{$tab}<$fldName>" . $this->fieldsArray["$fldName"]["strHeader"] . "</$fldName>{$this->br}";
                            };
                            echo "{$tab}</ColumnNames>{$this->br}</Header>{$this->br}<Body>{$this->br}";
                            $maskCell = "{$tab}{$tab}<%s>%s</%s>{$this->br}";
                            $maskRow  = "{$tab}<row>{$this->br}%s{$tab}</row>{$this->br}";
                            break;
                        case "E":
                            if (class_exists("PHPExcel"))
                            {
                                $objPHPExcel = new PHPExcel();
                                $objPHPExcel->setActiveSheetIndex(0);
                                $xlsDataArray = array();
                                if (!empty($this->titulo))
                                    $xlsDataArray[] = array(
                                        $this->titulo
                                    );
                                $tmpArray = array();
                                foreach ($fieldsTodisplay as $fldName)
                                    $tmpArray[] = $this->fieldsArray["$fldName"]["strHeader"];
                                $xlsDataArray[] = $tmpArray;
                            }
                            else
                            {
                                $this->br = $lnBr;
                                header("Content-Type: application/vnd.ms-excel");
                                header("Content-Disposition: attachment; filename={$file}.xls;");
                                $sep   = "";
                                $tdcsp = 0;
                                echo "<?xml version=\"1.0\" encoding=\"{$this->charset}\" ?>{$this->br}";
                                echo "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\" xmlns:html=\"http://www.w3.org/TR/REC-html40\">{$this->br}";
                                echo "<Worksheet ss:Name=\"{$this->tablename}\">{$this->br}";
                                echo "<Table>{$this->br}";
                                if (!empty($this->titulo))
                                {
                                    $tt = htmlspecialchars($this->titulo);
                                    echo "<Row>{$this->br}{$tab}<Cell><Data ss:Type=\"String\">{$tt}</Data></Cell>{$this->br}</Row>{$this->br}";
                                }
                                echo "<Row>{$this->br}";
                                foreach ($fieldsTodisplay as $fldName)
                                {
                                    $tdcsp++;

                                    //get ss:type for fieldsArray
                                    $ss_type = "String";
                                    $cell_data = $this->fieldsArray["$fldName"]["strHeader"];

                                    $replaced_comma = str_replace("," , "", $cell_data);
                                    if(is_numeric($replaced_comma)){
                                        $ss_type = "Number";
                                    }

                                    echo "{$tab}<Cell><Data ss:Type=\"{$ss_type}\">" . $this->fieldsArray["$fldName"]["strHeader"] . "</Data></Cell>{$this->br}";
                                    // echo "{$tab}<Cell><Data ss:Type=\"String\">" . $this->fieldsArray["$fldName"]["strHeader"] . "</Data></Cell>{$this->br}";
                                }
                                echo "</Row>{$this->br}";
                                $maskCell = "{$tab}<Cell><Data ss:Type=\"String\">%s</Data></Cell>{$this->br}";
                                $maskRow  = "<Row>{$this->br}%s</Row>{$this->br}";
                            }
                            break;
                        case "C":
                            header('Content-Type: application/x-octet-stream');
                            $sep      = $this->csvSeparator;
                            $maskCell = "%s{$sep}";
                            header("Content-Disposition: attachment; filename={$file}.csv;");
                        default:
                            echo "{$this->titulo}{$this->br}";
                            foreach ($fieldsTodisplay as $fldName)
                                echo $this->fieldsArray["$fldName"]["strHeader"] . $sep;
                            echo $lnBr;
                            break;
                    }
                    $this->rowsMaster = $this->rowsDetails = array();
                    $this->getData();
                    $this->isexporting = true;
                    foreach ($this->arrGridData as $key => $rowRes)
                    {
                        if ($this->vcode == 'P')
                            $pdf->SetX(3);
                        $cells     = "";
                        $arrkField = explode(".", $this->keyfield);
                        $kField    = (isset($arrkField[1])) ? $arrkField[1] : $arrkField[0];
                        $keyValue  = (empty($this->keyfield)) ? $key : $rowRes[$kField];
                        $csp       = 0;
                        $tmpArray  = array();

                        foreach ($fieldsTodisplay as $value)
                        {
                            $alignTD = $this->fieldsArray[$value]['align'];
                            $align   = strtoupper(substr($alignTD, 0, 1));
                            $this->getFieldData($value, $keyValue, $rowRes);
                            $vv = $this->fldValue;
                            $csp++;
                            switch ($this->vcode)
                            {
                                case "P":
                                    $pdf->Cell($w["$value"], 4, $vv, 1, 0, $align, 1);
                                    break;
                                case "X":
                                    $cells .= sprintf($maskCell, $value, $vv, $value);
                                    break;
                                case "E":
                                    if (class_exists("PHPExcel"))
                                    {
                                        $tmpArray[] = $vv;
                                        break;
                                    }
                                case "I":
                                case "E":
                                    if (!(isset($this->exportMagma) and $this->vcode == 'I'))
                                    {
                                        if ($this->vcode == 'I')
                                            $cells .= sprintf($maskCell, $alignTD, $vv);
                                        else{

                                            $type_ss = "String";

                                            $replaced_comma = str_replace("," , "", $vv);
                                            if(is_numeric($replaced_comma)){
                                                $type_ss = "Number";
                                            }

                                            //replace ss_type inside $maskCell with $type_ss
                                            $modified_maskCell = str_replace("ss:Type=\"String\"", "ss:Type=\"{$type_ss}\"", $maskCell);

                                            $cells .= sprintf($modified_maskCell, $vv);
                                        }
                                    }
                                    else
                                    {
                                        $cells .= "";
                                        foreach ($rowRes as $rKey => $rowData)
                                        {
                                            if (!is_numeric($rKey))
                                                $this->rowsMaster[$rowRes[$this->keyfield]][$rKey] = $rowData;
                                        }
                                    };
                                    break;
                                default:
                                    $cells .= sprintf($maskCell, $vv);
                                    break;
                            }
                        }
                        if (isset($this->exportDetails) and ($this->dg_edt == 1 or isset($this->exportDetails[$this->dg_edt])))
                        {
                            if (isset($this->exportDetails[$this->dg_edt]))
                            {
                                $this->exportDetails = $this->exportDetails[$this->dg_edt];
                                $this->dg_edt        = 1;
                            }
                            $sql    = $this->exportDetails['sql'];
                            $fields = explode(",", $this->exportDetails['parameters']);
                            $arg    = array();
                            foreach ($fields as $field)
                            {
                                $field = trim($field);
                                $sql   = str_replace("['{$field}']", $rowRes[$field], $sql);
                            }
                            $arrDetails = $this->SQL_query($sql);
                        }
                        switch ($this->vcode)
                        {
                            case "P":
                                $pdf->Ln();
                                break;
                            case "X":
                                echo sprintf($maskRow, $cells);
                                break;
                            case "E":
                                if (class_exists("PHPExcel"))
                                {
                                    $xlsDataArray[] = $tmpArray;
                                    break;
                                }
                            case "I":
                            case "E";
                                echo sprintf($maskRow, $cells);
                                if (isset($this->exportDetails) and $this->dg_edt == 1)
                                {
                                    $rows = "";
                                    $r    = 0;
                                    foreach ($arrDetails as $key => $rowDetails)
                                    {
                                        $cell = sprintf($maskCell, '', '', '');
                                        foreach ($rowDetails as $key => $cellData)
                                        {
                                            $align = (isset($this->exportDetails['align']['$key'])) ? $this->exportDetails['align']['$key'] : "";
                                            if (!is_numeric($key))
                                            {
                                                $cell .= sprintf($maskCell, $align, $cellData);
                                                $this->rowsDetails[$rowRes[$this->keyfield]][$r][$key] = $cellData;
                                            }
                                        }
                                        $rows .= sprintf($maskRow, $cell);
                                        $r++;
                                    }
                                    if (!(isset($this->exportMagma) and $this->vcode == 'I'))
                                    {
                                        echo "<tr><td colspan='{$tdcsp}'><table border='0' style='width:100%'>";
                                        echo $rows;
                                        echo "</table></td></tr>";
                                    }
                                };
                                break;
                            default:
                                echo sprintf($maskRow, $cells);
                                if (isset($this->exportDetails) and $this->dg_edt == 1)
                                {
                                    foreach ($arrDetails as $rowDetails)
                                    {
                                        $cell = sprintf($maskCell, '');
                                        foreach ($rowDetails as $key => $cellData)
                                        {
                                            if (!is_numeric($key))
                                                $cell .= sprintf($maskCell, $cellData);
                                        }
                                        echo sprintf($maskRow, $cell);
                                    }
                                };
                                break;
                        }
                    }
                    if (!empty($this->totalize))
                    {
                        if ($this->vcode == 'P')
                            $pdf->SetX(3);
                        $cells    = "";
                        $tmpArray = array();
                        foreach ($fieldsTodisplay as $value)
                        {
                            $alignTD = $this->fieldsArray[$value]['align'];
                            $align   = strtoupper(substr($alignTD, 0, 1));
                            $this->getFieldData($value, $keyValue, $rowRes, true);
                            $vv = $this->fldValue;
                            switch ($this->vcode)
                            {
                                case "P":
                                    $pdf->Cell($w["$value"], 4, $vv, 1, 0, $align, 1);
                                    break;
                                case "X":
                                    $cells .= sprintf($maskCell, $value, $vv, $value);
                                    break;
                                case "I":
                                    $cells .= sprintf(str_replace("class='dg", "class='dgTotal", $maskCell), $alignTD, $vv);
                                    break;
                                case "E":
                                    if (class_exists("PHPExcel"))
                                    {
                                        $tmpArray[] = $vv;
                                    }
                                    else
                                    {
                                        $cells .= sprintf($maskCell, $vv);
                                    }
                                    break;
                                default:
                                    $cells .= sprintf($maskCell, $vv);
                                    break;
                            }
                        }
                        switch ($this->vcode)
                        {
                            case "P":
                                $pdf->Ln();
                                break;
                            case "X":
                                $maskRow = "{$tab}<total>{$this->br}%s{$tab}</total>{$this->br}";
                            case "E":
                                if (class_exists("PHPExcel"))
                                {
                                    $xlsDataArray[] = $tmpArray;
                                    break;
                                }
                            default:
                                echo sprintf($maskRow, $cells);
                        }
                    }
                    switch ($this->vcode)
                    {
                        case "P":
                            $pdf->Output($file . ".pdf", 'D');
                            break;
                        case "X":
                            echo "</Body>{$this->br}<footer>{$this->footer}</footer>{$this->br}</{$this->tablename}>";
                            break;
                        case "E":
                            if (class_exists("PHPExcel"))
                            {
                                $objPHPExcel->getActiveSheet()->fromArray($xlsDataArray, NULL, 'A1');
                                if (!isset($this->phpExcelContentType))
                                    $this->phpExcelContentType = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
                                if (!isset($this->phpExcelExtension))
                                    $this->phpExcelExtension = "xslx";
                                header('Content-Type: ' . $this->phpExcelContentType);
                                header("Content-Disposition: attachment; filename={$file}.{$this->phpExcelExtension};");
                                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $this->phpExcelOutput);
                                $objWriter->save('php://output');
                            }
                            else
                            {
                                echo "<Row>{$this->br}{$tab}<Cell><Data ss:Type=\"String\">{$this->footer}</Data></Cell>{$this->br}</Row>{$this->br}";
                                echo "</Table>{$this->br}</Worksheet>{$this->br}</Workbook>";
                            }
                            break;
                        case "I":
                            if (!(isset($this->exportMagma) and $this->vcode == 'I'))
                                echo "</tbody>{$this->br}</table><p class='dgFooter'>{$this->footer}</p><script type='text/javascript'>print();</script>";
                            break;
                        default:
                            echo $this->footer;
                    }
                    if (!(isset($this->exportMagma) and $this->vcode == 'I'))
                        die();
                };
                $imgname   = $this->REQUEST("imgname{$this->dgGridID}");
                $img       = $this->images["upload"];
                $msg       = $this->message['upload'];
                $strReturn = "<div id='dg{$this->dgGridID}Addo{$this->FormName}' align='left' class='dgSearchDiv opaque dgAddWindow'>{$this->br}";
                $strReturn .= "<span class='dgSearchTit' onmousedown='DG_clickCapa(event, this, \"dgAddo{$this->FormName}\")' onmouseup='DG_liberaCapa(\"dgAddo{$this->FormName}\")'>";
                $strReturn .= "<img border='0' src='" . (isset($this->img_path['upload']) ? $this->img_path['upload'] : $this->imgpath) . "{$img}' alt='{$msg}' title='{$msg}' width='16' height='16' {$this->sl}>{$msg}</span>{$this->br}";
                $strReturn .= "<img style='cursor:pointer; float:right' src='" . (isset($this->img_path['close']) ? $this->img_path['close'] : $this->imgpath) . "{$this->images['close']}' alt='{$this->message['close']}' title='{$this->message['close']}' onclick='" . sprintf($this->actionCloseUpl, $this->dgGridID) . "'{$this->sl}>{$this->br}";
                $strReturn .= "<div id='DG_subdiv{$this->dgGridID}' class='dgInnerDiv' style='text-align:left; height:auto;'>{$this->br}<table class='dg_dataTable'>{$this->br}";
                $strReturn .= "<tr><td><table border='0' class='dgUpload'><tr><td>
                    <p>{$this->message['selectimage']}:</p>
                    <input id='DG_file{$this->dgGridID}' type='file' size='24' name='DG_file{$this->dgGridID}'{$this->sl}>
                    <input type='button' onclick=\"DG_upload(DG_goo('DG_file{$this->dgGridID}'))\" name='DG_u{$this->dgGridID}' id='DG_u{$this->dgGridID}' value='{$this->message['upload']}'{$this->sl}>
                    <input type='hidden' name='fldname{$this->dgGridID}' id='fldname{$this->dgGridID}' value='$this->dgrtd'{$this->sl}>
                    <input type='hidden' name='keyvalue{$this->dgGridID}' id='keyvalue{$this->dgGridID}' value='$this->vcode'{$this->sl}>
                    <input type='hidden' name='imgname{$this->dgGridID}' id='imgname{$this->dgGridID}' value='$imgname'{$this->sl}>
                    <input type='hidden' name='dg_uploading{$this->dgGridID}' id='dg_uploading{$this->dgGridID}' value='1'{$this->sl}>";
                $arrParams = explode("&", str_replace("&amp;", "&", $this->parameters));
                foreach ($arrParams as $parameter)
                {
                    $arrSubParam = explode("=", $parameter);
                    if (!empty($arrSubParam[0]))
                        $strReturn .= "<input type='hidden' name='" . $arrSubParam[0] . "' id='" . $arrSubParam[0] . "' value='" . $arrSubParam[1] . "'{$this->sl}>";
                };

                $strReturn .= "</td>{$this->br}</tr>{$this->br}</table>";
                $strReturn .= "</td>{$this->br}</tr>{$this->br}";
                $strReturn .= "<tr class='dgAddButons' >{$this->br}";
                $strReturn .= "<td align='center' style='color:#FFF'>{$this->br}";
                $strReturn .= "<input type='button' value='" . $this->message["close"] . "' class='dgInput' onclick='" . sprintf($this->actionCloseUpl, $this->dgGridID) . "'{$this->sl}>{$this->br}";
                $strReturn .= "</td>{$this->br}</tr>{$this->br}";
                $strReturn .= "</table>";
                echo $strReturn .= "</div>{$this->br}</div>{$this->br}<div id='dg{$this->dgGridID}Add{$this->FormName}' class='dgAddDiv' style='width:" . $this->REQUEST('x') . "px;height:" . $this->REQUEST('y') . "px'>{$this->br}</div>";
                die();
                break;
            case 9:
                list($value, $keyValue) = explode(".-.", $this->dgrtd);
                $xcod = $this->right($value, strlen($this->dgGridID));
                if ($xcod == $this->dgGridID)
                    $value = substr($value, 0, strlen($value) - strlen($this->dgGridID));
                $mask    = $this->fieldsArray["$value"]["mask"];
                $selData = $this->fieldsArray["$value"]["select"];
                if (strpos($mask, '//') === false)
                {
                    $mask1 = $mask;
                    $mask2 = '2';
                }
                else
                {
                    list($mask1, $mask2) = explode('//', $mask);
                    if (empty($mask2))
                        $mask2 = '2';
                };
                if (substr($mask, 0, 5) == 'scalc' and $keyValue != 'Total')
                {
                    $this->nt = $this->GetSQLValueString($this->nt, $this->fieldsArray["$value"]["mask"]);
                    if ($this->nt == "")
                        $this->nt = 0;
                    $isNumericID = (is_numeric($keyValue)) ? true : false;
                    $updWhere    = sprintf($updWhere, magic_quote($keyValue, $isNumericID));
                    $tablename   = (isset($this->updateOther)) ? $this->updateOther : $this->tablename;
                    $strUpdate   = "UPDATE {$this->backtick}{$tablename}{$this->backtick} set {$this->backtick}$value{$this->backtick}=" . magic_quote($this->nt) . " $updWhere";
                    $this->SQL_query($strUpdate);
                };
                echo $this->maskdata($this->nt, $mask2, 'number', $selData, $this->nt);
                echo "<input type='hidden' id='c{$this->dgGridID}{$value}.-.{$keyValue}' value='{$this->nt}'{$this->sl}>{$this->br}";
                die();
                break;
            case 10:
            case 11:
                if ($this->DG_ajaxid == 10)
                {
                    $ord = "DESC";
                    $ope = "<";
                }
                else
                {
                    $ord = "";
                    $ope = ">";
                };
                if (empty($this->where))
                    $elWhere = " WHERE {$this->setOrderby}{$ope}=" . magic_quote($this->dgrtd) . " ";
                else
                    $elWhere = str_replace("WHERE", "WHERE ({$this->setOrderby}{$ope}=" . magic_quote($this->dgrtd) . ") and ", $this->where);
                $strSQL    = "SELECT {$this->setOrderby} as myCampoOrd, {$this->keyfield} as myCampoKey FROM {$this->tablename} {$elWhere} ORDER BY {$this->setOrderby} {$ord} ";
                $arrValues = array();
                $arrCount  = 0;
                $arrResult = $this->SQL_query($strSQL, 2);
                foreach ($arrResult as $rowRes)
                {
                    $arrValues[$arrCount]["key"] = $rowRes["myCampoKey"];
                    $arrValues[$arrCount]["ord"] = $rowRes["myCampoOrd"];
                    $arrCount++;
                };
                if ($arrCount == 2)
                {
                    $x0        = 0;
                    $x1        = 1;
                    $tablename = (isset($this->updateOther)) ? $this->updateOther : $this->tablename;
                    for ($x = 0; $x <= 1; $x++)
                    {
                        $strSQL = sprintf("UPDATE {$this->backtick}{$tablename}{$this->backtick} SET {$this->setOrderby}=%s WHERE {$this->backtick}{$this->keyfield}{$this->backtick}=%s ", magic_quote($arrValues[$x0]["ord"]), magic_quote($arrValues[$x1]["key"]));
                        $this->SQL_query($strSQL);
                        $x0 = 1;
                        $x1 = 0;
                    }
                };
                break;
            case 12:
                if (!empty($this->chksel))
                {
                    $arrSelection = explode(",", $this->chksel);
                    foreach ($arrSelection as $key => $value)
                        $arrSelection[$key] = magic_quote(trim($value));
                    $chksel    = implode(",", $arrSelection);
                    $fldCond   = "{$this->backtick}{$this->keyfield}{$this->backtick} IN ({$chksel})";
                    $delWhere  = (empty($this->where)) ? " WHERE {$fldCond}" : str_replace("WHERE", "WHERE ({$fldCond}) and ", strtoupper($this->where));
                    $tablename = (isset($this->updateOther)) ? $this->updateOther : $this->tablename;
                    $strDelete = "DELETE FROM {$this->backtick}{$tablename}{$this->backtick} {$delWhere}";
                    $this->SQL_query($strDelete);
                    $this->strDebug[] = "MULTI-DELETE: " . $strDelete;
                }
                else
                {
                    echo "<script>alert('" . $this->message['norecselect'] . "');</script>";
                };
                break;
            case 13:
                $toNestValue = $this->REQUEST("DG_nestedValue");
                $strSQL      = sprintf(str_replace("select_nested:", "", $this->fieldsArray[$this->dgrtd]['mask']), magic_quote($toNestValue));
                $arrNestData = $this->SQL_query($strSQL);
                echo "&nbsp;<script>";
                echo "DG_goo('dgFld{$this->dgrtd}').disabled = false;";
                echo "DG_goo('dgFld{$this->dgrtd}').length = 0;";
                $cList = 0;
                foreach ($arrNestData as $row)
                {
                    echo "list=DG_goo('dgFld{$this->dgrtd}'); list[{$cList}] = new Option('" . utf8_encode($row[1]) . "', " . $row[0] . "); ";
                    $cList++;
                };
                echo "</script>";
                die();
                break;
            case 14:
                if (isset($_FILES['dgFld_file']))
                {
                    $fileName  = strtolower($_FILES['dgFld_file']['name']);
                    $arrParts  = explode(".", $fileName);
                    $extension = $arrParts[count($arrParts) - 1];
                    $fieldName = $_POST['dgFldtoUpload' . $this->dgGridID];
                    if (in_array(strtolower($extension), $this->validImgExtensions) or empty($this->validImgExtensions))
                    {
                        if ($this->renameUploads)
                            $newfileName = $fieldName . "_-_" . rand(10000, 99999) . "_-_" . $fileName;
                        else
                            $newfileName = $fileName;
                        if (is_uploaded_file($_FILES['dgFld_file']['tmp_name']))
                        {
                            $path = $this->uploadDirectory . $newfileName;
                            if (move_uploaded_file($_FILES['dgFld_file']['tmp_name'], $path))
                            {
                                $tmpWidth  = (isset($this->fieldsArray["$fieldName"]["imageWidth"])) ? "width:" . $this->fieldsArray["$fieldName"]["imageWidth"] . "px;" : "";
                                $tmpHeight = (isset($this->fieldsArray["$fieldName"]["imageHeight"])) ? "height:" . $this->fieldsArray["$fieldName"]["imageHeight"] . "px;" : "";
                                if (!in_array($extension, array(
                                    "png",
                                    "gif",
                                    "jpg",
                                    "jpeg"
                                )))
                                {
                                    $path      = $this->imgpath . $this->images['icn_' . $extension];
                                    $tmpWidth  = "50px";
                                    $tmpHeight = "50px";
                                }
                                echo "<script>parent.document.getElementById('dgFld{$fieldName}').value='{$newfileName}';  parent.document.getElementById('uplimg_{$fieldName}{$this->dgGridID}').style.display='none';  parent.document.getElementById('uplstat_{$fieldName}{$this->dgGridID}').style.display='inline';  parent.document.getElementById('uplstat_{$fieldName}{$this->dgGridID}').innerHTML='<img src=\"{$path}\" style=\"{$tmpWidth}{$tmpHeight};\" {$this->sl}></span>';  </script>";
                            }
                        }
                    }
                    else
                    {
                        echo "<script>alert('" . $this->message['invalidfile'] . "');  parent.document.getElementById('uplimg_{$fieldName}{$this->dgGridID}').style.display='none';  parent.document.getElementById('uplstat_{$fieldName}{$this->dgGridID}').style.display='none';  </script>";
                    }
                };
                die();
                break;
            case 15:
                $this->methodForm = 'get';
                $field            = $this->REQUEST("DG_field_img_dsply");
                $id_img           = $this->REQUEST("DG_id_img_dsply");
                $vcode            = md5($this->salt . $field . $id_img);
                if ($vcode != $this->REQUEST("DG_code_img_dsply"))
                    die("<span class='dgError'>" . $this->message["errcode"] . "</span>");
                $strSQL  = "select {$this->backtick}" . $field . "{$this->backtick} FROM {$this->backtick}{$this->tablename}{$this->backtick} where {$this->backtick}{$this->keyfield}{$this->backtick} = " . magic_quote($id_img);
                $arrData = $this->SQL_query($strSQL);
                if (isset($arrData[0]))
                    echo $arrData[0][$field];
                die();
                break;
        }
    }
    function getFooter()
    {
        $strReturn = "";
        if (!empty($this->footer))
            $strReturn .= "<div class='dgFooter' style='width:{$this->tWidth}'>{$this->footer}</div>{$this->br}";
        if ($this->poweredby)
            $strReturn .= "<div align='center' class='dgFooter' style='width:{$this->tWidth}'><br{$this->sl}>  <a href='http://www.gurusistemas.com' target='_blank'>  <img border='0' src='{$this->imgpath}poweredby.gif' alt='Powered by phpMyDataGrid {$this->dgVersion}' title='Powered by phpMyDataGrid {$this->dgVersion}' border='0'{$this->sl}></a><br{$this->sl}>  </div>{$this->br}";
        return $strReturn;
    }
    function getTitle()
    {
        return (!empty($this->titulo)) ? "<span class='dgHeader'>{$this->titulo}</span>{$this->br}" : "";
    }
    function addCheckBox($module, $keyValue = "", $AditionalClasses = "")
    {
        global $clAlt;
        if ($this->checkable)
        {
            switch ($module)
            {
                case "cols":
                    $this->tableWidth += 22;
                    $this->columns++;
                    return $this->tab() . "<col style='max-width:22px' align='center'{$this->sl}>{$this->br}";
                    break;
                case "head":
                    return $this->tab() . "<td class='dgTitles' align='center'>{$this->br}<input type='checkbox' onclick='DG_setCheckboxes(this.checked)'{$this->sl}>{$this->br}</td>{$this->br}";
                    break;
                case "row":
                    $strReturn = $this->tab() . "<td class='dgRow{$clAlt}' align='center'>{$this->br}";
                    $strReturn .= $this->tab(2) . "<input type='checkbox' name='chk{$this->dgGridID}sel[]' value='{$keyValue}' onclick='DG_select(this)'{$this->sl}>{$this->br}";
                    return $strReturn;
                    break;
                case "total":
                    return $this->tab() . "<td class='dgRowsTot'>&nbsp;</td>";
                    break;
            }
        }
    }
    function addNode($module, $keyValue = "", $rowRes = array(), $fieldName = "")
    {
        global $clAlt;
        if (!isset($this->relField))
            $this->relField = "";
        if (!empty($rowRes) and !empty($this->relField) and !isset($rowRes[$this->relField]))
            die("InvalidFieldDefinition");
        $keyValueNode = (isset($rowRes[$this->relField])) ? $rowRes[$this->relField] : "";
        if (isset($this->subGrid))
        {
            switch ($module)
            {
                case "cols":
                    $this->tableWidth += 22;
                    $this->columns++;
                    return $this->tab() . "<col style='max-width:22px' align='center'{$this->sl}>{$this->br}";
                    break;
                case "head":
                    return $this->tab() . "<td class='dgTitles' align='center'>{$this->br}&nbsp;</td>{$this->br}";
                    break;
                case "row":
                    $strReturn = $this->tab() . "<td class='dgRow{$clAlt}' align='center'>{$this->br}";
                    $strReturn .= $this->tab(2) . "<img id='openNode_{$this->dgGridID}{$keyValue}' border='0' src='" . (isset($this->img_path['node']) ? $this->img_path['node'] : $this->imgpath) . "{$this->images['node']}' alt='{$this->message['node']}' title='{$this->message['node']}' class='dgImgLink'  onclick='" . str_replace("\,", "\", ", sprintf($this->detonClic, $keyValueNode, $this->dgGridID . $keyValue)) . "' {$this->sl}>" . "<img id='closeNode_{$this->dgGridID}{$keyValue}' border='0' src='" . (isset($this->img_path['closenode']) ? $this->img_path['closenode'] : $this->imgpath) . "{$this->images['closenode']}' alt='{$this->message['closenode']}' title='{$this->message['closenode']}' class='dgImgLink'  onclick='DG_sii(\"DG_det_{$this->dgGridID}{$keyValue}\",\"\"); DG_nodeShow(\"{$this->dgGridID}{$keyValue}\")' style='display:none' {$this->sl}>";
                    return $strReturn;
                    break;
                case "total":
                    return $this->tab() . "<td class='dgRowsTot'>&nbsp;</td>";
                    break;
            }
        }
    }
    function PhpArrayToJsObject_Recurse($array)
    {
        // var_dump($array); die();
        // return json_encode($array);

        if (!is_array($array))
        {
            if ($array === null)
                return null;
            return "\"" . addslashes($array) . "\"";
        }
        $retVal = "{";
        $comma  = "";
        foreach ($array as $key => $value)
        {
            # overcome security issue
            $enable_security = true;
            if ($enable_security)
            {
                if ($key == "mask")
                {
                    $found = stripos($value, "SELECT");
                    if ($found === false) $found = stripos($value, "RELATE");
                    if ($found !== false and $found == 0) $value = "";
                    // dump($value);
                }
            }

            if (is_string($key))
                $key = "\"" . addslashes($key) . "\"";
            $retVal .= $comma . $key . " : " . $this->PhpArrayToJsObject_Recurse($value);
            $comma = ",";
        }
        return $retVal . "}";
    }
    function PhpArrayToJsObject_Recurse_bad($array)
    {
        if(!is_array($array) ){
            if ($array === null) return null;
            return "\"{$array}\"";
        };
        $retVal = "{"; $comma="";
/*        if (!isset($this->lastFieldName)) $this->lastFieldName = ""; */
        foreach($array as $key => $value){
/*            if ($key == 'strfieldName') $this->lastFieldName = $value; */
/*          if (!isset($this->fieldsArray[$this->lastFieldName]['inputtype']) or (!in_array($this->fieldsArray[$this->lastFieldName]['inputtype'],array(4,5,6,7)))){ */
            if (!isset($value['inputtype']) or (isset($value['inputtype']) and !in_array($value['inputtype'],array(4,5,6,7)))){
                if (is_string($key)) $key = "\"{$key}\"";
                $retVal .= $comma.$key." : ".$this->PhpArrayToJsObject_Recurse($value); $comma=",";
            };
        };
        return $retVal."}";
    }
    function getInputField($value, $keyValue, $rowRes, $default)
    {
        $selData   = $this->fieldsArray["$value"]['select'];
        $width     = $this->fieldsArray["$value"]["columnwidth"];
        $width     = (isset($this->fieldsArray["$value"]["inputwidth"])) ? intval($this->fieldsArray["$value"]["inputwidth"]) . "px" : $width;
        $rows      = $this->fieldsArray["$value"]["fieldWidth"];
        $fldname   = $this->fieldsArray["$value"]["strfieldName"];
        $fldLengt  = $this->fieldsArray["$value"]["maxlength"];
        $mask      = $this->fieldsArray["$value"]["mask"];
        $inputType = $this->fieldsArray["$value"]['inputtype'];

        $defaultchk = $default;
        if ($default != '')
            $default = $this->GetSQLValueString($default, $this->fieldsArray["$value"]["mask"]);

        $default = str_replace("'", "\'", stripslashes($default));
        $this->fieldsArray["$value"]["align"];
        $strInput = $default;
        $strDate  = "";

        $onBlur   = (isset($this->validations["$value"]["condition"])) ? "onblur=\"if (" . $this->validations["$value"]["condition"] . ") {" . "DG_hss('edtErr{$fldname}','none'); DG_hss('edtMsg{$fldname}','block')}" . "else " . "{DG_hss('edtErr{$fldname}','block'); DG_hss('edtMsg{$fldname}','none'); }\"" : "";
        $onChange = "";
        if (isset($this->nested["$value"]))
        {
            $nestAction = "DG_updateNested(this.value,'" . $this->nested["$value"]['target'] . "');";
            $onChange   = "onchange=\"{$nestAction}\"";
            if (empty($onBlur))
                $onBlur = "onblur=\"{$nestAction}\"";
            else
                $onBlur .= ";{$nestAction}\"";
        }
        switch ($this->fieldsArray["$value"]['datatype'])
        {
            case "image":
            case "imagelink":
                $strInput    = "<form method='post' enctype='multipart/form-data' target='DG_iframe{$this->dgGridID}' action='{$this->scriptName}' id='up{$fldname}{$this->dgGridID}'>";
                $styleInput  = "inline";
                $styleSpan   = "none";
                $spanContent = "";
                $pr          = $this->fieldsArray["$value"]['permissions'];
                if (!$this->isadding() and !empty($rowRes[$value]))
                {
                    $styleInput = "none";
                    $styleSpan  = "inline";
                    $tmpWidth   = (isset($this->fieldsArray["$value"]["imageWidth"])) ? "width:" . $this->fieldsArray["$value"]["imageWidth"] . "px;" : "";
                    $tmpHeight  = (isset($this->fieldsArray["$value"]["imageHeight"])) ? "height:" . $this->fieldsArray["$value"]["imageHeight"] . "px;" : "";
                    if (isset($this->fieldsArray["$value"]['isblob']))
                    {
                        $rowValue = "{$this->scriptName}?rand=" . rand(0, 9999) . $this->parameters . "&DG_field_img_dsply={$value}&DG_ajaxid{$this->dgGridID}=15&DG_id_img_dsply=" . $rowRes[$this->keyfield] . "&DG_code_img_dsply=" . md5($this->salt . $value . $rowRes[$this->keyfield]);
                    }
                    else
                    {
                        $rowValue = $this->uploadDirectory . $rowRes[$value];
                    }
                    $spanContent = "<img src=\"{$rowValue}\" style=\"{$tmpWidth}{$tmpHeight}\" {$this->sl}>";
                    if (!empty($pr) and $this->allowed("M", $pr))
                    {
                        $spanContent .= "<a href='javascript:void(0);' class='dgImgLink' onclick='DG_edit_uploadedImg(\"{$fldname}{$this->dgGridID}\")'><img border='0' src='" . (isset($this->img_path['edit']) ? $this->img_path['edit'] : $this->imgpath) . "{$this->images['edit']}' alt='{$this->message['edit']}' title='{$this->message['edit']}'{$this->sl}></a>{$this->br}";
                        $spanContent .= "<a href='javascript:void(0);' class='dgImgLink' onclick='DG_remove_uploadedImg(\"{$fldname}{$this->dgGridID}\",\"{$fldname}\")'><img border='0' src='" . (isset($this->img_path['erase']) ? $this->img_path['erase'] : $this->imgpath) . "{$this->images['erase']}' alt='{$this->message['delete']}' title='{$this->message['delete']}'{$this->sl}></a>{$this->br}";
                    }
                };
                if (empty($pr) or !$this->allowed("U", $pr))
                    $styleInput = "none";
                $strInput .= "<input type='file' id='dgFld_file' name='dgFld_file' class='dgInput' style='width:{$width}; display:{$styleInput}' onchange=\"DG_tmpUpload('dgFld{$fldname}',this.value, '{$fldname}{$this->dgGridID}')\"{$this->sl}>";
                $strInput .= "<input id='dgFldtoUpload{$this->dgGridID}' name='dgFldtoUpload{$this->dgGridID}' value='{$fldname}' type='hidden'{$this->sl}>";
                $strInput .= "<input id='dgFld{$fldname}' name='dgFld{$fldname}' type='hidden' value='current'{$this->sl}>";
                $strInput .= "<input id='DG_ajaxid{$this->dgGridID}' name='DG_ajaxid{$this->dgGridID}' type='hidden' value='14'{$this->sl}>";
                $arrParams = explode("&", str_replace("&amp;", "&", $this->parameters));
                foreach ($arrParams as $parameter)
                {
                    $arrSubParam = explode("=", $parameter);
                    if (!empty($arrSubParam[0]))
                        $strInput .= "<input type='hidden' name='" . $arrSubParam[0] . "' id='" . $arrSubParam[0] . "' value='" . $arrSubParam[1] . "'{$this->sl}>";
                };
                $strInput .= "<span id='uplimg_{$fldname}{$this->dgGridID}' style='display: none;'><img src='{$this->imgpath}{$this->images['ajax']}'></span>";
                $strInput .= "<span id='uplstat_{$fldname}{$this->dgGridID}' style='display:{$styleSpan};'>{$spanContent}</span>";
                $strInput .= '</form>';
                break;
            case "link":
            case "calc":
            case "chart":
                break;
            case "select":
                $arrMask = explode(":", $mask);
                if ($arrMask[0] == "select_nested")
                {
                    $toNestValue = "";
                    foreach ($this->nested as $source => $targetData)
                    {
                        if ($targetData['target'] == $value)
                        {
                            $toNestValue = $targetData['value'];
                            break;
                        }
                    }
                    $strSQL   = sprintf(str_replace("select_nested:", "", $mask), magic_quote($toNestValue));
                    $maskData = $this->SQL_query($strSQL);
                    $selData  = array();
                    foreach ($maskData as $arrResult)
                        $selData[$arrResult[0]] = htmlentities($arrResult[1]);
                };
                $strInput = "<select id='dgFld{$value}' class='dgSelectpage' style='width:{$width}' {$onBlur} {$onChange}>{$this->br}";
                foreach ($selData as $key => $value)
                {
                    $strInput .= "<option value='$key' " . (($key == $default) ? "selected='selected'" : "") . " >$value</option>{$this->br}";
                };
                $strInput .= "</select>";
                break;
            case "related":
            case "conditional":
                break;
            case "check":
                $strInput = "<input id='dgFld{$fldname}' type='checkbox' " . (($defaultchk == $this->fieldsArray[$value]["select"][1]) ? "checked" : "") . " class='dgCheck' {$this->sl}>{$this->br}";
                break;
            case "textarea_tinymce":
                if (!($inputType == 1 or $inputType == 2))
                    $this->elements_tinyMCE[] = "dgFld{$fldname}";
            case "textarea":
                $strInput = "<textarea id='dgFld{$fldname}' class='dgInput' rows='{$rows}' style='width:{$width}' onkeypress='return DG_imposeMaxLength(this, $fldLengt);' >$default</textarea>{$this->br}";
                break;
            case "password":
                $strInput = "<input id='dgFld{$fldname}' type='password' class='dgInput' value='$default' maxlength='$fldLengt' style='width:{$width}'{$this->sl}>";
                break;
            case 'date':
            case "datetime":
                $default = $defaultchk;
                if ($this->bolCalendar)
                {
                    $onblur  = "";
                    $strDate = "<input id='idgFld{$fldname}' type='hidden' value='{$defaultchk}' maxlength='{$fldLengt}'{$this->sl}>";
                    $strDate .= '<a href="javascript:void(0)" onClick="viewCalendar( \'dgFld' . $fldname . '\', \'' . $this->dateformat($mask) . '\', event );" ><img border="0" src="' . (isset($this->img_path['calendar']) ? $this->img_path['calendar'] : $this->imgpath) . $this->images["calendar"] . '" alt="' . $this->message['calendar'] . '" title="' . $this->message['calendar'] . '" border="0"' . $this->sl . '></a>';
                };
            default:
                $default  = str_replace('"', "&quot;", $default);
                $default  = stripslashes($default);
                $strInput = "<input id='dgFld{$fldname}' apa='{$this->fieldsArray["$value"]['datatype']}' type='text' class='dgInput' value=\"{$default}\" maxlength='{$fldLengt}' {$onBlur} style='width:{$width}'{$this->sl}>" . $strDate;
                break;
        }
        return $strInput;
    }
    function getFieldData($fieldName, $keyValue, $rowRes, $total = false)
    {
        global $clAlt;
        $this->currentFieldName = $fieldName;

        $dataType     = $this->fieldsArray["$fieldName"]['datatype'];
        $mask         = $this->fieldsArray["$fieldName"]['mask'];
        $selData      = $this->fieldsArray["$fieldName"]['select'];
        $cutChar      = $this->fieldsArray["$fieldName"]['cutChar'];
        $strHeader    = $this->fieldsArray["$fieldName"]["strHeader"];
        $isreadonly   =($this->fieldsArray["$fieldName"]['inputtype']==1)?true:false;
        $vrField      =(isset($rowRes["$fieldName"])?$rowRes["$fieldName"]:0);

        if (isset($this->nested["$fieldName"])) $this->nested["$fieldName"]['value'] = $vrField;

        if ($total)
        {
            $this->tmpCondition = array();
            $clAlt              = "sTot";
            $keyValue           = "Total";
            $isreadonly         = true;
            $vrField            = isset($this->totalColumn["$fieldName"]) ? $this->totalColumn["$fieldName"] : "";
        }
        $editCondition = (isset($this->edtResult)) ? $this->edtResult : true;
        $rowValue      = (isset($vrField) and $this->DG_ajaxid != 7) ? (($this->charset == 'ISO-8859-1') ? $this->putAcutes($vrField) : $vrField) : $vrField;
        $rowValue      = (($rowValue == "" or is_null($rowValue)) and $this->DG_ajaxid != 5) ? "&nbsp;" : $rowValue;
        $strReturn     = $this->tab(3) . "<td class='dgRow{$clAlt}";
        $claux         = $stlaux = "";
        if (isset($this->cellCondition["$fieldName"]) and !empty($rowRes))
        {
            foreach ($this->cellCondition["$fieldName"] as $key => $condition)
            {
                $this->tmpCondition["$key"] = (!empty($condition)) ? "if (" . strtr($condition, array(
                    "['" => "\$rowRes['"
                )) . ") { \$claux .= ' '. \"" . (strtr($this->cellStyle["$fieldName"]["$key"], array(
                    "['" => "\$rowRes['"
                ))) . "\"; }" : "";
                eval($this->tmpCondition["$key"]);
            }
        }
        $orderedClass = (isset($this->dgOrderData["myOe"][$fieldName])) ? "orderedData" . $clAlt : "";
        $strReturn .= $claux . " {$orderedClass}' {$stlaux}>{$this->br}";
        $strReturn .= $this->tab(4) . "<div id='{$fieldName}{$this->dgGridID}.-.{$keyValue}' align='" . $this->fieldsArray["$fieldName"]["align"] . "' ";
        if (in_array($this->fieldsArray["$fieldName"]['inputtype'], array(
            6,
            7
        )) or empty($this->ajaxEditable) or !$editCondition or $isreadonly or in_array($dataType, array(
            "link",
            "image",
            "imagelink",
            "calc",
            "chart"
        )))
        {
            if ($dataType == 'link')
                $strReturn .= "class='dgLinks'";
            if ($dataType == 'calc')
                $strReturn .= "class='dgCalc'";
        }
        else
        {
            $oc = ($this->typeOfClick == 1) ? "onclick" : "ondblclick";
            $strReturn .= "{$oc}='DG_D_edit(this,\"" . md5($this->salt . $fieldName . ":toEdit:" . $keyValue) . "\")' ";
        }
        $strReturn .= ">";
        $rowValue = stripslashes($rowValue);
        switch ($dataType)
        {
            case "select":
                $arrMask = explode(":", $mask);
                if ($arrMask[0] == "select_nested")
                {
                    $toNestValue = "";
                    foreach ($this->nested as $source => $targetData)
                    {
                        if ($targetData['target'] == $fieldName)
                        {
                            $toNestValue = $targetData['value'];
                            break;
                        }
                    }
                    $strSQL   = sprintf(str_replace("select_nested:", "", $mask), magic_quote($toNestValue));
                    $maskData = $this->SQL_query($strSQL);
                    $selData  = array();
                    foreach ($maskData as $arrResult)
                        $selData[$arrResult[0]] = htmlentities($arrResult[1]);
                };
                $strData = $this->maskdata($rowValue, $mask, $dataType, $selData, $rowRes);
                $strReturn .= $strData;
                $strValue = (isset($this->isviewing) and $this->isviewing or ($this->DG_ajaxid == 7 or $isreadonly)) ? $strData : $rowValue;
                break;
            case "date":
            case "datetime":
                $rowValue = $strValue = $this->maskdata($rowValue, $mask, $dataType, array(), $rowRes);
                break;
            case "link":
                list($type, $fieldNamelist) = explode(':', $mask);
                $strValue = "<a href=\"javascript:" . $this->extractLink($fieldNamelist, $rowRes) . "\">{$rowValue}</a>{$this->br}";
                break;
            case 'calc':
                $cRowRes = $rowRes;
                $cMask   = $mask;
                if (strpos($cMask, '//') === false)
                {
                    $cMask1 = $cMask;
                    $cMask2 = '2';
                }
                else
                {
                    list($cMask1, $cMask2) = explode('//', $cMask);
                    if (empty($cMask2))
                        $cMask2 = '2';
                };
                list($cType, $eCalc) = explode(':', $cMask1);
                $eTC          = $eCalc;
                $eCalc        = strtr($eCalc, array(
                    "+" => " ",
                    "*" => " ",
                    "-" => " ",
                    "/" => " ",
                    "(" => " ",
                    ")" => " "
                ));
                $varExpresion = explode(' ', trim($eCalc));
                $eTC          = strtr($eTC, array(
                    "+" => " + ",
                    "-" => " - ",
                    "/" => " / ",
                    "*" => " * ",
                    "(" => " ( ",
                    ")" => " ) "
                ));
                foreach ($varExpresion as $cField)
                {
                    $vrFieldC = (empty($cRowRes["$cField"])) ? 0 : $cRowRes["$cField"];
                    if (isset($this->fieldsArray["$cField"]["strfieldName"]))
                        $eTC = str_replace(" " . $cField . " ", $vrFieldC, $eTC);
                };
                eval("\$calcRst = " . $eTC . ";");
                if ($total)
                {
                    $rowValue = 0;
                    $calcRst  = $vrField;
                }
                else
                {
                    $rowValue = $calcRst;
                };
                $strValue = $this->maskdata($calcRst, $cMask2, "number", array(), $cRowRes);
                $rowValue = round($rowValue, $this->decimalDigits);
                break;
            case "password":
                $strValue = $rowValue = ($rowValue == "&nbsp;") ? "" : $rowValue;
                break;
            case "related":
                $sqlQuery = str_replace("related:", "", $mask);
                $sqlQuery = sprintf($sqlQuery, magic_quote($rowValue));
                $arrData  = $this->SQL_query($sqlQuery);
                $strValue = $rowValue = (isset($arrData[0][0])) ? $arrData[0][0] : "";
                break;
            case "conditional":
                $condicion    = "";
                $endCondition = false;
                if (empty($rowRes))
                {
                    $ans = "";
                }
                else
                {
                    foreach ($this->fieldsArray["$fieldName"]["select"] as $condition => $response)
                    {
                        foreach ($rowRes as $k => $v)
                            $response = str_replace("['{$k}']", $v, $response);
                        $condicion = (!empty($condition)) ? "if (" . strtr($condition, array(
                            "['" => "\$rowRes['"
                        )) . ") { \$ans = \$response; \$endCondition=true; }else{ \$ans = ''; }" : "";
                        eval($condicion);
                        if ($endCondition)
                            break;
                    }
                };
                $strValue = $rowValue = $ans;
                break;
            case 'image':
            case 'imagelink':
                $strValue = '';
                if (!$total)
                {
                    if ($dataType == "imagelink")
                        list($type, $imagedata, $fieldNamelist) = explode(':', $mask);
                    else
                        list($type, $imagedata) = explode(':', $mask);
                    if (!empty($imagedata))
                        $rowValue = str_replace("%s", str_replace("&nbsp;", "", $rowValue), $imagedata);
                    $pr = "";
                    if (isset($this->fieldsArray[$fieldName]['permissions']))
                        $pr = $this->fieldsArray["$fieldName"]['permissions'];
                    $IHaveButton = false;
                    $w           = (isset($this->fieldsArray["$fieldName"]["imageWidth"])) ? " width='" . $this->fieldsArray["$fieldName"]["imageWidth"] . "'" : "";
                    $h           = (isset($this->fieldsArray["$fieldName"]["imageHeight"])) ? " height='" . $this->fieldsArray["$fieldName"]["imageHeight"] . "'" : "";
                    if ((!isset($this->fieldsArray["$fieldName"]['isblob']) and ((substr($rowValue, strlen($rowValue) - 1, 1) == "/") or !file_exists($rowValue))) or (isset($this->fieldsArray["$fieldName"]['isblob']) and empty($rowRes[$fieldName])))
                    {
                        if ($this->show404image)
                            $strReturn .= "<img id='icn_{$this->dgGridID}{$fieldName}.-.{$keyValue}' alt='{$this->message['noimage']}' title='{$this->message['noimage']}' src='" . (isset($this->img_path['noimage']) ? $this->img_path['noimage'] : $this->imgpath) . "{$this->images['noimage']}'{$this->sl}>{$this->br}";
                        else
                        {
                            $strReturn .= "<img id='icn_{$this->dgGridID}{$fieldName}.-.{$keyValue}' alt='' title='' src='" . (isset($this->img_path['blank']) ? $this->img_path['blank'] : $this->imgpath) . "{$this->images['blank']}'{$this->sl}>{$this->br}";
                        }
                        if (!empty($pr) and $this->allowed("IU", $pr))
                        {
                            $strReturn .= "<div id='d_icn_{$this->dgGridID}{$fieldName}.-.{$keyValue}'>{$this->br}<input class='dgUploadInput' type='button' value='{$this->message['upload']}' onclick='DG_ui(\"{$fieldName}\",\"{$keyValue}\",\"{$imagedata}\")'{$this->sl}>{$this->br}</div>{$this->br}";
                            $IHaveButton = true;
                        }
                    }
                    else
                    {
                        $strHeader = $this->DGXtract($strHeader, "<em>", "</em>");
                        if (isset($this->tooltip[$fieldName]))
                        {
                            if (!is_array($this->tooltip[$fieldName]))
                            {
                                $strHeader = addslashes($this->tooltip[$fieldName]);
                            }
                            else
                            {
                                $strHeader = addslashes($this->tooltip[$fieldName]["$vrField"]);
                            }
                        }
                        $arrParts  = explode(".", $rowValue);
                        $extension = $arrParts[count($arrParts) - 1];
                        if (in_array($extension, array(
                            "png",
                            "gif",
                            "jpg",
                            "jpeg"
                        )))
                        {
                            if (isset($this->fieldsArray["$fieldName"]['isblob']))
                            {
                                $rowValue = "{$this->scriptName}?rand=" . rand(0, 9999) . $this->parameters . "&DG_field_img_dsply={$fieldName}&DG_ajaxid{$this->dgGridID}=15&DG_id_img_dsply=" . $rowRes[$this->keyfield] . "&DG_code_img_dsply=" . md5($this->salt . $fieldName . $rowRes[$this->keyfield]);
                            }
                            $strReturn .= "<img id='icn_{$this->dgGridID}{$fieldName}.-.{$keyValue}' alt='{$strHeader}' title='{$strHeader}' {$w} {$h} src='{$rowValue}' ";
                            if ($dataType == 'imagelink')
                                $strReturn .= 'class="dgImgLink" onclick="' . $this->extractLink($fieldNamelist, $rowRes) . '"';
                            $strReturn .= "{$this->sl}>{$this->br}";
                        }
                        else
                        {
                            $strReturn .= $rowValue;
                        }
                    }
                    if (!empty($pr) and $this->allowed("IM", $pr) and !$IHaveButton)
                    {
                        $strReturn .= "<div id='d_icn_{$this->dgGridID}{$fieldName}.-.{$keyValue}'>{$this->br}<input class='dgUploadInput' type='button' value='{$this->message['upload']}' onclick='DG_ui(\"{$fieldName}\",\"{$keyValue}\",\"{$imagedata}\")'{$this->sl}>{$this->br}</div>{$this->br}";
                    }
                };
                break;
            case "bool":
            case "check":
                if ($total)
                {
                    $mask = $rowValue = "";
                }
            default:
                if (!isset($this->isInternalEditing))
                {
                    if ($cutChar > 0 and strlen($rowValue) > $cutChar)
                        $rowValue = substr($rowValue, 0, $cutChar) . "...";
                };
                unset($this->isInternalEditing);
                $strValue = $this->maskdata($rowValue, $mask, $dataType, $selData, $rowRes);
                break;
        }
        if (!isset($this->calcID["$keyValue"]) and $keyValue != 'Total')
            $this->calcID["$keyValue"] = $keyValue;
        if (isset($this->isexporting) and $strValue == '&nbsp;')
            $strValue = '';
        $this->fldValue = $strValue;
        if ($dataType == "select")
            $strValue = "";
        if ($dataType == "password")
            $strValue = str_repeat("*", strlen($rowValue));
        $strReturn .= str_replace("&amp;nbsp;", "&nbsp;", $strValue) . "</div>{$this->br}";
        if (!(empty($this->ajaxEditable) or in_array($dataType, array(
            "link",
            "image",
            "imagelink",
            "chart"
        ))))
        {
            $rowInput = ($rowValue == "&nbsp;") ? "" : $rowValue;
            $strReturn .= $this->tab(4) . "<input type='hidden' id='i{$this->dgGridID}{$fieldName}.-.{$keyValue}' value=\"" . str_replace('"', '&quot;', $rowInput) . "\"{$this->sl}>{$this->br}";
        }
        $strReturn .= $this->tab(3) . "</td>{$this->br}";
        if (in_array($fieldName, $this->totalize))
            $this->totalColumn["$fieldName"] = (isset($this->totalColumn["$fieldName"])) ? ($this->totalColumn["$fieldName"] + $rowValue) : $rowValue;
        return $strReturn;
    }
    Function tooltip($fieldName, $text)
    {
        $this->tooltip[$fieldName] = $text;
    }
    function setTitle($fldName)
    {
        $drawArrows = true;
        if ($this->orderArrows and !in_array($this->fieldsArray["$fldName"]['inputtype'], array(
            2,
            3,
            4
        )))
        {
            if (isset($this->fieldsArray["$fldName"]["permissions"]))
            {
                if ($this->allowed("R", $this->fieldsArray["$fldName"]["permissions"]))
                {
                    $drawArrows = false;
                }
                if ($this->allowed("R-", $this->fieldsArray["$fldName"]["permissions"]))
                {
                    $drawArrows = true;
                }
            }
        }
        else
        {
            $drawArrows = false;
            if (isset($this->fieldsArray["$fldName"]["permissions"]) and $this->allowed("R-", $this->fieldsArray["$fldName"]["permissions"]))
            {
                $drawArrows = true;
            }
        }
        $this->fieldsArray["$fldName"]["ordering"] = $drawArrows;
        $mup                                       = $mdn = $rac = $o = $arrowName = "";
        $wo                                        = $this->fieldsArray["$fldName"]["columnwidth"];
        $w                                         = intval($wo) - 27;
        if ($drawArrows)
        {
            $mup = "<img border='0' src='" . (isset($this->img_path['miniup']) ? $this->img_path['miniup'] : $this->imgpath) . "{$this->images['miniup']}' class='pointer up' alt='{$this->message['sa']}' title='{$this->message['sa']}' onClick=\"DG_orderby('{$this->fieldsArray[$fldName]['strfieldName']}','ASC',event)\"{$this->sl}>";
            $mdn = "<img border='0' src='" . (isset($this->img_path['minidown']) ? $this->img_path['minidown'] : $this->imgpath) . "{$this->images['minidown']}' class='pointer dn' alt='{$this->message['sd']}' title='{$this->message['sd']}' onClick=\"DG_orderby('{$this->fieldsArray[$fldName]['strfieldName']}','DESC',event)\"{$this->sl}>";
        }
        if (in_array($fldName, $this->dgOrderData["myOrder"]) and !empty($this->dgOrderData["myOe"]["$fldName"]))
        {
            $arrowName = (isset($this->img_path[$this->dgOrderData["myOe"]["$fldName"]]) ? $this->img_path[$this->dgOrderData["myOe"]["$fldName"]] : $this->imgpath) . $this->images[$this->dgOrderData["myOe"]["$fldName"]];
            $o         = " ordered";
            $rac       = "<img border='0' src='$arrowName' alt='" . $this->dgOrderData["myOe"]["$fldName"] . "' title='" . $this->dgOrderData["myOe"]["$fldName"] . "' width='9' height='8'{$this->sl}>{$this->br}";
        }
        $mOver   = (isset($this->hideFields) and $this->inColumns and !empty($this->hideFields)) ? "onclick='this.rm=0' onmouseover='if(DG_gvv(\"DG_uac{$this->dgGridID}\")!=\"s{$this->dgGridID}Div{$fldName}\"){cancelColData(false);closeColumnMenu()};this.rm=1; DG_hss(\"s{$this->dgGridID}Div{$fldName}\",\"block\");DG_hss(\"as{$this->dgGridID}Div{$fldName}\",\"none\")' onmouseout='if (this.rm==1){ DG_hss(\"s{$this->dgGridID}Div{$fldName}\",\"none\");DG_hss(\"as{$this->dgGridID}Div{$fldName}\",\"block\")}'" : "";
        $dnArrow = (isset($this->hideFields) and $this->inColumns and !empty($this->hideFields)) ? "<div id='s{$this->dgGridID}Div{$fldName}' style='display:none;float:left' onclick='viewColumnOptions(\"s{$this->dgGridID}Div{$fldName}\", event, \"" . $this->message['columns'] . "\", 0 ); DG_svv(\"DG_uac{$this->dgGridID}\",this.id);' ><img border='0' src='" . (isset($this->img_path['dnarrow']) ? $this->img_path['dnarrow'] : $this->imgpath) . "{$this->images['dnarrow']}' class='pointer' alt='' title=''{$this->sl}></div>" : "";
        $header  = $this->fieldsArray["$fldName"]['strHeader'];
        if (substr($header, strlen($header) - 1, 1) == ":")
            $header = substr($header, 0, strlen($header) - 1);
        $bck       = " background:url($arrowName) no-repeat right 3px;";
        $strReturn = $this->tab(4) . "<table border='0' cellpadding='0' cellspacing='0' class='titleContainer{$o}' {$mOver}>{$this->br}";
        $strReturn .= $this->tab(5) . "<tr>{$this->br}";
        if (!isset($this->orderByTitleClick))
            $strReturn .= $this->tab(6) . "<td class='dgArrows up'>{$mup}</td>{$this->br}";
        $strReturn .= $this->tab(6) . "<td rowspan='2' style='vertical-align:middle; padding-left:5px; text-align:center;{$bck}'>{$this->br}";
        if (!isset($this->orderByTitleClick))
        {
            $strReturn .= $this->tab(7) . $this->columnTitle = $this->putAcutes($header);
        }
        else
        {
            $this->columnTitle = $this->putAcutes($header);
            if ($drawArrows)
            {
                $onclick = "onClick=\"DG_orderby('{$this->fieldsArray[$fldName]['strfieldName']}','" . ((!isset($this->dgOrderData["myOe"]["$fldName"]) or $this->dgOrderData["myOe"]["$fldName"] == "DESC") ? "ASC" : "DESC") . "',event); return false;\"";
                $strReturn .= $this->tab(7) . "<a href='javascript:void(0)' {$onclick}>" . $this->putAcutes($header) . "</a>";
            }
            else
            {
                $strReturn .= $this->tab(7) . $this->putAcutes($header);
            }
        }
        $strReturn .= $this->tab(6) . "</td>{$this->br}";
        $strReturn .= $this->tab(5) . "</tr>{$this->br}";
        $strReturn .= $this->tab(5) . "<tr>{$this->br}";
        if (!isset($this->orderByTitleClick))
            $strReturn .= $this->tab(6) . "<td class='dgArrows dn'>{$mdn}</td>{$this->br}";
        $strReturn .= $this->tab(5) . "</tr>{$this->br}";
        $strReturn .= $this->tab(4) . "</table>{$this->br}";
        return $strReturn;
    }
    function buildOrderBy()
    {
        $order          = "";
        $this->reqOrder = $this->orderColName;
        $this->reqOe    = $this->orderExpr;
        $myOrder        = $myOe = array();
        if ($this->issetREQUEST("dg_order{$this->dgGridID}"))
        {
            $this->reqOrder = $this->REQUEST("dg_order{$this->dgGridID}");
            $this->reqOe    = $this->REQUEST("dg_oe{$this->dgGridID}");
        }
        $this->reqOrder = trim($this->reqOrder);
        $myOrder        = explode(",", $this->reqOrder);
        $of             = 0;
        $myOe           = explode(",", $this->reqOe);
        $comma          = $myCampos = "";
        if (!empty($this->reqOrder))
        {
            foreach ($myOrder as $orderField)
            {
                $orderField = trim($orderField);
                if (!empty($orderField) and ($this->validField($orderField) or $this->setOrderby == $orderField))
                {
                    if (strpos($myCampos, "[" . $orderField . "]") === false)
                    {
                        $order .= $comma . $this->backtick . $orderField . $this->backtick;
                        $comma = ",";
                        $myCampos .= "[$orderField]";
                        $orderExpr = (isset($myOe[$of])) ? strtoupper($myOe[$of]) : "ASC";
                        if ($orderExpr != "ASC" and $orderExpr != "DESC")
                            $orderExpr = "ASC";
                        $order .= " $orderExpr";
                        $myOe["$orderField"] = $orderExpr;
                    }
                }
                $of++;
            }
        }
        $this->orderby     = (!empty($order)) ? " ORDER BY " . $order : "";
        $this->dgOrderData = array(
            'myOe' => $myOe,
            'myOrder' => $myOrder
        );
        $this->strDebug[]  = "Order By: " . $this->orderby;
    }
    function buildWhere()
    {
        if (isset($this->detailsWhere))
        {
            $connector   = (!empty($this->where) ? " and " : "");
            $this->where = $this->where . $connector . $this->detailsWhere;
        }
        if (!empty($this->where))
        {
            if (strpos(strtolower($this->where), "where") === false)
            {
                $this->where = " WHERE {$this->where}";
            }
        }
        if (in_array($this->ss, explode(",", str_replace(":select", "", $this->search))) and !empty($this->schrstr))
        {
            $this->schrstr = $this->GetSQLValueString($this->schrstr, $this->fieldsArray[$this->ss]["mask"]);
            $this->prefix  = (isset($this->searchby_table_prefix)) ? $this->backtick . $this->searchby_table_prefix . $this->backtick . "." : "";
            if (!isset($this->noSearchPercent))
                $this->schrstr = str_replace("%", '\%', $this->schrstr);
            if (!isset($this->searchMethods[$this->ss]))
            {
                $busqueda = "LIKE '%" . my_real_escape_string($this->schrstr) . "%'";
            }
            else
            {
                switch ($this->searchMethods[$this->ss])
                {
                    case "left":
                        $busqueda = "LIKE '%" . my_real_escape_string($this->schrstr) . "'";
                        break;
                    case "in":
                        $busqueda = "LIKE '%" . my_real_escape_string($this->schrstr) . "%'";
                        break;
                    case "any":
                        $busqueda = "LIKE '%" . implode("%", explode(" ", my_real_escape_string($this->schrstr))) . "%'";
                        break;
                    case "right":
                        $busqueda = "LIKE '" . my_real_escape_string($this->schrstr) . "%'";
                        break;
                    case "equal":
                        $busqueda = "='" . my_real_escape_string($this->schrstr) . "'";
                        break;
                    default:
                        $busqueda = "LIKE '%" . my_real_escape_string($this->schrstr) . "%'";
                        break;
                }
            }
            $this->where = ((empty($this->where)) ? " WHERE (" : $this->where . " AND (") . " {$this->prefix}{$this->backtick}{$this->ss}{$this->backtick} {$busqueda} )";
        }
        if ($this->DG_ajaxid == 7 and $this->dgrtd == "S")
        {
            $arrSelection = explode(",", $this->chksel);
            foreach ($arrSelection as $key => $value)
                $arrSelection[$key] = magic_quote(trim($value));
            $chksel      = implode(",", $arrSelection);
            $this->where = ((empty($this->where)) ? " WHERE (" : $this->where . " AND (") . " {$this->backtick}{$this->keyfield}{$this->backtick} IN ($chksel))";
        }
        $this->strDebug[] = "Where: " . $this->where;
    }
    function buildGroupBy()
    {
        if (!empty($this->groupby))
            $this->groupby = " GROUP BY {$this->groupby}";
        $this->groupbysimple = $this->groupby;
        if (!empty($this->having))
            $this->groupby .= " HAVING {$this->having}";
        $this->strDebug[] = "Group by: " . $this->groupby;
    }
    function getButtonsHTML($curCol, $toReturn = "", $width = 0, $align = 'center')
    {
        global $keyValue, $rowRes, $clAlt, $claux;
        $this->hasBtn = $hasBtn = ($this->addBtn or $this->updBtn or $this->delBtn or $this->chkBtn or $this->allExp or (!empty($this->search)));
        $wTop         = $wBody = 0;
        if ($this->addBtn and (!isset($this->toolbar) or !$this->toolbar))
            $wTop = $this->ButtonWidth;
        if ($this->allExp and (!isset($this->toolbar) or !$this->toolbar))
            $wTop += $this->ButtonWidth;
        if (!empty($this->search) and (!isset($this->toolbar) or !$this->toolbar))
            $wTop += $this->ButtonWidth;
        if ($this->updBtn)
            $wBody = $this->ButtonWidth;
        if ($this->delBtn)
            $wBody += $this->ButtonWidth;
        if ($this->chkBtn)
            $wBody += $this->ButtonWidth;
        if (!empty($this->setOrderby))
            $wBody += ($this->ButtonWidth * 2);
        $this->BtnsColWidth = ($wTop > $wBody) ? $wTop : $wBody;
        $strAdd             = $strExp = $strSch = "";
        $cols               = $this->tab() . "<col id='dgcolumn%s' style='width:%s' align='%s'{$this->sl}>{$this->br}";
        if ($hasBtn and $this->btnColumn == $curCol)
        {
            switch ($toReturn)
            {
                case "colhead":
                    $this->tableWidth += intval($this->BtnsColWidth);
                    $this->columns++;
                    return sprintf($cols, $this->columns, ($this->BtnsColWidth . "px"), $align);
                    break;
                case "header":
                case "total":
                case "pagination":
                    $class = ($toReturn == "header") ? "Titles" : (($toReturn == "total") ? "RowsTot" : "PagRow");
                    if ($this->addBtn)
                    {
                        $strAdd = "<img border='0' src='" . (isset($this->img_path['add']) ? $this->img_path['add'] : $this->imgpath) . "{$this->images['add']}' alt='{$this->message['add']}' title='{$this->message['add']}' class='dgImgLink' ";
                        if (!$this->toolbar)
                            $strAdd .= "onclick='{$this->addonClic}'";
                        $strAdd .= "onmouseover='DG_set_working_grid(\"{$this->dgGridID}\")' {$this->sl}>";
                        if ($this->getMyOwnButtons)
                        {
                            $this->strAddBtn = $strAdd;
                            $strAdd          = "&nbsp;";
                        }
                    };
                    if ($this->allExp)
                    {
                        $strExp = "<img border='0' src='" . (isset($this->img_path['export']) ? $this->img_path['export'] : $this->imgpath) . "{$this->images['export']}' alt='{$this->message['export']}' title='{$this->message['export']}' class='dgImgLink' ";
                        if (!$this->toolbar)
                            $strExp .= "onclick='{$this->exportTo}'";
                        $strExp .= "onmouseover='DG_set_working_grid(\"{$this->dgGridID}\")' {$this->sl}>";
                        if ($this->getMyOwnButtons)
                        {
                            $this->strExportBtn = $strExp;
                            $strExp             = "&nbsp;";
                        }
                    };
                    if (!empty($this->search))
                    {
                        $strSch = "<img border='0' src='" . (isset($this->img_path['search']) ? $this->img_path['search'] : $this->imgpath) . "{$this->images['search']}' alt='{$this->message['search']}' title='{$this->message['search']}' class='dgImgLink' ";
                        if (!$this->toolbar)
                            $strSch .= "onclick='{$this->srconClic}'";
                        $strSch .= "onmouseover='DG_set_working_grid(\"{$this->dgGridID}\")' {$this->sl}>";
                        if ($this->getMyOwnButtons)
                        {
                            $this->strSearchBtn = $strSch;
                            $strSch             = "&nbsp;";
                        }
                    };
                    if ($toReturn == 'pagination')
                        return $this->tab(3) . "<span style='float:right'>" . ((!empty($this->btnColName)) ? "{$this->btnColName}<br{$this->sl}>" : "") . "{$strAdd}{$strExp}{$strSch}</span>{$this->br}";
                    else
                        return $this->tab(3) . "<td class='dg{$class}' align='left'>" . ((!empty($this->btnColName)) ? "{$this->btnColName}<br{$this->sl}>" : "") . "{$strAdd}{$strExp}{$strSch}</td>{$this->br}";
                    break;
                case "body":
                    $btnChk=$btnUpd=$btnDel=$btnUp=$btnDn="";
                    if ($this->updBtn and $this->edtResult) $btnUpd = $this->tab(4) . sprintf ("<a href='javascript:void(0);' class='dgImgLink' onclick='{$this->edtonClic}'><img border='0' src='" . (isset($this->img_path['edit'])?$this->img_path['edit']:$this->imgpath) . "{$this->images['edit']}' alt='{$this->message['edit']}' title='{$this->message['edit']}'{$this->sl}></a>{$this->br}",$keyValue,md5($this->salt.$keyValue));
                    if ($this->delBtn and $this->delResult) $btnDel = $this->tab(4) . sprintf ("<a href='javascript:void(0);' class='dgImgLink' onclick='{$this->delonClic}'><img border='0' src='" . (isset($this->img_path['erase'])?$this->img_path['erase']:$this->imgpath) . "{$this->images['erase']}' alt='{$this->message['delete']}' title='{$this->message['delete']}'{$this->sl}></a>{$this->br}",$keyValue,md5($this->salt.$keyValue));
                    if ($this->chkBtn) $btnChk = $this->tab(4) . sprintf ("<a href='javascript:void(0);' class='dgImgLink' onclick='{$this->vieonClic}'><img border='0' src='" . (isset($this->img_path['view'])?$this->img_path['view']:$this->imgpath) . "{$this->images['view']}' alt='{$this->message['view']}' title='{$this->message['view']}' {$this->sl}></a>{$this->br}",$keyValue,md5($this->salt.$keyValue));
                    if (!empty($this->setOrderby)) $btnUp = $this->tab(4) . sprintf("<a href='javascript:void(0);' class='dgImgLink' onclick='{$this->uponClic}'><img border='0' src='" . (isset($this->img_path['arrup'])?$this->img_path['arrup']:$this->imgpath) . "{$this->images['arrup']}' alt='{$this->message['arrup']}' title='{$this->message['arrup']}'{$this->sl}></a>{$this->br}",$rowRes[$this->setOrderby]);
                    if (!empty($this->setOrderby)) $btnDn = $this->tab(4) . sprintf("<a href='javascript:void(0);' class='dgImgLink' onclick='{$this->dnonClic}'><img border='0' src='" . (isset($this->img_path['arrdn'])?$this->img_path['arrdn']:$this->imgpath) . "{$this->images['arrdn']}' alt='{$this->message['arrdn']}' title='{$this->message['arrdn']}'{$this->sl}></a>{$this->br}",$rowRes[$this->setOrderby]);
                    $strReturn = $this->tab(3) . "<td class='dgRow{$clAlt}' ";
                    $buttons = $this->btnOrder;
                    $buttons = str_replace("[E]",$btnUpd,$buttons);
                    $buttons = str_replace("[V]",$btnChk,$buttons);
                    $buttons = str_replace("[D]",$btnDel,$buttons);
                    $buttons = str_replace("[Up]",$btnUp,$buttons);
                    $buttons = str_replace("[Dn]",$btnDn,$buttons);
                    $strReturn.=" >{$this->br}{$buttons}";
                    $strReturn .= $this->tab(3) . "<input type='hidden' id='dg{$this->dgGridID}Choc{$keyValue}' value='dgRows{$clAlt}TR {$claux}'{$this->sl}>{$this->br}";
                    /* $this->tab() ."</td>{$this->br}"; */
                    $strReturn .= $this->tab(3) . "</td>{$this->br}";

                    return $strReturn;
                    break;
            }
        }
        else
        {
            if ($toReturn == "cols")
            {
                $this->columns++;
                $this->tableWidth += intval($width);
                return sprintf($cols, $this->columns, $width, $align);
            }
        }
        return "";
    }
    function issetREQUEST($variable)
    {
        if (strtolower($this->methodForm) == "post")
        {
            if (isset($_POST[$variable]))
                return true;
        }
        else
        {
            if (isset($_GET[$variable]))
                return true;
        }
        return false;
    }
    function REQUEST($variable)
    {
        if (strtolower($this->methodForm) == "post")
        {
            if (isset($_POST["$variable"]))
                return $_POST["$variable"];
            else
                return "";
        }
        else
        {
            if (isset($_GET["$variable"]))
                return $_GET["$variable"];
            else
                return "";
        }
    }
    function validField($strfieldName)
    {
        if (in_array($strfieldName, $this->getFields()))
            return true;
        else
            return false;
    }
    function getFields($filter = '', $screen = '*')
    {
        $arrFilter = explode(",", $filter);
        $arrField  = array();
        foreach ($this->fieldsArray as $value)
        {
            $p = (isset($value["permissions"])) ? $value["permissions"] : "";
            if ($screen != "*" and (strpos($p, $screen) !== false))
                $this->fieldsArray[$value["strfieldName"]]["inputtype"] = (strpos($p, $screen . "-") !== false) ? 2 : 0;
            $check = false;
            if (isset($value["inputtype"]))
                $check = in_array($value["inputtype"], $arrFilter);
            if (empty($filter) or $check)
                if (isset($value["strfieldName"]))
                    $arrField[] = $value["strfieldName"];
        }
        return $arrField;
    }
    function allowed($action, $permissions)
    {
        if (empty($permissions))
            return true;
        if (strpos(strtoupper($permissions), strtoupper($action)) === false)
            return false;
        else
            return true;
    }
    function setDetailsGrid($gridName, $fieldName)
    {
        $this->subGrid  = $gridName;
        $this->relField = $fieldName;
    }
    function setMasterRelation($relationField)
    {
        $suffix    = ((isset($_GET['dg_det_id'])) ? $_GET['dg_det_id'] : (isset($_POST['dg_det_id']) ? $_POST['dg_det_id'] : 0));
        $newSuffix = "";
        for ($n = 0; $n <= strlen($suffix); $n++)
        {
            $c = substr($suffix, $n, 1);
            if (in_array($c, $this->validName))
                $newSuffix .= $c;
        }
        $this->dgGridID  = "sd_" . $this->dgGridID . $newSuffix;
        $this->isDetails = true;
        $this->DG_ajaxid = $this->getAjaxID();
        if ($this->DG_ajaxid == 7 and isset($_GET['DG_ajaxid' . $this->dgGridID]))
            $this->methodForm = 'get';
        # if user passed in xx.id etc, we turn it to `xx`.`id`
        $relationField = str_ireplace(".", "{$this->backtick}.{$this->backtick}", $relationField);
        $this->detailsWhere = sprintf("({$this->backtick}{$relationField}{$this->backtick} = %s)", $this->magic_quote($this->REQUEST('dg_det_id')));
        $this->linkparam("&dg_det_id=" . $this->REQUEST('dg_det_id'));
        return $this->REQUEST('dg_det_id');
    }
    function putAcutes($strText)
    {
        if (empty($strText) or strtolower($this->sqlcharset) != '')
            return $strText;
        if ($this->returnEntities)
            return htmlentities($strText);
        $strText = strtr($strText, array(
            chr(225) => "&aacute;",
            chr(233) => "&eacute;",
            chr(237) => "&iacute;",
            chr(243) => "&oacute;",
            chr(250) => "&uacute;",
            chr(193) => "&Aacute;",
            chr(201) => "&Eacute;",
            chr(205) => "&Iacute;",
            chr(211) => "&Oacute;",
            chr(218) => "&Uacute;",
            chr(209) => "&Ntilde;",
            chr(241) => "&ntilde;",
            chr(176) => "&deg;",
            chr(186) => "&ordm;",
            chr(192) => '&Agrave;',
            chr(193) => '&Aacute;',
            chr(194) => '&Acirc;',
            chr(195) => '&Atilde;',
            chr(196) => '&Auml;',
            chr(197) => '&Aring;',
            chr(198) => '&AElig;',
            chr(199) => '&Ccedil;',
            chr(200) => '&Egrave;',
            chr(201) => '&Eacute;',
            chr(202) => '&Ecirc;',
            chr(203) => '&Euml;',
            chr(204) => '&Igrave;',
            chr(205) => '&Iacute;',
            chr(206) => '&Icirc;',
            chr(207) => '&Iuml;',
            chr(208) => '&ETH;',
            chr(209) => '&Ntilde;',
            chr(210) => '&Ograve;',
            chr(211) => '&Oacute;',
            chr(212) => '&Ocirc;',
            chr(213) => '&Otilde;',
            chr(214) => '&Ouml;',
            chr(216) => '&Oslash;',
            chr(217) => '&Ugrave;',
            chr(218) => '&Uacute;',
            chr(219) => '&Ucirc;',
            chr(220) => '&Uuml;',
            chr(221) => '&Yacute;',
            chr(222) => '&THORN;',
            chr(223) => '&szlig;',
            chr(224) => '&agrave;',
            chr(225) => '&aacute;',
            chr(226) => '&acirc;',
            chr(227) => '&atilde;',
            chr(228) => '&auml;',
            chr(229) => '&aring;',
            chr(230) => '&aelig;',
            chr(231) => '&ccedil;',
            chr(232) => '&egrave;',
            chr(233) => '&eacute;',
            chr(234) => '&ecirc;',
            chr(235) => '&euml;',
            chr(236) => '&igrave;',
            chr(237) => '&iacute;',
            chr(238) => '&icirc;',
            chr(239) => '&iuml;',
            chr(240) => '&eth;',
            chr(241) => '&ntilde;',
            chr(242) => '&ograve;',
            chr(243) => '&oacute;',
            chr(244) => '&ocirc;',
            chr(245) => '&otilde;',
            chr(246) => '&ouml;',
            chr(248) => '&oslash;',
            chr(249) => '&ugrave;',
            chr(250) => '&uacute;',
            chr(251) => '&ucirc;',
            chr(252) => '&uuml;',
            chr(253) => '&yacute;',
            chr(254) => '&thorn;',
            chr(255) => '&yuml;'
        ));
        return $strText;
    }
    function maskdata($value, $mask, $datatype, $arrSelect, $row)
    {
        switch ($datatype)
        {
            case "number":
                return $this->number_mask($value, $mask);
                break;
            case "date":
                return $this->date_mask($value, $mask);
                break;
            case "datetime":
                return $this->datetime_mask($value, $mask);
                break;
            case "check":
                if (strpos($mask, ":") > 0)
                {
                    $arrMask = explode(":", $mask);
                    $value   = (empty($value)) ? 0 : $value;
                    return $arrMask[$value + 1];
                };
                break;
            case "password":
                return str_repeat("*", strlen($value));
                break;
            case "select":
                if (is_array($arrSelect) && $value != '' && isset($arrSelect["$value"]))
                    return $arrSelect["$value"];
                else
                    return $value;
                break;
            default:
                $returnEntities = true;
                if (isset($this->currentFieldName))
                {
                    if (isset($this->noEntities[$this->currentFieldName]))
                        $returnEntities = false;
                    unset($this->currentFieldName);
                }
                return ($returnEntities) ? htmlentities($value) : $value;
                break;
        }
    }
    function number_mask($value, $mask)
    {
        if (is_null($value) or !is_numeric($value))
            return $value;
        $moneySign  = $this->moneySign;
        $decimalSep = $this->decimalsep;
        if (strpos($mask, ":") > 0)
        {
            $arrMask    = explode(":", $mask);
            $mask       = $arrMask[0];
            $moneySign  = (empty($arrMask[1])) ? $moneySign : $arrMask[1];
            $decimalSep = (empty($arrMask[2])) ? $decimalSep : $arrMask[2];
        }
        $thousandSep = ($decimalSep == ".") ? "," : ".";
        switch ($mask)
        {
            case "0":
            case "1":
            case "2":
            case "3":
            case "4":
                $retValue = sprintf("%s", number_format($value, $mask, $decimalSep, $thousandSep));
                break;
            case 'money':
                $retValue = sprintf("%s %s", $moneySign, number_format($value, $this->decimalDigits, $decimalSep, $thousandSep));
                break;
            case 'count':
            case 'integer':
            case 'unsigned':
                $retValue = sprintf('%s', number_format($value, 0, $decimalSep, $thousandSep));
                break;
            case 'percentage':
                $value    = $value * 100;
                $retValue = sprintf('%s ', number_format($value, $this->decimalDigits, $decimalSep, $thousandSep)) . '%';
                break;
            case 'promille':
                $value    = $value * 1000;
                $retValue = sprintf('%s &permil;', number_format($value, $this->decimalDigits, $decimalSep, $thousandSep));
                break;
            default:
                $retValue = number_format($value, 2, $decimalSep, $thousandSep);
                break;
        }
        return $retValue;
    }
    function date_mask($value, $mask)
    {
        $value = substr($value, 0, 10);
        if ($value == $this->nullDateFormat)
            return ($this->nullDateFormat == "") ? "&nbsp;" : $this->nullDateFormat;
        if ($value != "")
        {
            $format = $separator = "";
            if (strpos($mask, ":") > 0)
            {
                $arrMask   = explode(':', $mask);
                $format    = (empty($arrMask[1])) ? $format : $arrMask[1];
                $separator = (empty($arrMask[2])) ? $separator : $arrMask[2];
            }
            $arrDdate = $this->datecheck($value, 'ymd', '-', $format, $separator);
            if ($arrDdate != false)
                $value = $arrDdate['todate'];
        }
        return $value;
    }
    function datetime_mask($value, $mask)
    {
        if ($value == $this->nullDateFormat . ' 00:00:00')
            return ($this->nullDateFormat == "") ? "&nbsp;" : $this->nullDateFormat;
        if ($value != "")
        {
            $format     = $separator = "";
            $timeformat = "His,:";
            if (strpos($mask, ":") > 0)
            {
                $arrMask    = explode(':', $mask);
                $format     = (empty($arrMask[1])) ? $format : $arrMask[1];
                $separator  = (empty($arrMask[2])) ? $separator : $arrMask[2];
                $timeformat = (empty($arrMask[3])) ? $timeformat : $arrMask[3];
                if (empty($format))
                    $format = $this->defaultdateformat;
                if (empty($separator))
                    $separator = $this->defaultdateseparator;
                list($timeformat, $timeseparator) = explode(",", $timeformat);
                if (empty($timeseparator))
                    $timeseparator = ":";
            }
            $dateValue = substr($value, 0, 10);
            $timeValue = substr($value, 11, 8);
            $arrDdate  = $this->datecheck($dateValue, 'ymd', '-', $format, $separator);
            $strTtime  = $this->timecheck($timeValue, 'His', ':', $timeformat, $timeseparator);
            if ($arrDdate != false)
                $value = $arrDdate['todate'];
        }
        return $value . (!empty($strTtime) ? " " . $strTtime : "");
    }
    function timecheck($time, $format = 'His', $separator = ':', $xf098addf2aee16206a799ca4619b6828 = 'His', $toseparator = ':')
    {
        $format = ($format == '') ? 'his' : strtolower($format);
        if (count($datebits = explode($separator, $time)) != 3)
            return false;
        $hour                              = intval($datebits[strpos($format, 'h')]);
        $minute                            = intval($datebits[strpos($format, 'i')]);
        $second                            = intval($datebits[strpos($format, 's')]);
        $hour                              = ($hour < 10) ? '0' . $hour : $hour;
        $hour24                            = $hour;
        $hour12                            = ($hour > 12) ? ($hour - 12) : $hour;
        $ampm                              = ($hour > 12) ? "PM" : "AM";
        $minute                            = ($minute < 10) ? '0' . $minute : $minute;
        $second                            = ($second < 10) ? '0' . $second : $second;
        $xf098addf2aee16206a799ca4619b6828 = str_replace("H", $hour24, $xf098addf2aee16206a799ca4619b6828);
        $xf098addf2aee16206a799ca4619b6828 = str_replace("h", $hour12, $xf098addf2aee16206a799ca4619b6828);
        $xf098addf2aee16206a799ca4619b6828 = str_replace("i", $toseparator . $minute, $xf098addf2aee16206a799ca4619b6828);
        $xf098addf2aee16206a799ca4619b6828 = str_replace("s", $toseparator . $second, $xf098addf2aee16206a799ca4619b6828);
        $xf098addf2aee16206a799ca4619b6828 = str_replace("a", $ampm, $xf098addf2aee16206a799ca4619b6828);
        return $xf098addf2aee16206a799ca4619b6828;
    }
    function datecheck($date, $format = 'ymd', $separator = '-', $xf098addf2aee16206a799ca4619b6828 = 'mdy', $toseparator = '-')
    {
        $format = ($format == '') ? 'ymd' : strtolower($format);
        if (count($datebits = explode($separator, $date)) != 3)
            return false;
        $year  = intval($datebits[strpos($format, 'y')]);
        $month = intval($datebits[strpos($format, 'm')]);
        $day   = intval($datebits[strpos($format, 'd')]);
        $year  = ($year < 10) ? '200' . $year : $year;
        $year  = ($year < 50) ? '20' . $year : $year;
        $year  = ($year < 100) ? '19' . $year : $year;
        $month = ($month < 10) ? '0' . $month : $month;
        $day   = ($day < 10) ? '0' . $day : $day;
        if (($month < 1) or ($month > 12) or ($day < 1) or (($month == 2) and ($day > 28 + (!($year % 4)) - (!($year % 100)) + (!($year % 400)))) or ($day > 30 + (($month > 7) ^ ($month & 1))))
            return false;
        $arrDate           = array(
            'y' => $year,
            'm' => $month,
            'd' => $day,
            'iso' => $year . '-' . $month . '-' . $day,
            'fromdate' => $date,
            'todate' => ''
        );
        $arrDate['todate'] = $arrDate[$xf098addf2aee16206a799ca4619b6828[0]] . $toseparator . $arrDate[$xf098addf2aee16206a799ca4619b6828[1]] . $toseparator . $arrDate[$xf098addf2aee16206a799ca4619b6828[2]];
        return $arrDate;
    }
    function extractLink($valuelist, $rowRes)
    {
        $valuelist = str_replace("\\,", "[comma]", $valuelist);
        $action    = $valuelist;
        if (strpos($valuelist, ',') > 0)
        {
            $xe2275dcae5d20850a185c77bea1ee7b5 = explode(',', $valuelist);
            $action                            = $xe2275dcae5d20850a185c77bea1ee7b5[0];
            array_shift($xe2275dcae5d20850a185c77bea1ee7b5);
            $i     = 0;
            $comma = "";
            foreach ($xe2275dcae5d20850a185c77bea1ee7b5 as $therow)
            {
                $xe2275dcae5d20850a185c77bea1ee7b5[$i] = $comma . "'" . addslashes($rowRes[$xe2275dcae5d20850a185c77bea1ee7b5[$i]]) . "'";
                $comma                                 = ",";
                $i++;
            }
            $action = vsprintf($action, $xe2275dcae5d20850a185c77bea1ee7b5);
        }
        $action = str_replace('"', "'", $action);
        $action = str_replace("'", "'", $action);
        $action = str_replace("[comma]", ",", $action);
        return $action;
    }
    function GetSQLValueString($x093958f56a9365ed6b2f6fea4602f998, $theType, $theDefinedValue = 1, $theNotDefinedValue = 0)
    {
        $x093958f56a9365ed6b2f6fea4602f998 = (!get_magic_quotes_gpc()) ? addslashes($x093958f56a9365ed6b2f6fea4602f998) : $x093958f56a9365ed6b2f6fea4602f998;
        $format                            = '';
        $separator                         = $this->decimalsep;
        if (strpos($theType, ':') > 0)
        {
            $arrMask       = explode(':', $theType);
            $theType       = $arrMask[0];
            $format        = (empty($arrMask[1])) ? $format : $arrMask[1];
            $separator     = (empty($arrMask[2])) ? $separator : $arrMask[2];
            $tseparator    = (isset($arrMask[3])) ? $arrMask[3] : "";
            $arrTimeSet    = explode(",", $tseparator);
            $timeFormat    = $arrTimeSet[0];
            $timeSeparator = (isset($arrTimeSet[1]) ? $arrTimeSet[1] : "");
            if (empty($timeSeparator) and !empty($timeFormat))
                $timeSeparator = ":";
        }
        $thousandSep = ($separator == ".") ? "," : ".";
        switch ($theType)
        {
            case "textarea":
            case "text":
            case "textarea_tinymce":
                $x093958f56a9365ed6b2f6fea4602f998 = (!empty($x093958f56a9365ed6b2f6fea4602f998)) ? $x093958f56a9365ed6b2f6fea4602f998 : "";
                break;
            case "0":
            case "signed":
            case 'count':
            case "integer":
                if ($x093958f56a9365ed6b2f6fea4602f998 == "")
                    $x093958f56a9365ed6b2f6fea4602f998 = 0;
                else
                {
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($thousandSep, '', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($separator, '.', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = intval($x093958f56a9365ed6b2f6fea4602f998);
                };
                break;
            case "money":
                $value = $x093958f56a9365ed6b2f6fea4602f998;
                while (!(is_numeric(substr($value, 0, 1)) or substr($value, 0, 1) == '-'))
                    $value = substr($value, 1, 20);
                $x093958f56a9365ed6b2f6fea4602f998 = $value;
            case "1":
            case "2":
            case "3":
            case "4":
            case "float":
            case "double":
                if ($x093958f56a9365ed6b2f6fea4602f998 == "")
                    $x093958f56a9365ed6b2f6fea4602f998 = 0;
                else
                {
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($thousandSep, '', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($separator, '.', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = floatval($x093958f56a9365ed6b2f6fea4602f998);
                };
                break;
            case "percentage":
                if ($x093958f56a9365ed6b2f6fea4602f998 == "")
                    $x093958f56a9365ed6b2f6fea4602f998 = 0;
                else
                {
                    $x093958f56a9365ed6b2f6fea4602f998 = trim(str_replace('%', '', $x093958f56a9365ed6b2f6fea4602f998));
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($thousandSep, '', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($separator, '.', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = floatval($x093958f56a9365ed6b2f6fea4602f998) / 100;
                };
                break;
            case "promille":
                if ($x093958f56a9365ed6b2f6fea4602f998 == "")
                    $x093958f56a9365ed6b2f6fea4602f998 = 0;
                else
                {
                    $x093958f56a9365ed6b2f6fea4602f998 = trim(str_replace('', '', $x093958f56a9365ed6b2f6fea4602f998));
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($thousandSep, '', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = str_replace($separator, '.', $x093958f56a9365ed6b2f6fea4602f998);
                    $x093958f56a9365ed6b2f6fea4602f998 = floatval($x093958f56a9365ed6b2f6fea4602f998) / 1000;
                };
                break;
            case "date":
                if ($x093958f56a9365ed6b2f6fea4602f998 != "")
                {
                    $adate                             = $this->datecheck($x093958f56a9365ed6b2f6fea4602f998, $format, $separator);
                    $x093958f56a9365ed6b2f6fea4602f998 = ($adate != false and $x093958f56a9365ed6b2f6fea4602f998 != $this->nullDateFormat) ? $adate['iso'] : $this->nullDateFormat;
                }
                else
                    $x093958f56a9365ed6b2f6fea4602f998 = $this->nullDateFormat;
                break;
            case "datetime":
                if ($x093958f56a9365ed6b2f6fea4602f998 != "")
                {
                    $arrValue                          = explode(" ", $x093958f56a9365ed6b2f6fea4602f998);
                    $adate                             = $this->datecheck($arrValue[0], $format, $separator);
                    $atime                             = (isset($arrValue[1])) ? $this->timecheck($arrValue[1], $timeFormat, $timeSeparator) : "";
                    $x093958f56a9365ed6b2f6fea4602f998 = ($adate != false and $x093958f56a9365ed6b2f6fea4602f998 != $this->nullDateFormat) ? $adate['iso'] . " " . $atime : $this->nullDateFormat;
                }
                else
                    $x093958f56a9365ed6b2f6fea4602f998 = $this->nullDateFormat;
                break;
            case "bool":
            case "boolean":
            case "check":
                $x093958f56a9365ed6b2f6fea4602f998 = ($x093958f56a9365ed6b2f6fea4602f998 == "" || $x093958f56a9365ed6b2f6fea4602f998 == "0" || $x093958f56a9365ed6b2f6fea4602f998 == "false") ? $theNotDefinedValue : $theDefinedValue;
                break;
            default:
                $x093958f56a9365ed6b2f6fea4602f998 = (!empty($x093958f56a9365ed6b2f6fea4602f998)) ? $x093958f56a9365ed6b2f6fea4602f998 : "";
                break;
        }
        return $x093958f56a9365ed6b2f6fea4602f998;
    }
    function dateformat($mask)
    {
        $mask = strtolower($mask);
        list($nada, $format, $separator) = explode(":", $mask);
        $format = substr($format, 0, 1) . $separator . substr($format, 1, 1) . $separator . substr($format, 2, 1);
        $format = str_replace("d", "dd", $format);
        $format = str_replace("m", "mm", $format);
        return str_replace("y", "yyyy", $format);
    }
    function DGXtract($strHeader, $strInic, $strFin)
    {
        $pInicial = strpos($strHeader, $strInic);
        $strFinal = substr($strHeader, $pInicial + strlen($strInic), strlen($strHeader));
        $pFinal   = strpos($strFinal, $strFin);
        $strFinal = substr($strFinal, 0, $pFinal);
        return $strFinal;
    }
    function left($str, $length)
    {
        return substr($str, 0, $length);
    }
    function right($str, $length)
    {
        return substr($str, -$length);
    }
    function magic_quote($txt, $isNumeric = false)
    {
        return magic_quote($txt, $isNumeric);
    }
    function tab($amount = 1)
    {
        $strRet = '';
        for ($n = 1; $n <= $amount; $n++)
        {
            $strRet .= $this->tb;
        }
        return $strRet;
    }
}
if (!function_exists('magic_quote'))
{
    function magic_quote($value, $isNumeric = false)
    {
        $value = stripslashes($value);
        if ($isNumeric) {
            if (strpos(".", $value)!==false) $value = floatVal($value); else $value = intval($value);
        }else{
            if (function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()) $value = stripslashes($value);
            /* Revisar porque validaba los null */
            if (1==2 and strtolower($value)=='null')
                $value = my_real_escape_string($value);
            else
                $value = "'".my_real_escape_string($value)."'";
        }
        return $value;
    }
}
if (!function_exists('my_real_escape_string'))
{
    function my_real_escape_string($text)
    {
        return strtr($text, array(
            "\x00" => '\x00',
            "\n" => '\n',
            "\r" => '\r',
            '\\' => '\\\\',
            "'" => "\'",
            '"' => '\"',
            "\x1a" => '\x1a'
        ));
    }
}
if (!function_exists('set_DG_Header'))
{
    function set_DG_Header($pathJS = "js/", $pathCSS = "css/", $closetag = " /", $skin = "", $pathSkins = "")
    {
        $retStr = "\n<link type='text/css' rel='stylesheet' href='{$pathCSS}dgstyle.css'{$closetag}>";
        if (!empty($skin))
            $retStr .= "\n<link type='text/css' rel='stylesheet' href='{$pathSkins}skins/{$skin}/css/dgstyle.css'{$closetag}>";
        $retStr .= "\n<link type='text/css' rel='stylesheet' href='{$pathCSS}dgcalendar.css'{$closetag}>";
        $retStr .= "\n<!--[if IE ]><link type='text/css' rel='stylesheet' href='{$pathCSS}dgstyleIE.css'{$closetag}><![endif]-->";
        $retStr .= "\n<!--[if IE 6]><link type='text/css' rel='stylesheet' href='{$pathCSS}dgstyleIE6.css'{$closetag}><![endif]-->";
        $retStr .= "\n<script type='text/javascript' language='javascript' src='{$pathJS}mmscripts.js'></script>";
        $retStr .= "\n<script type='text/javascript' language='javascript' src='{$pathJS}dgcalendar.js'></script>";
        $retStr .= "\n<script type='text/javascript' language='javascript' src='{$pathJS}dgscripts.js'></script>";
        return $retStr;
    }
}
if (!function_exists('pre'))
{
    function pre($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}