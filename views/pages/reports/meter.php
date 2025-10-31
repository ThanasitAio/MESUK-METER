<?PHP 	session_start(); ?>
<?php

require("../../../pdf/fpdf.php");



class PDF extends FPDF
{
    function SetThaiFont() {
        $this->AddFont('AngsanaNew','','angsa.php');
        $this->AddFont('AngsanaNew','B','angsab.php');
        $this->AddFont('AngsanaNew','I','angsai.php');
        $this->AddFont('AngsanaNew','IB','angsaz.php');
    }

  function Header() 
    {
		$nLef=10;
		$this->SetXY($nLef,7);//ตำแหน่ง X  แนวนอน Y แนวตั้ง
		$this->Image('../../../pdf/happy-warehouse.jpg',$nLef,7,30);	

		  $this->SetFont('angsana','B',14);		 
		  $this->SetXY(50,5);//ตำแหน่ง X  แนวนอน Y แนวตั้ง  
		  $this->Cell(0,10,iconv( 'UTF-8','cp874' ,'บริษัท มีสุข คอร์ปอเรชั่น (2006) จำกัด'),0,0,'L');		
		  $this->SetFont('angsana','',14);		 
		  $this->SetXY(50,10);//ตำแหน่ง X  แนวนอน Y แนวตั้ง  
		  $this->Cell(0,10,iconv( 'UTF-8','cp874' ,'company-address'),0,0,'L');		
		  	    	  
		  $this->SetXY(50,15);//ตำแหน่ง X  แนวนอน Y แนวตั้ง
		  $this->Cell(0,10,iconv( 'UTF-8','cp874' ,'website: www.happyfranchise.co.th/store/'. intval($agn['FTRefCode'])),0,0,'L');			
		  
		  $this->Image('../../../pdf/meesuk-qr.jpg',$nLef+175,7,20);	

		  $this->SetFont('angsana','B',25);
		  $this->SetXY(80,25);//ตำแหน่ง X  แนวนอน Y แนวตั้ง
		  $this->Cell(0,10,iconv( 'UTF-8','cp874' ,'ใบสรุปราคา'),0,0,'L');
		  
		  $this->SetFont('angsana','',14);
		  //$this->SetXY(170,5);//ตำแหน่ง X  แนวนอน Y แนวตั้ง
		  //$this->Cell(0,10,iconv( 'UTF-8','cp874' ,'FM-MKT-02-01'),0,0,'L');
		    

 } 



}


$pdf=new PDF( 'P' , 'mm' , 'A4' );
$pdf->SetMargins( 10,30,109);

//Data loading

//************************//
$pdf->AddFont('angsana','','angsa.php');
$pdf->AddFont('angsana','B','angsab.php');
$pdf->AddFont('angsana','I','angsai.php');
$pdf->AddFont('angsana','BI','angsaz.php'); 

$pdf->SetFont('angsana','B',16);



$pdf->AddPage();
// พิมพ์ข้อความลงเอกสาร
$nLine=8;
$pdf->SetFont('angsana','',14);
$nLeft=100;
$nTop=40;
$pdf->setXY($nLeft,$nTop);
$pdf->MultiCell(55,0, iconv( 'UTF-8','cp874' ,"เลขที่"." : "),0,'R');
$pdf->setXY($nLeft+55,$nTop);
$pdf->MultiCell(70,0, iconv( 'UTF-8','cp874' ,$FTQuoDocNo),0,'L');


$nTop = 0;
$nTop = $nTop + 215;

$nLeft=120;
$pdf->setXY(20,$nTop+20);
$pdf->MultiCell(60, 0 , iconv( 'UTF-8','cp874' , ("ลงชื่อ")." ........................................... ".("ผู้ว่าจ้าง")),0,'C');
$pdf->setXY(20,$nTop+25);
$pdf->MultiCell(60, 8 , iconv( 'UTF-8','cp874' , "( ".$FTCustomer." )"),0,'C');


if ($FTSignImage){
	$pdf->SetXY($nLeft,$nTop+40);//ตำแหน่ง X  แนวนอน Y แนวตั้ง
	$pdf->Image('../picProfile/'.$FTSignImage,$nLeft+25,$nTop+40,25);
}



$pdf->setXY($nLeft,$nTop+20);
$pdf->MultiCell(80, 0 , iconv( 'UTF-8','cp874' , ("ลงชื่อ")." ........................................... ".("ผู้รับจ้าง")),0,'C');
$pdf->setXY($nLeft,$nTop+25);
$pdf->MultiCell(80, 0 , iconv( 'UTF-8','cp874' , "( ".$FTSigned1." )"),0,'C');

$pdf->setXY($nLeft,$nTop+35);
$pdf->MultiCell(80, 0 , iconv( 'UTF-8','cp874' , ("ลงชื่อ")." ........................................... ".("ผู้รับจ้าง")),0,'C');
$pdf->setXY($nLeft,$nTop+40);
$pdf->MultiCell(80, 0 , iconv( 'UTF-8','cp874' , "( ".$FTSigned2." )"),0,'C');

$pdf->setXY($nLeft,$nTop+50);
$pdf->MultiCell(80, 0 , iconv( 'UTF-8','cp874' , ("ลงชื่อ")." ........................................... ".("ฝ่ายขาย")),0,'C');
$pdf->setXY($nLeft,$nTop+55);
$pdf->MultiCell(80, 0 , iconv( 'UTF-8','cp874' , "( ".$FTAgnName." )"),0,'C');

$pdf->setXY($nLeft-50,$nTop+70);
$pdf->MultiCell(100 ,0, iconv( 'UTF-8','cp874' , ('quote-expiration')),0,'L');



$pdf->Output();
