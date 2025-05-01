<?php
/*
require '../../_system/_functionsMain.php';
include '../email/envio_sac.php';

$conadmmysql = $connAdm->connAdm();
//capturando as empresas com a comunicação
$sqlEmpresa = "SELECT em.LOG_ATIVO,em.COD_EMPRESA,db.DES_DATABASE,db.NOM_DATABASE,db.IP from empresas em
                    INNER JOIN tab_database db ON db.COD_EMPRESA=em.COD_EMPRESA and  em.cod_empresa NOT IN (136,514)
                    WHERE  em.LOG_ATIVO='S'
					  GROUP BY db.NOM_DATABASE";
$rwempresas = mysqli_query($conadmmysql, $sqlEmpresa);
while ($rsempresas = mysqli_fetch_assoc($rwempresas)) {
	$contemporaria = connTemp($rsempresas['COD_EMPRESA'], '');
	//verificar se existe dados para excluir
	$dados1 = "SELECT * FROM vendabasetmpconsolidado  LIMIT 1;";
	$dadosrw1 = mysqli_query($contemporaria, $dados1);
	if ($dadosrw1->num_rows >= 1) {
		$sqlcategoria = "TRUNCATE TABLE VENDABASETMPCONSOLIDADO;";
	}

	$dados2 = "SELECT * FROM VENDABASETMP  LIMIT 1";
	$dadosrw2 = mysqli_query($contemporaria, $dados2);
	if ($dadosrw2->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDABASETMP;";
		echo $sqlcategoria;
	}

	$dados3 = "SELECT * FROM clientepersonaexport  LIMIT 1";
	$dadosrw3 = mysqli_query($contemporaria, $dados3);
	if ($dadosrw3->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE clientepersonaexport;";
		echo $sqlcategoria;
	}

	$dados4 = "SELECT * FROM retornoclientes  LIMIT 1";
	$dadosrw4 = mysqli_query($contemporaria, $dados4);
	if ($dadosrw4->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE retornoclientes;";
		echo $sqlcategoria;
	}

	$dados5 = "SELECT * FROM CLIENTEPERSONASTMP  LIMIT 1";
	$dadosrw5 = mysqli_query($contemporaria, $dados5);
	if ($dadosrw5->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTEPERSONASTMP;";
		echo $sqlcategoria;
	}

	$dados6 = "SELECT * FROM VENDATMPTOPPRODUTO  LIMIT 1";
	$dadosrw6 = mysqli_query($contemporaria, $dados6);
	if ($dadosrw6->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMPTOPPRODUTO;";
		echo $sqlcategoria;
	}

	$dados7 = "SELECT * FROM ITEMVENDAGRPTMPTOPPRODUTO  LIMIT 1";
	$dadosrw7 = mysqli_query($contemporaria, $dados7);
	if ($dadosrw7->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE ITEMVENDAGRPTMPTOPPRODUTO;";
		echo $sqlcategoria;
	}

	$dados8 = "SELECT * FROM ITEMVENDAGRPTMPTOPPRODUTOCLIENTE  LIMIT 1";
	$dadosrw8 = mysqli_query($contemporaria, $dados8);
	if ($dadosrw8->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE ITEMVENDAGRPTMPTOPPRODUTOCLIENTE;";
		echo $sqlcategoria;
	}
	$dados9 = "SELECT * FROM CLIENTETMPTOPPRODUTO  LIMIT 1";
	$dadosrw9 = mysqli_query($contemporaria, $dados9);
	if ($dadosrw9->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTETMPTOPPRODUTO;";
		echo $sqlcategoria;
	}
	$dados10 = "SELECT * FROM CLIENTEQTDTMPTOPPRODUTO  LIMIT 1";
	$dadosrw10 = mysqli_query($contemporaria, $dados10);
	if ($dadosrw10->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTEQTDTMPTOPPRODUTO;";
		echo $sqlcategoria;
	}
	$dados11 = "SELECT * FROM RETORNOPRODUTOSTOPPRODUTO  LIMIT 1";
	$dadosrw11 = mysqli_query($contemporaria, $dados11);
	if ($dadosrw11->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE RETORNOPRODUTOSTOPPRODUTO;";
		echo $sqlcategoria;
	}
	$dados12 = "SELECT * FROM TMP_CODUNIVENDCURSOR  LIMIT 1";
	$dadosrw12 = mysqli_query($contemporaria, $dados12);
	if ($dadosrw12->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_CODUNIVENDCURSOR;";
		echo $sqlcategoria;
	}
	$dados13 = "SELECT * FROM TICKETTMP  LIMIT 1";
	$dadosrw13 = mysqli_query($contemporaria, $dados13);
	if ($dadosrw13->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMP;";
		echo $sqlcategoria;
	}
	$dados14 = "SELECT * FROM VENDATMPEXPORT  LIMIT 1";
	$dadosrw14 = mysqli_query($contemporaria, $dados14);
	if ($dadosrw14->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMPEXPORT;";
		echo $sqlcategoria;
	}
	$dados15 = "SELECT * FROM CREDITOSDEBITOSRESGATE  LIMIT 1";
	$dadosrw15 = mysqli_query($contemporaria, $dados15);
	if ($dadosrw15->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITOSDEBITOSRESGATE;";
		echo $sqlcategoria;
	}
	$dados16 = "SELECT * FROM VENDACOMPRAGRP  LIMIT 1";
	$dadosrw16 = mysqli_query($contemporaria, $dados16);
	if ($dadosrw16->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDACOMPRAGRP;";
		echo $sqlcategoria;
	}
	$dados17 = "SELECT * FROM RESGATETMP  LIMIT 1";
	$dadosrw17 = mysqli_query($contemporaria, $dados17);
	if ($dadosrw17->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE RESGATETMP;";
		echo $sqlcategoria;
	}
	$dados18 = "SELECT * FROM RESGATEQTDTMP  LIMIT 1";
	$dadosrw18 = mysqli_query($contemporaria, $dados18);
	if ($dadosrw18->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE RESGATEQTDTMP;";
		echo $sqlcategoria;
	}
	$dados19 = "SELECT * FROM VVRTMP  LIMIT 1";
	$dadosrw19 = mysqli_query($contemporaria, $dados19);
	if ($dadosrw19->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VVRTMP;";
		echo $sqlcategoria;
	}
	$dados20 = "SELECT * FROM CREDITODISPTMP  LIMIT 1";
	$dadosrw20 = mysqli_query($contemporaria, $dados20);
	if ($dadosrw20->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITODISPTMP;";
		echo $sqlcategoria;
	}
	$dados21 = "SELECT * FROM CREDITODISPSALDOTMP  LIMIT 1";
	$dadosrw21 = mysqli_query($contemporaria, $dados21);
	if ($dadosrw21->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITODISPSALDOTMP;";
		echo $sqlcategoria;
	}
	$dados22 = "SELECT * FROM CREDITOSALDOTOTALTMP  LIMIT 1";
	$dadosrw22 = mysqli_query($contemporaria, $dados22);
	if ($dadosrw22->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITOSALDOTOTALTMP;";
		echo $sqlcategoria;
	}
	$dados23 = "SELECT * FROM CREDITODISPSALDO30TMP  LIMIT 1";
	$dadosrw23 = mysqli_query($contemporaria, $dados23);
	if ($dadosrw23->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITODISPSALDO30TMP;";
		echo $sqlcategoria;
	}
	$dados24 = "SELECT * FROM CREDITOSDEBITOSTMP  LIMIT 1";
	$dadosrw24 = mysqli_query($contemporaria, $dados24);
	if ($dadosrw24->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITOSDEBITOSTMP;";
		echo $sqlcategoria;
	}
	$dados25 = "SELECT * FROM CREDITOSDEBITOSVVR  LIMIT 1";
	$dadosrw25 = mysqli_query($contemporaria, $dados25);
	if ($dadosrw25->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITOSDEBITOSVVR;";
		echo $sqlcategoria;
	}
	$dados26 = "SELECT * FROM TMP_CODUNIVENDCURSOR  LIMIT 1";
	$dadosrw26 = mysqli_query($contemporaria, $dados26);
	if ($dadosrw26->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_CODUNIVENDCURSOR;";
		echo $sqlcategoria;
	}
	$dados27 = "SELECT * FROM CLIENTEEXPORTA  LIMIT 1";
	$dadosrw27 = mysqli_query($contemporaria, $dados27);
	if ($dadosrw27->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTEEXPORTA;";
		echo $sqlcategoria;
	}
	$dados28 = "SELECT * FROM UNIDADEVENDA_TMP  LIMIT 1";
	$dadosrw28 = mysqli_query($contemporaria, $dados28);
	if ($dadosrw28->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE UNIDADEVENDA_TMP;";
		echo $sqlcategoria;
	}
	$dados29 = "SELECT * FROM ITEMVENDATMPEXPORT  LIMIT 1";
	$dadosrw29 = mysqli_query($contemporaria, $dados29);
	if ($dadosrw29->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE ITEMVENDATMPEXPORT;";
		echo $sqlcategoria;
	}
	$dados30 = "SELECT * FROM VENDAPRODTMPEXPORT  LIMIT 1";
	$dadosrw30 = mysqli_query($contemporaria, $dados30);
	if ($dadosrw30->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAPRODTMPEXPORT;";
		echo $sqlcategoria;
	}
	$dados31 = "SELECT * FROM VENDAPRODFINALEXPORT  LIMIT 1";
	$dadosrw31 = mysqli_query($contemporaria, $dados31);
	if ($dadosrw31->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAPRODFINALEXPORT;";
		echo $sqlcategoria;
	}
	$dados32 = "SELECT * FROM VENDAPRODCOMPRA  LIMIT 1";
	$dadosrw32 = mysqli_query($contemporaria, $dados32);
	if ($dadosrw32->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAPRODCOMPRA;";
		echo $sqlcategoria;
	}
	$dados33 = "SELECT * FROM TICKETTMPPRODUTOINI  LIMIT 1";
	$dadosrw33 = mysqli_query($contemporaria, $dados33);
	if ($dadosrw33->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMPPRODUTOINI;";
		echo $sqlcategoria;
	}
	$dados34 = "SELECT * FROM TICKETTMPPRODUTO  LIMIT 1";
	$dadosrw34 = mysqli_query($contemporaria, $dados34);
	if ($dadosrw34->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMPPRODUTO;";
		echo $sqlcategoria;
	}
	$dados35 = "SELECT * FROM FILTROPERSONATMP  LIMIT 1";
	$dadosrw35 = mysqli_query($contemporaria, $dados35);
	if ($dadosrw35->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE FILTROPERSONATMP;";
		echo $sqlcategoria;
	}
	$dados36 = "SELECT * FROM VENDATMP  LIMIT 1";
	$dadosrw36 = mysqli_query($contemporaria, $dados36);
	if ($dadosrw36->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMP;";
		echo $sqlcategoria;
	}
	$dados37 = "SELECT * FROM VENDATMPACUM  LIMIT 1";
	$dadosrw37 = mysqli_query($contemporaria, $dados37);
	if ($dadosrw37->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMPACUM;";
		echo $sqlcategoria;
	}
	$dados38 = "SELECT * FROM PRODUTOTMP  LIMIT 1";
	$dadosrw38 = mysqli_query($contemporaria, $dados38);
	if ($dadosrw38->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE PRODUTOTMP;";
		echo $sqlcategoria;
	}
	$dados39 = "SELECT * FROM CLIENTEPRODUTOTMP  LIMIT 1";
	$dadosrw39 = mysqli_query($contemporaria, $dados39);
	if ($dadosrw39->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTEPRODUTOTMP;";
		echo $sqlcategoria;
	}
	$dados40 = "SELECT * FROM TICKETTMPVENDAINI  LIMIT 1";
	$dadosrw40 = mysqli_query($contemporaria, $dados40);
	if ($dadosrw40->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMPVENDAINI;";
		echo $sqlcategoria;
	}
	$dados42 = "SELECT * FROM TICKETTMPVENDA  LIMIT 1";
	$dadosrw42 = mysqli_query($contemporaria, $dados42);
	if ($dadosrw42->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMPVENDA;";
		echo $sqlcategoria;
	}
	$dados43 = "SELECT * FROM TICKETTMPINI  LIMIT 1";
	$dadosrw43 = mysqli_query($contemporaria, $dados43);
	if ($dadosrw43->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMPINI;";
		echo $sqlcategoria;
	}
	$dados44 = "SELECT * FROM TICKETTMP  LIMIT 1";
	$dadosrw44 = mysqli_query($contemporaria, $dados44);
	if ($dadosrw44->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMP;";
		echo $sqlcategoria;
	}
	$dados45 = "SELECT * FROM CREDTEMPTIPOD  LIMIT 1";
	$dadosrw45 = mysqli_query($contemporaria, $dados45);
	if ($dadosrw45->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDTEMPTIPOD;";
		echo $sqlcategoria;
	}
	$dados46 = "SELECT * FROM RESGATETMP  LIMIT 1";
	$dadosrw46 = mysqli_query($contemporaria, $dados46);
	if ($dadosrw46->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE RESGATETMP;";
		echo $sqlcategoria;
	}
	$dados47 = "SELECT * FROM RESGATECREDTMP  LIMIT 1";
	$dadosrw47 = mysqli_query($contemporaria, $dados47);
	if ($dadosrw47->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE RESGATECREDTMP;";
		echo $sqlcategoria;
	}
	$dados48 = "SELECT * FROM CREDITOMP  LIMIT 1";
	$dadosrw48 = mysqli_query($contemporaria, $dados48);
	if ($dadosrw48->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDITOMP;";
		echo $sqlcategoria;
	}
	$dados49 = "SELECT * FROM SALDOTMP  LIMIT 1";
	$dadosrw49 = mysqli_query($contemporaria, $dados49);
	if ($dadosrw49->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE SALDOTMP;";
		echo $sqlcategoria;
	}
	$dados50 = "SELECT * FROM VENDAGRPTMP  LIMIT 1";
	$dadosrw50 = mysqli_query($contemporaria, $dados50);
	if ($dadosrw50->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAGRPTMP;";
		echo $sqlcategoria;
	}
	$dados51 = "SELECT * FROM VENDAGRPTMPFINAL  LIMIT 1";
	$dadosrw51 = mysqli_query($contemporaria, $dados51);
	if ($dadosrw51->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAGRPTMPFINAL;";
		echo $sqlcategoria;
	}
	$dados52 = "SELECT * FROM VENDAGRPPRODTMP  LIMIT 1";
	$dadosrw52 = mysqli_query($contemporaria, $dados52);
	if ($dadosrw52->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAGRPPRODTMP;";
		echo $sqlcategoria;
	}
	$dados53 = "SELECT * FROM VENDATMPEXPORTPERSONAS  LIMIT 1";
	$dadosrw53 = mysqli_query($contemporaria, $dados53);
	if ($dadosrw53->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMPEXPORTPERSONAS;";
		echo $sqlcategoria;
	}
	$dados54 = "SELECT * FROM VENDAPRODFINALEXPORT  LIMIT 1";
	$dadosrw54 = mysqli_query($contemporaria, $dados54);
	if ($dadosrw54->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDAPRODFINALEXPORT;";
		echo $sqlcategoria;
	}
	$dados55 = "SELECT * FROM CLIENTETMP  LIMIT 1";
	$dadosrw55 = mysqli_query($contemporaria, $dados55);
	if ($dadosrw55->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTETMP;";
		echo $sqlcategoria;
	}
	$dados56 = "SELECT * FROM RELATFATURAMENTOTMP  LIMIT 1";
	$dadosrw56 = mysqli_query($contemporaria, $dados56);
	if ($dadosrw56->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE RELATFATURAMENTOTMP;";
		echo $sqlcategoria;
	}
	$dados57 = "SELECT * FROM TMP_PERIODO_RELAT  LIMIT 1";
	$dadosrw57 = mysqli_query($contemporaria, $dados57);
	if ($dadosrw57->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_PERIODO_RELAT;";
		echo $sqlcategoria;
	}
	$dados58 = "SELECT * FROM HISTORICO_TMP  LIMIT 1";
	$dadosrw58 = mysqli_query($contemporaria, $dados58);
	if ($dadosrw58->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE HISTORICO_TMP;";
		echo $sqlcategoria;
	}
	$dados59 = "SELECT * FROM CREDDEBTMP  LIMIT 1";
	$dadosrw59 = mysqli_query($contemporaria, $dados59);
	if ($dadosrw59->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CREDDEBTMP;";
		echo $sqlcategoria;
	}
	$dados60 = "SELECT * FROM TMPHISTORICO  LIMIT 1";
	$dadosrw60 = mysqli_query($contemporaria, $dados60);
	if ($dadosrw60->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPHISTORICO;";
		echo $sqlcategoria;
	}
	$dados61 = "SELECT * FROM TMPCREDITODEBITO  LIMIT 1";
	$dadosrw61 = mysqli_query($contemporaria, $dados61);
	if ($dadosrw61->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCREDITODEBITO;";
		echo $sqlcategoria;
	}
	$dados62 = "SELECT * FROM TMPHISTORICOTOTAL  LIMIT 1";
	$dadosrw62 = mysqli_query($contemporaria, $dados62);
	if ($dadosrw62->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPHISTORICOTOTAL;";
		echo $sqlcategoria;
	}
	$dados63 = "SELECT * FROM TMPCREDITODEBITOTOTAL  LIMIT 1";
	$dadosrw63 = mysqli_query($contemporaria, $dados63);
	if ($dadosrw63->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCREDITODEBITOTOTAL;";
		echo $sqlcategoria;
	}
	$dados64 = "SELECT * FROM TMPRETORNOFINAL  LIMIT 1";
	$dadosrw64 = mysqli_query($contemporaria, $dados64);
	if ($dadosrw64->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPRETORNOFINAL;";
		echo $sqlcategoria;
	}
	$dados65 = "SELECT * FROM TMPRETORNO  LIMIT 1";
	$dadosrw65 = mysqli_query($contemporaria, $dados65);
	if ($dadosrw65->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPRETORNO;";
		echo $sqlcategoria;
	}
	$dados66 = "SELECT * FROM TMPRETORNOCREDITO  LIMIT 1";
	$dadosrw66 = mysqli_query($contemporaria, $dados66);
	if ($dadosrw66->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPRETORNOCREDITO;";
		echo $sqlcategoria;
	}
	$dados67 = "SELECT * FROM TMPRETORNODEBITO  LIMIT 1";
	$dadosrw67 = mysqli_query($contemporaria, $dados67);
	if ($dadosrw67->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPRETORNODEBITO;";
		echo $sqlcategoria;
	}
	$dados68 = "SELECT * FROM TMPRETORNOFINALANALITICO  LIMIT 1";
	$dadosrw68 = mysqli_query($contemporaria, $dados68);
	if ($dadosrw68->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPRETORNOFINALANALITICO;";
		echo $sqlcategoria;
	}
	$dados69 = "SELECT * FROM TMP_CODUNIVEND  LIMIT 1";
	$dadosrw69 = mysqli_query($contemporaria, $dados69);
	if ($dadosrw69->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_CODUNIVEND;";
		echo $sqlcategoria;
	}
	$dados70 = "SELECT * FROM TMP_CODUNIVENDCURSOR  LIMIT 1";
	$dadosrw70 = mysqli_query($contemporaria, $dados70);
	if ($dadosrw70->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_CODUNIVENDCURSOR;";
		echo $sqlcategoria;
	}
	$dados71 = "SELECT * FROM TMP_CODPERSONASCURSOR  LIMIT 1";
	$dadosrw71 = mysqli_query($contemporaria, $dados71);
	if ($dadosrw71->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_CODPERSONASCURSOR;";
		echo $sqlcategoria;
	}
	$dados72 = "SELECT * FROM CLIENTEPERSONASTMP  LIMIT 1";
	$dadosrw72 = mysqli_query($contemporaria, $dados72);
	if ($dadosrw72->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIENTEPERSONASTMP;";
		echo $sqlcategoria;
	}
	$dados73 = "SELECT * FROM VENDATMP1  LIMIT 1";
	$dadosrw73 = mysqli_query($contemporaria, $dados73);
	if ($dadosrw73->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMP1;";
		echo $sqlcategoria;
	}
	$dados74 = "SELECT * FROM VENDATMP2  LIMIT 1";
	$dadosrw74 = mysqli_query($contemporaria, $dados74);
	if ($dadosrw74->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMP2;";
		echo $sqlcategoria;
	}
	$dados75 = "SELECT * FROM ITEMVENDATMP1  LIMIT 1";
	$dadosrw75 = mysqli_query($contemporaria, $dados75);
	if ($dadosrw75->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE ITEMVENDATMP1;";
		echo $sqlcategoria;
	}
	$dados76 = "SELECT * FROM ITEMVENDATMP2  LIMIT 1";
	$dadosrw76 = mysqli_query($contemporaria, $dados76);
	if ($dadosrw76->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE ITEMVENDATMP2;";
		echo $sqlcategoria;
	}
	$dados77 = "SELECT * FROM TICKETTMPVENDA2  LIMIT 1";
	$dadosrw77 = mysqli_query($contemporaria, $dados77);
	if ($dadosrw77->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TICKETTMPVENDA2;";
		echo $sqlcategoria;
	}
	$dados78 = "SELECT * FROM VENDACONSOLIDADATMPFINAL  LIMIT 1";
	$dadosrw78 = mysqli_query($contemporaria, $dados78);
	if ($dadosrw78->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDACONSOLIDADATMPFINAL;";
		echo $sqlcategoria;
	}
	$dados79 = "SELECT * FROM VENDACONSOLIDADATMP1  LIMIT 1";
	$dadosrw79 = mysqli_query($contemporaria, $dados79);
	if ($dadosrw79->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDACONSOLIDADATMP1;";
		echo $sqlcategoria;
	}
	$dados80 = "SELECT * FROM VENDACONSOLIDADATMP2  LIMIT 1";
	$dadosrw80 = mysqli_query($contemporaria, $dados80);
	if ($dadosrw80->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDACONSOLIDADATMP2;";
		echo $sqlcategoria;
	}
	$dados81 = "SELECT * FROM TMPCONSOLIDATENDIDO  LIMIT 1";
	$dadosrw81 = mysqli_query($contemporaria, $dados81);
	if ($dadosrw81->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCONSOLIDATENDIDO;";
		echo $sqlcategoria;
	}
	$dados82 = "SELECT * FROM TMPCONSOLIDVENDA  LIMIT 1";
	$dadosrw82 = mysqli_query($contemporaria, $dados82);
	if ($dadosrw82->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCONSOLIDVENDA;";
		echo $sqlcategoria;
	}
	$dados83 = "SELECT * FROM TMP_CODUNIVENDCURSOR  LIMIT 1";
	$dadosrw83 = mysqli_query($contemporaria, $dados83);
	if ($dadosrw83->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMP_CODUNIVENDCURSOR;";
		echo $sqlcategoria;
	}
	$dados84 = "SELECT * FROM CLINATIVOTMP  LIMIT 1";
	$dadosrw84 = mysqli_query($contemporaria, $dados84);
	if ($dadosrw84->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLINATIVOTMP;";
		echo $sqlcategoria;
	}
	$dados85 = "SELECT * FROM CLIATENDIDOSTMP  LIMIT 1";
	$dadosrw85 = mysqli_query($contemporaria, $dados85);
	if ($dadosrw85->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIATENDIDOSTMP;";
		echo $sqlcategoria;
	}
	$dados86 = "SELECT * FROM CLIFATURAMENTOTMP  LIMIT 1";
	$dadosrw86 = mysqli_query($contemporaria, $dados86);
	if ($dadosrw86->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIFATURAMENTOTMP;";
		echo $sqlcategoria;
	}
	$dados87 = "SELECT * FROM CLIEXPIRARTMP  LIMIT 1";
	$dadosrw87 = mysqli_query($contemporaria, $dados87);
	if ($dadosrw87->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIEXPIRARTMP;";
		echo $sqlcategoria;
	}
	$dados88 = "SELECT * FROM TMPCLIEMAIL  LIMIT 1";
	$dadosrw88 = mysqli_query($contemporaria, $dados88);
	if ($dadosrw88->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIEMAIL;";
		echo $sqlcategoria;
	}

	$dados89 = "SELECT * FROM TMPCLINASCIME  LIMIT 1";
	$dadosrw89 = mysqli_query($contemporaria, $dados89);
	if ($dadosrw89->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLINASCIME;";
		echo $sqlcategoria;
	}
	$dados90 = "SELECT * FROM TMPCLICELULAR  LIMIT 1";
	$dadosrw90 = mysqli_query($contemporaria, $dados90);
	if ($dadosrw90->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLICELULAR;";
		echo $sqlcategoria;
	}
	$dados91 = "SELECT * FROM TMPCLIENDERECO  LIMIT 1";
	$dadosrw91 = mysqli_query($contemporaria, $dados91);
	if ($dadosrw91->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIENDERECO;";
		echo $sqlcategoria;
	}
	$dados92 = "SELECT * FROM TMPCLICEP  LIMIT 1";
	$dadosrw92 = mysqli_query($contemporaria, $dados92);
	if ($dadosrw92->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLICEP;";
		echo $sqlcategoria;
	}
	$dados93 = "SELECT * FROM TMPCLINOVOS  LIMIT 1";
	$dadosrw93 = mysqli_query($contemporaria, $dados93);
	if ($dadosrw93->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLINOVOS;";
		echo $sqlcategoria;
	}
	$dados94 = "SELECT * FROM TMPCLINOVOSANT  LIMIT 1";
	$dadosrw94 = mysqli_query($contemporaria, $dados94);
	if ($dadosrw94->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLINOVOSANT;";
		echo $sqlcategoria;
	}
	$dados95 = "SELECT * FROM TMPCLIANIVERSARIO  LIMIT 1";
	$dadosrw95 = mysqli_query($contemporaria, $dados95);
	if ($dadosrw95->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIANIVERSARIO;";
		echo $sqlcategoria;
	}
	$dados96 = "SELECT * FROM TMPVENDAUNIVENDANT  LIMIT 1";
	$dadosrw96 = mysqli_query($contemporaria, $dados96);
	if ($dadosrw96->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVENDANT;";
		echo $sqlcategoria;
	}
	$dados97 = "SELECT * FROM TMPTOTALCLIENTE  LIMIT 1";
	$dadosrw97 = mysqli_query($contemporaria, $dados97);
	if ($dadosrw97->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPTOTALCLIENTE;";
		echo $sqlcategoria;
	}
	$dados98 = "SELECT * FROM TMPVENDAUNIVEND  LIMIT 1";
	$dadosrw98 = mysqli_query($contemporaria, $dados98);
	if ($dadosrw98->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVEND;";
		echo $sqlcategoria;
	}
	$dados99 = "SELECT * FROM TMPCLIEXPIRADO  LIMIT 1";
	$dadosrw99 = mysqli_query($contemporaria, $dados99);
	if ($dadosrw99->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIEXPIRADO;";
		echo $sqlcategoria;
	}
	$dados100 = "SELECT * FROM TMPVENDAUNIVENDFIDEL  LIMIT 1";
	$dadosrw100 = mysqli_query($contemporaria, $dados100);
	if ($dadosrw100->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVENDFIDEL;";
		echo $sqlcategoria;
	}
	$dados101 = "SELECT * FROM TMPVENDAUNIVENDFIDELANT  LIMIT 1";
	$dadosrw101 = mysqli_query($contemporaria, $dados101);
	if ($dadosrw101->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVENDFIDELANT;";
		echo $sqlcategoria;
	}
	$dados102 = "SELECT * FROM TMPVENDAUNIVENDAVULSO  LIMIT 1";
	$dadosrw102 = mysqli_query($contemporaria, $dados102);
	if ($dadosrw102->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVENDAVULSO;";
		echo $sqlcategoria;
	}
	$dados103 = "SELECT * FROM TMPVENDAUNIVENDAVULSOANT  LIMIT 1";
	$dadosrw103 = mysqli_query($contemporaria, $dados103);
	if ($dadosrw103->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVENDAVULSOANT;";
		echo $sqlcategoria;
	}
	$dados104 = "SELECT * FROM TMPVENDAUNIVENDANIVERSARIO  LIMIT 1";
	$dadosrw104 = mysqli_query($contemporaria, $dados104);
	if ($dadosrw104->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDAUNIVENDANIVERSARIO;";
		echo $sqlcategoria;
	}
	$dados105 = "SELECT * FROM TMPVENDABASECLIATIVO  LIMIT 1";
	$dadosrw105 = mysqli_query($contemporaria, $dados105);
	if ($dadosrw105->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPVENDABASECLIATIVO;";
		echo $sqlcategoria;
	}
	$dados106 = "SELECT * FROM TMPCLIMESANOINI  LIMIT 1";
	$dadosrw106 = mysqli_query($contemporaria, $dados106);
	if ($dadosrw106->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANOINI;";
		echo $sqlcategoria;
	}
	$dados107 = "SELECT * FROM TMPCLIMESANO1  LIMIT 1";
	$dadosrw107 = mysqli_query($contemporaria, $dados107);
	if ($dadosrw107->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANO1;";
		echo $sqlcategoria;
	}
	$dados108 = "SELECT * FROM TMPCLIMESANO2  LIMIT 1";
	$dadosrw108 = mysqli_query($contemporaria, $dados108);
	if ($dadosrw108->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANO2;";
		echo $sqlcategoria;
	}
	$dados109 = "SELECT * FROM TMPCLIMESANO3  LIMIT 1";
	$dadosrw109 = mysqli_query($contemporaria, $dados109);
	if ($dadosrw109->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANO3;";
		echo $sqlcategoria;
	}
	$dados110 = "SELECT * FROM TMPCLIMESANO4  LIMIT 1";
	$dadosrw110 = mysqli_query($contemporaria, $dados110);
	if ($dadosrw110->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANO4;";
		echo $sqlcategoria;
	}
	$dados111 = "SELECT * FROM TMPCLIMESANO5  LIMIT 1";
	$dadosrw111 = mysqli_query($contemporaria, $dados111);
	if ($dadosrw111->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANO5;";
		echo $sqlcategoria;
	}
	$dados112 = "SELECT * FROM TMPCLIMESANO6  LIMIT 1";
	$dadosrw112 = mysqli_query($contemporaria, $dados112);
	if ($dadosrw112->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIMESANO6;";
		echo $sqlcategoria;
	}
	$dados113 = "SELECT * FROM TMPCLIRESGATE  LIMIT 1";
	$dadosrw113 = mysqli_query($contemporaria, $dados113);
	if ($dadosrw113->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIRESGATE;";
		echo $sqlcategoria;
	}
	$dados114 = "SELECT * FROM TMPTOTALINATIVO  LIMIT 1";
	$dadosrw114 = mysqli_query($contemporaria, $dados114);
	if ($dadosrw114->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPTOTALINATIVO;";
		echo $sqlcategoria;
	}
	$dados115 = "SELECT * FROM TMPCLINATIVOQTD  LIMIT 1";
	$dadosrw115 = mysqli_query($contemporaria, $dados115);
	if ($dadosrw114->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLINATIVOQTD;";
		echo $sqlcategoria;
	}
	$dados116 = "SELECT * FROM TMPCLIEXPIRAR  LIMIT 1";
	$dadosrw116 = mysqli_query($contemporaria, $dados116);
	if ($dadosrw116->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE TMPCLIEXPIRAR;";
		echo $sqlcategoria;
	}
	$dados117 = "SELECT * FROM CLIFECHAMENTO  LIMIT 1";
	$dadosrw117 = mysqli_query($contemporaria, $dados117);
	if ($dadosrw117->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE CLIFECHAMENTO;";
		echo $sqlcategoria;
	}
	$dados118 = "SELECT * FROM VENDATMP  LIMIT 1";
	$dados118 = mysqli_query($contemporaria, $dados118);
	if ($dados118->num_rows >= 1) {
		$sqlcategoria .= "TRUNCATE TABLE VENDATMP;";
		echo $sqlcategoria;
	}

	mysqli_multi_query($contemporaria, $sqlcategoria);

	echo $sqlcategoria;
	$tdEstorno .= "<tr>
                    <td>TMP</td> 
                    <td>" . date('d/m/Y H:m:s') . "</td>
                    <td>" . $sqlcategoria . "</td>     
                    </tr>";
}
$emailDestino = array(
	'email1' => 'diogo_tank@hotmail.com',
	'email5' => 'coordenacaoti@markafidelizacao.com.br;rone.all@gmail.com'
);
fnsacmail(
	$emailDestino,
	"LIMPA TMP PERSONAS",
	"<html>
                                            <head>
                                                <title>LIMPA TMP PERSONAS</title>
                                                <meta charset='UTF-8'>
                                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                            </head>
                                            <body>
                                                <table border='1'>
                                                   <tr>
                                                   <th>tipo_rotina</th>
                                                    <th>data execucao</th>
                                                    <th>COMMAND</th>
                                                  </tr>
                                                  $tdEstorno
                                                </table>    
                                            </body>
                                        </html>
                                 ",
	"LIMPA TMP PERSONAS",
	"LIMPA TMP PERSONAS",
	$connAdm->connAdm(),
	connTemp($rsconfig['COD_EMPRESA'], ''),
	"3"
);

//      mysqli_close($contemporaria);	
echo 'OK PODE EXECUTAR';

$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => 'http://externo.bunker.mk/Limpa_tables/setup.php',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 100,
	CURLOPT_TIMEOUT => 60000,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "",
	CURLOPT_HTTPHEADER => array(
		"Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
		"cache-control: no-cache"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
	echo "cURL Error #:" . $err;
} else {

	fnsacmail(
		$emailDestino,
		"LIMPA dados xml e comunicação 6 meses",
		$response,
		"LIMPA dados xml e comunicação 6 meses",
		"LIMPA dados xml e comunicação 6 meses",
		$connAdm->connAdm(),
		connTemp($rsconfig['COD_EMPRESA'], ''),
		"3"
	);
}
*/

