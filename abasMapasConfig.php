<ul class="nav nav-tabs"> 

	<li class="<?=($tipo == "CAD" || $tipo == "ALT"?"active":""); ?>"><a href="action.do?mod=<?=$_GET["mod"]; ?>&id=<?=$_GET["id"]; ?>&idT=<?=$_GET["idT"]; ?>&tipo=<?=fnEncode("ALT"); ?>&pop=<?=$_GET["pop"]; ?>">Cadastro</a></li>
	<li class="<?=($tipo == "IMP"?"active":""); ?>"><a href="action.do?mod=<?=$_GET["mod"]; ?>&id=<?=$_GET["id"]; ?>&idT=<?=$_GET["idT"]; ?>&tipo=<?=fnEncode("IMP"); ?>&pop=<?=$_GET["pop"]; ?>">Importa&ccedil;&atilde;o</a></li>

	
</ul>                                                   
<div class="push20"></div>                              