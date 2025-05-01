<?php 
include './_system/_functionsMain.php';
include_once '../totem/funWS/atualizacadastro.php';

// echo fnDebug('true');

// $arrayCampos = explode(";", $key);


$hashLocal = mt_rand(); 

if( $_SERVER['REQUEST_METHOD']=='POST' )
{

  $request = md5( implode( $_POST ) );

  $cpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['cpf']));
  $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
  // $cod_sexopes = fnLimpaCampoZero($_REQUEST['COD_SEXOPES']);
  $nom_cliente = trim(strtoupper(fnAcentos(fnLimpaCampo($_REQUEST['NOM_CLIENTE']))));
  $radio = fnLimpaCampo($_REQUEST['RADIO']);
  $des_placa = fnLimpaCampo(strtoupper($_REQUEST['DES_PLACA']));
  $dat_nascime = fnLimpaCampo($_REQUEST['DAT_NASCIME']);
  $num_celular = trim(fnLimpaCampo($_REQUEST['NUM_CELULAR']));
  $num_cepozof = trim(fnLimpaCampo($_REQUEST['NUM_CEPOZOF']));
  $des_emailus = trim(strtolower(fnLimpaCampo($_REQUEST['DES_EMAILUS'])));
  $des_senhaus = fnLimpaCampo($_REQUEST['DES_SENHAUS']);
  $cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
  $cod_profiss = fnLimpaCampoZero($_REQUEST['COD_PROFISS']);

  if($radio == "MASCULINO"){
    $cod_sexopes = 1;
  }else if($radio == "FEMININO"){
    $cod_sexopes = 2;
  }else{
    $cod_sexopes = 3;
  }

  $sql = "SELECT LOG_USUARIO, DES_SENHAUS FROM USUARIOS
        WHERE COD_EMPRESA = $cod_empresa AND 
        COD_TPUSUARIO = 10  AND 
        LOG_ESTATUS = 'S' LIMIT 1";

  $qrUs = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

  $login = $qrUs['LOG_USUARIO'];
  $senha = fnDecode($qrUs['DES_SENHAUS']);

  // fnEscreve($login);
  // fnEscreve($senha);

  $dadoslogin = array(
   '0'=>$login,
   '1'=>$senha,
   '2'=>$cod_univend,
   '3'=>'maquina',
   '4'=>$cod_empresa
  );

  $dadosatualiza=Array(
            'nome'=>$nom_cliente,
            'sexo'=>$cod_sexopes,
            'email'=>$des_emailus,
            'telefone'=>$num_celular,
            'cpf'=>$cpf,
            'cartao'=>$cpf,
            'senha'=>$des_senhaus,
            'dt_nascimento'=>$dat_nascime,
            // 'profissao'=>$cod_profiss,
            'cep'=>$num_cepozof
          );
           

  $atualiza=atualizacadastro($dadosatualiza, $dadoslogin);

  // fnEscreve($atualiza);

  if($atualiza == "Registro inserido!" || $atualiza == "Cadastro Atualizado !"){
    $sql = "SELECT COD_CLIENTE, NUM_CGCECPF FROM CLIENTES WHERE NUM_CGCECPF ='$cpf' AND COD_EMPRESA = $cod_empresa";
    $qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
    $cod_cliente_cad = $qrCli['COD_CLIENTE'];
    $num_cgcecpf = $qrCli['NUM_CGCECPF'];
    // fnEscreve($cpf);

    $sql2 = "UPDATE CLIENTES SET COD_ENTIDAD = 61 WHERE COD_CLIENTE = $cod_cliente_cad AND COD_EMPRESA = $cod_empresa";
    mysqli_query(connTemp($cod_empresa,''),$sql2);

  }   

  // fnEscreve($atualiza);
  
  if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
  {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  }
  else
  {
    $_SESSION['last_request']  = $request;           
                      
                       
    if ($opcao != ''){
      
        //mensagem de retorno
        switch ($opcao)
        {
          case 'CAD':
            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>"; 
            break;
          case 'ALT':
            $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";    
            break;
          case 'EXC':
            $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";    
            break;
          break;
        }     
        $msgTipo = 'alert-success';             
      
    }  

  }     
    
}

