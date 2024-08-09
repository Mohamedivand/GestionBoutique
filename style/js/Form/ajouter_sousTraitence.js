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
    adresse: $("#adresseClient").val(),
}

let vente = {
    client : null,
    reduction : 0,

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
            }

            listeProduit.push(ligneTmp);
        });

        console.log("Liste des produits:");
        console.log(listeProduit);

        return listeProduit;
    },

    reload : ()=>{
        vente.getNombreProduit();
    }
}

$("#selectProduitZone1").on("input", () => {
    $("#quantiteZone1").val(1);

    currentProduit.id = $("#selectProduitZone1 option:selected").attr("data-idProduit");
    currentProduit.nom = $("#selectProduitZone1 option:selected").text()

    console.log("Les infromation de votre produit :");
    console.log(currentProduit);
});

$("#selectProduitZone1").on("change", () => {
    $("#quantiteZone1").val(1);

    currentProduit.id = $("#selectProduitZone1 option:selected").attr("data-idProduit");
    currentProduit.nom = $("#selectProduitZone1 option:selected").text();

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

            vente.reload();
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

$("#emailClient").on("input", function () {
    client.email = $("#emailClient").val();
    console.log(client.email)
});

$("#adresseClient").on("input", function () {
    client.adresse = $("#adresseClient").val();
    console.log(client.adresse)
});

// pour valider la vente

$("#formFinalVente").on("submit", function (e) {
    $("#validerBtnZone button").addClass("btnLoad");

    let lancerRequest = true;

    if(vente.chargerListeProduit().length < 1){
        alert("Veuillez selectionnez au moin un produit");
        lancerRequest = false;
    }

    if(lancerRequest){
        $.ajax({
            type: "POST",
            url: "../../api/sousTraitence/ajouter_sousTraitence.php",
            data: {
                token : 'djessyaroma1234',
                listeProduit: vente.chargerListeProduit(),
                nom : client.nom,
                tel : client.num,
                email : client.email,
                adresse : client.adresse,
            },
            success: function (response) {
                if(confirm("Voulez vous une facture?")){
                    window.open('../../print/printSousTraitence.php?idSousTraitence='+response, '_blank');
                }
                window.location.reload(true)
            },
            error: function(){
                alert("Une erreur est survenue. Veuillez reessayer");
            }
        });
    }

    $("#validerBtnZone button").removeClass("btnLoad");
    e.preventDefault();
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