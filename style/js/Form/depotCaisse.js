let dateDepot= $("#dateDepot").val();
$("#dateDepot").change(function (e) { 
    e.preventDefault();
    dateDepot= $("#dateDepot").val();
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

let montant= $("#montant").val();
$("#montant").change(function (e) { 
    e.preventDefault();
    montant= $("#montant").val();
});

$("#btnEnvoyer").click(function (e) { 
    activeLoader()
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "../../api/caisse/depot.php",
        data: {
            token:"djessyaroma1234",
            date:dateDepot,
            nomEmployer:nomEmployer,
            numEmployer:numEmployer,
            montant:montant
        },
        success: function () {
            alert("Depot effectuer avec succes")
            window.location.href="../dashboard/caisse.php"
        },
        error: function(){
            alert("Une erreur c'est produite veuillez reesayer")
        }
    });
});