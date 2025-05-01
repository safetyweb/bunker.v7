<?php 

include "_system/_functionsMain.php"; 

$cod_empresa = fnLimpacampoZero(fnDecode($_GET['id']));
$cod_desafio = fnLimpacampoZero(fnDecode($_POST['COD_DESAFIO']));

$andLojasUsu = "";
$CarregaMaster='1';
$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='0'){

	$lojasUsuario = $_SESSION["SYS_COD_UNIVEND"];
	$arrLojasAut = explode(",", $_SESSION["SYS_COD_UNIVEND"]);
	$arrayLojasAut2 = str_replace(",", "|", $_SESSION["SYS_COD_UNIVEND"]);
	$andLojasUsu = "AND (DESAFIO_V2.COD_UNIVEND REGEXP '^($arrayLojasAut2)' OR DESAFIO_V2.COD_UNIVEND = '9999')";
	$optAllUnivend = "";
	$CarregaMaster='0';

}

if($cod_desafio != 0){

	$sqlExc = "DELETE FROM DESAFIO_V2 WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio; ";
	$sqlExc .= "DELETE FROM DESAFIO_CONTROLE_V2 WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio; ";
	$sqlExc .= "DELETE FROM FOLLOW_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio; ";

	mysqli_multi_query(connTemp($cod_empresa,''),$sqlExc);

}

$sqlDesafio = "SELECT DESAFIO_V2.*,
		(SELECT count(1) from DESAFIO_CONTROLE_V2 where DESAFIO_CONTROLE_V2.COD_DESAFIO = DESAFIO_V2.COD_DESAFIO) as hitsDesafio	
		FROM DESAFIO_V2 
		WHERE DESAFIO_V2.COD_EMPRESA = $cod_empresa 
		$andLojasUsu
		order by DAT_INI desc ";

$arrDesafio = mysqli_query(connTemp($cod_empresa,''),$sqlDesafio);

$count=0;
while ($qrListaDesafio = mysqli_fetch_assoc($arrDesafio)){	                                           
	$count++; 
	
	if ($qrListaDesafio['LOG_ATIVO'] == "S"){$desafioAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";}
	else {$desafioAtivo = "<i class='fas fa-times' aria-hidden='true' style='color: #F00;'></i>";}
	
?>

<tr>
<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaDesafio['DES_COR'] ?>; color: #fff;" ><i class="<?php echo $qrListaDesafio['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaDesafio['NOM_DESAFIO']; ?></td>
<td class='text-center'><?php echo fnValor($qrListaDesafio['hitsDesafio'],0); ?></td>
<td class='text-center'><?php echo $desafioAtivo; ?></td>
<td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_INI']); ?></td>
<td class="text-center"><small><?php echo fnDataShort($qrListaDesafio['DAT_FIM']); ?></td>
<td class="text-center"><small><?php echo fnValor($qrListaDesafio['VAL_METADES'],2); ?></td>
<td class="text-center">
<small>
	<div class="btn-group dropdown dropleft">
		<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			ações &nbsp;
			<span class="fas fa-caret-down"></span>
		</button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
			<li><a href='javascript:void(0)' class='addBox' data-url="action.php?mod=<?php echo fnEncode(1937)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($qrListaDesafio['COD_DESAFIO'])?>&pop=true" data-title="Desafio / <?php echo $qrListaDesafio['NOM_DESAFIO']; ?>">Editar </a></li>
			<li><a href="action.php?mod=<?php echo fnEncode(1946);?>&id=<?php echo fnEncode($cod_empresa);?>&idD=<?php echo fnEncode($qrListaDesafio['COD_DESAFIO']); ?>">Acessar Dash</a></li>
			<li class="divider"></li>
			<li><a href="javascript:void(0)" onclick='excTemplate("<?=fnEncode($qrListaDesafio[COD_DESAFIO])?>")'>Excluir</a></li>
		</ul>
	</div>
</small>
</td>
</tr>

<?php
  }										

?>
