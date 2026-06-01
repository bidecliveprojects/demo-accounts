// // Wait until the document is fully loaded
// $(document).ready(function () {
//   // Apply Select2 to all elements with the 'select2' class
//   $('.select2').select2({
//     placeholder: "Select an option",
//     allowClear: true,
//     width: '100%' // Ensures dropdown fits the container width
//   });
// });

//Start Print
function printView(param1, param2, param3) {
  $(".qrCodeDiv").removeClass("hidden");
  $("div").removeClass("well");
  $(".pdfClass").removeClass("hidden");
  $(".pdfClass").removeClass("hidden");
  $('.table-responsive').removeClass('overflowscroll');
  $('.table-responsive').removeClass('wrapper');
  $('.d-print-inline-block').removeClass('d-none d-print-inline-block');
  var printContents = document.getElementById(param1).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  //if(param3 == 1){
  location.reload();
  //}
}

function printViewTwo(param1, param2, param3) {
  $(".qrCodeDiv").removeClass("hidden");
  $("div").removeClass("well");
  var printContents = document.getElementById(param1).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;

  $("#showDetailModelOneParamerter").modal("toggle");
}

function ExportToExcelTwo(name, id) {
  var table = $('#' + id + '');
  $(table).table2excel({
    // exclude CSS class
    exclude: '.noExl',
    name: name,
    filename: '' + name + '_' + $.now(),//do not include extension
    fileext: '.xls', // file extension
    preserveColors: true,
    exclude_links: true,
    sheetName: '' + name + '_'
  });
}

function changeAccountingYear() {
  var changeAYearValue = $("#changeAYear").val();
  var changeAYearText = $("#changeAYear option:selected").text();
  var changeAYearTextSplit = changeAYearText.split(" <--> ");
  var fromDateOld = changeAYearTextSplit[0];
  var toDateOld = changeAYearTextSplit[1];

  var fromDate = fromDateOld
    .split("-")
    .reverse()
    .join("-");
  var toDate = toDateOld
    .split("-")
    .reverse()
    .join("-");
  var baseUrl = $("#url").val();

  $.ajax({
    url: "" + baseUrl + "/updateAccountingYearInSession",
    method: "GET",
    data: {
      fromDate: fromDate,
      toDate: toDate,
      changeAYearValue: changeAYearValue
    },
    error: function () {
      alert("error");
    },
    success: function (response) {
      location.reload();
    }
  });
}

function updateStartEndField() {
  $("#startRecordNo").val("0");
  $("#endRecordNo").val("30");
}

var validate = 0;
function jqueryValidationCustom() {
  var requiredField = document.getElementsByClassName("requiredField");
  // console.log(requiredField);
  for (i = 0; i < requiredField.length; i++) {
    var rf = requiredField[i].id;
    // console.log(rf);
    var checkType = requiredField[i].type;
    console.log(checkType);
    if ($("#" + rf).val() == "") {
      $("#" + rf).css("border-color", "red !important");
      $("#" + rf).focus();
      // validate = 1;

      if ($("#" + rf).attr('disabled')) {
        validate = 0
      } else {
        validate = 1;
      }
      alert(rf + ' ' + 'Required');
      return false;
    } else {
      $("#submit-btn-abc").prop("disabled", false);
      $("#" + rf).css("border-color", "#ccc");
      // console.log($("#" + rf).attr('disabled'));
      validate = 0;
      console.log('in2');
    }
  }
  return validate;
}

