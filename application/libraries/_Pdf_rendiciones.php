<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf_rendiciones extends TCPDF
{ 
    public $cliente = "";
    public $rendicion_id = "";
    public $domicilio = "";
    public $provincia = "";
    public $codigo_postal = "";
    public $image_dhl = "";
    function __construct() {
        parent::__construct(); 
    }
    
    //Page header
    public function Header() {
        // Logo
        $image_file = APPPATH.'media/logo_flash.gif';
        $image_file_dhl = APPPATH.'media/dhl.jpg';
        $this->Image($image_file, 15, 10, 50, '', 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('Times', 'B', 10);
        // Title
        $rendicion_id = $this->rendicion_id;
        $cliente = $this->cliente;
        $domicilio = $this->domicilio;
        $provincia = $this->provincia;
        $codigo_postal = $this->codigo_postal;
        $tbl = <<<EOD
                <span style="text-align:left;margin-left:100px">
                    <table cellspacing="0" cellpadding="0" border="0" width="80%">
                        <tr>
                            <td colspan="4" align="center"><label for="rendicion" style="font-size:10px">RENDICION DE PIEZAS NRO. $rendicion_id </label></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="seniores" style="font-size:10px">Señor/es</label></td>
                            <td></td>
                            <td></td>
                            <td></td>
                       </tr>
                        <tr>
                            <td colspan="2"><label for="cliente" style="font-size:10px">$cliente</label></td>
                            <td></td>
                            <td></td>
                            <td></td>
                       </tr>
                        <tr>
                            <td colspan="2"><label for="domicilio" style="font-size:10px">$domicilio</label></td>
                            <td></td>
                            <td></td>
                            <td></td>
                       </tr>
                        <tr>
                            <td colspan="2"><label for="provincia" style="font-size:10px">$provincia</label></td>
                            <td></td>
                            <td></td>
                            <td></td>
                       </tr>
                        <tr>
                            <td><label for="cp" style="font-size:10px">$codigo_postal</label></td>
                            <td></td>
                            <td></td>
                            <td></td>
                       </tr>
                    </table>
                </span><br/><br/>             
EOD;
$this->writeHTML($tbl, true, false, false, false, '');
$this->image($image_file_dhl, 230, 10, 50, '', 'jpg', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
/*Author:Tutsway.com */
/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */