<?php
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/User.php");
include("essai.php");


$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

// Appel de la librairie FPDF
require_once("../lib/php/fpdf/fpdf.php");
$pdf = new PDF_MC_Table();

$pdf->AddPage();


if (!is_null($boutique->getImageBanderole())) {

	$pdf->Cell(10);

	//put logo
	$pdf->Image('../res/images/banderole/' . $boutique->getImageBanderole(), 00, 00, 210, 50);
} else {
	//Display Company Info
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->Cell(50, 10, iconv('UTF-8', 'ISO-8859-1', $boutique->getNomBoutique()), 0, 1);
	$pdf->SetFont('Arial', '', 14);
	$pdf->Cell(50, 7, iconv('UTF-8', 'ISO-8859-1', "Adresse: " . (($boutique->getContact()->getAdresse()) ?: "--") . ","), 0, 1);
	$pdf->Cell(50, 7, iconv('UTF-8', 'ISO-8859-1', "Téléphone: " . (($boutique->getContact()->getTel()) ?: "--")), 0, 1);
	$pdf->Cell(50, 7, "Email: " . (($boutique->getContact()->getEmail()) ?: "--"), 0, 1);
}

$pdf->SetFont('Helvetica', 'B', 15);
// Titre gras (B) police Helbetica de 11
$pdf->SetFont('Helvetica', 'B', 14);
// fond de couleur gris (valeurs en RGB)
$pdf->setFillColor(230, 230, 230);
// position du coin supérieur gauche par rapport à la marge gauche (mm)
$pdf->SetY(55);
$pdf->SetX(70);
// Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok	
$pdf->Cell(60, 8, iconv('UTF-8', 'ISO-8859-1', 'Liste de vos depenses'), 0, 1, 'C', 0);
$pdf->ln(10);


// set with of table
$pdf->SetWidths(array(60, 60, 70));

// et alignement of table
$pdf->SetAligns(Array('','R','R'));

// taille of row
$pdf->SetLineHeight(6);

$pdf->SetFont('Helvetica', 'B', 11);
$pdf->SetDrawColor(183);
$pdf->SetFillColor(221);
$pdf->Cell(60, 10, 'Detail', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Montant', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Date', 1, 1, 'C', true);

$pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetDrawColor(183);
$pdf->SetFillColor(221);
$pdf->SetTextColor(0);

if (!is_null($boutique->chargerDepense())) {
	foreach ($boutique->getTableauDepense() as $depense) {
		$pdf->Row(array(
			iconv('UTF-8', 'ISO-8859-1', ($depense["detail"])),
			$depense["montant"],
			iconv('UTF-8', 'ISO-8859-1', ($depense["dateDepense"])),
		));
	}
}

//add table's bottom line
$pdf->Cell(190, 0, '', 'T', 1, '', true);

//Go to 1.5 cm from bottom
$pdf->SetY(-31);

$pdf->SetFont('Helvetica', 'B', 8);

//width = 0 means the cell is extended up to the right margin
$pdf->Cell(0, 10, 'Page ' . $pdf->PageNo() . " / {pages}", 0, 0, 'C');




//define new alias for total page numbers
$pdf->AliasNbPages('{pages}');

$pdf->SetAutoPageBreak(true, 15);




$pdf->Output('liste de vos dépenses.pdf', 'I');

ob_end_flush();
