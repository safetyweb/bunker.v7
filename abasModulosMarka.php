<?php
//inicialização
$aba1115 = "";
$aba1116 = "";
$aba1119 = "";
$aba1120 = "";
$aba1534 = "";

switch ($abaModulo) {
    case 1115: //blocos do template
        $aba1115 = "active";
        break;   

    case 1116: //Grupo Modulos
        $aba1116 = "active";
        break;
		
    case 1119: //Áreas de bloqueio
        $aba1119 = "active";
        break;		
   		
    case 1120: //lista módulos
        $aba1120 = "active";
        break;	
		
    case 1121: //matriz bloqueio
        $aba1121 = "active";
        break;		

    case 1534: //matriz bloqueio
        $aba1534 = "active";
        break;		


    //default:
    //code to be executed if n is different from all labels;
}
?>

									<ul class="nav nav-tabs">
										<li class="<?php echo $aba1116; ?>"><a href="action.do?mod=<?php echo fnEncode(1116); ?>">Certificações Marka</a></li>
										<li class="<?php echo $aba1115; ?>"><a href="action.do?mod=<?php echo fnEncode(1115); ?>">Módulos Marka</a></li>
										<li class="<?php echo $aba1120; ?>"><a href="action.do?mod=<?php echo fnEncode(1120); ?>" target="_blank">Lista Módulos</a></li>
										<li class="<?php echo $aba1119; ?>"><a href="action.do?mod=<?php echo fnEncode(1119); ?>">Áreas de Bloqueio</a></li>
										<li class="<?php echo $aba1121; ?>"><a href="action.do?mod=<?php echo fnEncode(1121); ?>">Matriz de Bloqueio</a></li>
										<li class="<?php echo $aba1534; ?>"><a href="action.do?mod=<?php echo fnEncode(1534); ?>">Versões do Sistema</a></li>
									</ul>
