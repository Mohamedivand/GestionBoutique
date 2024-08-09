function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
}


let idBoutique=getUrlParams("idBoutique");

$(document).ready(function () {
    if(idBoutique!=null){
        $(".title").text("Modification de la Boutique");
    }
});


function addBoutique(token,nom,tel,whatsapp=null,email=null,adresse=null,user){
    $.ajax({
        type: "POST",
        url: "../../api/addBoutique.php",
        data: {
            token:token,
            nomBoutique:nom,
            tel:tel,
            whatsapp:whatsapp,
            email:email,
            adresse:adresse,
            idUser:user
        },
        success: function () {
            alert("ajout reussi avec succes !")
            vider()
        },
        error:function (){
            alert("ajout non reussi!")
        }
    });
}

function updateBoutique(token,idBoutique,nom,tel,whatsapp=null,email=null,adresse=null,user){
    $.ajax({
        type: "POST",
        url: "../../api/addBoutique.php",
        data: {
            token:token,
            idBoutique:idBoutique,
            nomBoutique:nom,
            tel:tel,
            whatsapp:whatsapp,
            email:email,
            adresse:adresse,
            idUser:user
        },
        success: function () {
            alert("modification reussi avec succes !")
        },
        error:function (){
            alert("modification non reussi!")
        }
    });
}

let nomBoutique=$("#nomBoutique").val();
$("#nomBoutique").change(function (e) { 
    e.preventDefault();
    nomBoutique=$("#nomBoutique").val();
});
let telBoutique=$("#telBoutique").val();
$("#telBoutique").change(function (e) { 
    e.preventDefault();
    telBoutique=parseInt($("#telBoutique").val());
    if(telBoutique <50000000 || telBoutique >99999999){
        alert("Veuillez enregistrer un numero de telephone correcte");
        $("#telBoutique").css("color", "red");
    }
    else{
        $("#telBoutique").css("color", "initial");
    }
});
let whatsappBoutique=$("#whatsappBoutique").val();
$("#whatsappBoutique").change(function (e) { 
    e.preventDefault();
    whatsappBoutique=parseInt($("#whatsappBoutique").val());
    if(whatsappBoutique <50000000 || whatsappBoutique >99999999){
        alert("Veuillez enregistrer un numero de telephone correcte");
        $("#whatsappBoutique").css("color", "red");
    }
    else{
        $("#whatsappBoutique").css("color", "initial");
    }
});
let emailBoutique=$("#emailBoutique").val();
$("#emailBoutique").change(function (e) { 
    e.preventDefault();
    emailBoutique=$("#emailBoutique").val();
});
let adresseBoutique=$("#adresseBoutique").val();
$("#adresseBoutique").change(function (e) { 
    e.preventDefault();
    adresseBoutique=$("#adresseBoutique").val();
});
let proprietaire=$("#proprietaire").val();
$("#proprietaire").change(function (e) { 
    e.preventDefault();
    proprietaire=$("#proprietaire").val();
});


$("#btnEnvoyer").click(function (e) { 
    // e.preventDefault();
    if(idBoutique==null){
        addBoutique("djessyaroma1234",nomBoutique,telBoutique,whatsappBoutique,emailBoutique,adresseBoutique,proprietaire)
        window.location.href="../dashboard/boutique.php"
    }
    else{
        updateBoutique("djessyaroma1234",idBoutique,nomBoutique,telBoutique,whatsappBoutique,emailBoutique,adresseBoutique,proprietaire)
    }
    return false;
});

function vider(){
    $(nomBoutique).val("");
    $(telBoutique).val("");
    $(whatsappBoutique).val("");
    $(emailBoutique).val("");
    $(adresseBoutique).val("");
    $(proprietaire).val("");
}