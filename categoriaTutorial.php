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
$cod_categor = "";
$des_categor = "";
$abv_categor = "";
$des_icone = "";
$des_cor = "";
$num_ordenac = "";
$log_publico = "";
$Arr_COD_MULTEMP = "";
$i = "";
$cod_multemp = "";
$nom_submenus = "";
$cod_usucada = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_canalcom = "";
$cod_tpcom = "";
$abv_canalcom = "";
$des_canalcom = "";
$log_personaliza = "";
$log_preco = "";
$cod_submenus = "";
$formBack = "";
$abaTutorial = "";
$sqlEmpresas = "";
$arrayQueryEmpresas = [];
$qrListaEmpresas = "";
$arrayQuery = [];
$qrBuscaModulos = "";
$publico = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(serialize($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $cod_categor = fnLimpaCampoZero(@$_REQUEST['COD_CATEGOR']);
    $des_categor = fnLimpaCampo(@$_REQUEST['DES_CATEGOR']);
    $abv_categor = fnLimpaCampo(@$_REQUEST['ABV_CATEGOR']);
    $des_icone = fnLimpaCampo(@$_REQUEST['DES_ICONE']);
    $des_cor = fnLimpaCampoHtml(@$_REQUEST['DES_COR']);
    $num_ordenac = @$_POST['NUM_ORDENAC'];
    if (empty(@$_REQUEST['LOG_PUBLICO'])) {
      $log_publico = 'N';
    } else {
      $log_publico = @$_REQUEST['LOG_PUBLICO'];
    }
    if (isset($_POST['COD_MULTEMP'])) {
      $Arr_COD_MULTEMP = @$_POST['COD_MULTEMP'];
      //print_r($Arr_COD_MULTEMP);			 

      for ($i = 0; $i < count($Arr_COD_MULTEMP); $i++) {
        $cod_multemp = $cod_multemp . $Arr_COD_MULTEMP[$i] . ",";
      }

      $cod_multemp = substr($cod_multemp, 0, -1);
    } else {
      $cod_multemp = "0";
    }
    //fnEscreve($nom_submenus);

    $cod_usucada = $_SESSION['SYS_COD_USUARIO'];

    $opcao = @$_REQUEST['opcao'];
    $hHabilitado = @$_REQUEST['hHabilitado'];
    $hashForm = @$_REQUEST['hashForm'];

    if ($opcao != '') {

      // $sql = "CALL SP_ALTERA_COMUNICACAO (
      //  '".$cod_canalcom."',
      //  '".$cod_tpcom."',
      //  '".$abv_canalcom."',				
      //  '".$des_canalcom."',				
      //  '".$des_icone."', 
      //  '".$des_cor."', 
      //  '".$log_personaliza."', 
      //  '".$log_preco."', 
      //  '".$opcao."'    
      // ) ";
      //echo $sql;
      //fnEscreve($cod_submenus);
      // mysqli_query($connAdm->connAdm(),trim($sql));				
      //mensagem de retorno
      switch ($opcao) {
        case 'CAD':
          $sql = "INSERT INTO CATEGORIA_TUTORIAL(																		
											DES_CATEGOR,
											ABV_CATEGOR,
											DES_ICONE, 
											DES_COR,						
											LOG_PUBLICO,
                      COD_MULTEMP,
											COD_USUCADA
											)VALUES(																		
											'$des_categor',
											'$abv_categor',
											'$des_icone', 
											'$des_cor',
											'$log_publico',
											'$cod_multemp',
											$cod_usucada
											)";

          //echo $sql;
          // fnEscreve($sql);								

          mysqli_query($connAdm->connAdm(), trim($sql));

          $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
          break;

        case 'ALT':
          $sql = "UPDATE CATEGORIA_TUTORIAL SET
										DES_CATEGOR = '$des_categor', 
										ABV_CATEGOR = '$abv_categor',
										DES_ICONE = '$des_icone',  
										DES_COR = '$des_cor',
                    LOG_PUBLICO = '$log_publico',
                    COD_MULTEMP = '$cod_multemp'
								WHERE COD_CATEGOR = $cod_categor;";
          // fnEscreve($sql);

          mysqli_query($connAdm->connAdm(), trim($sql));


          $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
          break;

        case 'EXC':
          $sql = "DELETE FROM CATEGORIA_TUTORIAL WHERE COD_CATEGOR = $cod_categor;";
          // fnEscreve($sql);
          mysqli_query($connAdm->connAdm(), trim($sql));
          $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
          break;
          break;
      }
      $msgTipo = 'alert-success';
    }
  }
}

// fnMostraForm();
?>

<div class="push30"></div>

