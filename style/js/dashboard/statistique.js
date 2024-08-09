selectOnNavbar("elStatistique");
var polar=[]
var polarName=[]
var barsData=[]
var lineData=[]


function getPolarInfos(){
  $.ajax({
    type: "POST",
    url: "../../api/statistiques/classementProduit.php",
    data: {
      token:"djessyaroma1234"
    },
    dataType: "JSON",
    success: function (response) {
      let i=0
      for(let res of response){
        i++
        if(i<=5){
          polar.push(res.quantite)
          polarName.push(res.nomProduit)
        }
        let component=`<tr data-idProduit="" >
                        <td>${res.nomProduit}</td>
                        <td>${res.quantite}</td>
                        <td>${cfa.format(res.montantVendu)}</td>
                        <td>${cfa.format(res.benefice)}</td>
                      </tr> `
             $(".produitTable").append(component);         
      }
      const dataPolar = {
        labels: polarName,
        datasets: [{
          label: 'quantiter Vendue',
          data: polar,
          backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(75, 192, 192)',
            'rgb(255, 205, 86)',
            'rgb(201, 203, 207)',
            'rgb(54, 162, 235)'
          ]
        }]
      };
    const polarConfig = {
        type: 'polarArea',
        data: dataPolar,
        options: {}
      };
    
      const polarZone=document.getElementById("polar")
      const graphe1=new Chart(polarZone,polarConfig)
    },
    error: function(){
      // alert("une erreur c'est produite")
    }
  });
}
function getVente(){
  $.ajax({
    type: "post",
    url: "../../api/getBoutique.php",
    data: {
        token : "djessyaroma1234",
        idBoutique : "djessy",
        action : "4"
    },
    dataType: "JSON",
    success: function (response) {
      let i=0
      for(let vente of response.sesVentes){
        i++
        if(i>5){
          break
        }
        let component=`<tr data-idVente="" >
                        <td>${vente.client.nomUser}</td>
                        <td>${cfa.format(vente.montantPayer)}</td>
                        <td>${cfa.format(vente.reduction)}</td>
                        <td>${cfa.format(vente.reste_a_payer)}</td>
                        <td>vente ${(vente.typeVente=="det")? "detail":"En gros"}</td>
                       </tr> `
          $(".venteTable").append(component);
      }
    },
    error: function(){
      desactiveLoader()
      // alert("une erreur c'est produite veuillez reessayer")
    }
  });
}
function getBarInfos(){
  let totalBenefice=0
  $.ajax({
    type: "POST",
    url: "../../api/statistiques/benefice.php",
    data: {
      token:"djessyaroma1234"
    },
    dataType: "JSON",
    success: function (response) {
      for(let res of response){
        barsData.push(res.jour)  
        totalBenefice+=parseFloat(res.jour)
      }
      
      $(".totalBenefice").text(cfa.format(totalBenefice));

  const labelsBar = ["lundi","mardis","mercredis","jeudi","vendredi","samedi",'dimanche'];
  const dataBar = {
    labels: labelsBar,
    datasets: [{
      axis: 'y',
      label: 'Benefice',
      data: barsData,
      fill: false,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
      ],
      borderWidth: 1
    }]
  };
  const barConfig = {
      type: 'bar',
      data:dataBar,
      options: {
        indexAxis: 'y',
      }
    };
  
    const barZone=document.getElementById("bar")
    const graphe2=new Chart(barZone,barConfig)
    },
    error: function(){
      // alert("une erreur c'est produite")
    }
  });
}
function getLineInfos(){
  let totalVente=0
  $.ajax({
    type: "POST",
    url: "../../api/statistiques/vente.php",
    data: {
      token:"djessyaroma1234"
    },
    dataType: "JSON",
    success: function (response) {
      for(let res of response){
        lineData.push(res.jour)
        totalVente+=parseFloat(res.jour)
      }

      $(".totalVente").text(cfa.format(totalVente));

      const labelsLine = ["lundi","mardi","mercredis","jeudi","vendredi","samedi",'dimanche'];
      const dataLine = {
        labels: labelsLine,
        datasets: [{
          label: 'Ventes',
          data: lineData,
          fill: false,
          borderColor: 'rgb(75, 192, 192)',
          tension: 0.1
        }]
      };
      
      const lineConfig = {
        type: 'line',
        data: dataLine,
      };
      
      const line=document.getElementById("line")
      const graphe3=new Chart(line,lineConfig)
      desactiveLoader()

    },
    error: function(){
      desactiveLoader()
      // alert("une erreur c'est produite")
    }
  });
}
function getDette(){
  $.ajax({
    type: "POST",
    url: "../../api/statistiques/montantDette.php",
    data: {
      token:"djessyaroma1234"
    },
    success: function (response) {
      $(".totalDette").text(cfa.format(response));
    }
  });
}
function getDepense(){
  $.ajax({
    type: "POST",
    url: "../../api/statistiques/montantDepense.php",
    data: {
      token:"djessyaroma1234"
    },
    success: function (response) {
      $(".totalDepense").text(cfa.format(response));
    }
  });
}
function getQteProduit(){
  $.ajax({
    type: "POST",
    url: "../../api/statistiques/quantiteProduitVendu.php",
    data: {
      token:"djessyaroma1234"
    },
    success: function (response) {
      $(".totalQte").text(response);
    }
  });
}
$(document).ready(function () {
  activeLoader()
  getPolarInfos()
  getBarInfos()
  getVente()
  getDette()
  getDepense()
  getQteProduit()
  getLineInfos()
});





