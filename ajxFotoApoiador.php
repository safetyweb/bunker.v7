<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
	$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
	$opcao = fnLimpaCampo($_REQUEST['opcao']);
	$nom_arquivo = $cod_cliente."_webcam.jpg";

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	switch ($opcao) {
		case 'carregar':

			$sql = "SELECT * FROM FOTO_APOIADOR WHERE COD_CLIENTE = $cod_cliente";
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$qrFoto = mysqli_fetch_assoc($arrayQuery);

			if (isset($qrFoto)) {
				$nom_arquivo = "src='media/clientes/".$cod_empresa."/perfil/".$qrFoto['NOM_ARQUIVO']."'";
			}else{
				$nom_arquivo = "";
			}

			$filemtime = filemtime($qrFoto['NOM_ARQUIVO']);

		?>
		
		<img id="foto_perfil" class="foto" alt="Sem imagem"></img>
		<script type="text/javascript">
			var url = "media/clientes/<?=$cod_empresa?>/perfil/<?=$qrFoto[NOM_ARQUIVO]?>?rnd="+Math.random();
 			$('#foto_perfil').attr('src',url);
		</script>

		<?php 
													
		break;
		
		default:

			$sql = "DELETE FROM FOTO_APOIADOR WHERE COD_CLIENTE = $cod_cliente; ";

			mysqli_query(conntemp($cod_empresa,''),$sql);

			$sql = "INSERT INTO FOTO_APOIADOR(
								COD_EMPRESA,
								COD_CLIENTE,
								NOM_ARQUIVO,
								COD_USUCADA
								)VALUES(
								$cod_empresa,
								$cod_cliente,
								'$nom_arquivo',
								$cod_usucada
								); ";

			// fnEscreve($sql);

			mysqli_query(conntemp($cod_empresa,''),$sql);
			
		break;
	}

	

?>