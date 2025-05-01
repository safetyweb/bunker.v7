<?php
//inicialização
$aba1024 = "";
$aba1053 = "";
$aba1054 = "";
$aba1072 = "";
$aba1067 = "";
$aba1081 = "";
$aba1112 = "";


switch ($abaCli) {
    case 1024: //cliente
        $aba1024 = "active";
        break;    
	case 1053: //sac
        $aba1053 = "active";
        break;	
	case 1054: //follow up
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
	case 1112: //wallet
        $aba1112 = "active";
        break;	
	case 1173: //wallet
        $aba1173 = "active";
        break;
    //default:
    //code to be executed if n is different from all labels;
}

if ($cod_cliente == 0){
  $abaClieOff = "disabled";	
} else {
  $abaClieOff = "";	
}

?>


									<ul class="nav nav-tabs">
									    <li class="<?php echo $aba1024; ?>"><a href="action.do?mod=<?php echo fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Cliente</a></li>
									    <li class="<?php echo $aba1072.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1072)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Minhas Compras</a></li>
										<!--
									    <li class="<?php echo $aba1081.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1081)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Wallet</a></li>
									    <li class="<?php echo $aba1173.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1173)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Resgate Avulso</a></li>
									    <li class="<?php echo $aba1067.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1067)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Lançamento Avulso</a></li>
									    <li class="<?php echo $aba1112.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1112)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Troca de Cartão</a></li>
										-->
									</ul>
