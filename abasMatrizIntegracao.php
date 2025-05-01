<?php
//inicialização
$aba1151 = "";
$aba1152 = "";
$aba1153 = "";



switch ($abaModulo) {
    case 1151: //fases da integração
        $aba1151 = "active";
        break;   

    case 1152: //ações da integração
        $aba1152 = "active";
        break;
		
    case 1153: //ações da integração
        $aba1153 = "active";
        break;

    //default:
    //code to be executed if n is different from all labels;
}
?>

									<ul class="nav nav-tabs">
										<li class="<?php echo $aba1151; ?>"><a href="action.do?mod=<?php echo fnEncode(1151); ?>">Fases da Integração</a></li>
										<li class="<?php echo $aba1152; ?>"><a href="action.do?mod=<?php echo fnEncode(1152); ?>">Ações da Integração</a></li>
										<li class="<?php echo $aba1153; ?>"><a href="action.do?mod=<?php echo fnEncode(1154); ?>">Matriz por Empresa</a></li>
									</ul>
