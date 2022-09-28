<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf extends TCPDF
{ 
    function __construct() {
        parent::__construct(); 
    }
    
     //Page header
    public function Header() {
        // Logo
        $image_file = APPPATH.'media/logo_flash.gif';
        $this->Image($image_file, 15, 10, 50, '', 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('Times', 'B', 10);
        // Title
        $liquidacion_nro = $this->liquidacion_nro;
        $sucursal = ($this->sucursal != 'Seleccione')?$this->sucursal:'';
        $cliente = $this->cliente;
        $factura_nro = $this->factura_nro;
        $periodo_desde = $this->periodo_desde;
        $periodo_hasta = $this->periodo_hasta;
        $departamento = $this->departamento;
        $tbl = <<<EOD
                <span style="text-align:right;margin-right:100px">$departamento</span><br/><br/><br/><br/><br/>
<table cellspacing="0" cellpadding="0" border="">
    <tr><td colspan="2"><label for="nro" style="font-style: italic;font-size:14px;margin-top:-20px;">Liquidación Nro: $liquidacion_nro</label></td><td>$sucursal</td></tr>
</table>
<br/><br/>
<table cellspacing="0" cellpadding="0" border=""  width="100%">                
    <tr><td width="15%">Cliente:</td><td width="85%">$cliente</td></tr>
    <tr><td width="15%">Factura</td><td width="85%">$factura_nro</td></tr>
    <tr><td width="15%">Período</td><td width="85%">del $periodo_desde  al $periodo_hasta</td></tr>
</table>  
<br/>              
EOD;

$this->writeHTML($tbl, true, false, false, false, '');
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