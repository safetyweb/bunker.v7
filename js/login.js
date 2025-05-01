$(document).ready(function(){
$("#Login").click(function(){
var email = $("#email").val();
var password = $("#password").val();

// Checking for blank fields.
if( email =='' || password ==''){
$('input[type="text"],input[type="password"]').css("border","2px solid red");
$('input[type="text"],input[type="password"]').css("box-shadow","0 0 3px red");
$('#msgRetorno').html('<div class="alert alert-danger" role="alert">Por Favor Preencha os campos!</div>');
}else {
$('#mensagem').html('<img src="images/loading.gif"/>Aguarde...');
$('#msgRetorno').html('');

     
$.post("_system/seguranca.php",{ email1: email, password1:password},
function(data) {
if(data=='Usuário não encontrado!') {
$('input[type="text"]').css({"border":"2px solid red","box-shadow":"0 0 3px red"});
$('input[type="password"]').css({"border":"2px solid #00F5FF","box-shadow":"0 0 5px #00F5FF"});


}else if(data=='Email or Password is wrong...!!!!'){

$('input[type="text"],input[type="password"]').css({"border":"2px solid red","box-shadow":"0 0 3px red"});


} else if(data=='Successfully Logged in...'){

$("form")[0].reset();
$('input[type="text"],input[type="password"]').css({"border":"2px solid #00F5FF","box-shadow":"0 0 5px #00F5FF"});


} else{
//alert(data);

$('#mensagem').html('<img src="images/loading.gif"/>Aguarde...');
$('#msgRetorno').html('<div class="alert alert-danger" role="alert">'+data+'</div>');
$('#mensagem').html('');

 

/////////// 
}
});
}
});
});