<?php

include '../_system/_functionsMain.php';

// fnDebug('true');

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);
$univend = fnDecode($_GET['unv']);
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];

$lojasSelecionadas = $_POST['LOJAS'];


switch ($opcao) {

  case 'agrupado':

  $nomeRel = $_GET['nomeRel'];
  $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

  $sql = "SELECT 
  CL.COD_VENDEDOR,
  USU.NOM_USUARIO,
  UNV.NOM_FANTASI,
  CL.NUM_CELULAR,
  COUNT(*) AS QTD_REPETICOES
  FROM 
  clientes AS CL
  INNER JOIN unidadevenda AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
  LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
  WHERE 
  CL.COD_EMPRESA = $cod_empresa
  AND CL.COD_UNIVEND IN($lojasSelecionadas)
  GROUP BY
  CL.NUM_CELULAR,
  CL.COD_VENDEDOR
  HAVING 
  COUNT(*) > 2
  ORDER BY 
  QTD_REPETICOES DESC";

  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

  $arquivo = fopen($arquivoCaminho, 'w', 0);

  $CABECHALHO = ['Cód. Vendedor', 'Nome', 'Unidade', 'Núm. Celular', 'Qtd. Repetições'];
  $CABECHALHO = array_map('utf8_decode', $CABECHALHO);
  fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

  while ($row = mysqli_fetch_assoc($arrayQuery)) {
    $array = array_map("utf8_decode", $row);
    fputcsv($arquivo, $array, ';', '"', '\n');

  }
  fclose($arquivo);

  break;

  case 'detalhado':

  $nomeRel = $_GET['nomeRel'];
  $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

  $sql = "SELECT CL.COD_CLIENTE,CL.NOM_CLIENTE,CL.NUM_CGCECPF,CL.NUM_CELULAR, USU.NOM_USUARIO
FROM clientes AS CL
LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
WHERE CL.cod_empresa = $cod_empresa
AND CL.num_celular IN (
    SELECT num_celular
    FROM clientes
    WHERE cod_empresa = $cod_empresa
    GROUP BY num_celular
    HAVING COUNT(*) > 2
)
ORDER BY CL.num_celular";
  
  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

  $arquivo = fopen($arquivoCaminho, 'w', 0);

  $CABECHALHO = ['Cód Cliente', 'Nome CLiente', 'CPF', 'Número Celular', 'Atendente'];
  $CABECHALHO = array_map('utf8_decode', $CABECHALHO);
  fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

  while ($row = mysqli_fetch_assoc($arrayQuery)) {
    $array = array_map("utf8_decode", $row);
    fputcsv($arquivo, $array, ';', '"', '\n');

  }
  fclose($arquivo);

  break;

  case 'paginar':

  include "filtroGrupoLojas.php";

  $sql = "SELECT 1
  FROM 
  clientes AS CL
  INNER JOIN unidadevenda AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
  LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
  WHERE 
  CL.COD_EMPRESA = $cod_empresa
  AND CL.COD_UNIVEND IN($lojasSelecionadas)
  GROUP BY
  CL.NUM_CELULAR,
  CL.COD_VENDEDOR
  HAVING 
  COUNT(*) > 2
  ORDER BY 
  QTD_REPETICOES DESC";

  $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
  $totalitens_por_pagina = mysqli_num_rows($retorno);

  $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

  $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

  $sql = "
  SELECT 
  CL.NUM_CELULAR,
  CL.COD_VENDEDOR,
  USU.NOM_USUARIO,
  UNV.NOM_FANTASI,
  COUNT(*) AS QTD_REPETICOES
  FROM 
  clientes AS CL
  INNER JOIN unidadevenda AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
  LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
  WHERE 
  CL.COD_EMPRESA = $cod_empresa
  AND CL.COD_UNIVEND IN($lojasSelecionadas)
  GROUP BY
  CL.NUM_CELULAR,
  CL.COD_VENDEDOR
  HAVING 
  COUNT(*) > 2
  ORDER BY 
  QTD_REPETICOES DESC
  LIMIT $inicio, $itens_por_pagina
  ";

  $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

  $count = 0;
  while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

    ?>  
    <tr>
      <td><?= $qrListaVendas['COD_VENDEDOR']; ?></td>
      <td><?= $qrListaVendas['NOM_USUARIO']; ?></td>
      <td><?= $qrListaVendas['NOM_FANTASI']; ?></td>
      <td><?= fnmasktelefone($qrListaVendas['NUM_CELULAR']); ?></td>
      <td><?= $qrListaVendas['QTD_REPETICOES']; ?></td>
    </tr>
    <?php

    $count++;    
  }

  break;

}

?>