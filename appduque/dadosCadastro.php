<?php 
include './_system/_functionsMain.php';	
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';
include_once '../totem/funWS/saldo.php';
// echo fnDebug('true');

// $arrayCampos = explode(";", $key);


$hashLocal = mt_rand(); 

if( $_SERVER['REQUEST_METHOD']=='POST' )
{

  $request = md5( implode( $_POST ) );

  $cpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['cpf']));
  $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);

  $sql = "SELECT LOG_USUARIO, DES_SENHAUS FROM USUARIOS
        WHERE COD_EMPRESA = $cod_empresa AND 
        COD_TPUSUARIO = 10  AND 
        LOG_ESTATUS = 'S' LIMIT 1";

  $qrUs = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

  $login = $qrUs['LOG_USUARIO'];
  $senha = fnDecode($qrUs['DES_SENHAUS']);

  $dadoslogin = array(
   '0'=>$login,
   '1'=>$senha,
   '2'=>955,
   '3'=>'maquina',
   '4'=>$cod_empresa
  );

  // echo "<pre>";
  // print_r($dadoslogin);
  // echo "</pre>";
  
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

if(strlen($cpf)=='11')
{    
  //fnEscreve('11');
    $buscaconsumidor = fnconsulta($cpf, $dadoslogin);
    
}else{
  //fnEscreve('else');
    $buscaconsumidor = fnconsultacnpf($cpf, $dadoslogin); 
    
}


               
// if($buscaconsumidor['localizacaocliente']=='13')
//     {
//       $cpf= $cpf; 
//     }else{
//     if($buscaconsumidor['cpf']=='00000000000')
//     {   
//     $cpf=$cpf;  
//     }else
//     {
//       $cpf=$buscaconsumidor['cpf'];
//     }    
// }
  // fnEscreve($cpf);
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
		

      <form data-toggle="validator" role="form2" method="post" id="formulario" action="cadVeiculo.do">

        <div class="container">

        <div class="push15"></div>

        <div class="row">
          <div class="col-xs-12">
            <h4 style="font-weight: 900!important;">DADOS DO MOTORISTA</h4>
          </div>
        </div>

        <div class="push10"></div>

        <div class="row">   

          <div class="col-xs-12 text-center">
            <div class="form-group">
              <input type="text" id="NOM_CLIENTE" name="NOM_CLIENTE" class="form-control input-hg" placeholder="Seu nome" maxlength="60" value="<?php echo $buscaconsumidor['nome'];?>" autocomplete="off" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <?php

          $nascimento = "01/01/1980";
          $codSexo = "3";

          if($buscaconsumidor['datanascimento'] != ""){
            $nascimento= $buscaconsumidor['datanascimento'];
          }

          if($buscaconsumidor['sexo'] != ""){
            $codSexo = $buscaconsumidor['sexo'];
          }

        ?>

        <div class="row">

          <div class="col-xs-6 text-center">
            <div class="col-xs-6">
              <div class="push15"></div>
              <input type="radio" id="MASCULINO" name="RADIO" value="MASCULINO" required>
              <label for="MASCULINO">M</label>
            </div>

            <div class="col-xs-6">
              <div class="push15"></div>
              <input type="radio" id="FEMININO" name="RADIO" value="FEMININO">
              <label for="FEMININO">F</label>
            </div>
          </div>

          <script type="text/javascript">if("<?=$buscaconsumidor[sexo]?>" == 2){$("#FEMININO").prop("checked",true);}else{$("#MASCULINO").prop("checked",true);}</script>   

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="tel" id="DAT_NASCIME" name="DAT_NASCIME" class="form-control input-hg text-center data" placeholder="Dt. Nascimento" minlength="10" maxlength="10" data-error="A data precisa ter 2 dígitos para dia, 2 para mês e 4 para ano" value="<?php echo $buscaconsumidor['datanascimento'];?>" autocomplete="off" required>
              <div class="help-block with-errors"><small>Formato: DD/MM/AAAA</small></div>
            </div>
          </div>

        </div>

        <!-- <input type="hidden" name="DAT_NASCIME" id="DAT_NASCIME" value="<?=$nascimento?>">
        <input type="hidden" name="COD_SEXOPES" id="COD_SEXOPES" value="<?=$codSexo?>"> -->

        <div class="row">

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="tel" id="NUM_CELULAR" name="NUM_CELULAR" class="form-control input-hg text-center sp_celphones" placeholder="(xx) xxxxx-xxxx" maxlength="60" value="<?php echo $buscaconsumidor['telcelular'];?>" autocomplete="off">
              <div class="help-block with-errors"></div>
            </div>
          </div>   

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="tel" id="NUM_CEPOZOF" name="NUM_CEPOZOF" class="form-control input-hg text-center cep" placeholder="Insira o CEP" maxlength="10" value="<?php echo $buscaconsumidor['cep'];?>" autocomplete="off">
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="row">   

          <div class="col-xs-12 text-center">
            <div class="form-group">
              <input  type="email" id="DES_EMAILUS" name="DES_EMAILUS" class="form-control input-hg" placeholder="Seu email" maxlength="60" value="<?php echo $buscaconsumidor['email'];?>" autocomplete="off" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="row">   

          <div class="col-xs-12 text-center">
            <div class="form-group">
              <input type="email" id="DES_EMAILUS_CONF" name="DES_EMAILUS_CONF" class="form-control input-hg" placeholder="Confirme o email" maxlength="60" required data-match="#DES_EMAILUS" data-match-error="emails diferentes">
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="row">

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="password" id="DES_SENHAUS" name="DES_SENHAUS" class="form-control input-hg text-center" placeholder="Insira sua senha" data-minlength="6" data-minlength-error="Senha muito curta" maxlength="6" autocomplete="new-password" required>
              <span toggle="#DES_SENHAUS" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              <div class="help-block with-errors"><b>Senha de 6 dígitos</b></div>
            </div>
          </div>   

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="password" id="DES_SENHAUS_CONF" name="DES_SENHAUS_CONF" class="form-control input-hg text-center" placeholder="Confirme a senha" maxlength="6" data-match="#DES_SENHAUS" data-match-error="Senhas diferentes" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="row">

          <!-- <div class="col-xs-6">
            <div class="form-group">
              <label for="inputName" class="control-label">É caminhoneiro?</label>
                <select data-placeholder="Selecione uma opção" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect" required>
                  <option value=""></option>          
                  <option value="108">Sim</option>          
                  <option value="93">Não</option>          
                </select>
                <script>
                  $("#formulario #COD_PROFISS").on('change', function(){
                    if($(this).val() == 108){
                      $("#formulario #COD_UNIVEND").val("97385").trigger("chosen:updated");
                    }
                  });
                  // $("#formulario #COD_PROFISS").val("<?php echo $cod_profiss; ?>").trigger("chosen:updated");
                </script>
              <div class="help-block with-errors"></div>
            </div>
          </div> -->

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="text" id="DES_PLACA" name="DES_PLACA" class="form-control input-hg text-center placa" placeholder="Sua placa" data-minlength="7" data-minlength-error="Formato inválido" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>
		
        <div class="row">

          <div class="col-xs-12">
            <div class="form-group">
              <label for="inputName" class="control-label">Posto de Preferência</label>
                <select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect">
                  <option value=""></option>          
                  <option value="97385">DUQUE DUTRA (estrada)</option>          
                  <?php                                   
                    $sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND != 955 AND (LOG_ESTATUS = 'S' OR DAT_EXCLUSA IS NULL) ORDER BY NOM_FANTASI ";
                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
                  
                    while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery))
                      {                           
                      echo"
                          <option value='".$qrListaUnidade['COD_UNIVEND']."'>".$qrListaUnidade['NOM_FANTASI']."</option> 
                        "; 
                        }                     
                  ?>  
                </select>
                <!-- <script>$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated"); </script> -->
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="push30"></div>

        <!-- <input type="hidden" name="DADOS_LOGIN" id="DADOS_LOGIN" value="<?=$dadoslogin?>"> -->
        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
        <input type="hidden" name="cpf" id="cpf" value="<?=$cpf?>">

        <div class="row">       
          
          <div class="col-xs-10 col-xs-offset-1">
            <button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-block getBtn shadow" tabindex="5">Continuar</button>
          </div>
              
          
        </div><!-- /row -->
            

        </div> <!-- /container -->

    </form>	

		<?php include 'jsLib.php';?>
    </body>
</html>
<script>

  $(function(){

    $(".chosen-select-deselect").chosen();

    $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
    $('#formulario').validator();

    var SPMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
      onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
      }
    };
    
    $('.sp_celphones').mask(SPMaskBehavior, spOptions);
    $('.data').mask('00/00/0000');
    $('.cep').mask('99999-999');

    $('#DES_EMAILUS,#DES_EMAILUS_CONF').on('input',function() {
        str = $(this).val();
        str = str.replace(/\s/g, '');
        $(this).val(str);
    });

    $(".toggle-password").click(function() {

      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
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

  // function nospaces(t){
  //   if(t.value.match(/\s/g)){
  //     t.value=t.value.replace(/\s/g,'');
  //   }
  // }
  
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