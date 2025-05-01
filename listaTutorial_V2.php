<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(implode($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
} else {
    $_SESSION['last_request'] = $request;
    // $cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
    // $nom_artigo = fnLimpaCampo($_REQUEST['NOM_ARTIGO']);

    $val_pesquisa = fnLimpaCampo($_REQUEST['INPUT']);

    // fnEscreve($val_pesquisa);

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];
}
}

$isModAdm = true;
if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1514) {
  $isModAdm = false;
}

$cod_empresa = "0";
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
  $cod_empresa = fnDecode($_GET['id']);
}

if ($val_pesquisa != "") {
  $esconde = " ";
} else {
  $esconde = "display: none;";
}

//fnMostraForm();
?>

<?php if ($popUp != "true") { ?>
  <div class="push30"></div> 
<?php } ?>

<div class="row">				

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <div class="portlet portlet-bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="fal fa-terminal"></i>
          <span class="text-primary"><?php echo $NomePg; ?></span>
      </div>
      <?php include "atalhosPortlet.php"; ?>
  </div>


  <div class="portlet-body">

    <?php if ($msgRetorno <> '') { ?>	
      <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo $msgRetorno; ?>
    </div>
<?php } ?>

<div class="push30"></div>

<div class="container-xxl">
    <div class="row">

        <div class="col-md-3 border-end">

            <div class="row">
                <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

                    <div>
                        <div class="input-group activeItem">
                            <div class="input-group-btn search-panel">
                                <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-lg rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                    <span id="search_concept" data-search="#DES_ARTIGO">Buscar Artigo</span>&nbsp;
                                    <span class="far fa-angle-down"></span>                                                             
                                </button>
                            </div>
                            <input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">         
                            <input type="text" id="INPUT" class="form-control form-control-lg remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                            <div class="input-group-btn"id="CLEARDIV" style="<?= $esconde ?>">
                                <button class="btn btn-outline form-control form-control-lg remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span>
                                </button>
                            </div>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-outline form-control form-control-lg rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php
            if ($val_pesquisa != "") {
                ?>

                <div class="item-bd">
                  <div class="">
                      <!-- <center><span class="fal fa-search fa-3x"></span></center> -->

                      <h3>Pesquisa</h3>
                      <div class="push10"></div>
                      <?php
                      $sql2Where = "";

                      if ($_SESSION[SYS_COD_EMPRESA] != 2 && $_SESSION[SYS_COD_EMPRESA] != 3) {
                          $sql2Where = " AND (c.LOG_PUBLICO = 'S' OR (c.LOG_PUBLICO = 'N' AND POSITION(',{$cod_empresa},' IN CONCAT(',,' ,ifnull(c.COD_MULTEMP,0) ,','))>0))";
                      }

                      $sql2 = "SELECT a.COD_ARTIGO, a.NOM_ARTIGO 
                      FROM ARTIGO_TUTORIAL a 
                      INNER JOIN CATEGORIA_TUTORIAL c ON c.COD_CATEGOR = a.COD_CATEGOR
                      WHERE a.NOM_ARTIGO LIKE '%$val_pesquisa%' $sql2Where
                      ORDER BY a.NOM_ARTIGO";

                            // fnEscreve($sql2);
                      $arrayQueryArt = mysqli_query($connAdm->connAdm(), $sql2);

                      while ($qrArt = mysqli_fetch_assoc($arrayQueryArt)) {
                          ?>

                          <a href="action.php?mod=<?php echo fnEncode(1481) ?>&idA=<?php echo fnEncode($qrArt[COD_ARTIGO]) ?>" target="_blank">&rsaquo; <?= $qrArt['NOM_ARTIGO'] ?> </a> 
                          <div class="push5"></div>

                          <?php
                      }
                      ?>
                  </div>
              </div>

              <div class="push50"></div>
              <div class="push20"></div>

              <?php
          }

          if ($_SESSION[SYS_COD_EMPRESA] != 2 && $_SESSION[SYS_COD_EMPRESA] != 3) {
            $sqlWhere = " WHERE (LOG_PUBLICO = 'S' OR (LOG_PUBLICO = 'N' AND POSITION(',{$cod_empresa},' IN CONCAT(',,' ,ifnull(COD_MULTEMP,0) ,','))>0))";
        }

        $sql = "SELECT COD_CATEGOR, DES_CATEGOR, DES_COR, DES_ICONE, LOG_PUBLICO FROM CATEGORIA_TUTORIAL $sqlWhere
        ORDER BY NUM_ORDENAC";
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

        echo '<ul class="nav flex-column" id="categorias-lista">';

        while ($qrCat = mysqli_fetch_assoc($arrayQuery)) {

         $tituloCategoria = $qrCat['DES_CATEGOR'];
         $categoriaId = 'categoria-' . $qrCat['COD_CATEGOR'];
         ?>


         <li class="nav-item" style="display: flex; align-items: center;" data-categoria="<?= $categoriaId ?>">
            <span class="<?= $qrCat['DES_ICONE'] ?>" style="color:<?= $qrCat['DES_COR'] ?>; font-size: 20px; margin-right: 10px;"></span>
            <h4><?= $tituloCategoria ?></h4>
        </li>
        <?php
        $sql2 = "SELECT COD_ARTIGO, NOM_ARTIGO FROM ARTIGO_TUTORIAL WHERE COD_CATEGOR = $qrCat[COD_CATEGOR] ORDER BY NOM_ARTIGO";

                              // fnEscreve($sql2);
        $arrayQueryArt = mysqli_query($connAdm->connAdm(), $sql2);

        echo '<ul class="sub-list" id="' . $categoriaId . '" style="list-style-type: none;">';
        while ($qrArt = mysqli_fetch_assoc($arrayQueryArt)) {
            ?>

            <li>
                <a href="action.php?mod=<?php echo fnEncode(1481) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idA=<?php echo fnEncode($qrArt[COD_ARTIGO]) ?>" target="_blank">&rsaquo; <?= $qrArt['NOM_ARTIGO'] ?> </a>
            </li> 
            <div class="push5"></div>

            <?php
        }

        echo '</ul>';
    }

    echo '</ul>';
    ?>

