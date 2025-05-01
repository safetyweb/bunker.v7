<?php
include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
  echo fnDebug('true');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
}

$opcao = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$nom_cliente = "";
$des_placa = "";
$num_cartao = "";
$num_cgcecpf = "";
$log_funcionario = "";
$log_inativos = "";
$selectPlaca = "";
$andNome = "";
$andUnidades = "";
$andPlaca = "";
$andCartao = "";
$andCpf = "";
$andLojas = "";
$andFuncionarios = "";
$checkInativos = "";
$andInativos = "";
$andDatIni = "";
$andDatFim = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$zero = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$arrayVazio = [];
$array = [];
$cod_cliente = "";
$sql4 = "";
$arrayQuery4 = [];
$qrListaUnive4 = "";
$sexo = "";
$dat_niver = "";
$email = "";
$unidade = "";
$mostraPlaca = "";
$inicio = "";
$qrListaEmpresas = "";
$log_funciona = "";
$mostraCracha = "";


$log_univend = 'N';

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

$cod_univend = @$_POST['COD_UNIVEND'];
$lojasSelecionadas = @$_POST['LOJAS'];
@$nom_cliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);
@$des_placa = fnLimpacampo(@$_REQUEST['DES_PLACA']);
@$num_cartao = fnLimpaCampo(@$_POST['NUM_CARTAO']);
@$num_cgcecpf = fnLimpaDoc(fnLimpaCampo(@$_POST['NUM_CGCECPF']));


if (empty(@$_REQUEST['LOG_UNIVEND'])) {
  $log_univend = 'N';
} else {
  $log_univend = @$_REQUEST['LOG_UNIVEND'];
}

if (empty(@$_POST['LOG_FUNCIONARIO'])) {
  $log_funcionario = 'N';
} else {
  $log_funcionario = @$_POST['LOG_FUNCIONARIO'];
}
if (empty(@$_REQUEST['LOG_INATIVOS'])) {
  $log_inativos = 'N';
} else {
  $log_inativos = @$_REQUEST['LOG_INATIVOS'];
}



if ($cod_empresa == 19) {
  $selectPlaca = "(SELECT MAX(DES_PLACA) FROM VEICULOS WHERE COD_CLIENTE = CL.COD_CLIENTE) AS DES_PLACA,";
} else {
  $selectPlaca = "";
}

if ($nom_cliente != '' && $nom_cliente != 0) {
  $andNome = 'and cl.nom_cliente like "' . $nom_cliente . '%"';
} else {
  $andNome = ' ';
}

if ($cod_univend == 9999) {
  if ($log_univend == "N") {
    $andUnidades = "AND (CL.COD_UNIVEND IN (" . $lojasSelecionadas . ") OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
  } else {
    // $andUnidades = "AND (CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
    $andUnidades = "
                            -- AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                            AND 
                              case when CL.COD_UNIVEND IS NULL then '1'
                                   when CL.COD_UNIVEND = '0' then '1'
                                   when CL.COD_UNIVEND > '0' then '1'
                              ELSE '0' END IN (1)";
  }
} else {
  if ($log_univend == "N") {
    $andUnidades = "AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")";
  } else {
    // $andUnidades = "AND (CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
    $andUnidades = "
                            -- AND CL.COD_UNIVEND IN (" . $lojasSelecionadas . ")
                            AND 
                              case when CL.COD_UNIVEND IS NULL then '1'
                                   when CL.COD_UNIVEND = '0' then '1'
                                   when CL.COD_UNIVEND > '0' then '1'
                              ELSE '0' END IN (1)";
  }
}

if ($des_placa != '' && $des_placa != 0) {
  $andPlaca = 'AND CL.COD_CLIENTE = (SELECT COD_CLIENTE FROM VEICULOS WHERE DES_PLACA = "' . $des_placa . '")';
} else {
  $andPlaca = ' ';
}

if ($num_cartao != '' && $num_cartao != 0) {
  $andCartao = 'and cl.num_cartao=' . $num_cartao;
} else {
  $andCartao = ' ';
}

if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
  $andCpf = 'and cl.num_cgcecpf =' . $num_cgcecpf;
} else {
  $andCpf = ' ';
}

if ($cod_univend != '' && $cod_univend != 0) {
  $andLojas = 'and cl.cod_univend  in  (0,' . $lojasSelecionadas . ')';
} else {
  $andLojas = ' ';
}

if ($log_funcionario == 'S') {
  $andFuncionarios = " AND cl.LOG_FUNCIONA = 'S' ";
} else {
  $andFuncionarios = ' ';
}

