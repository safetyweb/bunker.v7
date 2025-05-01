<?php
include '../_system/_functionsMain.php';
$datahoraINI = date('Y-m-d H:i:s');
$datahorafim = date('Y-m-d H:i:s');
$datahoraINI = date('Y-m-d H:i:s', strtotime('-40 minute', strtotime($datahoraINI)));

$conadmf = $connAdm->connAdm();
$sqlempresa = "SELECT * from empresas e INNER JOIN tab_database t ON t.cod_empresa=e.COD_EMPRESA where e.LOG_ATIVO='S'";
$rwempresas = mysqli_query($conadmf, $sqlempresa);
while ($rsempresafull = mysqli_fetch_assoc($rwempresas)) {
	echo 'COD_EMPRESA   :' . $rsempresafull['COD_EMPRESA'] . '<br>';
	echo 'NOM_EMPRESA   :' . $rsempresafull['NOM_FANTASI'] . '<br>';

	$conncliente = connTemp($rsempresafull['COD_EMPRESA'], '');
	$sqlclientes = "SELECT * FROM clientes WHERE num_cartao!=0 and COD_EMPRESA=" . $rsempresafull['COD_EMPRESA'] . " and  DAT_CADASTR BETWEEN '$datahoraINI' AND '$datahorafim'";
	$rwclientes = mysqli_query($conncliente, $sqlclientes);
	while ($rscliente = mysqli_fetch_assoc($rwclientes)) {
		ob_start();
		$NOM_CLIENTEconsuta = explode(' ', $rscliente['NOM_CLIENTE']);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://adm.bunker.mk/wsjson/gender.do?login=diogo.master&senha=123456&idcliente=2&NOME=" . $NOM_CLIENTEconsuta[0],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Cookie: PHPSESSID=d0dig2mlmbdf4gjc8jqu8qc4iefjsf6ug747v2aq4ad438tglra0"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$retornoconsulta = json_decode($response, TRUE);
		/* echo '<pre>';
			print_r($retornoconsulta);
			echo '</pre>';*/



		if ($retornoconsulta['cod_msg'] == 3) {
			if ($retornoconsulta['PorcentagemAcerto'] >= '89%') {
				//echo 'Acima de 89%<br>';
				if ($retornoconsulta['Sexo_maiorPorcentagem'] == 'M' || $retornoconsulta['Sexo_maiorPorcentagem'] == 'm') {
					$sexo = '1';
				} elseif ($retornoconsulta['Sexo_maiorPorcentagem'] == 'F' || $retornoconsulta['Sexo_maiorPorcentagem'] == 'f') {
					$sexo = '2';
				} else {
					$sexo = '3';
				}

				//só alterar se o sexo for diferente
				if ($rscliente['COD_SEXOPES'] != $sexo) {
					echo 'Sexo antigo :' . $rscliente['COD_SEXOPES'] . '<br>';
					echo 'Sexo NOVO   :' . $sexo . '<br>';
					//update na base de dados
					$update = "UPDATE clientes SET COD_SEXOPES='$sexo' WHERE COD_CLIENTE=$rscliente[COD_CLIENTE] and cod_empresa='" . $rscliente['COD_EMPRESA'] . "'";
					mysqli_query($conncliente, $update);
					// echo '<br>'.$update.'<br>';
				}
			} else {
				//echo 'Menor que 89%<br>';
				//INSERIR NO  RELATORIO PARA GESTÃO FUTURA			

				$sqlvrificarcliente = "SELECT COD_CLIENTE,COD_EMPRESA from rel_inconsistencia 
				WHERE COD_CLIENTE=" . $rscliente['COD_CLIENTE'] . " and 
					  cod_empresa='" . $rscliente['COD_EMPRESA'] . "';";
				$rsclientecheck = mysqli_query($conncliente, $sqlvrificarcliente);
				if (mysqli_num_rows($rsclientecheck) <= '0') {

					$inseterro = "INSERT INTO rel_inconsistencia (COD_EMPRESA,
																COD_CLIENTE,
																SEXO,
																PORCENTO) 
															  VALUES 
															  ('" . $rscliente['COD_EMPRESA'] . "', 
															   '" . $rscliente['COD_CLIENTE'] . "',
															   '" . $sexo . "',
															   '" . $retornoconsulta['PorcentagemAcerto'] . "'
															   );";
					mysqli_query($conncliente, $inseterro);
				} else {
				}
			}
		}
		ob_end_flush();
		ob_flush();
		flush();
	}
}
