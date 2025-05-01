<?php
//inicialização
$aba1688 = "";
$aba1253 = "";
$aba1703 = "";
$aba1810 = "";
$aba1818 = "";
$aba1848 = "";

switch ($abaCli) {
    case 1688: //cliente
        $aba1688 = "active";
        break;    
	case 1253: //follow comunicação
        $aba1253 = "active";
        break;
    case 1703: //lançamentos RH
        $aba1703 = "active";
        break;
    case 1823: //lançamentos campanha
        $aba1823 = "active";
        break;
    case 1810: //contrato RH
        $aba1810 = "active";
        break;
    case 1818: //anexo de doc RH
        $aba1818 = "active";
        break;
    case 1848: //anexo de doc RH
        $aba1848 = "active";
        break;    
    case 2073: //anexo de doc RH
        $aba2073 = "active";
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


	<?php switch ($_SESSION["SYS_COD_SISTEMA"])  { 
			case 19: //rh
	?>
			
			<ul class="nav nav-tabs">

				<?php if(fnControlaAcesso("1688",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba1688; ?>"><a href="action.do?mod=<?php echo fnEncode(1688)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Cliente</a></li>
				<?php } ?>
				
				<?php if(fnControlaAcesso("1253",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba1253; ?>"><a href="action.do?mod=<?php echo fnEncode(1253)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Follow Up</a></li>
				<?php } ?>

				<?php if(fnControlaAcesso("1703",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba1703; ?>"><a href="action.do?mod=<?php echo fnEncode(1703)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Lançamentos RH</a></li>
				<?php } ?>	
				
				<?php if(fnControlaAcesso("1818",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
						<li class="<?php echo $aba1818; ?>"><a href="action.do?mod=<?php echo fnEncode(1818)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Anexo de Documentos</a></li>
				<?php } ?>	

				<?php if(fnControlaAcesso("2073",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba2073; ?>"><a href="action.do?mod=<?php echo fnEncode(2073)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Lançamentos Atestados</a></li>
				<?php } ?>	

				<?php if(fnControlaAcesso("1848",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
						<li class="<?php echo $aba1848; ?>"><a href="action.do?mod=<?php echo fnEncode(1848)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Prestação de Contas</a></li>
				<?php } ?>	

			</ul>

	<?php
			break;
			case 20: //campanha
	?>

			<ul class="nav nav-tabs">

				<?php if(fnControlaAcesso("1688",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba1688; ?>"><a href="action.do?mod=<?php echo fnEncode(1688)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Cliente</a></li>
				<?php } ?>
				
				<?php if(fnControlaAcesso("1253",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba1253; ?>"><a href="action.do?mod=<?php echo fnEncode(1253)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Follow Up</a></li>
				<?php } ?>

				<?php if(fnControlaAcesso("1810",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
						<li class="<?php echo $aba1810; ?>"><a href="action.do?mod=<?php echo fnEncode(1810)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Contrato</a></li>
				<?php } ?>				

				<?php if(fnControlaAcesso("1823",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
				<li class="<?php echo $aba1823; ?>"><a href="action.do?mod=<?php echo fnEncode(1823)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Carteira</a></li>
				<?php } ?>	

			
				<?php if(fnControlaAcesso("1818",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
						<li class="<?php echo $aba1818; ?>"><a href="action.do?mod=<?php echo fnEncode(1818)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Anexo de Documentos</a></li>
				<?php } ?>	

				<?php if(fnControlaAcesso("1848",$_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
						<li class="<?php echo $aba1848; ?>"><a href="action.do?mod=<?php echo fnEncode(1848)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_cliente); ?>">Prestação de Contas</a></li>
				<?php } ?>	
			</ul>

	
	<?php 
			break;
	
	} ?>	
