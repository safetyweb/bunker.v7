<?php
echo fnDebug('true');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$fluxo = fnDecode(@$_GET["fluxo"]);
$passo = @$_GET["passo"];
$passo_acao = @$_GET["passo_acao"];


if (@$_GET["novo_fluxo"] <> "") {
    //Inicia o FLUXO

    $n_fluxo = fnDecode($_GET["novo_fluxo"]);

    $sql = "SELECT * FROM fluxo_dados WHERE fluxo_dados.COD_FLUXO=0$n_fluxo";
    $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
    $ln = mysqli_fetch_assoc($rs);

    if (isset($ln)) {
        $sql = "INSERT INTO fluxo_operacional (COD_USUCADA,DAT_CADASTR)"
            . " VALUES ('" . $_SESSION["SYS_COD_USUARIO"] . "',NOW())";
        $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);

        $sql = "SELECT IFNULL(MAX(COD_FLUXO_OPER),0) COD_FLUXO_OPER FROM fluxo_operacional";
        $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
        $cod = mysqli_fetch_assoc($rs);

        $ln["DES_PASSO_ANTERIOR"] = json_encode([]);
        $ln["ERRO"] = "N";
        fnGravaFluxo($cod["COD_FLUXO_OPER"], $ln);
        fnSegueFluxo($cod["COD_FLUXO_OPER"]);
        exit;
    } else {
        fn_erro_fluxo("Fluxo não encontrado!");
        exit;
    }
} else {

    $sql = "SELECT 
    	        FD.COD_FLUXO,FO.COD_FLUXO_OPER,FD.COD_EMPRESA,FD.DES_FLUXO,FD.JSN_FLUXO_EXPORT,FD.DES_ITENS,FD.DES_FLUXO_MODULOS,
				FO.COD_MODULOS,FO.COD_NODE,FO.DES_PASSO_ANTERIOR
            FROM fluxo_operacional FO
			LEFT JOIN fluxo_dados FD ON FD.COD_FLUXO=FO.COD_FLUXO
			WHERE FO.COD_FLUXO_OPER=0$fluxo
			AND FO.COD_EMPRESA='$cod_empresa'";
    $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
    $ln = mysqli_fetch_assoc($rs);

    if (isset($ln)) {

        $passos = json_decode($ln["DES_ITENS"], true);
        $json = json_decode($ln["DES_FLUXO_MODULOS"], true);
        $passo_atual = $passos[$passo];
        $fluxo_atual = $json[$passo];
        $ln["RETORNO"] = "";
        $ln["COD_NODE"] = $passo;
        $ln["COD_MODULOS"] = $passo_atual["cod"];

        if ($fluxo_atual["type"] == "modulo" || $fluxo_atual["type"] == "home") {
            //O passo seguinte é módulo. Redireciona direto.


            if ($passo_atual["cod"] == "") {
                fn_erro_fluxo("Módulo não cadastrado neste passo!", $ln);
                exit;
            }

            echo $ln["DES_PASSO_ANTERIOR"] . "<br>";
            $ln["DES_PASSO_ANTERIOR"] = fnGeraPassoAnterior($ln["DES_PASSO_ANTERIOR"]);
            $ln["ERRO"] = "N";
            fnGravaFluxo($fluxo, $ln);
            fnSegueFluxo($ln["COD_FLUXO_OPER"]);
            exit;
        } elseif ($fluxo_atual["type"] == "decision") {
            //O passo seguinte é um decision. Verifica as regras.

            if ($passo_atual["origem"] == "url") {
                //Processar as regras de URL

                $cod_fluxo = "";
                $ln["REGRA"] = "";
                foreach ($passo_atual["regras"] as $regra) {
                    $valor_url = @$_GET[$regra["elemento"]];
                    if ($valor_url <> "" && $regra["cripto"] == 1) {
                        $valor_url = fnDecode($valor_url);
                    }

                    $ln["REGRA"] = "$valor_url " . $regra['operador'] . " \'" . $regra['valor'] . "\'";

                    switch ($regra['operador']) {
                        case "=":
                            $result = ($valor_url == $regra['valor']);
                            break;
                        case "!=":
                            $result = ($valor_url != $regra['valor']);
                            break;
                        case ">":
                            $result = ($valor_url > $regra['valor']);
                            break;
                        case "<":
                            $result = ($valor_url < $regra['valor']);
                            break;
                        case ">=":
                            $result = ($valor_url >= $regra['valor']);
                            break;
                        case "<=":
                            $result = ($valor_url <= $regra['valor']);
                            break;
                        default:
                            $result = false;
                    }

                    if ($result) {
                        $cod_fluxo = $regra['fluxo'];
                        break;
                    }
                }

                if ($cod_fluxo <> "") {
                    $passo_seguinte = $passos[$cod_fluxo];
                    if ($passo_seguinte["cod"] == "") {
                        $ln["REGRA"] = "";
                        fn_erro_fluxo("Módulo não cadastrado neste passo!", $ln);
                        exit;
                    }

                    $ln["DES_PASSO_ANTERIOR"] = fnGeraPassoAnterior($ln["DES_PASSO_ANTERIOR"]);
                    $ln["ERRO"] = "N";
                    fnGravaFluxo($fluxo, $ln);

                    $ln["REGRA"] = "";
                    $ln["COD_NODE"] = $cod_fluxo;
                    $ln["COD_MODULOS"] = $passo_seguinte["cod"];
                    fnGravaFluxo($fluxo, $ln);
                    fnSegueFluxo($ln["COD_FLUXO_OPER"]);
                    exit;
                } else {
                    fn_erro_fluxo("Regra do decision não contempla nenhum próximo passo!", $ln);
                    exit;
                }
            } else {

                fn_erro_fluxo("Origem dos dados da decisão não configurado!", $ln);
                exit;
            }
        } else {

            fn_erro_fluxo("Fluxo não configurado para o tipo: " . $passo_atual["type"], $ln);
            exit;
        }
    } else {
        fn_erro_fluxo("Fluxo não encontrado!");
        exit;
    }
}





