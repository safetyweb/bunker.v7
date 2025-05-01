<?php
$aba = fnDecode($_GET["mod"]);

?>

	<ul class="nav nav-tabs">
		<li class="<?=($aba == 1586?"active":"")?>"><a href="action.do?mod=<?php echo fnEncode(1586); ?>">Read Me</a></li>
		<li class="<?=($aba == 1587?"active":"")?>"><a href="action.do?mod=<?php echo fnEncode(1587); ?>">Regras de Comunica&ccedil;&atilde;o</a></li>
	</ul>
