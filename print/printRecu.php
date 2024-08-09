<?php
ob_start();
require("essai.php");
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Vente.php");

if (!isset($_GET['idVente'], $_COOKIE['idBoutique'])) {
  header("location:../pages/dashboard/vente.php");
  exit();
}
$idVente = $_GET['idVente'];
$vente = new Vente($bdd, $idVente);
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);
if (!$vente->getExiste()) {
  header("location:../pages/dashboard/vente.php");
  exit();
}
if (!$boutique->getExiste()) {
  header("location:../pages/dashboard/vente.php");
  exit();
}
if ($vente->getIdBoutique() != $boutique->getId()) {
  header("location:../pages/dashboard/vente.php");
  exit();
}

require_once("../lib/php/fpdf/fpdf.php");
$pdf = new PDF_MC_Table("P", "mm", array(80, 200));

$pdf->AddPage();

//customer and invoice details
$info = [
  "customer" => $vente->getClient()->getNom(),
  "address" => "4th cross,Car Street,",
  "city" => "Salem 636204.",
  "invoice_no" => $vente->getId(),
  "invoice_date" => "30-11-2021",
  "total_amt" => "5200.00",
  "words" => "Rupees Five Thousand Two Hundred Only",
];



//Display Company Info
$pdf->SetY(00);
$pdf->SetX(30);
$pdf->SetFont('Times', 'B', 7);
$pdf->Cell(25, 10, iconv('UTF-8', 'ISO-8859-2', $boutique->getNomBoutique()), 0, 1);
$pdf->Line(70, 10, 10, 10);
$pdf->Ln(5);

//Display Invoice no
$pdf->SetX(-25);
$pdf->SetFont('Times', '', 9);
$pdf->Cell(25, 7, iconv('UTF-8', 'ISO-8859-2', "Reçu N° : 00" . $vente->getId()));
$pdf->Ln(3);
//Display Invoice date
$pdf->SetX(-25);
$pdf->Cell(25, 7, "Date : " . date("d/m/Y"));

//Billing Details
$pdf->SetX(00);
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(25, 10, iconv('UTF-8', 'ISO-8859-2', "Client: " . $info["customer"]), 0, 1);

$pdf->Ln(1);

// set with of table
$pdf->SetWidths(array(30, 15, 15, 20));

// set alignement of table
$pdf->SetAligns(array('', 'C', 'C', 'R'));

// taille of row
$pdf->SetLineHeight(4);

// thead
$pdf->SetX(00);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 6, "PRODUIT", 1, 0);
$pdf->Cell(15, 6, "QTE", 1, 0, "C");
$pdf->Cell(15, 6, "UNITE", 1, 0, "C");
$pdf->Cell(20, 6, "TOTAL", 1, 1, "C");

//Display table product rows
if (!is_null($vente->getTableauProduit())) {
  foreach ($vente->getTableauProduit() as $produit) {
    $pdf->SetFont('Times', '', 6);
    $pdf->SetX(00);
    $pdf->Row(array(
    iconv('UTF-8', 'ISO-8859-2', $produit["produit"]->getNomProduit()),
    $prixUnit = ($vente->getTypeVente() == "det") ? $produit["produit"]->getPrixVenteDetail() : $produit["produit"]->getPrixVenteEngros(),
    $produit["quantite"],
    $toto = $produit["quantite"] * $prixUnit,
  ));
  }
}

//Display table total row
$pdf->SetFont('times', 'B', 9);
$pdf->SetX(00);
$pdf->Cell(60, 6, iconv('UTF-8', 'ISO-8859-2', "Réduction accordée"), 1, 0, "R");
$pdf->Cell(20, 6, $vente->getReduction(), 1, 1, "R");

//Display table total row
$pdf->SetX(00);
$pdf->Cell(60, 6, "NET A PAYER", 1, 0, "R");
$pdf->Cell(20, 6, $vente->getTotal_a_payer(), 1, 1, "R");
$pdf->Ln(3);


//set footer position
$pdf->SetFont('Arial', '', 5);

//Display Footer Text
$pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-2', "Merci pour votre fidélité - a bientôt"), 0, 1, "C");


$pdf->Output(iconv('UTF-8', 'ISO-8859-2', 'votre reçu de vente.pdf'), 'I');
ob_end_flush();
