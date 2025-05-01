<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_agrupado = "";
$cod_externo = "";
$des_objeto = "";
$des_secao = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";
$formBack = "";
$abaEmpresa = "";
$qrBuscaProdutos = "";
$mostraDown = "";


$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(serialize($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $cod_agrupado = fnLimpaCampoZero(@$_REQUEST['COD_AGRUPADO']);
    $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
    $cod_externo = fnLimpaCampo(@$_REQUEST['COD_EXTERNO']);
    $des_objeto = fnLimpaCampo(@$_REQUEST['DES_OBJETO']);
    $des_secao = fnLimpaCampo(@$_REQUEST['DES_SECAO']);

    $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
    $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $MODULO = @$_GET['mod'];
    $COD_MODULO = fndecode(@$_GET['mod']);

    $opcao = @$_REQUEST['opcao'];
    $hHabilitado = @$_REQUEST['hHabilitado'];
    $hashForm = @$_REQUEST['hashForm'];

    if ($opcao != '') {
      if ($opcao == 'CAD') {
        $sqlInsert = "INSERT INTO produto_agrupador(
                          COD_EMPRESA, 
                          COD_EXTERNO, 
                          DES_OBJETO, 
                          DES_SECAO
                          ) 
                          VALUES (
                          '$cod_empresa', 
                          '$cod_externo', 
                          '$des_objeto', 
                          '$des_secao'
                          );";

        $arrayInsert = mysqli_query($conn, $sqlInsert);

        if (!$arrayInsert) {

          $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
        }
      }

      if ($opcao == 'ALT') {
        $sqlUpdate = "UPDATE produto_agrupador SET 
                    COD_EXTERNO = '$cod_externo', 
                    DES_OBJETO = '$des_objeto', 
                    DES_SECAO = '$des_secao'								
                    WHERE COD_AGRUPADO = $cod_agrupado and COD_EMPRESA = $cod_empresa;";
        // fnEscreve($sql);
        $arrayUpdate = mysqli_query($conn, $sqlUpdate);

        if (!$arrayUpdate) {

          $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
        }
      }


      //mensagem de retorno
      switch ($opcao) {
        case 'CAD':
          if ($cod_erro == 0 || $cod_erro ==  "") {
            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
          } else {
            $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
          }
          break;
        case 'ALT':
          if ($cod_erro == 0 || $cod_erro ==  "") {
            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
          } else {
            $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
          }
          break;
        case 'EXC':
          if ($cod_erro == 0 || $cod_erro ==  "") {
            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
          } else {
            $msgRetorno = "Não foi possível excluir o registro : $cod_erro";
          }
          break;
      }
      if ($cod_erro == 0 || $cod_erro == "") {
        $msgTipo = 'alert-success';
      } else {
        $msgTipo = 'alert-danger';
      }
    }
  }
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
  //busca dados da empresa
  $cod_empresa = fnDecode(@$_GET['id']);
  $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
  //fnEscreve($sql);
  $arrayQuery = mysqli_query($adm, $sql);
  $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

  if (isset($arrayQuery)) {
    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
  }
} else {
  $cod_empresa = 0;
  //fnEscreve('entrou else');
}

//fnEscreve($cod_empresa);
//fnEscreve($nom_empresa);
//fnMostraForm();
?>

<div class="push30"></div>

