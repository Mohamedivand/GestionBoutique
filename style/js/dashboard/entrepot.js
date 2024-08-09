selectOnNavbar("elEntrepot");
desactiveLoader()

$(".histoDepot").click(function (e) { 
    e.preventDefault();
    let mdp=""
    if(mdp=prompt("Veuillez entrer votre mot de passe pour effecteur cette action: ")){
        $.ajax({
        type: "post",
        url: "../../api/auth/checkMdp.php",
        data: {
            token:"djessyaroma1234",
            mdp:mdp
        },
        success: function (response) {
           
        },
        error:function(){
            alert("Mot de passe Incorect-Acces Refuser")
        }
    });
    }
});
$(".histoRetrait").click(function (e) { 
    e.preventDefault();
    let mdp=""
    if(mdp=prompt("Veuillez entrer votre mot de passe pour effecteur cette action: ")){
        $.ajax({
        type: "post",
        url: "../../api/auth/checkMdp.php",
        data: {
            token:"djessyaroma1234",
            mdp:mdp
        },
        success: function (response) {
           
        },
        error:function(){
            alert("Mot de passe Incorect-Acces Refuser")
        }
    });
    }
});

function getHistorique(token,idVente){
    $.ajax({
        type: "POST",
        url: "../../api/getHistorique.php",
        data: {
            token:token,
            idVente:idVente
        },
        dataType: "JSON",
        success: function (response) {
            if(response.historique !== undefined){
                for(let res of response.historique){
                    let currentDate = addHours(new Date(res.date), 0);
                    let component=`<tr>
                    <td>`+ 
                    date2Digit(currentDate.getDate()) + "/" +
                    date2Digit(currentDate.getMonth()+1) + "/" +
                    currentDate.getFullYear() +
                    " " +
                    date2Digit(currentDate.getHours()) + ":" +
                    date2Digit(currentDate.getMinutes()) +
                    `</td>
                    <td>`+res.nomProduit+`</td>
                    <td>`+res.quantite+`</td>
                    </tr>`

                    $(".histoTable").append(component);
                }
                $(".historique").removeClass("historiqueZoneInactive");
                desactiveLoader()
                $(".closeHisto").click(function (e) { 
                    e.preventDefault();
                    $(".historique").addClass("historiqueZoneInactive");
                    $(".histoTable").html("");
                });
            }else{
                desactiveLoader()
                alert("Aucun depot n'as ete effectuer")
            }
        },
        error: function(){
            desactiveLoader()
            alert("Un probleme est survenue - veuillez reesayer")
        }
    });
}

function chargerProduit(){
    activeLoader()
    let totalQte=0
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token: "djessyaroma1234",
            action : 1,
            idBoutique : 'djessy',
        },
        dataType: "JSON",
        success: function (response) {
            let idProduit=0;
            desactiveLoader();
            // on charge les produits dans le tableau
            for(produit of response.sesproduits){
                let component = `<tr data-idProduit="${produit.idProduit}" data-nomChamp="${produit.nomProduit}" title="${produit.descriptionProduit}">
                    <td>${(produit.nomProduit) ? produit.nomProduit : "--"}</td>
                    <td>${(produit.quantiteEntrepot) ? produit.quantiteEntrepot : "--"}</td>
                    <td class="stockZone"><button class="stockBtn" id="${produit.idProduit}">Ajouter</button></td>
                </tr>`;
                $(".produitTable").append(component);
                totalQte+=parseFloat(produit.quantiteEntrepot)
            } 
            // on charge le nombre de produit
            $(".produit_number").text(totalQte);

            
            $(".stockBtn").click(function (e) { 
                e.preventDefault();
                idProduit=$($(this)).attr("id");
                $(".historique").removeClass("historiqueZoneInactive");
                $(".btnEnvoyer").click(function (e) { 
                    e.preventDefault();
                    augmenterEntrepot(idProduit)
                });
            });
            $(".boutiqueBtn").click(function (e) { 
                e.preventDefault();
                idProduit=$($(this).parent()).attr("class");
                $(".historique").removeClass("historiqueZoneInactive");
                $(".btnEnvoyer").attr("id", "btnDebit");
                $("#btnDebit").click(function (e) { 
                    e.preventDefault();
                    debiterStock(idProduit)
                });
            });
            $(".closeHisto").click(function (e) { 
                e.preventDefault();
                $(".historique").addClass("historiqueZoneInactive");
            });

        },
        error: function(){
            desactiveLoader();
            alert("vous n'avez aucun produit disponible")
        }
    });
}

function augmenterEntrepot(idProduit){
    let qte=$("#quantite").val();
    $.ajax({
        type: "POST",
        url: "../../api/entrepot/augmenterStockEntrepot.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idProduit:idProduit,
            quantite:qte
        },
        success: function () {
            alert("Stock Entrepot modifier avec succes")
            resetForm()
        },
        error:function(){
            alert("Une erreur c'est produite veuille")
        }
    });
}
function debiterStock(idProduit){
    let qte=$("#quantite").val();
    $.ajax({
        type: "POST",
        url: "../../api/entrepot/augmenterStockBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idProduit:idProduit,
            quantite:qte
        },
        success: function () {
            alert("Stock Boutique modifier avec succes")
            resetForm()
        },
        error:function(){
            alert("Une erreur c'est produite veuille")
        }
    });
}

chargerProduit()

var searchInput = '';
$("#searchBtn").click(function (e) { 
    searchInput = $("#searchInput").val();
    if (searchInput != '') {
        $('tbody tr').hide();
        $(`tr[data-nomChamp*="${searchInput}" i]` ).show();
    }
});

$("#searchInput").on("input", function () {
    searchInput = $("#searchInput").val();
    if (searchInput == '') {
        $('tbody tr').show();
    }
});