


window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();
function print() {
    var elementHTML = document.querySelector("#printTable");
    docPDF.html(elementHTML, {
        callback: function (docPDF) {
            docPDF.save('Recu d achat.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    });
}
$("#printButton").trigger("click");