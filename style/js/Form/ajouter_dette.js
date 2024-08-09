$(document).ready(function () {
    dateMinDeRemboursement();
});

// Qunad on Selectionne un produit dans la liste deroulante
let currentProduit = {
    id: null,
    nom: null,
    quantite: null,
    prixDet: null,
    prixGro: null,
    prixPersonnel: null,
    calculerTotal: () => {
        let prixTmp = 0;

        prixTmp = $("#prixPersonnel").val();

        if ($("#prixPersonnel").val() == 'null' || $("#prixPersonnel").val() == '') {
            prixTmp = ($("#typeVente").val() == "det") ? currentProduit.prixDet : currentProduit.prixGro;
        }

        return {
            prixUnitaire: prixTmp,
            total: prixTmp * currentProduit.quantite
        }
    }
}

let client = {
    nom: $("#nomClient").val(),
    num: $("#numClient").val(),
}

let vente = {
    client : null,
    sommeRecue : 0,
    reduction : 0,
    dateRemboursement : null,

    getNombreProduit : () => {
        let res = 0;
        $("#tableauListeProduit tr").each(function () {
            res += parseInt($(this).attr("data-quantiteProduit"))
        });

        $("#nombreProduitSpan").text(res);

        console.log("nombres de produits:");
        console.log(res);

        return res;
    },

    chargerListeProduit : () => {
        let listeProduit = Array();

        $("#tableauListeProduit tr").each(function () {
            let ligneTmp = {
                idProduit : $(this).attr("data-idProduit"),
                quantite : $(this).attr("data-quantiteProduit"),
                prixPersonnel: $(this).attr("data-prixPersonnel"),
            }

            listeProduit.push(ligneTmp);
        });

        console.log("Liste des produits:");
        console.log(listeProduit);

        return listeProduit;
    },

    calculerTotalHorsRemise : ()=>{
        let res =0;
        $("#tableauListeProduit tr").each(function () {
            let prixTmp = 0;

            if ($(this).attr('data-prixPersonnel') == 'null' || $(this).attr('data-prixPersonnel') == '') {
                prixTmp = ($("#typeVente").val() == "det") ? $(this).attr("data-prixDet") : $(this).attr("data-prixGro");
            }
            else {
                prixTmp = $(this).attr('data-prixPersonnel');
            }

            res += prixTmp * $(this).attr("data-quantiteProduit");
        });

        $("#montantHorsRemise").text(res);

        console.log("montant hors remise:");
        console.log(res);

        return res;
    },

    calculerTotalAvecRemise : ()=>{
        let res =vente.calculerTotalHorsRemise() - vente.reduction;

        $("#montantAvecRemise").text(res);

        console.log("montant avec remise:");
        console.log(res);

        return res; 
    },

    calculerMontantARemettre : ()=>{
        let res =vente.calculerTotalAvecRemise() - vente.sommeRecue ;

        $("#montantARemettre").text(res);

        console.log("montant a remettre:");
        console.log(res);

        return res; 
    },

    reload : ()=>{
        vente.calculerMontantARemettre();
        vente.getNombreProduit();
        vente.chargerListeProduit();
    }
}

$("#selectProduitZone1").on("input", () => {
    $("#quantiteZone1").val(null);
    $("#quantiteZone1").attr("max", $("#selectProduitZone1 option:selected").attr("data-stock"))
    $("#quantiteZone1").attr(
        "placeholder", 
        `Min: 1 | Max: ${$("#selectProduitZone1 option:selected").attr("data-stock")}`
    );

    currentProduit.id = $("#selectProduitZone1 option:selected").attr("data-idProduit");
    currentProduit.nom = $("#selectProduitZone1 option:selected").text()
    currentProduit.prixDet = $("#selectProduitZone1 option:selected").attr("data-prixDet")
    currentProduit.prixGro = $("#selectProduitZone1 option:selected").attr("data-prixGro")

    $("#prixDetZone1").val(currentProduit.prixDet);
    $("#prixGroZone1").val(currentProduit.prixGro);

    console.log("Les infromation de votre produit :");
    console.log(currentProduit);
});

