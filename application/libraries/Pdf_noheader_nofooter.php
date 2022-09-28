<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf_noheader_nofooter extends TCPDF
{ 
    function __construct() {
        parent::__construct(); 
    }
    
     //Page header
    public function Header() {
    }

    // Page footer
    public function Footer() {
    }
}
/*Author:Tutsway.com */
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */