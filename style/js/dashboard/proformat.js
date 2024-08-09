selectOnNavbar("elProFormat");
desactiveLoader()

function createProformat(id,date,nomClt,numClt,nbreProduit,montant,reduction,whatsapp,email){
    let component=`<tr data-idProformat="`+id+`">
        <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
        <td>`+nomClt+`</td>
        <td>`+numClt+`</td>
        <td>`+nbreProduit+`</td>
        <td>`+cfa.format(montant)+`</td>
        <td>`+reduction+`</td>
        <td class="sendZone"><a href="https://api.whatsapp.com/send?phone=`+whatsapp+`&text=Bonjour je vous contact conernant..." target="_blank" ><i class="fa-brands fa-whatsapp"></i></a> <a target="_blank" href = "mailto:`+email+`"><i class="fa-solid fa-envelope"></i></a></td>
        <td class="validateZone"><button class="btnValider" id="proformat`+id+`"><i class="fa-solid fa-check"></i></button></td>
        <td class=""><a href="../../print/printProformat.php?idProformat=`+id+`" target="_blank" class="btnPrint" ><i class="fa-solid fa-print"></i></a></td>
        <td class="deleteProformat" id="`+id+`"><i class="fa-solid fa-trash"></i></td>
    </tr> `
    $(".proZone").append(component);
}

function searchProformat(){
    activeLoader()
    let i=0
    let sommeTotal=0
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            action:6
        },
        dataType: "JSON",
        success: function (response) {
            for(let res of response.sesProformats) {
                let nbreProduit=0
                if(res.lesproduit != undefined){
                    for(let nbre of res.lesproduit){
                        nbreProduit+=parseInt(nbre.quantite)
                    }
                }
                createProformat(res.idProformat,res.dateProformat,res.client.nomUser,res.client.contact.tel,nbreProduit,res.total,res.reduction,res.client.contact.whatsapp,res.client.contact.email)
                i++
                sommeTotal+=parseFloat(res.total)
            }
            $(".nbreProformat").text(i);
            $(".totalProformat").text(sommeTotal);
            desactiveLoader()

            $(".deleteProformat").click(function (e) { 
                e.preventDefault();
                let idProformat=$($(this)).attr("id");
                if (mdp=prompt("Saisissez votre mot de passe: ")) {
                    $.ajax({
                        type: "POST",
                        url: "../../api/auth/checkMdp.php",
                        data: {
                            token:"djessyaroma1234",
                            mdp:mdp
                        },
                        success: function (response) {
                            $.ajax({
                                type: "GET",
                                url: "../../api/deleteProformat.php",
                                data: {
                                    token:"djessyaroma1234",
                                    idProformat:idProformat
                                },
                                success: function (response) {
                                    alert("Suppression reussie avec succes")
                                    $("tr[data-idProformat="+idProformat+"]").remove();
                                    resetForm()
                                },
                                error: function(){
                                    alert("une erreur c'est produite")
                                }
                            });
                        },
                        error: function(){
                            alert("Mot de passe incorect")
                        }
                    });
                    
                }
                return false;
            });
            $(".btnValider").click(function (e) { 
                e.preventDefault();
                let idProformat=$($(this)).attr("id");
                let id=""
                for(let i=9;i<idProformat.length;i++){
                    id+=idProformat[i]
                }
                if (mdp=prompt("Saisissez votre mot de passe: ")) {
                    $.ajax({
                        type: "POST",
                        url: "../../api/auth/checkMdp.php",
                        data: {
                            token:"djessyaroma1234",
                            mdp:mdp
                        },
                        success: function (response) {
                            $.ajax({
                                type: "POST",
                                url: "../../api/vente/valider_proformat.php",
                                data: {
                                    token:"djessyaroma1234",
                                    idProformat:id
                                },
                                success: function (response) {
                                    alert("Proformat Valider Avec Suces la vente a ete effectuer")
                                    resetForm()
                                },
                                error: function(){
                                    alert("une erreur c'est produite")
                                }
                            });
                        },
                        error: function(){
                            alert("Mot de passe incorect")
                        }
                    });
                    
                }
                return false; 
            });           
             
        },
        error: function(){
            desactiveLoader()
            alert("Vous n'avez creer aucun proformat pour le moment")
        }
    });
}

$(document).ready(function () {
    searchProformat()
});