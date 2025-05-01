<?php

	include '_system/_functionsMain.php';
	// require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	//echo fnDebug('true');
	//fnEscreve('Entra no ajax');

	// use Box\Spout\Reader\ReaderFactory;
	// use Box\Spout\Common\Type;

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$acao = fnLimpaCampo(@$_GET['acao']);

	switch($acao){

		case 'verificaFila':

			$sqlVerifica = "SELECT * FROM EMAIL_FILA
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_CAMPANHA = $cod_campanha
							AND COD_ENVIADO = 'N'
							AND DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') = NOW()";

			$arrVerifica = mysqli_query(connTemp($cod_empresa,""),trim($sqlVerifica));
			$num_verifica = mysqli_num_rows($arrVerifica);

			echo fnLimpaCampoZero($num_verifica);

		break;

		case 'cancelar':

			$cancelaCamp = "DELETE FROM EMAIL_FILA
							WHERE COD_CAMPANHA = $cod_campanha
							AND COD_EMPRESA = $cod_empresa;


							UPDATE CAMPANHA SET 
											LOG_CANCELA = 'S',
											DAT_CANCELA = NOW()
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CAMPANHA = $cod_campanha";

			mysqli_multi_query(connTemp($cod_empresa,""),trim($cancelaCamp));
			

			// file_get_contents("http://externo.bunker.mk/twilo/cancelamento.php?COD_EMPRESA=$cod_empresa&COD_CAMPANHA=$cod_campanha");

		break;

		case 'loadMore':

			$limite = $_GET['itens'];

			$sql3 = "SELECT ELT.COD_LOTE, 
								ELT.DAT_AGENDAMENTO, 
								ELT.DAT_CADASTR, 
								-- ELT.COD_STATUSUP,
								ELT.LOG_ENVIO,
								ELT.DES_PATHARQ,
								ELT.COD_PERSONAS,
								ELT.COD_CONTROLE,
								ELT.QTD_LISTA,
								ELT.COD_GERACAO,
								TE.NOM_TEMPLATE
						FROM SMS_LOTE ELT
						LEFT JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ELT.COD_EXT_TEMPLATE
						WHERE ELT.COD_CAMPANHA = $cod_campanha 
						AND ELT.COD_EMPRESA = $cod_empresa
						AND COD_LOTE != 0
						AND LOG_TESTE = 'N'
						ORDER BY ELT.DAT_CADASTR DESC
						LIMIT $limite,20";

				$arrayLotes = mysqli_query(connTemp($cod_empresa,""),trim($sql3));

				while($qrLote = mysqli_fetch_assoc($arrayLotes)){

					$count++;

					if($qrLote['DAT_CADASTR'] != ''){
						$dat_cadastr = fnDataFull($qrLote['DAT_CADASTR']);
						$dat_agendamento_lote = fnDataFull($qrLote['DAT_AGENDAMENTO']);
						$urlAnexo = '<a href="'.$qrLote['DES_PATHARQ'].'" download><span class="fa fa-download"></span></a>';

						if($qrLote['LOG_ENVIO'] == 'P'){
							$loteSync = '<span class="fas fa-clock text-warning"></span>';
							$syncMsg = "Aguardando processamento";
						}else if($qrLote['LOG_ENVIO'] == 'N'){
							$loteSync = '<span class="fas fa-calendar-check text-info"></span>';
							$syncMsg = "Enfileirado para envio";
						}else if($qrLote['LOG_ENVIO'] == 'S'){
							$loteSync = '<span class="fas fa-check text-success"></span>';
							$syncMsg = "Enviado";
						}else{
							$loteSync = '<span class="fas fa-exclamation-triangle text-danger"></span>';
							$syncMsg = "Falha na geração do lote";
							$urlAnexo = "";
						}
					}else{
						$dat_cadastr = "";
						$loteSync = '<span class="fas fa-times text-danger"></span>';
						$syncMsg = "Sincronizando... aguarde.";
					}

					$sqlPers = "SELECT DES_PERSONA FROM PERSONA WHERE COD_PERSONA IN($qrLote[COD_PERSONAS])";
					$arrayPers = mysqli_query(connTemp($cod_empresa,''),$sqlPers);
					$personas = "";

					// fnescreve($qrLote[COD_PERSONAS]);

					while ($qrPers = mysqli_fetch_assoc($arrayPers)) {
						$personas = $personas.$qrPers['DES_PERSONA'].", ";
					}

					$personas = rtrim(rtrim($personas,' '),',');

?>

					<tr>
						<td class="text-center"><small><?=$urlAnexo?></small></td>
						<td><small><small><?=$qrLote['COD_GERACAO']?></small>&nbsp;Geração do lote #<?=$qrLote['COD_CONTROLE']?>/<?=$qrLote['COD_LOTE']?></small></td>
						<td class="text-center"><small><?=$qrLote['NOM_TEMPLATE']?></small></td>
						<td class="text-center"><small><?=$personas?></small></td>
						<td class="text-center"><small><?=fnValor($qrLote['QTD_LISTA'],0)?></small></td>
						<td><small><?=$dat_cadastr?></small></td>
						<td><small><?=$dat_agendamento_lote?></small></td>
						<td><small><?=$syncMsg?></small></td>
						<td class="text-center"><small><?=$loteSync?></small></td>
					</tr>

<?php

				$tot_qtd += $qrLote['QTD_LISTA'];

			}


		break;

	}

?>