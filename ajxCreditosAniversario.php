<?php

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);
$lojasSelecionadas = $_POST['LOJAS'];

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT   b.COD_CLIENTE,
                        b.NOM_CLIENTE,
                        SUM(val_credito) AS val_credito,
                        SUM(val_saldo)val_saldo FROM creditosdebitos a,
                        clientes b WHERE a.cod_cliente=b.cod_cliente  
                        AND a.cod_empresa=b.cod_empresa  
                        AND a.cod_empresa=219  
                        AND a.cod_credlot>0  
                        AND a.val_saldo>0  
                        AND a.COD_UNIVEND IN($lojasSelecionadas)  
                        AND a.cod_empresa=219  
                        AND DATE(a.dat_expira) > DATE(NOW())
                        GROUP BY a.cod_cliente";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['val_credito'] = fnValor($row['val_credito'], 2);
            $row['val_saldo'] = fnValor($row['val_saldo'], 2);
            $limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            $textolimpo = json_decode($limpandostring, true);
            fputcsv($arquivo, $textolimpo, ';', '"');
        }
        fclose($arquivo);
}
