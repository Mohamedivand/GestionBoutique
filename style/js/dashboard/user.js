selectOnNavbar("elUser");

function chargerFournisseur(){
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token: "djessyaroma1234",
            action : 2,
            idBoutique : 'djessy',

        },
        dataType: "JSON",
        success: function (response) {
            // on charge le nombre de fournisseur
            $(".nbrUser").text(response.sesfournisseur.length);
            desactiveLoader();

            for(let user_tmp of response.sesfournisseur){
                let component = `<tr data-idUser="${user_tmp.idUser}" >
                    <td>${(user_tmp.nomUser) ? user_tmp.nomUser : "--"}</td>
                    <td>${(user_tmp.prenomUser) ? user_tmp.prenomUser : "--"}</td>
                    <td>${(user_tmp.contact.tel) ? user_tmp.contact.tel : "--"}</td>
                    <td>${(user_tmp.role.nomRole) ? user_tmp.role.nomRole : "--"}</td>
                    <td>
                        <a onClick="redirecteUser(${user_tmp.idUser})">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                     <td>
                        <a class="deleteUser" id="${user_tmp.idUser}">
                            <i class="fa fa-trash actionDeleteMagasin"></i>
                        </a>
                    </td>
                </tr>`

                $(".userTable").append(component);
            }

            $(".deleteUser").click(function (e) { 
                e.preventDefault();
                let A=confirm("Voulez vous vraiment supprimer cet Utilisateur?")
                if(A){
                    let idUser=$($(this)).attr("id");
                    $.ajax({
                        type: "POST",
                        url: "../../api/deleteUser.php",
                        data: {
                            token:"djessyaroma1234",
                            idUser:idUser
                        },
                        success: function (response) {
                            alert("Suppression reussie avec succes")
                            $("tr[data-idUser="+idUser+"]").remove();
                        },
                        error: function(){
                            alert("une erreur c'est produite")
                        }
                    });
                }
                return false;
            });
        },
        error: function() {
            desactiveLoader()
        }
    });
}

function redirecteUser(idUser){
    window.location.href= `../form/user.php?idUser=${idUser}`
}
$(document).ready(function () {
    activeLoader
    chargerFournisseur()
});