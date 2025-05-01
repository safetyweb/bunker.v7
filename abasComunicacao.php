<?php
//inicialização
$aba1442 = "";
$aba1445 = "";
$aba1454 = "";

switch ($abasComunicacao) {
    case 1442: //Tipo Comunicação
        $aba1442 = "active";
        break;

    case 1445: //Faixas Comunicação
        $aba1445 = "active";
        break;

    case 1454: //Preços Comunicação
        $aba1454 = "active";
        break;

    case 1458: //Configuração de Preços Comunicação
        $aba1458 = "active";
        break;
    
}
?>


<ul class="nav nav-tabs">
    <li class="<?php echo $aba1442; ?>"><a href="action.do?mod=<?php echo fnEncode(1442); ?>">Tipo Comunicação</a></li>
    <li class="<?php echo $aba1445; ?>"><a href="action.do?mod=<?php echo fnEncode(1445); ?>">Faixas Comunicação</a></li>
    <li class="<?php echo $aba1454; ?>"><a href="action.do?mod=<?php echo fnEncode(1454); ?>">Preços Comunicação</a></li>    
    <li class="<?php echo $aba1458; ?>"><a href="action.do?mod=<?php echo fnEncode(1458); ?>">Configuração de Preços Comunicação</a></li>    
</ul>

<div class="push30"></div>
