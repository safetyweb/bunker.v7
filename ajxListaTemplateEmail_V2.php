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
		$joinTempl = "INNER JOIN TEMPLATE_EMAIL_CAMPANHA TEC ON TEC.COD_TEMPLATE = TE.COD_TEMPLATE AND TEC.COD_CAMPANHA = $cod_campanha
					  LEFT JOIN campanha CP ON CP.COD_CAMPANHA=TEC.COD_CAMPANHA";
		$datCamp = ", CP.DAT_CADASTR AS DATA_CAMPANHA";
	}else{
		$joinTempl = "";
		$datCamp = "";
	}

	// fnEscreve($_REQUEST['LOG_ALL']);
	// fnEscreve($joinTempl);

	if($cod_template != 0){

		if($clone == 'true'){

			$sql = "INSERT INTO TEMPLATE_EMAIL(
									COD_EMPRESA,
									LOG_ATIVO,
									NOM_TEMPLATE,
									ABV_TEMPLATE,
									DES_TEMPLATE,
									DES_ASSUNTO,
									DES_REMET,
									END_REMET,
									EMAIL_RESPOSTA,
									LOG_OPT,
									TXT_LINKOPT,
									TAG_LINKOPT,
									TXT_OPT,
									TAG_OPT,
									COD_USUCADA
					   	  		) SELECT $cod_empresa,
					   	  				 'S',
					   	  				 CONCAT(NOM_TEMPLATE,'(2)'),
					   	  				 ABV_TEMPLATE,
					   	  				 DES_TEMPLATE,
					   	  				 DES_ASSUNTO,
										 DES_REMET,
										 END_REMET,
										 EMAIL_RESPOSTA,
										 LOG_OPT,
										 TXT_LINKOPT,
										 TAG_LINKOPT,
										 TXT_OPT,
										 TAG_OPT,
					   	  				 $_SESSION[SYS_COD_USUARIO]
					   	  		  FROM TEMPLATE_EMAIL 
					   	  		  WHERE COD_EMPRESA = $cod_empresa
					   	  		  AND COD_TEMPLATE = $cod_template; ";

			$sql .= "INSERT INTO MODELO_EMAIL(
									COD_EMPRESA,
									COD_TEMPLATE,
									DES_ASSUNTO,
									DES_REMET,
									DES_TEMPLATE,
									NOM_PAGINA,
									LOG_OPTOUT,
									COD_USUCADA
					   	  		) SELECT $cod_empresa,
					   	  				 (SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL 
									 		WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $_SESSION[SYS_COD_USUARIO]),
					   	  				 DES_ASSUNTO,
										 DES_REMET,
					   	  				 DES_TEMPLATE,
					   	  				 NOM_PAGINA,
										 LOG_OPTOUT,
					   	  				 $_SESSION[SYS_COD_USUARIO]
					   	  		  FROM MODELO_EMAIL 
					   	  		  WHERE COD_EMPRESA = $cod_empresa
					   	  		  AND COD_TEMPLATE = $cod_template; ";

			$sql .= "INSERT INTO TEMPLATE_EMAIL_CAMPANHA(
									COD_EMPRESA, 
									COD_TEMPLATE,
									COD_CAMPANHA, 
									COD_USUCADA
								) VALUES(
									$cod_empresa,
									(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL 
									 WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $_SESSION[SYS_COD_USUARIO]),
									$cod_campanha,
									$_SESSION[SYS_COD_USUARIO]
								); ";

			// FNeSCREVE($sql);

			mysqli_multi_query(connTemp($cod_empresa,''),$sql);

		}else{

			$sqlExc = "UPDATE TEMPLATE_EMAIL SET 
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


	$sql = "SELECT DISTINCT TE.COD_TEMPLATE FROM TEMPLATE_EMAIL TE
			$joinTempl
			WHERE TE.COD_EMPRESA = $cod_empresa 
			AND TE.LOG_ATIVO = 'S'";	

	$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
	$total_itens_por_pagina = mysqli_num_rows($retorno);
	// fnEscreve($sql);
	
	$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
	// fnEscreve($numPaginas);

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

	$sql = "SELECT DISTINCT TE.*
			$datCamp
			FROM TEMPLATE_EMAIL TE
			$joinTempl
			WHERE TE.COD_EMPRESA = $cod_empresa 
			AND TE.LOG_ATIVO = 'S'
			ORDER BY TE.DAT_CADASTR DESC
			LIMIT $inicio,$itens_por_pagina";

	// fnEscreve($sql);
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	
	$count=0;
	while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
		$count++;	

		if($qrBuscaModulos['COD_EXT_TEMPLATE'] != ""){
			$sincronia = "<span class='fas fa-check text-success' style='padding: 5px 5px;'></span>";
		}else{
			$sincronia = "<span class='fas fa-times text-danger' style='padding: 5px 5px;'></span>";
		}

		?>

			<tr>
	           <td><?php echo $qrBuscaModulos['NOM_TEMPLATE']; ?></td>
	           <td><small><?php echo fnDataFull($qrBuscaModulos['DAT_CADASTR']); ?></td>
	           <td><small><?php echo fnDataFull($qrBuscaModulos['DAT_ALTERAC']); ?></td>
	           <td class='text-center'>
	                 <?=$sincronia?>
	           </td>
	           <td class="text-center">
           		<small>
           			<div class="btn-group dropdown dropleft">
						<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ações &nbsp;
							<span class="fas fa-caret-down"></span>
					    </button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
							<li><a data-url="action.php?mod=<?php echo fnEncode(1409)?>&id=<?php echo fnEncode($cod_empresa)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true&rnd=<?=rand()?>" data-title="Template do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'>Editar</a></li>
							<li><a data-url='action.php?mod=<?php echo fnEncode(1644)?>&id=<?php echo fnEncode($cod_empresa)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true&rnd=<?=rand()?>' data-title="Template: <?=$qrBuscaModulos[NOM_TEMPLATE]?>" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"lg")} catch(err) {}'>Acessar</a></li>
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

	if($numPaginas == 0){
		$numPaginas = 1;
	}

?>

<script type="text/javascript">
	$('#paginacao').twbsPagination('destroy');
	carregarPaginacao("<?=$numPaginas?>");
	// console.log(<?=$numPaginas?>);
</script>