<?php
//inicialização

$aba1020 = "";
$aba1701 = "";
$aba1340 = "";
$aba1017 = "";
$aba1018 = "";
$aba1023 = "";
$aba1399 = "";
$aba1704 = "";
$aba1963 = "";
$aba1961 = "";

switch ($abaEmpresa) {
    case 1020: //empresa
    case 1701: //empresa
        $aba1701 = "active";
        break;
    case 1017: //usuários
        $aba1017 = "active";
        break;
    case 1340: //personalização
        $aba1017 = "active";
        break;
    case 1018: //perfil
        $aba1018 = "active";
        break;
    case 1960: //unidades
        $aba1960 = "active";
        break;
    case 1399: //filtros dinâmicos
        $aba1399 = "active";
        break;
    case 1714: //profissoes
        $aba1714 = "active";
        break;
    case 1963: //Tipo de Unidade
        $aba1963 = "active";
        break;
    case 1961: //Benfeitorias
        $aba1961 = "active";
        break;
	}

?>

	<ul class="nav nav-tabs">


		<?php if(fnControlaAcesso("1701",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1701; ?>"><a href="action.do?mod=<?php echo fnEncode(1701)."&id=".fnEncode($cod_empresa); ?>">Empresa</a></li>
		<?php } ?>
		
		<?php if(fnControlaAcesso("1340",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1340; echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1340)."&id=".fnEncode($cod_empresa); ?>">Personalização</a></li>
		<?php } ?>

		<?php if(fnControlaAcesso("1017",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1017; ?>"><a href="action.do?mod=<?php echo fnEncode(1017)."&id=".fnEncode($cod_empresa); ?>">Usuários</a></li>
		<?php } ?>
		
		<?php if(fnControlaAcesso("1960",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1960; ?>"><a href="action.do?mod=<?php echo fnEncode(1960)."&id=".fnEncode($cod_empresa); ?>">Unidades de Atendimento</a></li>
		<?php } ?>

		<?php if(fnControlaAcesso("1018",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1018; ?>"><a href="action.do?mod=<?php echo fnEncode(1018)."&id=".fnEncode($cod_empresa); ?>">Perfil</a></li>
		<?php } ?>

		<?php if(fnControlaAcesso("1399",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1399; ?>"><a href="action.do?mod=<?php echo fnEncode(1399)."&id=".fnEncode($cod_empresa); ?>">Filtros Dinâmicos</a></li>
		<?php } ?>

		<?php // if(fnControlaAcesso("1963",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1963; ?>"><a href="action.do?mod=<?php echo fnEncode(1963)."&id=".fnEncode($cod_empresa); ?>">Tipo de Unidade</a></li>
		<?php // } ?>

		<?php // if(fnControlaAcesso("1961",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
		<li class="<?php echo $aba1961; ?>"><a href="action.do?mod=<?php echo fnEncode(1961)."&id=".fnEncode($cod_empresa); ?>">Benfeitorias</a></li>
		<?php // } ?>

	</ul>
