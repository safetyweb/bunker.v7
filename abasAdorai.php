<?php
//inicialização
$aba1820 = "";
$aba1829 = "";
$aba1830 = "";
$aba1831 = "";
$aba1832 = "";
$aba1832 = "";
$aba1826 = "";
$aba2005 = "";
$cod_cliente = 0;

switch ($abaAdorai) {
        case 1820: //orcamento
                $aba1820 = "active";
                break;
        case 1829: //calendario adorai
                $aba1829 = "active";
                break;
        case 1830: //calendario Piedade 2
                $aba1830 = "active";
                break;
        case 1831: //calendario Paraty
                $aba1831 = "active";
                break;
        case 1832: //calendario Cunha
                $aba1832 = "active";
                break;
        case 1833: //hoteis
                $aba1833 = "active";
                break;
        case 1838: //reservas foco
                $aba1838 = "active";
                break;
        case 2006: //reservas adorai
                $aba2006 = "active";
                break;
        case 1826: //calendario unificado
                $aba1826 = "active";
                break;
                //default:
                //code to be executed if n is different from all labels;
}
/*
Hotel ID:
3008  Adorai Cunha/SP  
956 Adorai Paraty/RJ  
3010 Adorai Piedade 2/SP  
2957 Adorai Piedade/SP  

usuário: adorai
senha: kJmnf345Hnfhd
*/

if ($cod_cliente == 0) {
        $abaClieOff = "disabled";
} else {
        $abaClieOff = "";
}

?>


<ul class="nav nav-tabs">

        <li class="<?php echo $aba1820; ?>"><a href="action.do?mod=<?php echo fnEncode(1820); ?>">Orçamento</a></li>

        <li class="<?php echo $aba1826; ?>"><a href="action.do?mod=<?php echo fnEncode(1826); ?>">Calendário</a></li>

        <!-- <li class="<?php echo $aba1830; ?>"><a href="action.do?mod=<?php echo fnEncode(1830) . "&idH=" . fnEncode(3010); ?>">Piedade 2</a></li>
		
		<li class="<?php echo $aba1831; ?>"><a href="action.do?mod=<?php echo fnEncode(1831) . "&idH=" . fnEncode(956); ?>">Paraty</a></li>
		
		<li class="<?php echo $aba1832; ?>"><a href="action.do?mod=<?php echo fnEncode(1832) . "&idH=" . fnEncode(3008); ?>">Cunha</a></li>-->

        <!-- <li class="<?php echo $aba1838; ?>"><a href="action.do?mod=<?php echo fnEncode(1838); ?>">Reservas Foco</a></li>  -->

        <li class="<?php echo $aba2006; ?>"><a href="action.do?mod=<?php echo fnEncode(2006); ?>">Reservas Adorai</a></li>

        <li class="<?php echo $aba1833; ?>"><a href="action.do?mod=<?php echo fnEncode(1833) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">Manutenção</a></li>

</ul>