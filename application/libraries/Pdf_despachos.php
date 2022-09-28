<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf_despachos extends TCPDF
{ 
    function __construct() {
        parent::__construct(); 
    }
    
     //Page header
    public function Header() {
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 6);
        // Page number
        $this->SetY(-16);
        $this->writeHTMLCell(0, 2, '', '', "<hr>", 0, false);
        $this->SetY(-15);
        setlocale(LC_ALL,"es_ES");
            $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
//            echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y')."<br/>" ;
        $this->Cell(0, 10, $dias[date('w')].", ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
/*Author:Tutsway.com */
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */