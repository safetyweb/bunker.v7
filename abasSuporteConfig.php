<?php
//inicialização
$aba1282 = "";
$aba1280 = "";
$aba1267 = "";
$aba1278 = "";
$aba1269 = "";
$aba1270 = "";
$aba1431 = "";
$aba1271 = "";
$aba1272 = "";
$aba1275 = "";

switch ($abaInfoSuporte) {
    case 1282: //cadastro de help desk
        $aba1282 = "active";
        break;
    case 1280: //plataforma
        $aba1280 = "active";
        break;
    case 1267: //tipo de solicitação
        $aba1267 = "active";
        break;
    case 1278: //versão da integração
        $aba1278 = "active";
        break;
    case 1269: //status
        $aba1269 = "active";
        break;
    case 1270: //prioridade
        $aba1270 = "active";
        break;
    case 1431: //subcategoria de tipo
        $aba1431 = "active";
        break;
    case 1271: //importação de excell
        $aba1271 = "active";
        break;
    case 1272: //lista help desk empresa
        $aba1272 = "active";
        break;
    case 1275: //lista help desk empresa
        $aba1275 = "active";
        break;
	}
	
//fnEscreve($aba1278);
//fnEscreve("Ricardo...");

?>
                             
									<ul class="nav nav-tabs">                               
                                        <li><a href="action.do?mod=<?php echo fnEncode(1281); ?>&id=QunXraEOVrg¢">Lista de Desenvolvimento</a></li>
                                        <li class="<?php echo $aba1282; ?>"><a href="action.do?mod=<?php echo fnEncode(1282); ?>">Lista de Chamados (Admin)</a></li>
										<li class="<?php echo $aba1280; ?>"><a href="action.do?mod=<?php echo fnEncode(1280); ?>&id=QunXraEOVrg¢">Lista de Chamados (Cliente)</a></li>
										<li class="<?php echo $aba1267; ?>"><a href="action.do?mod=<?php echo fnEncode(1267); ?>">Cadastro de Helpdesk (Admin)</a></li>
										<li class="<?php echo $aba1278; ?>"><a href="action.do?mod=<?php echo fnEncode(1278); ?>&id=QunXraEOVrg¢">Cadastro de Helpdesk (Cliente)</a></li>
                                        <li class="<?php echo $aba1269; ?>"><a href="action.do?mod=<?php echo fnEncode(1269); ?>">Cadastro de Plataforma</a></li>
                                        <li class="<?php echo $aba1270; ?>"><a href="action.do?mod=<?php echo fnEncode(1270); ?>">Cadastro de Tipo de Solicitação</a></li>
                                        <li class="<?php echo $aba1431; ?>"><a href="action.do?mod=<?php echo fnEncode(1431); ?>">Cadastro de Subcategoria da Solicitação</a></li>
                                        <li class="<?php echo $aba1271; ?>"><a href="action.do?mod=<?php echo fnEncode(1271); ?>">Cadastro de Versão da Integração</a></li>
										<li class="<?php echo $aba1272; ?>"><a href="action.do?mod=<?php echo fnEncode(1272); ?>">Cadastro de Status</a></li>
                                        <li class="<?php echo $aba1275; ?>"><a href="action.do?mod=<?php echo fnEncode(1275); ?>">Cadastro de Prioridade</a></li>
									</ul>                                                   
									<div class="push20"></div>                              
