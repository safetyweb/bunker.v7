<?php
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Cache-Control: max-age=3600");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600) . " GMT"); // Exemplo: expira em 1 hora
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include '../_system/_functionsMain.php';

$passmarka= getallheaders();
if(!array_key_exists('authorizationCode', $passmarka))
{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
    echo json_decode(json_encode($erroinformation),JSON_PRETTY_PRINT);     
    exit();  
}
$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);
//validação do usuario
$admconn=$connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$arraydadosaut['0']."', '".fnEncode($arraydadosaut['1'])."','','','".$arraydadosaut['4']."','','')";
$buscauser=mysqli_query($admconn,$sql);
if(empty($buscauser->num_rows)) 
{
    http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
    echo json_decode(json_encode($erroinformation),JSON_PRETTY_PRINT);
    exit();    
}   
$user=mysqli_fetch_assoc($buscauser);
//================fim da validação de senha
//abrindo a com temporaria
$conexaotmp= connTemp($arraydadosaut['4'], '');
//====fim da conexão com a empresa
if(!array_key_exists('4', $arraydadosaut))
{

   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo json_decode(json_encode($erroinformation),JSON_PRETTY_PRINT);
     exit();    
}

//capturar o layout da base de dados 
$layout="SELECT * FROM totem_app WHERE cod_empresa=".$arraydadosaut['4'];
$buscauser=mysqli_fetch_assoc(mysqli_query($conexaotmp,$layout));
$rand=fnEncode(microtime());
$key=base64_encode(fnEncode($arraydadosaut['4']));

