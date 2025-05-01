<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_template = fnLimpaCampoZero(fnDecode($_POST['COD_TEMPLATE']));

	$opcao = $_GET['opcao'];
	$clone = $_GET['clone'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	if ($_REQUEST['LOG_ALL'] == 'N') {
		$joinTempl = "INNER JOIN TEMPLATE_PUSH_CAMPANHA TEC ON TEC.COD_TEMPLATE = TE.COD_TEMPLATE AND TEC.COD_CAMPANHA = $cod_campanha
					  LEFT JOIN campanha CP ON CP.COD_CAMPANHA=TEC.COD_CAMPANHA";
		$datCamp = ", CP.DAT_CADASTR AS DATA_CAMPANHA";
	}else{
		$joinTempl = "";
		$datCamp = "";
	}

	// fnEscreve($_REQUEST['LOG_ALL']);
	// fnEscreve($cod_template);
	// fnEscreve($clone);

	if($cod_template != 0){

		if($clone == 'true'){

			$sql = "INSERT INTO TEMPLATE_PUSH(
									COD_EMPRESA,
									LOG_ATIVO,
									NOM_TEMPLATE,
									ABV_TEMPLATE,
									DES_TEMPLATE,
									COD_USUCADA
					   	  		) SELECT $cod_empresa,
					   	  				 'S',
					   	  				 CONCAT(NOM_TEMPLATE,'(2)'),
					   	  				 ABV_TEMPLATE,
					   	  				 DES_TEMPLATE,										 
					   	  				 $_SESSION[SYS_COD_USUARIO]
					   	  		  FROM TEMPLATE_PUSH 
					   	  		  WHERE COD_EMPRESA = $cod_empresa
					   	  		  AND COD_TEMPLATE = $cod_template; ";

			// FNeSCREVE($sql);

			mysqli_query(connTemp($cod_empresa,''),$sql);

		}else{

			$sqlExc = "UPDATE TEMPLATE_PUSH SET 
					   LOG_ATIVO = 'N',
					   COD_EXCLUSA = $_SESSION[SYS_COD_USUARIO],
					   DAT_EXCLUSA = NOW()
					   WHERE cod_empresa = $cod_empresa 
				 	   AND COD_TEMPLATE = $cod_template";

			mysqli_query(connTemp($cod_empresa,''),$sqlExc);

		}

		// include "_system/func_dinamiza/Function_dinamiza.php";
		// delHtml($token,$cod_externo)

	}

	$sql = "SELECT COD_TEMPLATE FROM TEMPLATE_PUSH WHERE cod_empresa = $cod_empresa AND LOG_ATIVO = 'S'";	
			
	//fnEscreve($sql);
	$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$total_itens_por_pagina = mysqli_num_rows($retorno);
	
	$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

	$sql = "SELECT * FROM TEMPLATE_PUSH
	WHERE cod_empresa = $cod_empresa 
	AND LOG_ATIVO = 'S'
	ORDER BY DAT_CADASTR DESC
	LIMIT $inicio,$itens_por_pagina";
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	
	$count=0;
	while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
		$count++;	

		// fnEscreve('teste');

		if($qrBuscaModulos['LOG_ATIVO'] == "S"){
			$ativo = "<span class='fas fa-check text-success' style='padding: 5px 5px;'></span>";
		}else{
			$ativo = "<span class='fas fa-times text-danger' style='padding: 5px 5px;'></span>";
		}

		if($qrBuscaModulos['DAT_ALTERAC'] != ""){
			$alteradoEm = fnDataShort($qrBuscaModulos['DAT_ALTERAC']);
		}else{
			$alteradoEm = "";
		}

		?>

			<tr>
	           <td><?=$qrBuscaModulos['NOM_TEMPLATE']?></td>
	           <td><small><?=fnDataFull($qrBuscaModulos['DAT_CADASTR'])?></td>
	           <td><small><?=$alteradoEm?></td>
	           <td class='text-center'>
	                 <?=$ativo?>
	           </td>
	           <td class="text-center">
           		<small>
           			<div class="btn-group dropdown dropleft">
						<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ações &nbsp;
							<span class="fas fa-caret-down"></span>
					    </button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
							<li>
								<a data-url="action.php?mod=<?=fnEncode(1877)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&idT=<?=fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>&tipo=<?=fnEncode('ALT')?>&pop=true" 
								   data-title="Template Push" 
								   onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'>Editar
								</a>
							</li>
							<li class="divider"></li>
							<li><a href="javascript:void(0)" onclick='clonaTemplate("<?=fnEncode($qrBuscaModulos[COD_TEMPLATE])?>")'>Clonar Template</a></li>
							<li><a href="javascript:void(0)" onclick='excTemplate("<?=fnEncode($qrBuscaModulos[COD_TEMPLATE])?>")'>Excluir</a></li>
						</ul>
					</div>
           		</small>
           	   </td>
	        </tr>
<?php 
	}

?>