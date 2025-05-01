<?php
//inicialização
$aba1020 = "";
$aba1096 = "";
$aba1401 = "";
$aba1400 = "";
$aba1017 = "";
$aba1018 = "";
$aba1021 = "";
$aba1099 = "";
$aba1100 = "";
$aba1101 = "";
$aba1123 = "";
$aba1164 = "";
$aba1294 = "";
$aba1340 = "";
$aba1399 = "";

switch ($abaEmpresa) {
    case 1020: //empresa
        $aba1020 = "active";
        break;
    case 1096: //convênios
        $aba1096 = "active";
        break;
    case 1401: //grupo de trabalho
        $aba1401 = "active";
        break;
    case 1400: //formas de pagamento
        $aba1400 = "active";
        break;
    case 1017: //unidades
        $aba1017 = "active";
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
    case 1399: //categorização
        $aba1399 = "active";
        break;
    case 1524: //data de admissao
        $aba1524 = "active";
        break;
    case 1590: //usuários região
        $aba1590 = "active";
        break;
    case 1075: //entidade
        $aba1075 = "active";
        break;
    case 1730: //grupo de entidade
        $aba1730 = "active";
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

?>

									<ul class="nav nav-tabs">
									
									
										<?php if(fnControlaAcesso("1020",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
                                        <li class="<?php echo $aba1020; ?>"><a href="action.do?mod=<?php echo fnEncode(1020)."&id=".fnEncode($cod_empresa); ?>">Empresa</a></li>
										<?php } ?>

									    <?php if(fnControlaAcesso("1017",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1017; ?>"><a href="action.do?mod=<?php echo fnEncode(1017)."&id=".fnEncode($cod_empresa); ?>">Usuários</a></li>
										<?php } ?>	

										
										
										<?php if(fnControlaAcesso("1096",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1096;?>"><a href="action.do?mod=<?php echo fnEncode(1096)."&id=".fnEncode($cod_empresa); ?>">Convênios</a></li>
										<?php } ?>
										
										
									    <?php if(fnControlaAcesso("1073",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1073; ?>"><a href="action.do?mod=<?php echo fnEncode(1073)."&id=".fnEncode($cod_empresa); ?>">Tipo de Entidades</a></li>
										<?php } ?>	

									    <?php if(fnControlaAcesso("1075",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1075; ?>"><a href="action.do?mod=<?php echo fnEncode(1075)."&id=".fnEncode($cod_empresa); ?>">Entidades</a></li>
										<?php } ?>	
										
									    <?php if(fnControlaAcesso("1730",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
									    <li class="<?php echo $aba1730; ?>"><a href="action.do?mod=<?php echo fnEncode(1730)."&id=".fnEncode($cod_empresa); ?>">Grupo Entidades</a></li>
										<?php } ?>	
										
									    <?php 
										if ($cod_empresa == 136){
											if(fnControlaAcesso("1590",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
											<li class="<?php echo $aba1590; ?>"><a href="action.do?mod=<?php echo fnEncode(1590)."&id=".fnEncode($cod_empresa); ?>">Usuários Região</a></li>
										<?php 
											}
										} ?>	
										
										<!-- <?php if(fnControlaAcesso("1018",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1018; ?>"><a href="action.do?mod=<?php echo fnEncode(1018)."&id=".fnEncode($cod_empresa); ?>">Perfil</a></li>
										<?php } ?> -->

									    <?php if(fnControlaAcesso("1399",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1399; ?>"><a href="action.do?mod=<?php echo fnEncode(1399)."&id=".fnEncode($cod_empresa); ?>">Filtros Dinâmicos</a></li>
										<?php } ?>										
										
									    <?php if(fnControlaAcesso("1401",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1401; ?>"><a href="action.do?mod=<?php echo fnEncode(1401)."&id=".fnEncode($cod_empresa); ?>">Tipos de Evento</a></li>
										<?php } ?>										
										
									    <?php 
										if ($cod_empresa == 136){
											if(fnControlaAcesso("1524",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
											<li class="<?php echo $aba1524; ?>"><a href="action.do?mod=<?php echo fnEncode(1524)."&id=".fnEncode($cod_empresa); ?>">Admissão/Objetivos</a></li>
										<?php 
											} 
										} ?>										

										<!--
										<?php if(fnControlaAcesso("1400",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<li class="<?php echo $aba1400; ?>"><a href="action.do?mod=<?php echo fnEncode(1400)."&id=".fnEncode($cod_empresa); ?>">Agenda</a></li>
										<?php } ?>
										-->

									</ul>