<?php 
include '_system/_functionsMain.php';

	$cod_atendimento = fnLimpaCampoZero($_POST['COD_ATENDIMENTO']);
	$nom_arquivo = $_POST['SAC_ANEXO'];
	$dat_cadastr = date("Y-m-d H:i:s");
	$cod_refdown = $_POST['COD_REFDOWN'];
	$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];
	$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
	$primeiroUp = $_POST['PRIMEIRO_UP'];
	/*fnEscreve($cod_atendimento);
	fnEscreve($nom_arquivo);
	fnEscreve($cod_refdown);
	fnEscreve($usu_cadastr);
	fnEscreve($cod_empresa);
	fnEscreve($primeiroUp);
	*/

		$sql = "INSERT INTO ATENDIMENTO_ANEXO(
							COD_ATENDIMENTO,
							NOM_ARQUIVO,
							DAT_CADASTR,
							COD_REFDOWN,
							USU_CADASTR,
							COD_EMPRESA
							) VALUES(
							$cod_atendimento,
							'$nom_arquivo',
							'$dat_cadastr',
							$cod_refdown,
							$usu_cadastr,
							$cod_empresa
							)";
							
		//fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa,''),$sql);

		// if($primeiroUp == "S"){
		// 	$sql = "UPDATE CONTADOR SET NUM_CONTADOR = $cod_refdown WHERE COD_CONTADOR = 2";
		// 	mysqli_query(connTemp($cod_empresa,''),$sql);
		// }

		$sql = "SELECT * FROM ATENDIMENTO_ANEXO WHERE COD_REFDOWN = $cod_refdown AND COD_EMPRESA = $cod_empresa
		ORDER BY DAT_CADASTR DESC
		";

		$arrayquery = mysqli_query(connTemp($cod_empresa,''),$sql);
		while($qrAnexo = mysqli_fetch_assoc($arrayquery)){

		?>				

						<tr>
							<td><a class="download" href="../media/clientes/<?php echo $cod_empresa; ?>/helpdesk/<?php echo $qrAnexo['NOM_ARQUIVO']; ?>" target="_blank" download><span class="fa fa-download"></span></a></td>
							<td><?php echo $qrAnexo['NOM_ARQUIVO']; ?></td>
							<td><small><?php echo date("d/m/Y",strtotime($qrAnexo['DAT_CADASTR'])) ?></small>&nbsp;<small><?php echo date("H:i:s",strtotime($qrAnexo['DAT_CADASTR'])) ?></small></td>
						</tr>
<?php 
	}
?>