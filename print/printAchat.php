<?php 
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Achat.php");
include("essai.php");

if(!isset($_GET['idAchat'],$_COOKIE['idBoutique'])){
  header("location:../pages/dashboard/achat.php");
  exit();
}
$idAchat = $_GET['idAchat'];
$achat = new Achat($bdd, $idAchat);

$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

if(!$achat->getExiste()){
  header("location:../pages/dashboard/achat.php");
  exit();
}
if(!$boutique->getExiste()){
  header("location:../pages/dashboard/achat.php");
  exit();
}
if($achat->getId_boutique() != $boutique->getId() ){
  header("location:../pages/dashboard/achat.php");
  exit();
}

require_once("../lib/php/fpdf/fpdf.php");

$pdf = new PDF_MC_Table();

$pdf->AddPage();

    if(!is_null($boutique->getImageBanderole())){

      $pdf->Cell(10);
  
      //put logo
      $pdf->Image('../res/images/banderole/'.$boutique->getImageBanderole() ,00,00,210,50);
    }
    else{
    //Display Company Info
    $pdf->SetFont('Helvetica','B',14);
    $pdf->Cell(50,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getNomBoutique()),0,1);
    $pdf->SetFont('Times','',14);
    $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Adresse : ".$boutique->getContact()->getAdresse()? : "--"),0,1);
    $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Téléphone : ".$boutique->getContact()->getTel()),0,1);
    $pdf->Cell(50,7,"Email : ".$boutique->getContact()->getEmail(),0,1);
    
    //Display INVOICE text
    $pdf->SetY(25);
    $pdf->SetX(-67);
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(50,10,"BON ACHAT",0,1);

    //Display Horizontal line
    $pdf->Line(0,48,210,48);
  }

          //Display Invoice no
          $pdf->SetY(55);
          $pdf->SetX(10);
          $pdf->SetFont('Arial','B',12);
          $pdf->Cell(0,7,iconv('UTF-8', 'ISO-8859-1',"Bon d'achat N° : 00".$achat->getId()),0,0,'R');
          


    // set with of table
    $pdf->SetWidths(Array(50,45,40,45));

    // set alignement of table
    $pdf->SetAligns(Array('','C','C','C'));

    // taille of row
    $pdf->SetLineHeight(6);
		
    $pdf->SetY(65);
		$pdf->SetFont('Helvetica','B',11);
		$pdf->Cell(50,15,'Produit',1,0);
		$pdf->Cell(45,15,'DateCommande',1,0,'C');
		$pdf->Cell(40,15,iconv('UTF-8', 'ISO-8859-1','Qte_Commander'),1,0,'C');
		$pdf->Cell(45,15,iconv('UTF-8', 'ISO-8859-1','fournisseur'),1,1,'C');
		

		
    $pdf->SetFont('Times','',11);

      if(!is_null($achat->getTableauProduit())){
			foreach ($achat->getTableauProduit() as $produit) {
					$pdf->Row(Array(
            iconv('UTF-8', 'ISO-8859-1',$produit['produit']->getNomProduit()),
						$achat->getDate(),
						$produit["quantiteDemander"],
						"Entrepot",
					));
			}
    }


$pdf->Ln(10);
if(!is_null($boutique->getTextFooterPDF())){

  //set footer position
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Pour Acquis :",0,0,"L");
  $pdf->Cell(0,10,"Le Fournissseur :",0,0,"R");

  //Display Footer Text
  $pdf->SetFont('Arial','',9);
  $pdf->SetY(266);
  $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getTextFooterPDF()),0,1,"C");
}
else{
  //set footer position
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,10,"Pour Acquis :",0,0,"L");
  $pdf->Cell(0,10,"Le Fournissseur :",0,0,"R");

  //Display Footer Text
  $pdf->SetY(266);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Bon d'achat' de chez ".$boutique->getNomBoutique()),0,1,"C");
}

  $pdf->Output("Bon d'achat.pdf",'I');
  ob_end_flush();
?>