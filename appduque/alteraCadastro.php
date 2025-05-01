<?php 
include './_system/_functionsMain.php'; 
include_once '../totem/funWS/atualizacadastro.php';

  if( $_SERVER['REQUEST_METHOD']=='POST' )
  {

  $cpf = fnLimpaCampoZero(fnDecode($_REQUEST['CPF']));
  $cod_cliente_cad = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CLIENTE']));
  $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
  $cod_sexopes = fnLimpaCampoZero($_REQUEST['COD_SEXOPES']);
  // $radio = fnLimpaCampo($_REQUEST['RADIO']);
  $nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
  $des_senhaus = fnDecode($_REQUEST['DES_SENHAUS']);
  $dat_nascime = fnLimpaCampo($_REQUEST['DAT_NASCIME']);
  $num_celular = fnLimpaCampo($_REQUEST['NUM_CELULAR']);
  $num_cepozof = fnLimpaCampo($_REQUEST['NUM_CEPOZOF']);
  $des_emailus = fnLimpaCampo($_REQUEST['DES_EMAILUS']);
  $cod_profiss = fnLimpaCampoZero($_REQUEST['COD_PROFISS']);

  // if($radio == "MASCULINO"){
  //   $cod_sexopes = 1;
  // }else{
  //   $cod_sexopes = 2;
  // }

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
            'email'=>$des_emailus,
            'telefone'=>$num_celular,
            'cpf'=>$cpf,
            'cartao'=>$cpf,
            'senha'=>$des_senhaus,
            'sexo'=>$cod_sexopes,
            'dt_nascimento'=>$dat_nascime,
            // 'profissao'=>$cod_profiss,
            'cep'=>$num_cepozof
          );
           

  $atualiza=atualizacadastro($dadosatualiza, $dadoslogin);

  // fnEscreve($atualiza);

  if($atualiza == "Registro inserido!" || $atualiza == "Cadastro Atualizado !"){

  }   
    
    header("Location: https://adm.bunker.mk/appduque/infoCadastro.do?secur=".fnEncode($cpf)."&idp=".fnEncode(6));
    // exit();
                
  }
  
    //Rotina de logoff
  if($_GET['logoff']=='1'){
        //session_destroy();
        //session_unset();
        unset($_SESSION["login"]);
       
        
      // header("Location:https://www.rededuque.com.br/app/");
       header("Location:https://adm.bunker.mk/appduque/"); 
        
  }

if(isset($_GET['idc'])){
  $cod_cliente_cad = fnLimpaCampoZero(fnDecode($_GET['idc']));
  $secur = fnLimpaCampo(fnDecode($_GET['secur']));

  $sql = "SELECT * FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente_cad AND COD_EMPRESA = 19";
  $qrCli = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql));
}

// fnEscreve($cod_cliente_cad);

// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";

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
		

      <form data-toggle="validator" role="form2" method="post" id="formulario" action="alteraCadastro.do?<?=$_SERVER[QUERY_STRING]?>">

        <div class="container">

        <div class="push50"></div>

        <div class="row">
          <div class="col-xs-12">
            <h4 style="font-weight: 900!important;">DADOS DO MOTORISTA</h4>
          </div>
        </div>

        <div class="push10"></div>

        <div class="row">   

          <div class="col-xs-12 text-center">
            <div class="form-group">
              <input type="text" id="NOM_CLIENTE" name="NOM_CLIENTE" class="form-control input-hg" placeholder="Seu nome" maxlength="60" value="<?php echo $qrCli[NOM_CLIENTE];?>" autocomplete="off" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <!-- <div class="row">

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

          <script type="text/javascript">if("<?=$qrCli[COD_SEXOPES]?>" == 2){$("#FEMININO").prop("checked",true);}else{$("#MASCULINO").prop("checked",true);}</script>

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="text" id="DAT_NASCIME" name="DAT_NASCIME" class="form-control input-hg text-center data" placeholder="__/__/_____" maxlength="10" value="<?php echo $qrCli[DAT_NASCIME];?>" autocomplete="off" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div> -->

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

        <input type="hidden" name="DAT_NASCIME" id="DAT_NASCIME" value="<?=$nascimento?>">
        <input type="hidden" name="COD_SEXOPES" id="COD_SEXOPES" value="<?=$codSexo?>">

        <div class="row">

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="tel" id="NUM_CELULAR" name="NUM_CELULAR" class="form-control input-hg text-center sp_celphones" placeholder="(xx) xxxxx-xxxx" maxlength="60" value="<?php echo $qrCli[NUM_CELULAR];?>" autocomplete="off">
              <div class="help-block with-errors"></div>
            </div>
          </div>   

          <div class="col-xs-6 text-center">
            <div class="form-group">
              <input type="tel" id="NUM_CEPOZOF" name="NUM_CEPOZOF" class="form-control input-hg text-center cep" placeholder="Insira o CEP" maxlength="10" value="<?php echo $qrCli[NUM_CEPOZOF];?>" autocomplete="off">
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="row">   

          <div class="col-xs-12 text-center">
            <div class="form-group">
              <!-- pattern=".*@\w{2,}\.\w{2,}" -->
              <input  type="email" id="DES_EMAILUS" name="DES_EMAILUS" class="form-control input-hg" placeholder="Seu email" maxlength="60" value="<?php echo $qrCli[DES_EMAILUS];?>" autocomplete="off" required>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="row">   

          <div class="col-xs-12 text-center">
            <div class="form-group">
              <input type="text" id="DES_EMAILUS_CONF" name="DES_EMAILUS_CONF" class="form-control input-hg" placeholder="Confirme o email" maxlength="60" value="<?php echo $qrCli[DES_EMAILUS];?>" required data-match="#DES_EMAILUS" data-match-error="emails diferentes">
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <!-- <div class="row">

          <div class="col-xs-6">
            <div class="form-group">
              <label for="inputName" class="control-label">É caminhoneiro?</label>
                <select data-placeholder="Selecione uma opção" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect" required>
                  <option value=""></option>          
                  <option value="108">Sim</option>          
                  <option value="93">Não</option>          
                </select>
                <script>
                  $("#formulario #COD_PROFISS").val("<?php echo $qrCli[COD_PROFISS];?>").trigger("chosen:updated");
                </script>
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div> -->

        <div class="push50"></div>

        <!-- <input type="hidden" name="DADOS_LOGIN" id="DADOS_LOGIN" value="<?=$dadoslogin?>"> -->
        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
        <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=fnEncode($cod_cliente_cad)?>">
        <input type="hidden" name="DES_SENHAUS" id="DES_SENHAUS" value="<?=$qrCli[DES_SENHAUS]?>">
        <!-- <input type="hidden" name="COD_SEXOPES" id="COD_SEXOPES" value="<?=$qrCli[COD_SEXOPES]?>"> -->
        <input type="hidden" name="CPF" id="CPF" value="<?=fnEncode($secur)?>">
        <div class="row">       
          
          <div class="col-xs-12">
            <div class="col-xs-6 text-center">
              <a href="infoCadastro.do?secur=<?=fnEncode($secur)?>&idp=<?=fnEncode(6)?>" class="btn btn-default btn-block">Voltar</a>
            </div>
            <div class="col-xs-6 text-center">
              <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block getBtn shadow">Alterar</button>
            </div>
          </div>

          <div class="push20"></div>
          <div class="col-md-12 text-center">
            <a href="javascript:void(0)" name="EXC" id="EXC" tabindex="5" onclick='ajxDescadastra("<?=fnEncode($cod_cliente_cad)?>")' style="font-size: 16px;">Descadastrar-se</a>
          </div>
              
          
        </div><!-- /row -->
            

        </div> <!-- /container -->

    </form>	

		<?php include 'jsLib.php';?>
    <script src="https://bunker.mk/js/jquery-confirm.min.js"></script>
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


    $(".toggle-password").click(function() {

      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });

  });
  
  if($('.cpfcnpj').val() != undefined){
    mascaraCpfCnpj($('.cpfcnpj'));
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

  function ajxDescadastra(cod_cliente){

    $.alert({
          title: "Confirmação",
          content: "Deseja excluir seus dados de forma <b>definitiva</b>?",
          type: 'red',
          buttons: {
            "EXCLUIR": {
               btnClass: 'btn-danger',
               action: function(){
                
                    $.alert({
                      title: "Aviso!",
                      content: "<b>Todos</b> os dados serão excluídos <b>permanentemente</b>. Deseja <b>realmente</b> continuar?",
                      type: 'red',
                      buttons: {
                        "EXCLUIR PERMANENTEMENTE": {
                           btnClass: 'btn-danger',
                           action: function(){
                              $.ajax({
                  type: "POST",
                  url: "ajxDescadastro.do?id=<?php echo fnEncode($cod_empresa); ?>",
                  data: { COD_CLIENTE: cod_cliente },
                  beforeSend:function(){
                    $("#blocker").show();
                  },
                  success:function(data){ 
                    window.location.href = "descadastro.do";        
                  },
                  error:function(){
                      console.log('Erro');
                  }
                });
                           }
                        },
                        "CANCELAR": {
                          btnClass: 'btn-default',
                           action: function(){
                            
                           }
                        }
                      },
                      backgroundDismiss: function(){
                          return 'CANCELAR';
                      }
                    });

               }
            },
            "CANCELAR": {
              btnClass: 'btn-default',
               action: function(){
                
               }
            }
          },
          backgroundDismiss: function(){
              return 'CANCELAR';
          }
        });

  }
  
</script>