</div>

<div class="col-md-9">

</div>
</div>

</div>

<div class="push50"></div>		

<div class="tab-content">
  <!-- aba databases -->
  <div id="databases" class="tab-pane fade in active">

    <div class="push30"></div> 

    <div class="row">

    </div>

</div>
<div class="push50"></div>
<div style="text-align:center;">
    <hr/>
    <div class="push20"></div>
    <a href="https://adm.bunker.mk/action.php?mod=khmEt0XcJh8%C2%A2" class="btn btn-info btn-lg" target="_blank">Não encontrou o que procura? Clique aqui.</a>
</div>

<div class="push30"></div>

</div>

</div>
</div>
<!-- fim Portlet -->
</div>

</div>	

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>		

<div class="push30"></div> 

<script type="text/javascript">

    $(document).ready(function(){
    // Oculta todas as sub-listas
        $('.sub-list').hide();

        var primeiraCategoria = $('#categorias-lista li:first-child');
        var primeiraCategoriaId = primeiraCategoria.data('categoria');
        $('#' + primeiraCategoriaId).show();

    // Adiciona um evento de clique às categorias
        $('#categorias-lista li').click(function(){
            var categoriaId = $(this).data('categoria');
            var subLista = $('#' + categoriaId);

        // Alterna entre ocultar e mostrar a sub-lista
            subLista.toggle();
        });
    });

  //Barra de pesquisa essentials ------------------------------------------------------
    $(document).ready(function (e) {
        var value = $('#INPUT').val().toLowerCase().trim();
        if (value) {
          $('#CLEARDIV').show();
      } else {
          $('#CLEARDIV').hide();
      }
      $('.search-panel .dropdown-menu').find('a').click(function (e) {
          e.preventDefault();
          var param = $(this).attr("href").replace("#", "");
          var concept = $(this).text();
          $('.search-panel span#search_concept').text(concept);
          $('.input-group #VAL_PESQUISA').val(param);
          $('#INPUT').focus();
      });

      $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function () {
          $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
      });

      $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function () {
          $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
      });

      $('#CLEAR').click(function () {
          $('#INPUT').val('');
          $('#INPUT').focus();
          $('#CLEARDIV').hide();
          if ("<?= $filtro ?>" != "") {
            location.reload();
        } else {
            var value = $('#INPUT').val().toLowerCase().trim();
            if (value) {
              $('#CLEARDIV').show();
          } else {
              $('#CLEARDIV').hide();
          }
          $(".buscavel .item-bd").each(function (index) {
              if (!index)
                return;
            $(this).find(".referencia-busca").each(function () {
                var id = $(this).text().toLowerCase().trim();
                var sem_registro = (id.indexOf(value) == -1);
                $(this).closest('.item-bd').toggle(!sem_registro);
                return sem_registro;
            });
        });
      }
  });

    // $('#SEARCH').click(function(){
    // 	$('#formulario').submit();
    // });


  });

    function buscaRegistro(el) {
        var filtro = $('#search_concept').text().toLowerCase();

        if (filtro == "sem filtro") {
          var value = $(el).val().toLowerCase().trim();
          if (value) {
            $('#CLEARDIV').show();
        } else {
            $('#CLEARDIV').hide();
        }
        $(".buscavel .item-bd").each(function (index) {
            if (!index)
              return;
          $(this).find(".referencia-busca").each(function () {
              var id = $(this).text().toLowerCase().trim();
              var sem_registro = (id.indexOf(value) == -1);
              $(this).closest('.item-bd').toggle(!sem_registro);
              return sem_registro;
          });
      });
    }
}

  //-----------------------------------------------------------------------------------		

function retornaForm(index) {
    // $("#formulario #COD_ARTIGO").val($("#ret_COD_ARTIGO_"+index).val());
    // $("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_"+index).val());
    // $("#formulario #NOM_ARTIGO").val($("#ret_NOM_ARTIGO_"+index).val());            
    // $("#formulario #DES_MODULO").val($("#ret_DES_MODULO_"+index).val());			
    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');
}

</script>	