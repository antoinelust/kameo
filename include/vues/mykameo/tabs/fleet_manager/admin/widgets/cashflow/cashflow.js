
  //Module CASHFLOW ==> Cout ==> retrieve cost
  function retrieve_cost(ID, action){
    $.ajax({
      url: 'apis/Kameo/costs_management.php',
      type: 'get',
      data: {"ID": ID, "action": "retrieve"},
      success: function(response){
        if(response.response == 'error') {
          console.log(response.message);
        }
        if(response.response == 'success'){
          if(action=="retrieve"){
            $('#widget-costsManagement-form input').attr("readonly", true);
            $('#widget-costsManagement-form textarea').attr("readonly", true);
            $('#widget-costsManagement-form select').attr("readonly", true);
          }else{
            $('#widget-costsManagement-form input').attr("readonly", false);
            $('#widget-costsManagement-form textarea').attr("readonly", false);
            $('#widget-costsManagement-form select').attr("readonly", false);
          }
          $('#widget-costsManagement-form input[name=title]').val(response.title);
          $('#widget-costsManagement-form textarea[name=description]').val(response.description);
          $('#widget-costsManagement-form select[name=type]').val(response.type);

          if(response.start){
            $('#widget-costsManagement-form input[name=start]').val(response.start.substring(0,10));
          }
          if($("#widget-costsManagement-form select[name=type]").val()=="one-shot"){
            $("#widget-costsManagement-form input[name=end]").attr("readonly", true);
            $("#widget-costsManagement-form input[name=end]").val("");
          }else{
            if(action!="retrieve"){
              $("#widget-costsManagement-form input[name=start]").attr("readonly", false);
              $("#widget-costsManagement-form input[name=end]").attr("readonly", false);
            }
            if(response.end){
              $('#widget-costsManagement-form input[name=end]').val(response.end.substring(0,10));
            }
          }
          $('#widget-costsManagement-form input[name=action]').val("update");
          $('#widget-costsManagement-form input[name=ID]').val(ID);
          if(response.amount){
            $('#widget-costsManagement-form input[name=amount]').val(response.amount);
          }
        }
      }
    });
  }