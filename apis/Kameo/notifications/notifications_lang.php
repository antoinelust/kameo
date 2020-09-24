<?php

$mykameo_notif_lang = [
    '0' => L::notif_mark_as_read,
    '1' => L::notif_no_notif
]
?>

<script type="text/javascript">
    var notifTrads = {
        <?php
        foreach ($mykameo_notif_lang as $trads => $value) {
            echo '  ' . $trads . ': ' . '"' . $value . '",' . "\n";
        }
        ?>
    };
</script>