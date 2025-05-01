<?php 

include '_system/_functionsMain.php'; 

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];	
$pagina = $_GET['idPage'];

$sql = "SELECT COD_COMUNICA FROM COMUNICACAO_AVULSA WHERE cod_empresa = $cod_empresa";	

	//fnEscreve($sql);
$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$total_itens_por_pagina = mysqli_num_rows($retorno);

$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

	//variavel para calcular o início da visualização com base na página atual
$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

$sql = "SELECT CA.*, CP.QTD_LISTA, CP.LOG_ATIVO, CP.DES_MENSAGEM FROM COMUNICACAO_AVULSA CA
LEFT JOIN COMUNICAAV_PARAMETROS CP ON CP.COD_LISTA = CA.COD_LISTA
WHERE CA.cod_empresa = $cod_empresa 
ORDER BY CA.DAT_CADASTR DESC
LIMIT $inicio,$itens_por_pagina";

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

$view = 100;

$count=0;
while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
	$count++;

	if($qrBuscaModulos['DES_MENSAGEM'] == ""){
		$status = "Sem mensagem para envio";
	}else if($qrBuscaModulos['LOG_ATIVO'] == 0){
		$status = "Lista enviada";
	}else{
		$status = "Enviando";
	}	

	?>

	<tr>
		<td><?php echo $qrBuscaModulos['NOM_COMUNICA']; ?></td>
		<td><small><?php echo fnDataFull($qrBuscaModulos['DAT_CADASTR']); ?></td>
			<td><small><?php echo fnValor($qrBuscaModulos['QTD_LISTA'],0); ?></td>
				<td class="text-center"><small><?=$status?></small></td>
				<td class="text-center">
					<small>
						<div class="btn-group dropdown dropleft">
							<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								ações &nbsp;
								<span class="fas fa-caret-down"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
								<li class="text-info"><a href='javascript:void(0)' class='addBox' data-url="action.php?mod=<?php echo fnEncode(1559)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrBuscaModulos[COD_COMUNICA])?>&pop=true" data-title=""><i class='fas fa-pencil'></i> Editar </a></li>
								<li class="text-success"><a href="action.do?mod=<?php echo fnEncode(1560);?>&id=<?php echo fnEncode($cod_empresa);?>&idL=<?php echo fnEncode($qrBuscaModulos[COD_LISTA]);?>"><i class='fas fa-external-link-square'></i> Acessar </a></li>
							</ul>
						</div>
					</small>
				</td>
			</tr>
			<?php 
		}

		?>