<div class="row">

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <div class="portlet portlet-bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="fal fa-terminal"></i>
          <span class="text-primary"><?php echo $NomePg; ?></span>
        </div>

        <?php
        $formBack = "1019";
        include "atalhosPortlet.php";
        ?>

      </div>
      <div class="portlet-body">

        <?php if ($msgRetorno <> '') { ?>
          <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $msgRetorno; ?>
          </div>
        <?php } ?>
        <div class="push30"></div>
        <?php
        $abaTutorial = 1471;
        include "abasTutorial.php";
        ?>

        <div class="push30"></div>

        <div class="login-form">

          <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

            <fieldset>
              <legend>Categoria</legend>

              <div class="row">
                <div class="col-md-1">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Código</label>
                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGOR" id="COD_CATEGOR" value="">
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Descrição</label>
                    <input type="text" class="form-control input-sm" name="DES_CATEGOR" id="DES_CATEGOR" maxlength="75" required>
                    <div class="help-block with-errors"></div>
                  </div>
                </div>


                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Abreviação</label>
                    <input type="text" class="form-control input-sm" name="ABV_CATEGOR" id="ABV_CATEGOR" maxlength="3" required>
                    <div class="help-block with-errors"></div>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Cor</label>
                    <input type="text" class="form-control input-sm pickColor" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>">
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label for="inputName" class="control-label">Ícone</label><br />
                    <button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome"
                      data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right"
                      data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
                    </button>
                    <input type="hidden" name="DES_ICONE" id="DES_ICONE" value="<?php echo $des_icone ?>">
                  </div>
                </div>

              </div>


              <div class="col-md-1">
                <div class="form-group">
                  <label for="inputName" class="control-label">Público</label>
                  <div class="push5"></div>
                  <label class="switch switch-small">
                    <input type="checkbox" name="LOG_PUBLICO" id="LOG_PUBLICO" class="switch" value="S" checked>
                    <span></span>
                  </label>
                </div>
              </div>


              <div class="col-md-6" id="empresas">
                <div class="form-group">
                  <label for="inputName" class="control-label required">Empresa</label>
                  <select data-placeholder="Selecione uma empresa para acesso" name="COD_MULTEMP[]" id="COD_MULTEMP" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
                    <?php
                    //se sistema marka
                    $sqlEmpresas = ' SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS where cod_empresa <> 1 ';
                    $arrayQueryEmpresas = mysqli_query($connAdm->connAdm(), $sqlEmpresas);
                    while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQueryEmpresas)) {
                      echo "<option value='" . $qrListaEmpresas['COD_EMPRESA'] . "'>" . ucfirst($qrListaEmpresas['NOM_FANTASI']) . "</option>";
                    }
                    ?>
                  </select>


                  <div class="help-block with-errors"></div>
                </div>
              </div>


            </fieldset>

            <div class="push10"></div>
            <hr>
            <div class="form-group text-right col-lg-12">

              <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
              <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
              <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
              <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

            </div>

            <input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
            <input type="hidden" name="opcao" id="opcao" value="">
            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
            <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

          </form>

          <div class="push5"></div>

          <div class="push20"></div>
        </div>
      </div>
    </div>
    <div class="push20"></div>
    <div class="portlet portlet-bordered">
      <div class="portlet-body">
        <div class="login-form">
          <div class="col-lg-12">

            <div class="no-more-tables">

              <form name="formLista">

                <table class="table table-bordered table-striped table-hover table-sortable">
                  <thead>
                    <tr>
                      <th width="40"></th>
                      <th width="40"></th>
                      <th>Código</th>
                      <th>Descrição</th>
                      <th>Abreviação</th>
                      <th class="text-center">Ícone</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $sql = "select * from CATEGORIA_TUTORIAL ORDER BY NUM_ORDENAC";
                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                    $count = 0;
                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                      if ($qrBuscaModulos['LOG_PUBLICO'] == 'S') {
                        $publico = "<span class='fal fa-check text-success'></span>";
                      } else {
                        $publico = "<span class='fal fa-times text-danger'></span>";
                      }


                      $count++;
                      echo "
                          <tr>
                            <td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaModulos['COD_CATEGOR'] . "'></span></td>
                            <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                            <td>" . $qrBuscaModulos['COD_CATEGOR'] . "</td>
                            <td>" . $qrBuscaModulos['DES_CATEGOR'] . "</td>
                            <td>" . $qrBuscaModulos['ABV_CATEGOR'] . "</td>															  		  
                            <td align='center'><span style='color:" . $qrBuscaModulos['DES_COR'] . "' class='" . $qrBuscaModulos['DES_ICONE'] . "' ></td>
                          </tr>
                          <input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaModulos['COD_CATEGOR'] . "'>
                          <input type='hidden' id='ret_DES_CATEGOR_" . $count . "' value='" . $qrBuscaModulos['DES_CATEGOR'] . "'>
                          <input type='hidden' id='ret_ABV_CATEGOR_" . $count . "' value='" . $qrBuscaModulos['ABV_CATEGOR'] . "'>
                          <input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrBuscaModulos['DES_ICONE'] . "'>
                          <input type='hidden' id='ret_DES_COR_" . $count . "' value='" . $qrBuscaModulos['DES_COR'] . "'>
                          <input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaModulos['NUM_ORDENAC'] . "'>
                          <input type='hidden' id='ret_LOG_PUBLICO_" . $count . "' value='" . $qrBuscaModulos['LOG_PUBLICO'] . "'>
                          <input type='hidden' id='ret_COD_MULTEMP_" . $count . "' value='" . $qrBuscaModulos['COD_MULTEMP'] . "'>";
                    }
                    ?>

                  </tbody>
                </table>

              </form>

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
<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">


