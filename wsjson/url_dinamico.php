<?php
include '../_system/Class_conn.php';
include '../wsmarka/func/function.php';    
          $json = array("descricao1"=>'',
                        "URL1" =>'https://adm.bunker.mk/appduque/', 
                        "descricao2"=>'',
                        "URL2"=>'https://www.rededuque.com.br/app/',
                        "descricao3"=>'',
                        "URL3"=>'https://www.rededuque.com.br/app/',
                        "TIMER"=>'10',
                        "URL4"=>'https://www.rededuque.com.br/app/home.php?log=1',
                        "descricao4"=>''
                       );
            header('Content-type: application/json');
            echo  json_encode($json);?>