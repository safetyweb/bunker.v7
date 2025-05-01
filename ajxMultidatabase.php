<?php 
	include '_system/_functionsMain.php'; 
	include '_system/func_nexux/func_transacional.php';

//echo fnDebug('true');

	if ($_GET["acao"] == "token"){

		unset($_SESSION["TOKEN_SQL"]["SQL"]);

		if (@$_SESSION["TOKEN_SQL"]["ERROS"] > 3){
			if ($_SESSION["TOKEN_SQL"]["DATAHORA"] <> ""){
				$d1 = strtotime($_SESSION["TOKEN_SQL"]["DATAHORA"]);
				$d2 = strtotime(date("Y-m-d H:i:s"));
				$d3 = $d2 - $d1;
				$tempoEspera = 360;
				if ($d3 < $tempoEspera){
					echo "Tentativas de execu&ccedil;&atilde;o excedidas! Aguarde ".round($tempoEspera-$d3)." segundos e tente novamente!";
					exit;
				}else{
					unset($_SESSION["TOKEN_SQL"]["ERROS"]);
				}
			}else{
				unset($_SESSION["TOKEN_SQL"]["ERROS"]);
			}
		}


		function geraToken(){
			return fnEncode(mt_rand(100000,999999));
		}
		function soNumero($str) {
			return preg_replace("/[^0-9]/", "", $str);
		}

		if (@$_SESSION["TOKEN_SQL"]["TOKEN"] <> "" && $_SESSION["TOKEN_SQL"]["VALIDADO"] != true){
			$token = $_SESSION["TOKEN_SQL"]["TOKEN"];
		}else{
			$token = geraToken();
		}
		//$token=fnEncode(1111);


		$sql = "SELECT NOM_USUARIO,COD_EMPRESA,COD_USUARIO,NUM_CELULAR,DES_EMAILUS FROM USUARIOS WHERE COD_USUARIO=0".$_SESSION["SYS_COD_USUARIO"];
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$linha = mysqli_fetch_assoc($arrayQuery);

		if ($linha["NUM_CELULAR"] == "" && $linha["DES_EMAILUS"] == ""){
			echo "Celular e e-mail n&atilde;o cadastrados! N&atilde;o ser&aacute; poss&iacute;vel enviar Chave de Seguran&ccedil;a por SMS.";
			exit;
		}

		if ($linha["NUM_CELULAR"] <> ""){
			/*$api_key="1ccBGmCRjYJQaBNbZ8SCqVKqWt";
			$to="55".soNumero($linha["NUM_CELULAR"]);
			//echo $to;exit;
			$sms=urlencode("Token de autenticacao de script: ".fnDecode($token));
			$url="https://www.nexuscomunicacoes.com/sms/api?action=send-sms&api_key=$api_key&to=$to&sms=$sms";
			//echo $url;

			$json = file_get_contents($url);
			$json=json_decode($json,true);*/

			$sql = "SELECT * FROM senhas_parceiro apar
					WHERE COD_EMPRESA=7 AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S'
					";
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
			$rsempresa = mysqli_fetch_assoc($arrayQuery);
			$CLIE_SMS=[];
			$CLIE_SMS[] = array(
				"from" => $rsempresa['DES_CLIEXT'],
                "to" => "+55".soNumero($linha["NUM_CELULAR"]),
                "mensagem" => "Token de autenticacao de script: ".fnDecode($token),
                "DataAgendamento" => date("Y-m-d H:i:s"),
            );


			$base64 = base64_encode($rsempresa['DES_USUARIO'] . ':' . $rsempresa['DES_AUTHKEY']);
			$retornoSMS = sms_twilo($base64, $CLIE_SMS, $rsempresa['DES_USUARIO'], $rsempresa['DES_AUTHKEY']);
		}

/*
		if ($json["code"] <> "ok"){
			echo "Erro ao enviar Chave de Seguran&ccedil;a por SMS: [".$json["code"]."] ".$json["message"];
			exit;
		}
*/

		if ($linha["DES_EMAILUS"] <> ""){
			include './externo/email/envio_sac.php';
			$mail = fnsacmail(
						array("email1"=>$linha["DES_EMAILUS"]),
						$linha["NOM_USUARIO"],
						"<html>"."<b>Token de autenticacao de script:</b> ".fnDecode($token)."</html>",
						'Token de Autenticacao',
						'Help Desk Bunker',
						$connAdm->connAdm(),
						connTemp($linha["COD_EMPRESA"],""),'3');
		}


		$_SESSION["TOKEN_SQL"]["TOKEN"] = $token;
		$_SESSION["TOKEN_SQL"]["VALIDADO"] = false;
		$_SESSION["TOKEN_SQL"]["DATAHORA"] = date("Y-m-d H:i:s");
		$_SESSION["TOKEN_SQL"]["ID_EXEC"] = date("Ymd_His")."_usu-".$_SESSION["SYS_COD_USUARIO"];

		$_SESSION["TOKEN_SQL"]["SQL"][$token]["DATAHORA"] = date("Y-m-d H:i:s");
		$_SESSION["TOKEN_SQL"]["SQL"][$token]["SQL"] = @$_POST["SCRIPT_SQL"];
		$_SESSION["TOKEN_SQL"]["SQL"][$token]["DATABASE"] = @$_POST["DATABASE"];
		
		echo "ok";

	}elseif ($_GET["acao"] == "valida"){
		
		$token = fnEncode($_POST["token"]);
		
		if ($token == $_SESSION["TOKEN_SQL"]["TOKEN"]){
		
			$_SESSION["TOKEN_SQL"]["VALIDADO"] = true;
			
			$log = "";
			$log .= "Usuario: ".$_SESSION["SYS_COD_USUARIO"]." - ".$_SESSION["SYS_NOM_USUARIO"].PHP_EOL;
			$log .= "HTTP_CLIENT_IP: ".@$_SERVER["HTTP_CLIENT_IP"].PHP_EOL;
			$log .= "HTTP_X_FORWARDED_FOR: ".@$_SERVER["HTTP_X_FORWARDED_FOR"].PHP_EOL;
			$log .= "REMOTE_ADDR: ".@$_SERVER["REMOTE_ADDR"].PHP_EOL;
			$log .= "HTTP_USER_AGENT: ".@$_SERVER["HTTP_USER_AGENT"].PHP_EOL;
			$log .= "SQL: ".$_SESSION["TOKEN_SQL"]["SQL"][$token]["SQL"].PHP_EOL;
			grava_log_sql($log);

			echo "ok";
		
		}else{
			
			echo "Token inv&aacute;lido!";
			$_SESSION["TOKEN_SQL"]["ERROS"] = (@$_SESSION["TOKEN_SQL"]["ERROS"] == ""?0:$_SESSION["TOKEN_SQL"]["ERROS"])+1;
			$_SESSION["TOKEN_SQL"]["DATAHORA"] = date("Y-m-d H:i:s");
		
		}
		
		//print_r($_SESSION);
		
	}elseif ($_GET["acao"] == "executa"){
		
		$bd = $_POST["bd"];
		$token = fnEncode($_POST["token"]);
		
		$log = "";
		$log .= PHP_EOL . "------------------------------------------------".PHP_EOL;

		
		if ($_SESSION["TOKEN_SQL"]["SQL"][$token]["DATABASE"][$bd] == "S"){

			$sql = "SELECT COD_SERVIDOR,COD_EMPRESA,IP,USUARIODB,SENHADB,NOM_DATABASE,
						(select C.DES_SERVIDOR from servidores C where C.COD_SERVIDOR = A.COD_SERVIDOR) as NOM_SERVIDOR FROM tab_database A WHERE COD_DATABASE=0".$bd;
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
			$erro = mysqli_error($conn);
			if ($erro <> ""){
				$log .= "Erro: ".$erro.PHP_EOL;
				echo "<span class='text-danger'>".$erro."</span>";

			}else{
				$linha = mysqli_fetch_assoc($arrayQuery);
				$log .= "Servidor: ".$linha["NOM_SERVIDOR"]." (".$linha["IP"].")".PHP_EOL;
				$log .= "Database: ".$linha["NOM_DATABASE"].PHP_EOL;
				$log .= "Usuerio DB: ".$linha["USUARIODB"].PHP_EOL;

				$conn = mysqli_connect($linha['IP'], $linha['USUARIODB'],fnDecode($linha['SENHADB']), $linha['NOM_DATABASE'],'3320');
				if (!$conn) {
					echo "<span class='text-danger'>Error: Unable to connect to MySQL. ".mysqli_connect_error()."</span>";
					$log .= "Erro ao conectar: ".mysqli_connect_errno().PHP_EOL;
				}else{

					$sql = $_SESSION["TOKEN_SQL"]["SQL"][$token]["SQL"];
					
					$arrayQuery = mysqli_multi_query($conn,$sql);
					do {
						$erro = mysqli_error($conn);
						if ($erro <> ""){
							$log .= "Erro: ".$erro.PHP_EOL;
							echo "<span class='text-danger'>".$erro."</span>";
						}else{
							if (mysqli_affected_rows($conn) >= 0){
								$rows = "Linhas afetadas: ".mysqli_affected_rows($conn);
								$log .= $rows.PHP_EOL;
								echo $rows;
							}
						
							$grid = "";
							if ($result = mysqli_store_result($conn)) {
								$grid .= "<table class='table table-bordered table-striped table-hover'>";
								$grid .= "<thead>";
								$fieldinfo = mysqli_fetch_fields($result);
								foreach ($fieldinfo as $val) {
									$grid .= "<th>" . $val->name . "</th>";
								}
								$grid .= "</thead>";
								$grid .= "<tbody>";
								while ($qrLista = mysqli_fetch_assoc($result)){
									$grid .= "<tr>";
									foreach ($fieldinfo as $val) {
										$grid .= "<td>" . $qrLista[$val->name] . "</td>";
									}
									$grid .= "</tr>";
								}
								$grid .= "</tbody>";
								$grid .= "</table>";
							}
							echo $grid;
						}
						if (mysqli_more_results($conn)) {
							echo "<div class='push'></div><hr>";
						}
					} while (mysqli_next_result($conn));
				}
			}		
		}

		echo "<div class='push'></div><hr>";
		$log .= "------------------------------------------------".PHP_EOL;
		grava_log_sql($log);

		
	}


	function grava_log_sql($txt){
		$dir = __DIR__ . "/logs_sql/" . $_SESSION["TOKEN_SQL"]["ID_EXEC"].".txt";
		$fp = fopen($dir, "a");

		fwrite($fp, $txt.PHP_EOL);
		fclose($fp);
	}