if ($log_inativos == "S") {
  $checkInativos = "checked";
  $andInativos = "AND CL.LOG_ESTATUS = 'N'";
} else {
  $checkInativos = "";
  $andInativos = "";
}

if ($dat_ini == "" || $dat_fim == "") {
  $andDatIni = " ";
  $andDatFim = " ";
} else {
  $andDatIni = "AND DATE(CL.DAT_ALTERAC) BETWEEN '$dat_ini' AND '$dat_fim' ";
}

// if ($dat_fim == "") {
//   $andDatFim = " ";
// } else {
//   $andDatFim = "AND DATE_FORMAT(CL.DAT_ALTERAC, '%Y-%m-%d') <= '$dat_fim' ";
// }


switch ($opcao) {

  case 'exportar':

    $nomeRel = @$_GET['nomeRel'];
    $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


    $sql = "SELECT * FROM (    
                    SELECT * FROM (
                      SELECT 
                       '2' ORDERBY,
                       CL.COD_CLIENTE,
                       '' NOM_FANTASI,
                       CL.NOM_CLIENTE,
                       CL.DAT_NASCIME AS Data_Nascimento,
                       CL.DAT_CADASTR as Data_Cadastro,
                       CL.DAT_ALTERAC as Data_Alteracao,
                       USU2.NOM_USUARIO AS Usuario_Alteracao,
                       CL.DES_EMAILUS,
                       CL.NUM_CELULAR,
                       CL.NUM_CEPOZOF,
                       USU3.NOM_USUARIO AS NOME_ATENDENTE,
                      CASE 
                      WHEN CL.COD_SEXOPES = 1 THEN 'Masculino'
                        WHEN CL.COD_SEXOPES = 2 THEN 'Feminino'
                        ELSE 'Indefinido' END DES_SEXOPES
                      FROM log_alter_clientes CL
                      LEFT JOIN USUARIOS USU2 ON USU2.COD_USUARIO = CL.COD_ALTERAC
                      LEFT JOIN USUARIOS USU3 ON USU3.COD_USUARIO = CL.COD_ATENDENTE
                      WHERE CL.cod_empresa=$cod_empresa ORDER BY CL.DAT_ALTERAC DESC 
                    )TMPORDER

                  UNION ALL

                  SELECT 
                    '1' ORDERBY,
                    CL.COD_CLIENTE,
                    uni.NOM_FANTASI,
                    CL.NOM_CLIENTE,
                    CL.DAT_NASCIME AS Data_Nascimento,
                    CL.DAT_CADASTR,
                    '' DAT_ALTERAC,
                    USU2.NOM_USUARIO AS Usuario_Alteracao,
                    CL.DES_EMAILUS,
                    CL.NUM_CELULAR,
                    CL.NUM_CEPOZOF,
                    USU3.NOM_USUARIO AS NOME_ATENDENTE,
                    CASE 
                    WHEN CL.COD_SEXOPES = 1 THEN 'Masculino'
                      WHEN CL.COD_SEXOPES = 2 THEN 'Feminino'
                      ELSE 'Indefinido' END DES_SEXOPES
                    FROM clientes CL
                    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND= CL.COD_UNIVEND
                    LEFT JOIN USUARIOS USU2 ON USU2.COD_USUARIO = CL.COD_ALTERAC
                    LEFT JOIN USUARIOS USU3 ON USU3.COD_USUARIO = CL.COD_ATENDENTE
                    WHERE CL.cod_empresa=$cod_empresa AND 
                    cod_cliente IN (SELECT cod_cliente FROM log_alter_clientes WHERE cod_empresa=$cod_empresa)
                  )TMP
                  ORDER BY COD_CLIENTE DESC,ORDERBY ASC";

    // fnEscreve($sql);

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

    $arquivo = fopen($arquivoCaminho, 'w', 0);

    while ($headers = mysqli_fetch_field($arrayQuery)) {
      $CABECHALHO[] = $headers->name;
    }
    unset($CABECHALHO['0']);
    fputcsv($arquivo, $CABECHALHO, ';', '"');


    $zero = 0;
    while ($row = mysqli_fetch_assoc($arrayQuery)) {
      //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
      //$textolimpo = json_decode($limpandostring, true);
      if ($row['ORDERBY'] == 1 && $zero > 0) {
        $arrayVazio[] = " ";
        fputcsv($arquivo, $arrayVazio, ';', '"');
        unset($arrayVazio);
      }
      // $array['NOM_FANTASI'] = preg_replace("/[^a-zA-Z0-9]/", "",$array['NOM_FANTASI']);
      $array = array_map("utf8_decode", $row);
      unset($array['ORDERBY']);
      $array['Data_Cadastro'] = fnDataFull($array['Data_Cadastro']);
      $array['Data_Alteracao'] = fnDataFull($array['Data_Alteracao']);
      fputcsv($arquivo, $array, ';', '"');


      //echo "<pre>";
      //print_r($row);
      //echo "</pre>";
      $zero++;
    }
    fclose($arquivo);

    break;

  case 'abreDetail':


    $cod_cliente = fnLimpaCampoZero(@$_GET['cliente']);

    $sql4 = "
              SELECT 
              cl.*, 
              uni.NOM_FANTASI,
              USU.NOM_USUARIO,
              USU2.NOM_USUARIO AS USU_ALTERAC,
              CASE WHEN CL.COD_SEXOPES = 1 THEN 'Masculino'
              WHEN CL.COD_SEXOPES = 2 THEN 'Feminino'
              ELSE 'Indefinido' END DES_SEXOPES
              from log_alter_clientes cl
              LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND = cl.COD_UNIVEND
              LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = cl.COD_ATENDENTE
              LEFT JOIN USUARIOS USU2 ON USU2.COD_USUARIO = CL.COD_ALTERAC
              WHERE cl.cod_empresa = $cod_empresa
              AND cl.cod_cliente = $cod_cliente
              ORDER BY case when cl.DAT_ALTERAC IS NOT NULL then cl.DAT_ALTERAC ELSE cl.DAT_CADASTR END DESC 
              LIMIT 5";

    // fnEscreve($sql4);

    $arrayQuery4 = mysqli_query(connTemp($cod_empresa, ''), $sql4);

