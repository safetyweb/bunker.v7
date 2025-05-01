<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$cod_usuario = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$nom_empresa = "";
$NOM_CAMPOOBGCAD = "";
$NOM_CAMPOOBGTAB = "";
$campoSQL = "";
$hideExport = "";
$cod_cliente = "";
$qrLista = "";
$deleunidade = "";
$unidadeTEMP = "";
$andUsuario = "";
$qrTotal = "";
$cod_atendente_indefinido = "";
$unidade_venda_indefinido = "";
$num_masc = "";
$num_fem = "";
$num_ind = "";
$num_tot = "";
$Nomecampo = "";
$includsql = "";
$ARRAY_VENDEDOR1 = [];
$ARRAY_VENDEDOR = [];
$sql11 = "";
$countLinha = "";
$unidades = "";
$usuarios = "";
$usuariosTotais = "";
$qrListaVendas = "";
$NOM_ARRAY_NON_ATENDENTE = "";
$usuario = "";
$loja = "";
$qtd_nascimen = "";
$qtd_celular = "";
$qtd_telefon = "";
$qtd_emailus = "";
$qtd_enderec = "";
$total_campos = "";
$linha = "";
$tot_qualidade = "";
$totalQualidade = "";
$totalSoma = "";
$totalCampos = "";
$totalCad = "";
$somadesult = "";
$qualidade = "";
$TOTAL_QTD_CADASTRO = 0;
$nomecampos = "";
$nomecampos1 = "";
$TOTAL = 0;
$campoSQL1 = "";
$somaresult = "";
$saldoteste = [];
$VL_SUM = 0;
$content = "";
$countPie = "";
$i = "";


$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $cod_univend = @$_POST['COD_UNIVEND'];
        $cod_usuario = @$_POST['COD_USUARIO'];
        $cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
        $cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);
        // $lojasSelecionadas = @$_GET['LOJAS'];

        // fnEscreve($lojasSelecionadas);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '' && $opcao != 0) {
        }
    }
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}
//campos obrigatórios

$camposCAD = fnQualidadeCampos($connAdm->connAdm(), $cod_empresa);

//echo "<pre>";
//print_r($camposCAD);
//echo "</pre>";

$NOM_CAMPOOBGCAD = explode(',', $camposCAD['NOM_CAMPOOBG']);
$NOM_CAMPOOBGTAB = explode(',', $camposCAD['DES_CAMPOOBG']);

