selectOnNavbar("elProduit");
var minimum = 0;

function chargerProduit() {

  $.ajax({
    type: "POST",
    url: "../../api/getBoutique.php",
    data: {
      token: "djessyaroma1234",
      idBoutique: "djessy",
      action: 1,
    },
    dataType: "JSON",
    success: function (response) {
      desactiveLoader();
      // on charge les produits dans le tableau
      for (produit of response.sesproduits) {
        let component = `<tr data-idProduit="${produit.idProduit
          }" data-nomChamp="${produit.nomProduit}" title="${produit.descriptionProduit
          }" class='${produit.quantiteProduit < 3 ? "tableDenger" : ""}'>
                    <td>
                      <div class="client">
                        <img class="client-img bg-img" src="${produit.imageProduit}" alt="">
                      </div>
                    </td>
                    <td>${produit.nomProduit ? produit.nomProduit : "--"}</td>
                    <td>${produit.prixAchat ? cfa.format(produit.prixAchat) : "--"
          }</td>
                    <td>${produit.quantiteProduit ? produit.quantiteProduit : "--"
          }</td>
                    <td>${produit.prixVenteDetail
            ? cfa.format(produit.prixVenteDetail)
            : "--"
          }</td>
                    <td>${produit.prixVenteEngros
            ? cfa.format(produit.prixVenteEngros)
            : "--"
          }</td>
                    <td>${produit.marque ? produit.marque.nomMarque : "--"}</td>
                    <td>${produit.type ? produit.type.nomType : "--"}</td>
                    <td>${produit.collection
            ? produit.collection.nomCollection
            : "--"
          }</td>
                    <td>${produit.categorie ? produit.categorie.nomCategorie : "--"
          }</td>
                    <td onClick="generateCode('${produit.codeBar == ! null ? produit.codeBar : produit.nomProduit
          }')"><i class="fa-solid fa-barcode"></i></td>
                    <td>${produit.fournisseur
            ? produit.fournisseur.contact.tel
            : "--"
          }</td>
                    <td class="updateProduit" id="${produit.idProduit
          }"><i class="fa-solid fa-pen"></i></td>
                    <td>
                        <a class="deleteProduit" id="${produit.idProduit}">
                            <i class="fa fa-trash actionDeleteMagasin"></i>
                        </a>
                    </td>
                </tr>`;

        $(".produitTable").append(component);

      }

      $(".deleteProduit").click(function (e) {
        e.preventDefault();

        if ((mdp = prompt("Saisissez votre mot de passe: "))) {
          let idProduit = $($(this)).attr("id");
          $.ajax({
            type: "POST",
            url: "../../api/auth/checkMdp.php",
            data: {
              token: "djessyaroma1234",
              mdp: mdp,
            },
            success: function (response) {
              $.ajax({
                type: "POST",
                url: "../../api/deleteProduit.php",
                data: {
                  token: "djessyaroma1234",
                  idProduit: idProduit,
                },
                success: function (response) {
                  alert("Suppression reussie avec succes");
                  $("tr[data-idProduit=" + idProduit + "]").remove();
                },
                error: function () {
                  alert("une erreur c'est produite");
                },
              });
            },
            error: function () {
              alert("Mot de passe incorect");
            },
          });
        }
        return false;
      });

      $(".updateProduit").click(function (e) {
        e.preventDefault();
        let idProduit = $($(this)).attr("id");
        redirecteProduit(idProduit);
      });
    },
    error: function () {
      desactiveLoader();
      $("#voirPlus").css("display", "none");
    },
  });
}

function chargerFournisseur() {
  $.ajax({
    type: "POST",
    url: "../../api/getBoutique.php",
    data: {
      token: "djessyaroma1234",
      action: 2,
      idBoutique: "djessy",
    },
    dataType: "JSON",
    success: function (response) {
      // on charge le nombre de fournisseur
      $(".fournisseur_number").text(response.sesfournisseur.length);
    },
  });
}

function redirecteProduit(idProduit) {
  window.location.href = `../form/modifier_produit.php?idProduit=${idProduit}`;
}

var isProductLoaded = false;

function chargerProduitMin() {
  desactiveLoader();

  if (!isProductLoaded) {

    $.ajax({
      async: false,
      type: "POST",
      url: "../../api/produit/getProduits.php",
      data: {
        token: "djessyaroma1234",
        idBoutique: "djessy",
        idProduit: "djessy",
        min: minimum,
        max: parseInt(minimum) + 100,
      },
      dataType: "JSON",
      success: function (response) {
        if (response.length < 100) {
          isProductLoaded = true;
        }
        // on charge les produits dans le tableau
        let component = "";
        for (produit of response) {
          component = component + `<tr data-idProduit="${produit.idProduit
            }" data-nomChamp="${produit.nomProduit}" title="${produit.descriptionProduit
            }" class='${produit.quantiteProduit < 3 ? "tableDenger" : ""}'>
                        <td>${produit.nomProduit ? produit.nomProduit : "--"
            }</td>
                        <td>${produit.prixAchat
              ? cfa.format(produit.prixAchat)
              : "--"
            }</td>
                        <td>${produit.quantiteProduit
              ? produit.quantiteProduit
              : "--"
            }</td>
                        <td>${produit.prixVenteDetail
              ? cfa.format(produit.prixVenteDetail)
              : "--"
            }</td>
                        <td>${produit.prixVenteEngros
              ? cfa.format(produit.prixVenteEngros)
              : "--"
            }</td>
                        <td>${produit.marque ? produit.marque.nomMarque : "--"
            }</td>
                        <td>${produit.type ? produit.type.nomType : "--"}</td>
                        <td>${produit.collection
              ? produit.collection.nomCollection
              : "--"
            }</td>
                        <td>${produit.categorie
              ? produit.categorie.nomCategorie
              : "--"
            }</td>
                        <td onClick="generateCode('${produit.codeBar == ! null ? produit.codeBar : produit.nomProduit
            }')"><i class="fa-solid fa-barcode"></i></td>
                        <td>${produit.fournisseur
              ? produit.fournisseur.contact.tel
              : "--"
            }</td>
                        <td class="updateProduit" id="${produit.idProduit
            }"><i class="fa-solid fa-pen"></i></td>
                        <td>
                            <a class="deleteProduit" id="${produit.idProduit}">
                                <i class="fa fa-trash actionDeleteMagasin"></i>
                            </a>
                        </td>
                    </tr>`;

        }
        $(".produitTable").append(component);
        minimum += parseInt(100)
        desactiveLoader();


      },
      error: function () {
        desactiveLoader();
        $("#voirPlus").css("display", "none");
      },
    });

    chargerProduitMin();
  }

}

