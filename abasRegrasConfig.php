<?php
//inicialização

if ($tip_campanha == 23) {
	$abaCli = 2013;
}

$aba1022 = "";
$aba2013 = "";
$aba1041 = "";
$aba1057 = "";
$aba1061 = "";
$aba1612 = "";
$aba1063 = "";
$aba1169 = "";
$block_produtoEspec = "";


$url_programa = fnDecode($_GET['idc']);

//fnEscreve($tip_campanha);

switch ($abaCli) {
	case 1022: //modelo 
		$aba1022 = "active";
		break;
	case 2013: //cfg hotsite 
		$aba2013 = "active";
		break;
	case 1057: //extras
		$aba1057 = "active";
		break;
	case 1041: //resgates
		$aba1041 = "active";
		break;
	case 1061: //produtos
		$aba1061 = "active";
		break;
	case 1063: //produtos específicos
		$aba1063 = "active";
		break;
	case 1406: //numero da sorte
		$aba1406 = "active";
		break;
	case 1612: //numero da sorte
		$aba1612 = "active";
		break;
		//default:
		//code to be executed if n is different from all labels;
}

$sql = " SELECT 
		(SELECT COUNT(*) FROM CAMPANHAREGRA B WHERE B.COD_CAMPANHA=A.COD_CAMPANHA) OK_REGRA,
		(SELECT LOG_PRODUTO FROM CAMPANHAREGRA C WHERE C.COD_CAMPANHA=A.COD_CAMPANHA) USA_PRODUTO,
		(SELECT LOG_CATPROD FROM CAMPANHAREGRA C WHERE C.COD_CAMPANHA=A.COD_CAMPANHA) LOG_CATPROD,
		(SELECT COUNT(*) FROM CAMPANHAPRODUTO E WHERE E.COD_CAMPANHA=A.COD_CAMPANHA AND E.COD_EXCLUSAO = 0)OK_PRODUTO,
		(SELECT COUNT(*) FROM CAMPANHARESGATE D WHERE D.COD_CAMPANHA=A.COD_CAMPANHA)OK_RESGATE,
		(SELECT COUNT(*) FROM VANTAGEMEXTRA E WHERE E.COD_CAMPANHA=A.COD_CAMPANHA)OK_EXTRA,
		(SELECT COUNT(*) FROM CAMPANHA_HOTSITE CH WHERE CH.COD_CAMPANHA = A.COD_CAMPANHA)OK_HOTSITE
		FROM CAMPANHA A
		WHERE A.COD_CAMPANHA=$cod_campanha ";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrVerificaRegras = mysqli_fetch_assoc($arrayQuery);
//fnEscreve($sql);		 
$ok_regra = $qrVerificaRegras['OK_REGRA'];
if ($ok_regra > 0) {
	$ico_regra = "fa-check";
	$cor_regra = "text-success";
} else {
	$ico_regra = "text-danger";
	$cor_regra = "fa-times";
}

$ok_hotsite = $qrVerificaRegras['OK_HOTSITE'];
if ($ok_hotsite > 0) {
	$ico_regraHot = "fa-check";
	$cor_regraHot = "text-success";
} else {
	$ico_regraHot = "text-danger";
	$cor_regraHot = "fa-times";
}

$ok_numSorte = $qrVerificaRegras['OK_REGRA'];
if ($ok_numSorte > 0) {
	$ico_numSorte = "fa-check";
	$cor_numSorte = "text-success";
} else {
	$ico_numSorte = "text-danger";
	$cor_numSorte = "fa-times";
}

$usa_produto = $qrVerificaRegras['USA_PRODUTO'];
$usa_CatProd = $qrVerificaRegras['LOG_CATPROD'];

//fnEscreve($usa_produto);
//fnEscreve($usa_CatProd);

