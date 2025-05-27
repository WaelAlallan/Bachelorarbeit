
/**/
var datei_ausgewaehlt = document.getElementById('datei_ausgewaehlt');
var digital_ID = document.getElementById('digital_ID');
const password_toggle = document.getElementById("password_toggle");
const ID_password = document.getElementById("ID_password");

// display name of given ID file
digital_ID.addEventListener('change', function () {
    datei_ausgewaehlt.innerHTML = this.files[0].name;
});

// password toggle to show written password
password_toggle.addEventListener("click", function () {
    const type = ID_password.getAttribute("type") === "password" ? "text" : "password";
    ID_password.setAttribute("type", type); // zeige bzw. verberge das Passwort
    this.classList.toggle("bi-eye"); // icon ändern
});



function validate() {
    var ID_password = document.getElementById('ID_password').value;
    var digital_ID = document.getElementById('digital_ID').value;
    var form = $('#signatur_form')[0];
    var data = new FormData(form);
    var result = false;

    if (ID_password == "" || digital_ID == "") {
        document.getElementById('alert1').style.display = "block";
        return false;
    }

    $.ajax({ // request for password check
        url: "../Verwaltung/pw_check.php",
        type: "post",
        dataType: 'json',
        data: data,
        processData: false, // Important! to prevent jQuery form transforming the data into a query string
        contentType: false,
        cache: false,
        async: false,
        success: function (response) {
            result = response;
        }
    });

    if (result) { // if password korrekt
        return true;
    } else {
        document.getElementById('alert2').style.display = "block";
        return false;
    }
}


function change() {
    document.getElementById('alert1').style.display = "none";
    document.getElementById('alert2').style.display = "none";
}


const PDF_img = "/Bachelorarbeit/images/pdf.png";
const CHECK_img = "/Bachelorarbeit/images/check.png";
// 1. Tabelle
$(document).ready(function () {
    var table1 = $('#tabelle1').DataTable({
        data: daten1,   // var daten1 in verwaltung.php
        columns: [{
            title: ""
        },
        {
            title: "Datei",
        },
        {
            title: "Datum",
            width: "5%",
        },
        {
            title: "Von"
        },
        {
            title: "2. Sign.",
            width: "4%",
        },
        ],
        dom: 'Bfrtip',
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/de_de.json'
        },
        columnDefs: [{
            targets: 0,
            data: null,
            defaultContent: '',
            orderable: false,
            className: 'select-checkbox'
        },
        {
            targets: [1],
            "render": function (data, type, row) {
                return '<img src="' + PDF_img + '">' + '<a href="/Bachelorarbeit/generated_files/' + data + '" style="font-size: 14px;">' + data + '</a>';
            }
        },
        {
            targets: [4],
            "render": function (data, type, row) {
                if (data == true) {
                    return '<img src="' + CHECK_img + '">';
                }
                return '';
            }
        },
        {
            targets: [3],
            "render": function (data, type, row) {
                return '<p style="font-size: 14px;">' + data + '</p>';
            }
        },
        ],
        select: {
            style: 'multi',
            selector: 'tr',
        },

        buttons: [{
            extend: 'selected', // 
            text: ' <b style="font-size: 16px; color:#006c66">Digital unterschreiben</b> ' + '<i class="fas fa-pen-nib" style="font-size: 20px; color:#05918a"></i>',
            action: function (e, dt, button, config) {
                var arr = [];
                var selected = dt.rows({ selected: true }).data();
                for (let i = 0; i < selected.length; i++) {
                    arr[i] = selected[i][1];
                }
                // write selected files into hidden textinput
                document.getElementById('files_to_sign').value = arr;
                dialogOeffnen('signieren-dialog');
            },
        }],
    });
});


// 2. Tabelle
$(document).ready(function () {
    var table2 = $('#tabelle2').DataTable({
        data: daten2,  // var daten1 in verwaltung.php
        columns: [{
            title: "Datei"
        },
        {
            title: "signiert am",
        },
        {
            title: "gesendet von"
        },
        ],
        dom: 'Bfrtip',
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.3/i18n/de_de.json'
        },
        columnDefs: [
            {
                targets: [0],
                "render": function (data, type, row) {
                    return '<img src="' + PDF_img + '">' + '<a href="/Bachelorarbeit/generated_files/' + data + '" style="font-size: 14px;">' + data + '</a>';
                }
            },
            {
                targets: [2],
                "render": function (data, type, row) {
                    return '<p style="font-size: 14px;">' + data + '</p>';
                }
            },
        ],
    });
});