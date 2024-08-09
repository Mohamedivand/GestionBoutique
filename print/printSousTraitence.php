<?php
ob_start();
include("essai.php");
include("formatLettre.php");
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/sousTraitence.php");

$idSousTraitence = $_GET['idSousTraitence'];
$sousTraitence = new sousTraitence($bdd, $idSousTraitence);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

// if(!isset($_GET['idSoustraitence'],$_COOKIE['idBoutique'])){
//     header("location:../pages/dashboard/sousTraitance.php.php");
//     exit();
//   }
// if(!$boutique->getExiste()){
//     header("location:../pages/dashboard/sousTraitance.php.php");
//     exit();
//   }
//   if(!$boutique->getExiste()){
//     header("location:../pages/dashboard/sousTraitance.php.php");
//     exit();
//   }
//   if($sousTraitence->getId_boutique() != $boutique->getId() ){
//     header("location:../pages/dashboard/sousTraitance.php.php");
//     exit();
//   }
  
  require_once("../lib/php/fpdf/fpdf.php");
  
  
  $pdf = new PDF_MC_Table();
  
  $pdf->AddPage();
  $pdf->SetFont("Times", '', 14);

  $info=[
    "customer"=>$sousTraitence->getNomBoutique(),
    "address"=>$sousTraitence->getContact()->getTel(),
    "city"=>"Salem 636204.",
    "invoice_no"=>$sousTraitence->getId(),
    "invoice_date"=>"30-11-2021",
    "total_amt"=>"5200.00",
    "words"=>"Rupees Five Thousand Two Hundred Only",
  ];

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
  $pdf->Cell(50,10,"BON",0,1);

  //Display Horizontal line
  $pdf->Line(0,48,210,48);
}

        //Billing Details
        $pdf->SetY(55);
        $pdf->SetFont('Helvetica','B',12);       
        $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Nom Fournisseur : ".$info["customer"] ),0,1);
        $pdf->Ln(2);
        $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Num Fournisseur : ".$info["address"] ),0,1);
        $pdf->SetFont('Helvetica','',12);   
        $pdf->SetY(55);
        $pdf->SetX(-67);
        $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"BON N° : 00".$info['invoice_no']));
        $pdf->Ln(9);
        $pdf->SetX(-67);
        $pdf->Cell(50,7,"Bamako le : ".date("d m Y"));

// set with of table
$pdf->SetWidths(Array(90,40,40));

// set alignement of table
$pdf->SetAligns(Array('','C','C','R'));

// taille of row
$pdf->SetLineHeight(8);

// thead
$pdf->SetFont('Arial','B',10);
$pdf->SetY(75);
$pdf->Cell(90,9,"PRODUIT",1,0);
$pdf->Cell(40,9,"QTE_DEMANDER",1,0,"C");
$pdf->Cell(40,9,"MONTANT",1,1,"C");

if(!is_null($sousTraitence->getTableauProduit())){
    foreach ($sousTraitence->getTableauProduit() as $achat) {
      $pdf->Row(Array(
        iconv('UTF-8', 'ISO-8859-1',$achat["produit"]->getNomProduit()),
        $achat["quantite"],
        $toto[]=$achat["prix"]*$achat["quantite"],
      ));
    }
  }

  //Display table total row
  $pdf->Cell(130,9,iconv('UTF-8', 'ISO-8859-1',"Total"),1,0,"R");
  $pdf->Cell(40,9,array_sum($toto),1,1,"C");

  $pdf->Ln(5);
  $pdf->SetFont('Helvetica','',12);
  $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Le total des achats s'élève à : ".$word = numberToWord(array_sum($toto))." Franc CFA"),0,0,"L");


  $pdf->Ln(15);

  if(!is_null($boutique->getTextFooterPDF())){
    //set footer position
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,"Pour Acquis :",0,0,"L");
    $pdf->Cell(0,10,"Le Fournissseur :",0,0,"R");

    //Display Footer Text
    $pdf->SetFont('Arial','',9);
    $pdf->SetY(266);
    $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getTextFooterPDF()),0,1,"C");
  }
  else{
    
    //set footer position
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,"Pour Acquis :",0,0,"L");
    $pdf->Cell(0,10,"Le Fournissseur :",0,0,"R");

    //set footer position
    $pdf->SetY(266);
    $pdf->SetX(10);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Merci pour votre fidélité - a bientôt"),0,1,"C");
  }

  $pdf->Output(iconv('UTF-8', 'ISO-8859-1','votre reçu de sousTraitence.pdf'),'I');
  ob_end_flush();
?>