$ok_produto = $qrVerificaRegras['OK_PRODUTO'];
//if ( ($usa_produto == "N") && ($ok_produto > 0)){
//alterado por Lucas 03/06, solicitado por José, foi alterado de usa_produto para usa_CatProd
//verificação se tem produto cadastrado, solicitado por josé 14/11/2024
if ($usa_CatProd == "S" || $ok_produto > 0) {
	$ico_produto = "fa-check";
	$cor_produto = "text-success";
} else {
	$ico_produto = "text-danger";
	$cor_produto = "fa-times";
}

//não bloqueia mais produto
if ($usa_produto == "N") {
	$block_produto = "";
} else {

	if ($usa_CatProd == "S") {
		$block_produto = "";
	} else {
		$block_produto = "disabled";
	}
	//$block_produto = "disabled";	
	//$block_produto = "";
}

$ok_extra = $qrVerificaRegras['OK_EXTRA'];
if ($ok_extra > 0) {
	$ico_extra = "fa-check";
	$cor_extra = "text-success";
} else {
	$ico_extra = "text-danger";
	$cor_extra = "fa-times";
}

$ok_resgate = $qrVerificaRegras['OK_RESGATE'];
if ($ok_resgate > 0) {
	$ico_resgate = "fa-check";
	$cor_resgate = "text-success";
} else {
	$ico_resgate = "text-danger";
	$cor_resgate = "fa-times";
}

$ico_produtoEspec = "fa-check";
$cor_produtoEspec = "text-success";

$ico_semresgate = "fa-check";
$cor_semresgate = "text-success";

//ajustar com javascript
//$abaAtivacaoComp = "";

?>

<ul class="nav nav-tabs">
	<?php if ($tip_campanha != 23) { ?>
		<li class="<?php echo $aba1022; ?> "><a href="action.do?mod=<?php echo fnEncode(1022) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_regra . ' ' . $cor_regra; ?>" aria-hidden="true"></i> Modelo do Programa</a></li>
	<?php } ?>
	<?php if ($tip_campanha == 23) { ?>
		<li class="<?php echo $aba2013; ?> "><a href="action.do?mod=<?php echo fnEncode(2013) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_regraHot . ' ' . $cor_regraHot; ?>" aria-hidden="true"></i> Configuração Hotsite</a></li>
	<?php } ?>
	<?php if ($tip_campanha != 22 && $tip_campanha != 23) { ?>
		<?php if ($tip_campanha == 20) { ?>
			<li class="<?php echo $aba1406; ?> "><a href="action.do?mod=<?php echo fnEncode(1406) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_numSorte . ' ' . $cor_numSorte; ?>" aria-hidden="true"></i> Números do Cupom</a></li>
		<?php } ?>

		<?php if ($tip_campanha != 20) { ?>
			<li class="<?php echo $aba1061 . ' ' . $block_produto; ?>"><a href="action.do?mod=<?php echo fnEncode(1061) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_produto . ' ' . $cor_produto; ?>" aria-hidden="true"></i> Vantagens por Categoria</a></li>
			<li class="<?php echo $aba1057; ?> "><a href="action.do?mod=<?php echo fnEncode(1057) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_extra . ' ' . $cor_extra; ?>" aria-hidden="true"></i> Vantagens Extras</a></li>
			<li class="<?php echo $aba1063 . ' ' . $block_produtoEspec; ?>"><a href="action.do?mod=<?php echo fnEncode(1187) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_produtoEspec . ' ' . $cor_produtoEspec; ?>" aria-hidden="true"></i> Vantagens por Produtos Específicos</a></li>
			<li class="<?php echo $aba1041; ?> "><a href="action.do?mod=<?php echo fnEncode(1041) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_resgate . ' ' . $cor_resgate; ?>" aria-hidden="true"></i> Resgates </a></li>
			<li class="<?php echo $aba1612; ?> "><a href="action.do?mod=<?php echo fnEncode(1612) . "&id=" . $_GET['id'] . "&idc=" . $_GET['idc']; ?>"><i class="fa <?php echo $ico_semresgate . ' ' . $cor_semresgate; ?>" aria-hidden="true"></i> Produtos Sem Resgate </a></li>
		<?php } ?>
	<?php } ?>
	<!--<li class="disable" disabled><a href="#">Hot Site</a></li>-->
</ul>