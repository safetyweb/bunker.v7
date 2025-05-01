<?php 

include "_system/_functionsMain.php";

if(isset($_GET['tpF'])){
	$campo = 'NOM_FOLLOW';
	$dat_agendame = "";
	$valor = fnLimpaCampo($_POST['NOM_FOLLOW']);
}else{
	$campo = 'COD_CLASSIFICA';
	$dat_agendame = fnDataSql($_POST['DAT_AGENDAME']);
	$valor = fnLimpaCampo($_POST['COD_CLASSIFICA']);
}

$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
$cod_desafio = fnLimpaCampo($_POST['COD_DESAFIO']);
$cod_cliente = fnLimpaCampo($_POST['COD_CLIENTE']);
$des_coment = fnLimpaCampo($_POST['DES_COMENT']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

if($dat_agendame != ""){

	$sql = "INSERT INTO FOLLOW_CLIENTE(
					COD_EMPRESA,
					COD_DESAFIO,
					COD_CLIENTE,
					$campo,
					DAT_AGENDAME,
					DES_COMENT,
					TIP_FOLLOW,
					COD_SAC,
					COD_USUCADA
					) VALUES(
					$cod_empresa,
					$cod_desafio,
					$cod_cliente,
					'$valor',
					'$dat_agendame',
					'$des_coment',
					1,
					0,
					$cod_usucada
					)";

}else{

	if (empty($_REQUEST['LOG_ENVIOWPP'])) {

		$log_enviowpp='N';

	}else{

		$log_enviowpp=$_REQUEST['LOG_ENVIOWPP'];

		if($log_enviowpp == 'S'){

			$sqlCel = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa";
			$qrCel = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCel));

			if($qrCel['NUM_CELULAR'] != ""){

				$cel = preg_replace("/[^0-9]/", "", $qrCel['NUM_CELULAR']);

				// fnEscreve($cel);

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://v4.chatpro.com.br/chatpro-8e47haohde/api/v1/send_message",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "{\r\n  \"menssage\": \"$des_coment\",\r\n  \"number\": \"$cel\"\r\n}",
				  CURLOPT_HTTPHEADER => array(
				    "Authorization: 70f1717e06036b4ab5bae4e4162c68ac811e2570",
				    "cache-control: no-cache"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo "cURL Error #:" . $err;
				} else {
				  // echo $response;
				}
			}

		}

	}

	$sql = "INSERT INTO FOLLOW_CLIENTE(
					COD_EMPRESA,
					COD_DESAFIO,
					COD_CLIENTE,
					$campo,
					DES_COMENT,
					TIP_FOLLOW,
					COD_SAC,
					COD_USUCADA
					) VALUES(
					$cod_empresa,
					$cod_desafio,
					$cod_cliente,
					'$valor',
					'$des_coment',
					1,
					0,
					$cod_usucada
					); ";

	$sql .= "UPDATE DESAFIO_CONTROLE SET LOG_CONCLUIDO = 'S' 
			 WHERE COD_EMPRESA = $cod_empresa 
			 AND COD_CLIENTE = $cod_cliente 
			 AND COD_DESAFIO = $cod_desafio; ";

}

//fnEscreve($sql);
mysqli_multi_query(connTemp($cod_empresa,''),$sql);

//setando locale da data
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$sql2 = "SELECT FC.*, CA.DES_CLASSIFICA FROM FOLLOW_CLIENTE FC 
		LEFT JOIN CLASSIFICA_ATENDIMENTO CA ON CA.COD_CLASSIFICA = FC.COD_CLASSIFICA 
		WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = $cod_cliente
		ORDER BY FC.DAT_CADASTR DESC";
		
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2);
		while($qrFollow = mysqli_fetch_assoc($arrayQuery)){

			$mes = strtoupper(strftime('%B', strtotime($qrFollow['DAT_CADASTR'])));
			$mes = substr("$mes", 0, 3);
		?>

			<div class="cd-timeline__container">
				<div class="cd-timeline__block2">
					<div class="cd-timeline__img"></div>
					<div class="cd-timeline__content">
						<h2><?=$qrFollow['DES_CLASSIFICA']?></h2>
						<p><?=$qrFollow['DES_COMENT']?></p>
						<span class="cd-timeline__date"><?php echo strftime('%d ', strtotime($qrFollow['DAT_CADASTR']))."".$mes; ?><br><span class="hora"><?php echo date("H:i", strtotime($qrFollow['DAT_CADASTR'])); ?></span></span>
					</div>
				</div>
			</div>

<?php 
} 
?>