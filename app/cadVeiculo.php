<?php 
        include_once 'header.php'; 
        $tituloPagina = "Veículos";
        include_once "navegacao.php";
        include_once "controleSession.php";
        // include '_system/Lista_oferta.php';

        // $cpf = $_SESSION['usuario'];


        // $arrayOfertas=fnofertas($cpf,$dadoslogin);

        // if($_SESSION["usuario"] == 1734200014){
        //     $log_bannerlista = 'S';
        // }

    ?>

<style>
    .shadow{
       -webkit-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        -moz-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        /*width: 100%;*/
        border-radius: 5px;
    }

    .carousel{
        border-radius: 10px 10px 10px 10px;
        overflow: hidden;
    }
   .carousel-caption{
         color: <?=$cor_textos?>;
        /*background-color: rgba(0,0,0,0.2);*/
        border-radius: 30px 30px 30px 30px;
        padding-top: 5px;
        padding-bottom: 5px;
        bottom: 0px;
        background-color: rgba(255,255,255,0.7);
    }
    .contorno{
      /*-webkit-text-fill-color: white;  Will override color (regardless of order) */
      /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
      text-shadow: 1px 1px black;
    }

    .carousel-indicators{
        z-index: 0;
    }

    .carousel-control.left, .carousel-control.right {
        background-image: none
    }

    .img-lista{
        height: 85px; 
        width: 85px;
        border-radius: 50px; 
    }

    .center{
        margin: auto;
        position:absolute;
        right: 0;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
    }


</style>    
        
        <div class="container">

        <div class="push50"></div>

        <div class="row">
          <div class="col-xs-10">
            <h4 style="font-weight: 900!important;">VEÍCULOS CADASTRADOS</h4>
          </div>
          <!-- <div class="col-xs-2"><span class="fa fa-pencil fa-2x"></span></div> -->
          <div class="col-md-12">
            <hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
          </div>
        </div>

        <div class="push30"></div>

        <div class="row">   

          <div class="col-xs-5 col-xs-offset-1">
            <div class="form-group">
              <input type="text" id="DES_PLACA" name="DES_PLACA" class="form-control input-hg text-center placa" placeholder="Sua placa" data-minlength="7" data-minlength-error="Formato inválido">
              <div class="help-block with-errors"></div>
            </div>
          </div>

          <div class="col-xs-5">
            <a href='javascript:void(0)' name="ADD" id="ADD" class="btn btn-info btn-sm shadow" tabindex="5" disabled><span class="fa fa-plus"></span>&nbsp; Adicionar</a>
          </div>

        </div>

        <div class="push10"></div>

        <div class="row" id="placasConteudo">

          <?php

            $sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $usuario AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
            // echo($sql);
            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

            while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

            ?>

              <div class="col-xs-12" style="font-size: 20px!important;">
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

    </div> <!-- /container -->

    <?php include 'footer.php'; ?>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>
    <link href="libs/jquery-confirm.min.css" rel="stylesheet"/>
    <script src="libs/jquery-confirm.min.js"></script>

    <script>

$(function(){

    // CADASTRA PLACA INSERIDA NO FORM DO CLIENTE E RETORNA SE É DUPLICADA OU NÃO
    //cadPlaca();

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
          url: 'ajxCadVeiculo.php?t=<?=$rand?>',
          data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", DES_PLACA: $("#DES_PLACA").val(), NUM_CGCECPF: "<?=fnEncode($usuario)?>"},
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
      url: 'ajxCadVeiculo.php?opcao=excluir&t=<?=$rand?>',
      data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", NUM_CGCECPF: "<?=fnEncode($usuario)?>", COD_VEICULOS:cod_veiculos},
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
      url: 'ajxCadVeiculo.php?t=<?=$rand?>',
      data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", DES_PLACA: "<?=$des_placa?>", NUM_CGCECPF: "<?=fnEncode($usuario)?>"},
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