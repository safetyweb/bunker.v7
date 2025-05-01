<?php
include_once '../_system/Class_conn.php';
include_once '../wsmarka/func/function.php';
 $connadmtemp=$connAdm->connAdm();

 @$login = $_REQUEST['login'];
 @$COD_EMPRESA = $_REQUEST['idcliente'];
 

 $sql_aut="SELECT * FROM usuarios WHERE  COD_EXTERNO='$login' AND 
                                         COD_EMPRESA=$COD_EMPRESA AND 
                                         COD_EXCLUSA=0 AND
										(CASE
											 WHEN
												  LOG_ESTATUS IS NULL 
												  OR LOG_ESTATUS = ''
											 THEN
												  'S'
											 WHEN 
												  LOG_ESTATUS = 'S'
											 THEN
												  'S'
											 ELSE
											 'Não parametrizado'
     									END) IN ('S', '')";
 $resultsql=mysqli_fetch_assoc(mysqli_query($connadmtemp, $sql_aut));
 
    if(isset($resultsql['COD_EXTERNO']))
    {
        $json = array("AUTENTICADO" =>"TRUE",
                      "COD_VENDEDOR"=>$resultsql['COD_EXTERNO']

                              );
       header('Content-type: application/json');
        echo  json_encode($json);
        exit();
    } else{
        
        $json = array("AUTENTICADO" =>"FALSE",
                      "COD_VENDEDOR"=>0
                              );
       header('Content-type: application/json');
        echo  json_encode($json); 
        exit();
    }    