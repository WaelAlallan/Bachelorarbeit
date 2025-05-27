

 // datepicker (Feiertage und So./Sa. sind deaktiviert)
 $(document).ready(function() {
   var array = "<?php echo json_encode($holidays_JS);  ?>";
   $('#urlaub_beginn, #urlaub_ende').datepicker({
      dateFormat: 'yy-mm-dd',
      firstDay: 1,
      monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
      dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
      beforeShowDay: function(date) {
         var string = jQuery.datepicker.formatDate('dd.mm.yy', date);
         var show = true;
         if (date.getDay() == 6 || date.getDay() == 0) show = false
         return [array.indexOf(string) == -1 && show]; // 
      }
   });
});


var datei_ausgewaehlt = document.getElementById('datei_ausgewaehlt');
var digital_ID = document.getElementById('digital_ID');
const password_toggle = document.getElementById("password_toggle");
const ID_password = document.getElementById("ID_password");
 
// display name of given ID file in dialog
digital_ID.addEventListener('change', function () {
   datei_ausgewaehlt.innerHTML = this.files[0].name;
});

// password toggle to show written password in dialog
password_toggle.addEventListener("click", function () {
   const type = ID_password.getAttribute("type") === "password" ? "text" : "password";
   ID_password.setAttribute("type", type); // zeige bzw. verberge das Passwort
   this.classList.toggle("bi-eye"); // icon ändern
});



var urlaub_result = false;

// sende die Urlaubsdaten in einem Request zum Berechnen der Urlaubsdauer, und prüfe ob KM/Urlaub vorliegt.
function calc_urlaub() {
   var date1 = document.getElementById('urlaub_beginn').value;
   var date2 = document.getElementById('urlaub_ende').value;

   //* if both dates are set 
   if (date1 != "" && date2 != "") {

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {

         if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);
            document.getElementById('urlaub_dauer').value = myObj[0];
            if (myObj[1] < 0) {
               document.getElementById('urlaub_saldo').value = "";
               document.getElementById('alert4').style.display = "block";
               urlaub_result = true;
            } else {
               document.getElementById('urlaub_saldo').value = myObj[1];
               document.getElementById('alert4').style.display = "none";
               if (myObj[2]) {
                  document.getElementById('alert6').style.display = "block";
                  urlaub_result = true;
               } else {
                  document.getElementById('alert6').style.display = "none";
                  if (myObj[3]) {
                     document.getElementById('alert5').style.display = "block";
                     urlaub_result = true;
                  } else {
                     urlaub_result = false;
                     document.getElementById('alert5').style.display = "none";
                  }
               }
            }
         }
      };
      xmlhttp.open("GET", "../Urlaubsantrag/backend_urlaub.php?date1=" + date1 + "&date2=" + date2, false); // Request ist synchron
      xmlhttp.send();
   }
   //* if only one date is set
   else {
      document.getElementById('urlaub_dauer').value = "";
      document.getElementById('urlaub_saldo').value = "";
   }
   return urlaub_result;
}


//beim Klick auf 'Signieren & Absenden' werden die Eingaben validiert und es erschein ein Dialog
function validate() {
   var date1 = document.getElementById('urlaub_beginn').value;
   var date2 = document.getElementById('urlaub_ende').value;
   var vertreter = document.getElementById('vertreter').value;
   var start = new Date(date1);
   var end = new Date(date2);

   if (date1 == "" || date2 == "") {
      document.getElementById('alert1').style.display = "block";
      return false;
   }
   if (start > end) {
      document.getElementById('alert3').style.display = "block";
      return false;
   }
   if (vertreter == "") {
      document.getElementById('alert2').style.display = "block";
      return false;
   }

   if (calc_urlaub()) {
      return false;
   }

   dialogOeffnen('signieren-dialog');
   return true;
}

// Validation der Eingaben 
function validate2() {
   var ID_password = document.getElementById('ID_password').value;
   var digital_ID = document.getElementById('digital_ID').value;
   var form = $('#urlaubsformular')[0];
   var data = new FormData(form);
   var result = false;

   if (ID_password == "" || digital_ID == "") {
      document.getElementById('dialog_alert1').style.display = "block";
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
   }
   else {
      document.getElementById('dialog_alert2').style.display = "block";
      return false;
   }
}


// bei Änderung des Webformulars
function change() {
   document.getElementById('alert1').style.display = "none";
   document.getElementById('alert2').style.display = "none";
   document.getElementById('alert3').style.display = "none";
   document.getElementById('alert4').style.display = "none";
   document.getElementById('alert5').style.display = "none";
   document.getElementById('alert6').style.display = "none";
}


function change2() {
   document.getElementById('dialog_alert1').style.display = "none";
   document.getElementById('dialog_alert2').style.display = "none";
}
