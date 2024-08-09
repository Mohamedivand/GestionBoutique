/**
 * on lance une requete Ajax vers l'api getUser pour recuperer les boutiques propre a l'utilisateur qui s'est connecter et dont on a le cookie,
 * on affiche ensuite les informations de ses boutiques dans un tableau en fonction du role de l'utilisateur
 * 
 * @returns {undefined}
 */

let finishAbonnement = [];
function chargerVew(){
    $.ajax({
        type: "POST",
        url: "../../api/getUsers.php",
        data: {
            token:'djessyaroma1234'
        },
        dataType: "JSON",
        success: function (response) {
            $(".nbrBoutique").text(response.sesBoutiques.length);
            desactiveLoader()
            if(response.role.nomRole == "proprietaire"){
                $(".titleTab5").text("statistiques");
                $(".titleTab6").remove();
                $(".btnZone").remove();
                for(let boutique_tmp of response.sesBoutiques){
                    let tableComponent=``
                    if (new Date().toLocaleDateString("fr-fr") >= new Date(boutique_tmp.finAbonnement).toLocaleDateString('fr-fr')) {
                         tableComponent=`<tr data-idBoutique="${boutique_tmp.idBoutique}">
                        <td onClick="creeBoutiqueCookie(${boutique_tmp.idBoutique})">
                            <a class="gererBtn">
                                Gerer <i class="fa-solid fa-hand-pointer"></i>
                            </a>
                        </td>
                        <td>${boutique_tmp.nomBoutique}</td>
                        <td>
                            ${new Date(boutique_tmp.debutAbonnement).toLocaleDateString('fr-fr') }
                        </td>
                        <td>
                            ${ new Date(boutique_tmp.finAbonnement).toLocaleDateString('fr-fr') }
                            <span style="color: red;font-weight: bold;"></span>
                        </td>
                        <td>
                            <a  class="btnModifier" href="../form/ajouter_image_boutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                <i class="fa fa-pen actionEditeMagasin"></i>
                            </a>
                        </td>
                        <td onClick="chargerCookieTmp(${boutique_tmp.idBoutique})"> 
                            <i class="fa fa-chart-line "></i>
                        </td>
                    </tr>`;
                    }else{
                         tableComponent=`<tr data-idBoutique="${boutique_tmp.idBoutique}">
                        <td onClick="creeBoutiqueCookie(${boutique_tmp.idBoutique})">
                            <a class="gererBtn">
                                Gerer <i class="fa-solid fa-hand-pointer"></i>
                            </a>
                        </td>
                        <td>${boutique_tmp.nomBoutique}</td>
                        <td>
                            ${new Date(boutique_tmp.debutAbonnement).toLocaleDateString('fr-fr') }
                        </td>
                        <td>
                            ${ new Date(boutique_tmp.finAbonnement).toLocaleDateString('fr-fr') }
                        </td>
                        <td>
                            <a  class="btnModifier" href="../form/ajouter_image_boutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                <i class="fa fa-pen actionEditeMagasin"></i>
                            </a>
                        </td>
                        <td onClick="chargerCookieTmp(${boutique_tmp.idBoutique})"> 
                            <i class="fa fa-chart-line "></i>
                        </td>
                    </tr>`;
                    }
    
                    $(".boutiqueTable").append(tableComponent);
                }
            }
            else if(response.role.nomRole == "admin"){
                for(let boutique_tmp of response.sesBoutiques){
                    let tableComponent =``
                    if (new Date().toLocaleDateString("fr-fr") >= new Date(boutique_tmp.finAbonnement).toLocaleDateString('fr-fr')) {
                         tableComponent=`<tr data-idBoutique="${boutique_tmp.idBoutique}" id="ligneBoutique${boutique_tmp.idBoutique}">
                        <td onClick="creeBoutiqueCookie(${boutique_tmp.idBoutique})">
                            <a class="gererBtn">
                                Gerer <i class="fa-solid fa-hand-pointer"></i>
                            </a>
                        </td>
                            <td>${boutique_tmp.nomBoutique}</td>
                            <td>
                            ${new Date(boutique_tmp.debutAbonnement).toLocaleDateString('fr-fr') }
                        </td>
                        <td>
                            ${ new Date(boutique_tmp.finAbonnement).toLocaleDateString('fr-fr') }
                            <span style="color: red;font-weight: bold;"></span>
                        </td>
                            <td>
                                <a  class="btnModifier" href="../form/boutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                    <i class="fa fa-pen actionEditeMagasin"></i>
                                </a>
                            </td>
                            <td>
                                <a  class="btnModifier" href="../form/ajouter_image_boutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                    <i class="fa fa-pen actionEditeMagasin"></i>
                                </a>
                            </td>
                            <td>
                                <a class="btnSupprimer" id="${boutique_tmp.idBoutique}" href="../form/supprimerBoutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                    <i class="fa fa-trash actionDeleteMagasin"></i>
                                </a>
                            </td>
                            <td class="abonnementZone">
                                <a  class="renouveler" href="../form/renouvelerAbonnement.php?idBoutique=${boutique_tmp.idBoutique}">
                                   Renouveler <i class="fa-solid fa-rotate-left"></i>
                                </a>
                                <a  class="resilier" onClick="resilierBoutique(${boutique_tmp.idBoutique})">
                                   Resilier <i class="fa-solid fa-xmark"></i>
                                </a>
                            </td>
                        </tr>`;
                    }else{
                         tableComponent=`<tr data-idBoutique="${boutique_tmp.idBoutique}" id="ligneBoutique${boutique_tmp.idBoutique}">
                        <td onClick="creeBoutiqueCookie(${boutique_tmp.idBoutique})">
                            <a class="gererBtn">
                                Gerer <i class="fa-solid fa-hand-pointer"></i>
                            </a>
                        </td>
                            <td>${boutique_tmp.nomBoutique}</td>
                            <td>
                            ${new Date(boutique_tmp.debutAbonnement).toLocaleDateString('fr-fr') }
                        </td>
                        <td>
                            ${ new Date(boutique_tmp.finAbonnement).toLocaleDateString('fr-fr') }
                        </td>
                            <td>
                                <a  class="btnModifier" href="../form/boutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                    <i class="fa fa-pen actionEditeMagasin"></i>
                                </a>
                            </td>
                            <td>
                                <a  class="btnModifier" href="../form/ajouter_image_boutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                    <i class="fa fa-pen actionEditeMagasin"></i>
                                </a>
                            </td>
                            <td>
                                <a class="btnSupprimer" id="${boutique_tmp.idBoutique}" href="../form/supprimerBoutique.php?idBoutique=${boutique_tmp.idBoutique}">
                                    <i class="fa fa-trash actionDeleteMagasin"></i>
                                </a>
                            </td>
                            <td class="abonnementZone">
                                <a  class="renouveler" href="../form/renouvelerAbonnement.php?idBoutique=${boutique_tmp.idBoutique}">
                                   Renouveler <i class="fa-solid fa-rotate-left"></i>
                                </a>
                                
                            </td>
                        </tr>`;
                    }
    
                    $(".boutiqueTable").append(tableComponent);
                }
            }
        },
        error: function(){
            desactiveLoader();
        }
    });

    
}

