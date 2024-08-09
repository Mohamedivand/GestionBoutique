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
    <link rel="stylesheet" href="../lib/bootstrap-5.0.2/dist/css/bootstrap.min.css">
    <title>Recu de vente</title>


    <style>
        body{
            height: 100vh;
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
        .img{
            width: 800px;
            margin-bottom: 5px;
        }
        .total{
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="printTable" class="main">
        <h1 align="center" class="entete">
            
            <?php
            if(!is_null($boutique->getImageBanderole())){
                ?>
                <img src="<?php echo('../res/images/banderole/'.$boutique->getImageBanderole()); ?>" class="img" alt="">
                <?php
            }
            else{
                ?>
                Reçu de vente chez <?php echo($boutique->getNomBoutique()) ?>
                <?php
            }
            ?>
        </h1>
        <p align="right">
            Facture numéro <?php echo($vente->getId()) ?>
        </p>
        <h4 align="left">
            Nom Client: <?php echo($vente->getClient()->getNom()) ?>
        </h4>
        <h4 align="left">
            Numéro Client: <?php echo($vente->getClient()->getContact()->getTel()) ?>
        </h4>
        <div class="table-responsive table-data" >
            <table id="example" style="width:100%" class="table table-striped table-bordered infoZone">
              <thead>
                <tr>
                    <th class="titleTab2">Produit</th>
                    <th class="titleTab3">P.Unitaire</th>
                    <th class="titleTab4">Quantite</th>
                    <th class="titleTab5">Montant</th>
                </tr>
              </thead>
              <tbody class="venteTable">
              <?php
                        // $produits = new Produit($bdd, $t);
                        if(!is_null($vente->getTableauProduit())){
                            foreach ($vente->getTableauProduit() as $produit) {
                    ?>
                <tr>
                  <td><?php echo($produit['produit']->getNomProduit()) ?></td>
                  <td><?php echo($prixUnit = ($vente->getTypeVente()=="det") ? $produit['produit']->getPrixVenteDetail() : $produit['produit']->getPrixVenteEngros()) ?></td>
                  <td><?php echo($produit['quantite']) ?></td>
                  <td><?php echo($produit['quantite'] * $prixUnit) ?></td>
                </tr>
                <?php
                            }
                        }
                    ?>
                    <tr>
                        <td align="right" class="total">Nombre total de produit: </td>
                        <td colspan="3"><?php echo(is_array($vente->getTableauProduit()) ? sizeof($vente->getTableauProduit()) : "--") ?> FCFA</td>
                    </tr> 
                    <tr>
                        <td align="right" class="total">Reduction: </td>
                        <td colspan="3"><?php echo($vente->getReduction()) ?> FCFA</td>
                    </tr> 
                    <tr>
                        <td align="right" class="total">Montant total a payer (reduction inclue):  </td>
                        <td colspan="3"><?php echo($vente->getTotal_a_payer()) ?> FCFA</td>
                    </tr>
              </tbody>
            </table>
        </div>

        <div class="infoZone">
            <div class="client">
                <span>pour aquis</span>
            </div>
            <div class="fournisseur">
                <span>le fournisseur</span>
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
    <script src="html2pdf.min.js"></script>
    <script>
        // window.jsPDF = window.jspdf.jsPDF;
        // var docPDF = new jsPDF();
        // function print(){
        //     var elementHTML = document.querySelector("#printTable");
        //     docPDF.html(elementHTML, {
        //         callback: function(docPDF) {
        //             docPDF.save('Recu d achat.pdf');
        //         },
        //         x: 0,
        //         y: 0,
        //         width: 170,
        //         windowWidth: 650
        //     });
        // }
        // // $("#printButton").trigger("click");
        // // setTimeout(() => {
        // //     window.location.href="../pages/dashboard/vente.php";
        // // }, 2000);
        $("#printButton").click(function (e) { 
            e.preventDefault();
            var elementHTML=document.querySelector('body');
            var otp= {
                margin:0,
                filename:'myFile.pdf',
                image:{type:'jpeg',quality:0.98},
                html2canvas:{scale:2},
                jsPDF: {unit:'in',format:'letter',orientation:'portrait'}
            }
            html2pdf(elementHTML,otp)
        });
    </script>
    
</body>
</html>