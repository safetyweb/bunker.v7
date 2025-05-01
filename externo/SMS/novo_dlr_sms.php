<?php
 include '../../_system/_functionsMain.php';
$JSONRETORNO=file_get_contents("php://input");
$json_array=json_decode($JSONRETORNO,true);
file_put_contents('/srv/www/htdocs/externo/SMS/log.txt', $JSONRETORNO);
	
    foreach($json_array[data][after] as $KEY => $dados)
    {
		$dadoscampanha=explode('||',$dados[parceiro_id]);
		
		$EMPRESA=$dadoscampanha[1];
        $CAMPANHA=$dadoscampanha[0];
        $contemporaria= connTemp($EMPRESA, '');
        echo '<pre>';
        print_r($contemporaria);
        echo '</pre>';
         
                $testeinsert="INSERT INTO log_nuxux (COD_CAMPANHA,COD_EMPRESA, TIP_LOG, LOG_JSON,DAT_CADASTR) VALUES ('$CAMPANHA','$EMPRESA', '19', '".addslashes($JSONRETORNO)."','".date('Y-m-d')."');";
                echo $testeinsert;
				mysqli_query($contemporaria,$testeinsert);
    }
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++    
mysqli_close( $contemporaria);
 