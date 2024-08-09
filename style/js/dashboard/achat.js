selectOnNavbar("elAchat");
desactiveLoader()
$(document).ready(function () {
    activeLoader()
    searchAchat()
});
function createAchat(idAchat,date,totalQte){
    let component=`<tr data-idAchat="`+idAchat+`" >
                   <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
                  <td>`+totalQte+`</td>
                  <td>Entrepot</td>
                  <td onClick="getInfoAchat(`+idAchat+`)" class="voirCommande" ><i class="fa-solid fa-eye"></i></td>
                  <td onClick="validerAchat(`+idAchat+`)" class="validerCommande" ><button class="btnValider">valider</button></td>
                  <td > <i class="fa fa-pen" onClick="updateAchat(`+idAchat+`)"></i> <i class="fa fa-trash" onClick="deleteAchat(`+idAchat+`)"></i> <i class="fa-solid fa-print" onClick="printBonAchat(`+idAchat+`)"></i></td>
                </tr>`
            
        $(".achatTable").append(component);
}

function createAchatValider(idAchat,date,totalQte){
    let component=`<tr data-idAchat="`+idAchat+`" >
                   <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
                  <td>`+totalQte+`</td>
                  <td>Entrepot</td>
                  <td onClick="getInfoAchat(`+idAchat+`)" class="voirCommande" ><i class="fa-solid fa-eye"></i></td>
                  <td class="validerCommande" style="color: green;align:center;">Deja Valider</td>
                  <td > <i class="fa-solid fa-print" onClick="printBonAchat(`+idAchat+`)"></i></td>
                </tr>`
            
        $(".achatTable").append(component);
}

function searchAchat() {
    let total=0;
    let totalQte=0;
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            action:9
        },
        dataType: "JSON",
        success: function (response) {
            if(response != null || response != undefined){
                for (const achat of response.sesAchats) {
                    let qte=0
                    if (achat.lesproduit != undefined) {
                        for (const produit of achat.lesproduit) {
                            qte+=parseInt(produit.quantiteDemander)
                            totalQte+=qte
                        } 
                    }
                    if(achat.statut == 1){
                        createAchatValider(achat.idAchat,achat.dateAchat,qte,achat.fournisseur,achat.statutAchat)
                        total++;
                    }else{
                        createAchat(achat.idAchat,achat.dateAchat,qte,achat.fournisseur,achat.statutAchat)
                        total++
                    }
                }
            }
            $(".comandeNumber").text(total);
            $(".commandeQte").text(totalQte);
            desactiveLoader()
        },
        error : function(){
            desactiveLoader()
        }
    });
}

function getInfoAchat(idAchat) {
    $.ajax({
        type: "POST",
        url: "../../api/achat/getAchat.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idAchat:idAchat
        },
        dataType: "JSON",
        success: function (response) {
            for (const res of response.lesproduit) {
                let component=`<tr>
                    <td>`+res.produit.nomProduit+`</td>
                    <td>`+parseFloat(res.quantiteDemander)+`</td>
                </tr>`
                $(".infoAchat").append(component);
            }
            $("#produit").removeClass("historiqueZoneInactive");
            $(".closeHisto").click(function (e) { 
                e.preventDefault();
                $("#produit").addClass("historiqueZoneInactive");
                resetForm()
                $(".infoAchat").html("");
            });
        },
        error: function(){
            alert("Une erreur est survenue-Veuillez Reessayer")
        }
    });
}
function printBonAchat(idAchat){
    window.location.href="../../print/printAchat.php?idAchat="+idAchat
}

function deleteAchat(idAchat){
    if(confirm("Voulez vraiment supprimer cette commande?")){
        let mdp=prompt("veuillez saisir votre mot de passe pour continuer: ")
        if(mdp){
            if(verifierMotDePasse(mdp)){
                $.ajax({
                    type: "POST",
                    url: "../../api/achat/deleteAchat.php",
                    data: {
                        token : "djessyaroma1234",
                        idBoutique:"djessy",
                        idAchat:idAchat,
                    },
                    success: function () {
                        alert("Suppression reussi avec succes !!")
                        resetForm()
                    }
                }); 
            }else{
                alert("mot de passe incorect")
            }
        }
    }
}
function validerAchat(idAchat){
    if(confirm("Etes vous sur de vouloir valider cette commande ?")){
        let mdp=prompt("veuillez saisir votre mot de passe pour continuer: ")
        if(mdp){
            if(verifierMotDePasse(mdp)){
                $.ajax({
                    type: "POST",
                    url: "../../api/achat/validerAchat.php",
                    data: {
                        token : "djessyaroma1234",
                        idBoutique:"djessy",
                        idAchat : idAchat
                    },
                    success: function () {
                        alert("achat Valider, les produits ont ete ajouter avec succes")
                        resetForm()
                    },
                    error: function(){
                        alert("Une erreur est survenue-Veuillez Reessayer")
                    }
                }); 
            }else{
                alert("mot de passe incorect")
            }
        }
    }
}
function updateAchat(idAchat){
    $.ajax({
        type: "POST",
        url: "../../api/achat/getAchat.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idAchat:idAchat,
        },
        dataType: "JSON",
        success: function (response) {
            for (const res of response.lesproduit) {
                let component=` <tr>
                    <td>`+res.produit.nomProduit+`</td>
                    <td>`+res.quantiteDemander+`</td>
                    <td class="produitReturn">
                        <input type="number" id="`+res.produit.idProduit+`" class="qteRecu" value="`+res.quantiteRecu+`" max="`+res.quantite+`"> 
                    </td>
                    </tr>`
                $(".infoCommande2").append(component);
                $("#qte").removeClass("historiqueZoneInactive");
            }
            $(".closeHisto").click(function (e) { 
                e.preventDefault();
                $("#qte").addClass("historiqueZoneInactive");
                resetForm()
                $(".infoCommande2").html("");
            });
            $(".qteRecu").change(function (e) { 
                let idProduit=$(this).attr("id");
                let qte=$(this).val();
                updateQuantiteRecu(response.idAchat,idProduit,qte)
                e.preventDefault();
            });
        }
    });
}

function updateQuantiteRecu(idAchat,idProduit,qte){
    $.ajax({
        type: "POST",
        url: "../../api/achat/setQuantiteProduit.php",
        data: {
            token:"djessyaroma1234",
            idAchat:idAchat,
            idBoutique:"djessy",
            quantite:qte,
            idProduit:idProduit
        },
        success: function () {
            console.log("modification reussi avec succes !!!");
            // resetForm()
        },
        error: function(){
            console.log("Une erreur c'est produite - veuillez reessayer");
        }
    });
}