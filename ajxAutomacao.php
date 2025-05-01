<?php
include '_system/_functionsMain.php';

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT LOG_USUARIO, DES_SENHAUS FROM usuarios WHERE COD_EMPRESA = $cod_empresa AND COD_TPUSUARIO = 10";
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
        $qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
        $log_usuario = $qrBuscaUsuario['LOG_USUARIO'];
        $des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];

        $fileName = $arquivoCaminho;
        // Cria o arquivo CSV e abre para escrita
        $file = fopen($fileName, 'w');

        // Adiciona cabeçalho no CSV
        $header = ['ID_LOJA', 'CNPJ', 'NOMA_UNIDADE', 'ID_EMPRESA', 'LOGIN', 'SENHA', 'TOKEN'];
        fputcsv($file, $header);

        // Busca as unidades de venda
        $sqlBusca = "SELECT COD_UNIVEND, NOM_FANTASI, NUM_CGCECPF FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa";
        $query = mysqli_query($connAdm->connAdm(), $sqlBusca);
        $webhook = 'webhook';

        // Preenche o CSV com os dados das unidades
        while ($row = mysqli_fetch_assoc($query)) {
            $codUnivend = $row['COD_UNIVEND'];
            $nomeFantasia = $row['NOM_FANTASI'];
            $numCnpj = fnformatCnpjCpf($row['NUM_CGCECPF']);

            //MONTA BASE64
            $usuarioEncode = $log_usuario . ';' . fnDecode($des_senhaus) . ';' . fnDecode($codUnivend) . ';' . $webhook . ';' . fnDecode($cod_empresa);
            $autoriz = base64_encode(fnEncode($usuarioEncode));

            // Dados para cada linha do CSV
            $data = [$codUnivend, $numCnpj, $nomeFantasia, $cod_empresa, $log_usuario, fnDecode($des_senhaus), $autoriz];
            fputcsv($file, $data);
        }

        // Fecha o arquivo CSV
        fclose($file);

        break;

    case 'paginar':

        // Filtro por Grupo de Lojas
        include "../filtroGrupoLojas.php";

        $sql = "SELECT 1
        FROM (SELECT 
            MAX(B.COD_VENDA) COD_VENDA,
            A.COD_CLIENTE
            FROM clientes A
            inner JOIN creditosdebitos B ON A.COD_CLIENTE = B.COD_CLIENTE AND A.COD_EMPRESA=B.COD_EMPRESA
            WHERE A.COD_EMPRESA = $cod_empresa
            AND A.COD_UNIVEND IN($lojasSelecionadas)
            AND A.DIA BETWEEN DAY('$dat_ini') AND DAY('$dat_fim')
            AND A.MES BETWEEN MONTH('$dat_ini') AND MONTH('$dat_fim')
            GROUP BY A.COD_CLIENTE
            ORDER BY A.NOM_CLIENTE
            )tmpaniver
        INNER JOIN vendas VEN ON VEN.COD_VENDA=tmpaniver.COD_VENDA        
        GROUP BY tmpaniver.COD_CLIENTE";
        // fnEscreve($sql);

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT
        tmpaniver.COD_CLIENTE, 
        NOM_CLIENTE,
        NOM_FANTASI,
        usu.NOM_USUARIO,
        DAT_ULTCOMPR,
        tmpaniver.NUM_CELULAR,
        tmpaniver.DES_EMAILUS,
        tmpaniver.DAT_NASCIME,
        CREDITO_DISPONIVEL_GERAL,
        cat.NOM_FAIXACAT
        FROM (SELECT 
            max(B.COD_CREDITO) COD_CREDITO,
            MAX(B.COD_VENDA) COD_VENDA,
            A.COD_CLIENTE, 
            A.NOM_CLIENTE,
            uni.NOM_FANTASI,
            max(B.COD_VENDEDOR) COD_VENDEDOR ,
            A.DAT_ULTCOMPR,
            A.NUM_CELULAR,
            A.DES_EMAILUS,
            A.DAT_NASCIME,
            A.COD_CATEGORIA,
            (SELECT ifnull(SUM(AA.VAL_SALDO),0)
                FROM CREDITOSDEBITOS AA,empresas c
                WHERE AA.COD_CLIENTE=A.COD_CLIENTE 
                AND C.COD_EMPRESA=AA.COD_EMPRESA 
                AND AA.TIP_CREDITO='C' 
                AND AA.COD_STATUSCRED=1
                AND AA.tip_campanha = c.TIP_CAMPANHA 
                AND (DATE(AA.DAT_EXPIRA) >= CURDATE() or(AA.LOG_EXPIRA='N'))
                )AS CREDITO_DISPONIVEL_GERAL
            FROM clientes A
            LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND = A.COD_UNIVEND AND A.COD_EMPRESA=uni.COD_EMPRESA
            inner JOIN creditosdebitos B ON A.COD_CLIENTE = B.COD_CLIENTE AND A.COD_EMPRESA=B.COD_EMPRESA
            WHERE A.COD_EMPRESA = $cod_empresa
            AND A.COD_UNIVEND IN($lojasSelecionadas)
            AND A.DIA BETWEEN DAY('$dat_ini') AND DAY('$dat_fim')
            AND A.MES BETWEEN MONTH('$dat_ini') AND MONTH('$dat_fim')
            GROUP BY A.COD_CLIENTE
            ORDER BY A.NOM_CLIENTE
            )tmpaniver
        INNER JOIN vendas VEN ON VEN.COD_VENDA=tmpaniver. COD_VENDA
        left join usuarios usu ON VEN.COD_VENDEDOR = usu.cod_usuario
        LEFT JOIN categoria_cliente cat ON tmpaniver.COD_CATEGORIA = cat.COD_CATEGORIA
        GROUP BY COD_CLIENTE
        LIMIT $inicio, $itens_por_pagina
        ";

        // fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $count = 0;
        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

?>
            <tr>
                <td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?= $qrListaVendas['NOM_CLIENTE']; ?></a></td>
                <td><?= $qrListaVendas['NOM_FAIXACAT']; ?></td>
                <td><?= $qrListaVendas['NOM_FANTASI']; ?></td>
                <td><?= $qrListaVendas['NOM_USUARIO']; ?></td>
                <td><?= fnDataFull($qrListaVendas['DAT_ULTCOMPR']); ?></td>
                <td><?= fnmasktelefone($qrListaVendas['NUM_CELULAR']); ?></td>
                <td><?= $qrListaVendas['DES_EMAILUS']; ?></td>
                <td><?= $qrListaVendas['DAT_NASCIME']; ?></td>
                <td>R$ <?= fnValor($qrListaVendas['CREDITO_DISPONIVEL_GERAL'], 2); ?></td>
            </tr>
<?php

            $count++;
        }
        break;
}