function dashboardPrint(param1, param2, param3) {
  var printedVaues = [];
  var printContents1;
  var originalContents;
  var chaljao = [];
  $(".qrCodeDiv").removeClass("hidden");
  $("div").removeClass("well");
  $(".collectionRefund").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");
  $(".collectionRefund").addClass("col-lg-5 col-md-5 col-sm-5 col-xs-5");

  $(".bookingFunction").removeClass("col-lg-12 col-md-12 col-sm-12 col-xs-12");
  $(".bookingFunction").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3");

  $(".bookingOrder").removeClass("col-lg-6 col-md-6 col-sm-6 col-xs-12");
  $(".bookingOrder").addClass("col-lg-6 col-md-6 col-sm-6 col-xs-6");

  $(".bookingCollection").removeClass("col-lg-3 col-md-3 col-sm-3 col-xs-12");
  $(".bookingCollection").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3");

  $(".bookingRefund").removeClass("col-lg-3 col-md-3 col-sm-3 col-xs-12");
  $(".bookingRefund").addClass("col-lg-3 col-md-3 col-sm-3 col-xs-3");

  var printDate = document.getElementById("printDateCheck").innerHTML;
  for (i = 0; i < param1.length; i++) {
    printedVaues.push(param1[i]);
  }
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printDate + getElementById(printedVaues);
  window.print();
  document.body.innerHTML = originalContents;
  //if(param3 == 1){
  location.reload();
  //}

  function getElementById(ids) {
    var testing = [];
    var idList = ids.join(" ");
    var strArray = idList.split(" ");
    var results = [],
      item;
    for (var i = 0; i < strArray.length; i++) {
      //alert(strArray[i]);
      item = document.getElementById(strArray[i]).innerHTML;
      console.log(item);
      chaljao.push(item);
    }

    return chaljao;
  }
}

function singleDateWisePrint(param1, param2, param3, param4) {
  var checkedValues = [];
  var unCheckedValues = [];
  var printContents1;
  var originalContents;
  var chaljao = [];

  for (i = 0; i < param2.length; i++) {
    checkedValues.push(param2[i]);
    var finalCheckValues = checkedValues.join(" ");
    $("#" + finalCheckValues).addClass("visible-print");
  }
  for (i = 0; i < param3.length; i++) {
    unCheckedValues.push(param3[i]);
    var finalUnCheckValues = unCheckedValues.join(" ");
    $("#" + param3[i]).addClass("hidden-print");
  }

  var printContents = document.getElementById(param1).innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  //if(param3 == 1){
  location.reload();
  //}
}

//End Print

//Start Generate PDF / CSV (full dataset for server-side DataTables)
function isServerSideDataTable(tableId) {
  var tableElement = $('#' + tableId);
  return $.fn.dataTable.isDataTable(tableElement) && tableElement.DataTable().settings()[0].oFeatures.bServerSide;
}

function getExportColumns(tableId) {
  return $('#' + tableId).data('export-columns') || [];
}

function shouldExportColumn(column) {
  var cls = column.class || column.className || '';
  if (typeof cls === 'string' && cls.indexOf('hidden-print') >= 0) {
    return false;
  }
  if (column.data === 'action') {
    return false;
  }
  var title = (column.title || '').toLowerCase();
  return title !== 'action';
}

function stripHtml(html) {
  if (html === null || html === undefined) {
    return '';
  }
  var text = String(html);
  if (text.indexOf('<') === -1) {
    return text.trim();
  }
  var tmp = document.createElement('div');
  tmp.innerHTML = text;
  tmp.querySelectorAll('img').forEach(function (img) {
    var src = img.getAttribute('src') || '';
    img.replaceWith(document.createTextNode(src || '[Image]'));
  });
  return (tmp.textContent || tmp.innerText || '').replace(/\s+/g, ' ').trim();
}

function renderExportCell(column, row, rowIndex) {
  var cellData = column.data === null || column.data === undefined ? row : row[column.data];
  if (column.render) {
    return stripHtml(column.render(cellData, 'export', row, { row: rowIndex }));
  }
  return stripHtml(cellData);
}

