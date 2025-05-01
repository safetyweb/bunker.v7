<?php
//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $request = md5(implode($_POST));

  if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  } else {
    $_SESSION['last_request'] = $request;

    $cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
    $des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
    $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];

    if ($opcao != '') {

      //mensagem de retorno
      switch ($opcao) {
        case 'CAD':
          $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
          break;
        case 'ALT':
          $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
          break;
        case 'EXC':
          $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
          break;
          break;
      }
      $msgTipo = 'alert-success';
    }
  }
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
  //busca dados da empresa
  $cod_empresa = fnDecode($_GET['id']);
  $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
  //fnEscreve($sql);
  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
  $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

  if (isset($arrayQuery)) {
    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
  }
} else {
  $cod_empresa = 0;
  //fnEscreve('entrou else');
}

//busca perfil do usuário 
//4 - fidelidade
$sql1 = "select cod_usuario,cod_defsist,cod_perfils
			from usuarios
			where cod_empresa = " . $_SESSION["SYS_COD_EMPRESA"] . " and
				  cod_defsist = 4 and
				  cod_usuario = " . $_SESSION["SYS_COD_USUARIO"] . " ";

//fnEscreve($sql1);			  
if ($_SESSION["SYS_COD_SISTEMA"] == 3) {
  $cod_perfils = '9999';
} else {
  $arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1) or die(mysqli_error());
  $qrBuscaPerfil = mysqli_fetch_assoc($arrayQuery1);
  $cod_perfils = $qrBuscaPerfil['cod_perfils'];
}

//busca modulos autorizados
$sql2 = "select cod_modulos from perfil
			where cod_sistema=4 and
			cod_perfils in($cod_perfils)";

//fnEscreve($sql2);			
$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2) or die(mysqli_error());

$count = 0;
while ($qrBuscaAutorizacao = mysqli_fetch_assoc($arrayQuery2)) {
  $cod_modulos_aut = $qrBuscaAutorizacao['cod_modulos'];
  $modulosAutorizados = $modulosAutorizados . $cod_modulos_aut . ",";
}

$arrayAutorizado = explode(",", $modulosAutorizados);


//fnEscreve($sql2);

$arrayParamAutorizacao = array('COD_MODULO' => "9999",
    'MODULOS_AUT' => $arrayAutorizado,
    'COD_SISTEMA' => $_SESSION["SYS_COD_SISTEMA"]);

//echo "<pre>";	
//print_r($arrayParamAutorizacao);	
//echo "</pre>";	

function fnAutRelatorio($codRelatorio, $paramAutRelatorio) {
  $arrayCompara = $paramAutRelatorio['MODULOS_AUT'];
  //se sistema adm marka
  if ($paramAutRelatorio['COD_SISTEMA'] == 3) {
    $retornoAut = true;
  } else {
    if (recursive_array_search($codRelatorio, $arrayCompara) !== false) {
      $retornoAut = true;
    } else {
      $retornoAut = false;
    }
  }
  return $retornoAut;
}

//fnEscreve($cod_perfils);
//fnEscreve($modulosAutorizados);
//fnEscreve($_SESSION["SYS_COD_USUARIO"]);
//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);	
//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);	
//fnMostraForm();
//fnEscreve($modulosRelatorios);
?>


<style>


  #services {}
  #services .services-top {
    padding: 70px 0 50px;
  }
  #services .services-list {
    padding-top: 50px;
  }
  .services-list .service-block {
    margin-bottom: 25px;
  }
  .services-list .service-block .ico {
    font-size: 38px;
    float: left;
  }
  .services-list .service-block .text-block {
    margin-left: 58px;
  }
  .services-list .service-block .text-block .name {
    font-size: 20px;
    font-weight: 900;
    margin-bottom: 5px;
  }
  .services-list .service-block .text-block .info {
    font-size: 16px;
    font-weight: 300;
    margin-bottom: 10px;
  }
  .services-list .service-block .text-block .text {
    font-size: 12px;
    line-height: normal;
    font-weight: 300;
  }
  .highlight {
    color: #2ac5ed;
  }                    

</style>

<div class="push30"></div> 

