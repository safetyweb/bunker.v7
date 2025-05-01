<?php 

include '_system/_functionsMain.php'; 

//------------------------------------------------------------------------------------------------------------------------------------
$sqlBuscaEmpresa = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas";
//fnEscreve($sqlBuscaEmpresa);
$queryEmpresa = mysqli_query($connAdm->connAdm(), $sqlBuscaEmpresa);
$cont = 0;
while ($qrBuscaEmpresa = mysqli_fetch_assoc($queryEmpresa)) {

    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    $dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
    //echo "<pre>";
    //print_r($cod_empresa);
    //echo "</pre>";
//--------------------------------------------------------------------------------------------------------------------------------
    $sql = "SELECT 
            SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=13 then PM.QTD_PRODUTO ELSE NULL END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=13 then PM.QTD_PRODUTO ELSE NULL END) QTD_PRODUTO_EMAIL,
            SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=21 then PM.QTD_PRODUTO ELSE NULL END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=21 then PM.QTD_PRODUTO ELSE NULL END) QTD_PRODUTO_SMS,
            SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=20 then PM.QTD_PRODUTO ELSE NULL END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=20 then PM.QTD_PRODUTO ELSE NULL END) QTD_PRODUTO_WHATSAPP,
            GROUP_CONCAT( distinct PM.TIP_LANCAMENTO) TIP_LANCAMENTO, 
            GROUP_CONCAT( distinct CC.DES_CANALCOM) DES_CANALCOM,
            GROUP_CONCAT( distinct CC.COD_CANALCOM) COD_CANALCOM,
            S.NOM_SEGMENT,
            A.COD_SISTEMAS		
            FROM PEDIDO_MARKA PM
            INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
            INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM
            INNER JOIN EMPRESAS A ON A.COD_EMPRESA = PM.COD_EMPRESA
            INNER JOIN SEGMENTOMARKA S ON S.COD_SEGMENT = A.COD_SEGMENT 
            WHERE PM.COD_ORCAMENTO > 0
            $andFiltro
            $andAtivo  
            AND PM.COD_EMPRESA=$cod_empresa 
            GROUP BY PM.COD_EMPRESA";

    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
    fnTesteSql($arrayQuery);
    echo $andAtivo;

    $ARRAY_SISTEMA1 = array(
        'sql' => "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS",
        'cod_empresa' => 0,
        'conntadm' => $connAdm->connAdm(),
        'IN' => 'N',
        'nomecampo' => '',
        'conntemp' => '',
        'SQLIN' => ""
    );
    $ARRAY_SISTEMA = fnUnivend($ARRAY_SISTEMA1);
    $count = 0;

    while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

        if ($qrListaEmpresas['QTD_PRODUTO_SMS'] == NULL || $qrListaEmpresas['QTD_PRODUTO_EMAIL'] == NULL) {
            $sms = 0;
            $email = 0;
        } else {
            $sms = $qrListaEmpresas['QTD_PRODUTO_SMS'];
            $email = $qrListaEmpresas['QTD_PRODUTO_EMAIL'];
        }
        if (!empty($qrListaEmpresas['COD_SISTEMAS'])) {

            $tem_sistema = "tem";

            $sistemas = explode(',', $qrListaEmpresas[COD_SISTEMAS]);

            $des_sistema = "";

            for ($i = 0; $i < count($sistemas); $i++) {

                $NOM_ARRAY_SISTEMAS = (array_search($sistemas[$i], array_column($ARRAY_SISTEMA, 'COD_SISTEMA')));
                $des_sistema .= $ARRAY_SISTEMA[$NOM_ARRAY_SISTEMAS]['DES_SISTEMA'] . ", ";
            }

            $des_sistema = rtrim(trim($des_sistema), ",");
        } else {
            $tem_sistema = "nao";
        }
    ?>
        <tr>
            <td><?= $qrBuscaEmpresa['COD_EMPRESA'] ?></td>
            <td><?= $qrBuscaEmpresa['NOM_FANTASI'] ?></td>
            <td><?= $qrListaEmpresas['NOM_SEGMENT'] ?></td>
            <td><?= $des_sistema ?></td>
            <td class="text-center"><?= $sms ?></td>
            <td class="text-center"><?= $email ?></td>
        </tr>

    <?php
        $count++;
    }   
}    
?>