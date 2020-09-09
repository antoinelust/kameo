<?php
$phpToJsDays = [
    '0' => L::search_module_sun,
    '1' => L::search_module_mon,
    '2' => L::search_module_tue,
    '3' => L::search_module_wed,
    '4' => L::search_module_thu,
    '5' => L::search_module_fri,
    '6' => L::search_module_sat
];

$phpToJsMonths = [
    '0' => L::search_module_jan,
    '1' => L::search_module_feb,
    '2' => L::search_module_mar,
    '3' => L::search_module_apr,
    '4' => L::search_module_may,
    '5' => L::search_module_jun,
    '6' => L::search_module_jul,
    '7' => L::search_module_aug,
    '8' => L::search_module_sep,
    '9' => L::search_module_oct,
    '10' => L::search_module_nov,
    '11' => L::search_module_dec,
];
?>

<script type="text/javascript">
    var daysTrad = {
        <?php
        foreach ($phpToJsDays as $day => $value) {
            echo '  ' . $day . ': ' . '"' . $value . '",' . "\n";
        }
        ?>
    };

    var monthTrad = {
        <?php
        foreach ($phpToJsMonths as $month => $value) {
            echo '  ' . $month . ': ' . '"' . $value . '",' . "\n";
        }
        ?>
    };
</script>