selectOnNavbar("elCommande");
activeLoader()
$(document).ready(function () {
    searchCommande()
    $("#dateSelect").change(function (e) { 
        e.preventDefault();
        tableFilter()
    });
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            action:1
        },
        dataType: "JSON",
        success: function (response) {
            $(".newCommande").click(function (e) { 
                e.preventDefault();
                window.location.href="../extern_public/ajouter_commande.php?idBoutique="+response.idBoutique
            });
        }
    });
});

function searchCommande(){
    $.ajax({
        type: "post",
        url: "../../api/getBoutique.php",
        data: {
            token : "djessyaroma1234",
            idBoutique : "djessy",
            action : 3
        },
        dataType: "JSON",
        success: function (response) {
            desactiveLoader()
            for(let commande of response.sesCommande){
                let total=0
                $(".nbrCommande").text(response.sesCommande.length);
                let i=0
                for (const res of commande.lesproduit) {
                    i+=parseInt(res.quantite-res.quantiteRetourner)
                    let qteTmp=parseFloat(res.quantite-res.quantiteRetourner)
                    total+=(response.typeCommande="det" ? parseFloat(res.produit.prixVenteDetail) * qteTmp : parseFloat(res.produit.prixVenteEngros) * qteTmp)
                }
                if(commande.statutCommande==0){
                    createCommande(commande.idCommande,commande.dateCommande,commande.contact.tel,commande.contact.adresse,response.typeCommande,total,i)
                }else{
                    createCommandeValider(commande.idCommande,commande.dateCommande,commande.contact.tel,commande.contact.adresse,response.typeCommande,total,i)
                }
            }
            
           
        },
        error:function(){
            desactiveLoader()
            alert("Vous n'avez aucune commande")
        }
    });
}

function createCommande(idCommande,date,numClt,adresse,type,total,nbre){
    let component=`<tr id="commande`+idCommande+`">
    <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
    <td>`+numClt+`</td>
    <td>`+adresse+`</td>
    <td>`+nbre+`</td>
    <td>`+(type="det"?"detail":"En gros")+`</td>
    <td>`+cfa.format(total)+`</td>
    <td onClick="getInfoCommande(`+idCommande+`)" class="voirCommande" ><i class="fa-solid fa-eye"></i></td>
    <td onClick="validerCommande(`+idCommande+`)" class="validerCommande" ><button class="btnValider">valider</button></td>
    <td ><i class="fa fa-pen" onClick="getInfoCommande2(`+idCommande+`)"></i> <i class="fa-solid fa-print" onClick="printCommande(`+idCommande+`)"></i></td>
  </tr>`
  
  $(".commandeTable").append(component);
}
function createCommandeValider(idCommande,date,numClt,adresse,type,total,nbre){
    let component=`<tr id="commande`+idCommande+`">
    <td>`+ new Date(date).toLocaleDateString('fr-fr') +`</td>
    <td>`+numClt+`</td>
    <td>`+adresse+`</td>
    <td>`+nbre+`</td>
    <td>`+(type="det"?"detail":"En gros")+`</td>
    <td>`+cfa.format(total)+`</td>
    <td onClick="getInfoCommande(`+idCommande+`)" class="voirCommande" ><i class="fa-solid fa-eye"></i></td>
    <td class="validerCommande" style="color: green;">Deja Valider</td>
    <td ><i class="fa-solid fa-print" onClick="printCommande(`+idCommande+`)"></i></td>
  </tr>`
  
  $(".commandeTable").append(component);
}

function validerCommande(e){
    if(confirm("Etes vous sur de vouloir valider cette commande ?")){
        let mdp=prompt("veuillez saisir votre mot de passe pour continuer: ")
        if(mdp){
            if(verifierMotDePasse(mdp)){
                $.ajax({
                    type: "POST",
                    url: "../../api/gererCommande.php",
                    data: {
                        token : "djessyaroma1234",
                        idCommande : e,
                        action : 1
                    },
                    success: function () {
                        $(`#commande${e}`).remove();
                        resetForm()
                    }
                }); 
            }else{
                alert("mot de passe incorect")
            }
        }
    }
}

function getInfoCommande(e){
        activeLoader()
        $.ajax({
            type: "POST",
            url: "../../api/getCommande.php",
            data: {
                token:"djessyaroma1234",
                idBoutique:"djessy",
                idCommande:e,
            },
            dataType: "JSON",
            success: function (response) {
                for (const res of response.lesproduit) {
                    let qteTmp=parseFloat(res.quantite-res.quantiteRetourner)
                    let component=` <tr>
                        <td>`+res.produit.nomProduit+`</td>
                        <td>`+res.quantite+`</td>
                        <td class="produitReturn">`+res.quantiteRetourner+`</td>
                        <td>`+(response.typeCommande=="det" ? cfa.format(res.produit.prixVenteDetail)  : cfa.format(res.produit.prixVenteEngros))+`</td>
                        <td>`+(response.typeCommande=="det" ? cfa.format(parseFloat(res.produit.prixVenteDetail) * qteTmp)  : cfa.format(parseFloat(res.produit.prixVenteEngros) * qteTmp))+`</td>
                    </tr>`
                    $(".infoCommande").append(component);
                    $("#info").removeClass("historiqueZoneInactive");
                    desactiveLoader()
                }
                $(".closeHisto").click(function (e) { 
                    e.preventDefault();
                    $("#info").addClass("historiqueZoneInactive");
                    $(".infoCommande").html("");
                });
            },
            error: function(){
                alert("Une erreur est survenue-Veuillez Reessayer")
            }
        });
}

function getInfoCommande2(idCommande){
        $.ajax({
            type: "POST",
            url: "../../api/getCommande.php",
            data: {
                token:"djessyaroma1234",
                idBoutique:"djessy",
                idCommande:idCommande,
            },
            dataType: "JSON",
            success: function (response) {
                for (const res of response.lesproduit) {
                    let component=` <tr>
                        <td>`+res.produit.nomProduit+`</td>
                        <td>`+res.quantite+`</td>
                        <td class="produitReturn">
                            <input type="number" id="`+res.produit.idProduit+`" class="qteReturn" value="`+res.quantiteRetourner+`" max="`+res.quantite+`"> 
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

                $(".qteReturn").change(function (e) { 
                    let idProduit=$(this).attr("id");
                    let qte=$(this).val();
                    updateQuantiteReturn(response.idCommande,idProduit,qte)
                    e.preventDefault();
                });
            }
        });
}

function printCommande(idCommande){
    window.location.href="../../print/commandeTmp.php?idCommande="+idCommande+"?statut=1"
}

function updateQuantiteReturn(idCommande,idProduit,qte){
    $.ajax({
        type: "POST",
        url: "../../api/commande/setQuantiteRetourner.php",
        data: {
            token:"djessyaroma1234",
            idCommande:idCommande,
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