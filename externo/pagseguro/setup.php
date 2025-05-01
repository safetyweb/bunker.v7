<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
error_reporting(E_ALL);

include '../../_system/_functionsMain.php';

$post=@$_POST;
$get=@$_GET;
$request=@$_REQUEST;

$jpost=addslashes(json_encode(@$_POST));
$jget=addslashes(json_encode(@$_GET));
$jrequest=addslashes(json_encode(@$_REQUEST));

$conn=$connAdm->connAdm();


$ins='insert INTO LOG_PAGSEGURO (TEXTO_POST,TEXTO_GET,TEXTO_REQUEST) VALUES ("'.$jpost.'","'.$jget.'","'.$jrequest.'")';
mysqli_query($conn, $ins);
$COD_log= mysqli_insert_id($conn);

$PAGSEGURO_API_URL = "https://ws.pagseguro.uol.com.br/v2";

/*
$PAGSEGURO_EMAIL = "marcelo@markafidelizacao.com.br";
$PAGSEGURO_TOKEN = "3d64f393-6e53-4df3-bc38-c44d0aa4f830bc1e726f407eb6215afa99649dba854531ca-c256-4291-b454-2f8db87f969c";
 */
$PAGSEGURO_EMAIL = "financeiro@markafidelizacao.com.br";
$PAGSEGURO_TOKEN = "92f27db1-66f5-4574-a042-e1db88c456aa9c50219f408f98ff0ab54ce2e73057aecaad-0df4-4df2-88bc-c26d7ee10c7e";

$params = array();
$params["email"] = $PAGSEGURO_EMAIL;
$params["token"] = $PAGSEGURO_TOKEN;

$header = array('Content-Type' => 'application/json;charset = UTF-8;');



if (@$post["notificationCode"] <> ""){
	//RECEBE AS INFORMAÇÔES DO WEBHOOK E PROCESSA O PAGAMENTO

	$url = $PAGSEGURO_API_URL . "/transactions/notifications/" . $post["notificationCode"]."?".http_build_query($params, '', '&');


	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec($ch);
	curl_close($ch);

	$dadosJSon = json_decode(json_encode(@simplexml_load_string($data)));
	$tpPag = "N";
	if (@$dadosJSon->type == "1") {
	  $cdPag = @$dadosJSon->code;
	  $urlBoleto = @$dadosJSon->paymentLink;
	  switch (@$dadosJSon->status) {
		case "1": //Aguardando Confirmação
		case "2": //Analisando
		  $tpPag = "N";
		  break;

                case "3":  
		case "4": //Paga com possíveis resalvas
		  $tpPag = "S";
		  break;

		case "6": //Devolvido
		  $tpPag = "D";
		  break;

		case "7": //Cancelado
		  $tpPag = "C";
		  break;

		default:
		  break;
	  }
	}else{
		exit;
	}
	//print_r($dadosJSon);
        $date_validade='';
        if($tpPag=='S')
        {        
         $sqldat_validade="SELECT   DATE_ADD(CURDATE(),INTERVAL  QTD_DIAS  DAY) date_validade FROM pedido_marka  where QTD_DIAS  > 0 AND ID_SESSION_PAGSEGURO='$cdPag'";
         $wsldat_validade=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqldat_validade));
         $date_validade=' ,DAT_VALIDADE="'.$wsldat_validade['date_validade'].'"';
        
        }

	$sqlIns = " UPDATE PEDIDO_MARKA SET
					PAG_CONFIRMACAO = '$tpPag',
					RETORNO_JSON_PAGSEGURO_WH = '".json_encode($dadosJSon)."'
                                        $date_validade
                                            
				WHERE
					ID_SESSION_PAGSEGURO = '$cdPag'";
	//echo $sqlIns;
	mysqli_query($connAdm->connAdm(), $sqlIns) or die(mysqli_error());

}else{
	
	//FAZ UM SELECT NAS TRANSACOES EM ABERTO E VERIFICA SE O PAGAMENTO FOI FEITO

      $sql = "SELECT DISTINCT ID_SESSION_PAGSEGURO FROM pedido_marka WHERE PAG_CONFIRMACAO = 'N'";
      $rs = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
      while ($linha = mysqli_fetch_assoc($rs)) {
		  $url = $PAGSEGURO_API_URL . "/transactions/" . $linha["ID_SESSION_PAGSEGURO"]."?".http_build_query($params, '', '&');

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);

			$dadosJSon = json_decode(json_encode(@simplexml_load_string($data)));
			$tpPag = "N";
			if (@$dadosJSon->type == "1") {
			  $cdPag = @$dadosJSon->code;
			  $urlBoleto = @$dadosJSon->paymentLink;
			  switch (@$dadosJSon->status) {
				case "1": //Aguardando Confirmação
				case "2": //Analisando
				  $tpPag = "N";
				  break;

				case "3": //Pagamento Confirmado
				case "4": //Paga com possíveis resalvas
				  $tpPag = "S";
				  break;


				case "6": //Devolvido
				  $tpPag = "D";
				  break;

				case "7": //Cancelado
				  $tpPag = "C";
				  break;

				default:
				  break;
			  }

				if ($tpPag != "N"){
                                     $date_validade='';
                                     if($tpPag=='S')
                                        {        
                                         $sqldat_validade="SELECT   DATE_ADD(CURDATE(),INTERVAL  QTD_DIAS  DAY) date_validade FROM pedido_marka  where QTD_DIAS  > 0 AND ID_SESSION_PAGSEGURO='$cdPag'";
                                         $wsldat_validade=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqldat_validade));
                                         $date_validade=' ,DAT_VALIDADE="'.$wsldat_validade['date_validade'].'"';

                                        }
					$sqlIns = " UPDATE PEDIDO_MARKA SET
									PAG_CONFIRMACAO = '$tpPag',
									RETORNO_JSON_PAGSEGURO_WH = '".json_encode($dadosJSon)."'
                                                                        $date_validade
								WHERE
									ID_SESSION_PAGSEGURO = '$cdPag'";
					echo $sqlIns;
					mysqli_query($connAdm->connAdm(), $sqlIns) or die(mysqli_error());
				}
			}
	  }
	
	
}

mysqli_close($conntempvar);
mysqli_close($connAdmVAR);
