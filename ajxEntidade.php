<?php
include '_system/_functionsMain.php'; 
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$andFiltro = fnDecode($_POST['AND_FILTRO']);

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$andStatus = $_POST['AND_STATUS'];


if ($val_pesquisa != "") {
    $esconde = " ";
} else {
    $esconde = "display: none;";
}

switch ($opcao) {

    case 'retornar':

        $cod_agrupador = $_POST['COD_AGRUPADOR'];
        $cod_municipio = $_POST['COD_MUNICIPIO'];

        $sqlfiltro = "SELECT des_filtro FROM filtros_cliente
                                  WHERE cod_empresa=$cod_empresa AND 
                                  cod_tpfiltro=28 AND 
                                        cod_filtro IN(

                                  SELECT cod_regitra FROM entidade_grupo
                                  WHERE cod_empresa=$cod_empresa AND 
                                  cod_grupoent=$cod_agrupador
                                          )";   
        $resultadosql = mysqli_query(connTemp($cod_empresa, ''), $sqlfiltro);
        $resultset = mysqli_fetch_assoc($resultadosql);
        $resultado = $resultset['des_filtro'];
        ?>
        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_FILTRO" id="DES_FILTRO" value="<?= $resultado ?>">
        <?php
        break;
    case 'exportar':
        
        $nomeRel = $_GET['nomeRel'];
        $arquivo = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

        $writer = WriterFactory::create(Type::CSV);
        $writer->setFieldDelimiter(';');
        $writer->openToFile($arquivo); 
        
        $sql = "SELECT      
                    ENTIDADE.COD_ENTIDAD CODIGO,
                    ENTIDADE.NOM_ENTIDAD ENTIDADE,
                    ENTIDADE.DES_ENDERC ENDERECO,
                    ENTIDADE.NUM_ENDEREC NUMERO,
                    ENTIDADE.DES_BAIRROC BAIRRO,
                    ENTIDADE.NUM_CEPOZOF CEP,
                    ENTIDADE.NOM_CIDADES CIDADE,
                    ENTIDADE.NOM_ESTADOS ESTADO,
                    ENTIDADE.NOM_RESPON ENVOLVIDOS,
                    CONCAT(ENTIDADE.COD_MUNICIPIO, \";\" , ENTIDADE.COD_GRUPOENT) ASSOCIACAO,
                    REGIAO.DES_REGIAO REGIAO,
                    ENTIDADE_GRUPO.DES_GRUPOENT AGRUPADOR,
                    usuarios.NOM_USUARIO RESPONSAVEL,
                    ENTIDADE.NUM_TELEFONE TELEFONE,
                    ENTIDADE.NUM_CELULAR CELULAR,
                    ENTIDADE.EMAIL,
                    ENTIDADE.QTD_MEMBROS,
                    TIPOENTIDADE.DES_TPENTID TIPO
                from ENTIDADE  
                       left join ENTIDADE_GRUPO ON ENTIDADE_GRUPO.COD_GRUPOENT = ENTIDADE.COD_GRUPOENT
                       left join REGIAO ON REGIAO.COD_REGIAO = ENTIDADE.COD_REGIAO
                       left join ".$connAdm->DB.".usuarios ON ".$connAdm->DB.".usuarios.COD_USUARIO =  ENTIDADE.COD_USUARIO_RESP
                       left join ".$connAdm->DB.".empresas ON ENTIDADE.COD_EMPRESA = ".$connAdm->DB.".empresas.COD_EMPRESA 
                       left join ".$connAdm->DB.".tipoentidade ON entidade.COD_TPENTID = ".$connAdm->DB.".tipoentidade.COD_TPENTID 
               where ".$connAdm->DB.".empresas.COD_EMPRESA =  " .$cod_empresa." " .$andFiltro." ".$andCpf."
               AND LOG_STATUS = 'S' 
                order by COD_ENTIDAD";
        // echo($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

            $array = array();
            while($row = mysqli_fetch_assoc($arrayQuery)){
                $newRow = array();

                $cont = 0;
                foreach ($row as $objeto) {

                    if($cont == 9){

                        $buscaFiltro = explode(";", $objeto);

                        $cod_municipio = $buscaFiltro[0];
                        $cod_agrupador = $buscaFiltro[1];

                        $sqlfiltro = "SELECT des_filtro FROM filtros_cliente
                                                  WHERE cod_empresa=$cod_empresa AND 
                                                  cod_tpfiltro=28 AND 
                                                        cod_filtro IN(

                                                  SELECT cod_regitra FROM entidade_grupo
                                                  WHERE cod_empresa=$cod_empresa AND 
                                                  cod_grupoent=$cod_agrupador
                                                          )";   
                        $resultadosql = mysqli_query(connTemp($cod_empresa, ''), $sqlfiltro);
                        $resultset = mysqli_fetch_assoc($resultadosql);
                        $resultado = $resultset['des_filtro'];

                        array_push($newRow, $resultado);

                    }else{

                        array_push($newRow, $objeto);

                    }
                  

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
        left join webtools.empresas ON ENTIDADE.COD_EMPRESA = webtools.empresas.COD_EMPRESA
        where webtools.empresas.COD_EMPRESA = $cod_empresa
        $andStatus
        $andFiltro";
        //echo $sql;    
        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

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
                        TIPOENTIDADE.DES_TPENTID,
                        EMPRESAS.NOM_EMPRESA,
                        A.DES_GRUPOENT,
                        ENTIDADE.COD_USUARIO_RESP,
                        ENTIDADE.COD_REGIAO,
                        ENTIDADE.COD_CLIENTE_MULT,
                        ENTIDADE.LOG_STATUS
                from ENTIDADE  
                left join webtools.empresas ON ENTIDADE.COD_EMPRESA = webtools.empresas.COD_EMPRESA 
                left join webtools.tipoentidade ON entidade.COD_TPENTID = webtools.tipoentidade.COD_TPENTID
                left join entidade_grupo A ON A.COD_GRUPOENT = ENTIDADE.COD_GRUPOENT
                where webtools.empresas.COD_EMPRESA = $cod_empresa 
                $andFiltro
                $andStatus
                GROUP BY ENTIDADE.COD_ENTIDAD
                limit $inicio,$itens_por_pagina";

        // echo($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $count = 0;
        while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
            if (strlen($qrBuscaModulos['NUM_CGCECPF']) <= 11) {

                $retun = fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'], "F");
                //$retun = str_pad($qrBuscaModulos['NUM_CGCECPF'], 11, '0', STR_PAD_LEFT); // Resultado: 00009
            } else {
                $retun = fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'], "J");
            }

            // $municipio = "";
            $estado = "";

            // 	if($qrBuscaModulos[COD_MUNICIPIO] != ""){
            //  	$sqlCidade = "SELECT NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $qrBuscaModulos[COD_MUNICIPIO]";
            //  	$arrayMunicipio = mysqli_query(connTemp($cod_empresa,''),$sqlCidade);		
            //  	$qrMunicipio = mysqli_fetch_assoc($arrayMunicipio);
            //  	$municipio = $qrMunicipio[NOM_MUNICIPIO];
            // }
            

            if ($qrBuscaModulos[COD_ESTADO] != "") {

                $sqlEstado = "SELECT UF FROM ESTADO WHERE COD_ESTADO = $qrBuscaModulos[COD_ESTADO]";
                $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sqlEstado);
                $qrEstado = mysqli_fetch_assoc($arrayEstado);
                $estado = $qrEstado[UF];
            }

            $count++;
            echo "
        <tr>
        <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
        <td>" . $qrBuscaModulos['COD_ENTIDAD'] . "</td>
        <td>" . $qrBuscaModulos['NOM_ENTIDAD'] . "</td>
        <td>" . $qrBuscaModulos['NOM_RESPON'] . "</td>
        <td>" . $qrBuscaModulos['NOM_CIDADES'] . "</td>
        <td>" . $estado . "</td>    
        </tr>

        <input type='hidden' id='ret_COD_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['COD_ENTIDAD'] . "'>
        <input type='hidden' id='ret_COD_GRUPOENT_" . $count . "' value='" . $qrBuscaModulos['COD_GRUPOENT'] . "'>
        <input type='hidden' id='ret_COD_CLIENTE_MULT_" . $count . "' value='" . $qrBuscaModulos['COD_CLIENTE_MULT'] . "'>
        <input type='hidden' id='ret_NOM_CLIENTE_" . $count . "' value='" . $qrBuscaModulos['NOM_CLIENTE'] . "'>
        <input type='hidden' id='ret_COD_TPENTID_" . $count . "' value='" . $qrBuscaModulos['COD_TPENTID'] . "'>
        <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
        <input type='hidden' id='ret_des_filtro_" . $count . "' value='" . $resultset['des_filtro'] . "'>
        <input type='hidden' id='ret_NOM_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['NOM_ENTIDAD'] . "'>
        <input type='hidden' id='ret_LOG_STATUS_" . $count . "' value='" . $qrBuscaModulos['LOG_STATUS'] . "'>
        <input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrBuscaModulos['NUM_CGCECPF'] . "'>
        <input type='hidden' id='ret_DES_ENDERC_" . $count . "' value='" . $qrBuscaModulos['DES_ENDERC'] . "'>
        <input type='hidden' id='ret_NUM_ENDEREC_" . $count . "' value='" . $qrBuscaModulos['NUM_ENDEREC'] . "'>
        <input type='hidden' id='ret_DES_BAIRROC_" . $count . "' value='" . $qrBuscaModulos['DES_BAIRROC'] . "'>
        <input type='hidden' id='ret_NUM_CEPOZOF_" . $count . "' value='" . $qrBuscaModulos['NUM_CEPOZOF'] . "'>
        <input type='hidden' id='ret_COD_MUNICIPIO_" . $count . "' value='" . $qrBuscaModulos['COD_MUNICIPIO'] . "'>
        <input type='hidden' id='ret_NOM_CIDADES_" . $count . "' value='" . $qrBuscaModulos['NOM_CIDADES'] . "'>
        <input type='hidden' id='ret_COD_ESTADO_" . $count . "' value='" . $qrBuscaModulos['COD_ESTADO'] . "'>
        <input type='hidden' id='ret_NUM_TELEFONE_" . $count . "' value='" . $qrBuscaModulos['NUM_TELEFONE'] . "'>
        <input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrBuscaModulos['NUM_CELULAR'] . "'>
        <input type='hidden' id='ret_EMAIL_" . $count . "' value='" . $qrBuscaModulos['EMAIL'] . "'>
        <input type='hidden' id='ret_NOM_RESPON_" . $count . "' value='" . $qrBuscaModulos['NOM_RESPON'] . "'>
        <input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaModulos['COD_EXTERNO'] . "'>
        <input type='hidden' id='ret_QTD_MEMBROS_" . $count . "' value='" . $qrBuscaModulos['QTD_MEMBROS'] . "'>
        <input type='hidden' id='ret_COD_USUARIO_RESP_" . $count . "' value='" . $qrBuscaModulos['COD_USUARIO_RESP'] . "'>
        <input type='hidden' id='ret_COD_REGIAO_" . $count . "' value='" . $qrBuscaModulos['COD_REGIAO'] . "'>
        ";
        }

        break;
}
?>