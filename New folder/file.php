<?php
    $myfilename = "jon.txt";

    $myfile = fopen($myfilename,'r');

    $my_size = filesize($myfilename);

    $myfiledata = fread($myfile,$my_size);

    echo $myfiledata;

?>