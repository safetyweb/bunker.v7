<?php
//inicialização
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
$aba1123 = "";
$aba1164 = "";
$aba1294 = "";
$aba1340 = "";
$aba1398 = "";
$aba1399 = "";
$aba1420 = "";
$aba1488 = "";
$aba1604 = "";
$aba1607 = "";
$aba1621 = "";
$aba1674 = "";
$aba1698 = "";
$aba1165 = "";
$aba1188 = "";

switch ($abaEmpresa) {
    case 1698: //empresa
        $aba1698 = "active";
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
    case 1621: //grafico U
        $aba1621 = "active";
        break;
    case 1674: //termos e notas legais
        $aba1674 = "active";
        break;
}

//verifica se já tem acesso BD
$sql = " select count(COD_DATABASE) as temCOD_DATABASE FROM tab_database where COD_EMPRESA = $cod_empresa  ";
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrListaEmpresas = mysqli_fetch_assoc($arrayQuery);
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


    <?php if (fnControlaAcesso("1698", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1698; ?>"><a href="action.do?mod=<?php echo fnEncode(1698) . "&id=" . fnEncode($cod_empresa); ?>">Empresa</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1340", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1340;
                    echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1340) . "&id=" . fnEncode($cod_empresa); ?>">Personalização</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1101", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1101; ?>"><a href="action.do?mod=<?php echo fnEncode(1101) . "&id=" . fnEncode($cod_empresa); ?>">Campos Obrigatórios</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1018", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1018; ?>"><a href="action.do?mod=<?php echo fnEncode(1018) . "&id=" . fnEncode($cod_empresa); ?>">Perfil</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1456", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1456; ?>"><a href="action.do?mod=<?php echo fnEncode(1456) . "&id=" . fnEncode($cod_empresa); ?>">Frequência do Cliente</a></li>
    <?php } ?>


    <?php if (fnControlaAcesso("1017", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1017; ?>"><a href="action.do?mod=<?php echo fnEncode(1017) . "&id=" . fnEncode($cod_empresa); ?>">Usuários</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1023", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1023; ?>"><a href="action.do?mod=<?php echo fnEncode(1023) . "&id=" . fnEncode($cod_empresa); ?>">Unidades</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1399", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1399; ?>"><a href="action.do?mod=<?php echo fnEncode(1399) . "&id=" . fnEncode($cod_empresa); ?>">Filtros Dinâmicos</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1674", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1674;
                    echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1674) . "&id=" . fnEncode($cod_empresa); ?>">Termos e Notas Legais</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1165", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1165;
                    echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1165) . "&id=" . fnEncode($cod_empresa); ?>">Hot Site</a></li>
    <?php } ?>

    <?php if (fnControlaAcesso("1188", $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
        <li class="<?php echo $aba1188;
                    echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1188) . "&id=" . fnEncode($cod_empresa); ?>">Totem</a></li>
    <?php } ?>

</ul>