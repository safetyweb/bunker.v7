<?php
//inicialização
$aba1091 = "";
$aba1362 = "";

switch ($abaProposta) {
   
    case 1091: //proposta
        $aba1091 = "active";
        break;

    case 1362: //empresa
        $aba1362 = "active";
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
                                                <a href="action.do?mod=<?php echo fnEncode(1344)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
                                                <span class="fal fa-arrow-circle-left fa-2x"></span></a>
                                            </li>

                                            <li class="<?php echo $aba1091; ?><?=$disabled?>">
                                                <a href="action.do?mod=<?php echo fnEncode(1091)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                                                Propostas </a>
                                            </li>
											
                                            <li class="<?php echo $aba1362; ?><?=$disabled?>">
                                                <a href="action.do?mod=<?php echo fnEncode(1362)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                                                Ata da Proposta </a>
                                            </li>
											                                            
                                        </ul>
                        
                                    </div>
									
                               