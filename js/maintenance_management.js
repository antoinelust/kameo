function list_maintenances() {
  $.ajax({
      url: 'include/maintenance_management.php',
      method: 'get',
      data: {'action' : 'list'},
      success: function(response){
        if (response.message == "error") {
          console.log(response.message);
        } else {
          console.log(response);
          var dest = '<span>'+response.maintenancesNumberGlobal+' / <span style="color:red">'+response.maintenancesNumberAuto+'</span></span>';
          $('#counterMaintenance').html(dest);
        }
      }
  });
}
