<?php
//inicialização
$aba1024 = "";
$aba1053 = "";
$aba1054 = "";
$aba1072 = "";
$aba1067 = "";
$aba1081 = "";
$aba1112 = "";
$aba1253 = "";
$aba1665 = "";
$aba1757 = "";
$aba1818 = "";
$aba2080 = "";
$aba1173 = "";
$aba1518 = "";

// fnEscreve($abaCli);

switch ($abaCli) {
	case 1024: //cliente
		$aba1024 = "active";
		break;
	case 1053: //sac
		$aba1053 = "active";
		break;
	case 1054: //follow up - modelo original
		$aba1054 = "active";
		break;
	case 1072: //minhas compras
		$aba1072 = "active";
		break;
	case 1067: //venda avulsa
		$aba1067 = "active";
		break;
	case 1081: //wallet
		$aba1081 = "active";
		break;
	case 1112: //troca de cartão
		$aba1112 = "active";
		break;
	case 1173: //resgate avulso
		$aba1173 = "active";
		break;
	case 1253: //follow comunicação
		$aba1253 = "active";
		break;
	case 1423: //cliente simples
		$aba1423 = "active";
		break;
	case 1475: //agenda
		$aba1475 = "active";
		break;
	case 1476: //atendimento
		$aba1476 = "active";
		break;
	case 1518: //estorno
		$aba1518 = "active";
		break;
	case 1665: //estorno
		$aba1665 = "active";
		break;
	case 1757: //emendas
		$aba1757 = "active";
		break;
	case 1818: //anexo de documentos
		$aba1818 = "active";
		break;
	case 2080: //anexo de documentos
		$aba2080 = "active";
		break;
		//default:
		//code to be executed if n is different from all labels;
}

if ($cod_cliente == 0) {
	$abaClieOff = "disabled";
} else {
	$abaClieOff = "";
}

include "labelLibrary.php";

?>


<ul class="nav nav-tabs">

	<?php if ($_SESSION["SYS_COD_SISTEMA"] == 16) { ?>

		<?php if (fnControlaAcesso("1423", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
			<li class="<?php echo $aba1423; ?>"><a href="action.do?mod=<?php echo fnEncode(1423) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>"><?= $abaNome ?></a></li>
		<?php } ?>

	<?php } else { ?>

		<?php if (fnControlaAcesso("1024", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
			<li class="<?php echo $aba1024; ?>"><a href="action.do?mod=<?php echo fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Cliente</a></li>
		<?php } ?>

	<?php } ?>

	<?php if (fnControlaAcesso("1072", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1072 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1072) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Minhas Compras</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1081", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1081 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1081) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Wallet</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("2080", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba2080 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(2080) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Prêmios e Brindes</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1253", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1253; ?>"><a href="action.do?mod=<?php echo fnEncode(1253) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Follow Up</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1173", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1173 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1173) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Resgate Manual</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1067", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1067 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1067) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Lançamento Manual</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1112", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1112 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1112) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Troca de Cartão</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1518", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1518 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1518) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Estorno de Compras</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1665", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1665 . ' ' . $abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1665) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Exclusão do Cliente</a></li>
	<?php } ?>

	<?php if ($_SESSION["SYS_COD_SISTEMA"] == 16) { ?>

		<?php if (fnControlaAcesso("1475", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
			<li class="<?php echo $aba1475; ?>"><a href="action.do?mod=<?php echo fnEncode(1475) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Agenda do <?= $abaNome ?></a></li>
		<?php } ?>

		<?php if (fnControlaAcesso("1476", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
			<li class="<?php echo $aba1476; ?>"><a href="action.do?mod=<?php echo fnEncode(1476) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Atendimento do <?= $abaNome ?></a></li>
		<?php } ?>

		<?php
		if ($cod_empresa == 136) {
			if (fnControlaAcesso("1757", $_SESSION["SYS_MODUL_AUTOR"]) === true) {
		?>
				<li class="<?php echo $aba1757; ?>"><a href="action.do?mod=<?php echo fnEncode(1757) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Emendas da <?= $abaNome ?></a></li>
		<?php
			}
		}
		?>

		<?php if (fnControlaAcesso("1818", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
			<li class="<?php echo $aba1818; ?>"><a href="action.do?mod=<?php echo fnEncode(1818) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Anexo de Documentos</a></li>
		<?php } ?>

	<?php } ?>

</ul>