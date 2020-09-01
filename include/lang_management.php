<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/include/i18n/i18n.php'; //french by defaut, as many files as wanted can be added to the array
$i18n = new i18n([
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_mykameo_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_velo_partage_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_index_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_velo_personnel_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_gestion_flotte_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_location_ttinclus_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_achat_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_accessoires_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_bons_plans_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_avantages_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_cash4bike_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_contact_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_blog_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_successMessages_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_header_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_commander_{LANGUAGE}.ini',
    $_SERVER['DOCUMENT_ROOT'].'/lang/lang_offre_{LANGUAGE}.ini'
]);

$i18n->init();

?>
