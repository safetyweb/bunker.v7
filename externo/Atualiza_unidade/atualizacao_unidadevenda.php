<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../_system/_functionsMain.php';

$conadmin = $connAdm->connAdm();


// Verifique se a conexão foi bem-sucedida
/*if (!$conadmin) {
    die("Erro na conexão11: " . mysqli_connect_error());
    echo '<pre>';
    print_r($conadmin);
    echo '</pre>';
}*/

// Definir o charset para evitar problemas de codificação
if (!mysqli_set_charset($conadmin, 'utf8mb4')) {
    die("Erro ao definir o charset: " . mysqli_error($conadmin));
}



$date = date('Y-m-d');
$DATEMENOS1 = date('Y-m-d', strtotime("-1 days", strtotime($date)));

// Verifica se a empresa foi passada via GET
$COD_EMPRESA = isset($_GET['COD_EMPRESA']) && $_GET['COD_EMPRESA'] != '' ? $_GET['COD_EMPRESA'] : null;
$where = $COD_EMPRESA ? 'WHERE e.LOG_ATIVO="S" AND e.cod_empresa=' . $COD_EMPRESA : 'WHERE e.LOG_ATIVO="S" AND e.cod_empresa NOT IN (136,514)';

// Capturar a base de dados relacionada à empresa
$sqldatabases = "SELECT * FROM tab_database t
                 INNER JOIN empresas e ON e.cod_empresa=t.COD_empresa 
                 $where";
$rwdatabases = mysqli_query($conadmin, $sqldatabases);

if (!$rwdatabases) {
    die("Erro na consulta SQL de bases de dados: " . mysqli_error($conadmin));
}

while ($rsdatabases = mysqli_fetch_assoc($rwdatabases)) {

    // Verifica se a unidade foi passada via GET
    $COD_UNIVEND = isset($_GET['COD_UNIVEND']) && $_GET['COD_UNIVEND'] != '' ? $_GET['COD_UNIVEND'] : '';
    $whereunidade = $COD_UNIVEND ? 'WHERE cod_empresa=' . $_GET['COD_EMPRESA'] . ' AND COD_UNIVEND=' . $COD_UNIVEND : 'WHERE cod_empresa=' . $rsdatabases['COD_EMPRESA'];

    // Conectar à base temporária da empresa
    $CONNTEMPERARIA = connTemp($rsdatabases['COD_EMPRESA'], '');

    // Definir o charset na conexão temporária também
    if (!mysqli_set_charset($CONNTEMPERARIA, 'utf8mb4')) {
        die("Erro ao definir o charset na conexão temporária: " . mysqli_error($CONNTEMPERARIA));
    }



    // Capturar as unidades de venda
    $sqlunidadevenda_adm = "SELECT * FROM unidadevenda $whereunidade";
    $RWunidadevenda_adm = mysqli_query($conadmin, $sqlunidadevenda_adm);

    if (!$RWunidadevenda_adm) {
        die("Erro na consulta SQL de unidades de venda: " . mysqli_error($conadmin));
    }

    // Coletar os nomes dos campos apenas uma vez por consulta
    $fildecampo = '';
    while ($fildname = mysqli_fetch_field($RWunidadevenda_adm)) {
        $fildecampo .= $fildname->name . ',';
        $fildecampoarray[$fildname->name] = $fildname->name;
    }
    $fildecampo = rtrim($fildecampo, ','); // Remover a última vírgula

    while ($rsunidadevenda_adm = mysqli_fetch_assoc($RWunidadevenda_adm)) {
        // Verificar se a unidade já existe na base de dados temporária
        $sqlunidadevenda_adm1 = "SELECT * FROM unidadevenda WHERE COD_EMPRESA='" . $rsdatabases['COD_EMPRESA'] . "' AND COD_UNIVEND=" . $rsunidadevenda_adm['COD_UNIVEND'];
        $RWunidadevenda_adm1 = mysqli_query($CONNTEMPERARIA, $sqlunidadevenda_adm1);

        if (!$RWunidadevenda_adm1) {
            die("Erro na consulta SQL de verificação: " . mysqli_error($CONNTEMPERARIA));
        }

        // Se a unidade não existir, fazer o insert
        if (mysqli_num_rows($RWunidadevenda_adm1) <= 0) {
            $newDatec = date("Y-m-d", strtotime($rsunidadevenda_adm['DAT_CADASTR']));
            if ($rsunidadevenda_adm['DAT_CADASTR'] >= $newDatec) {
                $values = [];
                foreach (array_keys($rsunidadevenda_adm) as $key) {
                    if (!in_array($key, array_keys($rsunidadevenda_adm))) {
                        continue;
                    }
                    $value = $rsunidadevenda_adm[$key] == '' ? 'NULL' : "'" . mysqli_real_escape_string($CONNTEMPERARIA, $rsunidadevenda_adm[$key]) . "'";
                    $values[] = $value;
                }
                $values_str = implode(",", $values);
                $sqlinser = "INSERT INTO unidadevenda ($fildecampo) VALUES ($values_str);";

                echo "<br>Inserindo: $sqlinser<br>";
                $insert = mysqli_query($CONNTEMPERARIA, $sqlinser);

                if (!$insert) {
                    echo "<br>Erro ao inserir: " . mysqli_error($CONNTEMPERARIA) . "<br>";
                }
            } else {
                echo "<br>Não há novos registros para inserir.<br>";
            }
        } else {
            // Se a unidade existir, verificar se há atualizações
            $newDate = date("Y-m-d", strtotime($rsunidadevenda_adm['DAT_ALTERAC']));
            if ($newDate >= $DATEMENOS1) {
                $SETUPDATE = '';
                foreach (array_keys($rsunidadevenda_adm) as $key) {
                    if (!in_array($key, array_keys($rsunidadevenda_adm))) {
                        continue;
                    }
                    $value = $rsunidadevenda_adm[$key] == '' ? 'NULL' : "'" . mysqli_real_escape_string($CONNTEMPERARIA, $rsunidadevenda_adm[$key]) . "'";
                    $SETUPDATE .= "$key=$value,";
                }
                $SETUPDATE = rtrim($SETUPDATE, ',');
                $updatevalues = "UPDATE unidadevenda SET $SETUPDATE WHERE COD_EMPRESA='" . $rsunidadevenda_adm['COD_EMPRESA'] . "' AND COD_UNIVEND='" . $rsunidadevenda_adm['COD_UNIVEND'] . "'";

                echo "<br>Atualizando: $updatevalues<br>";
                $sqlup = mysqli_query($CONNTEMPERARIA, $updatevalues);

                if (!$sqlup) {
                    echo "<br>Erro ao atualizar: " . mysqli_error($CONNTEMPERARIA) . "<br>";
                }
            } else {
                echo "<br>Não há alterações para atualizar.<br>";
            }
        }

        unset($values);
        unset($SETUPDATE);
    }

    unset($fildecampo);
}