// fnEscreve($cod_cliente_cad);

?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		$tituloPagina = "Cadastro";
		include "menu.php"; 
		?>

    <style type="text/css">
      .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -30px;
        position: relative;
        z-index: 2;
      }
    </style>	
		

      <form data-toggle="validator" role="form2" method="post" id="formulario">

        <div class="container">

        <div class="push30"></div>

        <div class="row">
          <div class="col-xs-12">
            <h4 style="font-weight: 900!important;">CADASTRAR MAIS PLACAS</h4>
          </div>
        </div>

        <div class="push10"></div>

        <div class="row">   

          <div class="col-xs-5 col-xs-offset-1">
            <div class="form-group">
              <input type="text" id="DES_PLACA" name="DES_PLACA" class="form-control input-hg text-center placa" placeholder="Sua placa" data-minlength="7" data-minlength-error="Formato inválido">
              <div class="help-block with-errors"></div>
            </div>
          </div>

          <div class="col-xs-5">
            <a href='javascript:void(0)' name="ADD" id="ADD" class="btn btn-info shadow" tabindex="5" disabled><span class="fa fa-plus"></span>&nbsp; Adicionar</a>
          </div>

        </div>

        <div class="push50"></div>

        <div class="row">
          <div class="col-xs-12">
            <h4 style="font-weight: 900!important;">PLACAS CADASTRADAS</h4>
          </div>
        </div>

        <div class="push30"></div>

        <div class="row" id="placasConteudo">
          <?php

            $sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $num_cgcecpf AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
            // fnescreve($sql);
            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

            $loopH=0;

            while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

            ?>

              <div class="col-xs-8" style="font-size: 20px!important;">
               <div class="col-xs-9">
                <p class="text-muted"><span class="fa fa-car"></span>&nbsp; <?=$qrVeic['DES_PLACA']?></p>
               </div>
               <div class="col-xs-3 text-right">
                <a href="javascript:void(0)" onclick='excPlaca("<?=fnEncode($qrVeic[COD_VEICULOS])?>")'><span class="fa fa-trash text-danger" style="padding-top: 3.5px;"></span></a>
               </div>
              </div>

            <?php

            }

          ?>
        </div>


        <div class="push100"></div>
        <div class="push50"></div>

        <input type="hidden" name="REFRESH_PLACA" id="REFRESH_PLACA" value="S">

        <div class="row">       
          
          <div class="col-xs-10 col-xs-offset-1">
            <button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-block getBtn shadow" tabindex="5">Cadastrar</button>
          </div>
              
          
        </div><!-- /row -->
            

        </div> <!-- /container -->

    </form>	

		<?php include 'jsLib.php';?>		
    </body>
</html>

