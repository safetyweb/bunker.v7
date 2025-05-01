<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	$opcao = fnLimpaCampo($_GET['opcao']);

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	switch ($opcao) {
		case 'exc':

			$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));

			$sql = "UPDATE CLIENTES_EXTERNO 
					 SET LOG_IMPORT = 'S',
					 COD_EXCLUSA = $cod_usucada,
					 DAT_EXCLUSA = NOW()
					 WHERE COD_EMPRESA = $cod_empresa
					 AND COD_CLIENTE = $cod_cliente
					 ";

			mysqli_query(connTemp($cod_empresa,''),$sql);

		break;

		case 'unico':

			$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));

			$sqlCep = "SELECT NUM_CEPOZOF FROM CLIENTES_EXTERNO
					   WHERE COD_EMPRESA = $cod_empresa
					   AND COD_CLIENTE = $cod_cliente";

			$qrCep = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCep));

			// FNeSCREVE($qrCep[NUM_CEPOZOF]);

			if($qrCep['NUM_CEPOZOF'] != ""){

				$cep = fnLimpaDoc($qrCep['NUM_CEPOZOF']);

				$sqlCoord = "SELECT LATITUDE, LONGITUDE FROM CEPBR_GEO
						WHERE CEP = $cep";

				$qrCoord = mysqli_fetch_assoc(mysqli_query($DADOS_CEP->connUser(),$sqlCoord));

				$latitude = $qrCoord[LATITUDE];
				$longitude = $qrCoord[LONGITUDE];

			}else{

				$latitude = 0;
				$longitude = 0;

			}

			$sql = "INSERT INTO CLIENTES(
									COD_EMPRESA,
									NOM_CLIENTE,
									LOG_USUARIO,
									DES_EMAILUS,
									LOG_ESTATUS,
									LOG_TROCAPROD,
									NUM_RGPESSO,
									DAT_NASCIME,
									IDADE,
									DIA,
									MES,
									ANO,
									COD_ESTACIV,
									COD_SEXOPES,
									NUM_TENTATI,
									NUM_TELEFON,
									NUM_CELULAR,
									NUM_COMERCI,
									COD_EXTERNO,
									NUM_CARTAO,
									NUM_CGCECPF,
									DES_ENDEREC,
									NUM_ENDEREC,
									DES_COMPLEM,
									DES_BAIRROC,
									NUM_CEPOZOF,
									NOM_CIDADEC,
									COD_ESTADOF,
									LAT,
									LNG,
									COD_TPCLIENTE,
									DES_APELIDO,
									COD_PROFISS,
									COD_UNIVEND,
									DES_CONTATO,
									LOG_EMAIL,
									LOG_SMS,
									LOG_TELEMARK,
									LOG_FUNCIONA,
									NOM_PAI,
									NOM_MAE,
									KEY_EXTERNO,
									TIP_CLIENTE,
									DES_COMENT,
									COD_ESTADO,
									COD_MUNICIPIO,
									COD_INDICAD,
									COD_USUCADA,
									DAT_CADASTR,
									DAT_INDICAD
								) 
									SELECT COD_EMPRESA,
										   NOM_CLIENTE,
										   LOG_USUARIO,
										   DES_EMAILUS,
										   LOG_ESTATUS,
										   LOG_TROCAPROD,
										   NUM_RGPESSO,
										   DAT_NASCIME,
										   IDADE,
										   DIA,
										   MES,
										   ANO,
										   COD_ESTACIV,
										   COD_SEXOPES,
										   NUM_TENTATI,
										   NUM_TELEFON,
										   NUM_CELULAR,
										   NUM_COMERCI,
										   COD_CLIENTE,
										   NUM_CARTAO,
										   NUM_CGCECPF,
										   DES_ENDEREC,
										   NUM_ENDEREC,
										   DES_COMPLEM,
										   DES_BAIRROC,
										   NUM_CEPOZOF,
										   NOM_CIDADEC,
										   COD_ESTADOF,
										   $latitude,
										   $longitude,
										   COD_TPCLIENTE,
										   DES_APELIDO,
										   COD_PROFISS,
										   COD_UNIVEND,
										   DES_CONTATO,
										   LOG_EMAIL,
										   LOG_SMS,
										   LOG_TELEMARK,
										   LOG_FUNCIONA,
										   NOM_PAI,
										   NOM_MAE,
										   KEY_EXTERNO,
										   TIP_CLIENTE,
										   DES_COMENT,
										   COD_ESTADO,
										   COD_MUNICIPIO,
										   COD_INDICAD,
										   $cod_usucada,
										   NOW(),
										   NOW()
									FROM CLIENTES_EXTERNO
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_CLIENTE = $cod_cliente;
									";

			$sql .= "UPDATE CLIENTES_EXTERNO SET LOG_IMPORT = 'S' 
					 WHERE COD_EMPRESA = $cod_empresa
					 AND COD_CLIENTE = $cod_cliente;
					 ";

			fnEscreve($sql);

			mysqli_multi_query(connTemp($cod_empresa,''),$sql);
		break;

		case 'multiplo':

			$cod_cliente = json_decode($_POST['COD_CLIENTE']);
			$sql = "";

			for ($i=0; $i < count($cod_cliente) ; $i++) { 

				$sqlCep = "SELECT NUM_CEPOZOF FROM CLIENTES_EXTERNO
						   WHERE COD_EMPRESA = $cod_empresa
						   AND COD_CLIENTE = $cod_cliente[$i]";

				$qrCep = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCep));

				if($qrCep['NUM_CEPOZOF'] != ""){

					$cep = fnLimpaDoc($qrCep['NUM_CEPOZOF']);

					$sqlCoord = "SELECT LATITUDE, LONGITUDE FROM CEPBR_GEO
							WHERE CEP = $cep";

					$qrCoord = mysqli_fetch_assoc(mysqli_query($DADOS_CEP->connUser(),$sqlCoord));

					$latitude = $qrCoord[LATITUDE];
					$longitude = $qrCoord[LONGITUDE];

				}else{

					$latitude = 0;
					$longitude = 0;
					
				}

				$sql .= "INSERT INTO CLIENTES(
									COD_EMPRESA,
									NOM_CLIENTE,
									LOG_USUARIO,
									DES_EMAILUS,
									LOG_ESTATUS,
									LOG_TROCAPROD,
									NUM_RGPESSO,
									DAT_NASCIME,
									IDADE,
									DIA,
									MES,
									ANO,
									COD_ESTACIV,
									COD_SEXOPES,
									NUM_TENTATI,
									NUM_TELEFON,
									NUM_CELULAR,
									NUM_COMERCI,
									COD_EXTERNO,
									NUM_CARTAO,
									NUM_CGCECPF,
									DES_ENDEREC,
									NUM_ENDEREC,
									DES_COMPLEM,
									DES_BAIRROC,
									NUM_CEPOZOF,
									NOM_CIDADEC,
									COD_ESTADOF,
									LAT,
									LNG,
									COD_TPCLIENTE,
									DES_APELIDO,
									COD_PROFISS,
									COD_UNIVEND,
									DES_CONTATO,
									LOG_EMAIL,
									LOG_SMS,
									LOG_TELEMARK,
									LOG_FUNCIONA,
									NOM_PAI,
									NOM_MAE,
									KEY_EXTERNO,
									TIP_CLIENTE,
									DES_COMENT,
									COD_ESTADO,
									COD_MUNICIPIO,
									COD_INDICAD,
									COD_USUCADA,
									DAT_CADASTR,
									DAT_INDICAD
								) 
									SELECT COD_EMPRESA,
										   NOM_CLIENTE,
										   LOG_USUARIO,
										   DES_EMAILUS,
										   LOG_ESTATUS,
										   LOG_TROCAPROD,
										   NUM_RGPESSO,
										   DAT_NASCIME,
										   IDADE,
										   DIA,
										   MES,
										   ANO,
										   COD_ESTACIV,
										   COD_SEXOPES,
										   NUM_TENTATI,
										   NUM_TELEFON,
										   NUM_CELULAR,
										   NUM_COMERCI,
										   COD_CLIENTE,
										   NUM_CARTAO,
										   NUM_CGCECPF,
										   DES_ENDEREC,
										   NUM_ENDEREC,
										   DES_COMPLEM,
										   DES_BAIRROC,
										   NUM_CEPOZOF,
										   NOM_CIDADEC,
										   COD_ESTADOF,
										   $latitude,
										   $longitude,
										   COD_TPCLIENTE,
										   DES_APELIDO,
										   COD_PROFISS,
										   COD_UNIVEND,
										   DES_CONTATO,
										   LOG_EMAIL,
										   LOG_SMS,
										   LOG_TELEMARK,
										   LOG_FUNCIONA,
										   NOM_PAI,
										   NOM_MAE,
										   KEY_EXTERNO,
										   TIP_CLIENTE,
										   DES_COMENT,
										   COD_ESTADO,
										   COD_MUNICIPIO,
										   COD_INDICAD,
										   $cod_usucada,
										   NOW(), 
										   NOW() 
									FROM CLIENTES_EXTERNO
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_CLIENTE = $cod_cliente[$i];
									";

				$sql .= "UPDATE CLIENTES_EXTERNO SET LOG_IMPORT = 'S' 
					 WHERE COD_EMPRESA = $cod_empresa
					 AND COD_CLIENTE = $cod_cliente[$i];
					 ";

			}

			if($sql != ""){
				mysqli_multi_query(connTemp($cod_empresa,''),$sql);
			}

		break;
		
		default:

			$itens_por_pagina = $_GET['itens_por_pagina'];	
			$pagina = $_GET['idPage'];
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
			$cod_indicad = fnLimpaCampoZero($_POST['COD_INDICAD']);
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
			$nom_indicador = fnLimpaCampo($_REQUEST['NOM_INDICADOR']);

			if($nom_cliente != ""){
				$andCliente = "AND NOM_CLIENTE LIKE '%$nom_cliente%'";
			}else{
				$andCliente = "";
			}

			if($nom_indicador != ""){
				$andIndicador = "AND NOM_INDICADOR LIKE '%$nom_indicador%'";
			}else{
				$andIndicador = "";
			}

			if($cod_indicad != ""){
				$andCodIndicador = "AND COD_INDICAD = $cod_indicad";
			}else{
				$andCodIndicador = "";
			}
		
			$sql = "SELECT COD_CLIENTE 
					FROM CLIENTES_EXTERNO 
					WHERE COD_EMPRESA = $cod_empresa
					AND LOG_IMPORT = 'N'
					$andCliente
					$andIndicador
					$andCodIndicador
					";
			//fnTestesql(connTemp($cod_empresa,''),$sql);		
			//fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			// Filtro por Grupo de Lojas
			//include "filtroGrupoLojas.php";

			$sql = "SELECT COD_CLIENTE,
						   NOM_CLIENTE,
						   DAT_NASCIME,
						   DAT_CADASTR,
						   NOM_INDICADOR
					FROM CLIENTES_EXTERNO 
					WHERE COD_EMPRESA = $cod_empresa
					AND LOG_IMPORT = 'N'
					$andCliente
					$andIndicador
					$andCodIndicador
					order by NOM_CLIENTE desc 
					LIMIT $inicio,$itens_por_pagina
					";
			
			//fnEscreve($sql);
                                                   
			//fnTestesql(connTemp($cod_empresa,''),$sql);											
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
								  
			$count=0;
			while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
			{								

				$count++;

			?>
					<tr>
						<td class='text-center'><input type='checkbox' name='radio_<?=$count?>' onclick='attListaClientes()'>&nbsp;</td>
						<td><?=$qrApoia['COD_CLIENTE']?></td>
						<td><?=$qrApoia['NOM_CLIENTE']?></td>
						<td><?=$qrApoia['DAT_NASCIME']?></td>
						<td><?=fnDataFull($qrApoia['DAT_CADASTR'])?></td>
						<td><?=$qrApoia['NOM_INDICADOR']?></td>
						<td class="text-center">
							<small>
								<div class="btn-group dropdown dropleft">
									<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										ações &nbsp;
										<span class="fas fa-caret-down"></span>
									</button>
									<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
										<li><a href='javascript:void(0)' class="addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrApoia[COD_CLIENTE])?>&pop=true&op=LISTA" data-title="Busca Indicador">Buscar Indicador</a></li>
										<li><a href='javascript:void(0)' onclick='importaCliente("<?=fnEncode($qrApoia[COD_CLIENTE])?>","unico")'>Importar Apoiador</a></li>
										<li class="divider"></li>
										<li><a href='javascript:void(0)' onclick='importaCliente("<?=fnEncode($qrApoia[COD_CLIENTE])?>","exc")'>Excluir Apoiador</a></li>
										<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
									</ul>
								</div>
							</small>
						</td>
					</tr>

					<input type="hidden" name="ret_COD_CLIENTE_<?=$count?>" id="ret_COD_CLIENTE_<?=$count?>" value="<?=$qrApoia[COD_CLIENTE]?>">
			<?php

			}
			
		break;
	}

	

?>