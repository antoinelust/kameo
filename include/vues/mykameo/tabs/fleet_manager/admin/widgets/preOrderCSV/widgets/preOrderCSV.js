

$('.preOrderCSVManagerClick').click(function(){

  $("#displayCSVOrder").dataTable({
    destroy: true,
    ajax: {
      url: "api/csvOrder",
      contentType: "application/json",
      type: "GET",
      data: {
        action:"listOrderCSVFile",
      }
    },
    sAjaxDataProp: "",
    columns: [
    { title: "Nom du fichier", data: "CSV_NAME" ,fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html('<a href="#" class="text-red csvConfirmOrder" data-target="#csvConfirmOrder" data-toggle="modal" name="orderFilsCSV"  data-correspondent="'+sData+'">'+sData+'</a>');
    },
  },
  { title: "Fournisseur", data: "PROVIDER" },
  { title: "Traitement effectué", data: "LOAD_STATUS" },
  ],
  order: [
  [0, "asc"]
  ],
  paging : false,
  searching : false
});
  $("#displayCSVOrderClosed").dataTable({
    destroy: true,
    ajax: {
      url: "api/csvOrder",
      contentType: "application/json",
      type: "GET",
      data: {
        action:"listOrderCSVFileClosed",
      }
    },
    sAjaxDataProp: "",
    columns: [
    { title: "Nom du fichier", data: "CSV_NAME" ,fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
    //changer cette partie
    $(nTd).html('<a href="#" class="text-green csvConfirmOrder" data-target="#csvConfirmOrder" data-toggle="modal" name="orderFilsCSV" data-correspondent="'+sData+'">'+sData+'</a>');
  },
},
{ title: "Fournisseur", data: "PROVIDER" },
{ title: "Traitement effectué", data: "LOAD_STATUS" },
],
order: [
[0, "asc"]
],
paging : false,
searching : false
});
});
$('#displayCSVOrder').on( 'draw.dt', function () {
  $('#displayCSVOrder .csvConfirmOrder').off();
  $('#displayCSVOrder .csvConfirmOrder').click(function(){

   $('#csvConfirmOrder button[name=confirmForm]').show();
   detailCSVOrder($(this).data('correspondent'),'load'); 
 })
})


$('#displayCSVOrderClosed').on( 'draw.dt', function () {
  $('#displayCSVOrderClosed .csvConfirmOrder').off();
  $('#displayCSVOrderClosed .csvConfirmOrder').click(function(){
   $('#csvConfirmOrder button[name=confirmForm]').hide();
   detailCSVOrder($(this).data('correspondent'),'closed');     
 })
})


