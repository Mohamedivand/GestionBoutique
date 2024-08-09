selectOnNavbar("elDepense")
activeLoader()

let totalMontant = 0;
let totalReduction = 0;
let benefice = 0;
let depenseJournalier = 0;
let depenseBenefice = 0
let date = new Date().toLocaleDateString('fr-fr');


function createDepense(idDepense, detail, montant, date, isBenefice) {
    let component = `<tr id="ligneDepense` + idDepense + `">
                    <td>`+ detail + `</td>
                    <td>`+ montant + `</td>
                    <td>`+ new Date(date).toLocaleDateString('fr-fr') + `</td>
                    <td>`+ (isBenefice == 1 ? "Benefice" : "Personnel") + `</td>
                    <td class="btnSupprimer"  id="`+ idDepense + `"><i class="fa-solid fa-trash" ></i></td>
                </tr>`
    $(".depenseZone").append(component);
}

function searchDepense() {
    let totalQteDepenses = 0
    let totalMontantDepense = 0
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token: "djessyaroma1234",
            idBoutique: "djessy",
            action: 5
        },
        dataType: "JSON",
        success: function (response) {
            console.log(1);
            if(response.sesDepenses != undefined && response.sesDepenses != null){
                $(".totalDepenses").text(response.length);
                for (let res of response.sesDepenses) {
                    totalQteDepenses++
                    totalMontantDepense += parseFloat(res.montant)
                    createDepense(res.idDepense, res.detail, cfa.format(res.montant), res.dateDepense, res.provientBenefice)
                    if (date == new Date(res.dateDepense).toLocaleDateString('fr-fr')) {
                        depenseJournalier += parseFloat(res.montant);
                        if (res.provientBenefice == 1) {
                            depenseBenefice += parseFloat(res.montant);
                        }
                    }
                }
            }
            $(".totalQteDepenses").text(totalQteDepenses);
            $(".totalDepenses").text(cfa.format(totalMontantDepense));
            $(".depenseJournalier").text(depenseJournalier);

            $(".btnSupprimer").click(function (e) {
                e.preventDefault();
                let A = confirm("Voulez vous vraiment supprimer cette depense?")
                if (A) {
                    let idDepense = $($(this)).attr("id");
                    $.ajax({
                        type: "GET",
                        url: "../../api/deleteDepense.php",
                        data: {
                            token: "djessyaroma1234",
                            idBoutique: "djessy",
                            idDepense: idDepense
                        },
                        success: function (response) {
                            alert("suppression reussie")
                            $("#ligneDepense" + idDepense).remove();
                        },
                        error: function () {
                            alert("une erreur c'est produite")
                        }
                    });
                }
                return false;
            });
            desactiveLoader();
        },
        error: function () {
            desactiveLoader()
        }
    });
}


$(document).ready(function () {
    searchDepense()
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token: "djessyaroma1234",
            idBoutique: "djessy",
            action: "4"
        },
        dataType: "JSON",
        success: function (response) {
            desactiveLoader()
            for (let res of response.sesVentes) {
                if (date == new Date(res.dateVente).toLocaleDateString('fr-fr')) {
                    if (res.reste_a_payer > 0) {
                        continue
                    }
                    totalMontant += parseFloat(res.montantPayer)
                    totalReduction += parseFloat(res.reduction);
                }

            }
            benefice = totalMontant - totalReduction;
            $(".BeneficeAfter").text(cfa.format(parseFloat(benefice - depenseBenefice)));
        }
    });
});