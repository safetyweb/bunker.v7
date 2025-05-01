<?php
//inicialização
$aba1024 = "";
$aba1053 = "";
$aba1054 = "";
$aba1072 = "";
$aba1067 = "";
$aba1081 = "";
$aba1112 = "";
$aba1253 = "";
$aba1665 = "";
$aba1976 = "";

switch ($abaCli) {
    case 1024: //cliente
        $aba1024 = "active";
        break;    
	case 1253: //follow up 
        $aba1253 = "active";
        break;	
    case 1665: //exclusão
        $aba1665 = "active";
        break;
    case 1976: //anexo
        $aba1976 = "active";
        break;
    case 1969: //bens
        $aba1969 = "active";
        break;





	case 1423: //cliente simples
        $aba1423 = "active";
        break;
	case 1475: //agenda
        $aba1475 = "active";
        break;
	case 1476: //atendimento
        $aba1476 = "active";
        break;
	case 1518: //estorno
        $aba1518 = "active";
        break;
    //default:
    //code to be executed if n is different from all labels;
}

if ($cod_cliente == 0){
  $abaClieOff = "disabled";	
} else {
  $abaClieOff = "";	
}

?>


<ul class="nav nav-tabs">

	<?php if(fnControlaAcesso("1024",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
	<li class="<?php echo $aba1024; ?>"><a href="action.do?mod=<?php echo fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Cliente</a></li>
	<?php } ?>

	<?php // if(fnControlaAcesso("1969",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
	<li class="<?php echo $aba1969; ?>"><a href="action.do?mod=<?php echo fnEncode(1969)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Bens</a></li>
	<?php // } ?>

	<?php if(fnControlaAcesso("1253",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
	<li class="<?php echo $aba1253; ?>"><a href="action.do?mod=<?php echo fnEncode(1253)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Follow Up</a></li>
	<?php } ?>

	<?php //if(fnControlaAcesso("1976",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
    <li class="<?php echo $aba1976.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1976)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Anexo de Documentos</a></li>										
	<?php //} ?>

    <?php if(fnControlaAcesso("1665",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
    <li class="<?php echo $aba1665.' '.$abaClieOff; ?>"><a href="action.do?mod=<?php echo fnEncode(1665)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Exclusão do Cliente</a></li>                                     
    <?php } ?>
	

</ul>
