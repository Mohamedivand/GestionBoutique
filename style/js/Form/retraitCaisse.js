function getUrlParams(e){
    let url=window.location.search;
    const urlParams= new URLSearchParams(url);
    return urlParams.get(e);
  }
  let depot=getUrlParams("depot")

  $(document).ready(function () {
    if(depot != null){
        $(".motifBox").remove();
        let component=`<div class="input-box">
            <span class="details">Choisissez la carte</span>
            <select name="" id="cartes">
                <option value="">carte1</option>
                <option value="">carte2</option>
            </select>
        </div>`
        $(".user-details").append(component);
    }
  });
let dateRetrait= $("#dateRetrait").val();
$("#dateRetrait").change(function (e) { 
    e.preventDefault();
    dateRetrait= $("#dateRetrait").val();
});

let nomEmployer= $("#nomEmployer").val();
$("#nomEmployer").change(function (e) { 
    e.preventDefault();
    nomEmployer= $("#nomEmployer").val();
});

let numEmployer= $("#numEmployer").val();
$("#numEmployer").change(function (e) { 
    e.preventDefault();
    numEmployer= $("#numEmployer").val();
});

let montant= $("#montant").val();
$("#montant").change(function (e) { 
    e.preventDefault();
    montant= $("#montant").val();
});

let motif= $("#motif").val();
$("#motif").change(function (e) { 
    e.preventDefault();
    motif= $("#motif").val();
});

$("#btnEnvoyer").click(function (e) { 

    if(dateRetrait==null || dateRetrait=="" || dateRetrait.trim().length==0 || nomEmployer==null || nomEmployer=="" || nomEmployer.trim().length==0 || numEmployer==null || numEmployer=="" || numEmployer.trim().length==0){
        alert("Tous les champs sont obligatoires")
    }
    else if(parseFloat(montant)<=0 || montant==null || montant.trim().length==0){
        alert("le montant n'est pas valide")
    }else{
        activeLoader()
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "../../api/caisse/retrait.php",
            data: {
                token:"djessyaroma1234",
                date:dateRetrait,
                nomEmployer:nomEmployer,
                numEmployer:numEmployer,
                montant:montant,
                motif:motif
            },
            success: function () {
                alert("Depot effectuer avec succes")
                window.location.href="../dashboard/caisse.php"
            },
            error: function(){
                alert("Une erreur c'est produite veuillez reesayer")
            }
        });
    }
});