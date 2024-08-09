function cancel(){
    window.location.href="../dashboard/boutique.php";
}

function handleBanderole(){
    $("#imageBanderolePicker").css("background", "rgb(0, 177, 53)");
}

function banderolePreview(input) {

    if (input.files) {
        var filesAmount = input.files.length;
        $('#banderoleImageZone').text("");

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                let component= `<div class="productImagesZone">
                    <img src="${event.target.result}" alt="">
                </div>`;
                $('#banderoleImageZone').append(component);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

};

$('#imageBanderoleInput').on('change', function() {
    banderolePreview(this);
    handleBanderole();
});

function handleTampon(){
    $("#imageTamponPicker").css("background", "rgb(0, 177, 53)");
}

function tamponPreview(input) {

    if (input.files) {
        var filesAmount = input.files.length;
        $('#tamponImageZone').text("");

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                // $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                let component= `<div class="productImagesZone">
                    <img src="${event.target.result}" alt="">
                </div>`;
                $('#tamponImageZone').append(component);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

};

$('#imageTamponInput').on('change', function() {
    tamponPreview(this);
    handleTampon();
});