function fn_erro_fluxo($erro, $data = [])
{
    global $cod_empresa;

    if (count($data) > 0) {
        $sql = "SELECT FO.*,FD.DES_FLUXO_MODULOS FROM fluxo_operacional FO
                    LEFT JOIN fluxo_dados FD ON FD.COD_FLUXO=FO.COD_FLUXO
                    WHERE FO.COD_FLUXO_OPER=" . $data["COD_FLUXO_OPER"] . "
                    AND FO.COD_EMPRESA='" . $data["COD_EMPRESA"] . "'";
        $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
        $ln = mysqli_fetch_assoc($rs);

        $data["ERRO"] = "S";
        $data["RETORNO"] = $erro;
        fnGravaFluxo($data["COD_FLUXO_OPER"], $data);

        $ln["ERRO"] = "N";
        $ln["RETORNO"] = "";
        fnGravaFluxo($data["COD_FLUXO_OPER"], $ln);
    }

    echo "<p><br><br>";
    echo "<b><span class='text-danger'>" . $erro . "</span></b><br>Você esté sendo redirecionado para a tela anterior.";
?>
    <script>
        setTimeout(function() {
            window.history.back();
        }, 5000);
    </script>
<?php
}

function monta_url($params = [])
{
    return "/action.do?" . http_build_query($params);
}

function fn_redirect($url)
{
    header('Location: ' . $url);
?>
    <script>
        $("body").html("<div style='padding:20px;'>Redirecionando...</div>");
        window.location.href = "<?= $url ?>";
    </script>
<?php
}

