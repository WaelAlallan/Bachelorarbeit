

// datepicker (Feiertage und So./Sa. sind deaktiviert)
$(document).ready(function () {
   var array = "<?php echo json_encode($holidays_JS);  ?>";
   $('#AU_beginn_tag, #AU_bis').datepicker({
      dateFormat: 'yy-mm-dd',
      firstDay: 1,
      monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
      dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
      beforeShowDay: function (date) {
         var string = jQuery.datepicker.formatDate('dd.mm.yy', date);
         var show = true;
         if (date.getDay() == 6 || date.getDay() == 0) show = false
         return [array.indexOf(string) == -1 && show]; // 
      }
   });
});


var check_res = false;

// sende die Daten zur KM in einem Request und prüfe ob eine KM/Urlaub vorliegt.
function check_KM() {
   var date1 = document.getElementById('AU_beginn_tag').value;
   var date2 = document.getElementById('AU_bis').value;

   //* falls nur date1 eingegeben (aktuellkrank/nicht)
   if (date1 != '' && date2 == '') {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
         if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);

            if (myObj[0]) {
               document.getElementById('alert9').style.display = "block";
               check_res = true;
            } else {
               document.getElementById('alert9').style.display = "none";
               check_res = false; 
            }
         }
      };
      xmlhttp.open("GET", "backend_krank.php?date1=" + date1 + "&date2=" + date2, false); // Request ist synchron
      xmlhttp.send();
   }

   //* if both dates are set 
   if (date1 != "" && date2 != "") {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
         if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);

            if (myObj[0]) {
               document.getElementById('alert9').style.display = "block";
               check_res = true;
            } else {
               document.getElementById('alert9').style.display = "none";
               check_res = false; 
            }
         }
      };
      xmlhttp.open("GET", "backend_krank.php?date1=" + date1 + "&date2=" + date2, false);
      xmlhttp.send();
   }
   return check_res;
}



// wenn radiobutton ob aktuellkrank/nicht checked
function ist_krank() {
   var aktuellkrank = document.getElementById('radiobtn_ja').checked;
   document.getElementById('verlassen_um').disabled = aktuellkrank;
   document.getElementById('radiobtn2').disabled = aktuellkrank;

   if (aktuellkrank) {
      document.getElementById('AU_beginn_tag').value = "";
      document.getElementById('datum').style.display = "none";
      document.getElementById('radiobtn2').checked = false;
      document.getElementById('verlassen_um').value = "";
      document.getElementById('label_radiobtn2').style.color = '#8c9598';
   } else {
      document.getElementById('datum').style.display = "block";
      document.getElementById('label_radiobtn2').style.color = '#696b72';
   }
}


// func. zur Radiobutton Validation
function validateRadio(radiogroup) {
   var radios = document.getElementsByName(radiogroup);
   var valid = false;

   var i = 0;
   while (!valid && i < radios.length) {
      if (radios[i].checked) valid = true;
      i++;
   }
   return valid;
}


 
function validate_km() {
   var radiobtn2 = document.getElementById('radiobtn2').checked;
   var aktuellkrank = document.getElementById('radiobtn_ja').checked;
   var nichtkrank = document.getElementById('radiobtn_nein').checked;
   var AU_beginn_tag = document.getElementById('AU_beginn_tag').value;
   var AU_bis = document.getElementById('AU_bis').value;
   var verlassen_um = document.getElementById('verlassen_um').value;

   if (!validateRadio("radiogroup1")) {
      document.getElementById('alert1').style.display = "block";
      return false;
   }
   if (!validateRadio("radiogroup2")) {
      document.getElementById('alert2').style.display = "block";
      return false;
   }
   if (AU_beginn_tag == '' && !aktuellkrank) {
      document.getElementById('alert4').style.display = "block";
      return false;
   }
   if (AU_bis == '' && aktuellkrank) {
      document.getElementById('alert5').style.display = "block";
      return false;
   }
   if (aktuellkrank && !cond) {
      document.getElementById('alert6').style.display = "block";
      return false;
   }
   if (nichtkrank && cond) {
      document.getElementById('alert7').style.display = "block";
      return false;
   }
   if (AU_beginn_tag != '' && AU_bis != '') { // dates comparison: ==, !=, ===, and !== operators require to use date.getTime()
      var start = new Date(AU_beginn_tag);
      var end = new Date(AU_bis);

      if (start > end) {
         document.getElementById('alert8').style.display = "block";
         return false;
      }
   }
   if (radiobtn2 && verlassen_um == '') {
      document.getElementById('alert3').style.display = "block";
      return false;
   }
   if (aktuellkrank && AU_bis <= aktuellkrank_bis) {
      document.getElementById('alert10').style.display = "block";
      return false;
   }

   if (check_KM()) {
      return false;
   }

   return true;
}


// bei Änderungen des Formulars
function change() {
   var AU_beginn_tag = document.getElementById('AU_beginn_tag').value;
   var AU_bis = document.getElementById('AU_bis').value;
   var aktuellkrank = document.getElementById('radiobtn_ja').checked;
   var nichtkrank = document.getElementById('radiobtn_nein').checked;

   if (validateRadio("radiogroup1"))
      document.getElementById('alert1').style.display = "none";

   if (validateRadio("radiogroup2"))
      document.getElementById('alert2').style.display = "none";

   if (AU_beginn_tag != '')
      document.getElementById('alert4').style.display = "none";

   if (AU_bis != "")
      document.getElementById('alert5').style.display = "none";

   if (verlassen_um != '')
      document.getElementById('alert3').style.display = "none";

   if (aktuellkrank || nichtkrank) { // falls Auswahl geändert
      document.getElementById('alert6').style.display = "none";
      document.getElementById('alert7').style.display = "none";
      document.getElementById('alert9').style.display = "none";
   }

   if (aktuellkrank && AU_bis > aktuellkrank_bis)
      document.getElementById('alert10').style.display = "none";

   document.getElementById('alert8').style.display = "none";
}
