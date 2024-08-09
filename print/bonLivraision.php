<?php 
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Vente.php");
include("essai.php");

if(!isset($_GET['idVente'],$_COOKIE['idBoutique'])){
  header("location:../pages/dashboard/vente.php");
  exit();
}
$idVente = $_GET['idVente'];
$vente = new Vente($bdd, $idVente);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);
if(!$vente->getExiste()){
  header("location:../pages/dashboard/vente.php");
  exit();
}
if(!$boutique->getExiste()){
  header("location:../pages/dashboard/vente.php");
  exit();
}
if($vente->getIdBoutique() != $boutique->getId() ){
  header("location:../pages/dashboard/vente.php");
  exit();
}

require_once("../lib/php/fpdf/fpdf.php");
$pdf = new PDF_MC_Table();

$pdf->AddPage();

  //customer and invoice details
  $info=[
    "customer"=>iconv('UTF-8', 'ISO-8859-2','Doit : '.$vente->getClient()->getNom()),
    "address"=>iconv('UTF-8', 'ISO-8859-2',"Numéro Client : ".$vente->getClient()->getContact()->getTel()),
    "city"=>$vente->getClient()->getContact()->getEmail(),
    "invoice_no"=>"00".$vente->getId(),
  ];

  if(!is_null($boutique->getImageBanderole())){

    $pdf->Cell(10);
    
        //put logo
        $pdf->Image('../res/images/banderole/'.$boutique->getImageBanderole() ,00,00,210,50);
  }
  else{
          //Display Company Info
          $pdf->SetFont('Arial','B',14);
          $pdf->Cell(50,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getNomBoutique()),0,1);
          $pdf->SetFont('Arial','',14);
          $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Adresse: ".(($boutique->getContact()->getAdresse()) ? : "--").","),0,1);
          $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Téléphone: ".(($boutique->getContact()->getTel()) ? : "--")),0,1);
          $pdf->Cell(50,7,"Email: ".(($boutique->getContact()->getEmail()) ? : "--"),0,1);
  }
        
        //Display Invoice no
        $pdf->SetY(55);
        $pdf->SetX(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Bordereau de livraison N° : ".$info["invoice_no"]));
        $pdf->ln(8);
        $pdf->Cell(50,7,$info["customer"],0,1);
        $pdf->ln(8);

        
        // set with of table
        $pdf->SetWidths(Array(40,100,40));

        // set alignement of table
        $pdf->SetAligns(Array('C','C','C'));

        // taille of row
        $pdf->SetLineHeight(6);

      //Display Table headings
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(40,9,iconv('UTF-8', 'ISO-8859-1',"N°"),1,0,"C");
      $pdf->Cell(100,9,"DESIGNATION",1,0,"C");
      $pdf->Cell(40,9,"QTE",1,1,"C");
      $pdf->SetFont('Arial','',12);

      if(!is_null($vente->getTableauProduit())){
        foreach ($vente->getTableauProduit() as $produit) {
          $taille [] = $produit;
          for($m=0;$m < count($taille);$m++){
          }
          $pdf->Row(Array(
            $m,
            iconv('UTF-8', 'ISO-8859-1',$produit['produit']->getNomProduit()),
            $produit['quantite'],
          ));
        }
      }

      
      
      
      $pdf->Ln(10);
      if(!is_null($boutique->getTextFooterPDF())){
      
          //set footer position
          $pdf->SetFont('Arial','',12);
          $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Le Réceptionnaire :"),0,0,"L");
          $pdf->Cell(0,10,"Le Fournisseur :",0,0,"R");

          //Display Footer Text
          $pdf->SetFont('Arial','',9);
          $pdf->SetY(266);
          // $pdf->SetX(150);
          $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getTextFooterPDF()),0,1,"C");
        }
        else{
        //set footer position
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Le Réceptionnaire :"),0,0,"L");
        $pdf->Cell(0,10,"Le Fournisseur :",0,0,"R");

        //Display Footer Text
        $pdf->SetY(266);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Bon de livraision de chez ".$boutique->getNomBoutique()),0,1,"C");
        }


$pdf->Output();
ob_end_flush();
?>