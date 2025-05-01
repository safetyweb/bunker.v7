<?php 
include '_system/_functionsMain.php';

	$cod_chamado = fnLimpaCampoZero($_POST['COD_CHAMADO']);
	$nom_arquivo = $_POST['SAC_ANEXO'];
	$dat_cadastr = date("Y-m-d H:i:s");
	$cod_refdown = $_POST['COD_REFDOWN'];
	$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];
	$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
	$primeiroUp = $_POST['PRIMEIRO_UP'];
	/*fnEscreve($cod_chamado);
	fnEscreve($nom_arquivo);
	fnEscreve($cod_refdown);
	fnEscreve($usu_cadastr);
	fnEscreve($cod_empresa);
	fnEscreve($primeiroUp);
	*/

		$sql = "INSERT INTO SAC_ANEXO(
							COD_CHAMADO,
							NOM_ARQUIVO,
							DAT_CADASTR,
							COD_REFDOWN,
							USU_CADASTR,
							COD_EMPRESA
							) VALUES(
							$cod_chamado,
							'$nom_arquivo',
							'$dat_cadastr',
							$cod_refdown,
							$usu_cadastr,
							$cod_empresa
							)";
							
		//fnEscreve($sql);
		mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());

		// if($primeiroUp == "S"){
		// 	$sql = "UPDATE CONTADOR SET NUM_CONTADOR = $cod_refdown WHERE COD_CONTADOR = 2";
		// 	mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
		// }

		$sql = "SELECT * FROM SAC_ANEXO WHERE COD_REFDOWN = $cod_refdown AND COD_EMPRESA = $cod_empresa
		ORDER BY DAT_CADASTR DESC
		";

		$arrayquery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
		while($qrAnexo = mysqli_fetch_assoc($arrayquery)){

			if($qrAnexo['NOM_ARQUIVO'] < "2019-08-29 12:00:00"){
				$urlAnexo = "../media/clientes/".$cod_empresa."/helpdesk/".$qrAnexo['NOM_ARQUIVO'];
			}else{
				$urlAnexo = "../media/clientes/3/helpdesk/".$cod_empresa."/".$qrAnexo['NOM_ARQUIVO'];
			}

?>

			<tr>
				<td><a href="<?=$urlAnexo?>"><span class="fa fa-download"></span></a></td>
				<td><?php echo $qrAnexo['NOM_ARQUIVO']; ?></td>
				<td><small><?php echo date("d/m/Y",strtotime($qrAnexo['DAT_CADASTR'])) ?></small>&nbsp;<small><?php echo date("H:i:s",strtotime($qrAnexo['DAT_CADASTR'])) ?></small></td>
			</tr>
<?php 
		}
?>