$("#selectProduitZone1").on("change", () => {
    $("#quantiteZone1").val(null);
    $("#quantiteZone1").attr("max", $("#selectProduitZone1 option:selected").attr("data-stock"))
    $("#quantiteZone1").attr(
        "placeholder", 
        `Min: 1 | Max: ${$("#selectProduitZone1 option:selected").attr("data-stock")}`
    );
    currentProduit.id = $("#selectProduitZone1 option:selected").attr("data-idProduit");
    currentProduit.nom = $("#selectProduitZone1 option:selected").text()
    currentProduit.prixDet = $("#selectProduitZone1 option:selected").attr("data-prixDet")
    currentProduit.prixGro = $("#selectProduitZone1 option:selected").attr("data-prixGro")

    $("#prixDetZone1").val(currentProduit.prixDet);
    $("#prixGroZone1").val(currentProduit.prixGro);

    console.log("Les infromation de votre produit :");
    console.log(currentProduit);
});

$("#quantiteZone1").on("input", () => {
    currentProduit.quantite = $("#quantiteZone1").val();

    console.log("Les infromation de votre produit :");
    console.log(currentProduit);
});

$("#prixPersonnel").on("input", () => {
    currentProduit.prixPersonnel = $("#prixPersonnel").val();

    console.log("Les infromation de votre produit :");
    console.log(currentProduit);
});

$("#selectProduitZone1").trigger("input");
$("#quantiteZone1").trigger("input");

// si on soumet le formulaire de selection de produit
$("#form1").on("submit", function (e) {
    let component = `
        <tr data-idProduit="${currentProduit.id}"
            data-nomProduit="${currentProduit.nom}" 
            data-prixDet="${currentProduit.prixDet}" 
            data-prixGro="${currentProduit.prixGro}" 
            data-prixPersonnel="${currentProduit.prixPersonnel}" 
            data-quantiteProduit="${currentProduit.quantite}"
        >
            <td>${currentProduit.nom}</td>
            <td>${currentProduit.calculerTotal().prixUnitaire}</td>
            <td>${currentProduit.quantite}</td>
            <td>${currentProduit.calculerTotal().total}</td>
            <td class="actionZone">
                <div>
                    <span class="deleteBtn" title="supprimer">
                        <i class="fa fa-trash-alt"></i>
                    </span>
                    <span class="modifierBtn" title="Modifier">
                        <i class="fa fa-pen-alt"></i>
                    </span>
                </div>
            </td>
        </tr>
    `;

    // on verifie si on a deja le produit dans le tableau

    let selected = false;

    $("#tableauListeProduit tr").each(function () {
        if ($(this).attr("data-idProduit") == currentProduit.id) {
            selected = true;
        }
    });

    if (selected) {
        alert("Ce produit est deja selectionner. vous pouver le supprimer ou modifier la quantite");
    }
    else {
        $("#tableauListeProduit").append(component);
    }

    // pour supprimer lelement du tableau
    $(".deleteBtn").click(function (e) {
        if (confirm("Etes vous sur de vouloir supprimer tout ce produit du panier ?")) {
            $(this).parent().parent().parent().remove();

            vente.reload();
        }
    });

    $(".modifierBtn").click(function (e) {
        let id = $(this).parent().parent().parent().attr("data-idProduit");
        let nom = $(this).parent().parent().parent().attr("data-nomProduit");
        let prixDet = $(this).parent().parent().parent().attr("data-prixDet");
        let prixGro = $(this).parent().parent().parent().attr("data-prixGro");
        let qt = $(this).parent().parent().parent().attr("data-quantiteProduit");

        currentProduit.id = id;
        currentProduit.nom = nom;
        currentProduit.prixDet = prixDet;
        currentProduit.prixGro = prixGro;
        currentProduit.quantite = qt;

        $("#selectProduitZone1 option").removeAttr('selected').filter("[data-idProduit='" + id + "']").attr("selected", true);
        $("#quantiteZone1").val(qt);
        $("#prixDetZone1").val(prixDet);
        $("#prixGroZone1").val(prixGro);

        $(this).parent().parent().parent().remove();

        vente.reload();
    });

    vente.reload();

    e.preventDefault();
});

// pour vider le tableau
$("#btnViderTableau").click(function (e) {
    e.preventDefault();
    if (confirm("Etes vous sur de vouloir supprimer tout les produits du panier ?")) {
        $("#tableauListeProduit").empty();

        vente.reload();
    }
});

