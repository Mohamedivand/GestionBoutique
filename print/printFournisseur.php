<?php
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/User.php");


$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

// Appel de la librairie FPDF
require_once("../lib/php/fpdf/fpdf.php");

class PDF extends FPDF {
    public $boutique;
    public function initBoutique($boutique_Tmp)
    {
        $this->boutique=$boutique_Tmp;
    }
	function Header(){
		$this->SetFont('Helvetica','B',15);
		// Titre gras (B) police Helbetica de 11
		$this->SetFont('Helvetica','B',11);
		// fond de couleur gris (valeurs en RGB)
		$this->setFillColor(230,230,230);
 		// position du coin supérieur gauche par rapport à la marge gauche (mm)
		$this->SetX(70);
		// Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok	
		$this->Cell(60,8,iconv('UTF-8', 'ISO-8859-1','Liste des fournisseurs chez '.$this->boutique->getNomBoutique()),0,1,'C',0);
		// Saut de ligne 10 mm
		$this->Ln(10);		
		
		$this->SetFont('Helvetica','B',11);
		
		$this->SetDrawColor(183);
        $this->SetFillColor(221);
		$this->Cell(70,5,'Nom',1,0,'',true);
		$this->Cell(50,5,iconv('UTF-8', 'ISO-8859-1','Prénom'),1,0,'',true);
		$this->Cell(30,5,iconv('UTF-8', 'ISO-8859-1','Téléphone'),1,0,'',true);
		$this->Cell(40,5,'Role',1,1,'',true);
		
	}
	function Footer(){
		//add table's bottom line
		$this->Cell(190,0,'','T',1,'',true);
		
		//Go to 1.5 cm from bottom
		$this->SetY(-15);
				
		$this->SetFont('Helvetica','B',8);
		
		//width = 0 means the cell is extended up to the right margin
		$this->Cell(0,10,'Page '.$this->PageNo()." / {pages}",0,0,'C');
	}
}

$pdf = new PDF('P','mm','A4'); //use new class
$pdf->initBoutique($boutique);

//define new alias for total page numbers
$pdf->AliasNbPages('{pages}');

$pdf->SetAutoPageBreak(true,15);
$pdf->AddPage();

$pdf->SetFont('Helvetica','B',9);
$pdf->SetDrawColor(183);
$pdf->SetFillColor(221); 
$pdf->SetTextColor(0);

if(!is_null($boutique->chargerFournisseur())){
    foreach ($boutique->getTableauFournisseur() as $user) {
            $pdf->Cell(70,5,iconv('UTF-8', 'ISO-8859-1',($user["nomUser"])),1,0);
            $pdf->Cell(50,5,iconv('UTF-8', 'ISO-8859-1',($user["prenomUser"])),1,0);
            $existe_tmp = false;
            if(isset($user)){
                if(isset($user["contact"])){
                    if(isset($user["contact"]['tel'])){
                        $existe_tmp = true;
                    }
                }
            }
            $pdf->Cell(30,5,(($existe_tmp) ? $user["contact"]['tel'] : "--"),1,0);
            $existe_tmp = false;
            if(isset($user)){
                if(isset($user["role"])){
                    if(isset($user["role"]['nomRole'])){
                        $existe_tmp = true;
                    }
                }
            }
            $pdf->Cell(40,5,(($existe_tmp) ? $user["role"]['nomRole'] : "--"),1,1);
    }
}


$pdf->Output('Liste de vos fournisseurs.pdf','I');

ob_end_flush();
?>