$array=array(


            "EstilosApp"=>array(   

                                 "DES_LOGO" => 'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],
                                 "DES_IMGBACK" => $buscauser['DES_IMGBACK'],
                                 "COR_BACKBAR" => $buscauser['COR_BACKBAR'],
                                 "COR_BACKPAG" => $buscauser['COR_BACKPAG'],
                                 "COR_TITULOS" => $buscauser['COR_TITULOS'],
                                 "COR_TEXTOS" => $buscauser['COR_TEXTOS'],
                                 "COR_BOTAO" => $buscauser['COR_BOTAO'],
                                 "COR_BOTAOON" => $buscauser['COR_BOTAOON'],
                                 "COR_FULLPAG" => $buscauser['COR_FULLPAG'],
                                 "COR_TEXTFULL" => $buscauser['COR_TEXTFULL']
                          ),
            "navegacao"=>array(
                                 "DES_LOGO" => 'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],
                                 "COR_BACKBAR" => $buscauser['COR_BACKBAR'],
                                 'textoContraste'=>$buscauser['COR_TEXTFULL'],
                                 'links'=>array('INTRO'=>'https://adm.bunkerapp.com.br/app/intro.do?key='.$key.'&t='.$rand,
                                             'novoMenu'=>'https://adm.bunkerapp.com.br/app/novoMenu.do?key='.$key.'&t='.$rand
                              )
             ),
            "intro"=>array(
                            'pagina'=>'intro',   
                            'background'=>$buscauser['COR_FULLPAG'],
                            'logotipo'=>'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],
                            'corbotao'=>$buscauser['COR_BOTAO'],
                            'corbotaoOn'=>$buscauser['COR_BOTAOON'],
                            'textoContraste'=>$buscauser['COR_TEXTFULL'],
                            'background_img'=>'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['des_imgback'],
                            'links'=>array('CADASTRE_SE'=>'https://adm.bunkerapp.com.br/app/consulta_V2.do?key='.$key.'&t='.$rand,
                                           'FALE_CONOSCO'=>'https://adm.bunkerapp.com.br/app/faleConosco.do?key='.$key.'&t='.$rand,
                                           'SEJA_NOSSO_PARCEIRO'=>'https://adm.bunkerapp.com.br/app/parceiro.do?key='.$key.'&t='.$rand
                                          )
                            ),
            "app"=>array(
                           'pagina'=>'app', 
                           'parent'=>'intro',
                           "tituloPagina"=>"Faça seu login",
                           "DES_LOGO" => 'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],
                           "COR_BOTAO" => $buscauser['COR_BOTAO'],
                           "COR_BOTAOON" => $buscauser['COR_BOTAOON'],
                           'links'=>array('CADASTRE_SE'=>'https://adm.bunkerapp.com.br/app/consulta_V2.do?key='.$key.'&t='.$rand,
                                       'Esqueci_minha_senha'=>'https://adm.bunkerapp.com.br/app/faleConosco.do?key='.$key.'&t='.$rand
                                      )
                        ),
             "recuperacaoSenha"=>array(
                                       'pagina'=>'recuperacaoSenha',
                                       'parent'=>'app',
                                       "tituloPagina"=>"Recuperar Senha",
                                       "DES_LOGO" => 'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],

                                      ),
             "validaDados"=>array(
                                       'pagina'=>'validaDados',
                                       'parent'=>'recuperacaoSenha',
                                       "DES_LOGO" => 'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],
                                       "tituloPagina"=>"Validação",
                                       'links'=>array('validacao_token'=>'https://adm.bunkerapp.com.br/app/validaDados.do?key='.$key.'&t='.$rand,
                                                      'validacao_dados'=>'https://adm.bunkerapp.com.br/app/validaDados_v2.do?key='.$key.'&t='.$rand
                                                     )
                                      ),
             "novoMenu"=>array(
                                       'pagina'=>'novoMenu',
                                       'parent'=>'app',
                                       "DES_LOGO" => 'https://img.bunker.mk/media/clientes/'.$arraydadosaut['4'].'/'.$buscauser['DES_LOGO'],
                                       "tituloPagina"=>"Navegação",
                                       "colunasDuplas"=>"Navegação",
                                       'links'=>array(
                                                      'validacao_dados'=>'https://adm.bunkerapp.com.br/app/cadVeiculo.do?key='.$key.'&t='.$rand,
                                                      'ofertas'=>'https://adm.bunkerapp.com.br/app/ofertas.do?key='.$key.'&t='.$rand,
                                                      'jornal'=>'https://adm.bunkerapp.com.br/app/banner.do?key='.$key.'&t='.$rand,
                                                      'minhas_compras'=>'https://adm.bunkerapp.com.br/app/habito.do?key='.$key.'&t='.$rand,
                                                      'meus_dados'=>'https://adm.bunkerapp.com.br/app/cadastro_V2.do?key='.$key.'&t='.$rand,
                                                      'mensagens'=>'https://adm.bunkerapp.com.br/app/historicoPush.do?key='.$key.'&t='.$rand,
                                                      'premios'=>'https://adm.bunkerapp.com.br/app/premios.do?key='.$key.'&t='.$rand,
                                                      'parceiros'=>'https://adm.bunkerapp.com.br/app/parceiros.do?key='.$key.'&t='.$rand,
                                                      'fale_conosco'=>'https://adm.bunkerapp.com.br/app/faleConosco.do?key='.$key.'&t='.$rand,
                                                      'logout'=>'https://adm.bunkerapp.com.br/app/intro.do?key='.$key.'&t='.$rand,
                                                      'cashback'=>'https://adm.bunkerapp.com.br/app/relGanhos.do.do?key='.$key.'&t='.$rand,
                                                      'historico'=>'https://adm.bunkerapp.com.br/app/relCompras.do.do?key='.$key.'&t='.$rand,
                                                      'enderecos'=>'https://adm.bunkerapp.com.br/app/regioes.do.do?key='.$key.'&t='.$rand,
                                                      'enderecos_duque'=>'https://adm.bunkerapp.com.br/app/enderecosDuque.php.do?key='.$key.'&t='.$rand
                                                     ),
                                       'menu'=>array(

                                                'LOG_COLUNAS'=>$buscauser['LOG_COLUNAS'],
                                                'LOG_OFERTAS'=>$buscauser['LOG_OFERTAS'],
                                                'LOG_JORNAL'=>$buscauser['LOG_JORNAL'],
                                                'LOG_HABITO'=>$buscauser['LOG_HABITO'],
                                                'LOG_DADOS'=>$buscauser['LOG_DADOS'],
                                                'LOG_EXTRATO'=>$buscauser['LOG_EXTRATO'],
                                                'LOG_PREMIOS'=>$buscauser['LOG_PREMIOS'],
                                                'LOG_ENDERECOS'=>$buscauser['LOG_ENDERECOS'],
                                                'LOG_PARCEIROS'=>$buscauser['LOG_PARCEIROS'],
                                                'LOG_COMUNICA'=>$buscauser['LOG_COMUNICA'],
                                                'LOG_MENSAGEM'=>$buscauser['LOG_MENSAGEM'],
                                                'LOG_BANNERHOME'=>$buscauser['LOG_BANNERHOME'],
                                                'LOG_BANNERLISTA'=>$buscauser['LOG_BANNERLISTA'],
                                                'LOG_TOKEN'=>$buscauser['LOG_TOKEN'],
                                                'LOG_VEICULO'=>$buscauser['LOG_VEICULO']
                                       )
                                      ),

    
            );

 echo    json_encode($array,JSON_PRETTY_PRINT);
 */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Cache-Control: public, max-age=3600");

