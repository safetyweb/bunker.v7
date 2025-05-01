<?php 

	include '_system/_functionsMain.php'; 	

	echo fnDebug('true');
print_r($_POST);
	$cod_matriz = fnLimpaCampoZero($_POST['pk']);
	$cod_rateio = fnLimpaCampoZero($_POST['rateio']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);
	$classe = fnLimpaCampo($_POST['class']);
	$rateio = fnLimpaCampo($_POST['rateio']);
	$percent1 = fnLimpaCampo($_POST['percent1']);
	$percent2 = fnLimpaCampo($_POST['percent2']);
	$percent3 = fnLimpaCampo($_POST['percent3']);
	$cod_empresa = fnLimpaCampoZero($_POST['empresa']);
	$cod_univend = fnLimpaCampoZero($_POST['univend']);
	$cod_usuario = $_SESSION["SYS_COD_USUARIO"];

	//fnEscreve($valor);
	//fnEscreve($cod_rateio);
	//fnEscreve($campo);
	//fnEscreve($percent1);
	//fnEscreve($percent2);
	//fnEscreve($percent3);//


	if($cod_matriz == 0){
	
		$sql = "INSERT INTO MATRIZ_RATEIO(
							COD_RATEIO,
							COD_EMPRESA, 
							COD_UNIVEND, 
							COD_USUARIO,
							$campo
							) VALUES(
							$cod_rateio,
							$cod_empresa,
							$cod_univend,
							$cod_usuario,
							".fnValorSql($valor)."
							)";
							//echo $sql;
		mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
	}else{

		$sqlPercent = "SELECT $percent1, $percent2, $percent3 FROM MATRIZ_RATEIO WHERE COD_MATRIZ = $cod_matriz";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlPercent) or die(mysqli_error());
		$qrPer = mysqli_fetch_assoc($arrayQuery);

		//fnEscreve($sqlPercent);

		$valorSoma = fnValorSql($valor);

		$total = $qrPer[$percent1]+$qrPer[$percent2]+$qrPer[$percent3]+$valorSoma;

		if($total <= 100){
			//fnEscreve($qrPer[$percent1]+$qrPer[$percent2]+$qrPer[$percent3]+$valor);
			$sql = "UPDATE MATRIZ_RATEIO SET
							COD_ALTERAC = $cod_usuario,
							DAT_ALTERAC = NOW(),
							$campo =".fnValorSql($valor)."
							WHERE COD_MATRIZ = $cod_matriz";
			mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
			?>
			<script>
			var $el = $("tbody[data-univend=<?=$cod_univend?>]");
			$el.find("[name=<?=$campo?>]").val("<?=fnValorSql($valor)?>");
			calc_valores(<?=$cod_univend?>);
			</script>
			<?php 
		}else{
			$valorFinal = $valorSoma - ($total - 100);
			header('HTTP/1.1 400 Valor Invalido');
        	exit("<span class='msg-erro'><p style='color: #F00'><small>O valor máximo possível é: ".fnValor($valorFinal,2)."</small></p></span>");
		}
	}						
?>