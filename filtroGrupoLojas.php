<?php

// $cod_grupotr = array();
// $cod_univend = array();
// $cod_tiporeg = array();
// $cod_univend_master = array();
// $cod_tiporeg_master = array();
$lista_cod_grupotr = "";
$lista_cod_tiporeg = "";
$grupoRegiao = "";
$grupoTrabalho = "";

if (isset($cod_grupotr)) {
    for ($i = 0; $i < count($cod_grupotr); $i++) {
        $lista_cod_grupotr  .= $cod_grupotr[$i] . ',';
    }
    $lista_cod_grupotr = rtrim($lista_cod_grupotr, ',');

    if (!empty($lista_cod_grupotr)) {
        $sql = "SELECT COD_UNIVEND from unidadevenda where COD_EMPRESA = $cod_empresa and COD_GRUPOTR in ($lista_cod_grupotr) and (cod_exclusa is null or cod_exclusa = 0) order by trim(NOM_FANTASI)";
        // fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
            $grupoTrabalho .= $qrListaUniVendas['COD_UNIVEND'] . ",";
        }
        // fnEscreve($grupoTrabalho);
        $usuReportAdm = $_SESSION["SYS_COD_USUARIO"];
        $empReportAdm = $_SESSION["SYS_COD_EMPRESA"];

        if ($empReportAdm == $cod_empresa) {
            $sqlUsu = "SELECT COD_UNIVEND,
                                   (select count(*) from unidadevenda where cod_empresa=usuarios.COD_EMPRESA ) QTD_UNIVEND
                                   FROM usuarios WHERE cod_empresa = $cod_empresa AND cod_usuario = $usuReportAdm ";
            $arrayQuery = mysqli_query($connAdm->connAdm(), $sqlUsu);
            //fnEscreve($sqlUsu);
            $qrUnidadesUsuario = mysqli_fetch_assoc($arrayQuery);

            $cod_univendUsu = $qrUnidadesUsuario['COD_UNIVEND'];

            $listaEscolhida = explode(',', substr($grupoTrabalho, 0, -1));
            $listaAutorizada = explode(',', $cod_univendUsu);
        } else {

            $listaEscolhida = explode(',', substr($grupoTrabalho, 0, -1));
            $listaAutorizada = explode(',', substr($grupoTrabalho, 0, -1));
        }




        $novaLista1 = array();
        foreach ($listaEscolhida as &$valueEscolhida) {
            foreach ($listaAutorizada as &$valueAutorizada) {
                if ($valueAutorizada == $valueEscolhida) {
                    array_push($novaLista1, $valueEscolhida);
                }
            }
        }
    }
}
if (isset($cod_tiporeg)) {


    for ($i = 0; $i < count($cod_tiporeg); $i++) {
        $lista_cod_tiporeg  .= $cod_tiporeg[$i] . ',';
    }
    $lista_cod_tiporeg = rtrim($lista_cod_tiporeg, ',');

    //fnEscreve('filtro 2');
    // echo'<pre>';
    // print_r($novaLista1);
    // echo'</pre>';

    if (!empty($lista_cod_tiporeg)) {
        $sql = "select COD_UNIVEND from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and COD_TIPOREG in ('" . $lista_cod_tiporeg . "') and (cod_exclusa is null or cod_exclusa = 0) order by trim(NOM_FANTASI)";
        //fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
            $grupoRegiao .= $qrListaUniVendas['COD_UNIVEND'] . ",";
        }
        //fnEscreve('escolhida');

        //  echo'<pre>';
        // print_r($grupoRegiao);
        //echo'</pre>';
        $usuReportAdm = $_SESSION["SYS_COD_USUARIO"];
        $empReportAdm = $_SESSION["SYS_COD_EMPRESA"];


        if ($empReportAdm == $cod_empresa) {
            $sqlUsu = "SELECT COD_UNIVEND,
                                   (select count(*) from unidadevenda where cod_empresa=usuarios.COD_EMPRESA ) QTD_UNIVEND
                                   FROM usuarios WHERE cod_empresa = $cod_empresa AND cod_usuario = $usuReportAdm ";
            $arrayQuery = mysqli_query($connAdm->connAdm(), $sqlUsu);
            //fnEscreve($sqlUsu);
            $qrUnidadesUsuario = mysqli_fetch_assoc($arrayQuery);

            $cod_univendUsu = $qrUnidadesUsuario['COD_UNIVEND'];

            $listaEscolhida = explode(',', rtrim($grupoRegiao, ','));
            $listaAutorizada = explode(',', $cod_univendUsu);
        } else {
            $listaEscolhida = explode(',', rtrim($grupoRegiao, ','));
            $listaAutorizada = explode(',', rtrim($grupoRegiao, ','));
        }
        //  echo'<pre>';
        //  print_r($listaAutorizada);
        //   echo'</pre>';
        $novaLista2 = array();
        foreach ($listaEscolhida as &$valueEscolhida) {
            foreach ($listaAutorizada as &$valueAutorizada) {
                if ($valueAutorizada == $valueEscolhida) {
                    array_push($novaLista2, $valueEscolhida);
                }
            }
        }
    }
}

//$lojasSelecionadas = '';

if (empty($novaLista1) && empty($novaLista2) && (!empty($lista_cod_grupotr) || !empty($lista_cod_tiporeg))) {
    //fnEscreve('entrou');
    $lojasSelecionadas = '0';
}

if (!empty($novaLista1) && !empty($novaLista2)) {
    $lojasSelecionadas = implode(',', array_merge($novaLista1, $novaLista2));
    //fnEscreve('if');
} else if (!empty($novaLista1) && empty($novaLista2)) {
    $lojasSelecionadas = implode(',', $novaLista1);
    //fnEscreve('if2');
} else if (empty($novaLista1) && !empty($novaLista2)) {
    $lojasSelecionadas = implode(',', $novaLista2);
    $lojasSelecionadas = rtrim($lojasSelecionadas, ',');
    //fnEscreve('if3');
}

if ($lojasSelecionadas == '') {
    $lojasSelecionadas = 0;
}

//fnEscreve($lojasSelecionadas);
