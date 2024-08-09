
let montant = $("#montant").val();
$("#montant").change(function (e) {
  e.preventDefault();
  montant = $("#montant").val();
});

let detail = $("#detail").val();
$("#detail").change(function (e) {
  e.preventDefault();
  detail = $("#detail").val();
});
let isBenefice = $("#isBenefice").val();
$("#isBenefice").change(function (e) {
  e.preventDefault();
  isBenefice = $("#isBenefice").val();
});

let date = $("#date").val();
$("#date").change(function (e) {
  e.preventDefault();
  date = $("#date").val();
});
$("#btnEnvoyer").click(function (e) {
  e.preventDefault();
  addDepense(detail, montant, date);
});

function addDepense(detail, montant, date) {
  if(detail==null || detail=="" || detail.trim().length==0){
      alert("le champs detail n'est pas valide")
  }
  if(parseFloat(montant)<=0 || montant==null || montant.trim().length==0){
      alert("le montant n'est pas valide")
  }
  else{
    if ($("#isBenefice").is(":checked")) {
      $.ajax({
        type: "POST",
        url: "../../api/editDepense.php",
        data: {
          token: "djessyaroma1234",
          detail: detail,
          montant: montant,
          date: date,
          provientBenefice:1
        },
        success: function () {
          alert("depense enregistrer avec succes")
          resetForm()
        },
        error: function () {
          alert("depense non enregistrer");
        },
      });
    }else{
      $.ajax({
        type: "POST",
        url: "../../api/editDepense.php",
        data: {
          token: "djessyaroma1234",
          detail: detail,
          montant: montant,
          date: date,
          provientBenefice:0
        },
        success: function () {
          alert("depense enregistrer avec succes")
          resetForm()
        },
        error: function () {
          alert("depense non enregistrer");
        },
      });
    }
  }
}
$("#btnRetour").click(function (e) {
  e.preventDefault();
  window.location.href = "../dashboard/depenses.php";
});
