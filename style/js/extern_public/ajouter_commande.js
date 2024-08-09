// on recuperer lid de la idBoutique
function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
}

let idBoutique=getUrlParams("idBoutique");

// Qunad on Selectionne un produit dans la liste deroulante
let currentProduit = {
    id: null,
    nom: null,
    quantite: null,
    prixDet: null,
    prixGro: null,
    calculerTotal: () => {
        let prixTmp = ($("#typeVente").val() == "det") ? currentProduit.prixDet : currentProduit.prixGro;

        return {
            prixUnitaire: prixTmp,
            total: prixTmp * currentProduit.quantite
        }
    }
}

let client = {
    num: $("#numClient").val(),
    email: $("#emailClient").val(),
    whatsapp: $("#whatsappClient").val(),
    adresse: $("#adresseClient").val(),
}

let vente = {
    client : null,

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

    calculerTotal : ()=>{
        let res =0;
        $("#tableauListeProduit tr").each(function () {
            let prixTmp = ($("#typeVente").val() == "det") ? $(this).attr("data-prixDet") : $(this).attr("data-prixGro");

            res += prixTmp * $(this).attr("data-quantiteProduit") ;
        });

        $("#montantHorsRemise").text(res);

        console.log("montant hors remise:");
        console.log(res);

        return res;
    },

    reload : ()=>{
        vente.getNombreProduit();
        vente.chargerListeProduit();
        vente.calculerTotal();
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

$("#selectProduitZone1").trigger("input");
$("#quantiteZone1").trigger("input");

// si on soumet le formulaire de selection de produit
$("#form1").on("submit", function (e) {
    let component = `
        <tr data-idProduit="${currentProduit.id}"
            data-nomProduit="${currentProduit.nom}" 
            data-prixDet="${currentProduit.prixDet}" 
            data-prixGro="${currentProduit.prixGro}" 
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

$("#numClient").on("input", function () {
    client.num = $("#numClient").val();
    console.log(client.num)
});

$("#whatsappClient").on("change", function () {
    client.whatsapp = $("#whatsappClient").val();
    console.log(client.whatsapp)
});

$("#emailClient").on("input", function () {
    client.email = $("#emailClient").val();
    console.log(client.email)
});

$("#adresseClient").on("input", function () {
    client.adresse = $("#adresseClient").val();
    console.log(client.adresse)
});

// si il change le type de la vente
$("#typeVente").change(function (e) { 
    $("#tableauListeProduit tr").each(function () {
        $(this).children("td").eq(1).text(
            ($("#typeVente").val() == "det") ? $(this).attr("data-prixDet") : $(this).attr("data-prixGro")
        );
        
        $(this).children("td").eq(3).text(
            ($("#typeVente").val() == "det") ? $(this).attr("data-prixDet") : $(this).attr("data-prixGro")
                * 
            $(this).attr("data-quantiteProduit")
        );
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

    if(lancerRequest){
        $.ajax({
            type: "POST",
            url: "../../api/creeCommande.php",
            data: {
                token : 'djessyaroma1234',
                idBoutique : idBoutique,
                listeProduit: vente.chargerListeProduit(),
                typeCommande : $("#typeVente").val(),
                tel : client.num,
                whatsapp : client.whatsapp,
                email : client.email,
                adresse : client.adresse,
            },
            success: function (response) {
                if(confirm("Voulez vous une facture?")){
                    window.open('../../print/commandeTmp.php?idCommande='+response, '_blank');
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
var valueScanned = "";

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

        if(valueScanned.includes("ITEM")){
            let idProduit = valueScanned.substring(4);
            value = $(`#selectProduitZone1 option[data-idProduit=${idProduit}]`).text();
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

// recherche a partir du scanner
$("body").keypress(function(e){   
    let k = e.keyCode;   
    valueScanned += String.fromCharCode(k);  
    setTimeout(()=>{
        console.log(valueScanned) 
        if(valueScanned.includes("ITEM")){
            let idProduit = valueScanned.substring(4);
            $("#searchInput").val(
                $(`#selectProduitZone1 option[data-idProduit=${idProduit}]`).text()
            );
            $("#searchInput").trigger("input");
            $("#searchInput").val("");
            valueScanned = "";
        }
    }, 500);
});   