<div class="row">

  <div class="col-md-12 margin-bottom-30">
    <!-- Portlet -->
    <?php if ($popUp != "true") { ?>
      <div class="portlet portlet-bordered">
      <?php } else { ?>
        <div class="portlet" style="padding: 0 20px 20px 20px;">
        <?php } ?>

        <?php if ($popUp != "true") { ?>
          <div class="portlet-title">
            <div class="caption">
              <i class="fal fa-terminal"></i>
              <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
            </div>
            <?php
            $formBack = 1019;
            include "atalhosPortlet.php";
            ?>

          </div>

        <?php } ?>

        <div class="portlet-body">

          <?php if ($msgRetorno <> '') { ?>
            <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $msgRetorno; ?>
            </div>
          <?php } ?>

          <?php
          if ($popUp != "true") {
            //menu superior - empresas
            $abaEmpresa = 1465;
            switch ($_SESSION["SYS_COD_SISTEMA"]) {
              case 14: //rede duque
                include "abasEmpresaDuque.php";
                break;
              default;
                if (fnDecode(@$_GET['mod']) != 1194) {
                  include "abasProdutosConfig.php";
                }
                break;
            }
          ?>
            <div class="push30"></div>
          <?php
          }
          ?>
          <div class="login-form">

            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

              <fieldset>
                <legend>Dados Gerais</legend>
                <div class="row">

                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Código</label>
                      <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_AGRUPADO" id="COD_AGRUPADO" value="">
                      <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Código Externo</label>
                      <input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" value="" maxlength="20" required>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Objeto</label>
                      <input type="text" class="form-control input-sm" name="DES_OBJETO" id="DES_OBJETO" maxlength="100" required>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Seção</label>
                      <input type="text" class="form-control input-sm" name="DES_SECAO" id="DES_SECAO" maxlength="100" value="">
                    </div>
                  </div>

                </div>

              </fieldset>

              <div class="push10"></div>
              <hr>
              <div class="form-group text-right col-lg-12">

                <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                <!--
                <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
                -->
              </div>

              <input type="hidden" name="opcao" id="opcao" value="">
              <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
              <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

              <div class="push5"></div>

            </form>

            <div class="push50"></div>

            <div class="col-lg-12">

              <div class="no-more-tables">



                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="50"></th>
                      <th>Código</th>
                      <th>Cód. Externo </th>
                      <th>Objeto</th>
                      <th>Seção</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $sql = "select COD_AGRUPADO, COD_EXTERNO, DES_OBJETO, DES_SECAO from produto_agrupador where COD_EMPRESA = $cod_empresa order by COD_EXTERNO, DES_OBJETO, DES_SECAO ";
                    $arrayQuery = mysqli_query($conn, $sql);

                    $count = 0;
                    while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
                      $count++;

                      if ($popUp == "true") {
                        $mostraDown = "<a href='javascript: downForm($count)' style='margin-left: 10px;'><i class='fal fa-arrow-circle-down' aria-hidden='true'></i></a>";
                      } else {
                        $mostraDown = "";
                      }

                      echo "
                                      <tr>
                                        <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>&nbsp;
                                        $mostraDown
                                        </td>
                                        <td>" . $qrBuscaProdutos['COD_AGRUPADO'] . "</td>
                                        <td>" . $qrBuscaProdutos['COD_EXTERNO'] . "</td>
                                        <td>" . $qrBuscaProdutos['DES_OBJETO'] . "</td>
                                        <td>" . $qrBuscaProdutos['DES_SECAO'] . "</td>
                                      </tr>
                                      <input type='hidden' id='ret_COD_AGRUPADO_" . $count . "' value='" . $qrBuscaProdutos['COD_AGRUPADO'] . "'>
                                      <input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaProdutos['COD_EXTERNO'] . "'>
                                      <input type='hidden' id='ret_DES_OBJETO_" . $count . "' value='" . $qrBuscaProdutos['DES_OBJETO'] . "'>
                                      <input type='hidden' id='ret_DES_SECAO_" . $count . "' value='" . $qrBuscaProdutos['DES_SECAO'] . "'>
                                      ";
                    }
                    ?>

                  </tbody>
                </table>

              </div>

            </div>

            <div class="push"></div>

          </div>

        </div>
        </div>
        <!-- fim Portlet -->
      </div>

  </div>

  <div class="push20"></div>

  <link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css" />

  <script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
  <script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      //capturando o ícone selecionado no botão
      $('#btniconpicker').on('change', function(e) {
        $('#DES_ICONE').val(e.icon);
        //alert($('#DES_ICONE').val());
      });
    });

    function retornaForm(index) {
      $("#formulario #COD_AGRUPADO").val($("#ret_COD_AGRUPADO_" + index).val());
      $("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
      $("#formulario #DES_OBJETO").val($("#ret_DES_OBJETO_" + index).val());
      $("#formulario #DES_SECAO").val($("#ret_DES_SECAO_" + index).val());
      $('#formulario').validator('validate');
      $("#formulario #hHabilitado").val('S');
    }

    function downForm(index) {

      try {
        parent.$('#COD_AGRUPADO').val($("#ret_COD_AGRUPADO_" + index).val());
      } catch (err) {}
      $(this).removeData('bs.modal');
      console.log('entrou' + index);
      parent.$('#popModalAux').modal('hide');

    }
  </script>