$(".deleteProduit").click(function (e) {
  e.preventDefault();

  if ((mdp = prompt("Saisissez votre mot de passe: "))) {
    let idProduit = $($(this)).attr("id");
    $.ajax({
      type: "POST",
      url: "../../api/auth/checkMdp.php",
      data: {
        token: "djessyaroma1234",
        mdp: mdp,
      },
      success: function (response) {
        $.ajax({
          type: "POST",
          url: "../../api/deleteProduit.php",
          data: {
            token: "djessyaroma1234",
            idProduit: idProduit,
          },
          success: function (response) {
            alert("Suppression reussie avec succes");
            $("tr[data-idProduit=" + idProduit + "]").remove();
          },
          error: function () {
            alert("une erreur c'est produite");
          },
        });
      },
      error: function () {
        alert("Mot de passe incorect");
      },
    });
  }
  return false;
});

$(".updateProduit").click(function (e) {
  e.preventDefault();
  let idProduit = $($(this)).attr("id");
  redirecteProduit(idProduit);
});

$(document).ready(function () {
  //  setInterval(chargerProduitMin, 5000);
  chargerProduit();
  chargerFournisseur();
  getInfoProduit();
  // $("#printBtn").click(function (e) {
  //   e.preventDefault();
  //   genererPDF()
  // });
});

var searchInput = "";
$("#searchBtn").click(function (e) {
  searchInput = $("#searchInput").val();
  if (searchInput != "") {
    $("tbody tr").hide();
    $(`tr[data-nomChamp*="${searchInput}" i]`).show();
  }
});

$("#searchInput").on("input", function () {
  searchInput = $("#searchInput").val();
  if (searchInput == "") {
    $("tbody tr").show();
  }
});

function generateCode(codeBar) {
  let component = `<svg id="barcode"></svg>`;
  $("#contenuBar").html(component);
  JsBarcode("#barcode", codeBar);
  $(".historique").removeClass("historiqueZoneInactive");
  saveComponentASPNG();
  $(".closeHisto").click(function (e) {
    e.preventDefault();
    $(".historique").addClass("historiqueZoneInactive");
  });
}

$("#voirPlus").click(function (e) {
  chargerProduit(minimum);
  minimum += parseInt(100);
});

function getInfoProduit() {
  $.ajax({
    type: "POST",
    url: "../../api/produit/infos.php",
    data: {
      token: "djessyaroma1234",
      idBoutique: "djessy",
    },
    dataType: "JSON",
    success: function (response) {
      // on charge le nombre de produit
      $(".produit_number").text(response.sumQuantiteProduit);
      $(".totalP_achat").text(cfa.format(response.sumPrixAchat));
      $(".totalP_venteDetails").text(cfa.format(response.sumPrixVenteDetail));
      $(".totalP_venteEngros").text(cfa.format(response.sumPrixVenteEngros));
    },
  });
}

var products = [
  { name: 'Product 1', image: '../../res/images/logo.webp', price: 10, quantity: 5 },
  { name: 'Product 2', image: '../../res/images/logo.webp', price: 20, quantity: 3 },
  // ... autres produits
];

// Fonction pour générer le PDF
function generatePDF() {
  var doc = new jsPDF();

  // Entête du tableau
  var headers = ['Name', 'Image', 'Price', 'Quantity'];
  
  // Corps du tableau
  var data = products.map(product => [product.name, product.image, product.price, product.quantity]);

  // Ajouter le tableau au PDF
  doc.autoTable({
    head: [headers],
    body: data,
  });

  // Sauvegarder le PDF
  doc.save('product_list.pdf');
}

// Événement du bouton pour déclencher la génération du PDF
$('#printBtn').on('click', function() {
  // Convertir les images en base64 avec html2canvas
  var promises = products.map(product => 
    html2canvas(document.querySelector('#' + product.image)).then(canvas => canvas.toDataURL())
  );

  // Attendre que toutes les promesses soient résolues
  Promise.all(promises).then(images => {
    // Mettre à jour les données avec les images base64
    products.forEach((product, index) => {
      product.image = images[index];
    });

    // Générer le PDF une fois que les images sont prêtes
    generatePDF();
  });
});