selectOnNavbar("elVente");
activeLoader();
let date = new Date().toLocaleDateString("fr-fr");

var isDeleted = false;

$(document).ready(function () {
  getInfoVente()
  searchVente(minimum);

  $("#dateSelect").change(function (e) {
    e.preventDefault();
    tableFilter();
  });
});

let totalQte = 0;
let totalMontant = 0;
let totalReduction = 0;
let venteJournalier = 0;
let minimum = 0;

function searchVente(min) {
  activeLoader();

  $.ajax({
    type: "POST",
    async: false,
    url: "../../api/vente/getVente.php",
    data: {
      token: "djessyaroma1234",
      idBoutique: "djessy",
      min: min,
      max: min + 100,
    },
    dataType: "JSON",
    success: function (response) {
      for (let vente of response) {
        let prix = 0;
        if (new Date(vente.dateVente).toLocaleDateString("fr-fr") == date) {
          venteJournalier += parseFloat(vente.montantPayer);
        }
        if (vente.reste_a_payer > 0) {
          continue;
        }
        if (vente.lesproduit !== undefined && vente.lesproduit !== null) {
          for (const produit of vente.lesproduit) {
            prix+= parseFloat(produit.prixVenteProduit) * parseFloat(produit.quantite)
          }
        }
        let afterReduction = prix - parseFloat(vente.reduction);
        createVente(
          vente.idVente,
          vente.dateVente,
          vente.client.nomUser,
          vente.client.contact.tel,
          cfa.format(prix),
          cfa.format(vente.reduction),
          cfa.format(afterReduction),
          vente.typeVente == "det" ? "detail" : "En gros"
        );
        minimum++;
        
        let sumTotal = 0;
        if (vente.lesproduit !== undefined) {
          for (let produit of vente.lesproduit) {
            let totalProduit = "--";
            if (
              !isNaN(parseInt(produit.produit.prixVenteDetail)) &&
              !isNaN(parseInt(produit.quantite))
            ) {
              totalProduit =
                parseFloat(produit.produit.prixVenteDetail) *
                parseInt(produit.quantite);
              sumTotal += totalProduit;
              totalQte += parseInt(produit.quantite);
            }
          }
        }
        totalReduction += parseFloat(vente.reduction);
        totalMontant += sumTotal;

        
        $(".venteJournalier").text(cfa.format(venteJournalier));
      }
      desactiveLoader();
    },
    error: function () {
      desactiveLoader();
      $("#voirPlus").css("display", "none");
    },
  });
}

function createVente(
  idVente,
  date,
  nomClt,
  numClt,
  totalPayer,
  reduction,
  afterReduction,
  typeVente
) {
  let component =
    `<tr data-idVente="` +
    idVente +
    `" data-dateChamp="` +
    new Date(date).toLocaleDateString("fr-fr") +
    `">
    <td>` +
    new Date(date).toLocaleDateString("fr-fr") +
    `</td>
    <td>` +
    nomClt +
    `</td>
    <td>` +
    numClt +
    `</td>
    <td>` +
    totalPayer +
    `</td>
    <td>` +
    reduction +
    `</td>
    <td>` +
    afterReduction +
    `</td>
    <td>` +
    typeVente +
    `</td>
    <td class="produitBtn" onClick="getListeProduit(` +
    idVente +
    `)" ><a  target="_blank" title="voir Liste Produit"><i class="fa-solid fa-eye"></i></a></td>
    <td><a href="../../print/printRecuA4.php?idVente=` +
    idVente +
    `" target="_blank" title="imprimer le recu"><i class="fa-solid fa-print"></i></a></td>
    <td ><i class="fa fa-trash" onClick="deleteVente(` +
    idVente +
    `)"></i> <i class="fa-solid fa-print" onClick="printLivraison(` +
    idVente +
    `)"></i></td>
  </tr>`;

  $(".venteTable").append(component);
}

function deleteVente(idVente) {
  let mdp = prompt("Saisissez votre mot de passe: ");
  $.ajax({
    type: "POST",
    url: "../../api/auth/checkMdp.php",
    data: {
      token: "djessyaroma1234",
      mdp: mdp,
    },
    success: function (response) {
      $.ajax({
        type: "GET",
        url: "../../api/deleteVente.php",
        data: {
          token: "djessyaroma1234",
          idVente: idVente,
        },
        success: function (response) {
          alert("Suppression reussie avec succes");
          resetForm();
        },
        error: function () {
          alert("une erreur c'est produite");
        },
      });
    },
    error: function () {
      desactiveLoader();
    },
  });
  return false;
}
function printLivraison(idVente) {
  window.location.href = "../../print/bonLivraision.php?idVente=" + idVente;
}

function getListeProduit(idVente) {
  $.ajax({
    type: "POST",
    url: "../../api/getHistoriqueVente.php",
    data: {
      token: "djessyaroma1234",
      idVente: idVente,
    },
    dataType: "JSON",
    success: function (response) {
      if (response.lesproduit !== undefined) {
        for (let res of response.lesproduit) {
          let component =
            `<tr>
              <td>` + res.produit.nomProduit +`</td>
              <td>` +res.quantite +`</td>
              <td>`+res.quantite * res.prixVenteProduit +`</td>
            </tr>`;
          $(".histoTable").append(component);
        }

        $(".historique").removeClass("historiqueZoneInactive");
        desactiveLoader();
        $(".closeHisto").click(function (e) {
          e.preventDefault();
          $(".historique").addClass("historiqueZoneInactive");
          $(".histoTable").html("");
        });
      } else {
        desactiveLoader();
        alert("Cette dette ne content aucun produit");
      }
    },
    error: function () {
      desactiveLoader();
      alert("Un probleme est survenue - veuillez reesayer");
    },
  });
}
var searchInput = "";
$("#searchBtn").click(function (e) {
  searchInput = $("#searchInput").val();
  if (searchInput != "") {
    $("tbody tr").hide();
    $(`tr[data-dateChamp*="${searchInput}" i]`).show();
  }
});
$("#searchInput").on("input", function () {
  searchInput = $("#searchInput").val();
  if (searchInput == "") {
    $("tbody tr").show();
  }
});

window.onbeforeunload = (e) => {
  if (isDeleted) {
    localStorage.removeItem("listeVente");
  }
};

$("#voirPlus").click(function (e) {
  searchVente(minimum)
});

function getInfoVente(){
  $.ajax({
      type: "POST",
      url: "../../api/vente/infoVenteBoutique.php",
      data: {
          token:"djessyaroma1234",
          idBoutique:"djessy",
      },
      dataType: "JSON",
      success: function (response) {
          $(".totalMontantVendue").text(cfa.format(response.montantVente));
          $(".totalReduction").text(cfa.format(response.reduction));
          $(".totalMontantAfterReduction").text(cfa.format(response.montantApresReduction));
      }
  });
}