$(document).ready(function () {
    chargerVew();
    console.log(finishAbonnement);
    if(finishAbonnement.length > 0){
        $(".historique").removeClass("historiqueZoneInactive");
        for (const boutique of finishAbonnement) {
            let component =`<tr>
                                <td>
                                   ${boutique}
                                </td>
                            </tr>`
             $(".zoneResilier").append(component);        
        }
        $(".closeHisto").click(function (e) { 
            e.preventDefault();
            $(".historique").addClass("historiqueZoneInactive");
        });
    }
});

/**
 * elle creer le cookie idBoutique et charge les informations propre a cette boutique
 * @param {number} idBoutique
 * @returns {undefined}
 */
function creeBoutiqueCookie(idBoutique){
    $.ajax({
        type: "POST",
        url: "../../api/creeBoutiqueCookie.php",
        data: {
            token:'djessyaroma1234',
            idBoutique: idBoutique
        },
        success: function () {
            window.location.href= "vente.php"
        }
    });
}

function chargerCookieTmp(id){
    $.ajax({
        type: "POST",
        url: "../../api/creeBoutiqueCookie.php",
        data: {
            token:'djessyaroma1234',
            idBoutique: id
        },
        success: function () {
            window.location.href= "statistique.php"
        }
    });
}

function resilierBoutique(idBoutique){
    let mdp="";
    if (mdp=prompt("Entrez votre mot de passe pour pouvoir continuer : ")) {
        $.ajax({
            type: "POST",
            url: "../../api/auth/checkMdp.php",
            data: {
                token: "djessyaroma1234",
                mdp: mdp,
            },
            success: function () {
                $.ajax({
                    type: "POST",
                    url: "../../api/resilierBoutique.php",
                    data: {
                        token : "djessyaroma1234",
                        idBoutique : idBoutique
                    },
                    success: function (response) {
                        alert("Suppression reussie avec succes");
                        $("tr[data-idBoutique=" + idBoutique + "]").remove();
                    },
                    error: function(){
                        alert("Suppression non reussi");
                    }
                });
            },
            error : function(){
                alert("Mot de passe incorect");
            }
        });
    }
}
