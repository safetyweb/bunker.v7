<?php
//inicialização
$aba1315 = "";
$aba1623 = "";
$aba1130 = "";
$aba1317 = "";
$aba1389 = "";
$aba1948 = "";
$aba1955 = "";
$aba2044 = "";
$aba1561 = "";
$aba1319 = "";
$aba1410 = "";
$aba0 = "";


switch ($abaComunica) {
    case 1315: //sms
        $aba1315 = "active";
        break;

    case 1623: //sendi blue
        $aba1623 = "active";
        break;

    case 1130: //agendador
        $aba1130 = "active";
        break;

    case 1317: //parceiro comunicação
        $aba1317 = "active";
        break;

    case 1319: //parceiro ecommerce
        $aba1319 = "active";
        break;

    case 1389: //encaminhador ecommerce
        $aba1389 = "active";
        break;

    case 1410: //smtp
        $aba1410 = "active";
        break;

    case 1561: //whats
        $aba1561 = "active";
        break;

    case 1955: //whats Kommo
        $aba1955 = "active";
        break;

    case 1948: //gerenciador senhas salvas whats
        $aba1948 = "active";
        break;

    case 2044: //gerenciador senhas salvas whats
        $aba2044 = "active";
        break;
}
?>


<ul class="nav nav-tabs">
    <li class="<?php echo $aba1315; ?>"><a href="action.do?mod=<?php echo fnEncode(1315); ?>">Senhas SMS</a></li>
    <li class="<?php echo $aba1561; ?>"><a href="action.do?mod=<?php echo fnEncode(1561); ?>">Senhas Whats (Old)</a></li>
    <li class="<?php echo $aba1948; ?>"><a href="action.do?mod=<?php echo fnEncode(1948); ?>">WhatsApp</a></li>
    <li class="<?php echo $aba1955; ?>"><a href="action.do?mod=<?php echo fnEncode(1955); ?>">WhatsApp (Kommo)</a></li>
    <li class="<?php echo $aba2044; ?>"><a href="action.do?mod=<?php echo fnEncode(2044); ?>">WhatsApp (Empresa)</a></li>
    <li class="<?php echo $aba1319; ?>"><a href="action.do?mod=<?php echo fnEncode(1319); ?>">Senhas e-Commerce</a></li>
    <li class="<?php echo $aba1410; ?>"><a href="action.do?mod=<?php echo fnEncode(1410); ?>">Senhas SMTP</a></li>
    <li class="<?php echo $aba1389; ?>"><a href="action.do?mod=<?php echo fnEncode(1389); ?>">Encaminhador e-Commerce</a></li>
    <!-- <li class="<?php echo $aba1243; ?>"><a href="action.do?mod=<?php echo fnEncode(1243); ?>">Senhas e-Mail</a></li> -->
    <li class="<?php echo $aba1623; ?>"><a href="action.do?mod=<?php echo fnEncode(1623); ?>">Senhas e-Mail</a></li>
    <li class="<?php echo $aba1317; ?>"><a href="action.do?mod=<?php echo fnEncode(1317); ?>">Parceiro Comunicação</a></li>
    <li class="<?php echo $aba1130; ?>"><a href="action.do?mod=<?php echo fnEncode(1130); ?>">Agendador Cron</a></li>
    <li class="<?php echo $aba0; ?>"><a href="action.do?mod=<?php echo fnEncode(0); ?>">Agendador BD</a></li>
</ul>