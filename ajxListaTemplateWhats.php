<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	$sql = "SELECT COD_TEMPLATE FROM TEMPLATE_WHATS WHERE cod_empresa = $cod_empresa";	
			
	//fnEscreve($sql);
	$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$total_itens_por_pagina = mysqli_num_rows($retorno);
	
	$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

	$sql = "SELECT * FROM TEMPLATE_WHATS
	WHERE cod_empresa = $cod_empresa 
	ORDER BY DAT_CADASTR DESC
	LIMIT $inicio,$itens_por_pagina";
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	
	$count=0;
	while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
		$count++;	

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
								<a data-url="action.php?mod=<?=fnEncode(1576)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&idT=<?=fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>&tipo=<?=fnEncode('ALT')?>&pop=true" 
								   data-title="Template do Email" 
								   onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'>Editar
								</a>
							</li>
						</ul>
					</div>
           		</small>
           	   </td>
	        </tr>
<?php 
	}

?>