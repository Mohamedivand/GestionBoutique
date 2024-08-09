function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }

  let idBoutique=getUrlParams("idBoutique")

function deleteBoutique(token,loginProprietaire,loginAdmin,mdpAdmin){
    $.ajax({
        type: "POST",
        url: "../../api/deleteBoutique.php",
        data: {
            token:token,
            idBoutique:idBoutique,
            loginUser:loginProprietaire,
            loginAdmin:loginAdmin,
            mdpAdmin:mdpAdmin
        },
        success: function (response) {
            alert("Suppression reussie")
            window.location.href="../dashboard/boutique.php"
        },
        error: function (response){
            alert("Suppression non reussi")
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

$("#btnEnvoyer").click(function (e) { 
    e.preventDefault();
    deleteBoutique("djessyaroma1234",loginProprietaire,loginAdmin,mdpAdmin)
});