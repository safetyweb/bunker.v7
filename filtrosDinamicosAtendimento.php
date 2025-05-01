<?php
//echo "<h5>_".$opcao."</h5>";
$itens_por_pagina = 50;
$pagina = 1;
$hashLocal = mt_rand();
//fnDebug("true");

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_filtro = fnLimpaCampoZero($_REQUEST['COD_FILTRO']);
        $cod_tpfiltro = fnLimpaCampoZero($_REQUEST['COD_TPFILTRO']);
        $des_filtro = fnLimpaCampo($_REQUEST['DES_FILTRO']);
        $idS = fnLimpaCampo($_REQUEST['idS']);

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    $sqlInsert = "INSERT INTO FILTROS_ATENDIMENTO(
									COD_EMPRESA,
									COD_TPFILTRO,
									DES_FILTRO,
									COD_USUCADA
									) VALUES(
									$cod_empresa,
									$cod_tpfiltro,
									'$des_filtro',
									$cod_usucada
									)";

                    //fnEscreve($sql);

                    $arrayInsert = mysqli_query(conntemp($cod_empresa,''), $sqlInsert);

                    if (!$arrayInsert) {

                        $cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert,$nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }
                    ?>
                    <script>
                        try {
                            parent.$('#REFRESH_FILTRO').val("S");
                        } catch (err) {
                        }
                        try {
                            parent.$('#idS').val("<?= $idS ?>");
                        } catch (err) {
                        }
                        try {
                            parent.$('#COD_TPFILTRO').val("<?= $cod_tpfiltro ?>");
                        } catch (err) {
                        }
                    </script>
                    <?php                  
                    break;
                case 'ALT':

                    $sqlUpdate = "UPDATE FILTROS_ATENDIMENTO SET
								COD_TPFILTRO=$cod_tpfiltro,
								DES_FILTRO='$des_filtro'
								WHERE COD_FILTRO = $cod_filtro";

                    //echo $sql;

                    $arrayUpdate = mysqli_query(conntemp($cod_empresa,''), $sqlUpdate);

					if (!$arrayUpdate) {
 
					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate,$nom_usuario);
					}
					//fnEscreve($sqlUpdate);
 
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
                    ?>
                    <script>
                        try {
                            parent.$('#REFRESH_FILTRO').val("S");
                        } catch (err) {
                        }
                        try {
                            parent.$('#idS').val("<?= $idS ?>");
                        } catch (err) {
                        }
                        try {
                            parent.$('#COD_TPFILTRO').val("<?= $cod_tpfiltro ?>");
                        } catch (err) {
                        }
                    </script>
                    <?php
                    break;
                case 'EXC':

                    $sqlExc = "DELETE FROM FILTROS_ATENDIMENTO
								WHERE COD_FILTRO = $cod_filtro";

                    //echo $sql;

                    $arrayExc = mysqli_query(conntemp($cod_empresa,''),$sqlExc);
					if (!$arrayExc) {

                        $cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc,$nom_usuario);
                    }
                    
                    if($cod_erro == 0 || $cod_erro == "") {
                        $msgRetorno = "Registro Excluido com sucesso";
                    }else{
                        $msgRetorno = "Falha na Exclusão:$cod_erro";
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
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

if ($popUp == 'true') {
    $cod_tpfiltro = fnLimpaCampo(fnDecode($_GET['idF']));
    $idS = fnLimpaCampo(fnDecode($_GET['idS']));
    $disabled = 'disabled';
    $andCodTp = "AND FC.COD_TPFILTRO = $cod_tpfiltro";
} else {
    $cod_tpfiltro = "";
    $disabled = "";
    $andCodTp = "";
}

//fnMostraForm();
?>


<div class="push30"></div> 

<div class="row">				

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
                <?php if ($popUp != "true") { ?>							
            <div class="portlet portlet-bordered">
                <?php } else { ?>
                <div class="portlet" style="padding: 0 20px 20px 20px;" >
<?php } ?>

<?php if ($popUp != "true") { ?>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fal fa-terminal"></i>
                            <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                        </div>
                        <?php include "atalhosPortlet.php"; ?>
                    </div>
<?php } ?>
                <div class="portlet-body">

                    <?php if ($msgRetorno <> '') { ?>	
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                        </div>
                    <?php
                    }

                    if ($popUp != 'true') {
						
						$abaInfoAtendimento = fnDecode($_GET['mod']); 
                        include "abasAtendimentoConfig.php";
                    }
                    ?>

                    <div class="push30"></div> 

                    <div class="login-form">

                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                            <fieldset>
                                <legend>Dados Gerais</legend> 

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Código</label>
                                            <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FILTRO" id="COD_FILTRO" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="campogrupo">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Tipo do Filtro</label>
                                            <select onchange="agrupaCombo(this.value)" data-placeholder="Selecione um tipo" name="SEL_TPFILTRO" id="SEL_TPFILTRO" class="chosen-select-deselect requiredchk" style="width:100%" required>
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO_ATENDIMENTO
				                                                            WHERE COD_EMPRESA = $cod_empresa
                                                                                           ";
                                                $arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);
                                                

                                                while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                    ?>
                                                    <option value="<?= $qrLista['COD_TPFILTRO'] ?>"><?= $qrLista['DES_TPFILTRO'] ?></option> 
                                                    <?php
                                                }
                                                ?>

                                                <!-- <option value="add">&nbsp;ADICIONAR NOVO</option> -->
                                            </select>
                                            <script type="text/javascript">
                                                $('#campogrupo #SEL_TPFILTRO').change(function () {
                                                    valor = $(this).val();
                                                    if (valor == "add") {
                                                        $(this).val('').trigger("chosen:updated");
                                                        $('#COD_TPFILTRO').val($(this).val());
                                                        $('#btnCad').click();
                                                    } else {
                                                        $('#COD_TPFILTRO').val($(this).val());
                                                    }
                                                });
                                                $('#SEL_TPFILTRO').val(<?= $cod_tpfiltro ?>).trigger("chosen:updated");
                                            </script>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Filtro</label>
                                            <input type="text" class="form-control input-sm" name="DES_FILTRO" id="DES_FILTRO" maxlength="50" required>
                                            <div class="help-block with-errors"></div>
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
                                <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

                            </div>

                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="idS" id="idS" value="<?= $idS ?>">
                            <input type="hidden" name="COD_TPFILTRO" id="COD_TPFILTRO" value="<?= $cod_tpfiltro ?>">
                            <input type="hidden" name="REFRESH_FILTRO" id="REFRESH_FILTRO" value="N">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                            <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

                            <div class="push5"></div> 

                        </form>

                        <div class="push50"></div>

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <form name="formLista">

                                    <table class="table table-bordered table-striped table-hover tableSorter">
                                        <thead>
                                            <tr>
                                                <th class="{ sorter: false }" width="40"></th>
                                                <th>Código</th>
                                                <th>Tipo de Filtro</th>
                                                <th>Filtro</th>
                                            </tr>
                                        </thead>
                                        <tbody id="relatorioConteudo">

                                            <?php
                                            //paginação
                                            $sql="SELECT 1 FROM filtros_cliente FC
                                            LEFT JOIN TIPO_FILTRO_ATENDIMENTO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
                                            WHERE FC.COD_EMPRESA = $cod_empresa
                                            $andCodTp
                                            order by FC.COD_TPFILTRO, TF.DES_TPFILTRO";
                                            //echo $sql;
                                            
                                             $retorno = mysqli_query($conn, $sql);
                                             $total_itens_por_pagina = mysqli_num_rows($retorno);

                                             $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

                                             //variavel para calcular o início da visualização com base na página atual
                                             $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                            
                                            $sql = "SELECT FC.*, TF.DES_TPFILTRO FROM FILTROS_ATENDIMENTO FC
                                                    LEFT JOIN TIPO_FILTRO_ATENDIMENTO TF ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
                                                    WHERE FC.COD_EMPRESA = $cod_empresa
                                                    $andCodTp
                                                    order by FC.COD_TPFILTRO, TF.DES_TPFILTRO
                                                    LIMIT $inicio, $itens_por_pagina";

                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            //echo($sql);
                                            $count = 0;
                                            while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                                $count++;
                                                echo"
                                                    <tr>
                                                      <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                      <td>" . $qrBuscaModulos['COD_FILTRO'] . "</td>
                                                      <td>" . $qrBuscaModulos['DES_TPFILTRO'] . "</td>
                                                      <td>" . $qrBuscaModulos['DES_FILTRO'] . "</td>
                                                    </tr>
                                                    <input type='hidden' id='ret_COD_FILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_FILTRO'] . "'>
                                                    <input type='hidden' id='ret_COD_TPFILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_TPFILTRO'] . "'>
                                                    <input type='hidden' id='ret_DES_FILTRO_" . $count . "' value='" . $qrBuscaModulos['DES_FILTRO'] . "'>
                                                    ";
                                            }
                                            ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="" colspan="100">
                                                    <center><ul id="paginacao" class="pagination-sm"></ul></center>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="100">
                                                    <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                                </th>
                                            </tr>
                                        </tfoot>
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

    <a type="hidden" name="btnCad" id="btnCad" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1399) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Tipo de Filtro"></a>

    <!-- modal -->									
    <div class="modal fade" id="popModal" tabindex='-1'>
        <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
                </div>		
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal --> 

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script type="text/javascript">

