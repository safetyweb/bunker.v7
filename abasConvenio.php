<?php
//inicialização
$aba1075 = "";
$aba1097 = "";
$aba1080 = "";

switch ($abaConvenio) {
    case 1075: //empresa
        $aba1075 = "active";
        break;

    case 1097: //empresa
        $aba1097 = "active";
        break;

    case 1080: //empresa
        $aba1080 = "active";
        break;

    case 1093: //aditivo
        $aba1093 = "active";
        break;
    }

if(isset($_GET['idC'])){
    $cod_conveni = fnDecode($_GET['idC']);
}

if($cod_conveni != 0 && $cod_conveni != ""){
    $disabled = "";
}else{
    $disabled = "disabled";
}
?>

    <div class="tabbable-line">

        <ul class="nav nav-tabs ">
            <li>
                <a href="action.do?mod=<?php echo fnEncode(1563)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
                <span class="fal fa-arrow-circle-left fa-2x"></span></a>
            </li>
            
			<!--
            <li class="<?php echo $aba1097; ?>">
                <a href="action.do?mod=<?php echo fnEncode(1097)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
                Convênio</a>
            </li>
            
            <li class="<?php echo $aba1093; ?>">
                <a href="action.do?mod=<?php echo fnEncode(1093)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
                Aditivos</a>
            </li>
            
            <li class="<?php echo $aba1075; ?><?=$disabled?>">
                <a href="action.do?mod=<?php echo fnEncode(1075)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                Dados da Entidade </a>
            </li>
            
            <li class="<?php echo $aba1080; ?><?=$disabled?>">
                <a href="action.do?mod=<?php echo fnEncode(1080)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                Dados Bancários da Entidade </a>
            </li>
			-->
            
        </ul>

    </div>
                                    
                               