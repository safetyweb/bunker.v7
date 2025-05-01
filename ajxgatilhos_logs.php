<?php
include '_system/_functionsMain.php';

$opcao = $_GET['opcao'];

$sql = "select * FROM empresas order by NOM_FANTASI";
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
$empresas = [];
while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery)) {
    $empresas[$qrListaEmpresa['COD_EMPRESA']] = $qrListaEmpresa['NOM_FANTASI'];
}

if ($opcao == "items") {
    $data = fnDataSql(@$_REQUEST['DATA']);
    $empresa = @$_REQUEST['EMPRESA'];
    $tipo = @$_REQUEST['TIPO'];

    $sql = "SELECT
                *
            FROM gatilhos_logs_exec
            WHERE 1=1
              AND DATE(DATAHORA_INICIO)='$data'
              " . ($empresa != "" ? "AND CONCAT(',',CODS_EMPRESA,',') LIKE '%,$empresa,%'" : "") . "
              " . ($tipo != "" ? "AND TIPO = '$tipo'" : "") . "
              " . (@$_REQUEST["INCONSISTENCIAS"] == "ERROS" ? "AND ERROS > 0" : "") . "
              " . (@$_REQUEST["INCONSISTENCIAS"] == "DIVERGENCIAS" ? "AND FILA <> ENVIOS" : "") . "
            ORDER BY COD_LOG_EXEC DESC";

    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    while ($row = mysqli_fetch_assoc($arrayQuery)) {
        //$link = "?mod=" . $_REQUEST["mod"] . "&id=" . $row['COD_EXECUCAO'] . "&uid=" . $row["UID"] . "&tipo=" . $row['TIPO'];
        $link = "?mod=" . $_REQUEST["mod"] . "&uid=" . $row["UID"] . "&autorefresh=" . ($row['DATAHORA_FIM'] == "" ? "true" : "false");

        echo "<tr class=' " . ($row['ERROS'] > 0 ? "text-danger" : '') . " '>";
        echo "<td class='small'><a href='$link'>" . $row['COD_LOG_EXEC'] . "</a></td>";
        echo "<td class='small'>" . fnDataFull($row['DATAHORA_INICIO']) . "</td>";
        echo "<td class='small'>" . ($row['DATAHORA_FIM'] == "" ? "<span class='text-danger'>" . fnDataFull($row['DATAHORA_ATUALIZACAO_LOG']) . "</span>" : fnDataFull($row['DATAHORA_FIM'])) . "</td>";
        echo "<td class='small text-center'>" . "<span class='" . ($row['DATAHORA_FIM'] == "" ? 'text-danger' : '') . "'>" . duracaoDatas($row['DATAHORA_INICIO'], ($row['DATAHORA_FIM'] == "" ? $row['DATAHORA_ATUALIZACAO_LOG'] : $row['DATAHORA_FIM'])) . "</span>" . "</td>";
        echo "<td class='small text-center'>" . "<span class='badge' style='background-color:" . getCorByTipo($row['TIPO']) . "'>" . getLabelByTipo($row['TIPO']) . "</span>" . "</td>";
        echo "<td class='small'>" . showEmpresas($row['CODS_EMPRESA']) . "</td>";
        echo "<td class='small text-right " . ($row['FILA'] != $row['ENVIOS'] ? "text-danger" : "") . "'>" . $row['ENVIOS'] . " / " . $row['FILA'] . "</td>";
        echo "<td class='small text-right'>" . $row['ERROS'] . "</td>";
        echo "<td class='small text-center'>" . ($row['DATAHORA_FIM'] == "" ? "<span class='badge' style='background-color:#f39c12'>Em Execução</span>" : "<span class='badge' style='background-color:#337ab7'>Concluído</span>") . "</td>";
        echo "</tr>";
    }
} elseif ($opcao == "item") {
    $uid = $_REQUEST["uid"];

    $sql = "SELECT
      *
    FROM gatilhos_logs_exec
    WHERE 1=1
    AND UID='$uid'";

    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $row = mysqli_fetch_assoc($arrayQuery);

    echo "<div class='col-md-2'>
              <div class='form-group'>
                  <label class='control-label'>Cód. Execução</label>
                  <div>" . $row['COD_LOG_EXEC'] . "</div>
              </div>
          </div>";

    echo "<div class='col-md-3'>
              <div class='form-group'>
                  <label class='control-label'>Início</label>
                  <div>" . fnDataFull($row['DATAHORA_INICIO']) . "</div>
              </div>
          </div>";

    echo "<div class='col-md-3'>
              <div class='form-group'>
                  <label class='control-label'>Término</label>
                  <div>" . ($row['DATAHORA_FIM'] == "" ? "<span class='text-danger'>" . fnDataFull($row['DATAHORA_ATUALIZACAO_LOG']) . "</span>" : fnDataFull($row['DATAHORA_FIM'])) . "</div>
              </div>
          </div>";

    echo "<div class='col-md-1'>
              <div class='form-group'>
                  <label class='control-label'>Duração</label>
                  <div>" . "<span class='" . ($row['DATAHORA_FIM'] == "" ? 'text-danger' : '') . "'>" . duracaoDatas($row['DATAHORA_INICIO'], ($row['DATAHORA_FIM'] == "" ? $row['DATAHORA_ATUALIZACAO_LOG'] : $row['DATAHORA_FIM'])) . "</span>" . "</div>
              </div>
          </div>";

    echo "<div class='col-md-1'>
              <div class='form-group'>
                  <label class='control-label'>Tipo</label>
                  <div>" . "<span class='badge' style='background-color:" . getCorByTipo($row['TIPO']) . "'>" . getLabelByTipo($row['TIPO']) . "</span>" . "</div>
              </div>
          </div>";

    echo "<div class='col-md-2'>
              <div class='form-group'>
                  <label class='control-label'>Status</label>
                  <div>" . ($row['DATAHORA_FIM'] == "" ? "<span class='badge' style='background-color:#f39c12'>Em Execução</span>" : "<span class='badge' style='background-color:#337ab7'>Concluído</span>") . "</div>
              </div>
          </div>";

    echo "<div class='col-md-12'>&nbsp;</div>";

    echo "<div class='col-md-6'>
            <div class='form-group'>
                <label class='control-label'>Empresas</label>
                <div style='max-height: 67px;overflow: auto;'>" . showEmpresas($row['CODS_EMPRESA'], true) . "</div>
            </div>
        </div>";

    echo "<div class='col-md-2'>
            <div class='form-group'>
                <label class='control-label'>Qtd. Fila</label>
                <div>" . $row['FILA'] . "</div>
            </div>
        </div>";

    echo "<div class='col-md-2'>
            <div class='form-group'>
                <label class='control-label'>Qtd. Envios</label>
                <div>" . $row['ENVIOS'] . "</div>
            </div>
        </div>";

    echo "<div class='col-md-2'>
            <div class='form-group'>
                <label class='control-label'>Qtd. Erros</label>
                <div>" . $row['ERROS'] . "</div>
            </div>
        </div>";
} elseif ($opcao == "subitems") {
    $uid = $_REQUEST['UID'];
    $empresa = @$_REQUEST['EMPRESA'];
    $tip_gatilho = @$_REQUEST['TIP_GATILHO'];
    $cod_campanha = @$_REQUEST['COD_CAMPANHA'];
    $cod_gatilho = @$_REQUEST['COD_GATILHO'];

    $sql = "SELECT
              *
          FROM gatilhos_logs
          WHERE 1=1
            AND UID='$uid'
            " . ($empresa != "" ? "AND COD_EMPRESA = '$empresa'" : "") . "
            " . ($tip_gatilho != "" ? "AND TIP_GATILHO = '$tip_gatilho'" : "") . "
            " . ($cod_campanha != "" ? "AND COD_CAMPANHA = '$cod_campanha'" : "") . "
            " . ($cod_gatilho != "" ? "AND COD_GATILHO = '$cod_gatilho'" : "") . "
            " . (@$_REQUEST["INCONSISTENCIAS"] == "ERROS" ? "AND ERRO <> ''" : "") . "
          ORDER BY COD_LOG_GATILHO";
    //echo "<tr><td colspan=100>".$sql."</td></tr>";
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    while ($row = mysqli_fetch_assoc($arrayQuery)) {
        echo "<tr class=' " . ($row['ERRO'] != "" ? "text-danger" : @$row['LAYOUT']) . " '>";
        echo "<td class='small'>" . fnDataFull($row['DATAHORA']) . "</td>";
        echo "<td class='small text-center'>" . "<span class='badge' style='background-color:" . getCorByTipo($row['TIPO']) . "'>" . getLabelByTipo($row['TIPO']) . "</span>" . "</td>";
        echo "<td class='small'>" . $empresas[$row['COD_EMPRESA']] . "</td>";
        echo "<td class='small text-right'>" . $row['COD_CAMPANHA'] . "</td>";
        echo "<td class='small text-right'>" . $row['COD_GATILHO'] . "</td>";
        echo "<td class='small'>" . "<span class='badge'>" . $row['TIP_GATILHO'] . "</span>" . "</td>";
        echo "<td class='small'>";
        echo $row['DESCRICAO'];
        echo "<div>";
        if ($row['QUERY'] != "") {
            $id = 'modal_sql_' . $row['COD_LOG_GATILHO'];
            echo "<button style='margin:2px;' class='btn btn-info btn-xs' data-toggle='modal' data-target='#$id'>SQL</button>";
            createModal($id, "Query Executada", "<pre>" . $row['QUERY'] . "</pre>");
        }
        if ($row['ERRO'] != "") {
            $id = 'modal_err_' . $row['COD_LOG_GATILHO'];
            echo "<button style='margin:2px;' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#$id'>Erro</button>";
            createModal($id, "Detalhes do erro", "<div class='alert alert-danger'>" . $row['ERRO'] . "</div>");
        }
        if ($row['JSON'] != "") {
            $id = 'modal_json_' . $row['COD_LOG_GATILHO'];
            echo "<button style='margin:2px;' class='btn btn-success btn-xs' data-toggle='modal' data-target='#$id'>JSON</button>";
            createModal($id, "Retorno dos Dados", "<pre>" . print_r(json_decode($row['JSON'], true), true) . "</pre>");
        }
        if ($row['QTD_FILA'] > 0) {
            $id = 'modal_qtdfila_' . $row['COD_LOG_GATILHO'];
            echo "<button style='margin:2px;' class='btn btn-success btn-xs' data-toggle='modal' data-target='#$id'>Qtd. Fila: " . $row['QTD_FILA'] . "</button>";
            createModal($id, "Qtd. Fila", "<pre>" . print_r(json_decode($row['QTD_FILA'], true), true) . "</pre>");
        }
        if ($row['QTD_ENVIOS'] > 0) {
            $id = 'modal_qtdenv_' . $row['COD_LOG_GATILHO'];
            echo "<button style='margin:2px;' class='btn btn-success btn-xs' data-toggle='modal' data-target='#$id'>Qtd. Envios: " . $row['QTD_ENVIOS'] . "</button>";
            createModal($id, "Qtd. Envios", "<pre>" . print_r(json_decode($row['QTD_ENVIOS'], true), true) . "</pre>");
        }
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
}

function showEmpresas($lista, $show_label = false)
{
    global $empresas;

    $items = explode(",", $lista);

    $items = array_filter($items, function ($valor) {
        return $valor !== '0';
    });
    $items = array_map(function ($item) use ($show_label, $empresas) {
        if ($show_label == true) {
            return "<span class='badge'>$item | $empresas[$item]</span>";
        } else {
            return "<span class='badge' title='" . $empresas[$item] . "'>$item</span>";
        }
    }, $items);

    $lista = implode(" ", $items);
    return $lista;
}

function duracaoDatas($ini, $fin)
{
    $timestampInicio = strtotime($ini);
    $timestampFim = strtotime($fin);

    $diferencaSegundos = $timestampFim - $timestampInicio;

    $minutos = floor($diferencaSegundos / 60);
    $segundos = $diferencaSegundos % 60;

    return $minutos . ":" . str_pad($segundos, 2, '0', STR_PAD_LEFT);
}

function getLabelByTipo($tipo)
{
    if ($tipo == 'PUSH') {
        return 'Push';
    } elseif ($tipo == 'PUSH_LOTE') {
        return 'Push Lote';
    } elseif ($tipo == 'PUSH_GENERICO') {
        return 'Push Genérico';
    } elseif ($tipo == 'EMAIL') {
        return 'E-mail';
    } elseif ($tipo == 'WHATSAPP') {
        return 'Whatsapp';
    } else {
        return $tipo;
    }
}

function getCorByTipo($tipo)
{
    if ($tipo === "PUSH" || $tipo === "PUSH_LOTE" || $tipo === "PUSH_GENERICO") {
        return "#f39c12";
    } elseif ($tipo === "EMAIL") {
        return "#337ab7";
    } elseif ($tipo === "WHATSAPP") {
        return "#00BD07";
    } else {
        return "#18bc9c";
    }
}

function createModal($id, $titulo, $valor)
{
    echo "<script>";
    echo "$('body').append(`
    <div class='modal fade' id='" . $id . "' tabindex='-1' role='dialog' aria-labelledby='" . $id . "Label' aria-hidden='true'>
      <div class='modal-dialog' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='" . $id . "Label'>" . $titulo . "</h5>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
          </div>
          <div class='modal-body'>
            $valor
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-primary' data-dismiss='modal'>Fechar</button>
          </div>
        </div>
      </div>
    </div>
  `)";
    echo "</script>";
}
