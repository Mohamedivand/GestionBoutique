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



function addUser(token,nomUser,prenomUser,login,mdp,tel=null,email=null,adresse=null,whatsApp=null,role){
$.ajax({
    type: "POST",
    url: "../../api/addUser.php",
    data: {
        token:token,
        nomUser:nomUser,
        prenomUser:prenomUser,
        login:login,
        mdp:mdp,
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

function updateUser(token,idUser,nomUser,prenomUser,login,mdp,tel=null,email=null,adresse=null,whatsApp=null,role){
$.ajax({
    type: "POST",
    url: "../../api/addUser.php",
    data: {
        token:token,
        idUser:idUser,
        nomUser:nomUser,
        prenomUser:prenomUser,
        login:login,
        mdp:mdp,
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
let login=$("#login").val();
$("#login").change(function (e) { 
    e.preventDefault();
    login=$("#login").val();
});
let mdp=$("#mdp").val();
$("#mdp").change(function (e) { 
    e.preventDefault();
    mdp=$("#mdp").val();
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
        addUser("djessyaroma1234",nomUser,prenomUser,login,mdp,tel,email,adresse,whatsapp,role)
    }else{
        updateUser("djessyaroma1234",idUser,nomUser,prenomUser,login,mdp,tel,email,adresse,whatsapp,role)
    }
});