<?php 
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Proformat.php");
include("essai.php");
include("formatLettre.php");

if(!isset($_GET['idProformat'],$_COOKIE['idBoutique'])){
  header("location:../pages/dashboard/proformat.php");
  exit();
}
$idProformat = $_GET['idProformat'];
$proformat = new Proformat($bdd, $idProformat);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);
if(!$proformat->getExiste()){
  header("location:../pages/dashboard/proformat.php");
  exit();
}
if(!$boutique->getExiste()){
  header("location:../pages/dashboard/proformat.php");
  exit();
}
if($proformat->getId_boutique() != $boutique->getId() ){
  header("location:../pages/dashboard/proformat.php");
  exit();
}


// Appel de la librairie FPDF
require_once("../lib/php/fpdf/fpdf.php");
$pdf = new PDF_MC_Table();

$pdf->AddPage();

  //customer and invoice details
  $info=[
    "customer"=>$proformat->getClient()->getNom(),
    "address"=>$proformat->getClient()->getContact()->getTel(),
    "city"=>$proformat->getClient()->getContact()->getEmail(),
    "invoice_no"=>$proformat->getId(),
    "invoice_date"=>date("d/m/Y"),
    "words"=>"Cette offre est valable pour un délai de 6 mois",
  ];


      if(!is_null($boutique->getImageBanderole())){
        $pdf->Cell(10);
        $pdf->SetY(0);

		
		    //put logo
		    $pdf->Image('../res/images/banderole/'.$boutique->getImageBanderole() ,00,00,210,50);
      }
      else{
      //Display Company Info
      $pdf->SetFont('Arial','B',14);
      $pdf->SetY(3);
      $pdf->Cell(50,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getNomBoutique()),0,1);
      $pdf->SetFont('Times','',14);
      $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Adresse: ".(($boutique->getContact()->getAdresse()) ? : "--").","),0,1);
      $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Téléphone: ".(($boutique->getContact()->getTel()) ? : "--")),0,1);
      $pdf->Cell(50,7,"Email: ".(($boutique->getContact()->getEmail()) ? : "--"),0,1);
      
      //Display INVOICE text
      $pdf->SetY(5);
      $pdf->SetX(-75);
      $pdf->SetFont('Arial','B',18);
      $pdf->Cell(50,30,"FACTURE PROFORMA",0,1);
      
      //Display Horizontal line
      $pdf->Line(0,38,210,38);
      }

    

      
      //Billing Details
      $pdf->SetY(55);
      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',12);
      // $pdf->Cell(50,10,"Destinataire: ",0,1);
      $pdf->SetFont('Arial','',12);
      $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Nom Client : ".$info["customer"]),0,1);
      $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Téléphone : ".$info["address"]),0,1);
      $pdf->Cell(50,7,"Email : ".$info["city"],0,1);
      
      //Display Invoice no
      $pdf->SetY(55);
      $pdf->SetX(-80);
      $pdf->Cell(50,7,"Proforma No : 00".$info["invoice_no"]);
      
      //Display Invoice date
      $pdf->SetY(65);
      $pdf->SetX(-80);
      $pdf->Cell(50,7,"Bamako le : ".$info["invoice_date"]);

      // set with of table
      $pdf->SetWidths(Array(80,40,30,40));

      // taille of row
      $pdf->SetLineHeight(6);
      
      //Display Table headings
      $pdf->SetY(85);
      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(80,9,"DESIGNATIONS",1,0);
      $pdf->Cell(40,9,"PRIX",1,0,"C");
      $pdf->Cell(30,9,"QTY",1,0,"C");
      $pdf->Cell(40,9,"TOTAL",1,1,"C");
      $pdf->SetFont('Arial','',12);
      
      //Display table product rows
      if(!is_null($proformat->getTableauProduit())){
        foreach ($proformat->getTableauProduit() as $produit) {
          $pdf->Row(Array(
            iconv('UTF-8', 'ISO-8859-1',$produit["produit"]->getNomProduit()),
            $produit['prixProformatProduit'],
            $produit["quantite"],
            $produit["quantite"] * $produit['prixProformatProduit'],
          ));
      }
    }
    //Display table empty rows
    for($i=0;$i<5-count($produit);$i++)
    {
      $pdf->Cell(80,9,"","LR",0);
      $pdf->Cell(40,9,"","R",0,"R");
      $pdf->Cell(30,9,"","R",0,"C");
      $pdf->Cell(40,9,"","R",1,"C");
    }
      //Display table total row
      $pdf->SetFont('Times','',12);
      $pdf->Cell(150,9,iconv('UTF-8', 'ISO-8859-1',"Total hors réduction"),1,0,"R");
      $pdf->Cell(40,9,$proformat->getTotal(),1,1,"R");

      //Display table total row
      $pdf->SetFont('Times','',12);
      $pdf->Cell(150,9,iconv('UTF-8', 'ISO-8859-1',"Remise"),1,0,"R");
      $pdf->Cell(40,9,$proformat->getReduction(),1,1,"R");

      //Display table total row
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(150,9,iconv('UTF-8', 'ISO-8859-1',"Net à payer"),1,0,"R");
      $pdf->Cell(40,9,$proformat->getTotal() - $proformat->getReduction(),1,1,"R");
      
      $pdf->Ln(5);
      $pdf->SetFont('Helvetica','',12);
      $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Arrêté la présente facture à la somme de : ".$word = numberToWord($proformat->getTotal() - $proformat->getReduction())." Franc CFA"),0,0,"L");
    
      //Display amount in words
      $pdf->SetY(230);
      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(0,9,"",0,1);
      $pdf->SetFont('Arial','',12);
      // $pdf->Cell(0,9,iconv('UTF-8', 'ISO-8859-1',$info["words"]),0,1);
      
      $pdf->Ln(2);

   
      if(!is_null($boutique->getTextFooterPDF())){

            //set footer position
            $pdf->Cell(0,10,"Le Fournissseur :",0,0,"R");
      
      //Display Footer Text
      $pdf->SetY(266);
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getTextFooterPDF()),0,1,"C");
      }
      else{
      //set footer position
      $pdf->Cell(0,10,"Le Fournissseur :",0,0,"R");
 
      //Display Footer Text
      $pdf->SetY(266);
      $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Facture proforma de chez ".$boutique->getNomBoutique()),0,1,"C");
      }
      

    


  $pdf->Output('Facture proformat.pdf','I');
  ob_end_flush();
?>