// Incluindo a função principal
include_once '../_system/_functionsMain.php';

$passmarka = getallheaders();
if (!array_key_exists('authorizationCode', $passmarka)) {
    http_response_code(400);
    echo json_encode([
        "errors" => [
            [
                "message" => "Informe uma chave de acesso válida!",
                "coderro" => "400"
            ]
        ]
    ], JSON_PRETTY_PRINT);
    exit();
}

$autoriz = fndecode(base64_decode($passmarka['authorizationCode']));
$arraydadosaut = explode(';', $autoriz);

// Validação do usuário
$admconn = $connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('{$arraydadosaut[0]}', '" . fnEncode($arraydadosaut[1]) . "', '', '', '{$arraydadosaut[4]}', '', '')";
$buscauser = mysqli_query($admconn, $sql);
if (empty($buscauser->num_rows)) {
    http_response_code(400);
    echo json_encode([
        "errors" => [
            [
                "message" => "Usuário ou senha inválido!",
                "coderro" => "400"
            ]
        ]
    ], JSON_PRETTY_PRINT);
    exit();
}

$user = mysqli_fetch_assoc($buscauser);

// Abrindo a conexão temporária
$conexaotmp = connTemp($arraydadosaut[4], '');

// Cache de consulta ao banco de dados
/*$cacheFile = 'cache/totem_app_' . $arraydadosaut[4] . '.json';
$cacheTime = 3600; // 1 hora

if (file_exists($cacheFile) && (filemtime($cacheFile) + $cacheTime > time())) {
    $buscauser = json_decode(file_get_contents($cacheFile), true);
} else {
    $layout = "SELECT * FROM totem_app WHERE cod_empresa=" . $arraydadosaut[4];
    $result = mysqli_query($conexaotmp, $layout);
    $buscauser = mysqli_fetch_assoc($result);
    file_put_contents($cacheFile, json_encode($buscauser));
}*/
// Diretório de cache
$cacheDir = __DIR__ . '/cache';

// Verifica se o diretório existe; se não, tenta criá-lo com permissões 0755
if (!is_dir($cacheDir)) {
    if (!mkdir($cacheDir, 0755, true)) {
        die("Erro: Não foi possível criar o diretório de cache.");
    }
}

// Sanitiza o código da empresa para garantir que seja um número inteiro
$codEmpresa = intval($arraydadosaut[4]);

// Define o arquivo de cache com base no código da empresa
$cacheFile = $cacheDir . '/totem_app_' . $codEmpresa . '.json';
$cacheTime = 3600; // Tempo de validade do cache: 1 hora

// Verifica se o arquivo de cache existe e se ainda está válido
if (file_exists($cacheFile) && (filemtime($cacheFile) + $cacheTime > time())) {
    // Lê os dados do cache
    $buscauser = json_decode(file_get_contents($cacheFile), true);
} else {
    // Monta a consulta SQL utilizando o código da empresa sanitizado
    $layout = "SELECT * FROM totem_app WHERE cod_empresa = {$codEmpresa}";
    $result = mysqli_query($conexaotmp, $layout);

    if (!$result) {
        die("Erro na consulta: " . mysqli_error($conexaotmp));
    }

    $buscauser = mysqli_fetch_assoc($result);

    // Salva os dados no cache somente se houver resultado
    if ($buscauser !== null) {
        file_put_contents($cacheFile, json_encode($buscauser));
    }
}

// Definindo o `Last-Modified` com base na última modificação relevante
$lastModifiedTime = gmdate("D, d M Y H:i:s", strtotime($buscauser['LAST_MODIFIED'])) . " GMT";
header("Last-Modified: " . $lastModifiedTime);