<script>

  $(function(){

    // CADASTRA PLACA INSERIDA NO FORM DO CLIENTE E RETORNA SE É DUPLICADA OU NÃO
    cadPlaca();

    $("#CAD").click(function(e){
      e.preventDefault();
      if($("#REFRESH_PLACA").val() == 'S'){
        $.alert({
          title: "CADASTRO CONCLUÍDO",
          content: "O cadastro foi efetivado com sucesso. Faça login e aproveite as promoções!",
          type: 'green',
          columnClass: 'col-xs-12',
          buttons: {
            "LOGIN": {
              btnClass: 'btn-blue shadow',
               action: function(){
                window.location.href = "novoLogin.do";
               }
            }
          },
          backgroundDismiss: function(){
              return 'LOGIN';
          }
        });
      }else{
        $.alert({
          title: "CADASTRO PENDENTE",
          content: "O cadastro foi efetivado parcialmente. Faça login e cadastre seu veículo para aproveitar as promoções.",
          type: 'blue',
          columnClass: 'col-xs-12',
          buttons: {
            "LOGIN": {
               action: function(){
                window.location.href = "novoLogin.do";
               }
            },
            "RETOMAR CADASTRO": {
              btnClass: 'btn-blue shadow',
               action: function(){
                
               }
            }
          },
          backgroundDismiss: function(){
              return 'FINALIZAR CADASTRO';
          }
        });
      }
    });

    $("#DES_PLACA").keyup(function(){
      if(($(this).parent().hasClass('has-error') || $(this).val() == "" || $(this).val().replace('-','').length < 7)){
        // alert("erro");
        $("#ADD").attr("disabled",true);
      }else{
        // alert("ok");
        $("#ADD").removeAttr("disabled",false);
      }
    });

    $("#ADD").click(function(){
      if(!$("#ADD").attr('disabled')){
        $.ajax({
          method: 'POST',
          url: 'ajxCadVeiculo.php',
          data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", DES_PLACA: $("#DES_PLACA").val(), COD_CLIENTE: "<?=fnEncode($cod_cliente_cad)?>", NUM_CGCECPF: "<?=fnEncode($num_cgcecpf)?>"},
          beforeSend:function(){
            $("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
            $("#ADD").attr("disabled",true);
          },
          success:function(data){
            $("#placasConteudo").html(data);
            $("#DES_PLACA").val('');
            $("#REFRESH_PLACA").val('S');
          }
        });
      }else{
         
      }
    });

    $("body").delegate('input.placa','paste', function(e) {
          $(this).unmask();
      });
    $("body").delegate('input.placa','input', function(e) {
        $('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
    });


  });
  
  if($('.cpfcnpj').val() != undefined){
    mascaraCpfCnpj($('.cpfcnpj'));
  }

  function excPlaca(cod_veiculos){
    $.ajax({
      method: 'POST',
      url: 'ajxCadVeiculo.php?opcao=excluir',
      data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_CLIENTE: "<?=fnEncode($cod_cliente_cad)?>", NUM_CGCECPF: "<?=fnEncode($num_cgcecpf)?>", COD_VEICULOS:cod_veiculos},
      beforeSend:function(){
        $("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
      },
      success:function(data){
        $("#placasConteudo").html(data);
        $.alert({
          title: "AVISO",
          content: "Veículo excluído.",
          columnClass: 'col-xs-12',
          backgroundDismiss: true,
          buttons: {
            "OK": {
              btnClass: 'btn-blue shadow',
               action: function(){
               }
            }
          }
        });
      }
    });
  }
  
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

  function cadPlaca(){
    $.ajax({
      method: 'POST',
      url: 'ajxCadVeiculo.php',
      data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", DES_PLACA: "<?=$des_placa?>", COD_CLIENTE: "<?=fnEncode($cod_cliente_cad)?>", NUM_CGCECPF: "<?=fnEncode($num_cgcecpf)?>"},
      beforeSend:function(){
        $("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
        $("#ADD").attr("disabled",true);
      },
      success:function(data){
        $("#placasConteudo").html(data);
        $("#DES_PLACA").val('');
        $("#REFRESH_PLACA").val('S');
        // alert('CAD');
      }
    });
  }

  var MercoSulMaskBehavior = function (val) {
    var myMask = 'SSS0A00';
    var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
    var normal = /([A-Za-z]{3}[0-9]{2})/;
    var replaced = val.replace(/[^\w]/g, '');
    if (normal.exec(replaced)) {
        myMask = 'SSS-0000';
    } else if (mercosul.exec(replaced)) {
        myMask = 'SSS0A00';
    }
        return myMask;
  },

  mercoSulOptions = {
      onKeyPress: function(val, e, field, options) {
          field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
      }
  };
  
</script>