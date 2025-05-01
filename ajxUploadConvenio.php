<?php 
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
$nom_origem = fnLimpaCampo($_POST['NOM_ORIGEM']);
$nom_referen = fnLimpaCampo($_POST['NOM_REFEREN']);
$cod_tpAnexo = fnLimpaCampoZero($_POST['COD_TPANEXO']);
$tp_cont = fnLimpaCampo($_POST['TP_CONT']);
$TP_ANEXO = fnLimpaCampo($_POST['TP_ANEXO']);

if($TP_ANEXO != 'COD_CONVENI'){
	$cod_conveni = $_POST['COD_CONVENI'].",";
	$COD_CONVENI = 'COD_CONVENI,';
}else{
	$cod_conveni=" ";
	$COD_CONVENI=" ";
}

$sqlCont = "SELECT COD_CONTADOR, NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCont) or die(mysqli_error());
$qrCont = mysqli_fetch_assoc($arrayQuery);

//fnEscreve($sqlCont);

$cod_contador = $qrCont['COD_CONTADOR'];
$num_contador = $qrCont['NUM_CONTADOR'];


$sql = "INSERT INTO ANEXO_CONVENIO(
					COD_EMPRESA,
					COD_PROVISORIO,
					NOM_ORIGEM,
					NOM_REFEREN,
					COD_USUCADA,
					$COD_CONVENI
					$TP_ANEXO
					) VALUES(
					$cod_empresa,
					$num_contador,
					'$nom_origem',
					'$nom_referen',
					$_SESSION[SYS_COD_USUARIO],
					$cod_conveni
					$cod_tpAnexo
					);

		UPDATE CONTADOR SET
				NUM_CONTADOR = ($num_contador+1)
				WHERE COD_CONTADOR = $cod_contador;
				";
mysqli_multi_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

//fnEscreve($sql);

?>
