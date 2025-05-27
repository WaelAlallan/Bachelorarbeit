

// datepicker (Feiertage und So./Sa. sind deaktiviert)
$(document).ready(function () {
   var array = "<?php echo json_encode($holidays_JS);  ?>";
   $('#wiederaufnahme').datepicker({
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


function validate_gm() {
   var wiederaufnahme = document.getElementById('wiederaufnahme').value;
 
   document.getElementById('alert1').style.display = "none";
   
   if (wiederaufnahme == '') {
      document.getElementById('alert0').style.display = "block";
      return false;
   }

   if (!aktuellkrank) {
      document.getElementById('alert2').style.display = "block";
      return false;
   }
   return true;
}


function change() {
   var wiederaufnahme = document.getElementById('wiederaufnahme').value;
   var datum_ = new Date(wiederaufnahme);
   var today = new Date();
   var datum = datum_.getFullYear() + '-' + (datum_.getMonth() + 1) + '-' + datum_.getDate();
   var todaydate = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();

   document.getElementById('alert1').style.display = "none";
   document.getElementById('alert2').style.display = "none";

   if (wiederaufnahme != '') {
      document.getElementById('alert0').style.display = "none";
      if (datum != todaydate) {
         document.getElementById('alert1').style.display = "block";
      }
   }
}