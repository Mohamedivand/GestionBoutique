<?php
ob_start();
include("essai.php");
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Vente.php");
include('formatLettre.php');
try {
  //code...


  // if(!isset($_GET['idVente'],$_COOKIE['idBoutique'])){
  //   header("location:../pages/dashboard/vente.php");
  //   exit();
  // }
  // $idVente = $_GET['idVente'];
  // $vente = new Vente($bdd, $idVente);
  $idBoutique = $_COOKIE['idBoutique'];
  $boutique = new Boutique($bdd, $idBoutique);
  // if(!$vente->getExiste()){
  //   header("location:../pages/dashboard/vente.php");
  //   exit();
  // }
  // if(!$boutique->getExiste()){
  //   header("location:../pages/dashboard/vente.php");
  //   exit();
  // }
  // if($vente->getIdBoutique() != $boutique->getId() ){
  //   header("location:../pages/dashboard/vente.php");
  //   exit();
  // }

  require_once("../lib/php/fpdf/fpdf.php");


  $pdf = new PDF_MC_Table();

  $pdf->AddPage();
  $pdf->SetFont("Times", '', 14);
  //customer and invoice details
  $info = [
    "customer" => "",
    "address" => "",
    "city" => "Salem 636204.",
    "invoice_no" => "",
    "invoice_date" => "30-11-2021",
    "total_amt" => "5200.00",
    "words" => "Rupees Five Thousand Two Hundred Only",
  ];

  if (!is_null($boutique->getImageBanderole())) {

    $pdf->Cell(10);
    $pdf->SetY(0);

    //put logo
    $pdf->Image('../res/images/banderole/' . $boutique->getImageBanderole(), 00, 00, 210, 50);
  } else {
    //Display Company Info
    $pdf->SetFont('Helvetica', 'B', 14);
    $pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $boutique->getNomBoutique()), 0, 1);
    $pdf->SetFont('Times', '', 14);
    $pdf->Cell(50, 7, iconv('UTF-8', 'ISO-8859-1', "Adresse : " . $boutique->getContact()->getAdresse() ?: "--"), 0, 1);
    $pdf->Cell(50, 7, iconv('UTF-8', 'ISO-8859-1', "Téléphone : " . $boutique->getContact()->getTel()), 0, 1);
    $pdf->Cell(50, 7, "Email : " . $boutique->getContact()->getEmail(), 0, 1);

    //Display Horizontal line
    $pdf->Line(0, 48, 210, 48);
  }

  //Billing Details
  $pdf->SetY(55);
  $pdf->SetX(70);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->SetFont('Arial', 'B', 14);
  $pdf->Cell(00, 7, iconv('UTF-8', 'ISO-8859-1', "Liste de la vente du : " . date("d m Y")), "C", 0, 1,);
  $pdf->ln(15);

  // set with of table
  $pdf->SetWidths(array(55, 60, 25, 25, 25));

  // set alignement of table
  $pdf->SetAligns(array('C', '', 'C', 'C', 'R'));

  // taille of row
  $pdf->SetLineHeight(30);

  // thead
  $pdf->SetFont('Arial', 'B', 10);
  $pdf->Cell(55, 9, "IMAGE", 1, 0);
  $pdf->Cell(60, 9, "DESIGNATIONS", 1, 0);
  $pdf->Cell(25, 9, "QTE", 1, 0, "C");
  $pdf->Cell(25, 9, "P.U", 1, 0, "C");
  $pdf->Cell(25, 9, "MONTANT", 1, 1, "C");


  if ($boutique->chargerVenteJour()) {
    $montantTotal = 0;
    $listeProduit = null;
    foreach ($boutique->getTableauVenteJour() as $vente) {
      $montantTotal += $vente->getTotal_a_payer();

      if (is_array($vente->getTableauProduit())) {

        foreach ($vente->getTableauProduit() as $ligne) {
          $listeProduit[] = $ligne;
        }
      }
    }

    foreach ($listeProduit as $ligne) {
      $produit = $ligne['produit'];
      $extension = pathinfo($produit->getImageProduit(), PATHINFO_EXTENSION);

      if (
        in_array(
          $extension,
          ["jpeg", "jpg", "png"]
        )
      ) {
        $image_tmp = true;
      } else {
        $image_tmp = false;
      }
 
      $pdf->Row(array(
        $image_tmp ? $pdf->Image('../res/images/produit/'.$produit->getImageProduit(),$pdf->GetX(),$pdf->GetY(),55,25) : "--", 
        $produit->getNomProduit(),
        $ligne['quantite'],
        $ligne['prixVenteProduit'],
        $ligne['quantite'] * $ligne['prixVenteProduit'],
      ));
    }
  } else {
    //echo(1);
    exit();
  }


  //Display table total row
  $pdf->Cell(165, 9, iconv('UTF-8', 'ISO-8859-1', "Montant Total"), 1, 0, "R");
  $pdf->Cell(25, 9, $montantTotal +  $vente->getReduction(), 1, 1, "R");
  //Display table total row
  $pdf->Cell(165, 9, iconv('UTF-8', 'ISO-8859-1', "Remise"), 1, 0, "R");
  $pdf->Cell(25, 9, $vente->getReduction(), 1, 1, "R");
  //Display table total row
  $pdf->Cell(165, 9, iconv('UTF-8', 'ISO-8859-1', "Total Vente Jour"), 1, 0, "R");
  $pdf->Cell(25, 9, $montantTotal, 1, 1, "R");

  $pdf->Ln(5);
  $pdf->SetFont('Helvetica', '', 12);
  $pdf->SetWidths(array(195));
  $pdf->SetLineHeight(6);
  $pdf->Cell(195, 6, iconv('UTF-8', 'ISO-8859-1', "Arrêté la présente facture à la somme de : " . $word = numberToWord($montantTotal) . " Franc CFA"));
  // $pdf->Row(Array(iconv('UTF-8', 'ISO-8859-1',"Arrêté la présente facture à la somme de : ".$word = numberToWord($montantTotal)." Franc CFA"),));


  $pdf->Ln(15);

  if (!is_null($boutique->getTextFooterPDF())) {
    //Display Footer Text
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetY(266);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', $boutique->getTextFooterPDF()), 0, 1, "C");
  } else {
    //set footer position
    $pdf->SetY(266);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', "Merci pour votre fidélité - a bientôt"), 0, 1, "C");
  }


  $pdf->Output(iconv('UTF-8', 'ISO-8859-1', 'votre reçu de vente.pdf'), 'I');
  ob_end_flush();
} catch (Exception $th) {
  echo ($th->getMessage());
}
