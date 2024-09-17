$(document).ready(function () {
  var $rows = $(".table-data-row tr");
  $("#myInput").keyup(function () {
    var val =
        "^(?=.*\\b" +
        $.trim($(this).val()).split(/\s+/).join("\\b)(?=.*\\b") +
        ").*$",
      reg = RegExp(val, "i"),
      text;

    $rows
      .show()
      .filter(function () {
        text = $(this).text().replace(/\s+/g, " ");
        return !reg.test(text);
      })
      .hide();
  });
});

function closeSidebar() {
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");

  sidebar.classList.toggle("open");
  if (sidebar.classList.contains("open")) {
    closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
  } else {
    closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
  }
}

var popoverTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="popover"]')
);
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl);
});
var tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});
function copyElement(containerid) {
  var copyText = $(containerid).text();
  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText);
  $.toast({
    text: "Text copied to clipboard",
    position: "bottom-center",
    stack: 2,
    hideAfter: 1000,
    bgColor: "#15181c",
    loader: false,
    // loaderBg: "#1DA1F2",
  });
}
function copyData(containerid) {
  var copyText = containerid;
  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText);
  $.toast({
    text: "Text copied to clipboard",
    position: "bottom-center",
    stack: 2,
    hideAfter: 1000,
    bgColor: "#15181c",
    loader: false,
    // loaderBg: "#1DA1F2",
  });
}
// the selector will match all input controls of type :checkbox
// and attach a click event handler
$("input:checkbox").on("click", function () {
  // in the handler, 'this' refers to the box clicked on
  var $box = $(this);
  if ($box.is(":checked")) {
    // the name of the box is retrieved using the .attr() method
    // as it is assumed and expected to be immutable
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    // the checked state of the group/box on the other hand will change
    // and the current value is retrieved using .prop() method
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }
});
function exportTableToExcel(tableid, fileName) {
  $("#exportButton").prop("disabled", true);
  $("#exportButton").html(
    "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading..."
  );
  var SpreadsheetName = fileName + ".csv";
  var tableID = tableid;
  $(tableID).table2csv({
    file_name: SpreadsheetName,
    header_body_space: 0,
  });
  $("#exportButtonArea").load(" #exportButtonArea");
}

$(document).on("click", "table thead tr th:not(.no-sort)", function () {
  var table = $(this).parents("table");
  var rows = $(this)
    .parents("table")
    .find("tbody tr")
    .toArray()
    .sort(TableComparer($(this).index()));
  var dir = $(this).hasClass("sort-asc") ? "desc" : "asc";

  if (dir == "desc") {
    rows = rows.reverse();
  }

  for (var i = 0; i < rows.length; i++) {
    table.append(rows[i]);
  }

  table.find("thead tr th").removeClass("sort-asc").removeClass("sort-desc");
  $(this)
    .removeClass("sort-asc")
    .removeClass("sort-desc")
    .addClass("sort-" + dir);
});

function TableComparer(index) {
  return function (a, b) {
    var val_a = TableCellValue(a, index);
    var val_b = TableCellValue(b, index);
    var result =
      $.isNumeric(val_a) && $.isNumeric(val_b)
        ? val_a - val_b
        : val_a.toString().localeCompare(val_b);

    return result;
  };
}

function TableCellValue(row, index) {
  return $(row).children("td").eq(index).text();
}

function deleteBtnOC(stdID) {
  $("#deleteElementButton").val(stdID);
  $("#deleteElement").modal("show");
}
function disableBtnOC(disableStdIDToken) {
  var disableStdID = disableStdIDToken;
  $.post(
    "dbh/manageAccount",
    { DisablestdID: disableStdID },
    function (data, status) {
      $(".table-data-row").load(" .table-data-row > *");
    }
  );
}
function EnableBtnOC(enableStdIDToken) {
  var enableStdID = enableStdIDToken;
  $.post(
    "dbh/manageAccount",
    { EnablestdID: enableStdID },
    function (data, status) {
      $(".table-data-row").load(" .table-data-row > *");
    }
  );
}
function ApprovePayment(id) {
  if (window.confirm("Are you sure in approving this payment?")) {
    var paymentID = id;
    $("#manage" + paymentID).html(
      "<span class='spinner-border spinner-border-sm text-white' role='status' aria-hidden='true'></span> Loading..."
    );
    $.post(
      "dbh/updatePayment",
      { ApprovePaymentID: paymentID },
      function (data, status) {
        $(".table-data-row").load(" .table-data-row > *");
        $("#statusToast").load(" #statusToast > *");
        console.log(data)
      }
    );
  }
}
function RejectPayment(id) {
  if (window.confirm("Are you sure in rejecting this payment?")) {
    var paymentID = id;
    $("#manage" + paymentID).html(
      "<span class='spinner-border spinner-border-sm text-white' role='status' aria-hidden='true'></span> Loading..."
    );
    $.post(
      "dbh/updatePayment",
      { RejectPaymentID: paymentID },
      function (data, status) {
        $(".table-data-row").load(" .table-data-row > *");
      }
    );
  }
}
function HaltAccess(id) {
  if (window.confirm("Are you sure in halting the meeting access of this student?")) {
    var orderID = id;
    $("#orderLesson" + orderID).html(
      "<span class='spinner-border spinner-border-sm text-white' role='status' aria-hidden='true'></span> Loading..."
    );
    $.post(
      "dbh/updateOrderStatus",
      { HaltAccess: orderID },
      function (data, status) {
        $(".table-data-row").load(" .table-data-row > *");
      }
    );
  }
}
function EnableAccess(id) {
  if (window.confirm("Are you sure in enabling the meeting access of this student?")) {
    var orderID = id;
    $("#orderLesson" + orderID).html(
      "<span class='spinner-border spinner-border-sm text-white' role='status' aria-hidden='true'></span> Loading..."
    );
    $.post(
      "dbh/updateOrderStatus",
      { EnableAccess: orderID },
      function (data, status) {
        $(".table-data-row").load(" .table-data-row > *");
      }
    );
  }
}