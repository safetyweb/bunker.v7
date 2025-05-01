<?php

include "_system/_functionsMain.php";

$conadmmysql=$connAdm->connAdm();
$contemporaria= connTemp(19, '');

function fnCompString($arrayWs,$cod_empresa,$cpf,$CONNADM,$conntmp){ 

            $buscli= "SELECT
                    NOM_CLIENTE as NOM_USUARIO,DES_EMAILUS,NUM_CELULAR,DAT_NASCIME,DES_SENHAUS
                    FROM clientes

                     WHERE cod_empresa=$cod_empresa  and
                          case when num_cgcecpf='$cpf' then  1
                               when num_cartao='$cpf' then  2
                      ELSE 0 END  IN (1,2)";
  
                     
            $sqldados=mysqli_query($conntmp, $buscli);
            while($fields=mysqli_fetch_field($sqldados))
            {   
              $chaves_desejadas[$fields->name] =$fields->name;
            }
            $rsdados= mysqli_fetch_assoc($sqldados);
            
            $array_filtrado = array_intersect_key($arrayWs, array_flip($chaves_desejadas));
            // Comparar os valores do $request com os valores do banco de dados
            $diferencas = array_diff_assoc($array_filtrado, $rsdados);
           // Verifique as diferenças e sinalize-as
            if (empty($diferencas)) {
                echo "Não há diferenças.";
            } else {
               
                $insqllog= "insert INTO log_alter_clientes (	COD_CLIENTE, 
                                                                COD_EMPRESA, 
                                                                COD_ENTIDAD, 
                                                                NOM_CLIENTE, 
                                                                DES_APELIDO, 
                                                                DES_CONTATO, 
                                                                DES_SENHAUS, 
                                                                LOG_USUARIO, 
                                                                DES_EMAILUS, 
                                                                DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                COD_ALTERAC, 
                                                                DAT_ALTERAC, 
                                                                COD_EXCLUSA, 
                                                                DAT_EXCLUSA, 
                                                                NUM_CGCECPF, 
                                                                TIP_CLIENTE, 
                                                                LOG_ESTATUS, 
                                                                LOG_TROCAPROD,
                                                                NUM_RGPESSO, 
                                                                DAT_NASCIME, 
                                                                COD_ESTACIV, 
                                                                COD_SEXOPES, 
                                                                NUM_TENTATI, 
                                                                NUM_TELEFON, 
                                                                NUM_CELULAR, 
                                                                NUM_COMERCI, 
                                                                COD_EXTERNO, 
                                                                NUM_CARTAO,
                                                                DES_ENDEREC, 
                                                                NUM_ENDEREC, 
                                                                DES_COMPLEM, 
                                                                DES_BAIRROC, 
                                                                NUM_CEPOZOF, 
                                                                NOM_CIDADEC, 
                                                                COD_ESTADOF, 
                                                                COD_PROFISS, 
                                                                COD_UNIVEND, 
                                                                COD_UNIVEND_PREF,
                                                                LOG_FIDELIDADE, 
                                                                LOG_EMAIL,
                                                                LOG_SMS, 
                                                                LOG_TELEMARK, 
                                                                LOG_WHATSAPP, 
                                                                LOG_PUSH, 
                                                                LOG_FIDELIZADO, 
                                                                DES_COMENT, 
                                                                NOM_PAI, 
                                                                NOM_MAE, 
                                                                IDADE,
                                                                DIA,
                                                                MES, 
                                                                ANO, 
                                                                LOG_AVULSO, 
                                                                COD_MAQUINA, 
                                                                COD_VENDEDOR, 
                                                                DAT_ULTCOMPR, 
                                                                COD_MULTEMP,
                                                                KEY_EXTERNO, 
                                                                COD_TPCLIENTE, 
                                                                COD_ATENDENTE,
                                                                DAT_PRICOMPR,
                                                                LOG_FUNCIONA, 
                                                                LOG_ATIVCAD,
                                                                LOG_CADOK,
                                                                COD_CATEGORIA,
                                                                COD_CATEGORIA_U, 
                                                                LAT,
                                                                LNG, 
                                                                COD_INDICAD, 
                                                                DAT_INDICAD, 
                                                                ID_ASSOCIADO,
                                                                COD_FREQUENCIA, 
                                                                VAL_FREQUENCIA, 
                                                                COD_FREQUENCIA_U, 
                                                                VAL_FREQUENCIA_U, 
                                                                LOG_CADTOTEM,
                                                                COD_CADPESQ,
                                                                COD_UNIVEND_ANT, 
                                                                LOG_OFERTAS,
                                                                DES_TOKEN,
                                                                LOG_TERMO ) SELECT COD_CLIENTE, 
                                                                COD_EMPRESA, 
                                                                COD_ENTIDAD, 
                                                                NOM_CLIENTE, 
                                                                DES_APELIDO, 
                                                                DES_CONTATO, 
                                                                DES_SENHAUS, 
                                                                LOG_USUARIO, 
                                                                DES_EMAILUS, 
                                                                DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                COD_ALTERAC, 
                                                                DAT_ALTERAC, 
                                                                COD_EXCLUSA, 
                                                                DAT_EXCLUSA, 
                                                                NUM_CGCECPF, 
                                                                TIP_CLIENTE, 
                                                                LOG_ESTATUS, 
                                                                LOG_TROCAPROD,
                                                                NUM_RGPESSO, 
                                                                DAT_NASCIME, 
                                                                COD_ESTACIV, 
                                                                COD_SEXOPES, 
                                                                NUM_TENTATI, 
                                                                NUM_TELEFON, 
                                                                NUM_CELULAR, 
                                                                NUM_COMERCI, 
                                                                COD_EXTERNO, 
                                                                NUM_CARTAO,
                                                                DES_ENDEREC, 
                                                                NUM_ENDEREC, 
                                                                DES_COMPLEM, 
                                                                DES_BAIRROC, 
                                                                NUM_CEPOZOF, 
                                                                NOM_CIDADEC, 
                                                                COD_ESTADOF, 
                                                                COD_PROFISS, 
                                                                COD_UNIVEND, 
                                                                COD_UNIVEND_PREF,
                                                                LOG_FIDELIDADE, 
                                                                LOG_EMAIL,
                                                                LOG_SMS, 
                                                                LOG_TELEMARK, 
                                                                LOG_WHATSAPP, 
                                                                LOG_PUSH, 
                                                                LOG_FIDELIZADO, 
                                                                DES_COMENT, 
                                                                NOM_PAI, 
                                                                NOM_MAE, 
                                                                IDADE,
                                                                DIA,
                                                                MES, 
                                                                ANO, 
                                                                LOG_AVULSO, 
                                                                COD_MAQUINA, 
                                                                COD_VENDEDOR, 
                                                                DAT_ULTCOMPR, 
                                                                COD_MULTEMP,
                                                                KEY_EXTERNO, 
                                                                COD_TPCLIENTE, 
                                                                COD_ATENDENTE,
                                                                DAT_PRICOMPR,
                                                                LOG_FUNCIONA, 
                                                                LOG_ATIVCAD,
                                                                LOG_CADOK,
                                                                COD_CATEGORIA,
                                                                COD_CATEGORIA_U, 
                                                                LAT,
                                                                LNG, 
                                                                COD_INDICAD, 
                                                                DAT_INDICAD, 
                                                                ID_ASSOCIADO,
                                                                COD_FREQUENCIA, 
                                                                VAL_FREQUENCIA, 
                                                                COD_FREQUENCIA_U, 
                                                                VAL_FREQUENCIA_U, 
                                                                LOG_CADTOTEM,
                                                                COD_CADPESQ,
                                                                COD_UNIVEND_ANT, 
                                                                LOG_OFERTAS,
                                                                DES_TOKEN,
                                                                LOG_TERMO FROM clientes 
                                    WHERE cod_empresa=$cod_empresa  and
                                                 case when num_cgcecpf='$cpf' then  1
                                                      when num_cartao='$cpf' then  2
                                             ELSE 0 END  IN (1,2)";
            mysqli_query($conntmp, $insqllog); 
        }
}

