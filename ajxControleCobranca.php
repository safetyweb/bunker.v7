<?php
include '_system/_functionsMain.php';

$conadmmysql = $connAdm->connAdm();

$opcao = $_GET['opcao'];

$cod_usucada = fnDecode($_GET['idU']);
$cod_empresa = $_POST['codEmpresa'];
$log_msgcobr = $_POST['valorCheckbox'];

$cod_univend = $_POST['codUnivend'];
$cod_emprDetail = $_GET['ide'];

switch ($opcao) {
	case 'empresa':
	$sql = "UPDATE empresas SET LOG_MSGCOBR = '$log_msgcobr', COD_ALTERAC = $cod_usucada WHERE COD_EMPRESA = $cod_empresa";
	mysqli_query($conadmmysql, $sql);

	$sql2 = "SELECT * FROM unidadevenda WHERE COD_EMPRESA = $cod_empresa";
	$query = mysqli_query($conadmmysql, $sql2);

	while ($result2 = mysqli_fetch_assoc($query)){ 
	    $sqlUni = "UPDATE unidadevenda SET LOG_MSGCOBR = '$log_msgcobr', COD_ALTERAC = $cod_usucada WHERE COD_UNIVEND = {$result2['COD_UNIVEND']}";
	    mysqli_query($conadmmysql, $sqlUni);
	}
	break;

	case 'univend':
	$sql = "UPDATE unidadevenda SET LOG_MSGCOBR = '$log_msgcobr', COD_ALTERAC = $cod_usucada WHERE COD_UNIVEND = $cod_univend";
	mysqli_query($conadmmysql, $sql);
    break;

	default:

	$sql = "SELECT * FROM unidadevenda WHERE COD_EMPRESA = $cod_emprDetail";
	$arrayQuery = mysqli_query($conadmmysql, $sql);
	?>

	<tr>
		<th></th>
		<th></th>
		<th></th>
		<th class="text-center" style="font-weight: normal;">Unidade</th>
		<th class="text-center" style="font-weight: normal;">Nome Fantasia</th>
		<th class="text-center"style="font-weight: normal;">Msg Cobrança</th>
	</tr>
	<?php

	while ($result = mysqli_fetch_assoc($arrayQuery)) {
		?>
		<tr id='UNIVEND_<?php echo $result['COD_UNIVEND']; ?>'>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-center"><small style="font-weight: normal;"><?=$result['COD_UNIVEND'] . "- " . $result['NOM_UNIVEND']?></small></td>
			<td class="text-center" style="font-weight: normal;"><small><?=$result['NOM_FANTASI']?></small></td>
			<td class="text-center">
				<label class="switch switch-small">
					<input type="checkbox" name="LOG_MSGCOBRUNI_<?=$result['COD_UNIVEND']?>" id="LOG_MSGCOBRUNI_<?=$result['COD_UNIVEND']?>" class="switch" value="S" <?= $result['LOG_MSGCOBR'] == 'S' ? 'checked' : '' ?>>
					<span></span>
				</label>
			</td>
		</tr>
		<?php
	}
	break;
}
?>
