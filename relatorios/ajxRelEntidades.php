r<?php
include '../_system/_functionsMain.php'; 
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$nom_entidad = fnLimpacampo($_REQUEST['NOM_ENTIDAD']);
$nom_respon = fnLimpacampo($_REQUEST['NOM_RESPON']);
$nom_municipio = fnLimpacampo($_REQUEST['NOM_MUNICIPIO']);
$andFiltro = fnLimpaCampo($_REQUEST['AND_FILTROS']);


$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));


if($nom_entidad != ""){
  $andEntidad = "AND ENTIDADE.NOM_ENTIDAD LIKE '%$nom_entidad%'";
}else{
  $andEntidad = "";
}

if($nom_municipio != ""){
  $andMunicipio = "AND ENTIDADE.NOM_CIDADES LIKE '%$nom_municipio%'";
}else{
  $andMunicipio = "";
}

if($nom_respon != ""){
  $andRespon = "AND ENTIDADE.NOM_RESPON LIKE '%$nom_respon%'";
}else{
  $andRespon = "";
}

switch ($opcao) {

    case 'exportar':
        
        $nomeRel = $_GET['nomeRel'];
        $arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

        $writer = WriterFactory::create(Type::CSV);
        $writer->setFieldDelimiter(';');
        $writer->openToFile($arquivo); 
        
        $sql = "SELECT  ENTIDADE.NOM_ENTIDAD, 
                        ENTIDADE.NUM_CGCECPF, 
                        ENTIDADE.DES_ENDERC, 
                        ENTIDADE.NUM_ENDEREC, 
                        ENTIDADE.DES_BAIRROC, 
                        ENTIDADE.NUM_CEPOZOF, 
                        ENTIDADE.NOM_CIDADES, 
                        ENTIDADE.NOM_ESTADOS, 
                        ENTIDADE.NUM_TELEFONE, 
                        ENTIDADE.NUM_CELULAR, 
                        ENTIDADE.EMAIL, 
                        ENTIDADE.NOM_RESPON, 
                        ENTIDADE.QTD_MEMBROS,
                        B.DES_FILTRO REGIAO
                        FROM ENTIDADE 
                        INNER  JOIN entidade_grupo  A ON A.COD_GRUPOENT=ENTIDADE.COD_GRUPOENT AND A.COD_EMPRESA=ENTIDADE.COD_EMPRESA
                        INNER JOIN FILTROS_CLIENTE B ON B.COD_FILTRO=A.COD_REGITRA AND B.COD_EMPRESA=A.COD_EMPRESA
                        $andFiltro
                        $andRespon 
                        $andMunicipio
                        $andEntidad
                        WHERE ENTIDADE.COD_EMPRESA = $cod_empresa
                        ORDER BY ENTIDADE.NOM_ENTIDAD";
        echo $sql;
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

            $array = array();
            while($row = mysqli_fetch_assoc($arrayQuery)){
                $newRow = array();

                $cont = 0;
                foreach ($row as $objeto) {
                  
                  array_push($newRow, $objeto);

                  $cont++;
                }
              $array[] = $newRow;
            }

            $arrayColumnsNames = array();
            while($row = mysqli_fetch_field($arrayQuery))
            {
                array_push($arrayColumnsNames, $row->name);
            }			

            $writer->addRow($arrayColumnsNames);
            $writer->addRows($array);

            $writer->close();
        break;
    case 'paginar':
                            $sql = "SELECT 1 from ENTIDADE 
                                INNER  JOIN entidade_grupo  A ON A.COD_GRUPOENT=ENTIDADE.COD_GRUPOENT AND A.COD_EMPRESA=ENTIDADE.COD_EMPRESA
                                INNER JOIN FILTROS_CLIENTE B ON B.COD_FILTRO=A.COD_REGITRA AND B.COD_EMPRESA=A.COD_EMPRESA
                                where ENTIDADE.COD_EMPRESA = $cod_empresa
                                $andEntidad
                                $andMunicipio
                                $andRespon
                                $andFiltro";
                          //echo $sql;    
                          $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
                          $totalitens_por_pagina = mysqli_num_rows($retorno);
                          $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                          // fnEscreve($numPaginas);

                          $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                          
                          
                          
                          $sql = "SELECT ENTIDADE.COD_ENTIDAD, 
                                                ENTIDADE.COD_GRUPOENT, 
                                                ENTIDADE.COD_TPENTID, 
                                                ENTIDADE.COD_EXTERNO, 
                                                ENTIDADE.COD_EMPRESA, 
                                                ENTIDADE.COD_MUNICIPIO, 
                                                ENTIDADE.COD_ESTADO, 
                                                ENTIDADE.NOM_ENTIDAD, 
                                                ENTIDADE.NUM_CGCECPF, 
                                                ENTIDADE.DES_ENDERC, 
                                                ENTIDADE.NUM_ENDEREC, 
                                                ENTIDADE.DES_BAIRROC, 
                                                ENTIDADE.NUM_CEPOZOF, 
                                                ENTIDADE.NOM_CIDADES, 
                                                ENTIDADE.NOM_ESTADOS, 
                                                ENTIDADE.NUM_TELEFONE, 
                                                ENTIDADE.NUM_CELULAR, 
                                                ENTIDADE.EMAIL, 
                                                ENTIDADE.NOM_RESPON, 
                                                ENTIDADE.QTD_MEMBROS,
                                                B.COD_FILTRO,
                                                B.DES_FILTRO
                                FROM ENTIDADE 
                                INNER  JOIN entidade_grupo  A ON A.COD_GRUPOENT=ENTIDADE.COD_GRUPOENT AND A.COD_EMPRESA=ENTIDADE.COD_EMPRESA
                                INNER JOIN FILTROS_CLIENTE B ON B.COD_FILTRO=A.COD_REGITRA AND B.COD_EMPRESA=A.COD_EMPRESA
                                $andFiltro
                                $andRespon 
                                $andMunicipio
                                $andEntidad
                                WHERE ENTIDADE.COD_EMPRESA = $cod_empresa
                                ORDER BY ENTIDADE.NOM_ENTIDAD
                                LIMIT $inicio,$itens_por_pagina";
                          
                              
                          $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                           echo($sql);

                          $count=0;
                           while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                              // $municipio = "";
                              $estado = "";

                              //  if($qrBuscaModulos[COD_MUNICIPIO] != ""){
                              //    $sqlCidade = "SELECT NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $qrBuscaModulos[COD_MUNICIPIO]";
                              //    $arrayMunicipio = mysqli_query(connTemp($cod_empresa,''),$sqlCidade);   
                              //    $qrMunicipio = mysqli_fetch_assoc($arrayMunicipio);
                              //    $municipio = $qrMunicipio[NOM_MUNICIPIO];
                              // }

                              if ($qrBuscaModulos[COD_ESTADO] != "") {

                                  $sqlEstado = "SELECT UF FROM ESTADO WHERE COD_ESTADO = $qrBuscaModulos[COD_ESTADO]";
                                  $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sqlEstado);
                                  $qrEstado = mysqli_fetch_assoc($arrayEstado);
                                  $estado = $qrEstado[UF];
                              }

                              $count++;
                            ?>
                                  <tr>
                                    <td><input type='radio' name='radio1' onclick='retornaForm(<?=$count;?>)'></td>
                                    <td><?=$qrBuscaModulos['COD_ENTIDAD']?></td>
                                    <td><a href="action.do?mod=<?=fnEncode(1075)?>&id=<?=fnEncode($cod_empresa)?>&idE=<?=fnEncode($qrBuscaModulos['COD_ENTIDAD'])?>" class="f14" target="_blank"><?=$qrBuscaModulos['NOM_ENTIDAD']?></td>
                                    <td><?=$qrBuscaModulos['NOM_RESPON']?></td>
                                    <td><?=$qrBuscaModulos['NOM_CIDADES']?></td>
                                    <td><?=$qrBuscaModulos['DES_FILTRO']?></td>
                                    <td><?=$estado?></td>    
                                  </tr>

                                  <input type='hidden' id='ret_COD_ENTIDAD_<?=$count?>' value='<?=$qrBuscaModulos['COD_ENTIDAD']?>'>
                                  <input type='hidden' id='ret_COD_GRUPOENT_<?=$count?>' value='<?=$qrBuscaModulos['COD_GRUPOENT']?>'>
                                  <input type='hidden' id='ret_COD_TPENTID_<?=$count?>' value='<?=$qrBuscaModulos['COD_TPENTID']?>'>
                                  <input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value='<?=$qrBuscaModulos['COD_EMPRESA']?>'>
                                  <input type='hidden' id='ret_des_filtro_<?=$count?>' value='<?=$resultset['des_filtro']?>'>
                                  <input type='hidden' id='ret_NOM_ENTIDAD_<?=$count?>' value='<?=$qrBuscaModulos['NOM_ENTIDAD']?>'>
                                  <input type='hidden' id='ret_COD_FILTRO_<?=$count?>' value='<?=$qrBuscaModulos['COD_FILTRO']?>'>
                                  <input type='hidden' id='ret_NUM_CGCECPF_<?=$count?>' value='<?=$qrBuscaModulos['NUM_CGCECPF']?>'>
                                  <input type='hidden' id='ret_DES_ENDERC_<?=$count?>' value='<?=$qrBuscaModulos['DES_ENDERC']?>'>
                                  <input type='hidden' id='ret_NUM_ENDEREC_<?=$count?>' value='<?=$qrBuscaModulos['NUM_ENDEREC']?>'>
                                  <input type='hidden' id='ret_DES_BAIRROC_<?=$count?>' value='<?=$qrBuscaModulos['DES_BAIRROC']?>'>
                                  <input type='hidden' id='ret_NUM_CEPOZOF_<?=$count?>' value='<?=$qrBuscaModulos['NUM_CEPOZOF']?>'>
                                  <input type='hidden' id='ret_COD_MUNICIPIO_<?=$count?>' value='<?=$qrBuscaModulos['COD_MUNICIPIO']?>'>
                                  <input type='hidden' id='ret_NOM_CIDADES_<?=$count?>' value='<?=$qrBuscaModulos['NOM_CIDADES']?>'>
                                  <input type='hidden' id='ret_COD_ESTADO_<?=$count?>' value='<?=$qrBuscaModulos['COD_ESTADO']?>'>
                                  <input type='hidden' id='ret_NUM_TELEFONE_<?=$count?>' value='<?=$qrBuscaModulos['NUM_TELEFONE']?>'>
                                  <input type='hidden' id='ret_NUM_CELULAR_<?=$count?>' value='<?=$qrBuscaModulos['NUM_CELULAR']?>'>
                                  <input type='hidden' id='ret_EMAIL_<?=$count?>' value='<?=$qrBuscaModulos['EMAIL']?>'>
                                  <input type='hidden' id='ret_NOM_RESPON_<?=$count?>' value='<?=$qrBuscaModulos['NOM_RESPON']?>'>
                                  <input type='hidden' id='ret_COD_EXTERNO_<?=$count?>' value='<?=$qrBuscaModulos['COD_EXTERNO']?>'>
                                  <input type='hidden' id='ret_QTD_MEMBROS_<?=$count?>' value='<?=$qrBuscaModulos['QTD_MEMBROS']?>'>
                      <?php 
                          }

        break;
}
?>