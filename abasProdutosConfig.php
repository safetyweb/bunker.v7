<?php
//inicialização
$aba1064 = "";
$aba1045 = "";
$aba1043 = "";
$aba1044 = "";
$aba1046 = "";
$aba1181 = "";
$aba1465 = "";

switch ($abaEmpresa) {
    case 1043: //grupo de produtos
        $aba1043 = "active";
        break;    
	case 1045: //sub grupo de produtos
        $aba1045 = "active";
        break;		
	case 1044: //sub grupo de produtos
        $aba1044 = "active";
        break;
	case 1046: //sub grupo de produtos
        $aba1046 = "active";
        break;	
	case 1465: //agrupador de produtos (objeto)
        $aba1465 = "active";
        break;	
	case 1064: //fornecedores
        $aba1064 = "active";
        break;
	case 1181: //produtos gestao
        $abaGst1181 = "active";
        break;	
    //default:
        //code to be executed if n is different from all labels;
}
?>

									<ul class="nav nav-tabs">
										<li class="<?php echo $aba1064; ?>"><a href="action.do?mod=<?php echo fnEncode(1064)."&id=".fnEncode($cod_empresa); ?>">Fornecedores</a></li>
										<li class="<?php echo $aba1045; ?>"><a href="action.do?mod=<?php echo fnEncode(1045)."&id=".fnEncode($cod_empresa); ?>">Informação Adicional</a></li>								
										<li class="<?php echo $aba1043; ?>"><a href="action.do?mod=<?php echo fnEncode(1043)."&id=".fnEncode($cod_empresa); ?>">Grupo Produtos</a></li>
										<li class="<?php echo $aba1044; ?>"><a href="action.do?mod=<?php echo fnEncode(1044)."&id=".fnEncode($cod_empresa); ?>">Sub Grupo Produtos</a></li>
										<li class="<?php echo $aba1465; ?>"><a href="action.do?mod=<?php echo fnEncode(1465)."&id=".fnEncode($cod_empresa); ?>">Agrupador Produtos</a></li>
										<li class="<?php echo $aba1046; ?>"><a href="action.do?mod=<?php echo fnEncode(1046)."&id=".fnEncode($cod_empresa); ?>">Produtos</a></li>										
										<li class="<?php echo $aba1181; ?>"><a href="action.do?mod=<?php echo fnEncode(1181)."&id=".fnEncode($cod_empresa); ?>">Gestão de Produtos</a></li>										
									</ul>
