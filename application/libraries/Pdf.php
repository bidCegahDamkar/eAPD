<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class Pdf extends TCPDF
{ 
	function __construct() 
	{ 
        parent::__construct(); 
	}

	public function Header() {
        $CI =& get_instance();
        $skpd = $CI->config->item('skpd');
        /*// Logo
        $image_file = FCPATH.'assets/images/logo_dki.png';
        $this->Image($image_file, 15, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('times', 'B', 13);
		// Title
		//$this->Cell(0, 0, 'tes', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetXY(30, 13);
		$this->Cell(160, 0, 'PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA', 0, false, 'C', 0, '', 1, false, 'M', 'M');
        //$this->SetFont('times', 'B', 15);
		$this->SetXY(30, 19);
		$this->Cell(160, 0, 'DINAS PENANGGULANGAN KEBAKARAN DAN PENYELAMATAN', 0, false, 'C', 0, '', 1, false, 'M', 'M');
        //$this->Cell(0, 0, '   tes', 1, false, 'C', 0, '', 1, false, 'M', 'M');
        $this->SetFont('times', 'B', 10);
        $this->SetXY(30, 24);
        $this->Cell(160, 0, 'http://www.jakartafire.net', 0, false, 'C', 0, '', 1, false, 'M', 'M');
        //$this->SetLineWidth(0.2); */
        $this->SetFont('times', 'B', 13);
        $this->SetXY(20, 13);
        $this->Cell(160, 0, $skpd, 0, false, 'L', 0, '', 1, false, 'M', 'M');
        $this->SetXY(20, 19);
        $this->Cell(160, 0, 'Pemerintah Provinsi DKI Jakarta', 0, false, 'L', 0, '', 1, false, 'M', 'M');
        $color = 'black';
        $style = array('width' => (0.5), 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color);
        $this->Line(15,27,190,27,$style);
        //$this->SetXY(30, 29);
        //$this->Cell(220, 0, 'what the hell', 0, false, 'C', 0, '', 1, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        //logo tcpdf
        //$logo = FCPATH.'assets/images/tcpdf.png';
        //$this->Image($logo, 190, 285, 10, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 0, 'Halaman '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetX(280);
        $this->Cell(0, 10, 'powered by eAPD', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
    }

    public function MultiRow($left, $right) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

        $page_start = $this->getPage();
        $y_start = $this->GetY();

        // write the left cell
        $this->MultiCell(40, 0, $left, 0, 'L', 0, 2, '', '', true, 0);

        $page_end_1 = $this->getPage();
        $y_end_1 = $this->GetY();

        $this->setPage($page_start);

        // write the right cell
        $this->MultiCell(0, 0, $right, 0, 'L', 0, 1, $this->GetX() ,$y_start, true, 0);

        $page_end_2 = $this->getPage();
        $y_end_2 = $this->GetY();

        // set the new row position by case
        if (max($page_end_1,$page_end_2) == $page_start) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 == $page_end_2) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 > $page_end_2) {
            $ynew = $y_end_1;
        } else {
            $ynew = $y_end_2;
        }

        $this->setPage(max($page_end_1,$page_end_2));
        $this->SetXY($this->GetX(),$ynew);
    }

    public function MultiRowGedung($left, $right) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

        $page_start = $this->getPage();
        $y_start = $this->GetY();

        // write the left cell
        $this->MultiCell(40, 0, $left, 0, 'L', 0, 2, '', '', true, 0);

        $page_end_1 = $this->getPage();
        $y_end_1 = $this->GetY();

        $this->setPage($page_start);

        // write the right cell
        $this->MultiCell(80, 0, $right, 0, 'L', 0, 1, $this->GetX() ,$y_start, true, 0);

        $page_end_2 = $this->getPage();
        $y_end_2 = $this->GetY();

        // set the new row position by case
        if (max($page_end_1,$page_end_2) == $page_start) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 == $page_end_2) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 > $page_end_2) {
            $ynew = $y_end_1;
        } else {
            $ynew = $y_end_2;
        }

        $this->setPage(max($page_end_1,$page_end_2));
        $this->SetXY($this->GetX(),$ynew);
    }
}
