<?php
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Produit.php");
require_once("../class/Type.php");
require_once("../class/Categorie.php");
require_once("../class/Collection.php");
require_once("../class/Marque.php");
require_once("../class/User.php");
include("essai.php");
try{
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

// Appel de la librairie FPDF
require_once("../lib/php/fpdf/fpdf.php");

$pdf = new PDF_MC_Table();
$pdf->SetAutoPageBreak(true,145);
$pdf->AddPage();
$pdf->SetFont("Times", '', 14);

$pdf->SetFont('Helvetica', 'B', 15);
// Titre gras (B) police Helbetica de 11
$pdf->SetFont('Helvetica', 'B', 11);
// fond de couleur gris (valeurs en RGB)
$pdf->setFillColor(230, 230, 230);
// position du coin supérieur gauche par rapport à la marge gauche (mm)
$pdf->SetX(70);
// Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok	
$pdf->Cell(60, 8, iconv('UTF-8', 'ISO-8859-1', 'Liste des produits chez ' . $boutique->getNomBoutique()), 0, 1, 'C', 0);
// Saut de ligne 10 mm
$pdf->Ln(10);
// set with of table
$pdf->SetWidths(array(50, 50, 10, 20, 20, 20, 29));

// set alignement of table
$pdf->SetAligns(array('C', '', 'C', 'C', 'R'));

// taille of row
$pdf->SetLineHeight(30);

$pdf->SetFont('Helvetica', 'B', 11);

$pdf->SetDrawColor(183);
$pdf->SetFillColor(221);
$pdf->Cell(50, 10, 'Image', 1, 0, '', true);
$pdf->Cell(50, 10, 'Produit', 1, 0, '', true);
$pdf->Cell(10, 10, 'Qte', 1, 0, '', true);
$pdf->Cell(20, 10, 'P.Achats', 1, 0, '', true);
$pdf->Cell(20, 10, 'P.Details', 1, 0, '', true);
$pdf->Cell(20, 10, 'P.Engros', 1, 0, '', true);
$pdf->Cell(29, 10, 'Fournisseur', 1, 1, '', true);

 

$pdf->SetFont('Helvetica', 'B', 9);
$pdf->SetDrawColor(183);
$pdf->SetFillColor(221);
$pdf->SetTextColor(0);

if (!is_null($boutique->chargerProduit())) {
	foreach ($boutique->getTableauProduit() as $produit) { 


		$extension = pathinfo($produit['imageProduit'], PATHINFO_EXTENSION);

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
		if (isset($produit["fournisseur"])) {
			if (isset($produit["fournisseur"]["contact"])) {
				if (isset($produit["fournisseur"]["contact"]['tel'])) {
					$existe_tmp = true;
				}
			}
		}

		$pdf->Row(
			array(
				$image_tmp ? $pdf->Image($produit['imageProduit'], $pdf->GetX(), $pdf->GetY(), 48, 25) : "--",
				// $image_tmp ? "--" : "--",
				$produit['nomProduit'],
				$produit["quantiteProduit"],
				$produit["prixAchat"],
				$produit['prixVenteDetail'],
				$produit['prixVenteEngros'],
				(($existe_tmp) ? $produit["fournisseur"]["contact"]['tel'] : "--"),
			)
		);
	}
} else {
	echo (1);
	exit();
}

/*if($pdf->GetStringWidth($produit['nomProduit']) > 85){
		  $pdf->SetFont('times','',6);
		  $pdf->Cell(90,10,$produit['nomProduit'],1,0);
		  $pdf->SetFont('times','',7);
	  }else{
		  $pdf->Cell(90,10,iconv('UTF-8', 'ISO-8859-1',($produit['nomProduit'])),1,0);
	  }
		  $pdf->SetFont('Times','',11);
		  $pdf->Cell(10,10,($produit["quantiteProduit"]),1,0);
		  $pdf->Cell(20,10,($produit["prixAchat"]),1,0);
		  $pdf->Cell(20,10,($produit['prixVenteDetail']),1,0);
		  $pdf->Cell(20,10,($produit['prixVenteEngros']),1,0);
		  $existe_tmp = false;
		  if(isset($produit["fournisseur"])){
			  if(isset($produit["fournisseur"]["contact"])){
				  if(isset($produit["fournisseur"]["contact"]['tel'])){
					  $existe_tmp = true;
				  }
			  }
  }
		  $pdf->Cell(30,10,(($existe_tmp) ? $produit["fournisseur"]["contact"]['tel'] : "--"),1,1);
	  
  }
}**/


$pdf->Output('liste de vos produits.pdf', 'I');

ob_end_flush();
} catch (Exception $th) {
	echo ($th->getMessage());
  }
?>