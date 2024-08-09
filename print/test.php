<?php
require('fpdf/fpdf.php');

// Fonction pour générer le fichier PDF avec un tableau de produits
function genererPDF($listeProduits, $nomFichier) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // En-têtes du tableau
    $pdf->Cell(40, 10, 'Image', 1);
    $pdf->Cell(40, 10, 'Nom', 1);
    $pdf->Cell(40, 10, 'Prix', 1);
    $pdf->Ln(); // Aller à la ligne

    // Ajouter chaque produit au tableau
    foreach ($listeProduits as $produit) {
        // Ajouter l'image
        $pdf->Cell(40, 40, $pdf->Image($produit['image'], $pdf->GetX(), $pdf->GetY(), 40), 1);

        // Ajouter le nom du produit
        $pdf->Cell(40, 40, $produit['nom'], 1);

        // Ajouter le prix du produit
        $pdf->Cell(40, 40, $produit['prix'], 1);

        $pdf->Ln(); // Aller à la ligne
    }

    $pdf->Output($nomFichier, 'F');
}

// Exemple de liste de produits (remplacez par votre liste complète)
$listeProduitsComplets = [
    // ... (tous les produits)
];

// Diviser la liste en lots de 30 produits
$nombreProduitsParLot = 30;
$nombreTotalProduits = count($listeProduitsComplets);
$nombreLots = ceil($nombreTotalProduits / $nombreProduitsParLot);

// Générer un fichier PDF pour chaque lot
for ($i = 0; $i < $nombreLots; $i++) {
    $debutLot = $i * $nombreProduitsParLot;
    $finLot = min(($i + 1) * $nombreProduitsParLot, $nombreTotalProduits);
    
    $listeProduitsLot = array_slice($listeProduitsComplets, $debutLot, $finLot - $debutLot);
    
    // Nom du fichier avec le numéro du lot
    $nomFichier = 'listeProduits_lot' . ($i + 1) . '.pdf';

    // Appeler la fonction pour générer le PDF avec le lot de produits
    genererPDF($listeProduitsLot, $nomFichier);

    echo "Fichier PDF généré avec succès : $nomFichier <br>";
}

echo "Tous les fichiers PDF ont été générés avec succès.";
?>
