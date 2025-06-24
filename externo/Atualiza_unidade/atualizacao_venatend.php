<?php
require '../../_system/_functionsMain.php';
$conadmin = $connAdm->connAdm();
fndebug('true');
$date = date('Y-m-d');
$DATEMENOS1 = date('Y-m-d', strtotime(" -1 days", strtotime($date)));
if ($_GET['COD_EMPRESA'] != '') {
    $COD_EMPRESA = $_GET['COD_EMPRESA'];
    $where = 'where e.LOG_ATIVO="S" and e.cod_empresa=' . $COD_EMPRESA;
} else {
    $where = 'where e.LOG_ATIVO="S" and e.cod_empresa not in (136)';
}

//capturar a base de dados relacionado a empresa
$sqldatabases = "SELECT * FROM tab_database t
               INNER JOIN empresas e ON e.cod_empresa=t.COD_empresa
         $where";

$rwdatabases = mysqli_query($conadmin, $sqldatabases);
while ($rsdatabases = mysqli_fetch_assoc($rwdatabases)) {

    if ($_GET['COD_EMPRESA'] == '') {
        // $whereunidade='where cod_empresa='.$rsdatabases[COD_EMPRESA].' AND COD_TPUSUARIO in (11,2,8,7)'; 
        $whereunidade = 'where cod_empresa in(' . $rsdatabases['COD_EMPRESA'] . ',2,3)';
    } else {
        // $whereunidade='where COD_TPUSUARIO in (11,2,8,7) and cod_empresa='.$_GET['COD_EMPRESA']; 
        $whereunidade = 'where  cod_empresa in (' . $_GET['COD_EMPRESA'] . ',2,3)';
    }
    $CONNTEMPERARIA = connTemp($rsdatabases['COD_EMPRESA'], '');
    //capturar as unidades da webtools
    $sqlunidadevenda_adm = "SELECT * FROM usuarios  $whereunidade";


    $RWunidadevenda_adm = mysqli_query($conadmin, $sqlunidadevenda_adm);
    while ($rsunidadevenda_adm = mysqli_fetch_assoc($RWunidadevenda_adm)) {



        while ($fildname = mysqli_fetch_field($RWunidadevenda_adm)) {
            $fildecampo .= $fildname->name . ',';
            $fildecampoarray[$fildname->name] = $fildname->name;
        }


        //verificar se ja existe ma univend do cliente.
        $sqlunidadevenda_adm1 = "SELECT * FROM usuarios WHERE COD_USUARIO=" . $rsunidadevenda_adm['COD_USUARIO'] . "
                                              		 and  cod_empresa in(" . $rsdatabases['COD_EMPRESA'] . ",2,3);";

        $RWunidadevenda_adm1 = mysqli_query($CONNTEMPERARIA, $sqlunidadevenda_adm1);

        if (mysqli_num_rows($RWunidadevenda_adm1) <= 0) {

            $newDatec = date("Y-m-d", strtotime($rsunidadevenda_adm['DAT_CADASTR']));
            if ($rsunidadevenda_adm['DAT_CADASTR'] >= $newDatec) {
                $fildecampo = rtrim($fildecampo, ',');
                //inserindo na base de dados do cliente
                foreach (array_keys($rsunidadevenda_adm) as $key) {
                    if (!in_array($key)) {
                        if ($rsunidadevenda_adm[$key] == '') {
                            $rsunidadevenda_adm[$key] = 'NULL';
                        }
                        if ($rsunidadevenda_adm[$key] == 'NULL') {
                            $values[] = $rsunidadevenda_adm[$key];
                        } else {
                            $values[] = "'" . addslashes($rsunidadevenda_adm[$key]) . "'";
                        }
                    }
                }
                $values = implode(",", $values);
                $sqlinser = 'insert into usuarios (' . $fildecampo . ')value(' . $values . ');';

                $insert = mysqli_query($CONNTEMPERARIA, $sqlinser);
                if (!$insert) {
                    echo $rsdatabases['COD_EMPRESA'] . '<br>ERRO: ' . $sqlinser . '<br>';
                }
                mysqli_next_result($CONNTEMPERARIA);
            } else {
                // echo '<br>Não tem insert<br>';
            }
        } else {

            $newDate = date("Y-m-d", strtotime($rsunidadevenda_adm['DAT_ALTERAC']));
            if ($newDate >= $DATEMENOS1) {

                foreach (array_keys($rsunidadevenda_adm) as $key) {
                    if (!in_array($key)) {
                        if ($rsunidadevenda_adm[$key] == '') {
                            $rsunidadevenda_adm[$key] = 'NULL';
                        }
                        if ($rsunidadevenda_adm[$key] == 'NULL') {
                            $values[$key] = $rsunidadevenda_adm[$key];
                        } else {
                            $values[$key] = "'" . $rsunidadevenda_adm[$key] . "'";
                        }
                    }
                    unset($values['COD_USUARIO']);
                }

                foreach ($values as $chave => $valor) {
                    $SETUPDATE .= $chave . '=' . $valor . ',';
                }


                // unset($sqlleitura3ARRAY[$key1]); 
                $SETUPDATE = rtrim($SETUPDATE, ',');
                $updatevalues = "UPDATE usuarios SET $SETUPDATE  WHERE COD_EMPRESA in ($values[COD_EMPRESA],2,3) and COD_USUARIO=" . $rsunidadevenda_adm['COD_USUARIO'];
                //   echo $updatevalues.'<br>';
                $sqlup = mysqli_query($CONNTEMPERARIA, $updatevalues);
                if (!$sqlup) {
                    echo $rsdatabases['COD_EMPRESA'] . '<br>erro : ' . $updatevalues . '<br>';
                    print_r($CONNTEMPERARIA);
                }
            } else {
                // Echo '<br>Não tem alterção<br>';
            }
        }

        unset($values);
        unset($SETUPDATE);
    }
    unset($fildecampo);
    // mysqli_close($CONNTEMPERARIA);
    // unset($CONNTEMPERARIA);
    //primeiro passo verificar se ja existe na unidades do cliente

    //SELECT * FROM unidadevenda


}
