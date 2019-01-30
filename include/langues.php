<?php

if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

function setLangue($data){
	$_SESSION['langue'] = $data;
    ?>
    <script type="text/javascript">
        langueJava="<?php echo $data; ?>";
    </script>
    <?php
}



function getLangue($data)
{
    $langue=isset($_SESSION['langue']) ? $_SESSION['langue'] : NULL;
    $langueCookie=isset($_COOKIE['langue']) ? $_COOKIE['langue'] : NULL;
	if ($langue<> 'fr' && $langue<> 'nl' && $langue<> 'en' && ($langueCookie=='fr' || $langueCookie=='en' || $langueCookie=='nl'))
	{	
		$_SESSION['langue']=$langueCookie;
	} else if($langue=='fr' || $langue=='nl' || $langue=='en'){
        $_SESSION['langue']=$langue;
    } else
    {
        $_SESSION['langue']="fr";
    }
    echo $_SESSION['langue'];
}

if (isset($_POST['setLangue'])) {
	setLangue($_POST['setLangue']);
    $_SESSION['langue'];
    return true;
}

if (isset($_POST['getLangue'])) {
	$temp=getLangue($_POST['getLangue']);
    echo $temp;
}

?>