<?php 

	include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CELULAR']));
	$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
	$des_origem = fnLimpaCampo($_REQUEST['DES_ORIGEM']);
	$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
	$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);

	//fnEscreve($dat_ini);

	if($des_origem != ""){
		$andOrigem = "AND AD.DES_ORIGEM = '$des_origem'";
	}else{
		$andOrigem = "";
	}

	if($cod_hotel != "" && $cod_hotel != "0"){
		$andHotel = "AND AD.COD_HOTEL IN($cod_hotel)";
	}else{
		$andHotel = "";
	}

	if($cod_chale != "" && $cod_chale != "0"){
		$andChale = "AND AD.COD_CHALE = $cod_chale";
	}else{
		$andChale = "";
	}

	if($num_celular != ""){
		$andCelular = "AND AD.NUM_CELULAR = '$num_celular'";
	}else{
		$andCelular = "";
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
			
			$sql = "SELECT AD.COD_ACESSO, 
						   AD.DES_ORIGEM, 
						   AD.NUM_CELULAR, 
						   AD.DAT_CADASTR, 
						   AD.DAT_INI, 
						   AD.COD_HOTEL AS LOCALIDADES, 
						   AD.COD_CHALE AS CHALE
					FROM ACESSOS_ADORAI AD
					WHERE AD.COD_EMPRESA = $cod_empresa
					AND AD.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andOrigem
					$andHotel
					$andChale
					$andCelular
					ORDER BY DAT_INI ASC, DAT_CADASTR DESC";

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

				if($row['LOCALIDADES'] == "2957,3010,3008,956" || $row['LOCALIDADES'] == "2957,3010,956,3008"){

					$nomeHotel = "Todas as Localidades";

				}else{

					$hoteis = explode(",", $row['LOCALIDADES']);

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

				$row[LOCALIDADES] = $nomeHotel;
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
		
		default:
		
			$sql = "SELECT AD.* FROM ACESSOS_ADORAI AD
					WHERE AD.COD_EMPRESA = $cod_empresa
					AND AD.DAT_INI >= '$dat_ini 00:00:00'
					$andOrigem
					$andHotel
					$andChale
					$andCelular";

			// fnEscreve($sql);
			
			$retorno = mysqli_query(conntemp($cod_empresa,""),$sql);
			$total_itens_por_pagina = mysqli_num_rows($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			$sql = "SELECT AD.* FROM ACESSOS_ADORAI AD
					WHERE AD.COD_EMPRESA = $cod_empresa
					AND AD.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andOrigem
					$andHotel
					$andChale
					$andCelular
					ORDER BY DAT_INI ASC, DAT_CADASTR DESC
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
				
				echo "
					<tr>
						<td>" . $qrBuscaModulos['COD_ACESSO'] . "</td>
						<td>" . $qrBuscaModulos['DES_ORIGEM'] . "</td>
						<td>" . $qrBuscaModulos['NUM_CELULAR'] . "</td>
						<td><small>" . fnDataFull($qrBuscaModulos['DAT_CADASTR']) . "</small></td>
						<td><small>" . fnDataShort($qrBuscaModulos['DAT_INI']) . "</small></td>
						<td>" . $nomeHotel . "</td>
						<td>" . $nomeChale . "</td>
					</tr>
				";

			}

		break;

	}

?>