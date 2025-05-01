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
$aba1340 = "";

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
	case 1046: //produtos
        $aba1046 = "active";
        break;	
	case 1164: //entidades
        $aba1164 = "active";
        break;	
	case 1183: //gestão
        $aba1183 = "active";
		break;		
	case 1043: //grupo produtos
        $aba1043 = "active";
        break;
    case 1340: //categorização
        $aba1340 = "active";
        break;
}

?>

									<ul class="nav nav-tabs">
									    <li class="<?php echo $aba1020; ?>"><a href="action.do?mod=<?php echo fnEncode(1020)."&id=".fnEncode($cod_empresa); ?>">Empresa</a></li>
                                        <li class="<?php echo $aba1340; ?>"><a href="action.do?mod=<?php echo fnEncode(1340)."&id=".fnEncode($cod_empresa); ?>">Personalização</a></li>
									    <li class="<?php echo $aba1164; ?>"><a href="action.do?mod=<?php echo fnEncode(1164)."&id=".fnEncode($cod_empresa); ?>">Clientes</a></li>
									    <li class="<?php echo $aba1017; ?>"><a href="action.do?mod=<?php echo fnEncode(1017)."&id=".fnEncode($cod_empresa); ?>">Usuários</a></li>
										<li class="<?php echo $aba1023; ?>"><a href="action.do?mod=<?php echo fnEncode(1023)."&id=".fnEncode($cod_empresa); ?>">Unidades</a></li>
										<li class="<?php echo $aba1043; ?>"><a href="action.do?mod=<?php echo fnEncode(1043)."&id=".fnEncode($cod_empresa); ?>">Grupo Produtos</a></li>
										<li class="<?php echo $aba1046; ?>"><a href="action.do?mod=<?php echo fnEncode(1046)."&id=".fnEncode($cod_empresa); ?>">Produtos</a></li>
										<li class="<?php echo $aba1183; ?>"><a href="action.do?mod=<?php echo fnEncode(1184)."&id=".fnEncode($cod_empresa); ?>">Limites por Cliente</a></li>
										<!--
									    <li class="<?php echo $aba1101; ?>"><a href="action.do?mod=<?php echo fnEncode(1101)."&id=".fnEncode($cod_empresa); ?>">Campos Obrigatórios</a></li>
										<li class="<?php echo $aba1025; ?>"><a href="action.do?mod=<?php echo fnEncode(1025)."&id=".fnEncode($cod_empresa); ?>">Grupo Trabalho</a></li>
										<li class="<?php echo $aba1068; ?>"><a href="action.do?mod=<?php echo fnEncode(1068)."&id=".fnEncode($cod_empresa); ?>">Formas de Pagamento</a></li>
										<li class="<?php echo $aba1104; ?>"><a href="action.do?mod=<?php echo fnEncode(1104)."&id=".fnEncode($cod_empresa); ?>">Máquinas</a></li> 
										<li class="<?php echo $aba1018; ?>"><a href="action.do?mod=<?php echo fnEncode(1018)."&id=".fnEncode($cod_empresa); ?>">Perfil</a></li>
										-->
									</ul>                                                   
									<!--
									<div class="push20"></div>                              
									<ul class="nav nav-tabs">                               
										<li class="<?php echo $aba1021; echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1021)."&id=".fnEncode($cod_empresa); ?>">Set Up</a></li>
										<li class="<?php echo $aba1099; echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1099)."&id=".fnEncode($cod_empresa); ?>">Desbloqueio</a></li>
										<li class="<?php echo $aba1123; echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1123)."&id=".fnEncode($cod_empresa); ?>">Estorno</a></li>
										<li class="<?php echo $aba1100; echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1100)."&id=".fnEncode($cod_empresa); ?>">Cartões Pré Cadastrados</a></li>
										<li class="<?php echo $aba1105; echo $abaLibBD; ?>"><a href="action.do?mod=<?php echo fnEncode(1105)."&id=".fnEncode($cod_empresa); ?>">Chave de Acesso</a></li>
									</ul>
									-->