/*
require '../../_system/_functionsMain.php';
include '../email/envio_sac.php';

$conAdm = $connAdm->connAdm();

// Busca as empresas ativas com comunicação
$sqlEmpresa = "
    SELECT em.LOG_ATIVO, em.COD_EMPRESA, db.DES_DATABASE, db.NOM_DATABASE, db.IP 
    FROM empresas em
    INNER JOIN tab_database db ON db.COD_EMPRESA = em.COD_EMPRESA 
        AND em.cod_empresa NOT IN (136,514)
    WHERE em.LOG_ATIVO = 'S'
    GROUP BY db.NOM_DATABASE
";
$rwEmpresas = mysqli_query($conAdm, $sqlEmpresa);

$tdEstorno = ""; // Variável para armazenar log para e-mail

// Array com os nomes (únicos) de todas as tabelas a serem truncadas
$tables = [
	'VENDABASETMPCONSOLIDADO',
	'VENDABASETMP',
	'clientepersonaexport',
	'retornoclientes',
	'CLIENTEPERSONASTMP',
	'VENDATMPTOPPRODUTO',
	'ITEMVENDAGRPTMPTOPPRODUTO',
	'ITEMVENDAGRPTMPTOPPRODUTOCLIENTE',
	'CLIENTETMPTOPPRODUTO',
	'CLIENTEQTDTMPTOPPRODUTO',
	'RETORNOPRODUTOSTOPPRODUTO',
	'TMP_CODUNIVENDCURSOR',
	'TICKETTMP',
	'VENDATMPEXPORT',
	'CREDITOSDEBITOSRESGATE',
	'VENDACOMPRAGRP',
	'RESGATETMP',
	'RESGATEQTDTMP',
	'VVRTMP',
	'CREDITODISPTMP',
	'CREDITODISPSALDOTMP',
	'CREDITOSALDOTOTALTMP',
	'CREDITODISPSALDO30TMP',
	'CREDITOSDEBITOSTMP',
	'CREDITOSDEBITOSVVR',
	'CLIENTEEXPORTA',
	'UNIDADEVENDA_TMP',
	'ITEMVENDATMPEXPORT',
	'VENDAPRODTMPEXPORT',
	'VENDAPRODFINALEXPORT',
	'VENDAPRODCOMPRA',
	'TICKETTMPPRODUTOINI',
	'TICKETTMPPRODUTO',
	'FILTROPERSONATMP',
	'VENDATMP',
	'VENDATMPACUM',
	'PRODUTOTMP',
	'CLIENTEPRODUTOTMP',
	'TICKETTMPVENDAINI',
	'TICKETTMPVENDA',
	'TICKETTMPINI',
	'CREDTEMPTIPOD',
	'RESGATECREDTMP',
	'CREDITOMP',
	'SALDOTMP',
	'VENDAGRPTMP',
	'VENDAGRPTMPFINAL',
	'VENDAGRPPRODTMP',
	'VENDATMPEXPORTPERSONAS',
	'CLIENTETMP',
	'RELATFATURAMENTOTMP',
	'TMP_PERIODO_RELAT',
	'HISTORICO_TMP',
	'CREDDEBTMP',
	'TMPHISTORICO',
	'TMPCREDITODEBITO',
	'TMPHISTORICOTOTAL',
	'TMPCREDITODEBITOTOTAL',
	'TMPRETORNOFINAL',
	'TMPRETORNO',
	'TMPRETORNOCREDITO',
	'TMPRETORNODEBITO',
	'TMPRETORNOFINALANALITICO',
	'TMP_CODUNIVEND',
	'TMP_CODPERSONASCURSOR',
	'VENDATMP1',
	'VENDATMP2',
	'ITEMVENDATMP1',
	'ITEMVENDATMP2',
	'TICKETTMPVENDA2',
	'VENDACONSOLIDADATMPFINAL',
	'VENDACONSOLIDADATMP1',
	'VENDACONSOLIDADATMP2',
	'TMPCONSOLIDATENDIDO',
	'TMPCONSOLIDVENDA',
	'CLINATIVOTMP',
	'CLIATENDIDOSTMP',
	'CLIFATURAMENTOTMP',
	'CLIEXPIRARTMP',
	'TMPCLIEMAIL',
	'TMPCLINASCIME',
	'TMPCLICELULAR',
	'TMPCLIENDERECO',
	'TMPCLICEP',
	'TMPCLINOVOS',
	'TMPCLINOVOSANT',
	'TMPCLIANIVERSARIO',
	'TMPVENDAUNIVENDANT',
	'TMPTOTALCLIENTE',
	'TMPVENDAUNIVEND',
	'TMPCLIEXPIRADO',
	'TMPVENDAUNIVENDFIDEL',
	'TMPVENDAUNIVENDFIDELANT',
	'TMPVENDAUNIVENDAVULSO',
	'TMPVENDAUNIVENDAVULSOANT',
	'TMPVENDAUNIVENDANIVERSARIO',
	'TMPVENDABASECLIATIVO',
	'TMPCLIMESANOINI',
	'TMPCLIMESANO1',
	'TMPCLIMESANO2',
	'TMPCLIMESANO3',
	'TMPCLIMESANO4',
	'TMPCLIMESANO5',
	'TMPCLIMESANO6',
	'TMPCLIRESGATE',
	'TMPTOTALINATIVO',
	'TMPCLINATIVOQTD',
	'TMPCLIEXPIRAR',
	'CLIFECHAMENTO'
];

while ($rsEmpresa = mysqli_fetch_assoc($rwEmpresas)) {
	// Cria a conexão específica para a empresa
	$conTemp = connTemp($rsEmpresa['COD_EMPRESA'], '');

	// (Opcional) Desabilita temporariamente as checagens de chaves estrangeiras
	mysqli_query($conTemp, "SET FOREIGN_KEY_CHECKS = 0;");

	// Monta a string com todos os comandos TRUNCATE
	$sqlCategoria = "";
	foreach ($tables as $table) {
		// Se quiser verificar se a tabela possui registros antes de truncar, descomente o bloco abaixo:

		$result = mysqli_query($conTemp, "SELECT 1 FROM `$table` LIMIT 1");
		if ($result && mysqli_num_rows($result) > 0) {
			$sqlCategoria .= "TRUNCATE TABLE `$table`; ";
		}

		// Caso contrário, executa o TRUNCATE incondicionalmente:
		//$sqlCategoria .= "TRUNCATE TABLE `$table`; ";
	}

	// Executa os comandos se houver algo a executar
	if (!empty($sqlCategoria)) {
		if (!mysqli_multi_query($conTemp, $sqlCategoria)) {
			error_log("Erro ao truncar tabelas para COD_EMPRESA {$rsEmpresa['COD_EMPRESA']}: " . mysqli_error($conTemp));
			$errolog = "Erro ao truncar tabelas para COD_EMPRESA {$rsEmpresa['COD_EMPRESA']}:";
		}

		// Acrescenta informações para o log de e-mail
		$tdEstorno .= "<tr>
            <td>TMP</td> 
            <td>" . date('d/m/Y H:i:s') . "</td>
            <td>" . $sqlCategoria . "</td> 
			 <td>" . $errolog . "</td>     
        </tr>";
	}

	// Reabilita as checagens de chaves estrangeiras
	mysqli_query($conTemp, "SET FOREIGN_KEY_CHECKS = 1;");
}

// Configura os e-mails de destino
$emailDestino = [
	'email1' => 'diogo_tank@hotmail.com',
	'email5' => 'coordenacaoti@markafidelizacao.com.br;rone.all@gmail.com'
];

// Envia o log por e-mail
fnsacmail(
	$emailDestino,
	"LIMPA TMP PERSONAS",
	"<html>
        <head>
            <title>LIMPA TMP PERSONAS</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body>
            <table border='1'>
                <tr>
                    <th>tipo_rotina</th>
                    <th>data execução</th>
                    <th>COMMAND</th>
					 <th>LOG</th>
                </tr>
                $tdEstorno
            </table>    
        </body>
    </html>",
	"LIMPA TMP PERSONAS",
	"LIMPA TMP PERSONAS",
	$conAdm,
	connTemp($rsconfig['COD_EMPRESA'], ''),
	"3"
);

echo 'OK PODE EXECUTAR';

// Executa chamada via cURL para o outro script
$curl = curl_init();
curl_setopt_array($curl, [
	CURLOPT_URL => 'http://externo.bunker.mk/Limpa_tables/setup.php',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 100,
	CURLOPT_TIMEOUT => 60000,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "",
	CURLOPT_HTTPHEADER => [
		"Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
		"cache-control: no-cache"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	fnsacmail(
		$emailDestino,
		"LIMPA dados xml e comunicação 6 meses",
		$response,
		"LIMPA dados xml e comunicação 6 meses",
		"LIMPA dados xml e comunicação 6 meses",
		$conAdm,
		connTemp($rsconfig['COD_EMPRESA'], ''),
		"3"
	);
}*/

