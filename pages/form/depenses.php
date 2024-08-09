<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>depense</title>
    <link rel="stylesheet" href="../../style/css/style.css">
    <link rel="stylesheet" href="../../style/css/Form.css">
    <script src="../../lib/js/jquery-3.6.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="title">Nouvelle Depense</div>
        <div class="content">
            <form action="#">
                <div class="user-details">

                    <div class="input-box">
                        <span class="details">Details</span>
                        <input type="text" id="detail" >
                    </div>
                    <div class="input-box">
                        <span class="details">Montant</span>
                        <input type="num" placeholder="Entrer la somme" id="montant" required>
                    </div>
                    <div class="input-box">
                        <span class="details">date</span>
                        <input type="date" placeholder="Entrer la somme" id="date" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Provenance</span>
                        <div class="radio">
                        <div class="radioZone">
                            <label for="">benefice</label>
                            <input type="checkbox" class="inputRadio" value="1"  id="isBenefice" >
                        </div>
                        <div class="radioZone">
                            <label for="">Personnel</label>
                            <input type="checkbox" class="inputRadio" value="0" id="isNotBenefice" >
                        </div>
                        </div>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" value="Enregistrer" id="btnEnvoyer">
                    <input type="button" class="annuler" value="Retour" id="btnRetour">
                </div>
            </form>
        </div>
    </div>

    <script src="../../style/js/script.js"></script>
    <script src="../../style/js/Form/depense.js"></script>
</body>

</html>