function detailCSVOrder(csvName,typeDisplay){

  $('#csvConfirmOrder input[name=testCSVOrderDetail]').val(csvName);

  if(typeDisplay=='load'){
    $("#displayDetailCSVOrder").dataTable({
      destroy: true,
      ajax: {
        url: "api/csvOrder",
        contentType: "application/json",
        type: "GET",
        data: {
          action:"retrieve",
          csvName:csvName,
        }
      },
      sAjaxDataProp: "",
      columns: [
      { title: "ID", data: "id"},
      { title: "Marque", data: "brand"},
      { title: "Modèle", data: "model"},
      { title: "Fournisseur", data: "provider" },
      { title: "Stock Minimal", data: "min" },
      { title: "Stock Optimal", data: "stockOpti" },
      { title: "Nombre d'article en stock ", data: "stock" },
      { title: "Nombre d'article à commander ", data: "numberToOrder",fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html('<input type="number" min="0" max="100" class="text-green csvnumberOrderArticle"  name="numberArticleCSV'+oData.id+'" value="'+sData+'"data-correspondent="'+oData.reference+'">');
      }, },
      { title: "Prix", data: "price" },

      ],
      order: [
      [0, "asc"]
      ],
      paging : false,
      searching : false,
      initComplete : function( settings, json){
        var table = $('#displayDetailCSVOrder').DataTable();
        $('#widget-csvDetailOrder-form input[name=totalArticleNumber]').val(table.rows().count());
      }
    });
  }
  else{
   $("#displayDetailCSVOrder").dataTable({
    destroy: true,
    ajax: {
      url: "api/csvOrder",
      contentType: "application/json",
      type: "GET",
      data: {
        action:"retrieve",
        csvName:csvName,
      }
    },
    sAjaxDataProp: "",
    columns: [
    { title: "ID", data: "id"},
    { title: "Marque", data: "brand"},
    { title: "Modèle", data: "model"},
    { title: "Fournisseur", data: "provider" },
    { title: "Stock Minimal", data: "min" },
    { title: "Stock Optimal", data: "stockOpti" },
    { title: "Nombre d'article en stock ", data: "stock" },
    { title: "Nombre d'article à commander ", data: "numberToOrder",fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
      $(nTd).html('<input type="number"  min="0" max="100" class="text-green csvnumberOrderArticle"  disabled name="numberArticleCSV'+oData.id+'" value="'+sData+'"data-correspondent="'+oData.reference+'">');
    }, },
    { title: "Prix", data: "price" },

    ],
    order: [
    [0, "asc"]
    ],
    paging : false,
    searching : false,
    initComplete : function( settings, json){
      var table = $('#displayDetailCSVOrder').DataTable();
      $('#widget-csvDetailOrder-form input[name=totalArticleNumber]').val(table.rows().count());
    }
  });
 }
}




var holder = document.getElementById('holder'),
tests = {
  filereader: typeof FileReader != 'undefined',
  dnd: 'draggable' in document.createElement('span'),
  formdata: !!window.FormData,
  progress: "upload" in new XMLHttpRequest
}, 
support = {
  filereader: document.getElementById('filereader'),
  formdata: document.getElementById('formdata'),
  progress: document.getElementById('progress')
},
acceptedTypes = {
  'application/vnd.ms-excel': true
},
progress = document.getElementById('uploadprogress'),
fileupload = document.getElementById('upload');

"filereader formdata progress".split(' ').forEach(function (api) {
  if (tests[api] === false) {
    support[api].className = 'fail';
  } else {
    support[api].className = 'hidden';
  }
});

function previewfile(file) {

  if (tests.filereader === true && acceptedTypes[file.type] === true) {
   holder.innerHTML = '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size/1024|0) + 'K' : '');
    $('#widget-csvOrder-form input[name=fileCSV]').val(file.name);
 }
}

function readfiles(files) {
  var formData = tests.formdata ? new FormData() : null;
  for (var i = 0; i < files.length; i++) {
    if (tests.formdata) formData.append('file', files[i]);
    previewfile(files[i]);
  }

    // now post a new XHR request
    if (tests.formdata) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/devnull.php');
      xhr.onload = function() {
        progress.value = progress.innerHTML = 100;
      };

      if (tests.progress) {
        xhr.upload.onprogress = function (event) {
          if (event.lengthComputable) {
            var complete = (event.loaded / event.total * 100 | 0);
            progress.value = progress.innerHTML = complete;
          }
        }
      }

      xhr.send(formData);
    }
  }

  if (tests.dnd) { 
    console.log('ici1');
    holder.ondragover = function () { this.className = 'hover'; return false; };
    holder.ondragend = function () { this.className = ''; return false; };
    holder.ondrop = function (e) {
      this.className = '';
      e.preventDefault();
      readfiles(e.dataTransfer.files);
    }
  } else {

    fileupload.className = 'hidden';
    fileupload.querySelector('input').onchange = function () {
      readfiles(this.files);
    };
  }
function reloadDatatable(){
  holder.innerHTML ='';
  console.log('test');
  $("#displayCSVOrder").dataTable().api().ajax.reload();
  $("#displayCSVOrderClosed").dataTable().api().ajax.reload();
}