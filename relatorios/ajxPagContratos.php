<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);
	$cod_indicad = fnLimpaCampoZero($_POST['COD_INDICAD']);
	$cod_univend = fnLimpaCampoArray($_POST['COD_UNIVEND']);
	$cod_estado = fnLimpaCampoZero($_POST['COD_ESTADO']);
	$cod_municipio = fnLimpaCampoZero($_POST['COD_MUNICIPIO']);

	// fnEscreve($cod_univend);

	if (empty($_REQUEST['LOG_PAGO'])) {
		$log_pago = 'N';
	} else {
		$log_pago = $_REQUEST['LOG_PAGO'];
	}
	
	
	if($cod_indicad != 0){
		$andIndicad = "AND a.COD_INDICAD=$cod_indicad ";
	}else{
		$andIndicad = "";
	}

	if($cod_estado != 0){
		$andEstado = "AND a.COD_ESTADO=$cod_estado ";
	}else{
		$andEstado = "";
	}

	if($cod_municipio != 0){
		$andMunicipio = "AND a.COD_MUNICIPIO=$cod_municipio ";
	}else{
		$andMunicipio = "";
	}

	if($cod_univend == 0){
		$cod_univend = $_SESSION['SYS_COD_UNIVEND'];
	}

	if($log_pago == "S"){
		$andPago = "and IFNULL((SELECT FORMAT(TRUNCATE(sum(val_credito),2),2,'pt_BR') FROM caixa WHERE caixa.cod_cliente=a.cod_cliente AND caixa.cod_contrat=i.cod_contrat AND caixa.tip_lancame='D' AND caixa.cod_exclusa=0),0) >0 ";
	}else{
		$andPago = "";
	}

	switch ($opcao) {

		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';		
           			       
			$sql = "SELECT 
					a.cod_cliente AS Codigo,
					a.cod_externo,
					case when a.LOG_ESTATUS='S' then
					'Ativo'
					when a.LOG_ESTATUS='N' then
					'Inativo'
					END STATUS,
					case when a.LOG_TERMO='S' then
					'Contrato Assinado'
					when a.LOG_TERMO='N' then
					'sem contrato assinado'
					END contrato,

					A.NOM_CLIENTE AS Colaborador,
					A.NUM_CGCECPF AS CPF,
					A.NUM_RGPESSO AS RG, 
					A.DES_EMAILUS AS Email,
					A.num_celular AS Celular,
					A.des_enderec as Endereço,
					A.num_enderec Numero,
					A.des_complem AS Complemento,
					A.des_bairroc AS Bairro,
					A.num_cepozof CEP,
					c.NOM_MUNICIPIO Cidade,
					d.uf Estado,
					e.num_pix AS conta,

					case when cod_profiss=364 then
					'Divulgador (Cabo Eleitoral)'
					when cod_profiss=365 then
					'Coordenador'
					when cod_profiss=366 then
					'Cessão de Serviço Voluntário'
					END AS cod_profis,
					F.NOM_UNIVEND AS campanha,
					h.des_filtro AS dobradas,
					a.cod_indicad,
					(SELECT nom_cliente FROM clientes g WHERE g.COD_CLIENTE=a.cod_indicad) AS acessor,
					(SELECT COUNT(*)FROM contrato_eleitoral i WHERE  i.cod_cliente=a.cod_cliente AND i.COD_EXCLUSA=0) qtd_contrato,
					IFNULL(FORMAT(TRUNCATE(i.VAL_CONTRAT,2),2,'pt_BR'),0) val_contrato,
					IFNULL((SELECT FORMAT(TRUNCATE(sum(val_credito),2),2,'pt_BR') FROM caixa WHERE caixa.cod_cliente=a.cod_cliente AND caixa.cod_contrat=i.cod_contrat AND caixa.tip_lancame='D' AND caixa.cod_exclusa=0),0) AS val_pago,
					case when tip_contrat = 1 then
					'Genérico'
					when tip_contrat = 2 then
					'Cabo Eleitoral'
					when tip_contrat = 3 then
					'Coordenador Cabo Eleitoral'
					when tip_contrat = 4 then
					'Cessão Serviços'
					when tip_contrat = 5 then
					'Cessão Gratuita de Veículos'
					END tipo_contrato


					FROM clientes a
					LEFT JOIN  MUNICIPIOS C ON A.COD_MUNICIPIO=C.COD_MUNICIPIO AND C.COD_ESTADO=35
					LEFT JOIN ESTADO D ON  A.COD_ESTADO=D.COD_ESTADO
					LEFT JOIN DADOS_BANCARIOS e ON 	A.COD_CLIENTE=E.COD_CLIENTE
					INNER JOIN unidadevenda f ON f.COD_UNIVEND=a.COD_UNIVEND AND f.COD_EMPRESA=a.cod_empresa
					LEFT JOIN cliente_filtros g ON g.cod_cliente=a.cod_cliente AND g.cod_empresa=a.cod_empresa AND cod_tpfiltro=43
					LEFT JOIN filtros_cliente h ON h.cod_filtro=g.cod_filtro AND h.cod_tpfiltro=43
					LEFT JOIN contrato_eleitoral i ON i.cod_cliente=a.cod_cliente AND i.COD_EXCLUSA=0

					WHERE a.cod_empresa=$cod_empresa AND 
					      a.cod_indicad!=29007 AND 
					      a.cod_univend in($cod_univend) 
					      $andIndicad 
					      $andPago
					      $andEstado
					      $andMunicipio
					      ORDER BY nom_cliente";
					  
					
			fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			
				
				$arquivo = fopen($arquivoCaminho, 'w',0);
                        
				while($headers=mysqli_fetch_field($arrayQuery)){
					 $CABECHALHO[]=$headers->name;
				}
				fputcsv ($arquivo,$CABECHALHO,';','"','\n');
			  
				while ($row=mysqli_fetch_assoc($arrayQuery)){  	
					
					// $row[val_contrato] = fnValor($row['val_contrato'],2);
					// $row[val_pago] = fnValor($row['val_pago'],2);
					//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                    //$textolimpo = json_decode($limpandostring, true);
                    $array = array_map("utf8_decode", $row);
                    fputcsv($arquivo, $array, ';', '"', '\n');	
				}
				fclose($arquivo);

		break;
		    
		case 'paginar':

				$sql = "SELECT 1
						FROM clientes a
						INNER JOIN unidadevenda f ON f.COD_UNIVEND=a.COD_UNIVEND AND f.COD_EMPRESA=a.cod_empresa
						WHERE a.cod_empresa=$cod_empresa AND 
						      a.cod_indicad!=29007 AND 
						      a.cod_univend in($cod_univend) 
						      $andIndicad
						      $andPago";
				//fnTestesql(connTemp($cod_empresa,''),$sql);		
				//fnEscreve($sql);

				$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
				$totalitens_por_pagina = mysqli_num_rows($retorno);

				$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

				// Filtro por Grupo de Lojas
				//include "filtroGrupoLojas.php";

				$sql = "SELECT 
						a.cod_cliente AS Codigo,
						a.cod_externo,
						a.LOG_TERMO as TERMO,
						case when a.LOG_ESTATUS='S' then
						'Ativo'
						when a.LOG_ESTATUS='N' then
						'Inativo'
						END STATUS,
						case when a.LOG_TERMO='S' then
						'Contrato Assinado'
						when a.LOG_TERMO='N' then
						'sem contrato assinado'
						END contrato,

						A.NOM_CLIENTE AS Colaborador,
						A.NUM_CGCECPF AS CPF,
						A.NUM_RGPESSO AS RG, 
						A.DES_EMAILUS AS Email,
						A.num_celular AS Celular,
						A.des_enderec as Endereço,
						A.num_enderec Numero,
						A.des_complem AS Complemento,
						A.des_bairroc AS Bairro,
						A.num_cepozof CEP,
						c.NOM_MUNICIPIO Cidade,
						d.uf Estado,
						e.num_pix AS conta,

						case when cod_profiss=364 then
						'Divulgador (Cabo Eleitoral)'
						when cod_profiss=365 then
						'Coordenador'
						when cod_profiss=366 then
						'Cessão de Serviço Voluntário'
						END AS cod_profis,
						F.NOM_UNIVEND AS campanha,
						h.des_filtro AS dobradas,
						a.cod_indicad,
						(SELECT nom_cliente FROM clientes g WHERE g.COD_CLIENTE=a.cod_indicad) AS acessor,
						(SELECT COUNT(*)FROM contrato_eleitoral i WHERE  i.cod_cliente=a.cod_cliente AND i.COD_EXCLUSA=0) qtd_contrato,
						i.cod_contrat AS Numero,
						IFNULL(FORMAT(TRUNCATE(i.VAL_CONTRAT,2),2,'pt_BR'),0) val_contrato,
						IFNULL((SELECT FORMAT(TRUNCATE(sum(val_credito),2),2,'pt_BR') FROM caixa WHERE caixa.cod_cliente=a.cod_cliente AND caixa.cod_contrat=i.cod_contrat AND caixa.tip_lancame='D' AND caixa.cod_exclusa=0),0) AS val_pago,
						case when tip_contrat = 1 then
						'Genérico'
						when tip_contrat = 2 then
						'Cabo Eleitoral'
						when tip_contrat = 3 then
						'Coordenador Cabo Eleitoral'
						when tip_contrat = 4 then
						'Cessão Serviços'
						when tip_contrat = 5 then
						'Cessão Gratuita de Veículos'
						END tipo_contrato


						FROM clientes a
						LEFT JOIN  MUNICIPIOS C ON A.COD_MUNICIPIO=C.COD_MUNICIPIO AND C.COD_ESTADO=35
						LEFT JOIN ESTADO D ON  A.COD_ESTADO=D.COD_ESTADO
						LEFT JOIN DADOS_BANCARIOS e ON 	A.COD_CLIENTE=E.COD_CLIENTE
						INNER JOIN unidadevenda f ON f.COD_UNIVEND=a.COD_UNIVEND AND f.COD_EMPRESA=a.cod_empresa
						LEFT JOIN cliente_filtros g ON g.cod_cliente=a.cod_cliente AND g.cod_empresa=a.cod_empresa AND cod_tpfiltro=43
						LEFT JOIN filtros_cliente h ON h.cod_filtro=g.cod_filtro AND h.cod_tpfiltro=43
						LEFT JOIN contrato_eleitoral i ON i.cod_cliente=a.cod_cliente AND i.COD_EXCLUSA=0

						WHERE a.cod_empresa=$cod_empresa AND 
						      a.cod_indicad!=29007 AND 
						      a.cod_univend in($cod_univend) 
						      $andIndicad 
						      $andPago
						      $andEstado
					      	  $andMunicipio
						      ORDER BY nom_cliente 
						LIMIT $inicio,$itens_por_pagina
						";
				//fnEscreve($sql);
                //fnTestesql(connTemp($cod_empresa,''),$sql);											
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
									  
				$count=0;
				while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
				  {								

				  	if(strtoupper($qrCupom['TERMO']) == "S"){
				  		$contratoAssinado = "<span class='fal fa-check text-success'></span>";
				  	}else{
				  		$contratoAssinado = "<span class='fal fa-times text-danger'></span>";
				  	}

					$count++;	
					echo"
						<tr>
						  <td>".$qrCupom['Codigo']."</td>
						  <td>".$qrCupom['cod_externo']."</td>
						  <td>".$qrCupom['Colaborador']."</td>
						  <td>".$qrCupom['CPF']."</td>
						  <td>".$qrCupom['tipo_contrato']."</td>
						  <td class='text-center'>".$contratoAssinado."</td>
						  <td>".$qrCupom['cod_profis']."</td>
						  <td>".$qrCupom['campanha']."</td>
						  <td>".$qrCupom['dobradas']."</td>
						  <td>".$qrCupom['acessor']."</td>
						  <td>".$qrCupom['qtd_contrato']."</td>
						  <td>".$qrCupom['val_contrato']."</td>
						  <td>".$qrCupom['val_pago']."</td>
						</tr>
						"; 
					  }									

			break; 		
	}
?>