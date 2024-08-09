selectOnNavbar("elDette");

let date= new Date().toLocaleDateString('fr-fr');

$(document).ready(function () {
    activeLoader()
    searchDette2()
    $("#dateSelect").change(function (e) { 
        e.preventDefault();
        tableFilter()
    });
});

let totalDette=0;
let venteJournalier=0

function searchDette2(){
    $.ajax({
        type: "post",
        url: "../../api/getBoutique.php",
        data: {
            token : "djessyaroma1234",
            idBoutique : "djessy",
            action : "4"
        },
        dataType: "JSON",
        success: function (response) {
            let i=0
            for(let dette of response.sesVentes){
                if(new Date(dette.dateVente).toLocaleDateString('fr-fr') == date){
                    venteJournalier+=parseFloat(dette.montantPayer)
                }
               if(dette.reste_a_payer>0){
                    i++
                    createDette(dette.idVente,dette.dateVente,dette.client.nomUser,dette.client.contact.tel,
                        cfa.format(parseFloat(dette.total_a_payer)),
                        cfa.format(dette.reduction),
                        cfa.format(parseFloat(dette.total_a_payer) - parseFloat(dette.reduction)),
                        cfa.format(dette.montantPayer),
                        cfa.format(dette.reste_a_payer),
                        dette.dateRemboursement);
                    totalDette+=dette.reste_a_payer
                    desactiveLoader()
               }
            }
            $(".totalDette").text(cfa.format(totalDette));
            $(".totalQte").text(i);
            $(".detteJournalier").text(cfa.format(venteJournalier));

            desactiveLoader()
            $(".btnValider").click(function (e) { 
                e.preventDefault();
                let idVente=$($(this)).attr("id");
               let sommeEntrer=$("#sommeEntrer"+idVente).val(); 
               if (sommeEntrer>totalDette) {
                    alert("cette somme est superieur a la somme que le client nous doit")
               } else{
                  if(confirm("Voulez vous vraiment deduire "+sommeEntrer+" de la dette")){
                    $.ajax({
                        type: "POST",
                        url: "../../api/reduireDette.php",
                        data: {
                            token:"djessyaroma1234",
                            idVente:idVente,
                            montant: sommeEntrer
                        },
                        
                        success: function () {
                            if(confirm("La somme a ete deduis avec succes")){
                              resetForm()
                            }
                        },
                        error: function(){
                            alert("une erreur c'est produite")
                        }
                    });
                  }
               }
            });

            $(".histoBtn").click(function (e) { 
                activeLoader()
                e.preventDefault();
                let idVente=$($(this)).attr("id");
                getHistorique("djessyaroma1234",idVente);

                return false
            });
        },
        error: function(){
            desactiveLoader()
            alert("Vous n'avez pas de dette")
        }
    });
}


function createDette(idDette,dateVente,nomClt,numClt,totalPayer,reduction,afterReduction,sommePayer,reste,dateRemboursement){
    let component=`<tr detteNum="`+idDette+`">
    <td>`+new Date(dateVente).toLocaleDateString('fr-fr')+`</td>
    <td>`+nomClt+`</td>
    <td>`+numClt+`</td>
    <td>`+totalPayer+`</td>
    <td>`+reduction+`</td>
    <td>`+afterReduction+`</td>
    <td>`+sommePayer+`</td>
    <td id="reste`+idDette+`">`+reste+`</td>
    <td>`+new Date(dateRemboursement).toLocaleDateString('fr-fr')+`</td>
    <td class="produitBtn" onClick="getListeProduit(`+idDette+`)" ><a  target="_blank" title="voir Liste Produit"><i class="fa-solid fa-eye"></i></a></td>
    <td class="histoBtn" id="`+idDette+`"><a  target="_blank" title="voir historique Payement"><i class="fa-solid fa-eye"></i></a></td>
    <td class="zonePayement"><input type="num" id="sommeEntrer`+idDette+`"> <button class="btnValider" id="`+idDette+`"><i class="fa-solid fa-check"></i></button></td>
    <td><a href="../../print/printDette.php?idVente=`+idDette+`" target="_blank" title="imprimer le recu"><i class="fa-solid fa-print"></i></a></td>
  </tr>`

  $(".detteTable").append(component);
}

function getHistorique(token,idVente){
    $.ajax({
        type: "POST",
        url: "../../api/getHistoriqueVente.php",
        data: {
            token:token,
            idVente:idVente
        },
        dataType: "JSON",
        success: function (response) {
            if(response.historiquePaiement !== undefined){
                $(".titleTab13").text("date Payement");
                $(".titleTab14").text("Montant");
                for(let res of response.historiquePaiement){
                    let component=`<tr>
                    <td>`+  new Date(res.datePaiement).toLocaleDateString('fr-fr') +`</td>
                    <td>`+cfa.format(res.montant)+`</td>
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
                alert("Aucun paiement effectuer par ce client")
            }
        },
        error: function(){
            desactiveLoader()
            alert("Un probleme est survenue - veuillez reesayer")
        }
    });
}

function getListeProduit(idVente){
    $.ajax({
        type: "POST",
        url: "../../api/getHistoriqueVente.php",
        data: {
            token:"djessyaroma1234",
            idVente:idVente
        },
        dataType: "JSON",
        success: function (response) {
            if(response.lesproduit !== undefined){
                $(".titleTab13").text("Produit");
                $(".titleTab14").text("quantiter");
                for(let res of response.lesproduit){
                    let component=`<tr>
                    <td>`+res.produit.nomProduit+`</td>
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
                alert("Cette dette ne content aucun produit")
            }
        },
        error: function(){
            desactiveLoader()
            alert("Un probleme est survenue - veuillez reesayer")
        }
    });
}