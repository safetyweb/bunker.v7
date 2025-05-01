<?php

include "moduloControlaAcesso.php";

//inicialização
$aba1106 = "";
$aba1107 = "";
$aba1110 = "";
$aba1111 = "";
$aba1126 = "";
$aba1128 = "";
$aba1129 = "";
$aba1131 = "";
$aba1484 = "";
$aba1806 = "";
$aba1890 = "";
$aba1918 = "";
$aba1180 = "";
$aba1168 = "";

switch ($abaModulo) {
	case 1106: //blocos do template
		$aba1106 = "active";
		break;
	case 1107: //grupo de produtos
		$aba1107 = "active";
		break;
	case 1110: //blacklist
		$aba1110 = "active";
		break;
	case 1111: //lista templates
		$aba1111 = "active";
		break;
	case 1126: //produtos
		$aba1126 = "active";
		break;
	case 1128: //configuração
		$aba1128 = "active";
		$aba1168 = "active";
		break;
	case 1168: //configuração
		$aba1168 = "active";
		break;
	case 1129: //configuração
		$aba1129 = "active";
		break;
	case 1131: //grupo de desconto
		$aba1131 = "active";
		break;
	case 1180: //descontos
		$aba1180 = "active";
		break;
	case 1484: //regras indicação
		$aba1484 = "active";
		break;
	case 1806: //regras indicação
		$aba1806 = "active";
		break;
	case 1890: //regras indicação
		$aba1890 = "active";
		break;
	case 1918: //descontos v2
		$aba1918 = "active";
		break;

		//default:
		//code to be executed if n is different from all labels;
}

//echo "<h3> ".$abaModulo." </h3>";

?>

<ul class="nav nav-tabs">

	<?php if (fnControlaAcesso("1111", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1111; ?>"><a href="action.do?mod=<?php echo fnEncode(1111) . "&id=" . fnEncode($cod_empresa); ?>">Template</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1107", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1107; ?>"><a href="action.do?mod=<?php echo fnEncode(1107) . "&id=" . fnEncode($cod_empresa); ?>">Categoria do Ticket</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1131", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1131; ?>"><a href="action.do?mod=<?php echo fnEncode(1131) . "&id=" . fnEncode($cod_empresa); ?>">Grupo de Desconto</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1110", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1110; ?>"><a href="action.do?mod=<?php echo fnEncode(1110) . "&id=" . fnEncode($cod_empresa); ?>">Blacklist</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1168", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1168; ?>"><a href="action.do?mod=<?php echo fnEncode(1168) . "&id=" . fnEncode($cod_empresa); ?>">Produtos do Ticket</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1126", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1126; ?>"><a href="action.do?mod=<?php echo fnEncode(1126) . "&id=" . fnEncode($cod_empresa); ?>">Configuração</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1129", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1129; ?>"><a href="action.do?mod=<?php echo fnEncode(1129) . "&id=" . fnEncode($cod_empresa); ?>">Simulador</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1890", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1890; ?>"><a href="action.do?mod=<?php echo fnEncode(1890) . "&id=" . fnEncode($cod_empresa); ?>">Documentos</a></li>
	<?php } ?>

</ul>

<div class="push10"></div>

<ul class="nav nav-tabs">

	<?php if (fnControlaAcesso("1806", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1806; ?>"><a href="action.do?mod=<?php echo fnEncode(1806) . "&id=" . fnEncode($cod_empresa); ?>">Personas do Ticket</a></li>
	<?php } ?>

	<li class="disabled" disabled><a href="#">Regras da Lista de Oferta</a></li>

	<?php if (fnControlaAcesso("1484", $arrayParamAutorizacao) === true) { ?>
		<li class="<?php echo $aba1484; ?>"><a href="action.do?mod=<?php echo fnEncode(1484) . "&id=" . fnEncode($cod_empresa); ?>">Regras de Indicação</a></li>
	<?php } ?>

	<li class="<?php echo $aba1918; ?>"><a href="action.do?mod=<?php echo fnEncode(1918) . "&id=" . fnEncode($cod_empresa); ?>">Descontos</a></li>

	<?php if (fnControlaAcesso("1918", $arrayParamAutorizacao) === true) { ?>
	<?php  } ?>

	<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2) { ?>
	<?php } ?>
</ul>