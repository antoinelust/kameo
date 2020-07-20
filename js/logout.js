function logout(){
    $.ajax({
      url: 'apis/Kameo/logout.php',
      method: 'post',
      data: {},
      //si le tableau de session est vide, on est bien déconnecté
      success: function(response){
        if (response.length == 0) {
          window.location.reload(true);
        }
      }
    });
}