// Criando um `ETag`
$etag = md5($buscauser['DES_LOGO'] . $buscauser['COR_BACKBAR']);
header("ETag: \"$etag\"");

// Verificando os cabeçalhos `If-Modified-Since` e `If-None-Match`
if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] === $lastModifiedTime) ||
    (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag)
) {
    http_response_code(304);
    exit();
}

// Geração do restante dos dados para a resposta
$rand = fnEncode(microtime());
$key = base64_encode(fnEncode($arraydadosaut[4]));

$array = [
    "EstilosApp" => [
        "DES_LOGO" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO'],
        "DES_LOGOINI" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGOINI'],
        "DES_IMGBACK" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_IMGBACK'],
        "DES_BANNERINI" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_BANNERINI'],
        "DES_IMGBACKINI" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_IMGBACKINI'],
        "COR_BACKBAR" => $buscauser['COR_BACKBAR'],
        "COR_BACKPAG" => $buscauser['COR_BACKPAG'],
        "COR_TITULOS" => $buscauser['COR_TITULOS'],
        "COR_TEXTOS" => $buscauser['COR_TEXTOS'],
        "COR_BOTAO" => $buscauser['COR_BOTAO'],
        "COR_BOTAOON" => $buscauser['COR_BOTAOON'],
        "COR_FULLPAG" => $buscauser['COR_FULLPAG'],
        "COR_TEXTFULL" => $buscauser['COR_TEXTFULL']
    ],
    "navegacao" => [
        "DES_LOGO" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO'],
        "DES_LOGOINI" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGOINI'],
        "COR_BACKBAR" => $buscauser['COR_BACKBAR'],
        'textoContraste' => $buscauser['COR_TEXTFULL'],
        'links' => [
            'INTRO' => 'https://adm.bunkerapp.com.br/app/intro.do?key=' . $key . '&t=' . $rand,
            'novoMenu' => 'https://adm.bunkerapp.com.br/app/novoMenu.do?key=' . $key . '&t=' . $rand
        ]
    ],
    "intro" => [
        'pagina' => 'intro',
        'background' => $buscauser['COR_FULLPAG'],
        'logotipo_inicio' => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGOINI'],
        'logotipo' => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO'],
        'corbotao' => $buscauser['COR_BOTAO'],
        'corbotaoOn' => $buscauser['COR_BOTAOON'],
        'textoContraste' => $buscauser['COR_TEXTFULL'],
        'background_img' => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_IMGBACKINI'],
        'banner_img' => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_BANNERINI'],
        'links' => [
            'CADASTRE_SE' => 'https://adm.bunkerapp.com.br/app/consulta_V2.do?key=' . $key . '&t=' . $rand,
            'FALE_CONOSCO' => 'https://adm.bunkerapp.com.br/app/faleConosco.do?key=' . $key . '&t=' . $rand,
            'SEJA_NOSSO_PARCEIRO' => 'https://adm.bunkerapp.com.br/app/parceiro.do?key=' . $key . '&t=' . $rand
        ]
    ],
    "app" => [
        'pagina' => 'app',
        'parent' => 'intro',
        "tituloPagina" => "Faça seu login",
        "DES_LOGO" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO'],
        "COR_BOTAO" => $buscauser['COR_BOTAO'],
        "COR_BOTAOON" => $buscauser['COR_BOTAOON'],
        'links' => [
            'CADASTRE_SE' => 'https://adm.bunkerapp.com.br/app/consulta_V2.do?key=' . $key . '&t=' . $rand,
            'Esqueci_minha_senha' => 'https://adm.bunkerapp.com.br/app/faleConosco.do?key=' . $key . '&t=' . $rand
        ]
    ],
    "recuperacaoSenha" => [
        'pagina' => 'recuperacaoSenha',
        'parent' => 'app',
        "tituloPagina" => "Recuperar Senha",
        "DES_LOGO" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO']
    ],
    "validaDados" => [
        'pagina' => 'validaDados',
        'parent' => 'recuperacaoSenha',
        "DES_LOGO" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO'],
        "tituloPagina" => "Validação",
        'links' => [
            'validacao_token' => 'https://adm.bunkerapp.com.br/app/validaDados.do?key=' . $key . '&t=' . $rand,
            'validacao_dados' => 'https://adm.bunkerapp.com.br/app/validaDados_v2.do?key=' . $key . '&t=' . $rand
        ]
    ],
    "novoMenu" => [
        'pagina' => 'novoMenu',
        'parent' => 'app',
        "DES_LOGO" => 'https://img.bunker.mk/media/clientes/' . $arraydadosaut[4] . '/' . $buscauser['DES_LOGO'],
        "tituloPagina" => "Navegação",
        "colunasDuplas" => "Navegação",
        'links' => [
            'validacao_dados' => 'https://adm.bunkerapp.com.br/app/cadVeiculo.do?key=' . $key . '&t=' . $rand,
            'ofertas' => 'https://adm.bunkerapp.com.br/app/ofertas.do?key=' . $key . '&t=' . $rand,
            'jornal' => 'https://adm.bunkerapp.com.br/app/banner.do?key=' . $key . '&t=' . $rand,
            'minhas_compras' => 'https://adm.bunkerapp.com.br/app/habito.do?key=' . $key . '&t=' . $rand,
            'meus_dados' => 'https://adm.bunkerapp.com.br/app/cadastro_V2.do?key=' . $key . '&t=' . $rand,
            'mensagens' => 'https://adm.bunkerapp.com.br/app/historicoPush.do?key=' . $key . '&t=' . $rand,
            'premios' => 'https://adm.bunkerapp.com.br/app/premios.do?key=' . $key . '&t=' . $rand,
            'parceiros' => 'https://adm.bunkerapp.com.br/app/parceiros.do?key=' . $key . '&t=' . $rand,
            'fale_conosco' => 'https://adm.bunkerapp.com.br/app/faleConosco.do?key=' . $key . '&t=' . $rand,
            'meus_amigos' => 'https://adm.bunkerapp.com.br/app/manutencao.do?key=' . $key . '&t=' . $rand,
            'meus_premios' => 'https://adm.bunkerapp.com.br/app/manutencao.do?key=' . $key . '&t=' . $rand,
            'fale_conosco' => 'https://adm.bunkerapp.com.br/app/faleConosco.do?key=' . $key . '&t=' . $rand,
            'logout' => 'https://adm.bunkerapp.com.br/app/intro.do?key=' . $key . '&t=' . $rand,
            'cashback' => 'https://adm.bunkerapp.com.br/app/relGanhos.do?key=' . $key . '&t=' . $rand,
            'historico' => 'https://adm.bunkerapp.com.br/app/relCompras.do?key=' . $key . '&t=' . $rand,
            'enderecos' => 'https://adm.bunkerapp.com.br/app/regioes.do?key=' . $key . '&t=' . $rand,
            'enderecos_duque' => 'https://adm.bunkerapp.com.br/app/enderecosDuque.do?key=' . $key . '&t=' . $rand
        ],
        'menu' => [
            'LOG_COLUNAS' => $buscauser['LOG_COLUNAS'],
            'LOG_OFERTAS' => $buscauser['LOG_OFERTAS'],
            'LOG_JORNAL' => $buscauser['LOG_JORNAL'],
            'LOG_HABITO' => $buscauser['LOG_HABITO'],
            'LOG_DADOS' => $buscauser['LOG_DADOS'],
            'LOG_EXTRATO' => $buscauser['LOG_EXTRATO'],
            'LOG_PREMIOS' => $buscauser['LOG_PREMIOS'],
            'LOG_ENDERECOS' => $buscauser['LOG_ENDERECOS'],
            'LOG_PARCEIROS' => $buscauser['LOG_PARCEIROS'],
            'LOG_COMUNICA' => $buscauser['LOG_COMUNICA'],
            'LOG_AMIGOS' => $buscauser['LOG_AMIGOS'],
            'LOG_BRINDES' => $buscauser['LOG_BRINDES'],
            'LOG_MENSAGEM' => $buscauser['LOG_MENSAGEM'],
            'LOG_BANNERHOME' => $buscauser['LOG_BANNERHOME'],
            'LOG_BANNERLISTA' => $buscauser['LOG_BANNERLISTA'],
            'LOG_TOKEN' => $buscauser['LOG_TOKEN'],
            'LOG_VEICULO' => $buscauser['LOG_VEICULO']
        ]
    ]
];

echo json_encode($array, JSON_PRETTY_PRINT);