function escapeCsvField(value) {
  var text = String(value == null ? '' : value);
  text = text.replace(/"/g, '""');
  return '"' + text + '"';
}

function downloadTextFile(filename, content, mimeType) {
  var blob = new Blob([content], { type: mimeType || 'text/csv;charset=utf-8;' });
  var link = document.createElement('a');
  if (link.download !== undefined) {
    var url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  }
}

function fetchAllDataTableRows(tableId) {
  var tableElement = $('#' + tableId);
  var dt = tableElement.DataTable();
  var params = $.extend(true, {}, dt.ajax.params());
  params.start = 0;
  params.length = -1;

  return $.ajax({
    url: dt.ajax.url(),
    type: 'GET',
    data: params,
    dataType: 'json'
  }).then(function (response) {
    return response.data || [];
  });
}

function getCurrentPageDataTableRows(tableId) {
  return $('#' + tableId).DataTable().rows({ page: 'current' }).data().toArray();
}

function promptExportScope() {
  if (typeof Swal === 'undefined') {
    return $.when(window.confirm('Export complete data? Click OK for all records, Cancel for current page only.') ? 'complete' : 'current');
  }

  return Swal.fire({
    title: 'Export options',
    text: 'Which records do you want to export?',
    icon: 'question',
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonText: 'Complete data',
    denyButtonText: 'Current page',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#3085d6',
    denyButtonColor: '#6c757d'
  }).then(function (result) {
    if (result.isConfirmed) {
      return 'complete';
    }
    if (result.isDenied) {
      return 'current';
    }
    return null;
  });
}

function exportDomTableToCSV(tableId, fileName) {
  $('.hidden-print').remove();
  $('.hideExportTwo').remove();
  $('#' + tableId).tableHTMLExport({ type: 'csv', filename: fileName + '.csv' });
  location.reload();
}

function exportDomTableToPDF(tableId, fileName) {
  $('.hidden-print').remove();
  $('.pdfClass').removeClass('hidden');

  var table = document.getElementById(tableId);
  if (!table) {
    console.error("Table with ID '" + tableId + "' not found.");
    setExportButtonsLoading(false);
    return;
  }

  domtoimage.toPng(table, { bgcolor: '#ffffff' }).then(function (dataUrl) {
    var img = new Image();
    img.src = dataUrl;
    img.onload = function () {
      var pdf = new jsPDF('l', 'pt', [img.width, img.height]);
      pdf.addImage(img, 'PNG', 0, 0, img.width, img.height);
      pdf.save(fileName + '.pdf');
      location.reload();
    };
  }).catch(function (error) {
    console.error('Error generating PDF:', error);
    setExportButtonsLoading(false);
  });
}

function buildCsvFromDataTableRows(tableId, rows) {
  var columns = getExportColumns(tableId).filter(shouldExportColumn);
  var lines = [columns.map(function (col) {
    return escapeCsvField(col.title || '');
  }).join(',')];

  rows.forEach(function (row, idx) {
    lines.push(columns.map(function (col) {
      return escapeCsvField(renderExportCell(col, row, idx));
    }).join(','));
  });

  return lines.join('\n');
}

function buildFullExportTableElement(tableId, rows) {
  var original = document.getElementById(tableId);
  var columns = getExportColumns(tableId).filter(shouldExportColumn);
  var table = document.createElement('table');
  table.className = original.className;
  table.style.background = '#fff';

  var thead = document.createElement('thead');
  var originalThead = original.querySelector('thead');
  if (originalThead) {
    originalThead.querySelectorAll('tr.pdfClass, tr.hideExportTwo').forEach(function (tr) {
      thead.appendChild(tr.cloneNode(true));
    });
  }

  var headerRow = document.createElement('tr');
  columns.forEach(function (col) {
    var th = document.createElement('th');
    th.className = 'text-center';
    th.textContent = col.title || '';
    headerRow.appendChild(th);
  });
  thead.appendChild(headerRow);
  table.appendChild(thead);

  var tbody = document.createElement('tbody');
  rows.forEach(function (row, idx) {
    var tr = document.createElement('tr');
    columns.forEach(function (col) {
      var td = document.createElement('td');
      td.className = 'text-center';
      td.textContent = renderExportCell(col, row, idx);
      tr.appendChild(td);
    });
    tbody.appendChild(tr);
  });
  table.appendChild(tbody);

  return table;
}

function setExportButtonsLoading(isLoading) {
  $('#csv, #pdf').prop('disabled', isLoading);
}

function refreshTableDataForExport() {
  var form = $('#list_data');
  if (!form.length) {
    return $.when();
  }
  return $.ajax({
    type: 'get',
    url: form.attr('action'),
    data: form.serialize(),
    cache: false
  }).then(function (html) {
    $('#data').html(html);
  });
}

function exportServerSideDataTable(tableId, fileName, format, scope) {
  setExportButtonsLoading(true);

  var rowsPromise = scope === 'current'
    ? $.when(getCurrentPageDataTableRows(tableId))
    : fetchAllDataTableRows(tableId);

  rowsPromise.done(function (rows) {
    if (!rows.length) {
      alert('No records found to export.');
      return;
    }

    if (format === 'csv') {
      downloadTextFile(fileName + '.csv', buildCsvFromDataTableRows(tableId, rows));
      return;
    }

    var exportTable = buildFullExportTableElement(tableId, rows);
    var wrapper = document.createElement('div');
    wrapper.style.cssText = 'position:absolute;left:-10000px;top:0;background:#fff;padding:8px;';
    wrapper.appendChild(exportTable);
    document.body.appendChild(wrapper);

    $(exportTable).find('.pdfClass').removeClass('hidden');

    domtoimage.toPng(exportTable, { bgcolor: '#ffffff' }).then(function (dataUrl) {
      var img = new Image();
      img.src = dataUrl;
      img.onload = function () {
        var pdf = new jsPDF('l', 'pt', [img.width, img.height]);
        pdf.addImage(img, 'PNG', 0, 0, img.width, img.height);
        pdf.save(fileName + '.pdf');
        document.body.removeChild(wrapper);
      };
    }).catch(function (error) {
      document.body.removeChild(wrapper);
      console.error('Error generating PDF:', error);
      alert('Could not generate PDF. Try CSV export or fewer filters.');
    });
  }).fail(function () {
    alert('Could not load records for export. Please try again.');
  }).always(function () {
    setExportButtonsLoading(false);
  });
}

function runCSVExport(tableId, fileName, scope) {
  if (isServerSideDataTable(tableId)) {
    exportServerSideDataTable(tableId, fileName, 'csv', scope);
    return;
  }

  if (scope === 'complete') {
    setExportButtonsLoading(true);
    refreshTableDataForExport().done(function () {
      exportDomTableToCSV(tableId, fileName);
    }).fail(function () {
      alert('Could not load data for export.');
      setExportButtonsLoading(false);
    });
    return;
  }

  exportDomTableToCSV(tableId, fileName);
}

function runPDFExport(tableId, fileName, scope) {
  if (isServerSideDataTable(tableId)) {
    exportServerSideDataTable(tableId, fileName, 'pdf', scope);
    return;
  }

  if (scope === 'complete') {
    setExportButtonsLoading(true);
    refreshTableDataForExport().done(function () {
      exportDomTableToPDF(tableId, fileName);
    }).fail(function () {
      alert('Could not load data for export.');
      setExportButtonsLoading(false);
    });
    return;
  }

  setExportButtonsLoading(true);
  exportDomTableToPDF(tableId, fileName);
}

function generateCSVFile(tableId, fileName) {
  promptExportScope().then(function (scope) {
    if (!scope) {
      return;
    }
    runCSVExport(tableId, fileName, scope);
  });
}

function generatePDFFile(tableId, fileName) {
  promptExportScope().then(function (scope) {
    if (!scope) {
      return;
    }
    runPDFExport(tableId, fileName, scope);
  });
}
//End Generate PDF

//Start Export
function exportView(param1, param2, $param3) {
  $("#" + param1 + "").tableToCSV();
}

jQuery.fn.tableToCSV = function () {
  var clean_text = function (text) {
    text = text.replace(/"/g, '""');
    return '"' + text + '"';
  };

  $(this).each(function () {
    var table = $(this);
    var caption = $(this)
      .find("caption")
      .text();
    var title = [];
    var rows = [];

    $(this)
      .find("tr")
      .each(function () {
        var data = [];
        $(this)
          .find("th")
          .each(function () {
            var text = clean_text($(this).text());
            title.push(text);
          });
        $(this)
          .find("td")
          .each(function () {
            var text = clean_text($(this).text());
            data.push(text);
          });
        data = data.join(",");
        rows.push(data);
      });
    title = title.join(",");
    rows = rows.join("\n");

    var csv = title + rows;
    var uri = "data:text/csv;charset=utf-8," + encodeURIComponent(csv);
    var download_link = document.createElement("a");
    download_link.href = uri;
    var ts = new Date().getTime();
    if (caption == "") {
      download_link.download = ts + ".csv";
    } else {
      download_link.download = caption + "-" + ts + ".csv";
    }
    document.body.appendChild(download_link);
    download_link.click();
    document.body.removeChild(download_link);
  });
};

//End Export

//Start Datalist Filter Remove

jQuery(function ($) {
  function tog(v) {
    return v ? "addClass" : "removeClass";
  }
  $(document)
    .on("input", ".clearable", function () {
      $(this)[tog(this.value)]("x");
    })
    .on("mousemove", ".x", function (e) {
      $(this)[
        tog(
          this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left
        )
      ]("onX");
    })
    .on("click", ".onX", function () {
      $(".clearable").val("");
      $(this)
        .removeClass("x onX")
        .val("");
    });
});

//End Datalist Filter Remove

//Start Reports Function
function adminRangeFilter(value) {
  var rangeFilterValue = $("#adminRangeFilter").val();
  if (rangeFilterValue == 1) {
    $("#startDate").removeAttr("readonly", "readonly");
    $("#endDate").removeAttr("readonly", "readonly");
    $("#btnDate").removeAttr("disabled", "disabled");

    $("#startMonth").attr("readonly", "readonly");
    $("#endMonth").attr("readonly", "readonly");
    $("#btnMonth").attr("disabled", "disabled");

    $("#startYear").attr("disabled", "disabled");
    $("#endYear").attr("disabled", "disabled");
    $("#btnYear").attr("disabled", "disabled");
  } else if (rangeFilterValue == 2) {
    $("#startDate").attr("readonly", "readonly");
    $("#endDate").attr("readonly", "readonly");
    $("#btnDate").attr("disabled", "disabled");

    $("#startMonth").removeAttr("readonly", "readonly");
    $("#endMonth").removeAttr("readonly", "readonly");
    $("#btnMonth").removeAttr("disabled", "disabled");

    $("#startYear").attr("disabled", "disabled");
    $("#endYear").attr("disabled", "disabled");
    $("#btnYear").attr("disabled", "disabled");
  } else if (rangeFilterValue == 3) {
    $("#startDate").attr("readonly", "readonly");
    $("#endDate").attr("readonly", "readonly");
    $("#btnDate").attr("disabled", "disabled");

    $("#startMonth").attr("readonly", "readonly");
    $("#endMonth").attr("readonly", "readonly");
    $("#btnMonth").attr("disabled", "disabled");

    $("#startYear").removeAttr("disabled", "disabled");
    $("#endYear").removeAttr("disabled", "disabled");
    $("#btnYear").removeAttr("disabled", "disabled");
  }
}


function deleteRowMasterTable(m, id, tableName) {
  var baseUrl = $("#url").val();
  $.ajax({
    url: "" + baseUrl + "/masd/deleteMasterTableRecord",
    type: "GET",
    data: {
      id: id,
      tableName: tableName,
      m: m
    },
    success: function (data) {
      $("#mainFunctionPage").hide();
      $("#functionAjaxResponse").html(data);
      $("#showMasterTableEditModel").modal("hide");
      location.reload();
    }
  });
}
function repostMasterTableRecords(m, id, tableName) {
  var baseUrl = $("#url").val();
  $.ajax({
    url: "" + baseUrl + "/masd/repostMasterTableRecord",
    type: "GET",
    data: {
      id: id,
      tableName: tableName,
      m: m
    },
    success: function (data) {
      $("#mainFunctionPage").hide();
      $("#functionAjaxResponse").html(data);
      $("#showMasterTableEditModel").modal("hide");
      location.reload();
    }
  });
}

function uniqueValueInTable(fieldId, tableName, dbType) {
  alert(fieldId + " - " + tableName + " - " + dbType);
}
//Start Purchase Sale Invoice Functions
function approvePurchaseSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  var baseUrl = $("#url").val();
  if (type == 1) {
    var url = 'purchase-invoice/approvePurchaseInvoiceVoucher';
  }  else {
    var url = 'sale-invoice/approveSaleInvoiceVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function reversePurchaseSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  var baseUrl = $("#url").val();
  if (type == 1) {
    var url = 'purchase-invoice/reversePurchaseInvoiceVoucher';
  }  else {
    var url = 'sale-invoice/reverseSaleInvoiceVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}
//End Purchase Sale Invoice Functions

//Start Finance Functions
function deleteFinanceVoucher(type, id, status, voucherTypeStatus) {
  var baseUrl = $("#url").val();
  if (type == 1) {
    var url = 'payments/deletePaymentVoucher';
  } else if (type == 3) {
    var url = 'journalvouchers/deleteJournalVoucher';
  } else {
    var url = 'receipts/deleteReceiptVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}
function approveFinanceVoucher(type, id, status, voucherTypeStatus) {
  var baseUrl = $("#url").val();
  if (type == 1) {
    var url = 'payments/approvePaymentVoucher';
  } else if (type == 3) {
    var url = 'journalvouchers/approveJournalVoucher';
  } else {
    var url = 'receipts/approveReceiptVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function rejectFinanceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'payments/paymentVoucherRejectAndRepost';
  } else if (type == 3) {
    var url = 'journalvouchers/journalVoucherRejectAndRepost';
  } else {
    var url = 'receipts/receiptVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 3 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function repostFinanceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'payments/paymentVoucherRejectAndRepost';
  } else if (type == 3) {
    var url = 'journalvouchers/journalVoucherRejectAndRepost';
  } else {
    var url = 'receipts/receiptVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function inactiveFinanceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'payments/paymentVoucherActiveAndInactive';
  } else if (type == 3) {
    var url = 'journalvouchers/journalVoucherActiveAndInactive';
  } else {
    var url = 'receipts/receiptVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 2 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function activeFinanceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'payments/paymentVoucherActiveAndInactive';
  } else if (type == 3) {
    var url = 'journalvouchers/journalVoucherActiveAndInactive';
  } else {
    var url = 'receipts/receiptVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}
function reverseFinanceVoucher(type, id, status, voucherTypeStatus) {
  var baseUrl = $("#url").val();
  if (type == 1) {
    var url = ''+baseUrl+'/finance/payments/reversePaymentVoucher';
  } else if (type == 3) {
    var url = ''+baseUrl+'/finance/journalvouchers/reverseJournalVoucher';
  } else {
    var url = ''+baseUrl+'/finance/receipts/reverseReceiptVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}
//End Finance Functions

//Start Purchase Functions

function approvePurchaseVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'purchase-orders/approvePurchaseOrderVoucher';
  } else {
    var url = 'good-receipt-notes/approveGoodReceiptNoteVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function rejectPurchaseVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'purchase-orders/purchaseOrderVoucherRejectAndRepost';
  } else {
    var url = 'good-receipt-notes/goodReceiptNoteVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 3 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function repostPurchaseVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'purchase-orders/purchaseOrderVoucherRejectAndRepost';
  } else {
    var url = 'good-receipt-notes/goodReceiptNoteVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function inactivePurchaseVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'purchase-orders/purchaseOrderVoucherActiveAndInactive';
  } else {
    var url = 'good-receipt-notes/goodReceiptNoteVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 2 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function activePurchaseVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'purchase-orders/purchaseOrderVoucherActiveAndInactive';
  } else {
    var url = 'good-receipt-notes/goodReceiptNoteVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

//End Purchase Functions


//Start Store Functions

//Start Sale Functions

function approveDirectSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'direct-sale-invoices/approveDirectSaleInvoiceVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function rejectDirectSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'direct-sale-invoices/directSaleInvoiceVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 3 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function repostDirectSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'direct-sale-invoices/directSaleInvoiceVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function inactiveDirectSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'direct-sale-invoices/directSaleInvoiceVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 2 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function activeDirectSaleInvoiceVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'direct-sale-invoices/directSaleInvoiceVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

//End Sale Functions

function approveStoreVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'transfer-notes/approveTransferNoteVoucher';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function rejectStoreVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'transfer-notes/transferNoteVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 3 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function repostStoreVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'transfer-notes/transferNoteVoucherRejectAndRepost';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function inactiveStoreVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'transfer-notes/transferNoteVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 2 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}

function activeStoreVoucher(type, id, status, voucherTypeStatus) {
  if (type == 1) {
    var url = 'transfer-notes/transferNoteVoucherActiveAndInactive';
  }
  $.ajax({
    url: url,  // URL of the Blade view (e.g., 'sections/_form')
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id, status: status, voucherTypeStatus: voucherTypeStatus, value: 1 },
    success: function (response) {
      if (response == 'Done') {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
      }
    }
  });
}


function inactiveReturnVoucher(id) {
  
  var url = `return-good-receipt-notes/destroy/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function activeReturnVoucher(id) {
  
  var url = `return-good-receipt-notes/status/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function rejectReturnVoucher(id) {
  var url = `return-good-receipt-notes/returnGoodReceiptNoteVoucherReject/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function repostReturnVoucher(id) {
  var url = `return-good-receipt-notes/returnGoodReceiptNoteVoucherRepost/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function approveReturnVoucher(id) {
  var url = `return-good-receipt-notes/approveReturnGoodReceiptNoteVoucher`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}


//End Store Functions

function get_ajax_data() {
  var form = $('#list_data');
  var actionUrl = form.attr('action');
  $('#data').html('<tr><td colspan="100"><div class="loader"></div></td></tr>');
  var pages = $('#pages').val();
  $.ajax({
    type: "get",
    url: actionUrl,
    data: form.serialize(), // serializes the form's elements.
    async: true,
    cache: false,
    success: function (data) {
      $('#data').html(data);
      //$('select').select2();
    }
  });
}

function get_ajax_data_two(tableId, columns) {
  var form = $('#list_data');
  var actionUrl = form.attr('action');
  var tableElement = $(`#${tableId}`);

  tableElement.data('export-columns', columns);

  // Destroy the existing DataTable instance if it exists
  if ($.fn.dataTable.isDataTable(tableElement)) {
    tableElement.DataTable().destroy();
  }
  tableElement.find('tbody').empty();
  tableElement.DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: actionUrl,
      type: 'GET',
      data: function (d) {
        // Serialize form data and include it in the request
        var formData = form.serializeArray();
        $.each(formData, function (i, field) {
          d[field.name] = field.value;
        });
      },
      dataSrc: 'data' // Specify where the data is in the server response
    },
    columns: columns
  });
}




function inactiveReturnSale(id) {
  
  var url = `sales-return/destroy/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function activeReturnSale(id) {
  
  var url = `sales-return/status/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function rejectReturnSale(id) {
  var url = `sales-return/returnSaleReject/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function repostReturnSale(id) {
  var url = `sales-return/returnSaleRepost/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
function approveReturnSale(id) {
  var url = `sales-return/returnSaleApprove/${id}`;
  $.ajax({
    url: url, 
    type: 'post',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: { id: id },
    success: function (response) {
        $("#showDetailModelOneParamerter").modal("toggle");
        get_ajax_data();
    }
  });
}
//End Reports Function
