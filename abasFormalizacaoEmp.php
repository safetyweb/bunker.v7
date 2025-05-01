<?php
//inicialização
$aba1075 = "";
$aba1076 = "";
$aba1077 = "";
$aba1078 = "";
$aba1079 = "";
$aba1080 = "";
$aba1082 = "";
$aba1083 = "";
$aba1084 = "";
$aba1085 = "";
$aba1086 = "";
$aba1087 = "";
$aba1088 = "";
$aba1089 = "";
$aba1090 = "";
$aba1091 = "";
$aba1092 = "";
$aba1347 = "";

switch ($abaFormalizacao) {
    case 1075: //entidade
        $aba1075 = "active";
        break;    
	case 1076: //checklist
        $aba1076 = "active";
        break; 
	case 1077: //documentos
        $aba1077 = "active";
        break; 
	case 1078: //documentos checklist
        $aba1078 = "active";
        break;
	case 1079: //convênio lista
        $aba1079 = "active";
        break;		
	case 1080: //conta bancária
        $aba1080 = "active";
        break;	
	case 1082: //conta bancária
        $aba1082 = "active";
        break;	
	case 1083: //convenio
        $aba1083 = "active";
        break;
	case 1084: //tipo de motivo
        $aba1084 = "active";
        break;	
	case 1085: //termo aditivo
        $aba1085 = "active";
        break;	
	case 1086: //ordem bancária
        $aba1086 = "active";
        break;
	case 1087: //tipo unidade de medida
        $aba1087 = "active";
        break;		
	case 1088: //tipo modalidade
        $aba1088 = "active";
        break;	
	case 1089: //licitação
        $aba1089 = "active";
        break;	
	case 1090: //prestador
        $aba1090 = "active";
        break;	
	case 1091: //propostas
        $aba1091 = "active";
        break;			
	case 1092: //contratos
        $aba1092 = "active";
        break;
	case 1093: //aditivo contratos
        $aba1093 = "active";
        break;		
	case 1096: //home convenio (folder)
        $aba1096 = "active";
        break;		
	case 1347: //home convenio (folder)
	    $aba1347 = "active";
	    break;		
    //default:
        //code to be executed if n is different from all labels;
}

?>

	<ul class="nav nav-tabs">
		<li class="<?php echo $aba1096; ?>"><a href="action.do?mod=<?php echo fnEncode(1096)."&id=".fnEncode($cod_empresa); ?>">Home</a></li>
		<li class="<?php echo $aba1075; ?>"><a href="action.do?mod=<?php echo fnEncode(1075)."&id=".fnEncode($cod_empresa); ?>">Entidade</a></li>
		<li class="<?php echo $aba1347; ?>"><a href="action.do?mod=<?php echo fnEncode(1347)."&id=".fnEncode($cod_empresa); ?>">Tags</a></li>
		<li class="<?php echo $aba1076; ?>"><a href="action.do?mod=<?php echo fnEncode(1076)."&id=".fnEncode($cod_empresa); ?>">CheckList</a></li>
		<li class="<?php echo $aba1077; ?>"><a href="action.do?mod=<?php echo fnEncode(1077)."&id=".fnEncode($cod_empresa); ?>">Documentos</a></li>
		<li class="<?php echo $aba1078; ?>"><a href="action.do?mod=<?php echo fnEncode(1078)."&id=".fnEncode($cod_empresa); ?>">Documentos CheckList</a></li>
		<li class="<?php echo $aba1079; ?>"><a href="action.do?mod=<?php echo fnEncode(1079)."&id=".fnEncode($cod_empresa); ?>">Convênios Lista</a></li>
		<li class="<?php echo $aba1083; ?> disabled"><a>Convênio</a></li>
		<li class="<?php echo $aba1080; ?>"><a href="action.do?mod=<?php echo fnEncode(1080)."&id=".fnEncode($cod_empresa); ?>">Conta Bancária</a></li>
		<li class="<?php echo $aba1082; ?>"><a href="action.do?mod=<?php echo fnEncode(1082)."&id=".fnEncode($cod_empresa); ?>">Upload Base</a></li>
	</ul>
	<div class="push20"></div> 
	<ul class="nav nav-tabs">
		<li class="<?php echo $aba1084; ?>"><a href="action.do?mod=<?php echo fnEncode(1084)."&id=".fnEncode($cod_empresa); ?>">Tipo de Motivo</a></li>
		<li class="<?php echo $aba1085; ?>"><a href="action.do?mod=<?php echo fnEncode(1085)."&id=".fnEncode($cod_empresa); ?>">Aditivo Convênios</a></li>
		<li class="<?php echo $aba1093; ?>"><a href="action.do?mod=<?php echo fnEncode(1093)."&id=".fnEncode($cod_empresa); ?>">Aditivo Contratos</a></li>
		<li class="<?php echo $aba1086; ?>"><a href="action.do?mod=<?php echo fnEncode(1086)."&id=".fnEncode($cod_empresa); ?>">Ordem Bancária</a></li>
		<li class="<?php echo $aba1087; ?>"><a href="action.do?mod=<?php echo fnEncode(1087)."&id=".fnEncode($cod_empresa); ?>">Tipo Unidade de Medida</a></li>
		<li class="<?php echo $aba1088; ?>"><a href="action.do?mod=<?php echo fnEncode(1088)."&id=".fnEncode($cod_empresa); ?>">Tipo Modalidade</a></li>
		<li class="<?php echo $aba1089; ?>"><a href="action.do?mod=<?php echo fnEncode(1089)."&id=".fnEncode($cod_empresa); ?>">Licitação</a></li>
		<li class="<?php echo $aba1090; ?>"><a href="action.do?mod=<?php echo fnEncode(1090)."&id=".fnEncode($cod_empresa); ?>">Prestador</a></li>
	</ul>
	<div class="push20"></div> 
	<ul class="nav nav-tabs">
		<li class="<?php echo $aba1091; ?>"><a href="action.do?mod=<?php echo fnEncode(1091)."&id=".fnEncode($cod_empresa); ?>">Propostas</a></li>
		<li class="<?php echo $aba1092; ?>"><a href="action.do?mod=<?php echo fnEncode(1092)."&id=".fnEncode($cod_empresa); ?>">Contratos</a></li>
	</ul>