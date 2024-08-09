function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }

  let idUser=getUrlParams("idUser")


$(document).ready(function () {

    if(idUser!=null){
        $("#btnEnvoyer").val("Modifier");
        $(".title").text("modification Utilisateur");
    }
});



function addUser(token,nomUser,prenomUser,tel,email=null,adresse=null,whatsApp=null,role){
    alert(nomUser)
    if(nomUser==null || nomUser=="" || nomUser.trim().length==0){
        alert("le champs nom n'est pas valide")
    }
    else{
        $.ajax({
            type: "POST",
            url: "../../api/addUser.php",
            data: {
                token:token,
                nomUser:nomUser,
                prenomUser:prenomUser,
                login:"fournisseur5431",
                mdp:"fournisseur5431",
                tel:tel,
                email:email,
                adresse:adresse,
                whatsapp:whatsApp,
                idRole:role
            },
            success: function () {
                alert("inscription reussie avec succes")
                resetForm()
            },
            error: function () {
                alert("inscription non reussie")
            },
        
        });
    }
}

function updateUser(token,idUser,nomUser,prenomUser,tel,email=null,adresse=null,whatsApp=null,role){
$.ajax({
    type: "POST",
    url: "../../api/addUser.php",
    data: {
        token:token,
        idUser:idUser,
        nomUser:nomUser,
        prenomUser:prenomUser,
        login:"fournisseur5431",
        mdp:"fournisseur5431",
        tel:tel,
        email:email,
        adresse:adresse,
        whatsApp:whatsApp,
        idRole:role
    },
    success: function () {
        alert("modification reusssi avec succes")
        resetForm()
    },
    error: function () {
        alert("modification non reussi ")
    },

});
}

let nomUser=$("#nomUser").val();
$("#nomUser").change(function (e) { 
    e.preventDefault();
    nomUser=$("#nomUser").val();
});
let prenomUser=$("#prenomUser").val();
$("#prenomUser").change(function (e) { 
    e.preventDefault();
    prenomUser=$("#prenomUser").val();
});

let tel=$("#tel").val();
$("#tel").change(function (e) { 
    e.preventDefault();
    tel=$("#tel").val();
});
let email=$("#email").val();
$("#email").change(function (e) { 
    e.preventDefault();
    email=$("#email").val();
});
let adresse=$("#adresse").val();
$("#adresse").change(function (e) { 
    e.preventDefault();
    adresse=$("#adresse").val();
});
let whatsapp=$("#whatsapp").val();
$("#whatsapp").change(function (e) { 
    e.preventDefault();
    whatsapp=$("#whatsapp").val();
});
let role=$("#role").val();
$("#role").change(function (e) { 
    e.preventDefault();
    role=$(this).children("option:selected").val();
});


$("#btnEnvoyer").click(function (e) { 
    e.preventDefault();
    if(idUser===null){
        addUser("djessyaroma1234",nomUser,prenomUser,tel,email,adresse,whatsapp,role)
    }else{
        updateUser("djessyaroma1234",idUser,nomUser,prenomUser,tel,email,adresse,whatsapp,role)
    }
});