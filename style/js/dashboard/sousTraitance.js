selectOnNavbar("elSousTraitance");

$(document).ready(function () {
    activeLoader()
    searchSousTraitance()
});

function createSousTraitance(idSousTraitance,date,totalQte,fournisseur,prix){
    let component=`<tr data-idSousTraitance="`+idSousTraitance+`" >
                   <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
                  <td>`+totalQte+`</td>
                  <td>`+fournisseur+`</td>
                  <td>`+prix+`</td>
                  <td onClick="getInfoSousTraitence(`+idSousTraitance+`)" class="voirCommande" ><i class="fa-solid fa-eye"></i></td>
                  <td onClick="validerSousTraitence(`+idSousTraitance+`)" class="validerCommande" ><button class="btnValider">valider</button></td>
                  <td > <i class="fa fa-pen" onClick="updateSousTraitence(`+idSousTraitance+`)"></i> <i class="fa fa-trash" onClick="deleteSousTraitence(`+idSousTraitance+`)"></i> <i class="fa-solid fa-print" onClick="printBonSousTraitence(`+idSousTraitance+`)"></i></td>
                </tr>`
            
        $(".sousTraitanceTable").append(component);
}

function createSousTraitanceValider(idSousTraitance,date,totalQte,fournisseur,prix){
    let component=`<tr data-idSousTraitance="`+idSousTraitance+`" >
                   <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
                   <td>`+totalQte+`</td>
                   <td>`+fournisseur+`</td>
                  <td>`+prix+`</td>
                  <td onClick="getInfoSousTraitence(`+idSousTraitance+`)" class="voirCommande" ><i class="fa-solid fa-eye"></i></td>
                  <td class="validerCommande" style="color: green;align:center;">Deja Valider</td>
                  <td > <i class="fa-solid fa-print" onClick="printBonSousTraitence(`+idSousTraitance+`)"></i></td>
                </tr>`
            
        $(".sousTraitanceTable").append(component);
}


function searchSousTraitance() {
    let total=0;
    let totalQte=0;
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            action:10
        },
        dataType: "JSON",
        success: function (response) {
            if(response != null || response != undefined){
                for (const res of response.sesSousTraitence) {
                    let qte=0
                    let prix=0
                    if (res.lesproduit != undefined) {
                        for (const produit of res.lesproduit) {
                            qte+=parseInt(produit.quantite)
                            prix+=parseFloat(produit.prix)
                        }
                    }
                    
                    if(res.statut == 1){
                        createSousTraitanceValider(res.idSousTraitence,res.date,qte,res.nomBoutique,prix)
                        total++;
                        totalQte+=qte
                    }else{
                        createSousTraitance(res.idSousTraitence,res.date,qte,res.nomBoutique,prix)
                        total++
                        totalQte+=qte
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

function updateSousTraitence(idSousTraitence){
    $.ajax({
        type: "POST",
        url: "../../api/sousTraitence/getSousTraitence.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idSousTraitence:idSousTraitence,
        },
        dataType: "JSON",
        success: function (response) {
            for (const res of response.lesproduit) {
                let component=` <tr>
                    <td>`+res.produit.nomProduit+`</td>
                    <td>
                        <input type="number" data-id="`+res.produit.idProduit+`" class="quantite`+res.produit.idProduit+` quantiteInput" value="`+res.quantite+`"> 
                    </td>
                    <td class="produitReturn">
                        <input type="number" id="`+res.produit.idProduit+`"  class="price`+res.produit.idProduit+` priceInput" value="`+res.prix+`" > 
                    </td>
                    </tr>`
                $(".updateProduit").append(component);
                $("#qte").removeClass("historiqueZoneInactive");
            }
            $(".closeHisto").click(function (e) { 
                e.preventDefault();
                $("#qte").addClass("historiqueZoneInactive");
                resetForm()
                $(".updateProduit").html("");
            });
            $(".quantiteInput").change(function (e) { 
                let idProduit=$(this).attr("data-id");
                let qte=$(this).val();
                let montant=$(".price"+idProduit).val();
                updateLigne(response.idSousTraitence,idProduit,qte,montant)
                e.preventDefault();
            });
            $(".priceInput").change(function (e) { 
                let idProduit=$(this).attr("id");
                let montant=$(this).val();
                let qte=$(".quantite"+idProduit).val();
                updateLigne(response.idSousTraitence,idProduit,qte,montant)
                e.preventDefault();
            });
        },
        error : function(){
            alert("une erreur c'est produit - Veuillez ressayer")
        }
    });
}


function updateLigne(idSousTraitance,idProduit,qte,montant){
    $.ajax({
        type: "POST",
        url: "../../api/sousTraitence/modifier_ligne.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idSousTraitence:idSousTraitance,
            prix:montant,
            quantite:qte,
            idProduit:idProduit
        },
        success: function () {
            console.log("modification reussi avec succes !!!");
        },
        error: function(){
            console.log("Une erreur c'est produite - veuillez reessayer");
        }
    });
}

function validerSousTraitence(idSousTraitence){
    if(confirm("Etes vous sur de vouloir valider cette commande ?")){
        $.ajax({
            type: "POST",
            url: "../../api/sousTraitence/valider.php",
            data: {
                token : "djessyaroma1234",
                idBoutique:"djessy",
                idSousTraitence : idSousTraitence
            },
            success: function () {
                alert("SousTraitence Valider, les produits ont ete ajouter avec succes")
                resetForm() 
            },
            error: function(){
                alert("Une erreur est survenue-Veuillez Reessayer")
            }
        }); 
    }
}

function getInfoSousTraitence(idSousTraitence) {
    $.ajax({
        type: "POST",
        url: "../../api/sousTraitence/getSousTraitence.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idSousTraitence:idSousTraitence
        },
        dataType: "JSON",
        success: function (response) {
            for (const res of response.lesproduit) {
                let component=`<tr>
                    <td>`+res.produit.nomProduit+`</td>
                    <td>`+parseFloat(res.quantite)+`</td>
                    <td>`+parseFloat(res.prix)+`</td>
                </tr>`
                $(".infoProduit").append(component);
            }
            $("#produitZone").removeClass("historiqueZoneInactive");
            
            $(".closeHisto").click(function (e) { 
                e.preventDefault();
                $("#produitZone").addClass("historiqueZoneInactive");
                resetForm()
                $(".infoProduit").html("");
            });
        },
        error: function(){
            alert("Une erreur est survenue-Veuillez Reessayer")
        }
    });
}

function printBonSousTraitence(idSousTraitence){
    window.location.href="../../print/printSousTraitence.php?idSousTraitence="+idSousTraitence
}

function deleteSousTraitence(idSousTraitence){
    if(confirm("Voulez vraiment supprimer cette commande?")){
        $.ajax({
            type: "POST",
            url: "../../api/sousTraitence/supprimer.php",
            data: {
                token : "djessyaroma1234",
                idBoutique:"djessy",
                idSousTraitence:idSousTraitence,
            },
            success: function () {
                alert("Suppression reussi avec succes !!")
                resetForm()
            }
        }); 
    }
}