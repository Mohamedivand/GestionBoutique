<?php
    ob_start();
    require_once("../script/connexion_bd.php");
    require_once("../class/Boutique.php");
    require_once("../class/Vente.php");

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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../lib/js/jquery-3.6.1.min.js"></script>
    <script src="../lib/js/jspdf/jspdf.umd.min.js"></script>
    <script src="../lib/js/jspdf/html2canvas.min.js"></script>
    <link rel="stylesheet" href="../lib/css/fontawesome/css/all.css">
    <title>Recu de vente</title>


    <style>
        body{
            width: 100vw;
            height: 100vh;
        }
        body{
            font-family: arial;
        }
        a{
            text-decoration: none;
            font-weight: bold;
        }
        .contactZone{
            display: flex; 
            align-items: center;
            gap: 30px; 
            background: black; 
            color: white; 
            padding: 10px; 
            border-radius: 10px;
        }
        .contactZone a{
            color: white;
        }
        table{
            width: 80%; 
            min-width: 400px; 
            font-size: 10px; 
            border-collapse: collapse; 
            text-align: center; 
            border: 1px solid; 
            border-radius: 15px 15px 0 0; 
            overflow: hidden; 
            padding: 20px;
        }
        thead{
            background: black; 
            color: white; 
            padding: 5px; 
            height: 30px;
        }
        tbody tr{
            border-bottom: 1px solid black; 
        }
        tbody tr:nth-child(even){
            background: rgb(172, 172, 253);
        }

        .signature{
            margin-top: 50px;
            margin-right: 50px;
        }

        .downloadZone{
            margin-top: 50px;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .downloadZone button{
            padding: 5px;
            font-size: 20px;
            font-weight: bold;
            background: black;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50px;
            color: white;
            border: none;
            gap: 10px;
            padding-right: 50px;
            cursor: pointer;
            transition: all ease-in .1s;

        }
        .downloadZone button:hover{
            transform: translateY(-10px);
            box-shadow: 0px 5px 10px grey;
            background: white;
            color: black;
            border: 5px solid black;
        }
        .downloadZone button:hover .downloadIcone{
            background: black;
            color: white;
        }
        .downloadIcone{
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
            background: white;
            border-radius: 50%;
        }
        .infoZone{
            padding: 100px 10%;
        }
    </style>
</head>
<body>
    <div id="printTable" class="main">
        <h1 align="center">
            Reçu de vente chez <?php echo($boutique->getNomBoutique()) ?>
        </h1>
        <p align="right">
            Achat numero <?php echo($vente->getId()) ?>
        </p>
        <h4 align="left">
            Nom Client: <?php echo($vente->getClient()->getNom()) ?>
        </h4>
        <h4 align="left">
            Numéro Client: <?php echo($vente->getClient()->getContact()->getTel()) ?>
        </h4>
        <div style="display: flex; justify-content: center;">
        <table>
            <?php
                        // $produits = new Produit($bdd, $t);
                        if(!is_null($vente->getTableauProduit())){
                            foreach ($vente->getTableauProduit() as $produit) {
                    ?>
                <tr>
                    
                    <td><?php echo($produit['produit']->getNomProduit()) ?></td>
                 </tr>
                 <tr>
                    <td><?php echo($prixUnit = ($vente->getTypeVente()=="det") ? $produit['produit']->getPrixVenteDetail() : $produit['produit']->getPrixVenteEngros()) ?> FCFA</td>
                </tr>
                <tr>
                    <td><?php echo($produit['quantite']) ?></td>
                </tr>
                    <?php
                            }
                        }
                    ?>
            </table>
        </div>

        <div class="infoZone">
            <b>Nombre total de produit: </b>
            <span>
                <?php echo(is_array($vente->getTableauProduit()) ? sizeof($vente->getTableauProduit()) : "--") ?> 
            </span>
            <hr>
            <b>Reduction: </b>
            <span>
                <?php echo($vente->getReduction()) ?> FCFA
            </span>
            <hr>

            <b>Montant total a payer (reduction inclue): </b>
            <span>
                <?php echo($vente->getTotal_a_payer()) ?> FCFA
            </span>
            <hr>
        </div>

        <div class="signature" align="right">
            <h5>Achat effectuee le: <?php echo($vente->getDateVente()) ?></h5>
            <h3>Signature:</h3>
        </div>
    </div>

    <div class="downloadZone">
        <button id="printButton" onclick="print()">
            <span class="downloadIcone">
                <i class="fa fa-download"></i>
            </span>
            Print
        </button>
    </div>

    <script>
        window.jsPDF = window.jspdf.jsPDF;
        var docPDF = new jsPDF();
        function print(){
            var elementHTML = document.querySelector("#printTable");
            docPDF.html(elementHTML, {
                callback: function(docPDF) {
                    docPDF.save('Recu d achat.pdf');
                },
                x: 15,
                y: 15,
                width: 170,
                windowWidth: 650
            });
        }
        $("#printButton").trigger("click");
    </script>
    
</body>
</html>