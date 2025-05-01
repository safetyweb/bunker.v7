<?php

$aba1020 = "";
$aba1017 = "";
$aba1025 = "";
$aba1068 = "";
$aba1023 = "";
$aba1018 = "";
$aba1021 = "";
$aba1099 = "";
$aba1100 = "";
$aba1101 = "";
$aba1104 = "";
$aba1105 = "";
$aba1123 = "";
$aba1165 = "";
$aba1167 = "";
$aba1178 = "";
$aba1179 = "";
$aba1185 = "";
$aba1188 = "";
$aba1193 = "";
$aba1230 = "";
$aba1258 = "";
$aba1261 = "";
$aba1262 = "";
$aba1264 = "";
$aba1340 = "";
$aba1398 = "";
$aba1399 = "";
$aba1420 = "";
$aba1456 = "";
$aba1488 = "";
$aba1503 = "";
$aba1530 = "";
$aba1584 = "";
$aba1600 = "";
$aba1604 = "";
$aba1607 = "";
$aba1781 = "";
$aba1621 = "";
$aba1674 = "";
$aba1736 = "";
$aba1813 = "";
$aba1950 = "";
//inicialização


switch ($abaEmpresa) {
	case 1020: //empresa
		$aba1020 = "active";
		break;
	case 1017: //usuários
		$aba1017 = "active";
		break;
	case 1025: //grupo de trabalho
		$aba1025 = "active";
		break;
	case 1068: //formas de pagamento
		$aba1068 = "active";
		break;
	case 1023: //unidades
		$aba1023 = "active";
		break;
	case 1018: //perfil
		$aba1018 = "active";
		break;
	case 1021: //automação
		$aba1021 = "active";
		break;
	case 1099: //automação
		$aba1099 = "active";
		break;
	case 1100: //automação
		$aba1100 = "active";
		break;
	case 1101: //campos obrigatórios
		$aba1101 = "active";
		break;
	case 1104: //maquinas
		$aba1104 = "active";
		break;
	case 1105: //chave de acesso
		$aba1105 = "active";
		break;
	case 1123: //estorno de vendas
		$aba1123 = "active";
		break;
	case 1165: //hot site
		$aba1165 = "active";
		break;
	case 1167: //faq
		$aba1167 = "active";
		break;
	case 1178: //tipo de cliente
		$aba1178 = "active";
		break;
	case 1179: //região grupo
		$aba1179 = "active";
		break;
	case 1185: //vendedores
		$aba1185 = "active";
		break;
	case 1188: //totem
		$aba1188 = "active";
		break;
	case 1193: //saldo
		$aba1193 = "active";
		break;
	case 1230: //redes sociais
		$aba1230 = "active";
		break;
	case 1258: //app
		$aba1258 = "active";
		break;
	case 1261: //profissões
		$aba1261 = "active";
		break;
	case 1262: //banner totem
		$aba1262 = "active";
		break;
	case 1264: //categorização
		$aba1264 = "active";
		break;
	case 1340: //categorização
		$aba1340 = "active";
		break;
	case 1398: //categorização
		$aba1398 = "active";
		break;
	case 1399: //filtros
		$aba1399 = "active";
		break;
	case 1420: //webhook
		$aba1420 = "active";
		break;
	case 1456: //frequencia
		$aba1456 = "active";
		break;
	case 1488: //documentos
		$aba1488 = "active";
		break;
	case 1503: //compras comunicação
		$aba1503 = "active";
		break;
	case 1530: //loja de preferencia
		$aba1530 = "active";
		break;
	case 1584: //comunicação - analytics
		$aba1584 = "active";
		break;
	case 1600: //campanha config
		$aba1600 = "active";
		break;
	case 1604: //persona config
		$aba1604 = "active";
		break;
	case 1607: //totem app
		$aba1607 = "active";
		break;
	case 1781: //parceiros app
		$aba1781 = "active";
		break;
	case 1621: //grafico U
		$aba1621 = "active";
		break;
	case 1674: //termos e notas legais
		$aba1674 = "active";
		break;
	case 1736: //canasi de atendimento
		$aba1736 = "active";
		break;
	case 1813: //transfere unidade
		$aba1813 = "active";
		break;
	case 1950:
		$aba1950 = "active";
		break;
}

//verifica se já tem acesso BD
$sql = " select count(COD_DATABASE) as temCOD_DATABASE FROM tab_database where COD_EMPRESA = $cod_empresa  ";

$qrListaEmpresas = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
//$qrListaEmpresas = mysqli_fetch_assoc($arrayQuery);
$temCOD_DATABASE = $qrListaEmpresas['temCOD_DATABASE'];
if ($temCOD_DATABASE == 0) {
	$abaLibBD = "disabled";
} else {
	$abaLibBD = "";
}
//fnEscreve($temCOD_DATABASE);

//echo "<pre>";
//print_r($_SESSION["SYS_MODUL_AUTOR"]);
//echo "</pre>";

?>

