<div class="text-center">
	<p><b>CONTRATO DE <?=$tipoContrato?> DE SERVIÇOS PARA CAMPANHA ELEITORAL</b></p>
	<br/>
</div>
<div class="paragrafo">
	
	<b><?=strtoupper($qrContrato['NOM_FANTASI'])?></b>, candidato(a) a <?=$cargoPolitico?> pelo <?=$qrContrato['DES_PARTIDO']?>/<?=$qrContrato['UF']?>, com número de campanha <?=$qrContrato['NUM_CANDIDATO']?>, inscrita no CNPJ sob o nº <?=fnCompletaDoc($qrContrato['CNPJ'],'J')?>, representada por seu administrador financeiro, <?=ucwords(strtolower($qrContrato['NOM_ADMIN']))?>, inscrito no CPF sob nº <span class="cpfcnpj"><?=fnCompletaDoc($qrContrato['CPF_ADMIN'],"F")?></span>, doravante denominado <b>CONTRATANTE</b>. 
	<br/>
	Nome: <?=ucwords(strtolower($qrCli['NOM_CLIENTE']))?> (PESSOA <?=$pessoa?>), inscrito(a) no CPF/CNPJ sob nº <span class="cpfcnpj"><?=fnCompletaDoc($qrCli['NUM_CGCECPF'],"$letraPessoa")?></span>, portador(a) do RG nº <?=$qrCli['NUM_RGPESSO']?> com endereço na <?=$qrCli['DES_ENDEREC']?>, no. <?=$qrCli['NUM_ENDEREC']?>, bairro <?=$qrCli['DES_BAIRROC']?>, cidade <?=$qrCli['NOM_MUNICIPIO']?>, estado <?=$qrCli['UF']?>, CEP: <?=$qrCli['NUM_CEPOZOF']?>, <i>WhatsApp</i> <span class="sp_celphones"><?=fnCorrigeTelefone($qrCli['NUM_CELULAR'])?></span>, doravante denominado(a) <b><b>CONTRATADO(A)</b></b>. 
	<br/>
	Por este contrato de prestação de serviços, doravante denominado <b>CONTRATO</b>, as <b>PARTES</b>, identificadas e caracterizadas acima, nos termos da Leiº 9.504/97 e da Resolução TSE nº 23.607/2019, celebram entre si o presente instrumento, conforme as seguintes cláusulas:
	<br/>
</div>

<div class="clausula">
	<b>CLÁUSULA 1ª – DO OBJETO</b>
	<br/>
	<br/>
	1.1 – O presente contrato tem por objeto a prestação, pelo(a) <b>CONTRATADO(A)</b>, dos seguintes serviços para a campanha eleitoral do <b>CONTRATANTE</b>: coordenador de equipe de mobilização. 
	<br/>
	<br/>
	1.2 – Os serviços serão prestados nos locais e períodos indicados pelo <b>CONTRATANTE</b>. 
	<br/>
	<br/>
	<b>CLÁUSULA 2ª – DO VALOR E FORMA DE PAGAMENTO</b>
	<br/>
	<br/>
	2.1 – Pela prestação dos serviços ora ajustados, o <b>CONTRATANTE</b> pagará ao(a) <b>CONTRATADO(A)</b> o valor total de R$<?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?>. 
	<br/>
	<br/>
	2.2 – O pagamento será exclusivamente efetuado mediante transferência eletrônica – TED, DOC ou PIX, desde que pela chave PIX CPF do <b>CONTRATADO(A)</b> – para conta corrente de sua titularidade. 
	<br/>
	<br/>
	<b>CLÁUSULA 3ª – DO PRAZO</b> 
	<br/>
	<br/>
	3.1 – O presente contrato terá vigência de <?=fnDataShort($qrBuscaModulos['DAT_INI'])?> até <?=fnDataShort($qrBuscaModulos['DAT_FIM'])?>, encerrando-se imediatamente após o transcurso do prazo. 
	<br/>
	<br/>
	<b>CLÁUSULA 4ª – DA RESCISÃO</b> 
	<br/>
	<br/>
	4.1 – O contrato pode ser rescindido a qualquer tempo pelo <b>CONTRATANTE</b>, por sua conveniência, e, com aviso prévio de 7 dias, pelo(a) <b>CONTRATADO(A)</b>, sendo devido ao <b>CONTRATADO(A)</b> o valor proporcional dos serviços prestados até a data da rescisão. 
	<br/>
	<br/>
	4.2 – A rescisão do contrato não produzirá nenhum encargo, vínculo trabalhista ou indenização, nos termos do artigo 100 da Lei 9.504/97. 
	<br/>
	<br/>
	<b>CLÁUSULA 5ª – DAS OBRIGAÇÕES DO(A) <b>CONTRATADO(A)</b>:</b> 
	<br/>
	<br/>
	5.1 – Prestar os serviços nos prazos e condições ajustados com o <b>CONTRATANTE</b> e fornecer todos os documentos aptos a comprovar a sua regular inscrição no CPF, regularidade trabalhista, previdenciária, securitária e tributária, caso solicitado. 
	<br/>
	<br/>
	5.2 – Não utilizar os dados, informações e documentos que porventura venham a ser disponibilizados em razão do presente contrato, tampouco utilizar o nome e a imagem do <b>CONTRATANTE</b>, ainda que para fins exclusivos de divulgação do seu portfólio, sem a prévia e expressa aprovação por escrito. 
	<br/>
	<br/>
	5.3 – Ao término da prestação de serviços, emitir recibo que contenha o local e data da emissão, a descrição e o valor da operação ou prestação, a identificação completa, pelo nome ou razão social, CPF, endereço de residência e assinatura. 
	<br/>
	<br/>
	5.4 – Utilizar uniforme de campanha, caso fornecido pelo <b>CONTRATANTE</b>, com a obrigação de devolvê-lo ao término da prestação de serviço. 
	<br/>
	<br/>
	5.5 – O <b>CONTRATADO(A)</b> declara que não há incompatibilidade de horário entre a prestação dos serviços ora estipulados e eventuais outras funções ou cargos que exerça. 
	<br/>
	<br/>
	<b>CLÁUSULA 6ª – DAS OBRIGAÇÕES DO <b>CONTRATANTE</b></b> 
	<br/>
	<br/>
	6.1 – Disponibilizar ao <b>CONTRATADO(A)</b> todas as informações necessárias à execução do objeto deste contrato, reservado o direito de alterar as diretrizes sem a necessidade de aviso prévio, e efetuar o(s) pagamento(s) devido(s) na forma prevista neste <b>CONTRATO</b>. 
	<br/>
	<br/>
	<b>CLÁUSULA 7ª – DO FORO</b> 
	<br/>
	<br/>
	7.1 – Elege-se o foro da comarca de <?=$qrContrato['COMARCA'];?> para dirimir quaisquer controvérsias oriundas do presente contrato, renunciando as partes a qualquer outro, ainda que mais privilegiado.
	<br/>
	<br/>
</div>
E, por estarem justas e acertadas, firmam as <b>PARTES</b> o presente <b>CONTRATO</b>, em 02 (duas) vias de igual teor e para o mesmo fim.
<br/>
<br/>