<?php

include '../_system/_functionsMain.php'; 	
//echo fnDebug('true');

// definir o numero de itens por pagina
$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];  
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);   

$cod_univend = $_POST['COD_UNIVEND'];
$lojasSelecionadas = $_POST['LOJAS'];

$cod_cliente = fnLimpacampo($_REQUEST['COD_CLIENTE']);
$cod_externo = fnLimpacampozero($_REQUEST['COD_EXTERNO']);
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
    $dat_ini = ""; 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
    $dat_fim = "";
}


if ($cod_cliente != 0) {
    $andCliente = "AND CL.COD_CLIENTE = $cod_cliente";
} else {
    $andCliente = '';
}

if ($dat_ini == "") {
    $andDatIni = "";
} else {
    $andDatIni = "AND DATE_FORMAT(VL.DAT_EXCLUSA, '%Y-%m-%d') >= '$dat_ini' ";
}

if ($dat_fim == "") {
    $andDatFim = "";
} else {
    $andDatFim = "AND DATE_FORMAT(VL.DAT_EXCLUSA, '%Y-%m-%d') <= '$dat_fim' ";
}

if($num_cpf == ""){
    $andCpf = "";
}else{
    $andCpf = "AND CL.NUM_CGCECPF='$num_cpf'";
}

switch ($opcao) {

    case  'exportar' :

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
    
               
        $sql = "SELECT CL.COD_CLIENTE,CL.NOM_CLIENTE,CL.NUM_CGCECPF,UNI.NOM_FANTASI,VL.* FROM clientes CL
                INNER JOIN veiculos_exec  VL ON VL.COD_CLIENTE_EXT=CL.NUM_CGCECPF
                LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
                WHERE CL.COD_EMPRESA=$cod_empresa
                $andCpf
                $andCliente
                $andExterno
                $andDatIni
                $andDatFim";
                  
                
        fnEscreve($sql);
                
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

        $arquivo = fopen($arquivoCaminho, 'w',0);
                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
			while ($row=mysqli_fetch_assoc($arrayQuery)){
                                
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                //$textolimpo = json_decode($limpandostring, true);
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');	
                
                
			}
			fclose($arquivo);
/*
        $array = array();

        while($row = mysqli_fetch_assoc($arrayQuery)){

            $newRow = array();
              
            $cont = 0;

            foreach ($row as $objeto) {   

                if($cont == 5){

                    switch ($objeto) {

                        case 2:
                            $canal = 'Hotsite';
                        break;

                        case 3:
                            $canal = 'Totem';
                        break;
                        
                        default:
                            $canal = 'Bunker';
                        break;

                    }

                    array_push($newRow, $canal);

                }else if($cont == 7){

                    $usuario = $objeto;

                    switch ($row['COD_CANAL']) {

                        case 2:
                            $usuario = 'Hotsite';
                        break;

                        case 3:
                            $usuario = 'Totem';
                        break;

                    }

                    array_push($newRow, $usuario);

                }else if($cont == 8){

                    array_push($newRow, fnValor($objeto,2));

                }else{               

                    array_push($newRow, $objeto);

                }

                $cont++;

            }                                                       

            $array[] = $newRow;

        }
        
        $arrayColumnsNames = array();

        while($row = mysqli_fetch_field($arrayQuery)){

            array_push($arrayColumnsNames, $row->name);

        }           

        $writer->addRow($arrayColumnsNames);
        $writer->addRows($array);
        $writer->close();
*/
    break;

    case  'paginar' :        

        //paginação
        $sql = "SELECT 1 FROM clientes CL
        LEFT JOIN veiculos_exec  VL ON VL.COD_CLIENTE_EXT=CL.NUM_CGCECPF
        LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = CL.COD.UNIVEND
        LEFT JOIN USUARIOS US ON US.COD_USUARIO = CL.COD_CLIENTE
        WHERE CL.COD_EMPRESA=$cod_empresa
        $andCpf
        $andCliente
        $andExterno
        $andDatIni
        $andDatFim";

$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
$total_itens_por_pagina = mysqli_num_rows($retorno);
//fnEscreve($sql);
$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;                                             


$sql = "SELECT CL.COD_CLIENTE,CL.NOM_CLIENTE,CL.NUM_CGCECPF,UNI.NOM_FANTASI,VL.* FROM clientes CL
        INNER JOIN veiculos_exec  VL ON VL.COD_CLIENTE_EXT=CL.NUM_CGCECPF
        LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
        WHERE CL.COD_EMPRESA=$cod_empresa
        $andCpf
        $andCliente
        $andExterno
        $andDatIni
        $andDatFim 
        LIMIT $inicio,$itens_por_pagina";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
//fnEscreve($sql);
$count = 0;
while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

    $count++;

    echo"
        <tr>
            <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
            <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF'])  . "</small></td>
            <td><small>" . fnDataFull($qrListaEmpresas['DAT_EXCLUSA']) . "</small></td>
            <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
            <td> <small>" . $qrListaEmpresas['DES_PLACA'] . "</small></td>
        </tr>";

}

    break;

}
