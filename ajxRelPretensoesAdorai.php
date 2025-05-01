<?php 

	include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CELULAR']));
	$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
	$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$dat_acesso = fnDataSql($_POST['DAT_ACESSO']);

	//fnEscreve($dat_ini);

	if($dat_acesso != "" && $dat_acesso != "0"){
		$andAcesso = "AND LA.DAT_ACESSO BETWEEN '$dat_acesso 00:00:00' AND '$dat_acesso 23:59:59'";
	}else{
		$andAcesso = "";
	}

	if($dat_ini != "" && $dat_ini != "0"){
		$andIni = "AND AND LA.DAT_INI = '$dat_ini'";
	}else{
		$andIni = "";
	}

	if($dat_fim != "" && $dat_fim != "0"){
		$andfim = "AND AND LA.DAT_FIM = '$dat_fim'";
	}else{
		$andfim = "";
	}

	if($cod_hotel != "" && $cod_hotel != "0"){
		$andHotel = "AND LA.COD_HOTEL IN($cod_hotel)";
	}else{
		$andHotel = "";
	}

	if($cod_chale != "" && $cod_chale != "0"){
		$andChale = "AND LA.COD_CHALE = $cod_chale";
	}else{
		$andChale = "";
	}

	if($cod_atendente != "" && $cod_atendente != "0"){
		$andAtendente = "AND LA.COD_ATENDENTE = $cod_atendente";
	}else{
		$andAtendente = "";
	}

	if($num_celular != ""){
		$andCelular = "AND LA.NUM_CELULAR = '$num_celular'";
	}else{
		$andCelular = "";
	}

	if($log_agrupa == "S"){
		$groupBy = "GROUP BY LA.NUM_CELULAR";
	}else{
		$groupBy = "";
	}

	$ARRAY_UNIDADE1=array(
				   'sql'=>"SELECT COD_UNIVEND,COD_EXTERNO,COD_EMPRESA,NOM_FANTASI,NOM_UNIVEND FROM UNIDADEVENDA WHERE COD_EMPRESA=$cod_empresa AND COD_EXCLUSA=0 AND LOG_ESTATUS = 'S'",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>$connAdm->connAdm(),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

	$ARRAY_UNIDADE2=array(
				   'sql'=>"SELECT COD_EXTERNO, NOM_QUARTO FROM ADORAI_CHALES WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>conntemp($cod_empresa,""),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
	$ARRAY_CHALES=fnUnivend($ARRAY_UNIDADE2);

	switch ($opcao) {

		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
			
			$sql = "SELECT LA.COD_LINK, 
						   LA.NUM_CELULAR, 
						   LA.DAT_INI AS CHECK_IN, 
						   LA.DAT_FIM AS CHECK_OUT, 
						   LA.COD_PROPRIEDADE AS LOCALIDADE, 
						   LA.COD_CHALE AS CHALE,
						   LA.DAT_ACESSO, 
						   LA.DES_CANAL AS ORIGEM
					FROM LINK_ADORAI LA
					WHERE LA.NUM_CELULAR != ''
					$andAcesso
					$andIni
					$andfim
					$andHotel
					$andChale
					$andAtendente
					$andCelular
					$groupBy
					ORDER BY DAT_ACESSO DESC";

			fnEscreve($sql);
			
			$arrayQuery = mysqli_query(conntemp($cod_empresa,""),$sql);

			$arquivo = fopen($arquivoCaminho, 'w',0);
                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}
			
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
			while ($row=mysqli_fetch_assoc($arrayQuery)){
				 	
				$nomeHotel = "";
				$nomeChale = "";

				if($row['LOCALIDADE'] == "2957,3010,3008,956" || $row['LOCALIDADE'] == "2957,3010,956,3008"){

					$nomeHotel = "Todas as Localidades";

				}else{

					$hoteis = explode(",", $row['LOCALIDADE']);

					foreach ($hoteis as $codExtHotel) {
						$NOM_ARRAY_UNIDADE=(array_search($codExtHotel, array_column($ARRAY_UNIDADE, 'COD_EXTERNO')));
						$nomeHotel .= $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['NOM_FANTASI'].", ";
					}

				}

				$nomeHotel = rtrim(ltrim(trim($nomeHotel),","),",");

				if($row['CHALE'] != 0){
					$NOM_ARRAY_CHALE=(array_search($row['CHALE'], array_column($ARRAY_CHALES, 'COD_EXTERNO')));
					$nomeChale = $ARRAY_CHALES[$NOM_ARRAY_CHALE]['NOM_QUARTO'];
				}

				$row[LOCALIDADE] = $nomeHotel;
				$row[CHALE] = fnAcentos($nomeChale);
				
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"', '\n');
				
				// echo "<pre>";
				// print_r($row);
				// echo "<pre>";
			}
			fclose($arquivo);

		break;

		case 'ok':

		// fnEscreve('entrou');

			$tipo = $_GET['tipo'];

			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
			$cod_link = fnLimpaCampoZero(fnDecode($_POST['COD_LINK']));
			$count = fnLimpaCampoZero(fnDecode($_POST['COUNT']));

			if($tipo == "okReserva"){

				$dat = "DAT_OK";
				$cod = "COD_OK";

			}else{

				$dat = "DAT_FECHAMENTO";
				$cod = "COD_FECHAMENTO";

			}
		
			$sql = "UPDATE LINK_ADORAI SET
								$dat = NOW(),
								$cod = $cod_usucada
					WHERE COD_LINK = $cod_link";

			mysqli_query(connTemp($cod_empresa,''),$sql);

			$sql = "SELECT LA.*, US.NOM_USUARIO FROM LINK_ADORAI LA 
					LEFT JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_ATENDENTE
					WHERE LA.NUM_CELULAR != ''
					AND COD_LINK = $cod_link";

			// fnEscreve($sql);

			$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

			$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

			$nomeHotel = "";
			$nomeChale = "";

			if($qrBuscaModulos['COD_HOTEL'] == "2957,3010,3008,956" || $qrBuscaModulos['COD_HOTEL'] == "2957,3010,956,3008"){

				$nomeHotel = "Todas as Localidades";

			}else{

				$hoteis = explode(",", $qrBuscaModulos['COD_HOTEL']);

				foreach ($hoteis as $codExtHotel) {
					$NOM_ARRAY_UNIDADE=(array_search($codExtHotel, array_column($ARRAY_UNIDADE, 'COD_EXTERNO')));
					$nomeHotel .= $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['NOM_FANTASI'].", ";
				}

			}

			$nomeHotel = rtrim(ltrim(trim($nomeHotel),","),",");

			if($qrBuscaModulos['COD_CHALE'] != 0){
				$NOM_ARRAY_CHALE=(array_search($qrBuscaModulos['COD_CHALE'], array_column($ARRAY_CHALES, 'COD_EXTERNO')));
				$nomeChale = $ARRAY_CHALES[$NOM_ARRAY_CHALE]['NOM_QUARTO'];
			}

			if($qrBuscaModulos['DAT_OK'] != ''){
				$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'><span class='fa fa-check'></span></a>";
			}else{
				$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okMisto(\"".fnEncode($qrBuscaModulos['COD_LINK'])."\",\"okReserva\")'><span class='fa fa-flag'></span></a>";
			}

			if($qrBuscaModulos['DAT_FECHAMENTO'] != ''){
				$log_fechado = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'><span class='fa fa-thumbs-up'></span></a>";
			}else{
				$log_fechado = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okMisto(\"".fnEncode($qrBuscaModulos['COD_LINK'])."\",\"okFechamento\")'><span class='fa fa-thumbs-down'></span></a>";
			}

			if($qrBuscaModulos['VAL_RESERVA'] != ""){
				$val_reserva = $qrBuscaModulos['VAL_RESERVA'];
			}else{
				$parts = parse_url($qrBuscaModulos['DES_LINK_ORIGEM']);
				parse_str($parts['query'], $query);
				$val_reserva = base64_decode($query[iv]);
			}
		
?>

			<td><small><?=$qrBuscaModulos['COD_LINK']?></small></td>
			<td><small><?=$qrBuscaModulos['NUM_CELULAR']?></small></td>
			<td><small><?=fnDataShort($qrBuscaModulos['DAT_INI'])?></small></td>
			<td><small><?=fnDataShort($qrBuscaModulos['DAT_FIM'])?></small></td>
			<td><small><?=$nomeHotel?></small></td>
			<td><small><?=$nomeChale?></small></td>
			<td class="text-right"><small><?=fnValor($val_reserva,2)?></small></td>
			<td><small><?=$qrBuscaModulos['DES_CANAL']?></small></td>
			<td><small><?=fnDataFull($qrBuscaModulos['DAT_ACESSO'])?></small></td>
			<td>
			  	<a href="#" class="editable-atendente" 
				  	data-type='select' 
				  	data-title='Editar Atentente'
				  	data-id="<?=fnEncode($cod_empresa)?>"
				  	data-pk="<?=fnencode($qrBuscaModulos['COD_LINK'])?>" 
				  	data-name="COD_ATENDENTE" 
				  	data-count="<?php echo $count; ?>"><?=$qrBuscaModulos['NOM_USUARIO']?>
			  		
			  	</a>
		  	</td>
			<td><small><?=$log_ok?></small></td>
			<td><small><?=$log_fechado?></small></td>
<?php 

			

		break;
		
		default:
		
			$sql = "SELECT LA.* FROM LINK_ADORAI LA
					WHERE LA.NUM_CELULAR != ''
					$andAcesso
					$andIni
					$andfim
					$andHotel
					$andChale
					$andAtendente
					$andCelular";

			// fnEscreve($sql);
			
			$retorno = mysqli_query(conntemp($cod_empresa,""),$sql);
			$total_itens_por_pagina = mysqli_num_rows($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			$sql = "SELECT LA.*, US.NOM_USUARIO FROM LINK_ADORAI LA
					LEFT JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_ATENDENTE
					WHERE LA.NUM_CELULAR != ''
					$andAcesso
					$andIni
					$andfim
					$andHotel
					$andChale
					$andAtendente
					$andCelular
					$groupBy
					ORDER BY DAT_ACESSO DESC
					LIMIT $inicio, $itens_por_pagina";

			// fnEscreve($sql);

			$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

			$count = 0;
			while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

				$count++;

				$nomeHotel = "";
				$nomeChale = "";

				if($qrBuscaModulos['COD_HOTEL'] == "2957,3010,3008,956" || $qrBuscaModulos['COD_HOTEL'] == "2957,3010,956,3008"){

					$nomeHotel = "Todas as Localidades";

				}else{

					$hoteis = explode(",", $qrBuscaModulos['COD_HOTEL']);

					foreach ($hoteis as $codExtHotel) {
						$NOM_ARRAY_UNIDADE=(array_search($codExtHotel, array_column($ARRAY_UNIDADE, 'COD_EXTERNO')));
						$nomeHotel .= $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['NOM_FANTASI'].", ";
					}

				}

				$nomeHotel = rtrim(ltrim(trim($nomeHotel),","),",");

				if($qrBuscaModulos['COD_CHALE'] != 0){
					$NOM_ARRAY_CHALE=(array_search($qrBuscaModulos['COD_CHALE'], array_column($ARRAY_CHALES, 'COD_EXTERNO')));
					$nomeChale = $ARRAY_CHALES[$NOM_ARRAY_CHALE]['NOM_QUARTO'];
				}

				if($qrBuscaModulos['DAT_OK'] != ''){
					$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'><span class='fa fa-check'></span></a>";
				}else{
					$log_ok = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okMisto(\"".fnEncode($qrBuscaModulos['COD_LINK'])."\",\"okReserva\")'><span class='fa fa-flag'></span></a>";
				}

				if($qrBuscaModulos['DAT_FECHAMENTO'] != ''){
					$log_fechado = "<a href='javascript:void(0);' class='btn btn-xs btn-success transparency'><span class='fa fa-thumbs-up'></span></a>";
				}else{
					$log_fechado = "<a href='javascript:void(0);' class='btn btn-xs btn-danger transparency' onclick='okMisto(\"".fnEncode($qrBuscaModulos['COD_LINK'])."\",\"okFechamento\")'><span class='fa fa-thumbs-down'></span></a>";
				}

				if($qrBuscaModulos['VAL_RESERVA'] != ""){
					$val_reserva = $qrBuscaModulos['VAL_RESERVA'];
				}else{
					$parts = parse_url($qrBuscaModulos['DES_LINK_ORIGEM']);
					parse_str($parts['query'], $query);
					$val_reserva = base64_decode($query[iv]);
				}

?>
				
				
					<tr id='<?=fnEncode($qrBuscaModulos['COD_LINK'])?>'>
						<td><small><?=$qrBuscaModulos['COD_LINK']?></small></td>
						<td><small><?=$qrBuscaModulos['NUM_CELULAR']?></small></td>
						<td><small><?=fnDataShort($qrBuscaModulos['DAT_INI'])?></small></td>
						<td><small><?=fnDataShort($qrBuscaModulos['DAT_FIM'])?></small></td>
						<td><small><?=$nomeHotel?></small></td>
						<td><small><?=$nomeChale?></small></td>
						<td class="text-right"><small><?=fnValor($val_reserva,2)?></small></td>
						<td><small><?=$qrBuscaModulos['DES_CANAL']?></small></td>
						<td><small><?=fnDataFull($qrBuscaModulos['DAT_ACESSO'])?></small></td>
						<td>
						  	<a href="#" class="editable-atendente" 
							  	data-type='select' 
							  	data-title='Editar Atentente'
							  	data-id="<?=fnEncode($cod_empresa)?>"
							  	data-pk="<?=fnencode($qrBuscaModulos['COD_LINK'])?>" 
							  	data-name="COD_ATENDENTE" 
							  	data-count="<?php echo $count; ?>"><?=$qrBuscaModulos['NOM_USUARIO']?>
						  		
						  	</a>
					  	</td>
						<td><small><?=$log_ok?></small></td>
						<td><small><?=$log_fechado?></small></td>
					</tr>

					<script type="text/javascript">
						$(function(){
							$('.editable-atendente').editable({ 
						    	emptytext: '_______________',  
						        source: atendentes,
						        url: 'ajxAtendentePretensoes.php',
					    		ajaxOptions:{type:'post'},
					    		params: function(params) {
							        params.count = $(this).data('count');
							        params.id = $(this).data('id');
							        return params;
							    },
					    		success:function(data){
									console.log(data);
								}
						    });
						});

					</script>

<?php 
				

			}

		break;

	}

?>