<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Rendering_service.php";

class Pdf_rendering_service extends Rendering_service
{
    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
    }

    public function convert_html_to_pdf($input_html = "", $output_name = "", $dest = "I", $lang = "en")
    {
        /* @param $dest (string) Destination where to send the document.
            It can take one of the following values:
                I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
                D: send to the browser and force a file download with the name given by name.
                F: save to a local server file with the name given by name.
                S: return the document as a string (name is ignored).
                FI: equivalent to F + I option
                FD: equivalent to F + D option
                E: return the document as base64 mime multi-part email attachment (RFC 2045)
        */

        if($input_html == "")
        {
            exit();
        }

        if($output_name == "")
        {
            $output_name = date("Ymdhis").".pdf";
        }

        require_once(BASEPATH.'plugins/tcpdf/tcpdf.php');
        require_once(BASEPATH.'plugins/tcpdf/config/lang/eng.php');

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        //$pdf->SetCreator(PDF_CREATOR);
        //$pdf->SetAuthor('');
        //$pdf->SetTitle('');
        //$pdf->SetSubject('');
        //$pdf->SetKeywords('');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->setPrintHeader(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        //set some language-dependent strings
        $pdf->setLanguageArray($l);

        // ---------------------------------------------------------
        // set font
        switch ($lang) {
            case 'ja':
                $pdf->SetFont('freemonounic_ja', '', 10);
                break;
            case 'ko':
                $pdf->SetFont('freemonounic_ko', '', 10);
                break;
            case 'zh-tw':
                $pdf->SetFont('freemonounic_zh-tw', '', 10);
                break;
            case 'zh-cn':
                $pdf->SetFont('freemonounic_zh-cn', '', 10);
                break;
            default:
                $pdf->SetFont('freemono', '', 10);
        }

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($input_html, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();

        //Close and output PDF document
        $pdf->Output($output_name, $dest);
        return $output_name;
    }
}

/* End of file rendering_service.php */
/* Location: ./system/application/libraries/service/Rendering_service.php */