require '../../_system/_functionsMain.php';
include '../email/envio_sac.php';

$conAdm = $connAdm->connAdm();

// Busca as empresas ativas com comunicação
$sqlEmpresa = "
    SELECT em.LOG_ATIVO, em.COD_EMPRESA, db.DES_DATABASE, db.NOM_DATABASE, db.IP 
    FROM empresas em
    INNER JOIN tab_database db ON db.COD_EMPRESA = em.COD_EMPRESA 
        AND em.cod_empresa NOT IN (136,514)
    WHERE em.LOG_ATIVO = 'S'
    GROUP BY db.NOM_DATABASE
";
$rwEmpresas = mysqli_query($conAdm, $sqlEmpresa);

$tdEstorno = ""; // Variável para armazenar as linhas do log (cada linha em um <tr>)

// Array com os nomes (únicos) de todas as tabelas a serem limpas
$tables = [
	'VENDABASETMPCONSOLIDADO',
	'VENDABASETMP',
	'clientepersonaexport',
	'retornoclientes',
	'CLIENTEPERSONASTMP',
	'VENDATMPTOPPRODUTO',
	'ITEMVENDAGRPTMPTOPPRODUTO',
	'ITEMVENDAGRPTMPTOPPRODUTOCLIENTE',
	'CLIENTETMPTOPPRODUTO',
	'CLIENTEQTDTMPTOPPRODUTO',
	'RETORNOPRODUTOSTOPPRODUTO',
	'TMP_CODUNIVENDCURSOR',
	'TICKETTMP',
	'VENDATMPEXPORT',
	'CREDITOSDEBITOSRESGATE',
	'VENDACOMPRAGRP',
	'RESGATETMP',
	'RESGATEQTDTMP',
	'VVRTMP',
	'CREDITODISPTMP',
	'CREDITODISPSALDOTMP',
	'CREDITOSALDOTOTALTMP',
	'CREDITODISPSALDO30TMP',
	'CREDITOSDEBITOSTMP',
	'CREDITOSDEBITOSVVR',
	'CLIENTEEXPORTA',
	'UNIDADEVENDA_TMP',
	'ITEMVENDATMPEXPORT',
	'VENDAPRODTMPEXPORT',
	'VENDAPRODFINALEXPORT',
	'VENDAPRODCOMPRA',
	'TICKETTMPPRODUTOINI',
	'TICKETTMPPRODUTO',
	'FILTROPERSONATMP',
	'VENDATMP',
	'VENDATMPACUM',
	'PRODUTOTMP',
	'CLIENTEPRODUTOTMP',
	'TICKETTMPVENDAINI',
	'TICKETTMPVENDA',
	'TICKETTMPINI',
	'CREDTEMPTIPOD',
	'RESGATECREDTMP',
	'CREDITOMP',
	'SALDOTMP',
	'VENDAGRPTMP',
	'VENDAGRPTMPFINAL',
	'VENDAGRPPRODTMP',
	'VENDATMPEXPORTPERSONAS',
	'CLIENTETMP',
	'RELATFATURAMENTOTMP',
	'TMP_PERIODO_RELAT',
	'HISTORICO_TMP',
	'CREDDEBTMP',
	'TMPHISTORICO',
	'TMPCREDITODEBITO',
	'TMPHISTORICOTOTAL',
	'TMPCREDITODEBITOTOTAL',
	'TMPRETORNOFINAL',
	'TMPRETORNO',
	'TMPRETORNOCREDITO',
	'TMPRETORNODEBITO',
	'TMPRETORNOFINALANALITICO',
	'TMP_CODUNIVEND',
	'TMP_CODPERSONASCURSOR',
	'VENDATMP1',
	'VENDATMP2',
	'ITEMVENDATMP1',
	'ITEMVENDATMP2',
	'TICKETTMPVENDA2',
	'VENDACONSOLIDADATMPFINAL',
	'VENDACONSOLIDADATMP1',
	'VENDACONSOLIDADATMP2',
	'TMPCONSOLIDATENDIDO',
	'TMPCONSOLIDVENDA',
	'CLINATIVOTMP',
	'CLIATENDIDOSTMP',
	'CLIFATURAMENTOTMP',
	'CLIEXPIRARTMP',
	'TMPCLIEMAIL',
	'TMPCLINASCIME',
	'TMPCLICELULAR',
	'TMPCLIENDERECO',
	'TMPCLICEP',
	'TMPCLINOVOS',
	'TMPCLINOVOSANT',
	'TMPCLIANIVERSARIO',
	'TMPVENDAUNIVENDANT',
	'TMPTOTALCLIENTE',
	'TMPVENDAUNIVEND',
	'TMPCLIEXPIRADO',
	'TMPVENDAUNIVENDFIDEL',
	'TMPVENDAUNIVENDFIDELANT',
	'TMPVENDAUNIVENDAVULSO',
	'TMPVENDAUNIVENDAVULSOANT',
	'TMPVENDAUNIVENDANIVERSARIO',
	'TMPVENDABASECLIATIVO',
	'TMPCLIMESANOINI',
	'TMPCLIMESANO1',
	'TMPCLIMESANO2',
	'TMPCLIMESANO3',
	'TMPCLIMESANO4',
	'TMPCLIMESANO5',
	'TMPCLIMESANO6',
	'TMPCLIRESGATE',
	'TMPTOTALINATIVO',
	'TMPCLINATIVOQTD',
	'TMPCLIEXPIRAR',
	'CLIFECHAMENTO'
];

