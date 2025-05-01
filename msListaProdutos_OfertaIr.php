<?php
//echo fnDebug('true');

$hashLocal = mt_rand();

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
  //busca dados da empresa
  $cod_orcamento = "";
  $cod_empresa = fnDecode($_GET['id']);
  $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

  //fnEscreve($sql);
  $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
  $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

  if (isset($qrBuscaEmpresa)) {
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
  }
} else {
  $cod_empresa = 0;
  $nom_empresa = "";
  $cod_orcamento = 0;
}


//busca dados da configuração	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
  //busca dados da empresa
  $cod_empresa = fnDecode($_GET['id']);
  $sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '" . $cod_empresa . "' ";

  //fnEscreve($sql);
  //fnTesteSql(connTemp($cod_empresa,""),trim($sql));

  $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error());
  $qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);

  //print_r($arrayQuery);	

  if (isset($qrBuscaConfiguracao)) {
    $cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
    $log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
    if ($log_ativo_tkt == "S") {
      $mostraLOG_ATIVO_TKT = "checked";
    } else {
      $mostraLOG_ATIVO_TKT = "";
    }
    $log_emisdia = $qrBuscaConfiguracao['LOG_EMISDIA'];
    if ($log_emisdia == "S") {
      $mostraLOG_EMISDIA = "checked";
    } else {
      $mostraLOG_EMISDIA = "";
    }
    $cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
    $qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
    $qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
    $qtd_ofertws_tkt = $qrBuscaConfiguracao['QTD_OFERTWS_TKT'];
    $qtd_ofertas_lst = $qrBuscaConfiguracao['QTD_OFERTAS_LST'];
    $qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
    $qtd_produtos_tkt = $qrBuscaConfiguracao['QTD_PRODUTOS_TKT'];
    $qtd_produtos_cat = $qrBuscaConfiguracao['QTD_PRODUTOS_CAT'];
    $num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
    $min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
    $max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
    $cod_blklist = $qrBuscaConfiguracao['COD_BLKLIST'];
    $des_pratprc = $qrBuscaConfiguracao['DES_PRATPRC'];
    $des_validade = $qrBuscaConfiguracao['DES_VALIDADE'];
    $log_listaws = $qrBuscaConfiguracao['LOG_LISTAWS'];
    if ($log_listaws == "S") {
      $mostraLOG_LISTAWS = "checked";
    } else {
      $mostraLOG_LISTAWS = "";
    }
  } else {
    $cod_configu = 0;
    $log_ativo_tkt = "";
    $log_emisdia = "";
    $cod_template_tkt = 0;
    $qtd_compras_tkt = "";
    $qtd_ofertas_tkt = "";
    $qtd_ofertws_tkt = "";
    $qtd_ofertas_lst = "";
    $qtd_categor_tkt = "";
    $qtd_produtos_tkt = "1";
    $qtd_produtos_cat = "1";
    $num_historico_tkt = "";
    $min_historico_tkt = "0";
    $max_historico_tkt = "30";
    $cod_blklist = "0";
    $des_validade = "0";
    $mostraLOG_EMISDIA = '';
    $mostraLOG_ATIVO_TKT = '';
    $mostraLOG_LISTAWS = '';
  }
} else {
  $cod_configu = 0;
  $log_ativo_tkt = "";
  $log_emisdia = "";
  $cod_template_tkt = 0;
  $qtd_compras_tkt = "";
  $qtd_ofertas_tkt = "";
  $qtd_ofertws_tkt = "";
  $qtd_categor_tkt = "";
  $qtd_produtos_tkt = "";
  $num_historico_tkt = "";
  $min_historico_tkt = "0";
  $max_historico_tkt = "30";
  $des_validade = "0";
  $cod_blklist = "0";
  $mostraLOG_EMISDIA = '';
  $mostraLOG_ATIVO_TKT = '';
  $mostraLOG_LISTAWS = '';
}
//print_r(explode($_REQUEST['NUM_HISTORICO_TKT']));	
//fnMostraForm();
//fnEscreve($min_historico_tkt);	
//fnEscreve($cod_empresa);	
//fnEscreve($nom_empresa);	
?>

