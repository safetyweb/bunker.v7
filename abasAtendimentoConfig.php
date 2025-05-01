<?php
//inicialização
$aba1437 = "";
$aba1439 = "";
$aba1438 = "";
$aba1868 = "";
$aba1869 = "";

switch ($abaInfoAtendimento) {
    case 1437: //status
        $aba1437 = "active";
        break;
    case 1439: //tipo de solicitação
        $aba1439 = "active";
        break;
    case 1438: //prioridade
        $aba1438 = "active";
        break;
    case 1868: //tipo filtro
        $aba1868 = "active";
        break;
    case 1869: //filtros dinamicos
        $aba1869 = "active";
        break;
	}
	
//fnEscreve($aba1278);
//fnEscreve("Ricardo...");

?>
                         
<ul class="nav nav-tabs"> 

	<li class="<?php echo $aba1437; ?>"><a href="action.do?mod=<?php echo fnEncode(1437); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Cadastro de Status</a></li>
    <li class="<?php echo $aba1439; ?>"><a href="action.do?mod=<?php echo fnEncode(1439); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Cadastro de Tipo de Solicitação</a></li>
    <li class="<?php echo $aba1438; ?>"><a href="action.do?mod=<?php echo fnEncode(1438); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Cadastro de Prioridade</a></li>
    <li class="<?php echo $aba1868; ?>"><a href="action.do?mod=<?php echo fnEncode(1868); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Filtros</a></li>
    <li class="<?php echo $aba1869; ?>"><a href="action.do?mod=<?php echo fnEncode(1869); ?>&id=<?php echo fnEncode($cod_empresa); ?>">Ocorrências dos Filtros</a></li>
	
</ul>                                                   
<div class="push20"></div>                              
