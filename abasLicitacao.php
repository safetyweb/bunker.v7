<?php
//inicialização
$aba1089 = "";
$aba1364 = "";

switch ($abaProposta) {
   
    case 1089: //proposta
        $aba1089 = "active";
        break;

    case 1364: //empresa
        $aba1364 = "active";
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

                                            <li class="<?php echo $aba1089; ?><?=$disabled?>">
                                                <a href="action.do?mod=<?php echo fnEncode(1089)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                                                Licitação </a>
                                            </li>
											
                                            <li class="<?php echo $aba1364; ?><?=$disabled?>">
                                                <a href="action.do?mod=<?php echo fnEncode(1364)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                                                Itens do Objeto</a>
                                            </li>
                                            
                                            <li class="<?php echo $aba13641; ?><?=$disabled?>">
                                                <a href="action.do?mod=<?php echo fnEncode(1805)."&id=".fnEncode($cod_empresa)."&idE=".fnEncode($cod_entidad)."&idC=".fnEncode($cod_conveni); ?>">
                                                Detalhes dos Itens</a>
                                            </li>
                                            
                                        </ul>
                        
                                    </div>

                               