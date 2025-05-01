<?php
//inicialização
$aba1155 = "";
$aba1157 = "";

switch ($abaModulo) {
    case 1155: //fases da integração
        $aba1155 = "active";
        break;   

    case 1157: //ações da integração
        $aba1157 = "active";
        break;

    //default:
    //code to be executed if n is different from all labels;
}
?>

									<ul class="nav nav-tabs">
										<li class="<?php echo $aba1155; ?>"><a href="action.do?mod=<?php echo fnEncode(1155); ?>">Campos Obrigatórios</a></li>
										<li class="<?php echo $aba1157; ?>"><a href="action.do?mod=<?php echo fnEncode(1156); ?>">Matriz por Empresa</a></li>
									</ul>
