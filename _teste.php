<?php
include "_system/_functionsMain.php";


// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_SSL_VERIFYPEER=> false,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>'{
//   "extension": ".mp4",
//   "forceSend": true,
//   "number": "5515988034772",
//   "verifyContact": true,
//   "linkUrl": "https://adm.bunker.mk/media/adorai.mp4",
//   "fileName": "video-chale",
//   "caption": "teste de video para @contato.nome"
// }',
//   CURLOPT_HTTPHEADER => array(
//     'access-token: 60b7fefa6739b9349ab43fd5',
//     'Content-Type: application/json',
//     'Accept: application/json'
//   ),
// ));

// $response = curl_exec($curl);

// curl_close($curl);
// echo $response; 

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');                    
    date_default_timezone_set("america/sao_paulo");

    $mesIni = explode("-", "2022-04-01");
    $mesFim = explode("-", "2022-06-01");

    $selectValues = "";
    $caseWhen = "";
    $meses = array();

    for ($i=$mesIni[1]+0; $i <= $mesFim[1]; $i++) { 
        $selectValues .= "SUM(PCT_DIARIO$i) MES_$i, ";
        $caseWhen .= "CASE WHEN MONTH(DAT_MOVIMENTO)='$i' THEN ROUND(((SUM(QTD_TOTFIDELIZ)/ SUM(QTD_TOTVENDA))*100),2) ELSE 0 END AS PCT_DIARIO$i, ";
        array_push($meses, substr(ucfirst(strftime("%B", strtotime('2022-'.$i.'-01'))),0,3));
    }


    $sql = "SELECT COD_UNIVEND, NOM_FANTASI, 

                    $selectValues 

                    DAT_MOVIMENTO, 
                    MES
    FROM(
    SELECT vendas_diarias.COD_UNIVEND, uni.NOM_FANTASI, 

    $caseWhen

    DAT_MOVIMENTO, 
    MONTH(DAT_MOVIMENTO) MES
    FROM vendas_diarias
    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=vendas_diarias.COD_UNIVEND
    WHERE DAT_MOVIMENTO BETWEEN '2022-04-01' AND '2022-06-31' AND uni.COD_UNIVEND IN(96548,96549,96550,96551,96552,96553,96554,96555,96556,96557,96559,96560,96561,96562,96563,96564,96567,96568,96569,96570,96571,96573,96574,96575,96576,96577,96579,96580,96581,96582,96583,96584,96585,96586,96587,96588,96589,96591,96592,96593,96594,96596,96598,96599,96600,96601,96602,96603,96604,96605,96606,96607,96608,96609,96610,96612,96613,96614,96616,96617,96618,96619,96620,96621,96623,96626,96627,96628,96629,96630,96631,96632,96633,96634,96638,96639,96641,96643,96644,96645,96646,96647,96648,96649,96650,96651,96653,96654,96655,96656,96658,96659,96663,96664,96666,96667,96668,96671,96673,96675,96677,96678,96679,96680,96684,96686,96690,96691,96692,96694,96695,96696,96697,96698,96699,96702,96703,96705,96706,96707,96709,96710,96711,96713,96714,96715,96716,96717,96718,96720,96721,96722,96726,96727,96728,96729,96730,96731,96732,96733,96734,96735,96736,96739,96741,96742,96743,96745,96747,96748,96754,96755,96756,96757,96758,96759,96760,96761,96762,96763,96764,96765,96767,96768,96769,96770,96771,96772,96773,96775,96777,96778,96779,96780,96922,96930,97004,97006,97013,97015,97093,97094,97140,97152,97161,97167,97168,97173,97197,97264,97277,97390,97391,97392,97396,97506,97624,97636,97699,97700,97743)
    GROUP BY COD_UNIVEND, MONTH(DAT_MOVIMENTO))tmpvendasmovi
    GROUP BY COD_UNIVEND";

    $arrQuery = mysqli_query(connTemp(77,''),$sql);

    $arrResult = array();

    while($qrMes = mysqli_fetch_assoc($arrQuery)){

        $arrResult[$qrMes[COD_UNIVEND]][NOM_FANTASI] = $qrMes["NOM_FANTASI"];

        for ($i=$mesIni[1]+0; $i <= $mesFim[1] ; $i++) { 

            $arrResult[$qrMes[COD_UNIVEND]]["MESES"][substr(ucfirst(strftime("%B", strtotime('2022-'.$i.'-01'))),0,3)] = fnValor($qrMes["MES_$i"],2);

        }

    }

    echo "<pre>";
    print_r($arrResult);
    echo "</pre>";

?>
