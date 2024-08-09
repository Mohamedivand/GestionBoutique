selectOnNavbar("elCaisse");
$(document).ready(function () {
    if(verifieIdentiter()){
        searchComponent()
    }else{
        window.location.href="produit.php"

    }
});


function verifieIdentiter(){
    activeLoader()
    let res=false
    for(let i=3;i>0;i--){
        let mdp=prompt("Veuillez saisir votre mot de passe pour continuer(vous avez "+i+" tentative): ")
        if(!mdp){
            break
        }else{
            if(verifierMotDePasse(mdp)){
                desactiveLoader()
                res=true
                break
            }
            if(i==2){
                window.location.href="boutique.php"
            }
        }
    }
    return res
}

function createTransaction(idTransaction,date,nom,num,montant,type,motif) {
    let component=`<tr data-idTransaction="`+idTransaction+`">
    <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
    <td>`+nom+`</td>
    <td>`+num+`</td>
    <td>`+cfa.format(montant)+`</td>
    <td>`+( (type==1) ? "depot" : "retrait" )+`</td>
    <td>`+( (motif) ? motif : "--" )+`</td>
    <td>
        <a class="deleteTransaction" id="`+idTransaction+`">
            <i class="fa fa-trash actionDeleteMagasin"></i>
        </a>
    </td>
</tr>`
$(".caisseZone").append(component);
}

function searchComponent(){
    activeLoader()
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            action:7,
            idBoutique:"djessy"
        },
        dataType: "JSON",
        success: function (response) {
            let i=0,j=0
            for(let res of response.sesTransactions){
                if(res.type==1){
                    i+=parseFloat(res.montant)
                }else{
                    j+=parseFloat(res.montant)
                }
                createTransaction(res.idTransaction,res.dateTransaction,res.nomEmployer,res.numEmployer,res.montant,res.type,res.motif)
            }
            $(".totalDepot").text(i);
            $(".totalRetrait").text(j);
            $(".sommeTotal").text(cfa.format(response.solde));
            desactiveLoader()

            $(".deleteTransaction").click(function (e) { 
                e.preventDefault();
                
                if (mdp=prompt("Saisissez votre mot de passe: ")) {

                    let idTransaction=$($(this)).attr("id");
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
                                url: "../../api/caisse/supprimerTansaction.php",
                                data: {
                                    token:"djessyaroma1234",
                                    idBoutique:"djessy",
                                    idTransaction:idTransaction
                                },
                                success: function (response) {
                                    alert("Suppression reussie avec succes")
                                    $("tr[data-idTransaction="+idTransaction+"]").remove();
                                },
                                error: function(){
                                    alert("une erreur c'est produite - Veuillez reessayer")
                                }
                            });
                        },
                        error: function(){
                            alert("Mot de passe incorect - Veuillez reessayer")
                        }
                    });
                    
                }
                return false;
            });
        },
        error: function(){
            desactiveLoader()
            alert("Vous n'avez aucune transaction dans votre historique")
        }
    });
}