<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

?>


<form method="post" id="frmLogin" name="frmLogin" action="https://mail2easypro.com/authenticate" target="mail">
<input type="hidden" name="hash" id="hash"  value="" />
<input id="username" type="hidden" value="mkt@markafidelizacao.com.br" name="username" placeholder="Seu e-mail" class="field-text">
<input id="password" type="hidden" value="olecram1974" name="password" placeholder="Sua senha" class="field-text">
</form>	
<script>
function carregar(){
//alert("Here i go...");
//$( "#frmLogin" ).submit();	
}
carregar();
</script>
<!--
<iframe src="http://mail.markafidelizacao.com.br/console/login.aspx" style="border: 0; position:fixed; top:0; left:0; right:0; bottom:0; width:100%; height:100%">
-->

<iframe src="http://www.marka.mk/" name="mail" style="border: 0; position:fixed; top:60px; left:0; right:0; bottom:0; width:100%; height:100%">