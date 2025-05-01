<?php
//inicialização
$aba2023 = "";
$aba2025 = "";
$aba2012 = "";

switch ($abaAdorai) {
    case 2023: //orcamento
        $aba2023 = "active";
        break;    
	case 2012: //calendario adorai
        $aba2012 = "active";
        break;	
    case 2025: //calendario adorai
        $aba2025 = "active";
        break;

}

?>


	<ul class="nav nav-tabs">
		<li class="<?php echo $aba2012; ?>"><a href="action.do?mod=<?php echo fnEncode(2012); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idp=<?php echo fnEncode($cod_pedido); ?>&pop=true">Detalhes</a>
		</li>

		<li class="<?php echo $aba2023; ?>"><a href="action.do?mod=<?php echo fnEncode(2023); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idp=<?php echo fnEncode($cod_pedido); ?>&pop=true">Pagamento</a>
		</li>
		
		<li class="<?php echo $aba2025; ?>"><a href="action.do?mod=<?php echo fnEncode(2025); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idp=<?php echo fnEncode($cod_pedido); ?>&pop=true">Parcelas</a>
		</li>

	</ul>