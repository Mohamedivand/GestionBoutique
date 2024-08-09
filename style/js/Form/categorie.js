function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }

  let idCategorie=getUrlParams("idCategorie")

  $(document).ready(function () {
    if(idCategorie!=null){
        $.ajax({
            type: "POST",
            url: "../../api/getCategories.php",
            data: {
                token:"djessyaroma1234",
                idCategorie:idCategorie
            },
            dataType: "JSON",
            success: function (response) {
                $(".title").text("Modification Categorie");
                $("#nomCategorie").val(response.nomCategorie);
                $("#descCategorie").val(response.descriptionCategorie);
                $("#nomCategorie").trigger('change')
                $("#descCategorie").trigger('change')
            }
        });
    }
 });


function addCategorie(token,nomCategorie,descCategorie=null){
    if(nomCategorie == null || nomCategorie == "" || nomCategorie.trim().length === 0){
        alert("Verifier le nom de la categorie");
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/editCategorie.php",
            data: {
                token:token,
                nomCategorie:nomCategorie,
                descriptionCategorie:descCategorie
            },
            success: function (response) {
                alert("ajout reussi avec succes")
                resetForm()
    
            },
            error: function (response) {
                alert("ajout non reussi")
            },
        });
    }
}
function updateCategorie(token,idCategorie,nomCategorie,descCategorie=null){
    if(nomCategorie == null || nomCategorie == "" || nomCategorie.trim().length === 0){
        alert("Verifier le nom de la categorie");
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/editCategorie.php",
            data: {
                token:token,
                idCategorie:idCategorie,
                nomCategorie:nomCategorie,
                descriptionCategorie:descCategorie
            },
            success: function (response) {
                alert("modification reussi avec succes")
                resetForm()

            },
            error: function (response) {
                alert("modification non reussi")
            },
        });
    }
}
let nomCategorie=$("#nomCategorie").val();
$("#nomCategorie").change(function (e) { 
    e.preventDefault();
    nomCategorie=$("#nomCategorie").val();
});
let descCategorie=$("#descCategorie").val();
$("#descCategorie").change(function (e) { 
    e.preventDefault();
    descCategorie=$("#descCategorie").val();
});


$("#btnEnvoyer").click(function (e) { 
    e.preventDefault();
    if (idCategorie===null) {
        addCategorie("djessyaroma1234",nomCategorie,descCategorie)
    } else {
        updateCategorie("djessyaroma1234",idCategorie,nomCategorie,descCategorie)
    }
    return false
});