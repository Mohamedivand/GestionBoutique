function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }

  let idCollection=getUrlParams("idCollection")
  $(document).ready(function () {
    if(idCollection!=null){
        $.ajax({
            type: "POST",
            url: "../../api/getCollections.php",
            data: {
                token:"djessyaroma1234",
                idCollection:idCollection
            },
            dataType: "JSON",
            success: function (response) {
                $(".title").text("Modification collection");
                $("#nomCollection").val(response.nomCollection);
                $("#descCollection").val(response.descriptionCollection);
                $("#nomCollection").trigger('change')
                $("#descCollection").trigger('change')
            }
        });
    }
 });


function addCollection(token,nomCollection,descCollection=null){
    if(nomCollection == null || nomCollection == "" || nomCollection.trim().length === 0){
        alert("Verifier le nom de la collection");
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/editCollection.php",
            data: {
                token:token,
                nomCollection:nomCollection,
                descriptionCollection:descCollection
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
function updateCollection(token,idCollection,nomCollection,descCollection=null){
    if(nomCollection == null || nomCollection == "" || nomCollection.trim().length === 0){
        alert("Verifier le nom de la collection");
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/editCollection.php",
            data: {
                token:token,
                idCollection:idCollection,
                nomCollection:nomCollection,
                descriptionCollection:descCollection
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
let nomCollection=$("#nomCollection").val();
$("#nomCollection").change(function (e) { 
    e.preventDefault();
    nomCollection=$("#nomCollection").val();
});
let descCollection=$("#descCollection").val();
$("#descCollection").change(function (e) { 
    e.preventDefault();
    descCollection=$("#descCollection").val();
});


$("#btnEnvoyer").click(function (e) { 
    e.preventDefault();
    if (idCollection===null) {
        addCollection("djessyaroma1234",nomCollection,descCollection)
    } else {
        updateCollection("djessyaroma1234",idCollection,nomCollection,descCollection)
    }
    return false
});