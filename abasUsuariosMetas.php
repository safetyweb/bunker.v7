<?php
//inicialização
$aba1302 = "";
$aba1304 = "";
$aba1305 = "";
$aba1331 = "";
$aba1333 = "";

switch ($abaMetas) {
    case 1302: //usuários da meta
        $aba1302 = "active";
        break;
    case 1304: //produtos da meta
        $aba1304 = "active";
        break;
    case 1305: //preview
        $aba1305 = "active";
        break;
    case 1331: //valores do rateio
        $aba1331 = "active";
        break;    
	case 1333: //controle do rateio 
        $aba1333 = "active";
        break;	
	case 1334: //controle do rateio 
        $aba1334 = "active";
        break;
	}
	
//fnEscreve($aba1278);

?>
                             
									<ul class="nav nav-tabs">                               
										<?php
										/*<li class="<?php echo $aba1304; ?>"><a href="action.do?mod=<?php echo fnEncode(1304); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Produtos da Meta</a></li>*/
										?>
                                        <li class="<?php echo $aba1302; ?>"><a href="action.do?mod=<?php echo fnEncode(1302); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Controle de Metas</a></li>
										<li class="<?php echo $aba1331; ?>"><a href="action.do?mod=<?php echo fnEncode(1331); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Valores de Rateio</a></li>
										<?php
										/*<li class="<?php echo $aba1333; ?>"><a href="action.do?mod=<?php echo fnEncode(1333); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Controle do Rateio</a></li>*/
										?>
										<li class="<?php echo $aba1305; ?>"><a href="action.do?mod=<?php echo fnEncode(1305); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Preview Metas</a></li>
										<li class="<?php echo $aba1334 ?>"><a href="action.do?mod=<?php echo fnEncode(1334); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Preview Prêmios</a></li>
									</ul>                                                   
									<div class="push20"></div>                              