foreach ($NOM_CAMPOOBGTAB as $campoSQL) {
    if ($campoSQL == "") {
        $msgRetorno = "Atualize sua <strong>Matriz de Campos Obrigatórios</strong>.";
        $msgTipo = 'alert-warning';
        $hideExport = 'true';
    } else {
        $hideExport = 'false';
    }
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnEscreve($lojasSelecionadas);

//fnMostraForm();
//fnEscreve($cod_cliente);
?>
<script src="js/pie-chart.js"></script>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                </div>

                <?php
                include "backReport.php";
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

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Grupo de Lojas</label>
                                        <?php include "grupoLojasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Região</label>
                                        <?php include "grupoRegiaoMulti.php"; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Final</label>

                                        <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Vendedor</label>
                                        <select data-placeholder="Selecione um vendedor" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;">
                                            <option value="">&nbsp;</option>
                                            <?php

                                            $sql = "SELECT * from USUARIOS 
                                                            WHERE COD_EMPRESA = $cod_empresa 
                                                            AND DAT_EXCLUSA IS NULL 
                                                            AND COD_TPUSUARIO in(7,11) 
                                                            ORDER BY NOM_USUARIO";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                            while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
                                                              <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
                                                            ";
                                            }
                                            ?>

                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                            </div>

                        </fieldset>
                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">
                        <input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
                        <input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <div class="push5"></div>

                    </form>
                </div>
            </div>
        </div>


        <div class="push30"></div>

        <div class="row">

            <div class="col-md-12 col-lg-12 margin-bottom-30">
                <!-- Portlet -->
                <div class="portlet portlet-bordered">

                    <div class="portlet-body">

                        <div class="row text-center">

                            <?php

                            include "filtroGrupoLojas.php";
                            //inserindo as unidade em table temporary
                            // $deleunidade='DELETE FROM unidadevenda WHERE   COD_EMPRESA='.$cod_empresa;
                            // mysqli_query(connTemp($cod_empresa,''), $deleunidade);
                            // $unidadeTEMP="insert into unidadevenda (COD_UNIVEND,COD_EMPRESA,NOM_FANTASI)
                            //  SELECT COD_UNIVEND,COD_EMPRESA,NOM_FANTASI FROM webtools.unidadevenda WHERE cod_empresa='$cod_empresa'";
                            // mysqli_query(connTemp($cod_empresa,''), $unidadeTEMP);
                            if ($cod_usuario != "" && $cod_usuario != 0) {
                                $andUsuario = "AND A.COD_ATENDENTE = $cod_usuario";
                            } else {
                                $andUsuario = "";
                            }

                            $sql = "SELECT SUM(A.COD_SEXOPES=1) AS MASCULINO,
                                                    SUM(A.COD_SEXOPES=2) AS FEMININO ,
                                                    SUM(ifnull(A.COD_SEXOPES,3)=3) AS INDEFINIDO,
                                                    SUM(ifnull(A.COD_ATENDENTE,0) = 0 ) AS COD_ATENDENTE_INDEFINIDO ,
                                                    ( SELECT COUNT(*)
                                                      FROM CLIENTES  
                                                      WHERE DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
                                                      and LOG_AVULSO='N' 
                                                      AND COD_EMPRESA = $cod_empresa
                                                      AND ifnull(COD_UNIVEND,0) = 0 ) AS UNIDADE_VENDA_INDEFINIDO
                                         FROM CLIENTES A 
                                         WHERE 
										 -- A.COD_ATENDENTE!=0 and
                                         A.DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
                                         and A.LOG_AVULSO='N' 
                                         $andUsuario
                                         AND A.COD_EMPRESA = $cod_empresa 
                                         AND A.COD_UNIVEND IN($lojasSelecionadas)";

                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                            $qrTotal = mysqli_fetch_assoc($arrayQuery);
                            //fnEscreve($sql);

                            $cod_atendente_indefinido = $qrTotal['COD_ATENDENTE_INDEFINIDO'];
                            $unidade_venda_indefinido = $qrTotal['UNIDADE_VENDA_INDEFINIDO'];
                            $num_masc = $qrTotal['MASCULINO'];
                            $num_fem = $qrTotal['FEMININO'];
                            $num_ind = $qrTotal['INDEFINIDO'];
                            $num_tot = ($num_masc + $num_fem + $num_ind);

                            ?>

                            <div class="form-group text-center col-md-2 col-lg-2">

                                <div class="push20"></div>

                                <p><span><?php echo fnValor($unidade_venda_indefinido, 0); ?></span></p>
                                <p><b>Sem Unidade</b></p>

                                <div class="push20"></div>

                            </div>


                            <div class="form-group text-center col-md-3 col-lg-3">

                                <div class="push20"></div>

                                <p><span><?php echo fnValor($num_tot, 0); ?></span></p>
                                <p><b>Total</b></p>

                                <div class="push"></div>

                                <div>
                                    <div id="pie-4" class="pie-title-center" data-percent="<?php echo fnValor(100, 0); ?>">
                                        <span class="pie-value"><?php echo fnValor(100, 2); ?>%</span>
                                    </div>
                                </div>

                                <div class="push20"></div>

                            </div>



                            <div class="form-group text-center col-md-2 col-lg-2">

                                <div class="push20"></div>

                                <p><span><?php echo fnValor($num_masc, 0); ?></span></p>
                                <p><b>Masculino</b></p>

                                <div class="push"></div>

                                <div>
                                    <div id="pie-1" class="pie-title-center" data-percent="<?php echo fnValor((($num_masc / $num_tot) * 100), 0); ?>">
                                        <span class="pie-value"><?php echo fnValor(100, 2); ?>%</span>
                                    </div>
                                </div>

                                <div class="push20"></div>

                            </div>

                            <div class="form-group text-center col-md-3 col-lg-3">

                                <div class="push20"></div>

                                <p><span><?php echo fnValor($num_fem, 0); ?></span></p>
                                <p><b>Feminino</b></p>

                                <div class="push"></div>

                                <div>
                                    <div id="pie-2" class="pie-title-center" data-percent="<?php echo fnValor((($num_fem / $num_tot) * 100), 0); ?>">
                                        <span class="pie-value"><?php echo fnValor(100, 2); ?>%</span>
                                    </div>
                                </div>

                                <div class="push20"></div>

                            </div>


                            <div class="form-group text-center col-md-2 col-lg-2">

                                <div class="push20"></div>

                                <p><span><?php echo fnValor($num_ind, 0); ?></span></p>
                                <p><b>Indefinido</b></p>

                                <div class="push"></div>

                                <div>
                                    <div id="pie-3" class="pie-title-center" data-percent="<?php echo fnValor((($num_ind / $num_tot) * 100), 0); ?>">
                                        <span class="pie-value"><?php echo fnValor(100, 2); ?>%</span>
                                    </div>
                                </div>

                                <div class="push20"></div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="portlet portlet-bordered">
            <div class="portlet-body">

                <div class="login-form">
                    <div class="row">

                        <div class="col-md-12" id="div_Produtos">

                            <div class="push20"></div>

                            <table class="table table-bordered table-hover  ">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><small>Vendedor</small></th>
                                        <th><small>Cadastro</small></th>
                                        <?php
                                        foreach ($NOM_CAMPOOBGCAD as $Nomecampo) {
                                            if ($Nomecampo != "") {
                                                echo  "<th><small>$Nomecampo</small></th>";
                                            }
                                        }
                                        ?>
                                        <th class="text-center"><small>Qualidade</small></th>
                                    </tr>
                                </thead>

                                <?php

                                include "filtroGrupoLojas.php";


                                foreach ($NOM_CAMPOOBGTAB as $campoSQL) {
                                    if ($campoSQL != "") {

                                        if ($campoSQL == "COD_SEXOPES") {

                                            $includsql .=  " IFNULL(sum(
                        						case when A.$campoSQL IS NULL  then
                        						0
                        						when A.$campoSQL = '3' then
                        						0
                        						else
                        						1
                        						END),0) $campoSQL,";
                                        } else {

                                            $includsql .=  " IFNULL(sum(
                        						case when A.$campoSQL IS NULL  then
                        						0
                        						when A.$campoSQL = '' then
                        						0
                        						else
                        						1
                        						END),0) $campoSQL,";
                                            //$includsql.=  "ifnull(SUM(if(A.$campoSQL IS NOT NULL,1,0)),0) AS  $campoSQL,";

                                        }
                                        /*
   $includsql.=  " IFNULL(sum(
					case when A.$campoSQL IS NULL  then
					0
					when A.$campoSQL = '' then
					0
					else
					1
					END),0) $campoSQL,";
	//$includsql.=  "ifnull(SUM(if(A.$campoSQL IS NOT NULL,1,0)),0) AS  $campoSQL,";		
	fnEscreve($campoSQL);
	*/
                                    }
                                }

                                //fnEscreve($includsql);

                                $ARRAY_VENDEDOR1 = array(
                                    'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
                                    'cod_empresa' => $cod_empresa,
                                    'conntadm' => $connAdm->connAdm(),
                                    'IN' => 'N',
                                    'nomecampo' => '',
                                    'conntemp' => '',
                                    'SQLIN' => ""
                                );
                                $ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

                                $sql11 = "SELECT DISTINCT 
                                        	A.COD_EMPRESA,
                                        	A.COD_UNIVEND,
                                        	A.COD_ATENDENTE,
                                        	US.NOM_USUARIO,      
                                        	B.NOM_FANTASI,
                                        	COUNT(COD_CLIENTE) QTD_CADASTRO,
                                        	$includsql
                                        	A.COD_ATENDENTE
                                        	FROM CLIENTES A
                                        	LEFT JOIN unidadevenda B ON B.COD_UNIVEND = A.COD_UNIVEND
                                        	LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_ATENDENTE
                                        	WHERE A.DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' and 
                                        	A.LOG_AVULSO='N' AND
                                        	A.COD_EMPRESA = $cod_empresa AND
                                        	A.COD_UNIVEND IN($lojasSelecionadas)
                                        	$andUsuario
                                        	GROUP BY A.COD_ATENDENTE,A.COD_UNIVEND
                                        	ORDER BY B.NOM_FANTASI";

                                //fnEscreve($sql11);

                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql11);

                                $countLinha = 1;
                                $unidades = 1;
                                $usuarios = 0;
                                $usuariosTotais = 0;
                                while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                    // $NOM_ARRAY_NON_ATENDENTE=(array_search($qrListaVendas['COD_ATENDENTE'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

                                    if ($qrListaVendas['NOM_USUARIO'] != "") {
                                        $usuario = $qrListaVendas['NOM_USUARIO'];
                                    } else {
                                        $usuario = "Sem usuário";
                                    }
                                    // fnEscreve($qrListaVendas['COD_ATENDENTE']);


                                    //monta primeiro cabeçalho
                                    if ($countLinha == 1) {
                                        $loja = $qrListaVendas['COD_UNIVEND'];
                                        $usuarios = 0;

                                        //$qtd_nascimen = $qrListaVendas['QTD_NASCIMEN'];
                                        //$qtd_celular = $qrListaVendas['QTD_CELULAR'];
                                        //$qtd_telefon = $qrListaVendas['QTD_TELEFON'];
                                        //$qtd_emailus = $qrListaVendas['QTD_EMAILUS'];
                                        //$qtd_enderec = $qrListaVendas['QTD_ENDEREC'];
                                        //$total_campos = (($qtd_nascimen+$qtd_celular+$qtd_telefon+$qtd_emailus+$qtd_enderec)/5);

                                        $total_campos = "";



                                ?>
                                        <thead>
                                            <tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
                                                <th width="50" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
                                                <th><?php echo $qrListaVendas['NOM_FANTASI']; ?></th>
                                                <th class="text-center">
                                                    <div id="total_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
                                                </th>
                                                <?php
                                                $linha = 1;
                                                foreach ($NOM_CAMPOOBGTAB as $Nomecampo) {
                                                    $linha++;
                                                ?>
                                                    <th class="text-center">
                                                        <div id="total_col<?php echo $linha; ?>_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
                                                    </th>
                                                <?php
                                                }
                                                ?>
                                                <th class="text-center">
                                                    <div id="total_col_qualidade_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        </tbody>
                                    <?php
                                    }
                                    //monta primeira linha
                                    if ($loja != $qrListaVendas['COD_UNIVEND']) {
                                        $unidades++;

                                    ?>

                                        <script type="text/javascript">
                                            $(function() {
                                                // $("#total_col_qualidade_<?= $loja ?>").text("<?= fnValor(($tot_qualidade / $usuarios), 2) ?>%");
                                                //          console.log("<?= $tot_qualidade ?>");
                                                //          console.log("<?= $usuarios ?>");
                                            });
                                        </script>

                                        <?php


                                        ?>
                                        <thead>
                                            <tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
                                                <th width="50" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
                                                <th><?php echo $qrListaVendas['NOM_FANTASI']; ?></th>
                                                <th class="text-center">
                                                    <div id="total_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
                                                </th>
                                                <?php
                                                $linha = 1;
                                                foreach ($NOM_CAMPOOBGTAB as $Nomecampo) {
                                                    $linha++;
                                                ?>
                                                    <th class="text-center">
                                                        <div id="total_col<?php echo $linha; ?>_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
                                                    </th>
                                                    <!-- <script type="text/javascript">
													$(function(){
														$("#total_col_qualidade_<?= $loja ?>").text("<?= fnValor(($tot_qualidade / $usuarios), 2) ?>%");
													});
												</script> -->
                                                <?php
                                                }
                                                ?>
                                                <th class="text-center">
                                                    <div id="total_col_qualidade_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        </tbody>

                                    <?php
                                        $usuariosTotais += $usuarios;
                                        $usuarios = 0;
                                        $tot_qualidade = 0;
                                    }
                                    //if($qrListaVendas['COD_ATENDENTE'] != 0){

                                    if ($loja != $qrListaVendas['COD_UNIVEND']) {
                                        $totalQualidade = ((($totalSoma) / $totalCampos) * 100) / $totalCad;

                                        // fnEscreve($totalSoma);
                                        // fnEscreve($totalCampos);
                                        // fnEscreve($totalCad);
                                        // fnEscreve($totalQualidade);
                                        // fnEscreve("--------------------------------");
                                        // fnEscreve($usuarios);

                                    ?>

                                        <script type="text/javascript">
                                            $(function() {
                                                // COMENTADO 12/11/2021 - MUDOU O CONCEITO DOS PERDCENTUAIS POR UNIDADE (KARINA PEDIU NO CHAMADO #3153)
                                                // $("#total_col_qualidade_<?= $loja ?>").html("<b><?= fnValor(($tot_qualidade / $usuarios), 2) ?>%</b> &nbsp;<span style='font-size: 11px; font-weight: 100;'>média por unidade</span>");
                                                $("#total_col_qualidade_<?= $loja ?>").html("<b><?= fnValor($totalQualidade, 2) ?>%</b> &nbsp;<span style='font-size: 11px; font-weight: 100;'></span>");
                                                console.log("_<?= $totalQualidade ?>_");
                                            });
                                        </script>

                                    <?php

                                        $totalSoma = 0;
                                        $totalCampos = 0;
                                        $totalCad = 0;
                                        $totalQualidade = 0;

                                        $loja = $qrListaVendas['COD_UNIVEND'];

                                        // exit();

                                    }

                                    //fnEscreve($loja);
                                    ?>

                                    <tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
                                        <td></td>
                                        <td><small><a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1520) ?>&id=<?php echo fnEncode($cod_empresa) ?>&dat_ini=<?= $dat_ini ?>&dat_fim=<?= $dat_fim ?>&cod_vendedor=<?= fnEncode($qrListaVendas['COD_ATENDENTE']) ?>&cod_univend=<?php echo fnEncode($qrListaVendas['COD_UNIVEND']); ?>&pop=true" data-title="Detalhes"><span class="fal fa-search-plus"></span></a> &nbsp; <?php echo $usuario; ?></small></td>
                                        <td class="text-center"><small class="qtde_col1_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_CADASTRO'], 0); ?></small></td>
                                        <?php

                                        $count = 1;

                                        foreach ($NOM_CAMPOOBGTAB as $campoSQL) {
                                            @$somadesult += $qrListaVendas[$campoSQL];
                                            $count++;
                                        ?>
                                            <td class="text-center"><small class="qtde_col<?= $count ?>_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas[$campoSQL], 0); ?></small></td>
                                        <?php
                                        }

                                        $somadesult = rtrim($somadesult, "+");

                                        //fnEscreve("soma " . $somadesult);
                                        //fnEscreve("count " . count($NOM_CAMPOOBGTAB));
                                        //fnEscreve("cadastros " . $qrListaVendas['QTD_CADASTRO']);
                                        //fnEscreve($campoSQL);

                                        $qualidade = ((($somadesult) / count($NOM_CAMPOOBGTAB)) * 100) / $qrListaVendas['QTD_CADASTRO'];
                                        $tot_qualidade += $qualidade;

                                        // VARIAVEIS NECESSARIAS PARA NOVO CALCULO                                                                               
                                        $totalSoma += $somadesult;
                                        $totalCampos = count($NOM_CAMPOOBGTAB);
                                        $totalCad += $qrListaVendas['QTD_CADASTRO'];
                                        // -----------------------------------------                                                                              

                                        ?>

                                        <td class="text-center"><small><?php echo fnValor($qualidade, 2); ?> %</small></td>
                                    </tr>


                                <?php
                                    unset($somadesult);
                                    $TOTAL_QTD_CADASTRO += $qrListaVendas['QTD_CADASTRO'];
                                    if ($countLinha <= 1) {
                                        foreach ($NOM_CAMPOOBGTAB as $nomecampos) {
                                            $nomecampos1 = $TOTAL . '_' . $nomecampos;
                                            $nomecampos1 += $qrListaVendas[$nomecampos];
                                            //echo '<br>'.$nomecampos1 .'<br>';
                                        }
                                    }



                                    foreach ($NOM_CAMPOOBGTAB as $campoSQL1) {

                                        // $TOTAL.'_'.$campoSQL += $qrListaVendas[$campoSQL1];
                                        //echo $TOTAL.'_'.$campoSQL.'<BR>'; 
                                        $somaresult += $qrListaVendas[$campoSQL1];
                                        $saldoteste[$campoSQL1][] = $qrListaVendas[$campoSQL1];
                                    }



                                    $countLinha++;
                                    $usuarios++;
                                }

                                // GERANDO ULTIMA UNIDADE
                                $totalQualidade = ((($totalSoma) / $totalCampos) * 100) / $totalCad;

                                ?>
                                <script type="text/javascript">
                                    $(function() {
                                        // COMENTADO 12/11/2021 - MUDOU O CONCEITO DOS PERDCENTUAIS POR UNIDADE (KARINA PEDIU)
                                        // $("#total_col_qualidade_<?= $loja ?>").html("<b><?= fnValor(($tot_qualidade / $usuarios), 2) ?>%</b> &nbsp;<span style='font-size: 11px; font-weight: 100;'>média por unidade</span>");
                                        $("#total_col_qualidade_<?= $loja ?>").html("<b><?= fnValor($totalQualidade, 2) ?>%</b> &nbsp;<span style='font-size: 11px; font-weight: 100;'></span>");
                                        console.log("_<?= $totalQualidade ?>_");
                                    });
                                </script>
                                <?php

                                $usuariosTotais += $usuarios;




                                ?>


                                <tr>
                                    <td colspan="2"></td>
                                    <!-- <td colspan="1"><b><?= fnValor($usuariosTotais, 0) ?></b> <span class="f14">usuários</span></td> -->
                                    <td class="text-center"><b><?php echo fnValor($TOTAL_QTD_CADASTRO, 0); ?></b></small></td>
                                    <?php
                                    foreach ($saldoteste as $VL_SUM) {
                                    ?>
                                        <td class="text-center"><b><?php echo fnValor(array_sum($VL_SUM), 0); ?></b></small></td>
                                    <?php
                                    }
                                    ?>

                                    <td class="text-center"><b><?php echo fnValor(((($somaresult) / count($NOM_CAMPOOBGTAB)) * 100) / $TOTAL_QTD_CADASTRO, 2);
                                                                unset($somaresult); ?> %</b> <span style='font-size: 11px;'>média geral</span></small></td>
                                </tr>
                                </tbody>

                                <?php if ($hideExport != 'true') { ?>

                                    <!-- <tfoot>
                                        <tr>
                                            <th colspan="100">
                                                <a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar</a>
                                            </th>
                                        </tr>
                                    </tfoot> -->
                                    <tfoot>
                                        <td class="text-left">
                                            <small>
                                                <div class="btn-group dropdown left">
                                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
                                                        &nbsp; Exportar &nbsp;
                                                        <span class="fas fa-caret-down"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                                        <li><a class="btn btn-sm exportarDetalhadoCSV">&nbsp;Exportar Detalhado</a></li>
                                                        <li><a class="btn btn-sm exportarGeralCSV">&nbsp;Exportar Geral</a></li>
                                                        <!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
                                                    </ul>
                                                </div>
                                            </small>
                                        </td>
                                    </tfoot>

                                <?php } ?>

                            </table>

                        </div>

                    </div>



                    <div class="push50"></div>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

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

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
    //datas
    $(function() {

        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY',
            maxDate: 'now',
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
        });

        $(".exportarDetalhadoCSV").click(function() {
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
                        action: function() {
                            var nome = this.$content.find('.nome').val();
                            if (!nome) {
                                $.alert('Por favor, insira um nome');
                                return false;
                            }

                            $.confirm({
                                title: 'Mensagem',
                                type: 'green',
                                icon: 'fa fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "relatorios/ajxRelCadastrosDinamico.do?opcao=exportarDetalhado&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        console.log(response);
                                    }).fail(function() {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                    });
                                },
                                buttons: {
                                    fechar: function() {
                                        //close
                                    }
                                }
                            });
                        }
                    },
                    cancelar: function() {
                        //close
                    },
                }
            });
        });

        $(".exportarGeralCSV").click(function() {
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
                        action: function() {
                            var nome = this.$content.find('.nome').val();
                            if (!nome) {
                                $.alert('Por favor, insira um nome');
                                return false;
                            }

                            $.confirm({
                                title: 'Mensagem',
                                type: 'green',
                                icon: 'fa fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "relatorios/ajxRelCadastrosDinamico.do?opcao=exportarGeral&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        console.log(response);
                                    }).fail(function() {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                    });
                                },
                                buttons: {
                                    fechar: function() {
                                        //close
                                    }
                                }
                            });
                        }
                    },
                    cancelar: function() {
                        //close
                    },
                }
            });
        });

        // Carregar totais de quantidade na linhas
        $("div[id^='total_col']").each(function(index) {
            if ($(this).attr('id').indexOf("total_col_qualidade") >= 0) {
                // var total_base = parseInt($('#total_col1_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
                // var total_1 = parseInt($('#total_col3_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
                // var total_2 = parseInt($('#total_col4_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
                // var total_3 = parseInt($('#total_col5_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
                // var total_4 = parseInt($('#total_col6_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
                // var total_5 = parseInt($('#total_col7_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
                // var result = ((((total_base + total_1 + total_2 + total_3 + total_4 + total_5) / 6) * 100) / total_base);
                // $(this).text(result.toFixed(2) + ' %')
            } else {
                var total = 0;
                $(".qtde_col" + $(this).attr('id').replace('total_col', '')).each(function(index, item) {
                    total += parseFloat($(this).text().replace('.', ''));
                });
                // console.log(total+"<br>");

                $('#' + $(this).attr('id')).text(total.toFixed(2));
                $('#' + $(this).attr('id')).mask("#.##0,00", {
                    reverse: true
                });
            }
        });

        $("div[id^='total_col']").each(function() {
            if ($(this).attr('id').indexOf("total_col_qualidade") < 0) {
                $(this).text($(this).text().slice(0, -3));
            }

        });
    });

    $("div[id^='total_col_qualidade_']").each(function(index) {
        if ($(this).attr('id').indexOf("total_col_qualidade") >= 0) {
            // var total_base = parseInt($('#total_col1_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
            // var total_1 = parseInt($('#total_col3_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
            // var total_2 = parseInt($('#total_col4_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
            // var total_3 = parseInt($('#total_col5_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
            // var total_4 = parseInt($('#total_col6_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
            // var total_5 = parseInt($('#total_col7_' + $(this).attr('id').replace('total_col_qualidade_', '')).text().replace('.', ''));
            // var result = ((((total_base + total_1 + total_2 + total_3 + total_4 + total_5) / 6) * 100) / total_base);
            // $(this).text(result.toFixed(2) + ' %')
        } else {
            var total = 0;
            $(".qtde_col" + $(this).attr('id').replace('total_col', '')).each(function(index, item) {
                total += parseFloat($(this).text().replace('.', ''));
            });
            // console.log(total+"<br>");

            $('#' + $(this).attr('id')).text(total.toFixed(2));
            $('#' + $(this).attr('id')).mask("#.##0,00", {
                reverse: true
            });
        }
    });


    function abreDetail(idBloco) {
        var idItem = $('.abreDetail_' + idBloco)
        if (!idItem.is(':visible')) {
            idItem.show();
            $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
            idItem.hide();
            $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
        }
    }

    //graficos
    $(document).ready(function() {

        $('#main-pie').pieChart({
            barColor: '#2c3e50',
            trackColor: '#eee',
            lineCap: 'round',
            lineWidth: 8,
            onStep: function(from, to, percent) {
                $(this.element).find('.pie-value').text(Math.round(percent) + '%');
            }
        });

        <?php
        //fnEscreve($countPie-1);
        for ($i = 1; $i < (5); $i++) {
        ?>
            $('#pie-<?php echo $i; ?>').pieChart({
                barColor: '#3bb2d0',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function(from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });

        <?php
        }
        ?>



    });
</script>