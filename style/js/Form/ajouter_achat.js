// Qunad on Selectionne un produit dans la liste deroulante
let currentProduit = {
    id: null,
    nom: null,
    quantite: null,
}

let client = {
    nom: $("#nomClient").val(),
    num: $("#numClient").val(),
    email: $("#emailClient").val(),
    whatsapp: $("#whatsappClient").val(),
    adresse: $("#adresseClient").val(),
}

// Pour charger la liste des produits dans un tableau qui sera envoyer dans la requete final
function chargerListeProduit() {
    let listeProduit = Array();

    $("#tableauListeProduit tr").each(function () {
        let ligneTmp = {
            idProduit : $(this).attr("data-idProduit"),
            quantite : $(this).attr("data-quantiteProduit"),
        }

        listeProduit.push(ligneTmp);
    });

    console.log("Liste des produits:");
    console.log(listeProduit);

    return listeProduit;
}

$("#selectProduitZone1").on("input", () => {
    if($("#selectProduitZone1 option:selected").attr("data-stock") <1){
        alert("Ce produit est fini dans votre entrepot");
        $("#quantiteZone1, #form1 button").hide();
    }
    else{
        $("#quantiteZone1, #form1 button").show();
    }

    $("#quantiteZone1").val(null);
    $("#quantiteZone1").attr("max", $("#selectProduitZone1 option:selected").attr("data-stock"))
    $("#quantiteZone1").attr(
        "placeholder", 
        `Min: 1 | Max: ${$("#selectProduitZone1 option:selected").attr("data-stock")}`
    );
    currentProduit.id = $("#selectProduitZone1 option:selected").attr("data-idProduit");
    currentProduit.nom = $("#selectProduitZone1 option:selected").text()

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

    console.log("Les infromation de votre produit :");
    console.log(currentProduit);
});

$("#quantiteZone1").on("input", () => {
    currentProduit.quantite = $("#quantiteZone1").val();

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
            data-quantiteProduit="${currentProduit.quantite}"
        >
            <td>${currentProduit.nom}</td>
            <td>${currentProduit.quantite}</td>
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

            chargerListeProduit()
        }
    });

    $(".modifierBtn").click(function (e) {
        let id = $(this).parent().parent().parent().attr("data-idProduit");
        let nom = $(this).parent().parent().parent().attr("data-nomProduit");
        let qt = $(this).parent().parent().parent().attr("data-quantiteProduit");

        currentProduit.id = id;
        currentProduit.nom = nom;
        currentProduit.quantite = qt;

        $("#selectProduitZone1 option").removeAttr('selected').filter("[data-idProduit='" + id + "']").attr("selected", true);
        $("#quantiteZone1").val(qt);

        $(this).parent().parent().parent().remove();

        chargerListeProduit()
    });

    chargerListeProduit()

    e.preventDefault();
});

// pour vider le tableau
$("#btnViderTableau").click(function (e) {
    e.preventDefault();
    if (confirm("Etes vous sur de vouloir supprimer tout les produits du panier ?")) {
        $("#tableauListeProduit").empty();

        chargerListeProduit()
    }
});

// pour valider la vente

$("#validerVenteBtn").click("click", function (e) {
    $("#validerVenteBtn").addClass("btnLoad");

    let lancerRequest = true;

    if(chargerListeProduit().length < 1){
        alert("Veuillez selectionnez au moin un produit");
        lancerRequest = false;
    }

    if(lancerRequest){
        $.ajax({
            type: "POST",
            url: "../../api/achat/ajouter_achat.php",
            data: {
                token : 'djessyaroma1234',
                listeProduit: chargerListeProduit()
            },
            success: function (response) {
                if(confirm("Voulez vous un document de commande?")){
                    window.open('../../print/printAchat.php?idAchat='+response, '_blank');
                }
                window.location.reload(true)
            },
            error: function(){
                alert("Stoque insuffisant. veuillez verifier votre stoque de produit");
            }
        });
    }

    $("#validerBtnZone button").removeClass("btnLoad");
    e.preventDefault();

    return false;
});

var valueScanned = "";

// pout rechercher un produit
$("#searchInput").on("input", function (e) { 
    let value = $("#searchInput").val();
    if(value == "" || value == null){
        $("#selectProduitZone1 option").show();
    }
    else{
        $.expr[":"].contains = $.expr.createPseudo(function(arg) {
            return function( elem ) {
                return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
            };
        });
    
        $("#selectProduitZone1 option").hide();

        if(valueScanned.length > 5){
            value = $(`#selectProduitZone1 option[data-codeBar=${valueScanned}]`).text();
            $("#selectProduitZone1 option").show();
        }
    
        $("#selectProduitZone1").find(
            `option:contains(${value})`
        ).show();
    
        $("#selectProduitZone1").find(
            `option:contains(${value})`
        ).eq(
            0
        ).prop("selected", true);
    
        $("#selectProduitZone1").trigger("change");
    }

});

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
