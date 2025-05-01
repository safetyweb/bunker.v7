
$(document).ready(function() {
    //MANIPULA OS LINKS, ADICIONANDO UM IDENTIFICADOR ÚNICO NO FINAL
	$("a").not('.addBox').each(function(){
        $(this).attr("href",$(this).attr("href")+"&<?=date("Ymdhis").round(microtime(true) * 1000);?>");
    });
	$("form").not('#formCliente').each(function(){
        $(this).attr("action",$(this).attr("action")+"&<?=date("Ymdhis").round(microtime(true) * 1000);?>");
    });
});
