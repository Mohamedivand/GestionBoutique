function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
}


let idBoutique=getUrlParams("idBoutique");

function renouvellement(token,nomBoutique,loginProprietaire,loginAdmin,mdpAdmin,nbreMois){
    $.ajax({
        type: "POST",
        url: "../../api/renouveller_abonnement.php",
        data: {
            token:token,
            nomBoutique:nomBoutique,
            loginProprietaire:loginProprietaire,
            loginAdmin:loginAdmin,
            mdpAdmin:mdpAdmin,
            nomMois:nbreMois,
            idBoutique:idBoutique
        },
        success: function (response) {
            alert("modification  reussi")
            // resetForm()
        },
        error: function (){
            alert("modification non reussi")
        }
    });
}


let nomBoutique=$("#nomBoutique").val();
$("#nomBoutique").change(function (e) { 
    e.preventDefault();
    nomBoutique=$("#nomBoutique").val();
});

let loginProprietaire=$("#loginProprietaire").val();
$("#loginProprietaire").change(function (e) { 
    e.preventDefault();
    loginProprietaire=$("#loginProprietaire").val();
});

let loginAdmin=$("#loginAdmin").val();
$("#loginAdmin").change(function (e) { 
    e.preventDefault();
    loginAdmin=$("#loginAdmin").val();
});

let mdpAdmin=$("#mdpAdmin").val();
$("#mdpAdmin").change(function (e) { 
    e.preventDefault();
    mdpAdmin=$("#mdpAdmin").val();
});

let nbreMois=$("#nbreMois").val();
$("#nbreMois").change(function (e) { 
    e.preventDefault();
    nbreMois=$("#nbreMois").val();
});

$("#btnEnvoyer").click(function (e) { 
    e.preventDefault();
    renouvellement("djessyaroma1234",nomBoutique,loginProprietaire,loginAdmin,mdpAdmin,nbreMois);
});