// Pour changer les info du client
$("#nomClient").on("change", function () {
    client.nom = $("#nomClient").val();
    console.log(client.nom)
});

$("#numClient").on("input", function () {
    client.num = $("#numClient").val();
    console.log(client.num)

});

// On charge les info de paimement
$("#sommeRecu").on("input", function () {
    vente.sommeRecue = $("#sommeRecu").val();
    vente.reload();
});

$("#reductionBtn").click(function (e) { 
    vente.reduction = $("#reduction").val();
    vente.reload();
});

$("#dateRemboursement").change(function (e) {
    vente.dateRemboursement = $("#dateRemboursement").val();
});

// si il change le type de la vente
$("#typeVente").change(function (e) { 
    $("#tableauListeProduit tr").each(function () {
        if ($(this).attr("data-prixPersonnel") == 'null' || $(this).attr("data-prixPersonnel") == '') {
            $(this).children("td").eq(1).text(
                ($("#typeVente").val() == "det") ? $(this).attr("data-prixDet") : $(this).attr("data-prixGro")
            );

            $(this).children("td").eq(3).text(
                ($("#typeVente").val() == "det") ? $(this).attr("data-prixDet") : $(this).attr("data-prixGro")
                    *
                    $(this).attr("data-quantiteProduit")
            );
        }
    });

    vente.reload();
    
    e.preventDefault();
});

// pour valider la vente

$("#formFinalVente").on("submit", function (e) {
    $("#validerBtnZone button").addClass("btnLoad");

    let lancerRequest = true;

    if(vente.chargerListeProduit().length < 1){
        alert("Veuillez selectionnez au moin un produit");
        lancerRequest = false;
    }
    if(!(vente.dateRemboursement >= dateMinDeRemboursement())){
        alert("vEUILLEZ MENTIONNER une date ulterieur");
        lancerRequest = false;
    }

    if(lancerRequest){
        $("#validerVenteBtn").attr("disabled", true);

        $.ajax({
            type: "POST",
            url: "../../api/vente/ajouter_dette.php",
            data: {
                token : 'djessyaroma1234',
                listeProduit: vente.chargerListeProduit(),
                typeVente : $("#typeVente").val(),
                reduction : vente.reduction,
                montantPayer : vente.sommeRecue,
                nom : client.nom,
                tel : client.num,
                dateRemboursement : vente.dateRemboursement
            },
            success: function () {
                alert("Nouvelle vente effectuer");
                window.location.reload(true)
            },
            error: function(){
                alert("Stoque insuffisant. veuillez verifier votre stoque de produit");
            }
        });
    }

    // $.ajax({
    //     type: "POST",
    //     url: "api/auth/connexion.php",
    //     data: dataString,
    //     success: () => {
    //         window.location.href = "pages/dashboard/boutique.php";
    //     },
    //     error: () => {
    //         alert("Identifiants incorrecte.");
    //     }
    // });

    $("#validerBtnZone button").removeClass("btnLoad");
    e.preventDefault();
});

let dateMinDeRemboursement = () => {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    } 
                                    
    today = yyyy + '-' + mm + '-' + dd;
    // alert(today);

    $("#dateRemboursement").attr("min", today);

    return today;
}

// pout rechercher un produit
$("#searchInput").on("input", function (e) {
    let value = $("#searchInput").val();

    $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    $("#selectProduitZone1 option").removeAttr('selected');

    $("#selectProduitZone1 option").hide();

    $("#selectProduitZone1").find(
        `option:contains(${value})`
    ).show();

    $("#selectProduitZone1").find(
        `option:contains(${value})`
    ).eq(
        0
    ).prop("selected", true);

    $("#selectProduitZone1").trigger("change");

});

$("#codeBar").click(() => {
    $("#codeBar").val("");
})

$("#codeBar").on("change", function (e) {
    
    $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    let value = $("#codeBar").val();

    $("#selectProduitZone1 option").removeAttr('selected');

    $("#selectProduitZone1 option").filter("[data-codeBar='" + value + "']").attr("selected", true);

    $("#selectProduitZone1").trigger("change");

    return false;
});