// Define o tamanho do lote para deleção (50 mil linhas por vez)
$batchSize = 50000;

while ($rsEmpresa = mysqli_fetch_assoc($rwEmpresas)) {
	// Cria a conexão específica para a empresa
	$conTemp = connTemp($rsEmpresa['COD_EMPRESA'], '');

	// Desabilita temporariamente as checagens de chaves estrangeiras
	mysqli_query($conTemp, "SET FOREIGN_KEY_CHECKS = 0;");

	foreach ($tables as $table) {
		// Tenta consultar a tabela para verificar se há registros
		$result = mysqli_query($conTemp, "SELECT 1 FROM `$table` LIMIT 1");
		if ($result) {
			if (mysqli_num_rows($result) > 0) {
				$totalDeleted = 0;
				do {
					$deleteQuery = "DELETE FROM `$table` LIMIT $batchSize";
					mysqli_query($conTemp, $deleteQuery);
					$deletedRows = mysqli_affected_rows($conTemp);
					$totalDeleted += $deletedRows;
				} while ($deletedRows > 0);
				$logMessage = "deletados $totalDeleted registros.";
			} else {
				$logMessage = "sem registros.";
			}
		} else {
			$logMessage = "erro na consulta.";
		}
		// Cria uma linha para cada tabela processada
		$tdEstorno .= "<tr>
            <td>TMP</td>
            <td>" . date('d/m/Y H:i:s') . "</td>
            <td>$table</td>
            <td>$logMessage</td>
        </tr>";
	}

	// Reabilita as checagens de chaves estrangeiras
	mysqli_query($conTemp, "SET FOREIGN_KEY_CHECKS = 1;");
}

