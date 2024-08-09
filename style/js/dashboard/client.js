selectOnNavbar("elClient");
activeLoader()
function createClient(nom,numero,totalPayer,Reste_a_payer,date){
    let currentDate = addHours(new Date(date), 0);
    let component=` <tr>
                    <td>`+nom+`</td>
                    <td>`+numero+`</td>
                    <td>`+totalPayer+`</td>
                    <td>`+Reste_a_payer+`</td>
                    <td>`+ new Date(date).toLocaleDateString('fr-fr') +`</td>
                </tr>`
                $(".clientTable").append(component);
}

function searchClient(){
    let i=0
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token:"djessyaroma1234",
            idBoutique:"djessy",
            action:4
        },
        dataType: "JSON",
        success: function (response) {
            $(".totalClient").text(response.sesVentes.length);
            for(let res of response.sesVentes){
                if(res.reste_a_payer>0){
                    i++
                }
                createClient(res.client.nomUser,res.client.contact.tel,cfa.format(res.total_a_payer),cfa.format(res.reste_a_payer),res.dateVente)
            }
            $(".totalClientEndetter").text(i);
            desactiveLoader()
        },
        error: function(){
            desactiveLoader()
        }
    });
}

$(document).ready(function () {
    searchClient()
});