$rray=Array
(
    'LOG_ESTATUS' => 'S',
    'LOG_TROCAPROD' => 'S',
    'COD_USUARIO' => '690594',
    'NOM_EMPRESA' => 'Rede Duque App',
    'COD_EMPRESA' => '19',
    'NOM_USUARIO' => 'Diogo L de Souza',
    'NUM_CARTAO' => '1734200014',
    'NUM_CGCECPF' => '017.342.000-14',
    'DAT_NASCIME' => '22/08/2000',
    'COD_SEXOPES' => '1',
    'DES_EMAILUS' => 'diogo_tank@hotmail.com',
    'NUM_CELULAR' => '48996243831',
    'LOG_FIDELIZADO' => 'S',
    'LOG_EMAIL' => 'S',
    'LOG_SMS' => 'S',
    'LOG_WHATSAPP' => 'S',
    'LOG_PUSH' => 'S',
    'LOG_OFERTAS' => 'S',
    'LOG_TELEMARK' => 'S',
    'DAT_CADASTR_CLI' => '23/01/2023 11:08:05',
    'CANAL_CAD' => 'HOTSITE',
    'DAT_CADASTR_CANAL' => '23/01/2023 11:08:19',
    'COD_TIPOATV' => '0',
    'COD_TPFILTRO_0' => '2',
    'COD_TPFILTRO_1' => '1',
    'COD_FILTRO_1' => '3',
    'NUM_CEPOZOF' => '89230-749',
    'DAT_CADASTR' => '23/01/2023 11:08:05',
    'NUM_TENTATI' => '1',
    'COD_UNIVEND' => '669',
    'INATIVOU_CLI' => 'N',
    'REFRESH_FILTRO' => 'N',
    'REFRESH_CLIENTE' => 'N',
    'COUNT_FILTROS' => '2',
    'TIP_CLIENTE' => 'F',
    'COD_CHAVECO' => '1',
    'opcao' => 'ALT',
    'hashForm' => '67438164',
    'hHabilitado' => 'S'
);
$conadmmysql=$connAdm->connAdm();
$contemporaria= connTemp(19, '');
$tesr=fnCompString($rray,'19','01734200014',$connAdm->connAdm(),connTemp(19, ''));
?>