function fnGravaFluxo($cod = 0, $data = [])
{
    global $cod_empresa;

    $get = $_GET;
    unset($get["passo_orig"]);
    unset($get["passo_acao"]);
    unset($get["passo_atual"]);
    unset($get["passo"]);
    unset($get["novo_fluxo"]);
    unset($get["fluxo"]);
    unset($get["mod"]);
    unset($get["id"]);
    $params = json_encode($get);
    $params = str_replace('\\', '\\\\', $params);

    $fluxo_modulos = json_decode($data["DES_FLUXO_MODULOS"], true);
    $fluxo_modulo = $fluxo_modulos[$data["COD_NODE"]];


    $sql = "UPDATE fluxo_operacional SET "
        . " COD_FLUXO = '" . $data["COD_FLUXO"] . "',"
        . " COD_EMPRESA = '" . $data["COD_EMPRESA"] . "',"
        . " COD_MODULOS = '0" . $data["COD_MODULOS"] . "',"
        . " COD_NODE = '" . $data["COD_NODE"] . "',"
        . " DES_PASSO_ANTERIOR = '" . $data["DES_PASSO_ANTERIOR"] . "',"
        . " PARAMS = '" . $params . "',"
        . " ACAO = '" . (@$_GET["novo_fluxo"] <> "" ? "new" : $_GET["passo_acao"]) . "',"
        . " TIPO_FLUXO = '" . $fluxo_modulo["type"] . "',"
        . " ERRO = '" . @$data["ERRO"]  . "',"
        . " RETORNO = '" . @$data["RETORNO"]  . "',"
        . " REGRA = '" . @$data["REGRA"]  . "',"
        . " COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',"
        . " DAT_ALTERAC = NOW()"
        . " WHERE COD_FLUXO_OPER = 0$cod ";

    mysqli_query(conntemp($cod_empresa, ""), trim($sql));


    $sql = "UPDATE fluxo_operacional_historico SET PASSO_ATUAL='N' WHERE COD_FLUXO_OPER=0$cod";
    mysqli_query(conntemp($cod_empresa, ""), trim($sql));

    $sql = "SELECT IFNULL(MAX(SEQUENCIA),0)+1 SEQUENCIA FROM fluxo_operacional_historico WHERE COD_FLUXO_OPER=0$cod";
    $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
    $seq = mysqli_fetch_assoc($rs);


    $sql = "INSERT INTO fluxo_operacional_historico (
                COD_FLUXO_OPER,COD_FLUXO,COD_EMPRESA,COD_MODULOS,COD_NODE,DES_PASSO_ANTERIOR,PARAMS,COD_CLIENTE,NUM_CONTRATO,
                ACAO,TIPO_FLUXO,ERRO,RETORNO,REGRA,PASSO_ATUAL,SEQUENCIA,COD_USUCADA,DAT_CADASTR
            ) 
            SELECT
                COD_FLUXO_OPER,COD_FLUXO,COD_EMPRESA,COD_MODULOS,COD_NODE,DES_PASSO_ANTERIOR,PARAMS,COD_CLIENTE,NUM_CONTRATO,
                ACAO,TIPO_FLUXO,ERRO,RETORNO,REGRA,'S'," . $seq["SEQUENCIA"] . ",COD_ALTERAC,DAT_ALTERAC
            FROM fluxo_operacional WHERE COD_FLUXO_OPER=0$cod
        ";
    mysqli_query(conntemp($cod_empresa, ""), trim($sql));

    return $cod;
}

function fnSegueFluxo($cod)
{
    global $cod_empresa;

    $sql = "SELECT * FROM fluxo_operacional WHERE COD_FLUXO_OPER=0$cod";
    $rs = mysqli_query(conntemp($cod_empresa, ""), $sql);
    $ln = mysqli_fetch_assoc($rs);

    $params = array_merge(
        ['mod' => fnEncode($ln["COD_MODULOS"]), 'id' => fnEncode($ln["COD_EMPRESA"]), 'fluxo' => fnEncode($ln["COD_FLUXO_OPER"])],
        json_decode($ln["PARAMS"], true)
    );
    $url = monta_url($params);
    fn_redirect($url);
    exit;
}

function fnGeraPassoAnterior($passo)
{
    if ($_GET["passo_acao"] == "prev") {
        $p = json_decode($passo, true);
        array_pop($p);
        $passo = json_encode($p);
    } elseif (@$_GET["passo_atual"] <> "") {
        $p = json_decode($passo, true);

        $passo_atual = $_GET["passo_atual"];
        $p[] = $passo_atual;

        $passo = json_encode($p);
    }
    return $passo;
}
exit;
