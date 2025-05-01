<?php
//inicialização
$aba1511 = "";
$aba1610 = "";
$aba1510 = "";

switch ($abaInfoSuporte) {
    case 1511: //cadastro de help desk
        $aba1511 = "active";
        break;
    case 1610: //plataforma
        $aba1610 = "active";
        break;
    case 1510: //tipo de solicitação
        $aba1510 = "active";
        break;
	}
	
//fnEscreve($aba1278);
//fnEscreve("Ricardo...");

?>
                             
									<ul class="nav nav-tabs">                               
                                        <li class="<?php echo $aba1510; ?>"><a href="action.php?mod=<?php echo fnEncode(1510)?>&id=<?php echo fnEncode($cod_empresa)?>&idP=<?php echo fnEncode($cod_pesquisa)?>&idc=<?=fnEncode($cod_campanha)?>">Configuração da Pesquisa</a></li>
                                        <li class="<?php echo $aba1511; ?>"><a href="action.php?mod=<?php echo fnEncode(1511)?>&id=<?php echo fnEncode($cod_empresa)?>&idp=<?=fnEncode($cod_pesquisa)?>&idd=<?=fnEncode($des_dominio)?>&idc=<?php echo fnEncode($cod_campanha);?>">Exportação da Lista</a></li>
										<li class="<?php echo $aba1610; ?>"><a href="action.php?mod=<?php echo fnEncode(1511)?>&id=<?php echo fnEncode($cod_empresa)?>&idp=<?=fnEncode($cod_pesquisa)?>&idd=<?=fnEncode($des_dominio)?>&idc=<?php echo fnEncode($cod_campanha);?>">Usuários Relatório</a></li>
									</ul>                                                   
									<div class="push20"></div>                              
