$('button').eq(1).click(function(){
    window.location.href="../dashboard/produit.php";
})

function handle(){
    document.getElementById("image-class").style.backgroundColor = "rgb(0, 177, 53)";
}

// function supprimerImage(id, objet){
//     if(confirm("Etes vous sur de vouloir supprimer cette image?")){
//         $.ajax({
//             url: "../api/ajouterProduit/supprimerImage.php",
//             type: "GET",
//             data: {
//                 "idImgProduit":id
//             },
//             success: function (response) {
//                 alert("L'image a ete supprimer");
//                 $(objet).remove();
//             },
//             error: function(){
//                 alert("Une erreur est survenue");
//             }
//         });
//     }
// }

// $(".productImagesZone").click(function (e) {
//     supprimerImage($(this).attr('id'), this);
// });

var imagesPreview = function(input) {

    if (input.files) {
        var filesAmount = input.files.length;
        $('.imageSliderZone').text("");

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                let component= `<div class="productImagesZone">
                    <img src="${event.target.result}" alt="">
                </div>`;
                $('.imageSliderZone').append(component);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

};

$('.inp_image').on('change', function() {
    imagesPreview(this);
    handle();
});