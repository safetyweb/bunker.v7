<?php
//inicialização
$aba1248 = "";
$aba1249 = "";
$aba1225 = "";
$aba1227 = "";
$aba1372 = "";

switch ($abaMarkaPontos) {
	case 1248: //categoria
        $aba1248 = "active";
        break;
	case 1249: //sub categoria
        $aba1249 = "active";
        break;
	case 1225: //produtos
        $aba1225 = "active";
        break;
	case 1227: //banner
        $aba1227 = "active";
        break;
    case 1372: //estoque
        $aba1372 = "active";
        break;	
    //default:
    //code to be executed if n is different from all labels;
}
?>

									<ul class="nav nav-tabs">
										<li class="<?php echo $aba1248; ?>"><a href="action.do?mod=<?php echo fnEncode(1248)."&id=".fnEncode($cod_empresa); ?>">Grupo Promoção</a></li>
										<li class="<?php echo $aba1249; ?>"><a href="action.do?mod=<?php echo fnEncode(1249)."&id=".fnEncode($cod_empresa); ?>">Sub Grupo Promoção</a></li>
										<li class="<?php echo $aba1225; ?>"><a href="action.do?mod=<?php echo fnEncode(1225)."&id=".fnEncode($cod_empresa); ?>">Produtos Promoção</a></li>										
										<li class="<?php echo $aba1372; ?>"><a href="action.do?mod=<?php echo fnEncode(1372)."&id=".fnEncode($cod_empresa); ?>">Controle do Estoque</a></li>										
										<li class="<?php echo $aba1227; ?>"><a href="action.do?mod=<?php echo fnEncode(1227)."&id=".fnEncode($cod_empresa); ?>">Banner</a></li>										
									</ul>
