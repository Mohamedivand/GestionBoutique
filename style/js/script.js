// const body = document.querySelector("body"),
//     modeToggle = body.querySelector(".mode-toggle");
// sidebar = body.querySelector("nav");
// sidebarToggle = body.querySelector(".sidebar-toggle");

// let getMode = localStorage.getItem("mode");
// if (getMode && getMode === "dark") {
//     body.classList.toggle("dark");
// }

// let getStatus = localStorage.getItem("status");
// if (getStatus && getStatus === "close") {
//     sidebar.classList.toggle("close");
// }

// sidebarToggle.addEventListener("click", () => {
//     sidebar.classList.toggle("close");
//     if (sidebar.classList.contains("close")) {
//         localStorage.setItem("status", "close");
//     } else {
//         localStorage.setItem("status", "open");
//     }
// })

function selectOnNavbar(elName) {
    $(".nav-links li").removeClass("nav-links-active");
    $(`.${elName}`).addClass("nav-links-active");
}

function activeLoader() {
    $(".popup").removeClass("popupInactive");
}

function desactiveLoader() {
    $(".popup").addClass("popupInactive");
}
function resetForm() {
    window.location.reload(true)
}

var searchInput = '';
$(".searchInput").on("input", function () {
    $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    searchInput = $(this).val();
    $('tbody').children().hide(300);

    if (searchInput == '') {
        $('tbody').children().show(300);
    }
    else {
        $('tbody').children(`tr:contains("${searchInput}")`).show(300);
    }
});

function tableFilter() {
    let selected = $("#dateSelect option:selected").val();
    let date = new Date().toLocaleDateString('fr-fr');
    let twoDigitMonth = ((date.getMonth().length + 1) === 1) ? (date.getMonth() + 1) : '0' + (date.getMonth() + 1);
    if (selected == 1) {
        $('tbody').children().show(300);
    }
    if (selected == 2) {
        let currentDate = date.getFullYear() + "-" + twoDigitMonth + "-" + date.getDate();
        $('tbody').children().hide(300);
        if (currentDate == '') {
            $('tbody').children().show(300);
        }
        else {
            $('tbody').children(`tr:contains("${currentDate}")`).show(300);
        }
    }
    if (selected == 3) {
        let currentDate = date.getFullYear() + "-" + twoDigitMonth;
        $('tbody').children().hide(300);
        if (currentDate == '') {
            $('tbody').children().show(300);
        }
        else {
            $('tbody').children(`tr:contains("${currentDate}")`).show(300);
        }
    }
    if (selected == 4) {
        let currentDate = date.getFullYear();
        $('tbody').children().hide(300);
        if (currentDate == '') {
            $('tbody').children().show(300);
        }
        else {
            $('tbody').children(`tr:contains("${currentDate}")`).show(300);
        }
    }
}

const cfa = new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XOF',
    // minimumFractionDigits: 3
});

function addHours(date, hours) {
    date.setTime(date.getTime() + hours * 60 * 60 * 1000);

    return date;
}

function date2Digit(date) {
    return (date < 10) ? ("0" + date) : date
}

function verifieMdp() {
    var i = 0
    let mdp = prompt("Veuillez entrer votre mot de passe pour effecteur cette action: ")
    if (mdp != false) {
        $.ajax({
            type: "POST",
            url: "../../api/auth/checkMdp.php",
            data: {
                token: "djessyaroma1234",
                mdp: mdp
            },
            success: function () {
                i++
            }
        });
    }
    if (i == 1) {
        alert(i)
        return true
    } else {
        return false
    }
}

function verifierMotDePasse(mdp){
    let res = false;
    $.ajax({
        async : false,
        type: "POST",
        url: "../../api/auth/checkMdp.php",
        data: {
            token: "djessyaroma1234",
            mdp: mdp
        },
        success: function () {
            res = true
        }
    });

    return res;
}

function cc_format(value) {
    var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
    var matches = v.match(/\d{4,16}/g);
    var match = matches && matches[0] || ''
    var parts = []

    for (i=0, len=match.length; i<len; i+=4) {
        parts.push(match.substring(i, i+4))
    }

    if (parts.length) {
        return parts.join(' ')
    } else {
        return value
    }
}

function saveComponentASPNG(){
    html2canvas(document.getElementById("contenuBar")).then(function (canvas) {
        let anchorTag = document.getElementById("downloadImg");
        anchorTag.href = canvas.toDataURL();
        anchorTag.click();
    });
}