$(function () {
     
        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

    $('#formulario').validator('destroy');
    $('#formulario').validator();

    //modal close
    $('.modal').on('hidden.bs.modal', function () {
        if ($('#REFRESH_FILTRO').val() == "S") {

            $.ajax({
                method: 'POST',
                url: 'ajxTipoFiltro.php',
                data: {COD_EMPRESA:<?= $cod_empresa ?>},
                beforeSend: function () {
                    $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function (data) {
                    console.log(data);
                    $('#relatorioConteudo').html(data);
                    $('#REFRESH_FILTRO').val("N");
                }
            });

        }
    });



$(".exportarCSV").click(function () {
            $.confirm({
                title: 'Exportação',
                content: '' +
                        '<form action="" class="formName">' +
                        '<div class="form-group">' +
                        '<label>Insira o nome do arquivo:</label>' +
                        '<input type="text" placeholder="Nome" class="nome form-control" required />' +
                        '</div>' +
                        '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gerar',
                        btnClass: 'btn-blue',
                        action: function () {
                            var nome = this.$content.find('.nome').val();
                            if (!nome) {
                                $.alert('Por favor, insira um nome');
                                return false;
                            }

                            $.confirm({
                                title: 'Mensagem',
                                type: 'green',
                                icon: 'fa fa-check-square',
                                content: function () {
                                    var self = this;
                                    return $.ajax({
                                        url: "ajxFiltrosAtendimento.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa)?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function (response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                         console.log(response);
                                    }).fail(function (response) {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                         console.log(response.responseText);
                                    });
                                },
                                buttons: {
                                    fechar: function () {
                                        //close
                                    }
                                }
                            });
                        }
                    },
                    cancelar: function () {
                        //close
                    },
                }
            });
        });
        });