<div class="row">				

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <div class="portlet portlet-bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="fal fa-terminal"></i>
          <span class="text-primary"><?php echo $NomePg; ?></span>
        </div>

<?php
$formBack = "1048";
include "atalhosPortlet.php";
?>	

      </div>
      <div class="portlet-body">

<?php if ($msgRetorno <> '') { ?>	
          <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <?php echo $msgRetorno; ?>
          </div>
        <?php } ?>

        <div class="login-form">

            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                <h4><?php echo $nom_empresa; ?></h4>

                <div class="push30"></div>

                <div class="row">
                    <div class="services-list">

                        <div class="row" style="margin: 0 0 0 1px;">

                            <div class="col-sm-6 col-md-3">

                                <div class="service-block" style="visibility: visible;">
                                    <div class="ico fal fa-wrench highlight"></div>
                                    <div class="text-block">
                                        <h4>Úteis</h4>
                                        <div class="text">Ferramentas do dia a dia</div>
                                        <div class="push10"></div>

                                        <a href="action.do?mod=<?php echo fnEncode(1210) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Módulos</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1308) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Reprocessameno de Cadastros</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1310) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos em Lote</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1315) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Senhas de Comunicação</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1623); ?>" target="_blank">&rsaquo; Senhas Parceiros </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1458); ?>" target="_blank">&rsaquo; Marka Store - Set Up </a> <br/>

                                        <span class="disabled-link"><a href="action.do?mod=<?php echo fnEncode(1174) . "&id=" . fnEncode($cod_empresa); ?>">&rsaquo; Performance</a> </span><br/>
                                    </div>
                                </div>

                                <div class="push30"></div>

                                <div class="service-block" style="visibility: visible;">
                                    <div class="ico fal fa-layer-plus highlight"></div>
                                    <div class="text-block">
                                        <h4>Overview</h4>
                                        <div class="text">Visão por Empresas</div>
                                        <div class="push10"></div>

                                        <a href="action.do?mod=<?php echo fnEncode(1605) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Overview Funil</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1383) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Overview Ticket</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1307) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Overview Faturamento</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1392) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Overview Marka (testes)</a> <br/>

                                    </div>
                                </div>

                                <div class="push30"></div>

                                <div class="service-block" style="visibility: visible;">
                                    <div class="ico fab fa-whatsapp highlight"></div>
                                    <div class="text-block">
                                        <h4>Easy Chat -  DW</h4>
                                        <div class="text">Api automação de Whats App</div>
                                        <div class="push10"></div>

                                        <a href="action.do?mod=<?php echo fnEncode(1605) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Campanha Damaris</a> <br/>

                                    </div>
                                </div>

                                <div class="push30"></div>



                            </div>



                            <div class="col-sm-6 col-md-3">
                                <div class="service-block" style="visibility: visible;">
                                    <div class="ico fal fa-code highlight"></div>
                                    <div class="text-block">
                                        <h4>Em Desenvolvimento</h4>
                                        <div class="text">No forno</div>
                                        <div class="push10"></div>

                                        <!-- <a href="javascript:void(0)" class="addBox" data-url="action.do?mod=<?php echo fnEncode(2033); ?>&id=<?php echo fnEncode(7); ?>&pop=true" data-title="Template WhatsApp">&rsaquo; Template WhatsApp v3 </a> <br/> -->
                                        <a href="action.do?mod=<?php echo fnEncode(2048); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Comunicação Simplificada Whatsapp </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(2045); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Comunicação Simplificada </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1977); ?>&id=<?php echo fnEncode(7); ?>&idC=69xOpFqeEKk¢" target="_blank">&rsaquo; Documentos (garantia) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1938); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Desafio/Agenda (Multicoisas) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1938); ?>&id=<?php echo fnEncode(219); ?>" target="_blank">&rsaquo; Desafio/Agenda (Kings) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1942); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Link Unidades Agenda (Multicoisas) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1942); ?>&id=<?php echo fnEncode(219); ?>" target="_blank">&rsaquo; Link Unidades Agenda (Kings) </a> <br/>
                                        <!-- <a href="action.do?mod=<?php echo fnEncode(1939); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Agenda do Vendedor Mobile (Multicoisas) </a> <br/> -->
                                        <a href="action.do?mod=<?php echo fnEncode(1942); ?>&id=<?php echo fnEncode(219); ?>" target="_blank">&rsaquo; Link Unidades Agenda </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1737); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Logs de Alteração </a> <br/>



                                        <a href="action.do?mod=<?php echo fnEncode(1710); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Empresa (Mais Cash) </a> <br/>

                                        <a href="action.do?mod=<?php echo fnEncode(1283); ?>" target="_blank">&rsaquo; Tiles</a> <br/>

                                        <a href="action.do?mod=<?php echo fnEncode(1337); ?>" target="_blank">&rsaquo; Banner do Login </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1405); ?>" target="_blank">&rsaquo; Relatório de Chamados SAC </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1421); ?>" target="_blank">&rsaquo; Kanban Chamados SAC </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1443); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Relatório de Agendamentos </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1381); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Desafio</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1493); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Lista Usuários Desafio (vendedor)</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1501); ?>" target="_blank">&rsaquo; Automação de Emails (Config.)</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1515); ?>" target="_blank">&rsaquo; Lista Tutorial (Empresa)</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1119); ?>" target="_blank">&rsaquo; Áreas de Bloqueio</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1539); ?>" target="_blank">&rsaquo; Controle de Horas </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1542); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Teste de envio WhatsApp </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1589); ?>" target="_blank">&rsaquo; Relatório Opt Out SMS </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1593) . "&id=" . fnEncode(136); ?>" target="_blank">&rsaquo; Relatório de Apoiador por Região </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1598) . "&id=" . fnEncode(136); ?>" target="_blank">&rsaquo; Lista de Apoiadores Externos </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1814); ?>" target="_blank">&rsaquo; Transferência de Unidade </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1824) . "&id=" . fnEncode(332); ?>" target="_blank">&rsaquo; Controle de Documentos Campanha</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1820); ?>" target="_blank">&rsaquo; Reservas de Hotel </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1826); ?>" target="_blank">&rsaquo; Reservas de Hotel Lista </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1834); ?>" target="_blank">&rsaquo; Reservas de Hotel Lista Ordenada </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1850) . "&id=" . fnEncode(332); ?>" target="_blank">&rsaquo; Relatório Caixa Campanha </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1861) . "&id=" . fnEncode(19); ?>" target="_blank">&rsaquo; Relatório Vendas Produtos </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1886) . "&id=" . fnEncode(311); ?>" target="_blank">&rsaquo; Personas (Mini) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1887) . "&id=" . fnEncode(311); ?>" target="_blank">&rsaquo; Dash Atendimentos </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1888) . "&id=" . fnEncode(311); ?>" target="_blank">&rsaquo; Lista de Campanhas (Mini) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1903)?>" target="_blank">&rsaquo; Senhas WhatsApp </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1947)?>" target="_blank">&rsaquo; Senhas WhatsApp (Adorai) </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1905)?>" target="_blank">&rsaquo; Emails Marka </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1775)?>" target="_blank">&rsaquo; Lista Tour </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1936)?>" target="_blank">&rsaquo; Artigo Tour </a> <br/>

                                    </div>
                                </div>
                            </div>	

                            <div class="col-sm-6 col-md-3">
                                <div class="service-block" style="visibility: visible;">
                                    <div class="ico fal fa-bug highlight"></div>
                                    <div class="text-block">
                                        <h4>Bug Report</h4>
                                        <div class="text">Opções do Help Desk</div>
                                        <div class="push10"></div>

                                        <a href="action.do?mod=<?php echo fnEncode(1268); ?>" target="_blank">&rsaquo; Help Desk Cliente</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1282); ?>" target="_blank">&rsaquo; Help Desk ADM</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1433); ?>" target="_blank">&rsaquo; Help Desk CON</a> <br/>
                                        <div class="push10"></div>
                                        <a href="action.do?mod=<?php echo fnEncode(1269); ?>" target="_blank">&rsaquo; Plataforma</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1270); ?>" target="_blank">&rsaquo; Solicitação</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1431); ?>" target="_blank">&rsaquo; Subcategoria da Solicitação</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1271); ?>" target="_blank">&rsaquo; Versão de Integração</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1272); ?>" target="_blank">&rsaquo; Status</a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1275); ?>" target="_blank">&rsaquo; Prioridade</a> <br/>


                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="service-block" style="visibility: visible;">
                                    <div class="ico fal fa-clipboard-check highlight"></div>
                                    <div class="text-block">
                                        <h4>Finalizados</h4>
                                        <div class="text" >Em Produção</div>
                                        <div class="push10"></div>

                                        <a href="javascript:void(0)" class="addBox" data-url="/whatsapp-editor/editorTemplate.php?t=<?=mt_rand()?>" data-title="Template WhatsApp">&rsaquo; Template WhatsApp v3 </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1688); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Cadastro de Funcionário </a> <br/>

                                        <a href="action.do?mod=<?php echo fnEncode(1455); ?>&id=<?php echo fnEncode(136); ?>&idP=<?php echo fnEncode(1); ?>" target="_blank">&rsaquo; Configuração de Perfil </a> <br/>

                                        <a href="action.do?mod=<?php echo fnEncode(1671); ?>&id=<?php echo fnEncode(60); ?>" target="_blank">&rsaquo; Link Relatório Detalhes Comunicação </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1669); ?>&id=<?php echo fnEncode(60); ?>" target="_blank">&rsaquo; Relatório Detalhes Comunicação </a> <br/>
                                        <a href="action.do?mod=<?php echo fnEncode(1596); ?>" target="_blank">&rsaquo; Histórico de Compras de Comunicação - Geral (store) </a> <br/>



                                        <div class="push10" style="height:400px; overflow:auto;">

                                            <a href="action.do?mod=<?php echo fnEncode(1588) . "&id=" . fnEncode(77); ?>" target="_blank">&rsaquo; Dash Cash Back</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1329); ?>" target="_blank">&rsaquo; Valores de Rateio</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1300); ?>" target="_blank">&rsaquo; Controle de Metas</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1730); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Cadastro de Entidades</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1717); ?>&id=<?php echo fnEncode(224); ?>" target="_blank">&rsaquo; Lista de Bonificações (iperglass) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1713); ?>&id=<?php echo fnEncode(224); ?>" target="_blank">&rsaquo; Relatório de Lançamentos (iperglass) </a> <br/>
                                            <!--  cod-cliente 21416 -->

                                            <a href="action.do?mod=<?php echo fnEncode(1663); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório Email V2 </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1662); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório SMS V2 </a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1536); ?>" target="_blank">&rsaquo; Home Fidelidade ADM</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1624); ?>&id=<?php echo fnEncode(7); ?>&idx=<?php echo fnEncode(98); ?>" target="_blank">&rsaquo; Personas V2 </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1623); ?>" target="_blank">&rsaquo; Senhas Parceiros </a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1620); ?>&id=<?php echo fnEncode(21); ?>&idP=<?php echo fnEncode(3); ?>" target="_blank">&rsaquo; Relatório de Produtos TO </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1622); ?>&id=<?php echo fnEncode(77); ?>&idP=<?php echo fnEncode(3); ?>" target="_blank">&rsaquo; Relatório de Pesquisas Sintético (diário) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1619); ?>&id=<?php echo fnEncode(77); ?>&idP=<?php echo fnEncode(3); ?>" target="_blank">&rsaquo; Relatório de Pesquisas (diário) </a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1527); ?>&id=<?php echo fnEncode(115); ?>" target="_blank">&rsaquo; Análise de Resgates</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1528); ?>&id=<?php echo fnEncode(115); ?>" target="_blank">&rsaquo; Análise de Campanhas</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1615); ?>&id=<?php echo fnEncode(85); ?>" target="_blank">&rsaquo; Relatório de Vendas Desbloqueadas e Excluídas </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1614); ?>&id=<?php echo fnEncode(58); ?>" target="_blank">&rsaquo; Relatório de Vendas Avulsas </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1612) . "&id=" . fnEncode(7); ?>" target="_blank">&rsaquo; Produtos Sem Resgate</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1608) . "&id=" . fnEncode(119); ?>" target="_blank">&rsaquo; Funil V2</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1591) . "&id=" . fnEncode(135); ?>" target="_blank">&rsaquo; Relatório Retorno SMS </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1595) . "&id=" . fnEncode(12); ?>" target="_blank">&rsaquo; Relatório Comunicação SMS </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1533); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório de Retorno Emails Enviados</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1538); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Relatório de Cupons por Cliente (consolidado)</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1552); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Campanha SMS V1 </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1170); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Campanha email V1 </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1599) . "&id=" . fnEncode(109); ?>" target="_blank">&rsaquo; Relatório de Vendas Estornadas WS </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1669); ?>&id=<?php echo fnEncode(60); ?>" target="_blank">&rsaquo; Relatório Detalhes Email </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1668); ?>" target="_blank">&rsaquo; Relatório Geral Email ADM </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1670); ?>" target="_blank">&rsaquo; Relatório Geral SMS ADM </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1667); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Relatório Membros por Região </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1666); ?>" target="_blank">&rsaquo; Relatório Optout SMS ADM </a> <br/>

                                            <a href="https://adm.bunker.mk/_qrcode.php" target="_blank">&rsaquo; Qr Code</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1485); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Marka Store - Teste</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1485); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Marka Store - Depyl</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1485); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Marka Store - Multicoisas</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1395); ?>&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Lucratividade </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1499); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Monitor de Unidades</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1490); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Dash Analytics</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1480); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatórios de Análises (Índices) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1327); ?>" target="_blank">&rsaquo; Dashboard Geolocalização</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1544); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório de Base Histórica</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1546); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório de Emails da Blacklist</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1551); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Relatório Cadastros Alterados</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1562); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório de Consolidado Faturamento</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1298); ?>" target="_blank">&rsaquo; Importações</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1306); ?>" target="_blank">&rsaquo; Importações Blacklist Steps</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1336); ?>" target="_blank">&rsaquo; Importações Produtos Steps</a> <br/>

                                            <a href="action.do?mod=<?php echo fnEncode(1479); ?>" target="_blank">&rsaquo; Tela de listagem dos Artigos do Tutorial </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1456); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Configuração de Frequência (Cliente) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1469); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório de Indicação de Produtos (Grupo) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1472); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Logs de Usuários </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1463); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Percentual de Vendas por Período (Persona) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1458); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Matriz de Configuração de Preços (Email) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1471); ?>" target="_blank">&rsaquo; Categorias do Tutorial </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1466); ?>" target="_blank">&rsaquo; Relatório de Logs de Usuários (ADM) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1459); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Relatório de Movimentação Clientes </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1460); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Troca de Cartões </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1453); ?>&id=<?php echo fnEncode(39); ?>&idP=<?php echo fnEncode(8); ?>" target="_blank">&rsaquo; Categoria de Produtos Top </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1448); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Indicação de Produtos (+ vendidos) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1457); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Envio Simples de Email </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1403); ?>" target="_blank">&rsaquo; Agenda </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1404); ?>" target="_blank">&rsaquo; Tipos de Eventos (Agenda) </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1397); ?>" target="_blank">&rsaquo; Relatório de Acessos</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1371); ?>" target="_blank">&rsaquo; Empresas Marka</a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1429); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Relatórios Social </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1408); ?>" target="_blank">&rsaquo; Template de Email </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1391); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Resgate Múltiplo</a> <br/>
                                            <a href="action.do?mod=us£uTXdaEin4¢&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Performace </a> <br/>
                                            <a href="action.do?mod=CZulp£tmbzM8¢&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Resgate </a> <br/>
                                            <a href="action.do?mod=OwYfV3z8SeQ%C2%A2&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Engajamento </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1384); ?>&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Faturamento </a> <br/>
                                            <a href="action.do?mod=<?php echo fnEncode(1335); ?>" target="_blank">&rsaquo; Barra de Pesquisa </a> <br/>


                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="push30"></div>

                        </div>

                    </div>

                </div>	


            </form>

            <div class="push50"></div>

        </div>								

      </div>
    </div>
    <!-- fim Portlet -->
  </div>

</div>					

<div class="push20"></div> 

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

  function retornaForm(index) {
    $("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
    $("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
    $('#formulario').validator('validate');
    $("#formulario #hHabilitado").val('S');
  }


  $(document).ready(function () {


  });



</script>	