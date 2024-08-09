function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }
  let idCarte=getUrlParams("idCarte")

  let date= $("#date").val();
$("#date").change(function (e) { 
    e.preventDefault();
    date= $("#date").val();
});

let nomEmployer= $("#nomEmployer").val();
$("#nomEmployer").change(function (e) { 
    e.preventDefault();
    nomEmployer= $("#nomEmployer").val();
});

let numEmployer= $("#numEmployer").val();
$("#numEmployer").change(function (e) { 
    e.preventDefault();
    numEmployer= $("#numEmployer").val();
});

let montantRetrait= $("#montantRetrait").val();
$("#montantRetrait").change(function (e) { 
    e.preventDefault();
    montantRetrait= $("#montantRetrait").val();
});
let montantDepot= $("#montantDepot").val();
$("#montantDepot").change(function (e) { 
    e.preventDefault();
    montantDepot= $("#montantDepot").val();
});

let motif= $("#motif").val();
$("#motif").change(function (e) { 
    e.preventDefault();
    motif= $("#motif").val();
});

$(document).ready(function () {
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "../../api/carteBancaire/getCarte.php",
            data: {
                token:"djessyaroma1234",
                idBoutique:"djessy",
                idCarte:idCarte
            },
            dataType: "JSON",
            success: function (response) {
                $("#numCarte").val(response.numeroCarte);
                $("#montantRetrait").attr("max", response.solde);
            }
        });
        $.ajax({
            type: "POST",
            url: "../../api/getBoutique.php",
            data: {
                token:"djessyaroma1234",
                idBoutique:"djessy",
                action:7
            },
            dataType: "JSON",
            success: function (response) {
                $("#montant").attr("max", response.solde);
            }
        });
    });

    $("#btnDepot").click(function (e) { 
        e.preventDefault();
        if(date==null || date=="" || date.trim().length==0 || nomEmployer==null || nomEmployer=="" || nomEmployer.trim().length==0 || numEmployer==null || numEmployer=="" || numEmployer.trim().length==0){
            alert("Tous les champs sont obligatoires")
        }
        else if(parseFloat(montantDepot)<=0 || montantDepot==null || montantDepot.trim().length==0){
            alert("veuillez saisir un montant valide")
        }else{
            $.ajax({
                type: "POST",
                url: "../../api/carteBancaire/depot.php",
                data: {
                    token:"djessyaroma1234",
                    date:date,
                    nomEmployer:nomEmployer,
                    numEmployer:numEmployer,
                    montant:montantDepot,
                    idCarte:idCarte
                },
                success: function () {
                    alert("depot reussi avec success")
                    window.location.href="../dashboard/banque.php"
                },
                error: function(){
                    alert("Une erreur c'est produite - veuillez reessayer")
                }
            });
        }
    });

    $("#btnRetrait").click(function (e) { 
        e.preventDefault();
        if(date==null || date=="" || date.trim().length==0 || nomEmployer==null || nomEmployer=="" || nomEmployer.trim().length==0 || numEmployer==null || numEmployer=="" || numEmployer.trim().length==0){
            alert("Tous les champs sont obligatoires")
        }
        else if(parseFloat(montantRetrait)<=0 || montantRetrait==null || montantRetrait.trim().length==0){
            alert("veuillez saisir un montant valide")
        }else{
            $.ajax({
                type: "POST",
                url: "../../api/carteBancaire/retrait.php",
                data: {
                    token:"djessyaroma1234",
                    date:date,
                    nomEmployer:nomEmployer,
                    numEmployer:numEmployer,
                    montant:montantRetrait,
                    idCarte:idCarte,
                    motif:motif
                },
                success: function () {
                    alert("retrait reussi avec success")
                    window.location.href="../dashboard/banque.php"
                },
                error: function(){
                    alert("Une erreur c'est produite - veuillez reessayer")
                }
            });
        }
    });

});
