<?php
    $my_file = fopen("jon.txt",'w');

    $my_text = "Digital School\n";

    fwrite($my_file,$my_text);
?>