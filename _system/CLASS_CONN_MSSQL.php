<?php

    $hostname = "173.212.201.183";
    $dbname = "INTEGRACAO_CLUBE_SO";
    $username = "Marka_so";
    $pw = "H+proc29.5";
    $con = mssql_connect ($hostname, $username, $pw);
    mssql_select_db ($dbname, $con) or die(Grava_log_cad($connUser->connUser(),$LOG,'Não foi possível selecionar o banco de dados!'));

?>