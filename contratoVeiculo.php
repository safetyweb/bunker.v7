<div class="text-center">
	<p><b> CONTRATO DE <?=$tipoContrato?> PARA CAMPANHA ELEITORAL</b></p>
	<br/>
</div>
<div class="paragrafo">
	<b><?=strtoupper($qrContrato['NOM_FANTASI'])?></b>, candidato(a) a <?=$cargoPolitico?> pelo <?=$qrContrato['DES_PARTIDO']?>/<?=$qrContrato['UF']?>, 
	com número de campanha <?=$qrContrato['NUM_CANDIDATO']?>, inscrita no CNPJ sob o nº <?=fnCompletaDoc($qrContrato['CNPJ'],'J')?>,
	representada por seu administrador financeiro, <?=ucwords(strtolower($qrContrato['NOM_ADMIN']))?>, 
	inscrito no CPF sob nº <span class="cpfcnpj"><?=fnCompletaDoc($qrContrato['CPF_ADMIN'],"F")?></span>, doravante denominado(a) <b>CANDIDATA</b>. 
	<br/>
	Nome: <?=ucwords(strtolower($qrCli['NOM_CLIENTE']))?> (PESSOA <?=$pessoa?>), inscrito(a) no CPF/CNPJ 
	sob nº <span class="cpfcnpj"><?=fnCompletaDoc($qrCli['NUM_CGCECPF'],"$letraPessoa")?></span>, portador(a) do RG nº <?=$qrCli['NUM_RGPESSO']?> 
	com endereço na <?=$qrCli['DES_ENDEREC']?>, no. <?=$qrCli['NUM_ENDEREC']?>, bairro <?=$qrCli['DES_BAIRROC']?>, 
	cidade <?=$qrCli['NOM_MUNICIPIO']?>, estado <?=$qrCli['UF']?>, CEP: <?=$qrCli['NUM_CEPOZOF']?>, 
	<i>WhatsApp</i> <span class="sp_celphones"><?=fnCorrigeTelefone($qrCli['NUM_CELULAR'])?></span>, doravante denominado(a) <b><b>CEDENTE</b></b>. 
	<br/>
	Por este contrato de prestação de serviços, doravante denominado <b>CONTRATO</b>, as <b>PARTES</b>, identificadas e caracterizadas acima, nos 
	termos da Leiº 9.504/97 e da Resolução TSE nº 23.607/2019, celebram entre si o presente instrumento, conforme as seguintes cláusulas:
	<br/>
</div>

<div class="clausula">
	<b>CLÁUSULA 1ª – DO OBJETO</b>
	<br/>
	<br/>
	1.1 – O presente contrato tem por objeto a cessão do veículo Tipo <?=$qrBuscaModulos[DES_TIPO]?> Marca <?=$qrBuscaModulos[DES_MARCA]?>, Modelo <?=$qrBuscaModulos[DES_MODELO]?>, Ano <?=$qrBuscaModulos[DES_ANO]?>, Placa <?=$qrBuscaModulos[DES_PLACA]?>, 
	RENAVAM <?=$qrBuscaModulos[DES_RENAVAM]?>, de propriedade do CEDENTE, para divulgação de campanha eleitoral da CANDIDATA por meio de instalação de som, adesivos, 
	assim como o transporte de materiais e de apoiadores.
	<br/>
	<br/>
	<b>CLÁUSULA 2ª – DA CESSÃO GRATUITA</b>
	<br/>
	<br/>
	2.1 – Pela cessão gratuita do veículo, o valor estimado para fins de prestação de contas de campanha é de R$ <?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?>, 
	valor compatível com o de mercado, nos termos da legislação eleitoral.. 
	<br/>
	<br/>
	<b>CLÁUSULA 3ª – DO PRAZO</b> 
	<br/>
	<br/>
	3.1 – O presente contrato terá vigência de <?=fnDataShort($qrBuscaModulos['DAT_INI'])?> até <?=fnDataShort($qrBuscaModulos['DAT_FIM'])?>, 
	encerrando-se imediatamente após o transcurso do prazo. 
	<br/>
	<br/>
	<b>CLÁUSULA 4ª – DA RESCISÃO</b> 
	<br/>
	<br/>
	4.1 – O contrato pode ser rescindido a qualquer tempo pelas PARTES, sem qualquer tipo de ônus, multa ou outro tipo de encargo. 
	<br/>
	<br/>
	<b>CLÁUSULA 5ª – DAS OBRIGAÇÕES DO(A) <b>CEDENTE</b>:</b> 
	<br/>
	<br/>
	5.1 – Fornecer todos os documentos aptos a comprovar a sua regular inscrição no CPF e a propriedade do veículo ora cedido. 
	<br/>
	<br/>
	5.2 – Não utilizar os dados, informações e documentos que porventura venham a ser disponibilizados em razão do presente contrato, tampouco utilizar o 
	nome e a imagem da <b>CANDIDATA</b>, ainda que para fins exclusivos de divulgação do seu portfólio, sem a prévia e expressa aprovação por escrito. 
	<br/>
	<br/>
	<b>CLÁUSULA 6ª – DAS OBRIGAÇÕES DA <b>CANDIDATA</b></b> 
	<br/>
	<br/>
	6.1 – Arcar com multas de trânsito, despesas e danos causados ao veículo e/ou terceiro, pessoais e materiais, durante o período de vigência contratual, 
	assim como com as despesas de combustível, que deverão ser contratadas mediante a emissão do documento fiscal. 
	<br/>
	<br/>
	6.2 – Restituir o veículo ora locado nas mesmas condições do termo inicial deste <b>CONTRATO</b>. 
	<br/>
	<br/>
	<b>CLÁUSULA 7ª – DO FORO</b> 
	<br/>
	<br/>
	7.1 – Elege-se o foro da comarca de <?=$qrContrato['COMARCA'];?> para dirimir quaisquer controvérsias oriundas do presente contrato, renunciando as partes a qualquer outro, 
	ainda que mais privilegiado.
	<br/>
	<br/>
</div>
E, por estarem justas e acertadas, firmam as <b>PARTES</b> o presente <b>CONTRATO</b>, em 02 (duas) vias de igual teor e para o mesmo fim.
<br/>
<br/>