<div class="push30"></div> 

<div class="row">

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <?php if ($popUp != "true") { ?>							
      <div class="portlet portlet-bordered">
      <?php } else { ?>
        <div class="portlet" style="padding: 0 20px 20px 20px;" >
        <?php } ?>

        <?php if ($popUp != "true") { ?>
          <div class="portlet-title">
            <div class="caption">
              <i class="glyphicon glyphicon-calendar"></i>
              <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
            </div>
            <?php include "atalhosPortlet.php"; ?>
          </div>
        <?php } ?>	

        <div class="portlet-body">

          <?php if ($msgRetorno <> '') { ?>	
            <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $msgRetorno; ?>
            </div>
          <?php } ?>

          <div class="push30"></div> 

          <div class="login-form">

            <div id="relatorioConteudo">

              <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


                <div class="row" >


                  <div class="col-md-2"></div>
				  
                  <div class="row">

                    <div class="col-md-12 ">
					
					  <center>
					  <img src="images/banner_marka_store2.jpg">
					  </center>
					  
					  <div class='cProdutos'>
						  <div class="push20"></div>
						  
						  <h4 style="margin-left: 30px;">Selecione os produtos desejados</h4>
						  
						  <div class="push20"></div> 										

						  <?php
						  
						  $bloco_qtd = "";
						  $sql = "SELECT
									sistema_versao.COD_VERSAO COD_CANALCOM,
									sistema_versao.NOM_VERSAO DES_CANALCOM,
									produto_marka.COD_PRODUTO,
									produto_marka.VAL_UNITARIO,
									produto_marka.VAL_TOTAL,
									produto_marka.QTD_CREDITO NOM_FAIXA
								FROM produto_marka
								INNER JOIN sistema_versao ON (sistema_versao.COD_SISTEMA = produto_marka.COD_SISTEMA AND sistema_versao.COD_VERSAO = produto_marka.COD_VERSAO)
								WHERE sistema_versao.COD_SISTEMA=17
								ORDER BY sistema_versao.COD_SISTEMA,sistema_versao.NUM_ORDENAC";
	//fnEscreve($sql);
						  $arrayQueryTipo = mysqli_query($connAdm->connAdm(), trim($sql));
						  while ($qrTipo = mysqli_fetch_assoc($arrayQueryTipo)) {
							  $tmpCampo = $qrTipo["COD_CANALCOM"];
							?>


							<div class="col-md-4">

							  <div class="col-md-12">

								<div class="no-more-tables">

								  <table id="tbl_<?= $tmpCampo ?>" class="table table-bordered table-striped table-hover">
									<thead>
									  <tr>
										<th class="text-center f21" colspan="4"><?= $qrTipo['DES_CANALCOM'] ?></th>        
									  </tr>
									  <tr>
										<th class="text-center">QUANTIDADE</th>
										<th class="text-center">TOTAL</th>
										<th class="text-center" width="40" style='display:none;'>&nbsp;</th>
									  </tr>
									</thead>

									<tbody align="center">
										<tr class="tr_<?= $tmpCampo ?>" >
										  <td><b><?= $qrTipo['NOM_FAIXA'] ?></b></td>

																				

										  <td class="text-right">R$ <span id="VAL_TOTAL_<?=$qrTipo["COD_PRECO"] ?>"><?= fnValor($qrTipo['VAL_TOTAL'], 2) ?></span></td>
										  <td style='display:none;'>&nbsp;
											<label class="switch">
											<input type="checkbox" name="CHECK_PRODUTO" onChange="calculaTotalCompra()" cod_produto="<?=$qrTipo['COD_PRODUTO']?>" valor="<?=$qrTipo['VAL_TOTAL']?>" id="CHECK_PRODUTO_<?=$qrTipo["COD_CANALCOM"]?>" class="switch" value="S" >
											<span></span>
											</label>
										  </td>

										</tr>
										<tr>
											<td colspan=3>
												<button type="button" id="bt_PRODUTO_<?=$qrTipo["COD_PRODUTO"]?>" cod_produto="<?=$qrTipo['COD_PRODUTO']?>" name="BT_PRODUTO" onClick="btTotalCompra('<?=$qrTipo['COD_CANALCOM']?>')" class="btn btn-success btn-block getBtn"><i class="fal fa-1x fa-cart-plus" aria-hidden="true"></i>&nbsp; Comprar </button>
											</td>
										</tr>

									</tbody>																    

									<tfoot>	

									</tfoot>

								  </table>

								</div>

							  </div>	

							  <div class="push20"></div>

							</div>

							<input type='hidden' name='QTD_<?=$qrTipo["COD_PRODUTO"]?>_TOTAL' id='QTD_<?=$qrTipo["COD_PRODUTO"]?>_TOTAL' value='0'>
							<?php
						  }
						  
						  //echo $bloco_qtd;
                      ?>
					  </div>
					  
					  <div class='cPagamento'>

						  <div class="push30"></div>                      

						  <div class="col-md-4">
							<div class="form-group">
							  <label for="inputName" class="control-label">Forma de Pagamento</label>
							  <select class="form-control input-lg text-center" name="TIP_PAGTO" id="TIP_PAGTO" >
								<option value="0"></option>
								<option value="1">Cartão</option>
								<option value="2">Boleto</option>
							  </select>
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>

						  <div class="col-md-8 camposCartao">
							<div class="form-group">
							  <label for="inputName" class="control-label">Nome Impresso no Cartão</label>
							  <input type="text" class="form-control input-lg text-center" name="NOM_CARTAO" id="NOM_CARTAO" value="" placeholder="Nome Impresso no Cartão" required="" maxlength="50">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>


						  <div class="col-md-5 camposCartao">
							<div class="form-group">
							  <label for="inputName" class="control-label">Número do Cartão</label>
							  <input type="text" class="form-control input-lg text-center int" name="NUM_CARTAO" id="NUM_CARTAO" value="" placeholder="Número do Cartão" required="" maxlength="16">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>	

						  <div class="col-md-1 camposCartao">
							<div class="form-group">
							  <img id="NUM_CARTAO_IMG" src=""/>
							  <input type="hidden" id="CARTAO_BANDEIRA" name="CARTAO_BANDEIRA" value=""/>
							</div>		
						  </div>	


						  <div class="col-md-2 camposCartao">
							<div class="form-group">
							  <label for="inputName" class="control-label">Mês de Validade</label>
							  <select class="form-control input-lg text-center" name="MES_VALIDO" id="MES_VALIDO" >
								<?php
								for ($i = 1; $i <= 12; $i++) {
								  $tmp = str_pad($i, 2, "0", STR_PAD_LEFT);
								  echo "<option value='$tmp'>$tmp</option>";
								}
								?>
							  </select>
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>

						  <div class="col-md-2 camposCartao">
							<div class="form-group">
							  <label for="inputName" class="control-label">Ano de Validade</label>
							  <select class="form-control input-lg text-center" name="ANO_VALIDO" id="ANO_VALIDO" >
								<?php
								for ($i = date("Y"); $i < date("Y") + 20; $i++) {
								  echo "<option value='$i'>$i</option>";
								}
								?>
							  </select>
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>

						  <div class="col-md-2 camposCartao">
							<div class="form-group">
							  <label for="inputName" class="control-label">CVV</label>
							  <input type="text" class="form-control input-lg text-center int" name="NUM_CVV" id="NUM_CVV" value="" placeholder="CVV" required="" maxlength="4">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         


						  <div class="col-md-4 camposPessoa">
							<div class="form-group">
							  <label for="inputName" class="control-label">CPF/CNPJ do Cartão</label>
							  <input type="text" class="form-control input-lg text-center cpfcnpj" name="NUM_DOCUMENTO" id="NUM_DOCUMENTO" value="" placeholder="CPF/CNPJ" required="" maxlength="20">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         


						  <div class="col-md-8 camposPessoa">
							<div class="form-group">
							  <label for="inputName" class="control-label">Nome Completo</label>
							  <input type="text" class="form-control input-lg text-center" name="NOM_COMPRADOR" id="NOM_COMPRADOR" value="" placeholder="Nome Completo" required="" maxlength="100">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-12 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Email</label>
							  <input type="text" class="form-control input-lg text-center" name="DES_EMAIL" id="DES_EMAIL" value="" placeholder="E-mail" required="" maxlength="100">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-3 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Nascimento</label>
							  <input type="text" class="form-control input-lg text-center int" name="DAT_NASCIMENTO" id="DAT_NASCIMENTO" value="" placeholder="Nascimento" required="" maxlength="10">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-2 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">DDD</label>
							  <input type="text" class="form-control input-lg text-center int" name="NUM_TELEFDDD" id="NUM_TELEFDDD" value="" placeholder="DDD" required="" maxlength="2">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-4 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Telefone</label>
							  <input type="text" class="form-control input-lg text-center int" name="NUM_TELEFONE" id="NUM_TELEFONE" value="" placeholder="Telefone" required="" maxlength="10">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-3 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">CEP</label>
							  <input type="text" class="form-control input-lg text-center int" name="NUM_CEP" id="NUM_CEP" value="" placeholder="CEP" required="" maxlength="9">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-8 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Endereço</label>
							  <input type="text" class="form-control input-lg text-center" name="DES_ENDERECO" id="DES_ENDERECO" value="" placeholder="Endereço" required="" maxlength="100">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-4 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Número</label>
							  <input type="text" class="form-control input-lg text-center int" name="NUM_ENDERECO" id="NUM_ENDERECO" value="" placeholder="Número" required="" maxlength="10">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         


						  <div class="col-md-6 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Complemento</label>
							  <input type="text" class="form-control input-lg text-center" name="DES_COMPLEMENTO" id="DES_COMPLEMENTO" value="" placeholder="Complemento" required="" maxlength="50">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-6 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Bairro</label>
							  <input type="text" class="form-control input-lg text-center" name="NOM_BAIRRO" id="NOM_BAIRRO" value="" placeholder="Bairro" required="" maxlength="50">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-8 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">Cidade</label>
							  <input type="text" class="form-control input-lg text-center" name="NOM_CIDADE" id="NOM_CIDADE" value="" placeholder="Complemento" required="" maxlength="50">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         

						  <div class="col-md-4 camposPessoaCompl">
							<div class="form-group">
							  <label for="inputName" class="control-label">UF</label>
							  <input type="text" class="form-control input-lg text-center" name="ABR_UF" id="ABR_UF" value="" placeholder="Bairro" required="" maxlength="9">
							  <div class="help-block with-errors"></div>
							</div>		
						  </div>                         


						  <div class="push30"></div>                      

						  <div class="col-md-6">

							<div class="form-group">
							  <label for="inputName" class="control-label">Total da Compra</label>
							  <input type="text" class="form-control input-lg leituraOff text-center money" name="VAL_TOTAL" id="VAL_TOTAL" value="" placeholder="0.00" required="" maxlength="10">
							  <div class="help-block with-errors"></div>
							</div>		

						  </div>

						  <div class="col-md-6">

							<div class="push20"></div>

							<button type="button" name="CARRINHO" id="CARRINHO" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-1x fa-cart-plus" aria-hidden="true"></i>&nbsp; Comprar </button>

							<button type="button" name="CREDITAR" id="CREDITAR" class="btn btn-danger btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-1x fa-cart-plus" aria-hidden="true"></i>&nbsp; Creditar </button>

						  </div>

						</div>
                    </div>
                  </div>


                  <div class="col-md-2"></div>

                </div>

				<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO">
                <input type="hidden" class="form-control input-sm" name="COD_ORCAMENTO" id="COD_ORCAMENTO" value="<?php echo $cod_orcamento ?>">
                <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                <input type="hidden" name="opcao" id="opcao" value="">
                <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

                <div class="push5"></div> 
              </form>

            </div>

            <div class="push50"></div>						

          </div>								
        </div>
      </div>
      <!-- fim Portlet -->
    </div>
  </div>

  <style>
    #NUM_CARTAO_IMG{display:none;margin-top:40px;}
    .camposCartao, .camposPessoa, .camposPessoaCompl{display:none;}
    .table {font-size:12px;}
    .table tr:nth-child(even).on, .table tr:nth-child(odd).on{background-color: #80ccff;}
    .table tr:nth-child(even).on td .cart , .table tr:nth-child(odd).on td .cart {color:#444444;}

    .cart{color:#DEDEDE;}
    .cart:hover{color:#444444;}
	
	.cPagamento{display:none;}
  </style>

  <script src="js/plugins/ion.rangeSlider.js"></script>
  <link rel="stylesheet" href="css/ion.rangeSlider.css" />
  <link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

  <div class="push20"></div> 

  <?php
  $cardIsHom = "S";
  if ($cardIsHom == "S") {
    ?>
    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
  <?php } else { ?>
    <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
  <?php } ?>

  <script type="text/javascript">
    $(document).ready(function () {

      PagSeguroDirectPayment.setSessionId('<?php echo sessions_PagSeguro(); ?>');

      $("#TIP_PAGTO").change(function () {
        $(".camposCartao").hide();
        $(".camposPessoa").hide();
        $(".camposPessoaCompl").hide();
        if ($(this).val() === "1") {
          $(".camposCartao").show();
        }

        if ($(this).val() !== "0") {
          $(".camposPessoa").show();
        }

      });
      $("#TIP_PAGTO").trigger("change");
      $("#NUM_CARTAO").change(function () {
        PagSeguroDirectPayment.getBrand({
          cardBin: $(this).val(),
          success: function (response) {
            var urlImg = "https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/" + response.brand.name + ".png";
            $("#CARTAO_BANDEIRA").val(response.brand.name);
            $("#NUM_CARTAO_IMG").attr("src", urlImg)
            $("#NUM_CARTAO_IMG").show();
          }
          , error: function (response) {
            $("#CARTAO_BANDEIRA").val("");
            $("#NUM_CARTAO_IMG").hide();
          }
        });
      });

      $("#NUM_DOCUMENTO").change(function () {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        $.ajax({
          method: 'POST',
          url: 'msBlocoCompra_OfertaIr.do?id=<?= fnEncode($cod_empresa) ?>&acao=busca_pagador',
          data: $("#formulario").serialize(),
          dataType: 'json',
          /*
           beforeSend: function () {
           $("#relatorioConteudo").html("<div class='loading' style='width:100%'></div>");
           },
           */
          success: function (data) {
            $.each(data, function (data, item) {
              $("#NOM_COMPRADOR").val(item.NOM_COMPRADOR);
              $("#DAT_NASCIMENTO").val(item.DAT_NASCIMENTO);
              $("#NUM_TELEFDDD").val(item.NUM_TELEFDDD);
              $("#NUM_TELEFONE").val(item.NUM_TELEFONE);
              $("#DES_EMAIL").val(item.DES_EMAIL);
              $("#NUM_CEP").val(item.NUM_CEP);
              $("#DES_ENDERECO").val(item.DES_ENDERECO);
              $("#NUM_ENDERECO").val(item.NUM_ENDERECO);
              $("#DES_COMPLEMENTO").val(item.DES_COMPLEMENTO);
              $("#NOM_BAIRRO").val(item.NOM_BAIRRO);
              $("#NOM_CIDADE").val(item.NOM_CIDADE);
              $("#ABR_UF").val(item.ABR_UF);
            });
          },
          error: function (data) {
            //$("#relatorioConteudo").html("Ops... Dados <b>não encontrados</b>.");
          }
        });
        $(".camposPessoaCompl").show();
        //MASCADA CPF/CNPJ
        if (val.length === 14) {
          valMsk = val.substr(0, 2)
                  + "." + val.substr(2, 3)
                  + "." + val.substr(5, 3)
                  + "/" + val.substr(8, 4)
                  + "-" + val.substr(12, 2);
        } else if (val.length === 11) {
          valMsk = val.substr(0, 3)
                  + "." + val.substr(3, 3)
                  + "." + val.substr(6, 3)
                  + "-" + val.substr(9, 2);
        } else {
          valMsk = "";
        }
        $(this).val(valMsk);
      });
      $("#DAT_NASCIMENTO").change(function () {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        if (val.length === 8) {
          valMsk = val.substr(0, 2)
                  + "/" + val.substr(2, 2)
                  + "/" + val.substr(4, 4);
        } else {
          valMsk = "";
        }
        $(this).val(valMsk);
      });
      $("#NUM_TELEFONE").change(function () {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        if (val.length === 8) {
          valMsk = val.substr(0, 4) + "-" + val.substr(4, 4);
        } else if (val.length === 9) {
          valMsk = val.substr(0, 5) + "-" + val.substr(5, 4);
        } else {
          valMsk = $(this).val();
        }
        $(this).val(valMsk);
      });
      $("#NUM_CEP").change(function () {
        var val = $(this).val().replace(/\D/g, '');
        var valMsk = "";
        var cep = val;
        //Verifica se campo cep possui valor informado.
        if (cep != "") {

          //Expressão regular para validar o CEP.
          var validacep = /^[0-9]{8}$/;
          //Valida o formato do CEP.
          if (validacep.test(cep)) {
            //Consulta o webservice viacep.com.br/
            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

              if (!("erro" in dados)) {
                $("#DES_ENDERECO").val(dados.logradouro);
                $("#NOM_BAIRRO").val(dados.bairro);
                $("#NOM_CIDADE").val(dados.localidade);
                $("#ABR_UF").val(dados.uf);
                $("#NUM_ENDERECO").focus();
              } //end if.
              else {
                //CEP pesquisado não foi encontrado.
                alert("CEP não encontrado. Preencha os dados manualmente!");
              }
            });
          } //end if.
          else {
            //cep é inválido.
            alert("Formato de CEP inválido.");
          }
        }

        //marcara cep
        if (val.length === 8) {
          valMsk = val.substr(0, 5) + "-" + val.substr(5, 3);
        } else {
          valMsk = "";
        }
        $(this).val(valMsk);
      });
//chosen

      function cadastraPedido(cardToken, cardHash, tpOperacao) {
        var urlPost = "msBlocoCompra_OfertaIr.do?id=<?= fnEncode($cod_empresa) ?>"
                + "&acao=cad_pedido"
                + "&cardToken=" + cardToken
                + "&cardHash=" + cardHash
                + "&cardIsHom=<?= $cardIsHom ?>"
                + "&tpOperacao=" + tpOperacao;

        $.ajax({
          method: "POST"
          , url: urlPost
          , data: $("#formulario").serialize()
          , beforeSend: function () {
            $("#relatorioConteudo").html("<div id='blocker'> <div style='text-align: center;'><img src='images/loading2.gif'><br/> Aguarde. Processando... ;-)</div> </div>");
          }
          , success: function (data) {
            $("#relatorioConteudo").html(data);
          }
          , error: function (data) {
            $("#relatorioConteudo").html(data);
          }
        });

      }

      $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
      $('#formulario').validator();


      $("#CARRINHO, #CREDITAR").click(function () {
        var tpOperacao = ($(this).attr("id") === "CARRINHO" ? "COMPRA" : "CREDITO");

        $.alert({title: tpOperacao
          , content: (tpOperacao === "COMPRA" ? "Confirma a compra no valor total de R$ " + $("#VAL_TOTAL").val() : "Confirma o crédito sem cobrança dos produtos?")
          , buttons: {Confirmar: function () {

              if ($("TIP_PAGTO").val() == "1") {

                var cardToken = "";
                var cardHash = PagSeguroDirectPayment.getSenderHash();

                PagSeguroDirectPayment.createCardToken({
                  cardNumber: $("#NUM_CARTAO").val()
                  , cvv: $("#NUM_CVV").val()
                  , expirationMonth: $("#MES_VALIDO").val()
                  , expirationYear: $("#ANO_VALIDO").val()
                  , success: function (response) {
                    cardToken = response.card.token;
                    cadastraPedido(cardToken, cardHash, tpOperacao);
                  }
                  , error: function (response) {
                    //console.log(response);
                    cardToken = "";
                  }
                });
              } else {
                cadastraPedido("", "", tpOperacao);
              }
            }
            , Cancelar: function () {

            }
          }

        });
      });

      //$("#TIP_PAGTO").val("1").trigger("change");
      $("#TIP_PAGTO").val("0").trigger("change");
      $("#NOM_CARTAO").val("Teste");
      $("#NUM_CARTAO").val("4111111111111111").trigger("change");
      $("#MES_VALIDO").val("12");
      $("#ANO_VALIDO").val("2030");
      $("#NUM_CVV").val("123");
      $("#NUM_DOCUMENTO").val("326.934.068-03").trigger("change");

    });


	function btTotalCompra(cod_produto) {
		$(".cProdutos").hide(300);
		$(".cPagamento").show(300);

		$('input[name=CHECK_PRODUTO]').attr("checked",false);
		$('#CHECK_PRODUTO_'+cod_produto).prop("checked",true);
		calculaTotalCompra();
	}

    function calculaTotalCompra() {
		var total = 0;
		$("#COD_PRODUTO").val("0");
		$('input[name=CHECK_PRODUTO]').each(function () {
			var cod_produto = $("#"+this.id).attr("cod_produto");
			if (this.checked){
				//$("#bt_PRODUTO_"+cod_produto).removeClass("btn-success");
				//$("#bt_PRODUTO_"+cod_produto).addClass("btn-danger");
				var valor = $("#"+this.id).attr("valor");
				$("#COD_PRODUTO").val($("#COD_PRODUTO").val() + "," + cod_produto);
				total = total+parseFloat("0" + valor);
				
				$("#QTD_"+cod_produto+"_TOTAL").val(valor);
			}else{
				//$("#bt_PRODUTO_"+cod_produto).removeClass("btn-danger");
				//$("#bt_PRODUTO_"+cod_produto).addClass("btn-success");
				$("#QTD_"+cod_produto+"_TOTAL").val("0");
			}
		});
      $("#VAL_TOTAL").val(parseFloat(total).toFixed(2));
    }

    $(function () {
      $("#NUM_HISTORICO_TKT").ionRangeSlider({
        hide_min_max: true,
        keyboard: true,
        min: 0,
        max: 120,
        from: <?php echo $min_historico_tkt; ?>,
        to: <?php echo $max_historico_tkt; ?>,
        type: 'int',
        step: 5,
        //prettify_enabled: true,
        //prettify_separator: "."
        //prefix: "Idade ",
        postfix: " dias",
        max_postfix: ""
                //grid: true
      });
      /*
       $("#range").ionRangeSlider();
       */

    });


  </script>	