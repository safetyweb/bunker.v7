 <?php
        //inicialização
        $aba1159 = "";
        $aba1160 = "";
        $aba1161 = "";
        $aba1018 = "";

        switch ($abaModulo) {
                case 1159: //matriz de integração
                        $aba1159 = "active";
                        break;
                case 1160: //campos obrigatórios
                        $aba1160 = "active";
                        break;
                case 1161: //chave de acesso
                        $aba1161 = "active";
                        break;
                case 1018: //perfil
                        $aba1018 = "active";
                        break;

                        //default:
                        //code to be executed if n is different from all labels;
        }
        ?>

 <ul class="nav nav-tabs">
         <li class="<?php echo $aba1159; ?>"><a href="action.do?mod=<?php echo fnEncode(1159) . "&id=" . $_GET['id']; ?>">Matriz de Integração</a></li>
         <li class="<?php echo $aba1160; ?>"><a href="action.do?mod=<?php echo fnEncode(1160) . "&id=" . $_GET['id']; ?>">Campos Obrigatórios</a></li>
         <li class="<?php echo $aba1161; ?>"><a href="action.do?mod=<?php echo fnEncode(1161) . "&id=" . $_GET['id']; ?>">Chave de Acesso</a></li>
         <!--<li class="<?php echo $aba1018; ?>"><a href="action.do?mod=<?php echo fnEncode(1018) . "&id=" . $_GET['id']; ?>">Perfil</a></li>-->
 </ul>