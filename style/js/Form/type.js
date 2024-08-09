function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }

  let idType= getUrlParams("idType")

 $(document).ready(function () {
    if(idType!=null){
        $.ajax({
            type: "POST",
            url: "../../api/getTypes.php",
            data: {
                token:"djessyaroma1234",
                idType:idType
            },
            dataType: "JSON",
            success: function (response) {
                $(".title").text("Modification Type");
                $("#nomType").val(response.nomType);
                $("#descType").val(response.descriptionType);
                $("#nomType").trigger('change')
                $("#descType").trigger('change')
            }
        });
    }
 });

function addType(token,nomType,descType=null){
    if(nomType == null || nomType == "" || nomType.trim().length === 0){
        alert("Verifier le nom de la categorie");
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/editType.php",
            data: {
                token:token,
                nomType:nomType,
                descriptionType:descType
            },
            success: function (response) {
                alert("ajout reussis avec succes")
                // resetForm()
            },
            error: function (response) {
                alert("ajout non reussi")
            }
        });
    }
    
}
function updateType(token,idType,nomType,descType=null){
    if(nomType == null || nomType=="" || nomType.trim().length === 0){
        alert("Verifier le nom de la categorie");
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/editType.php",
            data: {
                token:token,
                idType:idType,
                nomType:nomType,
                descriptionType:descType
            },
            success: function (response) {
                alert("modification reussis avec succes")
                resetForm()
    
            },
            error: function (response) {
                alert("modification non reussi")
            }
        });
    }
}

let nomType=$("#nomType").val();
$("#nomType").change(function (e) { 
    nomType=$("#nomType").val();
});

let descType=$("#descType").val();
$("#descType").change(function (e) { 
    descType=$("#descType").val();
});

$("#btnEnvoyer").click(function (e) {
    if (idType==null) {
        addType("djessyaroma1234",nomType,descType)
    } 
    else {
        updateType("djessyaroma1234",idType,nomType,descType)
    }
    return false;
});