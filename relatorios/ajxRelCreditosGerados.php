<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$mostraXml = @$_GET['mostrarXML'];
$cod_empresa = fnDecode(@$_GET['id']);
$casasDec = @$_REQUEST['CASAS_DEC'];
$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$num_cgcecpf = @$_POST['NUM_CGCECPF'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
      $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
      $dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
      $cod_univend = "9999";
}
//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
      $temUnivend = "N";
} else {
      $temUnivend = "S";
}

switch ($opcao) {
      case 'exportar':

            $nomeRel = $_GET['nomeRel'];
            $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

            if ($num_cgcecpf == "") {
                  $andCpf = " ";
            } else {
                  $andCpf = "NUM_CGCECPF = $num_cgcecpf AND ";
            }

            if ($cod_vendapdv == "") {
                  $andVendaPDV = " ";
            } else {
                  $andVendaPDV = "COD_PDV = '" . $cod_vendapdv . "' AND ";
            }

            $sql = "SELECT A.DAT_CADASTR,
                    uni.NOM_FANTASI AS LOJA,
					B.NOM_CLIENTE AS CLIENTE, 
					B.NUM_CGCECPF AS CPF, 
					A.COD_USUCADA AS COD_USUARIO, 
					D.NOM_USUARIO AS USUARIO, 
					A.VAL_CREDITO,
					VENINFO.DES_COMENTA AS COMENTARIO
					FROM  CREDITOSDEBITOS A
					left join CLIENTES B on A.COD_CLIENTE=B.COD_CLIENTE 
					LEFT JOIN WEBTOOLS.usuarios D ON A.COD_USUCADA=D.COD_USUARIO 
					LEFT JOIN venda_info VENINFO ON VENINFO.COD_VENDA=A.COD_CREDITO AND VENINFO.DES_TIPO=2
                    LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
					WHERE A.TIP_PONTUACAO ='AVU' AND 
					      A.VAL_CREDITO > 0 AND 
					DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' AND
					$andCpf
					A.COD_UNIVEND IN($lojasSelecionadas) AND
					A.cod_statuscred in(0,1,2,3,4,5,7,8,9) AND
					A.COD_EMPRESA=$cod_empresa
					ORDER BY A.DAT_CADASTR DESC";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

            $arquivo = fopen($arquivoCaminho, 'w', 0);

            while ($headers = mysqli_fetch_field($arrayQuery)) {
                  $CABECHALHO[] = $headers->name;
            }
            fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

            while ($row = mysqli_fetch_assoc($arrayQuery)) {

                  $row['VAL_CREDITO'] = fnValor($row['VAL_CREDITO'], 2);
                  //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                  //$textolimpo = json_decode($limpandostring, true);
                  $array = array_map("utf8_decode", $row);
                  fputcsv($arquivo, $array, ';', '"', '\n');
            }
            fclose($arquivo);

            break;
      case 'paginar':

            /*$ARRAY_UNIDADE1=array(
                                                'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa =0",
                                                'cod_empresa'=>$cod_empresa,
                                                'conntadm'=>$connAdm->connAdm(),
                                                'IN'=>'N',
                                                'nomecampo'=>'',
                                                'conntemp'=>'',
                                                'SQLIN'=> ""   
                                                );
                     $ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
                      * 
                      */

            if ($num_cgcecpf == "") {
                  $andCpf = " ";
            } else {
                  $andCpf = "B.NUM_CGCECPF = $num_cgcecpf AND ";
            }


            //fnEscreve(date('Y-m-d'));	
            //fnEscreve($dat_fim);

            $sql = "SELECT count(*) as contador from CREDITOSDEBITOS A
                                     left join CLIENTES B on A.COD_CLIENTE=B.COD_CLIENTE 
                                     LEFT JOIN WEBTOOLS.usuarios D ON A.COD_USUCADA=D.COD_USUARIO 
                                     WHERE A.TIP_PONTUACAO ='AVU' AND 
                                           A.VAL_CREDITO > 0 AND
                                     DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
                                     AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' AND
                                     $andCpf
                                     A.COD_UNIVEND IN($lojasSelecionadas) AND
                                     A.cod_statuscred in(0,1,2,3,4,5,7,8,9) AND
                                     A.COD_EMPRESA=$cod_empresa
                                     ";

            //fnEscreve($sql);

            $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
            $totalitens_por_pagina = mysqli_fetch_assoc($retorno);
            $numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

            //variavel para calcular o início da visualização com base na página atual
            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


            $sql2 = "SELECT A.DAT_CADASTR, 
                                     A.COD_UNIVEND,
                                     uni.NOM_FANTASI,
                                     B.NOM_CLIENTE, 
                                     B.NUM_CGCECPF, 
                                     A.COD_USUCADA, 
                                     D.NOM_USUARIO, 
                                     A.VAL_CREDITO,
                                     VENINFO.DES_COMENTA
                                     FROM  CREDITOSDEBITOS A
                                     left join CLIENTES B on A.COD_CLIENTE=B.COD_CLIENTE 
                                     LEFT JOIN WEBTOOLS.usuarios D ON A.COD_USUCADA=D.COD_USUARIO 
                                     LEFT JOIN venda_info VENINFO ON VENINFO.COD_VENDA=A.COD_CREDITO AND VENINFO.DES_TIPO=2
                                       LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                                     WHERE A.TIP_PONTUACAO ='AVU' AND 
                                           A.VAL_CREDITO > 0 AND 
                                     DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
                                     AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' AND
                                     $andCpf
                                     A.COD_UNIVEND IN($lojasSelecionadas) AND
                                     A.cod_statuscred in(0,1,2,3,4,5,7,8,9) AND
                                     A.COD_EMPRESA=$cod_empresa
                                     ORDER BY A.DAT_CADASTR DESC
                                     limit $inicio,$itens_por_pagina
                                     ";

            //fnEscreve($sql2);	

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);

            $countLinha = 1;
            while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
                  /*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                                              * 
                                              */

?>
                  <tr>
                        <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
                        <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
                        <td><small><?php echo fnMascaraCampo($qrListaVendas['NOM_CLIENTE']); ?></small></td>
                        <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
                        <td><small><?php echo $qrListaVendas['COD_USUCADA']; ?></small></td>
                        <td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
                        <td><small>R$ <?php echo fnValor($qrListaVendas['VAL_CREDITO'], $casasDec); ?></small></td>
                        <td><small><?php echo $qrListaVendas['DES_COMENTA']; ?></small></td>
                  </tr>
<?php

                  $countLinha++;
            }

            break;
}
?>