$("#toolBarCloser").click(function (e) { 
    if($(this).attr("data-isOpen") == "false"){
        $("#toolBar").slideDown();
        $("#toolBarCloser").html(`<i class="fa fa-arrow-up"></i>`);

        $(this).attr("data-isOpen", "true");
    }
    else{
        $("#toolBar").slideUp();

        $("#toolBarCloser").html(`<i class="fa fa-arrow-down"></i>`);

        $(this).attr("data-isOpen", "false");
    }
});

$("#toolBarCloser").trigger("click")