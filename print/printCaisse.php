<?php
ob_start();
require_once("../script/connexion_bd.php");
require_once("../class/Boutique.php");
require_once("../class/Caisse.php");
require_once("../class/User.php");

if (!isset($_COOKIE['idUser'])) {
    header("location:../../index.php");
    exit();
}
$idBoutique = $_COOKIE['idBoutique'];
$boutique = new Boutique($bdd, $idBoutique);

$userTmp = new User($bdd, $_COOKIE['idUser']);
if (!$userTmp->getExiste()) {
    header("location:../../index.php");
    exit();
}
if (!in_array($userTmp->getRole()->getRole(), array("admin", "proprietaire"))) {
    header("location:../../index.php");
    exit();
}

// Appel de la librairie FPDF
require_once("../lib/php/fpdf/fpdf.php");

class PDF extends FPDF {
    public $boutique;
    public function initBoutique($boutique_Tmp)
    {
        $this->boutique=$boutique_Tmp;
    }
	function Header(){
		// Titre gras (B) police Helbetica de 11
		$this->SetFont('Helvetica','B',11);
		// fond de couleur gris (valeurs en RGB)
		$this->setFillColor(230,230,230);
		$this->SetX(70);
		// Texte : 60 >largeur ligne, 8 >hauteur ligne. Premier 0 >pas de bordure, 1 >retour à la ligneensuite, C >centrer texte, 1> couleur de fond ok	
		$this->Cell(60,8,iconv('UTF-8', 'ISO-8859-1','Liste de vos trasaction de chez '.$this->boutique->getNomBoutique()),0,1,'C',0);
		// Saut de ligne 10 mm
		$this->Ln(10);		
		
		$this->SetFont('Helvetica','B',11);
		
		$this->SetDrawColor(183);
    $this->SetFillColor(221);
    $this->SetX(5);
		$this->Cell(50,15,'Date-Transaction',1,0,'C',true,);
		$this->Cell(50,15,iconv('UTF-8', 'ISO-8859-1','Employer'),1,0,'C',true,);
		$this->Cell(50,15,'Montant',1,0,'C',true,);
		$this->Cell(50,15,'Type Transaction',1,1,'C',true,);
		
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

$pdf = new PDF('P','mm','A4'); 
$pdf->initBoutique($boutique);
//define new alias for total page numbers
$pdf->AliasNbPages('{pages}');

$pdf->SetAutoPageBreak(true,15);
$pdf->AddPage();

$pdf->SetFont('Helvetica','B',9);
$pdf->SetDrawColor(183);
$pdf->SetFillColor(221); 
$pdf->SetTextColor(0);


if(!is_null($boutique->getCaisse())){
  $boutique->getCaisse()->chargerTransactions();
  if(!is_null($boutique->getCaisse()->getTableauTransaction())){
        foreach ($boutique->getCaisse()->getTableauTransaction() as $transaction) {
    $pdf->SetFont('Times','',11);
    $pdf->SetX(5);
    $pdf->Cell(50,15,$transaction["dateTransaction"],1,0,"C");
    $pdf->Cell(50,15,iconv('UTF-8', 'ISO-8859-1',$transaction["nomEmployer"])." - ".iconv('UTF-8', 'ISO-8859-1',$transaction["numEmployer"]),1,0,"C");
    $pdf->Cell(50,15,$transaction["montant"],1,0,"C");
    $pdf->Cell(50,15,iconv('UTF-8', 'ISO-8859-1',$transaction["type"]=="1" ? "Retrait" : "Dépôt" ),1,1,"C");
  }
  
}
}


$pdf->Output('liste de vos transactions.pdf','I');

ob_end_flush();
?>

