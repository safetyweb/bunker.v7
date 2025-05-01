<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_template = fnLimpaCampoZero(fnDecode($_POST['COD_TEMPLATE']));

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	if ($_REQUEST['LOG_ALL'] == 'N') {
		$joinTempl = "INNER JOIN TEMPLATE_EMAIL_CAMPANHA TEC ON TEC.COD_TEMPLATE = TE.COD_TEMPLATE AND TEC.COD_CAMPANHA = $cod_campanha";
	}else{
		$joinTempl = "";
	}

	// fnEscreve($_REQUEST['LOG_ALL']);
	// fnEscreve($joinTempl);

	if($cod_template != 0){
		$sqlExc = "UPDATE TEMPLATE_EMAIL SET 
				   LOG_ATIVO = 'N',
				   COD_EXCLUSA = $_SESSION[SYS_COD_USUARIO],
				   DAT_EXCLUSA = NOW()
				   WHERE cod_empresa = $cod_empresa 
			 	   AND COD_TEMPLATE = $cod_template";	
		mysqli_query(connTemp($cod_empresa,''),$sqlExc) or die(mysqli_error());
	}


	$sql = "SELECT DISTINCT TE.COD_TEMPLATE FROM TEMPLATE_EMAIL TE
			$joinTempl
			WHERE TE.COD_EMPRESA = $cod_empresa 
			AND TE.LOG_ATIVO = 'S'";	

	$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$total_itens_por_pagina = mysqli_num_rows($retorno);
	
	$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

	$sql = "SELECT DISTINCT TE.* FROM TEMPLATE_EMAIL TE
			$joinTempl
			WHERE TE.COD_EMPRESA = $cod_empresa 
			AND TE.LOG_ATIVO = 'S'
			ORDER BY TE.DAT_CADASTR DESC
			LIMIT $inicio,$itens_por_pagina";

	// fnEscreve($sql);
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	
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
							<li><a data-url='action.php?mod=<?php echo fnEncode(1411)?>&id=<?php echo fnEncode($cod_empresa)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true&rnd=<?=rand()?>' data-title="Template: <?=$qrBuscaModulos[NOM_TEMPLATE]?>" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"lg")} catch(err) {}'>Acessar</a></li>
							<li class="divider"></li>
							<li><a href="javascript:void(0)" onclick='excTemplate("<?=fnEncode($qrBuscaModulos[COD_TEMPLATE])?>")'>Excluir</a></li>
						</ul>
					</div>
           		</small>
           	   </td>
	        </tr>
<?php 
	}

?>