<?php
include_once 'header.php';
$tituloPagina = "Faça seu login";
include_once "navegacao.php";

$infoLogin = $_GET['idL'];


if($infoLogin != ""){
    $infoLogin = base64_decode($infoLogin);
    $infoLogin = explode("|", $infoLogin);
    $infoUsuario = fnDecode($infoLogin[0]);
    if(strlen($infoUsuario) <= 11){
        $tipo = "F";
    }else{
        $tipo = "J";
    }
    $infoUsuario = fnCompletaDoc($infoUsuario,$tipo);
    $infoSenha = fnDecode($infoLogin[1]);
    $checkManter = "checked";
}else{
    $infoUsuario = "";
    $infoSenha = "";
    $checkManter = "checked";
}

// echo($infoLogin);

?>
		
        <div class="container">

			<div class="push20"></div> 

            <div class="text-center pagination-centered">
                <a class="">
                    <img alt="" class="logo img-responsive center-block" src="img/user_icon.png">
                </a>
            </div>

            <div class="push10"></div> 
				
			<style>

			.bigCheck {width:18px; height: 18px;}

			</style>

			

            <form class="form-signin" method="post" id="formLogin" action="">
                <!-- <h4>1</h4> -->
                <!-- <label for="login" class="sr-only">Email</label> -->
				<input type="text" class="form-control input-lg text-center cpfcnpj" name="CPF" id="CPF" value="<?=$infoUsuario?>" placeholder="Informe seu CPF/CNPJ" required style="font-size: 20px;">
                <!-- <label for="senha" class="sr-only">Senha</label> -->
                <div class="push10"></div> 	
                <input type="password" name="senha" id="senha"  value="<?=$infoSenha?>" class="form-control" placeholder="Sua Senha" required style="font-size: 20px;">
                <?php 

                    
                    // if($_COOKIE['manter'] == "S"){
                    //     $checkManter = "checked";
                    // }
                ?>
                <div class="col-xs-12 text-center">
                    <div class="push5"></div>
                    <input type="checkbox" name="MANTER" id="MANTER" value="S" <?=$checkManter?>>&nbsp;
                    <label for="MANTER">Manter informações de login</label>
                    <div class="push20"></div>
                </div>
                <?php // if($_COOKIE['login'] != "" && $_COOKIE['senha'] != ""){ ?>
                <!-- <div class="col-xs-12 text-center">
                    <div class="push5"></div>
                    <a href="javascript:void(0)" onclick="zeraLogin(this)" class="text-white text-right">Não sou este usuário</a>
                    <div class="push20"></div>
                </div> -->
                <?php // } ?>
                <!-- <button class="btn btn-primary btn-block" type="submit">ENTRAR</button> -->
                <button type="button" class="btn btn-primary btn-block" name="btLogin" id="btLogin">ENTRAR</button>
				<div class="push10"></div>
                <div class="errorLogin" style="color: red; text-align: center; display: none">CPF/senha inválido(s).</div>
                <div class="push20"></div>
				<!-- <center><a href="recuperarSenha.php?key=<?=fnEncode($cod_empresa)?>">Esqueci minha senha</a></center> -->
                <?php // if($_GET['dev'] == 11478){ ?>
                <center><a href="javascript:void(0)" onclick='window.location.href = "recuperacaoSenha.do?key=<?=$_GET[key]?>&t=<?=$rand?>&cpf="+btoa($("#CPF").val())+"&t=<?=$rand?>"' >Esqueci minha senha </a></center> <br />
                <?php // } ?>

                
                <center><span>Não possui cadastro? </span><a href="consulta_V2.do?key=<?=$_GET[key]?>&t=<?=$rand?>" >Cadastre-se</a></center> <br /><div class="push30"></div>

                <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">

            </form>
			
            <div class="push10"></div>
			
            <!-- <center><a href="login2.php" class="btn btn-default">Esqueci a senha</a></center> -->
			
            <div class="push20"></div>

        </div> <!-- /container -->

    <?php include 'footer.php'; ?>

    <script>

       
            
    jQuery(document).ready(function( $ ) {

        // alert("<?=$cod_empresa?>");

        $('#btLogin').click(function() {
        //alert("click");
        
            var pCPF = $('#CPF').val().trim(),
            pSenha = $('#senha').val().trim(),
            pManter = "N",
            cod_empresa = $('#COD_EMPRESA').val(),
            infoLogin = "";
            // alert(cod_empresa);

            if($('#MANTER').prop("checked")){
                 pManter = "S";
            }
            
            $.ajax({
                type: "POST",                
                url: "ajxLogin.php",
                data: { CPF: pCPF, senha: pSenha, codEmpresa: cod_empresa, manter: pManter},
                success: function(msg) {

                    let tipo = msg.split(",");

                    if(tipo[0] == 1){
                        // LOGIN DEFAULT
                        $("#formLogin").attr("action","https://<?=$_SERVER['SERVER_NAME']?>/app/novoMenu.do?key=<?=$_GET[key]?>&idU="+tipo[1]+"&log=1&t=<?=$rand?>").submit();
                    }else if(tipo[0] == 2){
                        // LOGIN + ATUALIZACAO LGPD CADASTRO
                        $("#formLogin").attr("action","https://<?=$_SERVER['SERVER_NAME']?>/app/cadastro_V2.do?key=<?=$_GET[key]?>&idU="+tipo[1]+"&log=1&t=<?=$rand?>").submit();
                    }else if(tipo[0] == 0){
                        // ERRO DE LOGIN
                        $('.errorLogin').show();
                    }else{
                        // VERIFICACAO LGPD

                        // VERIFICA SE É PRA MANTER LOGADO
                        if(pManter == "S"){
                            infoLogin = "&idL="+tipo[3];
                        }

                        if(tipo[1] == '1'){ 
                            // LOGIN + ATUALIZACAO LGPD SIMPLES
                            // window.location.replace("https://<?=$_SERVER['SERVER_NAME']?>/app/aceiteLGPD.do?key=<?=$_GET[key]?>&idc="+tipo[1]+"&log=1"+infoLogin);
                            $("#formLogin").attr("action","https://<?=$_SERVER['SERVER_NAME']?>/app/aceiteLGPD.do?key=<?=$_GET[key]?>&idU="+tipo[2]+"&log=1&t=<?=$rand?>"+infoLogin).submit();
                        }else if(tipo[1] == '2'){
                            // LOGIN + ATUALIZACAO LGPD CADASTRO
                            // window.location.replace("https://<?=$_SERVER['SERVER_NAME']?>/app/cadastro_V2.do?key=<?=$_GET[key]?>&idc="+tipo[1]+"&log=1"+infoLogin);
                            $("#formLogin").attr("action","https://<?=$_SERVER['SERVER_NAME']?>/app/cadastro_V2.do?key=<?=$_GET[key]?>&idU="+tipo[2]+"&log=1&t=<?=$rand?>"+infoLogin).submit();
                        }else{ 
                            // LOGIN NORMAL SEM ATUALIZACAO
                            // window.location.replace("https://<?=$_SERVER['SERVER_NAME']?>/app/novoMenu.do?key=<?=$_GET[key]?>&idU="+tipo[1]+"&log=1"+infoLogin);
                            $("#formLogin").attr("action","https://<?=$_SERVER['SERVER_NAME']?>/app/novoMenu.do?key=<?=$_GET[key]?>&idU="+tipo[2]+"&log=1&t=<?=$rand?>"+infoLogin).submit();
                        }
                    }

                }
            }); 
                
        });

        

        if($('.cpfcnpj').val() != undefined){
            mascaraCpfCnpj($('.cpfcnpj'));
        }

    });
    
    function mascaraCpfCnpj(cpfCnpj){
        var optionsCpfCnpj = {
            onKeyPress: function (cpf, ev, el, op) {
                var masks = ['000.000.000-000', '00.000.000/0000-00'],
                    mask = (cpf.length >= 15) ? masks[1] : masks[0];
                cpfCnpj.mask(mask, op);
            }
        }   

        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
            
        cpfCnpj.mask(mask, optionsCpfCnpj);     
    }

    function login(){
        $('#btLogin').click();
    }

    

</script>