?>
    <tr style="background-color: #fff;" id="detail_<?= $cod_cliente ?>">

      <td colspan="12">
        <table class="table">

          <thead>
            <tr style="background-color: #fff;">
              <th class="{sorter:false}" width="3.05%"></th>
              <th class="{sorter:false}" width="6.01%">Código</th>
              <th class="{sorter:false}" width="14.20%">Nome do Cliente</th>
              <th class="{sorter:false}" width="6.87%"></th>
              <th class="{sorter:false}" width="7.08%">Data Alteração</th>
              <th class="{sorter:false}" width="7.08%">Usu. Alteração</th>
              <th class="{sorter:false}" width="8.37%">Loja</th>
              <th class="{sorter:false}" width="7.22%"></th>
              <th class="{sorter:false}" width="7.22%">Celular</th>
              <th class="{sorter:false}" width="6.11%">CEP</th>
              <th class="{sorter:false}" width="19.41%">e-Mail</th>
              <th class="{sorter:false}" width="26.05%">Senha</th>
              <th class="{sorter:false}" width="5.45%">Sexo</th>
              <th width="5.45%">Dat. Niver</th>
            </tr>
          </thead>

          <tbody>

            <?php

            while ($qrListaUnive4 = mysqli_fetch_assoc($arrayQuery4)) {

              $sexo = $qrListaUnive4['DES_SEXOPES'];
              $dat_niver = $qrListaUnive4['DAT_NASCIME'];

              if ($qrListaUnive4['DES_EMAILUS'] == "") {
                $email = "e-mail não cadastrado!";
              } else {
                $email = $qrListaUnive4['DES_EMAILUS'];
              }

              if ($qrListaUnive4['COD_UNIVEND'] != 0) {
                $unidade = $qrListaUnive4['NOM_FANTASI'];
              } else {
                $unidade = "Sem unidade";
              }

              if ($cod_empresa == 19) {
                $mostraPlaca = "<td class='text-center'><small>" . $qrListaUnive4['DES_PLACA'] . "</small></td>";
              } else {
                $mostraPlaca = "";
              }

            ?>

              <tr style="background-color: #fff;">
                <td><small></small></td>
                <td><small><?php echo $qrListaUnive4['COD_CLIENTE']; ?></small></td>
                <td><small><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($qrListaUnive4['COD_CLIENTE']); ?>" target="_blank"><?= $qrListaUnive4['NOM_CLIENTE']; ?></a></small></td>
                <td><small></small></td>
                <td><small><?php echo fnDataFull($qrListaUnive4['DAT_ALTERAC']); ?></small></td>
                <td><small><?php echo $qrListaUnive4['USU_ALTERAC']; ?></small></td>
                <td><small><?php echo $unidade; ?></small></td>
                <td><small></small></td>
                <td><small><?php echo $qrListaUnive4['NUM_CELULAR']; ?></small></td>
                <td><small><?php echo $qrListaUnive4['NUM_CEPOZOF']; ?></small></td>
                <td><small><?php echo $email; ?></small></td>
                <td><small><?php echo $qrListaUnive4['DES_SENHAUS']; ?></small></td>
                <td><small><?php echo $sexo; ?></small></td>
                <td><small><?php echo $dat_niver; ?></small></td>
              </tr>

            <?php
            }

            ?>
          </tbody>

        </table>

      </td>

    </tr>



    <?php

    break;

  case 'paginar':

    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

    //lista de clientes
    $sql = "SELECT CL.*,
                      USU.NOM_USUARIO,
                      USU2.NOM_USUARIO AS USU_ALTERAC,
                      uni.NOM_FANTASI,
                      CASE WHEN CL.COD_SEXOPES = 1 THEN 'Masculino'
                      WHEN CL.COD_SEXOPES = 2 THEN 'Feminino'
                      ELSE 'Indefinido' END DES_SEXOPES

              FROM CLIENTES CL
              LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_ATENDENTE
              LEFT JOIN USUARIOS USU2 ON USU2.COD_USUARIO = CL.COD_ALTERAC
              LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=CL.COD_UNIVEND
              WHERE CL.COD_EMPRESA = $cod_empresa
              AND CL.COD_CLIENTE IN (SELECT COD_CLIENTE FROM log_alter_clientes WHERE COD_EMPRESA = $cod_empresa)
                  $andUnidades
                  $andNome
                  $andPlaca
                  $andCartao
                  $andCpf
                  $andFuncionarios
                  $andInativos
                  $andDatIni
              ORDER BY CL.NOM_CLIENTE LIMIT $inicio,$itens_por_pagina";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
    // fnEscreve($sql);
    //  echo "___".$sql."___";
    $count = 0;
    while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

      $sexo = $qrListaEmpresas['DES_SEXOPES'];

      // fnEscreve($qrListaEmpresas['COD_SEXOPES']);

      $log_funciona = $qrListaEmpresas['LOG_FUNCIONA'];
      if ($log_funciona == "S") {
        $mostraCracha = '<i class="fa fa-address-card" aria-hidden="true"></i>';
      } else {
        $mostraCracha = "";
      }

      // if ($cod_empresa == 19) {
      //     $mostraPlaca = "<td class='text-center'><small>" . $qrListaEmpresas['DES_PLACA'] . "</small></td>";
      // } else {
      //     $mostraPlaca = "";
      // }
      if ($qrListaEmpresas['DES_EMAILUS'] == "") {
        $email = "e-mail não cadastrado!";
      } else {
        $email = $qrListaEmpresas['DES_EMAILUS'];
      }

      if ($qrListaEmpresas['COD_UNIVEND'] != 0) {
        $unidade = $qrListaEmpresas['NOM_FANTASI'];
      } else {
        $unidade = "Sem unidade";
      }
      $count++;

    ?>

      <tr id="bloco_<?php echo $qrListaEmpresas['COD_CLIENTE']; ?>">
        <td class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaEmpresas['COD_CLIENTE']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></td>
        <td><small><?php echo $qrListaEmpresas['COD_CLIENTE']; ?></small></td>
        <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($qrListaEmpresas['COD_CLIENTE']); ?>" target="_blank"><?= $qrListaEmpresas['NOM_CLIENTE']; ?></a></td>
        <td><small><?php echo fnDataFull($qrListaEmpresas['DAT_CADASTR']); ?></small></td>
        <td><small><?php echo fnDataFull($qrListaEmpresas['DAT_ALTERAC']); ?></small></td>
        <td><small><?php echo $qrListaEmpresas['USU_ALTERAC']; ?></small></td>
        <td><small><?php echo $unidade; ?></small></td>
        <td><small><?php echo $qrListaEmpresas['NUM_CGCECPF']; ?></small></td>
        <td><small><?php echo $qrListaEmpresas['NUM_CELULAR']; ?></small></td>
        <td><small><?php echo $qrListaEmpresas['NUM_CEPOZOF']; ?></small></td>
        <td><small><?php echo $email; ?></small></td>
        <td><small><?php echo $qrListaEmpresas['DES_SENHAUS']; ?></small></td>
        <td><small><?php echo $sexo; ?></small></td>
      </tr>

<?php
    }
}
