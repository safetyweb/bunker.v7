<?php
//inicialização

$aba1701 = "";
$aba1017 = "";
$aba1018 = "";
$aba1023 = "";
$aba1399 = "";
$aba1704 = "";

switch ($abaEmpresa) {
    case 1701: //empresa
        $aba1701 = "active";
        break;
    case 1017: //usuários
        $aba1017 = "active";
        break;
    case 1018: //perfil
        $aba1018 = "active";
        break;
    case 1817: //unidades
        $aba1817 = "active";
        break;
    case 1399: //filtro dinâmico
        $aba1399 = "active";
        break;
	case 1706: //lançamento mensal
        $aba1706 = "active";
        break;
    case 1704: //tipos de lançamento
        $aba1704 = "active";
        break;
    case 1714: //profissoes
        $aba1714 = "active";
        break;
    case 1797: //dobrada
        $aba1797 = "active";
        break;
	}

//verifica se já tem acesso BD
$sql = " select count(COD_DATABASE) as temCOD_DATABASE FROM tab_database where COD_EMPRESA = $cod_empresa  ";
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrListaEmpresas = mysqli_fetch_assoc($arrayQuery);
$temCOD_DATABASE = $qrListaEmpresas['temCOD_DATABASE'];
if ($temCOD_DATABASE == 0){ $abaLibBD = "disabled";} 
else { $abaLibBD = "";} 
//fnEscreve($temCOD_DATABASE);

//echo "<pre>";
//print_r($_SESSION["SYS_MODUL_AUTOR"]);
//echo "</pre>";

//fnEscreve2($_SESSION["SYS_COD_SISTEMA"]);

?>

									<ul class="nav nav-tabs">
									
									
										<?php if(fnControlaAcesso("1701",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
                                        <li class="<?php echo $aba1701; ?>"><a href="action.do?mod=<?php echo fnEncode(1701)."&id=".fnEncode($cod_empresa); ?>">Empresa</a></li>
										<?php } ?>

										<?php if(fnControlaAcesso("1817",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1817; ?>"><a href="action.do?mod=<?php echo fnEncode(1817)."&id=".fnEncode($cod_empresa); ?>">Campanha</a></li>
										<?php } ?>

										<?php if(fnControlaAcesso("1017",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1017; ?>"><a href="action.do?mod=<?php echo fnEncode(1017)."&id=".fnEncode($cod_empresa); ?>">Usuários</a></li>
										<?php } ?>

										<?php if(fnControlaAcesso("1797",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1797; ?>"><a href="action.do?mod=<?php echo fnEncode(1797)."&id=".fnEncode($cod_empresa); ?>">Dobrada Campanha</a></li>
										<?php } ?>

										<?php if(fnControlaAcesso("1399",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1399; ?>"><a href="action.do?mod=<?php echo fnEncode(1399)."&id=".fnEncode($cod_empresa); ?>">Filtros Dinâmicos</a></li>
										<?php } ?>
										
										<?php if(fnControlaAcesso("1704",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1704; ?>"><a href="action.do?mod=<?php echo fnEncode(1704)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Lançamento Caixa</a></li>
										<?php } ?>	

									</ul>
