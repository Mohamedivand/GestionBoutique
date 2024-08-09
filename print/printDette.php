<?php
ob_start();
include("essai.php");
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Vente.php");
include('formatLettre.php');

if(!isset($_GET['idVente'],$_COOKIE['idBoutique'])){
  header("location:../pages/dashboard/dette.php");
  exit();
}
$idVente = $_GET['idVente'];
$vente = new vente($bdd, $idVente);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);
if(!$vente->getExiste()){
  header("location:../pages/dashboard/dette.php");
  exit();
}
if(!$boutique->getExiste()){
  header("location:../pages/dashboard/dette.php");
  exit();
}
if($vente->getIdBoutique() != $boutique->getId() ){
  header("location:../pages/dashboard/dette.php");
  exit();
}

require_once("../lib/php/fpdf/fpdf.php");


$pdf = new PDF_MC_Table();

$pdf->AddPage();
$pdf->SetFont("Times", '', 14);
  //customer and invoice details
  $info=[
    "customer"=>$vente->getClient()->getNom(),
    "address"=>$vente->getClient()->getContact()->getTel(),
    "city"=>"Salem 636204.",
    "invoice_no"=>$vente->getId(),
    "invoice_date"=>"30-11-2021",
    "total_amt"=>"5200.00",
    "words"=>"Rupees Five Thousand Two Hundred Only",
  ];

  if(!is_null($boutique->getImageBanderole())){

    $pdf->Cell(10);
    $pdf->SetY(0);
    
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
  $pdf->Cell(50,10,"FACTURE",0,1);

  //Display Horizontal line
  $pdf->Line(0,48,210,48);
}

        //Billing Details
        $pdf->SetY(55);
        $pdf->SetFont('Arial','',12);        
        $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Facture N°: 00".$info["invoice_no"] ),0,1);
        $pdf->SetY(65);
        $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Doit : ".$info["customer"] ),0,1);

        //Display Invoice date
        $pdf->SetY(65);
        $pdf->SetX(-67);
        $pdf->Cell(50,7,"Bamako le : ".date("d m Y"));
        $pdf->ln(13);

// set with of table
$pdf->SetWidths(Array(115,25,25,25));

// set alignement of table
$pdf->SetAligns(Array('','C','C','R'));

// taille of row
$pdf->SetLineHeight(6);

// thead
$pdf->SetFont('Arial','B',10);
$pdf->Cell(115,9,"DESIGNATIONS",1,0);
$pdf->Cell(25,9,"QTE",1,0,"C");
$pdf->Cell(25,9,"P.U",1,0,"C");
$pdf->Cell(25,9,"MONTANT",1,1,"C");

if(!is_null($vente->getTableauProduit())){
    foreach ($vente->getTableauProduit() as $produit) {
      $pdf->Row(Array(
        iconv('UTF-8', 'ISO-8859-1',$produit["produit"]->getNomProduit()),
        $produit["quantite"],
        $produit["prixVenteProduit"],
        $toto = $produit["quantite"] * $produit["prixVenteProduit"],
      ));
    }
  }
  //Display table total row
  $pdf->Cell(165,9,iconv('UTF-8', 'ISO-8859-1',"Remise"),1,0,"R");
  $pdf->Cell(25,9,"-" .$vente->getReduction(),1,1,"R");
  
  //Display table total row
  $pdf->Cell(165,9,iconv('UTF-8', 'ISO-8859-1',"Montant Payer"),1,0,"R");
  $pdf->Cell(25,9,$vente->getMontantPayer(),1,1,"R");

  //Display table total row
  $pdf->Cell(165,9,iconv('UTF-8', 'ISO-8859-1',"Reste a Payer"),1,0,"R");
  $pdf->Cell(25,9,$reste = $vente->getTotal_a_payer() - $vente->getMontantPayer(),1,1,"R");


  //Display table total row
  $pdf->Cell(165,9,iconv('UTF-8', 'ISO-8859-1',"Montant Total"),1,0,"R");
  $pdf->Cell(25,9,$vente->getTotal_a_payer(),1,1,"R");

  $pdf->Ln(5);
  $pdf->SetFont('Helvetica','',12);
  $pdf->SetWidths(Array(195));
  $pdf->SetLineHeight(6);
  $pdf->Row(Array(iconv('UTF-8', 'ISO-8859-1',"Arrêté la présente facture à la somme de : ".$word = numberToWord($vente->getTotal_a_payer())." Franc CFA"),));

  $pdf->Ln(15);

  if(!is_null($boutique->getTextFooterPDF())){
    //set footer position
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,"Pour Acquit :",0,0,"L");
    $pdf->Cell(0,10,"Le Fournisseur :",0,0,"R");

    //Display Footer Text
    $pdf->SetFont('Arial','',9);
    $pdf->SetY(266);
    $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getTextFooterPDF()),0,1,"C");
  }
  else{
    
    //set footer position
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,"Pour Acquit :",0,0,"L");
    $pdf->Cell(0,10,"Le Fournisseur :",0,0,"R");

    //set footer position
    $pdf->SetY(266);
    $pdf->SetX(10);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Merci pour votre fidélité - a bientôt"),0,1,"C");
  }
  

  $pdf->Output(iconv('UTF-8', 'ISO-8859-1','votre reçu de vente.pdf'),'I');
  ob_end_flush();
?>