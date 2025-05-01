<?php 
include '_system/_functionsMain.php';

	$cod_atendimento = fnLimpaCampoZero($_POST['COD_ATENDIMENTO']);
	$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
	$cod_refdown = fnLimpaCampo($_POST['COD_REFDOWN']);

		$sql = "SELECT * FROM ATENDIMENTO_ANEXO WHERE (COD_REFDOWN = $cod_refdown OR COD_ATENDIMENTO = $cod_atendimento) AND COD_EMPRESA = $cod_empresa ORDER BY DAT_CADASTR DESC";
														    			//fnEscreve($sql);
		$arrayquery = mysqli_query(connTemp($cod_empresa,''),$sql);

		$row_cnt = mysqli_num_rows($arrayquery);
		if ($row_cnt == 0) {
			echo "Não existem anexos a serem exibidos.";
		}																		
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