<ul class="nav nav-tabs">


	<?php if (fnControlaAcesso("1020", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1020; ?>"><a href="action.do?mod=<?php echo fnEncode(1020) . "&id=" . fnEncode($cod_empresa); ?>">Empresa</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1488", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1488;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1488) . "&id=" . fnEncode($cod_empresa); ?>">Documentos</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1340", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1340;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1340) . "&id=" . fnEncode($cod_empresa); ?>">Personalização</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1021", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1021;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1021) . "&id=" . fnEncode($cod_empresa); ?>">Set Up</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1101", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1101; ?>"><a href="action.do?mod=<?php echo fnEncode(1101) . "&id=" . fnEncode($cod_empresa); ?>">Campos Obrigatórios</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1018", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1018; ?>"><a href="action.do?mod=<?php echo fnEncode(1018) . "&id=" . fnEncode($cod_empresa); ?>">Perfil</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1264", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1264; ?>"><a href="action.do?mod=<?php echo fnEncode(1264) . "&id=" . fnEncode($cod_empresa); ?>">Categorização</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1456", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1456; ?>"><a href="action.do?mod=<?php echo fnEncode(1456) . "&id=" . fnEncode($cod_empresa); ?>">Funil de Clientes por Gasto</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1621", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1621; ?>"><a href="action.do?mod=<?php echo fnEncode(1621) . "&id=" . fnEncode($cod_empresa); ?>">Funil de Clientes por Frequência</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1530", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1530; ?>"><a href="action.do?mod=<?php echo fnEncode(1530) . "&id=" . fnEncode($cod_empresa); ?>">Loja de Preferência</a></li>
	<?php } ?>


</ul>

<div class="push20"></div>

<ul class="nav nav-tabs">
	<?php if (fnControlaAcesso("1017", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1017; ?>"><a href="action.do?mod=<?php echo fnEncode(1017) . "&id=" . fnEncode($cod_empresa); ?>">Usuários</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1023", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1023; ?>"><a href="action.do?mod=<?php echo fnEncode(1023) . "&id=" . fnEncode($cod_empresa); ?>">Unidades</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1399", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1399; ?>"><a href="action.do?mod=<?php echo fnEncode(1399) . "&id=" . fnEncode($cod_empresa); ?>">Filtros Dinâmicos</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1261", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1261; ?>"><a href="action.do?mod=<?php echo fnEncode(1261) . "&id=" . fnEncode($cod_empresa); ?>">Profissões</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1736", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1736; ?>"><a href="action.do?mod=<?php echo fnEncode(1736) . "&id=" . fnEncode($cod_empresa); ?>">Canais</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1025", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1025; ?>"><a href="action.do?mod=<?php echo fnEncode(1025) . "&id=" . fnEncode($cod_empresa); ?>">Grupo Trabalho</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1179", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1179;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1179) . "&id=" . fnEncode($cod_empresa); ?>">Grupo Região</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1178", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1178;  ?>"><a href="action.do?mod=<?php echo fnEncode(1178) . "&id=" . fnEncode($cod_empresa); ?>">Tipo de Cliente</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1068", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1068;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1068) . "&id=" . fnEncode($cod_empresa); ?>">Formas de Pagamento</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1104", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1104;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1104) . "&id=" . fnEncode($cod_empresa); ?>">Máquinas</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1105", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1105;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1105) . "&id=" . fnEncode($cod_empresa); ?>">Chave de Acesso</a></li>
	<?php } ?>

</ul>

<div class="push20"></div>

<ul class="nav nav-tabs">

	<?php if (fnControlaAcesso("1584", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1584;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1584) . "&id=" . fnEncode($cod_empresa); ?>">Comunicação</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1674", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1674;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1674) . "&id=" . fnEncode($cod_empresa); ?>">Termos e Notas Legais</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1230", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1230;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1230) . "&id=" . fnEncode($cod_empresa); ?>">Redes Sociais</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1165", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1165;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1165) . "&id=" . fnEncode($cod_empresa); ?>">Hot Site</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1167", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1167;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1167) . "&id=" . fnEncode($cod_empresa); ?>">FAQ</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1188", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1188;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1188) . "&id=" . fnEncode($cod_empresa); ?>">Totem</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1262", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1262;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1262) . "&id=" . fnEncode($cod_empresa); ?>">Banner Totem</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1193", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1193;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1193) . "&id=" . fnEncode($cod_empresa); ?>">Saldo</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1258", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1258;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1258) . "&id=" . fnEncode($cod_empresa); ?>">App</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1607", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1607;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1607) . "&id=" . fnEncode($cod_empresa); ?>">Banner App</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1781", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1781;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1781) . "&id=" . fnEncode($cod_empresa); ?>">Parceiros App</a></li>
	<?php } ?>

	<!--<li class="<?php echo $aba1123;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1123) . "&id=" . fnEncode($cod_empresa); ?>">Estorno</a></li>-->

	<?php if (fnControlaAcesso("1420", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1420;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1420) . "&id=" . fnEncode($cod_empresa); ?>">Webhook</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1100", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1100;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1100) . "&id=" . fnEncode($cod_empresa); ?>">Cartões Pré Cadastrados</a></li>
	<?php } ?>

</ul>

<div class="push20"></div>

<ul class="nav nav-tabs">

	<!--
										<li class="<?php echo $aba1503; ?>"><i class="far fa-unlock-alt"></i></li>
										-->

	<?php if (fnControlaAcesso("1503", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1503; ?>"><a href="action.do?mod=<?php echo fnEncode(1503) . "&id=" . fnEncode($cod_empresa); ?>">Compras Comunicação</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1950", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1950; ?>"><a href="action.do?mod=<?php echo fnEncode(1950) . "&id=" . fnEncode($cod_empresa); ?>">WhatsApp</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1099", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1099;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1099) . "&id=" . fnEncode($cod_empresa); ?>">Desbloqueio</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1604", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1604;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1604) . "&id=" . fnEncode($cod_empresa); ?>">Controle de Personas</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1600", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1600;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1600) . "&id=" . fnEncode($cod_empresa); ?>">Controle de Campanhas</a></li>
	<?php } ?>

	<?php if (fnControlaAcesso("1813", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1813;
					echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1813) . "&id=" . fnEncode($cod_empresa); ?>">Transferência de Unidade</a></li>
	<?php } ?>

</ul>