function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "ajxFiltrosAtendimento.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function () {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function (data) {
                $("#relatorioConteudo").html(data);
                console.log(data);
            },
            error: function () {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

function agrupaCombo(selectObject) {
    $('#paginacao').twbsPagination('destroy');
    $.ajax({
        type: "POST",
        url: "ajxFiltrosAtendimento.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=1&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
        data: $('#formulario').serialize(),
        beforeSend: function () {
            $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
        },
        success: function (data) {
            $("#relatorioConteudo").html(data);
            console.log(data);
        },
        error: function () {
            $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
        }
    });
    //$.ajax({
        //type: "POST",
        //url: "ajxFiltrosAtendimento.do?opcao=retornar&id=<?php echo fnEncode($cod_empresa);?>",
         //data: {COD_AGRUPADOR:value},
        //beforeSend: function () {
            //$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
        //},
        //success: function (data) {
            //$("#relatorioConteudo").html(data);
            //console.log(data);
        //},

    //});
    }   

function retornaForm(index) {
    $("#formulario #COD_FILTRO").val($("#ret_COD_FILTRO_" + index).val());
    $("#formulario #DES_FILTRO").val($("#ret_DES_FILTRO_" + index).val());
    $("#formulario #SEL_TPFILTRO").val($("#ret_COD_TPFILTRO_" + index).val()).trigger("chosen:updated");
    $("#formulario #COD_TPFILTRO").val($("#ret_COD_TPFILTRO_" + index).val());
    agrupaCombo($("#ret_COD_TPFILTRO_"+index).val());
    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');
}

    </script>	