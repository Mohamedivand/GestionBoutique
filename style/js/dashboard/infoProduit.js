selectOnNavbar("elInfo");
$(document).ready(function () {
    activeLoader();
    searchMarque("djessyaroma1234","djessy")
    searchCategorie("djessyaroma1234","djessy")
    searchCollection("djessyaroma1234","djessy")
    searchType("djessyaroma1234","djessy")
    
});


/**
 * on creer un composant d'un tableau, ce composant contiendra les information recu en parametre d'une marque et ensuite on affiche le composant dans notre tableau
 * @param {number} idMarque
 * @param {string} image
 * @param {string} nom
 * @returns {undefined}
 */
function createMarque(idMarque,image,nom){
    let component=`<tr  id="ligneMarque`+idMarque+`">
        <td><img src="`+image+`" alt="" class="imgZone"></td>
        <td>`+nom+`</td>
        <td>
            <a href="../form/modifierMarque.php">
                <i class="fa-solid fa-pen"></i>
            </a>
        </td>
        <td>
            <a class="deleteMarque" id="`+idMarque+`">
                <i class="fa fa-trash actionDeleteMagasin"></i>
            </a>
        </td>
    </tr>`;
    $("#marqueZone").append(component);
}

/**
 * on lance une requete Ajax vers l'api getMarques pour recuperer toutes les marques de notre BD , 
 * ensuite sur chaque ligne  
 * @param {string} token
 * @param {string} idMarque
 * @returns {undefined}
 */
function searchMarque(token,idMarque){
    $.ajax({
        type: "POST",
        url: "../../api/getMarques.php",
        data: {
            token:token,
            idMarque:idMarque
        },
        dataType: "JSON",
        success: function (response) {
            for(let res of response){
                createMarque(res.idMarque,res.imageMarque,res.nomMarque);
            }
            $("#totalMarque").text(response.length);

            
            $(".deleteMarque").click(function (e) { 
                e.preventDefault();
                let A=confirm("Voulez vous vraiment supprimer cette Marque?")
                if(A){
                let idMarque=$($(this)).attr("id");
                $.ajax({
                    type: "POST",
                    url: "../../api/deleteInfoProduit.php",
                    data: {
                        token:"djessyaroma1234",
                        action:1,
                        id:idMarque
                    },
                    success: function (response) {
                        alert("suppression reussie")
                        $("#ligneMarque"+idMarque).remove();
                    },
                    error: function(){
                        alert("une erreur c'est produite")
                    }
                });
                }
                return false;
            });
        }
    });
}

function createCategorie(idCategorie,nomCategorie,descriptionCategorie){
    let component=`<tr id="ligneCategorie`+idCategorie+`">
            <td>`+nomCategorie+`</td>
            <td>`+descriptionCategorie+`</td>
            <td>
                <a href="../form/categorie.php?idCategorie=`+idCategorie+`">
                    <i class="fa-solid fa-pen"></i>
                </a>
            </td> 
            <td>
                <a class="deleteCategorie" id="`+idCategorie+`">
                    <i class="fa fa-trash actionDeleteMagasin"></i>
                </a>
            </td>
        </tr>`
        $("#categorieZone").append(component);
}
function searchCategorie(token,idCategorie){
    $.ajax({
        type: "POST",
        url: "../../api/getCategories.php",
        data: {
            token:token,
            idCategorie:idCategorie
        },
        dataType: "JSON",
        success: function (response) {
            for(let res of response){
                createCategorie(res.idCategorie,res.nomCategorie,res.descriptionCategorie)
            }
            $("#totalCategorie").text(response.length);

            $(".deleteCategorie").click(function (e) { 
                e.preventDefault();
                let A=confirm("Voulez vous vraiment supprimer cette categorie?")
                if(A){
                let idCategorie=$($(this)).attr("id");
                $.ajax({
                    type: "POST",
                    url: "../../api/deleteInfoProduit.php",
                    data: {
                        token:"djessyaroma1234",
                        action:4,
                        id:idCategorie
                    },
                    success: function (response) {
                        alert("suppression reusssi")
                        $("#ligneCategorie"+idCategorie).remove();
                    },
                    error: function(){
                        alert("une erreur c'est produite")
                    }
                });
                }
                return false;
            });
        }
    });
}

function createCollection(idCollection,nomCollection,descriptionCollection){
    let component=`<tr id="ligneCollection`+idCollection+`">
        <td>`+nomCollection+`</td>
        <td>`+descriptionCollection+`</td>
        <td>
            <a href="../form/collection.php?idCollection=`+idCollection+`">
                <i class="fa-solid fa-pen"></i>
            </a>
        </td>
        <td>
            <a class="deleteCollection" id="`+idCollection+`">
                <i class="fa fa-trash actionDeleteMagasin"></i>
            </a>
        </td>
    </tr>`
    $("#collectionZone").append(component);
}
function searchCollection(token,idCollection){
    $.ajax({
        type: "POST",
        url: "../../api/getCollections.php",
        data: {
            token:token,
            idCollection:idCollection
        },
        dataType: "JSON",
        success: function (response) {
            for(let res of response){
                createCollection(res.idCollection,res.nomCollection,res.descriptionCollection);
            }
            $("#totalCollection").text(response.length);

            $(".deleteCollection").click(function (e) { 
                e.preventDefault();
                let A=confirm("Voulez vous vraiment supprimer cette collection?")
                if(A){
                let idCollection=$($(this)).attr("id");
                $.ajax({
                    type: "POST",
                    url: "../../api/deleteInfoProduit.php",
                    data: {
                        token:"djessyaroma1234",
                        action:3,
                        id:idCollection
                    },
                    success: function (response) {
                        alert("suppression reussi")
                        $("#ligneCollection"+idCollection).remove();
                    },
                    error: function(){
                        alert("une erreur c'est produite")
                    }
                });
                }
                return false;
            });
        }
    });
}

function createType(idType,nomType,descriptionType){
    let component=`<tr id="ligneType`+idType+`">
        <td>`+nomType+`</td>
        <td>`+descriptionType+`</td>
        <td>
            <a href="../form/type.php?idType=`+idType+`">
                <i class="fa-solid fa-pen"></i>
            </a>
        </td>
        <td>
            <a class="deleteType" id="`+idType+`">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>`
    $("#typeZone").append(component);
}
function searchType(token,idType){
    $.ajax({
        type: "POST",
        url: "../../api/getTypes.php",
        data: {
            token:token,
            idType:idType
        },
        dataType: "JSON",
        success: function (response) {
            desactiveLoader();
            for(let res of response){
                createType(res.idType,res.nomType,res.descriptionType)
            }
            $("#totalType").text(response.length);

            $(".deleteType").click(function (e) { 
                e.preventDefault();
                let A=confirm("Voulez vous vraiment supprimer cette Type?")
                if(A){
                let idType=$($(this)).attr("id");
                $.ajax({
                    type: "POST",
                    url: "../../api/deleteInfoProduit.php",
                    data: {
                        token:"djessyaroma1234",
                        action:2,
                        id:idType
                    },
                    success: function (response) {
                        alert("suppression reussi")
                        $("#ligneType"+idType).remove();
                    },
                    error: function(){
                        alert("une erreur c'est produite")
                    }
                });
                }
                return false;
            });
        },
        error: function(){
            desactiveLoader()
        }
    });
}