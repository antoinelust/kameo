function getLanguage(){
       return $.ajax({
            url: 'include/langues.php',
            type: 'post',
            data: { "getLangue": "1"}
        });
}


function displayLanguage(){
    getLanguage()
    .done(function(response){
                if (response.length == "2")
                {
                    if ( response == "fr" )
                    {
                        displayFr();
                    }
                    else if ( response == "en" )
                    {
                        displayEn();
                    }
                    else if ( response == "nl" )
                    {
                        displayNl();
                    }
                    else
                    {
                        alert("Erreur langue");
                    }

                }
                else
                {
                    displayFr();
                }
    });
}
displayLanguage();

	
function setFr() {
    $.ajax({
        url: 'include/langues.php',
        type: 'post',
        data: { "setLangue": "fr"},
        success: function(response) { 
			displayFr();
			window.location.reload();
		}
    });
}
function setNl() {
    $.ajax({
        url: 'include/langues.php',
        type: 'post',
        data: { "setLangue": "nl"},
        success: function(response) { 
			displayNl();
			window.location.reload();
		}
    });
}
function setEn() {
    $.ajax({
        url: 'include/langues.php',
        type: 'post',
        data: { "setLangue": "en"},
        success: function(response) { 
			displayEn();
			window.location.reload();
		}
    });
}


function selectFirstOption(langue){

    var temp = "."+langue;
    selectTags = document.getElementsByTagName("select");

    for(var i = 0; i < selectTags.length; i++) {
        $(selectTags[i]).children(temp).eq(0).prop('selected',true);
    } 
}

function displayEn() {

	var appBanners = document.getElementsByClassName('en'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'block';
	}
    var appBanners = document.getElementsByClassName('en-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'inline';
	}

    var appBanners = document.getElementsByClassName('en-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'table-cell';
	}	
	
	var appBanners = document.getElementsByClassName('fr'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
    
	var appBanners = document.getElementsByClassName('fr-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	} 
    
	var appBanners = document.getElementsByClassName('fr-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	} 	
	
	var appBanners = document.getElementsByClassName('nl'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
    
	var appBanners = document.getElementsByClassName('nl-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
    
	var appBanners = document.getElementsByClassName('nl-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}	
    
    
	$(document).ready(function() {
    $('[id$=fr]').hide();
	});
	$(document).ready(function() {
    $('[id$=en]').show();
	});
	$(document).ready(function() {
    $('[id$=nl]').hide();
	});

    selectFirstOption("en");
}

function displayFr() {
	var appBanners = document.getElementsByClassName('fr'), i;
    
	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'block';
	}
	var appBanners = document.getElementsByClassName('fr-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'inline';
	}
	
	var appBanners = document.getElementsByClassName('fr-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'table-cell';
	}	

	var appBanners = document.getElementsByClassName('nl'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}	
    
    var appBanners = document.getElementsByClassName('nl-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}

    var appBanners = document.getElementsByClassName('nl-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
	
	
	var appBanners = document.getElementsByClassName('en'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
    
	var appBanners = document.getElementsByClassName('en-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}

	var appBanners = document.getElementsByClassName('en-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}	
    
	$(document).ready(function() {
    $('[id$=fr]').show();
	});
	$(document).ready(function() {
    $('[id$=en]').hide();
	});
	$(document).ready(function() {
    $('[id$=nl]').hide();
	});
    
    selectFirstOption("fr");

}

function displayNl() {
	var appBanners = document.getElementsByClassName('nl'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'block';
	}
	
	var appBanners = document.getElementsByClassName('nl-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'inline';
	}
	
	var appBanners = document.getElementsByClassName('nl-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'table-cell';
	}	
	    
	var appBanners = document.getElementsByClassName('fr'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
    
	var appBanners = document.getElementsByClassName('fr-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}    
		
	var appBanners = document.getElementsByClassName('fr-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}    
	
	var appBanners = document.getElementsByClassName('en'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}
    
	var appBanners = document.getElementsByClassName('en-inline'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}

	var appBanners = document.getElementsByClassName('en-cell'), i;

	for (var i = 0; i < appBanners.length; i ++) {
		appBanners[i].style.display = 'none';
	}	
    
	$(document).ready(function() {
    $('[id$=fr]').hide();
	});
	$(document).ready(function() {
    $('[id$=en]').hide();
	});
	$(document).ready(function() {
    $('[id$=nl]').show();
	});
    
    selectTags = document.getElementsByTagName("select");
    for(var i = 0; i < selectTags.length; i++) {
      selectTags[i].selectedIndex =0;
    }  
    
    selectFirstOption("nl");
}