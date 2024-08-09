$("#connexionForm").on("submit", function (e) {
    $(".btnZone button").addClass("btnLoad");
    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "api/auth/connexion.php",
        data: dataString,
        success: () => {
            window.location.href = "pages/dashboard/boutique.php";
        },
        error: () => {
            alert("Identifiants incorrecte.");
        }
    });
    $(".btnZone button").removeClass("btnLoad");
    e.preventDefault();
});

$("#passwordToggler").click(function (e) { 
    let type = $("#password").attr("type");
    if(type == "password"){
        $("#password").attr("type", "text");
        $("#passwordToggler i").removeClass("fa-eye");
        $("#passwordToggler i").addClass("fa-eye-slash");
    }
    else{
        $("#password").attr("type", "password");
        $(".pass-eyes i").removeClass("fa-eye-slash");
        $(".pass-eyes i").addClass("fa-eye");
    }
});