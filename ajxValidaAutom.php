<?php
include '_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);
$nom_fantasi = fnLimpaCampo($_POST['NOM_FANTASI']);

switch ($opcao) {
    case 'valFantasi':
        $sqlbusca = "SELECT DES_DOMINIO, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
        $query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
        if ($qrResult = mysqli_fetch_assoc($query)) {
            $des_dominio = $qrResult['DES_DOMINIO'];
            switch ($qrResult['COD_DOMINIO']) {
                case '1':
                    $dominio = $des_dominio . ".mais.cash";
                    break;

                case '2':
                    $dominio = $des_dominio . ".fidelidade.mk";
                    break;
            }
        }

        $templates = "";

        //TEMPLATES TOKEN
        $nomToken = "$cod_empresa - Template AUTOM SMS Token";
        $tempToken = strtoupper($nom_fantasi) . ": <#TOKEN> Ao informar esse token ao atendente vc confirma estar de acordo c/ nossas politicas disponiveis em: $dominio/#info";
        if (strlen($tempToken) > 159) {
            $templates .= $nomToken . ",";
        }

        //TEMPLATE BOAS VINDAS
        $nomBvs = $cod_empresa . " - Template AUTOM SMS Boas Vindas";
        $tempBvs = strtoupper($nom_fantasi) . ": <#NOMECLIENT> parabens por se cadastrar! Agora vc acumula cashback em todas as compras e recebe ofertas exclusivas!";
        if (strlen($tempBvs) > 159) {
            $templates .= $nomBvs . ",";
        }

        $nomNivers = $cod_empresa . ' - Template AUTOM SMS Niver';
        $tempNivers = strtoupper($nom_fantasi) . ":<#NOMECLIENT> parabens por mais um ano de vida. Estamos felizes por voce e desejamos muita saude e felicidades!";
        if (strlen($nomNivers) > 159) {
            $templates .= $nomNivers . ",";
        }

        $nomExpira = $cod_empresa . "- Template AUTOM SMS Expirar";
        $tempExpir = strtoupper($nom_fantasi) . ": Nao perca tempo, <#NOMECLIENT> seu saldo e R$ <#SALD> e parte dele vence em 7 dias. Corra e venha aproveitar seu credito!";
        if (strlen($tempExpir) > 159) {
            $templates .= $nomExpira . ",";
        }

        $nomInativo = $cod_empresa . "- Template AUTOM SMS Inativos 2a6";
        $tempInativo = strtoupper($nom_fantasi) . ": <#NOMECLIENT> como vai voce? Nao esqueca que aqui em todas as suas compras voce recebe dinheiro de volta!";
        if (strlen($tempInativo) > 159) {
            $templates .= $nomInativo . ",";
        }

        $nomTransa = $cod_empresa . "- Template AUTOM SMS Transacional";
        $tempTransa = strtoupper($nom_fantasi) . ": <#NOMECLIENT> obrigada pela preferencia, voce ja tem R$ <#SALD> de saldo para abater nas proximas compras";
        if (strlen($tempTransa) > 159) {
            $templates .= $nomTransa . ",";
        }

        $nomNiversClub = $cod_empresa . "- Template AUTOM Nivers club 3B5";
        $temNiversClub = strtoupper($nom_fantasi) . ": <#NOMECLIENT> este mes celebramos mais um ano de sua amizade e fidelidade. Muito obrigada pela confianca!";
        if (strlen($temNiversClub) > 159) {
            $templates .= $nomNiversClub . ",";
        }

        $nomResgate = $cod_empresa . "- Template AUTOM SMS Resgate";
        $tempResgate = strtoupper($nom_fantasi) . ": <#NOMECLIENT> parabens hoje vc usou <#RESG> de seus creditos em nossa loja, estamos felizes! Continue aproveitando!";
        if (strlen($tempResgate) > 159) {
            $templates .= $nomResgate . ",";
        }

        if ($templates != "") {
            $templates = rtrim($templates, ",");

            echo 'As Templates a seguir <strong>não serão cadastradas</strong> por excederem o limite de caracteres: <br>' . $templates;
        }

        break;
}
