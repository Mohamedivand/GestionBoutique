selectOnNavbar("elBanque");
desactiveLoader()



$(document).ready(function () {
    if(verifieIdentiter()){
        searchCard()
        $(".addCard").click(function (e) { 
            e.preventDefault();
            let mdp =prompt("Veuillez saisir votre mot de passe pour continuer: ")
            e.preventDefault();
            if(mdp){
                if(verifierMotDePasse(mdp)){
                    $(".historique").removeClass("historiqueZoneInactive");
                    $(".closeHisto").click(function (e) { 
                        e.preventDefault();
                        $(".historique").addClass("historiqueZoneInactive");
                    });
                }else{
                    alert("mot de passe incorect")
                }
            }
            return false
        });
        $("#btnCreateCard").click(function (e) { 
            e.preventDefault();
    
            let numero= $("#numCard").val();
            $("#numCard").change(function (e) { 
                e.preventDefault();
                numero= $("#numCard").val();
            });
            let nomBanque= $("#nomBanque").val();
            $("#nomBanque").change(function (e) { 
                e.preventDefault();
                solde= $("#nomBanque").val();
            });
            let solde= $("#soldeCard").val();
            $("#soldeCard").change(function (e) { 
                e.preventDefault();
                solde= $("#soldeCard").val();
            });
            if(parseFloat(numero)<=0 || numero==null || numero.trim().length==0 || parseFloat(solde)<=0 || solde==null || solde.trim().length==0){
                alert("Tous les champs sont obligatoires")
            }
            $.ajax({
                type: "POST",
                url: "../../api/carteBancaire/editCarte.php",
                data: {
                    token:"djessyaroma1234",
                    idBoutique:"djessy",
                    numero:numero,
                    nomBanque:nomBanque,
                    solde:solde
                },
                success: function () {
                    alert("La carte a ete ajouter avec succes")
                    $(".historique").removeClass(".historiqueZoneInactive");
                    resetForm()
                },
                error: function(){
                    alert("Une erreur c'est produite veuillez reessayer")
                }
            });
        });
        
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

function createCard(idCarte,num,montant,nomBanque){
    let component=` <div class="containerCard" id="`+idCarte+`">
        <header>
            <span class="logo">
            <img src="../../res/images/creditCard3.png" alt="" />
            <h5>${(nomBanque != null) ? nomBanque : "Master Card"}</h5>
            </span>
            <img src="../../res/images/creditCard2.png" alt="" class="chip" />
        </header>

        <div class="card-details">
            <div class="name-number">
                <h6>Card Number</h6>
                <h5 class="number">`+cc_format(num)+`</h5>
                <h5 class="montant" id="">`+cfa.format(montant)+`</h5>
            </div>
            <div class="valid-date">
                <h6>Action</h6>
                <div>
                    <i class="fa-solid fa-trash deleteCarte" style="color: red;cursor:pointer;"></i>
                </div>
            </div>
        </div>
    </div>`

    $(".creditZone").append(component);
}

let id=0

function searchCard(){
    activeLoader()
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            action:8
        },
        dataType: "JSON",
        success: function (response) {
            let liste=[]
            if (response.sesCartes !== null && response.sesCartes!== undefined) {
                for (const res of response.sesCartes) {
                    createCard(res.idCarteBancaire,res.numeroCarte,res.solde, res.nomBanque)
                    liste.push(res.idCarteBancaire)
                }
            }
            desactiveLoader()
            $(".containerCard:first").trigger("click");

            $(".containerCard").click(function (e) { 
                e.preventDefault();
                $(".containerCard").css("transform", "none");
                $(".containerCard").css("animation", "none");
                $(this).css("transform", "scale(1.1)");
                $(this).css("animation", "pulse 2s infinite");
                let idCarte=$($(this)).attr("id");
                $(".banqueZone").html("");
                searchHistorique(idCarte)
                $(".depotBtn").click(function (e) { 
                    window.location.href="../form/depotBanque.php?idCarte="+idCarte
                });
                $(".retraitbtn").click(function (e) { 
                    window.location.href="../form/retraitBanque.php?idCarte="+idCarte
                });
                $(".pritBtn").click(function (e) { 
                    e.preventDefault();
                    window.location.href="../../print/printBanque.php?idCarte="+idCarte
                });

                $(".deleteCarte").click(function (e) { 
                    e.preventDefault();
                    e.stopPropagation();
                    if (verifierMotDePasse(prompt("Veuillez saisir votre mot de passe"))) {
                        $.ajax({
                            type: "POST",
                            url: "../../api/carteBancaire/deleteCarte.php",
                            data: {
                                token:"djessyaroma1234",
                                idBoutique:"djessy",
                                idCarte:idCarte
                            },
                            success: function () {
                                alert("Carte Supprimer avec succes")
                                resetForm()
                            },
                            error: function(){
                                alert("une erreur est survenue - veuillez reessayer")
                            }
                        });
                    }
                });
            });
        },
        error: function(){
            desactiveLoader()
            alert("Vous n'avez compte Bancaire - Veuillez en enregistrer une")
        }
    });
}

function createHistorique(idTransaction,date,nomEmployer,numEmployer,numCompte,montant,operation,motif){
    let component=`<tr data-idTransaction="`+idTransaction+`">
        <td>`+new Date(date).toLocaleDateString('fr-fr')+`</td>
        <td>`+nomEmployer+`</td>
        <td>`+numEmployer+`</td>
        <td>`+numCompte+`</td>
        <td>`+cfa.format(montant)+`</td>
        <td>`+((operation ==1 ) ? "depot" : "retrait")+`</td>
        <td>`+( motif ? motif : "--")+`</td>
        <td onClick="deleteTransaction(`+idTransaction+`)"><i class="fa-solid fa-trash"></i></td>
    </tr>`
    $(".banqueZone").append(component);
}

function searchHistorique(idCarte){
    id=idCarte
    $.ajax({
        type: "POST",
        url: "../../api/carteBancaire/getCarte.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            idCarte:idCarte
        },
        dataType: "JSON",
        success: function (response) {
            if(response.sesTransactions==null){
                alert("vous n'avez effectuer aucune transaction pour le moment avec cette carte")
            }else{
                for (const transaction of response.sesTransactions) {
                    createHistorique(transaction.idTransaction,transaction.dateTransaction,transaction.nomEmployer,transaction.numEmployer,response.numeroCarte,transaction.montant,transaction.type,transaction.motif)
                }
            }
        },
        error: function(){
            desactiveLoader()
            alert("Vous n'avez aucune Transaction effectuer")
        }
    });
}
function deleteTransaction(idTransaction){
    if (mdp=prompt("Saisissez votre mot de passe: ")) {
        $.ajax({
            type: "POST",
            url: "../../api/auth/checkMdp.php",
            data: {
                token:"djessyaroma1234",
                mdp:mdp
            },
            success: function () {
                $.ajax({
                    type: "POST",

                    url: "../../api/carteBancaire/supprimerTansaction.php",
                    data: {
                        token:"djessyaroma1234",
                        idBoutique:"djessy",
                        idTransaction:idTransaction,
                        idCarteBancaire:id
                    },
                    success: function () {
                        alert("Suppression reussie avec succes")
                        $("tr[data-idTransaction="+idTransaction+"]").remove();
                        resetForm()
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
}

