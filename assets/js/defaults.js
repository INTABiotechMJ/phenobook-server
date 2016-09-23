
jQuery.extend(jQuery.validator.messages, {
    required: "Required",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Please enter at least {0} characters."),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});

$(".valid").each(function(){
  $(this).validate({
    ignore: ".date"
  });
});



$("input:not(.auto)").attr("autocomplete","off");

$(".select2").select2();


$(".date").mask("99/99/9999");

$(".year").mask("9999");

$(".pickatime").pickatime({
  clear: ""
});
//$(".pickatime").mask("99:99");

$.validator.addMethod(
  "pickadate",
  function ( value, element ) {
    if(value == ""){
      return true;
    }
    return value.match(/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/);
  },
  "Escriba una fecha válida"
  );


$(".ask").click(function(){
  var what = $(this).data("what");
  var href = $(this).data("href");
  bootbox.confirm({
    message: what,
    buttons: {
      cancel: {
        label: "No"
      },
      confirm: {
        label: "Si"
      }
    },
    callback: function(result) {
      if (result) {
        window.location.href = href;
      }
    }
  });
});


jQuery.extend({
 postJSON: function( url, data, callback) {
  return jQuery.post(url, data, callback, "json");
}
});

function redirect(url){
  window.location = url;
}

String.prototype.toHHMMSS = function () {
  var sec_num = parseInt(this, 10);
  var hours   = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
  var seconds = sec_num - (hours * 3600) - (minutes * 60);

  if (hours   < 10) {hours   = "0"+hours;}
  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  var time    = hours+':'+minutes+':'+seconds;
  return time;
}


$(".export").on('click', function (event) {
  // CSV
  var $table = $(this).parents("table");
  exportTableToCSV.apply(this, [$table, 'export.csv']);
  // IF CSV, don't do event.preventDefault() or return false
  // We actually need this to be a typical hyperlink
});

function exportTableToCSV($table, filename) {

  var $rows = $table.find('tr:has(td):not(.trfooter), tr:has(th)'),

  // Temporary delimiter characters unlikely to be typed by keyboard
  // This is to avoid accidentally splitting the actual contents
  tmpColDelim = String.fromCharCode(11), // vertical tab character
  tmpRowDelim = String.fromCharCode(0), // null character

  // actual delimiter characters for CSV format
  colDelim = '";"',
  rowDelim = '"\r\n"',

  // Grab text from table into CSV formatted string
  csv = '"' + $rows.map(function (i, row) {
    var $row = $(row),
    $cols = $row.find('td, th');

    return $cols.map(function (j, col) {
      var $col = $(col),
      text = $col.text();

      return text.replace('"', '""'); // escape double quotes

    }).get().join(tmpColDelim);

  }).get().join(tmpRowDelim)
  .split(tmpRowDelim).join(rowDelim)
  .split(tmpColDelim).join(colDelim) + '"',

  // Data URI
  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

  $(this)
  .attr({
    'download': filename,
    'href': csvData,
    'target': '_blank'
  });
}


$(".python-exec").each(function(ele){
  var ele = $(this);
  var src = $(this).data("src");
  var params = $(this).data("params");
  ele.html("Cargando...");
  $.ajax({
    url: URL + "logic/python/exec.php?src=" + src + "&" + params
  })
  .done(function( data ) {
    ele.html(data);
  });
});

function tab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}

$(".select-multiple").select2();




$(".int").keydown(function (e) {
  // Allow: backspace, delete, tab, escape, enter and f5
  if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 116]) !== -1 ||
   // Allow: Ctrl+A
   (e.keyCode == 65 && e.ctrlKey === true) ||
   // Allow: home, end, left, right
   (e.keyCode >= 35 && e.keyCode <= 39)) {
   // let it happen, don't do anything
 return;
}
  // Ensure that it is a number and stop the keypress
  if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
    e.preventDefault();
  }
});

$(".float").keydown(function (e) {
  if($(this).val().indexOf(".") != -1 && e.keyCode == 190){ // . only once
    e.preventDefault();
    return;
  }
  // Allow: backspace, delete, tab, escape, enter . and f5
  if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 116]) !== -1 ||
   // Allow: Ctrl+A
   (e.keyCode == 65 && e.ctrlKey === true) ||
   // Allow: home, end, left, right
   (e.keyCode >= 35 && e.keyCode <= 39)) {
   // let it happen, don't do anything
 return;
}
  // Ensure that it is a number and stop the keypress
  if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
    e.preventDefault();
  }
});




$('.sercheable').keyup(function() {
  var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
  reg = RegExp(val, 'i'),
  text;
  var $rows = $('table.'+$(this).data("target") + " tr");
  console.log($(this).data("target"));
  $rows.show().filter(function() {
    text = $(this).text().replace(/\s+/g, ' ');
    return !reg.test(text);
}).hide();
});




$.fn.shiftSelectable = function() {
    var lastChecked,
        $boxes = this;

    $boxes.click(function(evt) {
        if(!lastChecked) {
            lastChecked = this;
            return;
        }

        if(evt.shiftKey) {
            var start = $boxes.index(this),
                end = $boxes.index(lastChecked);
            $boxes.slice(Math.min(start, end), Math.max(start, end) + 1)
                .attr('checked', lastChecked.checked)
                .trigger('change');
        }

        lastChecked = this;
    });
};
