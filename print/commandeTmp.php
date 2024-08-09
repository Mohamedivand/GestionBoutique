<?php 
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Commande.php");
require_once("../class/Contact.php");
require_once("../class/Vente.php");
require_once("../class/User.php");
include('essai.php');
include('formatLettre.php');

$idCommande = $_GET['idCommande'];
$commande = new Commande($bdd, $idCommande);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

require_once("../lib/php/fpdf/fpdf.php");
$pdf = new PDF_MC_Table();

$pdf->AddPage();


  //customer and invoice details
  $info=[
    "customer"=>iconv('UTF-8', 'ISO-8859-1'," "),
    "address"=>iconv('UTF-8', 'ISO-8859-1',"Num Client : ".$commande->getContact()->getTel()),
    "city"=>iconv('UTF-8', 'ISO-8859-1',"Email Client : ".$commande->getContact()->getEmail()),
    "invoice_no"=>"00".$commande->getId(),
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
        $pdf->Cell(50,10,"BON DE COMMANDE",0,1);
      
        //Display Horizontal line
        $pdf->Line(0,48,210,48);
      }

      //Billing Details
      $pdf->SetY(55);
      $pdf->SetX(10);
      $pdf->SetFont('Arial','B',12);
      // $pdf->Cell(50,10,"Destinataire: ",0,1);
      $pdf->SetFont('Arial','',12);
      $pdf->Cell(50,7,$info["address"],0,1);
      $pdf->Cell(50,7,$info["city"],0,1);
      
      //Display Invoice no
      $pdf->SetY(55);
      $pdf->SetX(-67);
      $pdf->Cell(50,7,iconv('UTF-8', 'ISO-8859-1',"Bon de commande N° : ".$info['invoice_no']));
      
      //Display Invoice date
      $pdf->SetY(63);
      $pdf->SetX(-58);
      $pdf->Cell(50,7,"Bamako le : ".date("d m Y"));

      // set with of table
      $pdf->SetWidths(Array(70,30,28,30,32));

      // taille of row
      $pdf->SetLineHeight(6);
      
      if($commande->getStatut() == 1){
        //Display Table headings
        $pdf->SetY(82);
        $pdf->SetX(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(70,9,"DESIGNATION",1,0);
        $pdf->Cell(30,9,"P.U",1,0,"C");
        $pdf->Cell(28,9,"QTE",1,0,"C");
        $pdf->Cell(30,9,"TOTAL",1,0,"C");
        $pdf->Cell(32,9,iconv('UTF-8', 'ISO-8859-1',"QTE  Retourner"),1,1,"C");
        $pdf->SetFont('Arial','',12);
      }else{
        //Display Table headings
        $pdf->SetY(85);
        $pdf->SetX(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(70,9,"DESIGNATION",1,0);
        $pdf->Cell(30,9,"P.U",1,0,"C");
        $pdf->Cell(28,9,"QTE",1,0,"C");
        $pdf->Cell(30,9,"TOTAL",1,0,"C");
        $pdf->Cell(32,9,"ETAT",1,1,"C");
        $pdf->SetFont('Arial','',12);
        
      }
      
      //Display table product rows
      if(!is_null($commande->getTableauProduit())){
        foreach ($commande->getTableauProduit() as $produit) {
          if($commande->getStatut() == 1){
            $pdf->Row(Array(
              iconv('UTF-8', 'ISO-8859-1',$produit["produit"]["nomProduit"]),
              $prixUnit = ($commande->getTypeCommande()=="det") ? $produit["produit"]["prixVenteDetail"] : $produit["produit"]["prixVenteEngros"],
              $produit["quantite"],
              $long[] = $produit["quantite"] * $prixUnit,
              $produit['quantiteRetourner'],
            ));
          }else{
            $pdf->Row(Array(
              iconv('UTF-8', 'ISO-8859-1',$produit["produit"]["nomProduit"]),
              $prixUnit = ($commande->getTypeCommande()=="det") ? $produit["produit"]["prixVenteDetail"] : $produit["produit"]["prixVenteEngros"],
              $produit["quantite"],
              $long[] = $produit["quantite"] * $prixUnit,
              "",
            ));
          }
      }
    }
      //Display table empty rows
      for($i=0;$i<12-count($produit);$i++)
      {
        $pdf->Cell(70,9,"","LR",0);
        $pdf->Cell(30,9,"","R",0,"R");
        $pdf->Cell(28,9,"","R",0,"C");
        $pdf->Cell(30,9,"","R",0,"C");
        $pdf->Cell(32,9,"","R",1,"R");
      }

      if($commande->getStatut() == 1){
        //Display table total row
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(158,9,"NET A PAYER",1,0,"R");
        $pdf->Cell(32,9,array_sum($long),1,1,"C");
        $pdf->Ln(5);
        $pdf->SetFont('Helvetica','',12);
        $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Arrêté la présente facture à la somme de : ".$word = numberToWord(array_sum($long))." Franc CFA"),0,0,"L");
      
      }else{
        //Display table total row
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(158,9,"NET A PAYER",1,0,"R");
        $pdf->Cell(32,9,array_sum($long),1,1,"C");
  
        //Display table total row
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(158,9,"MONTANT RECU",1,0,"R");
        $pdf->Cell(32,9,"",1,1,"C");

        $pdf->Ln(5);
        $pdf->SetFont('Helvetica','',12);
        $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Arrêté la présente facture à la somme de : ".$word = numberToWord(array_sum($long))." Franc CFA"),0,0,"L");
      

      }

      $pdf->Ln(10);
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
      $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',$boutique->getNomBoutique()." :"),0,0,"R");
      
      //Display Footer Text
      $pdf->SetY(266);
      $pdf->SetFont('Arial','',9);
      $pdf->Cell(0,10,iconv('UTF-8', 'ISO-8859-1',"Bon de commande de chez ".$boutique->getNomBoutique()),0,1,"C");

      }
      



  $pdf->Output('Bon de commande.pdf','I');
  ob_end_flush();
?>