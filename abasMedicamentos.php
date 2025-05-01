<?php
//inicialização

$aba1899 = "";
$aba2000 = "";
$aba2001 = "";
$aba2002 = "";


switch ($abasMedicamentos) {
    case 1899: //empresa
        $aba1899 = "active";
        break;
    case 2000: //usuários
        $aba2000 = "active";
        break;
    case 2001: //personalização
        $aba2001 = "active";
        break;
    case 2002: //perfil
        $aba2002 = "active";
        break;
	}

?>

	<ul class="nav nav-tabs">
	
		<li class="<?php echo $aba1899; ?>"><a href="action.do?mod=<?php echo fnEncode(1899)."&id=".fnEncode($cod_empresa); ?>">Uso Continuo Original</a></li>
		
		<li class="<?php echo $aba2000; ?>"><a href="action.do?mod=<?php echo fnEncode(2000)."&id=".fnEncode($cod_empresa); ?>">Uso Continuo</a></li>

		<li class="<?php echo $aba2001; ?>"><a href="action.do?mod=<?php echo fnEncode(2001)."&id=".fnEncode($cod_empresa); ?>">Patologias</a></li>

		<li class="<?php echo $aba2002; ?>"><a href="action.do?mod=<?php echo fnEncode(2002)."&id=".fnEncode($cod_empresa); ?>">Laboratórios</a></li>

	</ul>