// Configura os e-mails de destino
$emailDestino = [
	'email1' => 'diogo_tank@hotmail.com',
	'email5' => 'coordenacaoti@markafidelizacao.com.br;rone.all@gmail.com'
];

// Envia o log por e-mail
fnsacmail(
	$emailDestino,
	"LIMPA TMP PERSONAS",
	"<html>
        <head>
            <title>LIMPA TMP PERSONAS</title>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body>
            <table border='1'>
                <tr>
                    <th>Tipo Rotina</th>
                    <th>Data Execução</th>
                    <th>Tabela</th>
                    <th>Log</th>
                </tr>
                $tdEstorno
            </table>    
        </body>
    </html>",
	"LIMPA TMP PERSONAS",
	"LIMPA TMP PERSONAS",
	$conAdm,
	connTemp(3, ''),
	"3"
);

echo 'OK PODE EXECUTAR';

// Executa chamada via cURL para o outro script
$curl = curl_init();
curl_setopt_array($curl, [
	CURLOPT_URL => 'http://externo.bunker.mk/Limpa_tables/setup.php',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 100,
	CURLOPT_TIMEOUT => 60000,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "",
	CURLOPT_HTTPHEADER => [
		"Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
		"cache-control: no-cache"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	fnsacmail(
		$emailDestino,
		"LIMPA dados xml e comunicação 6 meses",
		$response,
		"LIMPA dados xml e comunicação 6 meses",
		"LIMPA dados xml e comunicação 6 meses",
		$conAdm,
		connTemp(3, ''),
		"3"
	);
}
