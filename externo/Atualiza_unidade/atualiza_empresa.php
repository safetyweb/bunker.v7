
<?php
require '../../_system/_functionsMain.php';
$conadmin = $connAdm->connAdm();
fndebug('true');
$date = date('Y-m-d');
$DATEMENOS1 = date('Y-m-d', strtotime("-1 days", strtotime($date)));
if ($_GET['COD_EMPRESA'] != '') {
    $COD_EMPRESA = $_GET['COD_EMPRESA'];
    $where = 'where e.LOG_ATIVO="S" and e.cod_empresa=' . $COD_EMPRESA;
} else {
    $where = 'where e.LOG_ATIVO="S"  and e.cod_empresa NOT IN (136,514) ';
}

// Capturar a base de dados relacionado a empresa
$sqldatabases = "SELECT * FROM tab_database t
               INNER JOIN empresas e ON e.cod_empresa=t.COD_empresa
         $where";
$rwdatabases = mysqli_query($conadmin, $sqldatabases);
if (!$rwdatabases) {
    die("Erro na consulta SQL: " . mysqli_error($conadmin));
}
while ($rsdatabases = mysqli_fetch_assoc($rwdatabases)) {

    if ($_GET['COD_EMPRESA'] == '') {
        $whereunidade = 'where cod_empresa=' . $rsdatabases['COD_EMPRESA'];
    } else {
        $whereunidade = 'where cod_empresa=' . $_GET['COD_EMPRESA'];
    }
    $CONNTEMPERARIA = connTemp($rsdatabases['COD_EMPRESA'], '');

    // Capturar as unidades da webtools
    $sqlunidadevenda_adm = "SELECT * FROM empresas $whereunidade";
    $RWunidadevenda_adm = mysqli_query($conadmin, $sqlunidadevenda_adm);
    if (!$RWunidadevenda_adm) {
        die("Erro na consulta SQL: " . mysqli_error($conadmin));
    }

    $fildecampo = '';
    while ($fildname = mysqli_fetch_field($RWunidadevenda_adm)) {
        $fildecampo .= $fildname->name . ',';
    }
    $fildecampo = rtrim($fildecampo, ',');

    while ($rsunidadevenda_adm = mysqli_fetch_assoc($RWunidadevenda_adm)) {
        $sqlunidadevenda_adm1 = "SELECT * FROM empresas WHERE COD_EMPRESA=" . $rsdatabases['COD_EMPRESA'];
        echo '<br>' . $sqlunidadevenda_adm1 . '<br>';
        $RWunidadevenda_adm1 = mysqli_query($CONNTEMPERARIA, $sqlunidadevenda_adm1);

        if (mysqli_num_rows($RWunidadevenda_adm1) <= 0) {
            $newDatec = date("Y-m-d", strtotime($rsunidadevenda_adm['DAT_CADASTR']));
            if ($rsunidadevenda_adm['DAT_CADASTR'] >= $newDatec) {
                $values = [];
                foreach (array_keys($rsunidadevenda_adm) as $key) {
                    $value = $rsunidadevenda_adm[$key] == '' ? 'NULL' : "'" . mysqli_real_escape_string($CONNTEMPERARIA, $rsunidadevenda_adm[$key]) . "'";
                    $values[] = $value;
                }
                $values = implode(",", $values);
                $sqlinser = "INSERT INTO empresas ($fildecampo) VALUES ($values);";
                echo '<br>' . $sqlinser . '<br>';
                $insert = mysqli_query($CONNTEMPERARIA, $sqlinser);
                if (!$insert) {
                    echo "Erro ao inserir: " . mysqli_error($CONNTEMPERARIA) . "<br>";
                }
                mysqli_next_result($CONNTEMPERARIA);
            }
        } else {
            $newDate = date("Y-m-d", strtotime($rsunidadevenda_adm['DAT_ALTERAC']));
            if ($newDate >= $DATEMENOS1) {
                $SETUPDATE = '';
                foreach (array_keys($rsunidadevenda_adm) as $key) {
                    $value = $rsunidadevenda_adm[$key] == '' ? 'NULL' : "'" . mysqli_real_escape_string($CONNTEMPERARIA, $rsunidadevenda_adm[$key]) . "'";
                    $SETUPDATE .= "$key=$value,";
                }
                $SETUPDATE = rtrim($SETUPDATE, ',');
                $updatevalues = "UPDATE empresas SET $SETUPDATE WHERE COD_EMPRESA=" . $rsdatabases['COD_EMPRESA'];
                echo $updatevalues;
                $sqlup = mysqli_query($CONNTEMPERARIA, $updatevalues);
                if (!$sqlup) {
                    echo "Erro ao atualizar: " . mysqli_error($CONNTEMPERARIA) . "<br>";
                }
            }
        }

        unset($values);
        unset($SETUPDATE);
    }
    unset($fildecampo);
}
?>
