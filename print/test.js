let listeProduit = [];



function imprimerProduit() {
    $.ajax({
        type: "POST",
        url: "../../api/getBoutique.php",
        data: {
            token: "djessyaroma1234",
            idBoutique: "djessy",
            action: 1,
        },
        dataType: "JSON",
        async : false,
        success: function (response) {
            for (const produit of response.sesproduits) {
                let tmp = {
                    imageProduit: produit.imageProduit,
                    nom: produit.nomProduit,
                    prixAchat: produit.prixAchat,
                    quantite: produit.quantiteProduit,
                    prixVenteDetail: produit.prixVenteDetail,
                    prixVenteEnGros: produit.prixVenteEngros,
                    fournisseur: produit.fournisseur != null ? produit.fournisseur.nomUser : "--"
                }

                listeProduit.push(tmp)
            }
            console.log(listeProduit);
        },
        error: function () {
            alert("une erreur c'est produite")
        }
    });

    // var doc = new jsPDF()

    // // Ajouter des métadonnées au document PDF
    // doc.setProperties({
    //     title: 'Liste des Produits',
    //     author: 'Votre Nom',
    // });

    // // Ajouter un en-tête au document
    // doc.setFontSize(18);
    // doc.text('Liste des Produits', 20, 20);

    // // Ajouter les données de listeProduit au document
    // var startY = 30;
    // var xOffset = 20;

    // listeProduit.forEach((produit, index) => {
    //     // Ajouter l'image du produit
    //     if (produit.imageProduit) {
    //         var imgData = produit.imageProduit; // Assurez-vous que produit.imageProduit contient les données de l'image correctes
    //         doc.addImage(imgData, 'JPEG', xOffset, startY, 40, 40);
    //     }

    //     // Ajouter les autres données du produit
    //     doc.text(`Nom: ${produit.nom}`, xOffset + 50, startY);
    //     doc.text(`Prix Achat: ${produit.prixAchat}`, xOffset + 50, startY + 10);
    //     doc.text(`Quantité: ${produit.quantite}`, xOffset + 50, startY + 20);
    //     doc.text(`Prix Vente Détail: ${produit.prixVenteDetail}`, xOffset + 50, startY + 30);
    //     doc.text(`Prix Vente En Gros: ${produit.prixVenteEnGros}`, xOffset + 50, startY + 40);
    //     doc.text(`Fournisseur: ${produit.fournisseur}`, xOffset + 50, startY + 50);

    //     // Augmenter la position Y pour la prochaine entrée
    //     startY += 80;

    //     // Ajouter une nouvelle page si nécessaire (par exemple, après chaque 3 produits)
    //     if (index > 0 && index % 3 === 0) {
    //         doc.addPage();
    //         startY = 20;
    //     }
    // });

    // // Enregistrer le document en tant que fichier PDF
    // doc.save('listeProduit.pdf');

    var props = {
        outputType: jsPDFInvoiceTemplate.OutputType.Save,
        returnJsPDFDocObject: true,
        fileName: "Invoice 2021",
        orientationLandscape: false,
        compress: true,
        stamp: {
            inAllPages: true, //by default = false, just in the last page
            src: "https://raw.githubusercontent.com/edisonneza/jspdf-invoice-template/demo/images/qr_code.jpg",
            type: 'JPG', //optional, when src= data:uri (nodejs case)
            width: 20, //aspect ratio = width/height
            height: 20,
            margin: {
                top: 0, //negative or positive num, from the current position
                left: 0 //negative or positive num, from the current position
            }
        },
        business: {
            name: "Business Name",
            address: "Albania, Tirane ish-Dogana, Durres 2001",
            phone: "(+355) 069 11 11 111",
            email: "email@example.com",
            email_1: "info@example.al",
            website: "www.example.al",
        },
        invoice: {
            headerBorder: false,
            tableBodyBorder: false,
            header: [
                {
                    title: "#",
                    style: {
                        width: 10
                    }
                },
                {
                    title: "Nom",
                    style: {
                        width: 50
                    }
                },
                {
                    title: "Prix_Achat",
                    // style: {
                    //     width: 80
                    // }
                },
                { title: "Qte" },
                { title: "PV_Detail" },
                { title: "PV_ENGROS" },
                { title: "fournisseur" }
            ],
            table: Array.from(listeProduit, (produit, index) => ([
                index + 1,
                produit.nom,
                produit.prixAchat,
                produit.quantite,
                produit.prixVenteDetail,
                produit.prixVenteEnGros,
                produit.fournisseur
            ])),
            additionalRows: [{
                col1: 'Total:',
                col2: '145,250.50',
                col3: 'ALL',
                style: {
                    fontSize: 14 //optional, default 12
                }
            },
            {
                col1: 'VAT:',
                col2: '20',
                col3: '%',
                style: {
                    fontSize: 10 //optional, default 12
                }
            },
            {
                col1: 'SubTotal:',
                col2: '116,199.90',
                col3: 'ALL',
                style: {
                    fontSize: 10 //optional, default 12
                }
            }],
        },
        footer: {
            text: "The invoice is created on a computer and is valid without the signature and stamp.",
        },
        pageEnable: true,
        pageLabel: "Page ",
    };


    var pdfObject = jsPDFInvoiceTemplate.default(props);
    console.log(pdfObject);
}