<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  $(function() {
    $('#LOG_PUBLICO').change(function() {
      if ($('#LOG_PUBLICO').prop("checked")) {
        $('#empresas').fadeOut('fast');
        $('#COD_MULTEMP').val('').trigger('chosen:updated').prop('required', false);
      } else {
        $('#empresas').fadeIn('fast');
        $('#COD_MULTEMP').prop('required', true);
      }
      //$('#formulario').validator('validate');
    });
    $('#LOG_PUBLICO').trigger("change");


    $(".table-sortable tbody").sortable();
    $('.table-sortable tbody').sortable({
      handle: 'span'
    });
    $(".table-sortable tbody").sortable({

      stop: function(event, ui) {

        var Ids = "";
        $('table tr').each(function(index) {
          if (index != 0) {
            Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
          }
        });
        //update ordenação
        //console.log(Ids.substring(0,(Ids.length-1)));

        var arrayOrdem = Ids.substring(0, (Ids.length - 1));
        //alert(arrayOrdem);
        execOrdenacao(arrayOrdem, 15);

        function execOrdenacao(p1, p2) {
          //alert(p1);
          $.ajax({
            type: "GET",
            url: "ajxOrdenacao.php",
            data: {
              ajx1: p1,
              ajx2: p2
            },
            beforeSend: function() {
              //$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
              console.log(data);
            },
            error: function() {
              $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
            }
          });
        }

      }

    });
    $(".table-sortable tbody").disableSelection();
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {



    //arrastar 
    $('.grabbable').on('change', function(e) {
      //console.log(e.icon);
      $("#DES_ICONE").val(e.icon);
    });

    $(".grabbable").click(function() {
      $(this).parent().addClass('selected').siblings().removeClass('selected');
    });

    //color picker
    $('.pickColor').minicolors({
      control: $(this).attr('data-control') || 'hue',
      theme: 'bootstrap'
    });

    //icon picker
    $('.btnSearchIcon').iconpicker({
      cols: 8,
      iconset: 'fontawesome',
      rows: 6,
      searchText: 'Procurar  &iacute;cone'
    });

    //capturando o ícone selecionado no botão
    $('#btniconpicker').on('change', function(e) {
      $('#DES_ICONE').val(e.icon);
      //alert($('#DES_ICONE').val());
    });

  });


  function retornaForm(index) {
    $("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val());
    $("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
    $("#formulario #ABV_CATEGOR").val($("#ret_ABV_CATEGOR_" + index).val());
    $("#btniconpicker").iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
    $("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
    $("#formulario #DES_COR").minicolors('value', $("#ret_DES_COR_" + index).val());
    $("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
    if ($("#ret_LOG_PUBLICO_" + index).val() === 'S') {
      $('#formulario #LOG_PUBLICO').prop('checked', true);
      $('#empresas').fadeOut('fast');
      $('#COD_EMPRESA').val('').trigger('chosen:updated').prop('required', false);
    } else {
      $('#formulario #LOG_PUBLICO').prop('checked', false);
      $('#empresas').fadeIn('fast');
      $('#COD_EMPRESA').prop('required', true);
    }

    if ($("#ret_LOG_PUBLICO_" + index).val() === 'N') {
      var sistemasMst = $("#ret_COD_MULTEMP_" + index).val();
      var sistemasMstArr = sistemasMst.split(',');
      for (var i = 0; i < sistemasMstArr.length; i++) {
        $("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
      }
      $("#formulario #COD_MULTEMP").trigger("chosen:updated");
    } else {
      $("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
    }

    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');
  }
</script>