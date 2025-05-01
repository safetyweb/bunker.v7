$(document).ready(function(){

	// back top top
	var offset = 300,
		offset_opacity = 1200,
		scroll_top_duration = 700,
		$back_to_top = $('.cd-top');

	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});

	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
			}, scroll_top_duration
		);
	});			
	
	//menu
	// $("#menu").mmenu({
	// 	// options
	// 	extensions	: ["theme-white"]
	// }, {
	// 	// configuration
	// 	offCanvas: {
	// 		pageSelector: ".outContainer"
	// 	}
	// });

	var API = $("#menu").data("mmenu");

	$(".btnMenu").click(function() {
		API.close();
	});
	
	try {
		API.bind( "closed", function() {
			API.closeAllPanels();
		});
	}
	catch(err) {
		//alert("pegou...");
	}	
		
	var corTexto = $(".navbar .navbar-brand").css("color");

	$("#menu").css("top", ($(".menuCentral").height()) + "px");

	$("#menu").css("background-color", $(".navbar").css("background-color"));
	$("#menu").css("opacity", "0.9");

	$("#menu").css("color", corTexto);
	$("#menu").find('.mm-title').css("color", corTexto);
	
	$('head').append('<style>'
					+'.mm-prev:before, .mm-prev:after, .mm-next:before, .mm-next:after{'
					+'      border-color: ' +corTexto+ ' !important ;}</style>');
	
	$('head').append('<style>'
					+'#menuLateral > div > a.active.navbar-brand{'
					+'border-left: 3px solid } </style>');
	
	$(".navbar-brand").click(function() {
		$("#menuLateral > div > a.active.navbar-brand").removeClass("active");
		$(this).addClass("active");
	});
	
	$(document).scroll(function(){
		var positionScroll = $("body").scrollTop();
		var menuSize = $(".menuCentral").height();

		if(positionScroll == 0){
			$(".navbar-fixed-left").css("top", $(".menuCentral").height()+ "px");
			$("#menu").css("top", ($(".menuCentral").height()) + "px");
		} else if (positionScroll > 0 && positionScroll <= 50){
			$(".navbar-fixed-left").css("top", ($(".menuCentral").height() - positionScroll) + "px");
			$("#menu").css("top", ($(".menuCentral").height() - positionScroll + 20) + "px");
		}else{
			$(".navbar-fixed-left").css("top", "0");
			$("#menu").css("top", "0");
		}
	});
	
	//choosen
	// $(".chosen-select-deselect").chosen({allow_single_deselect:true});

	
	// //máscaras
	// $('.money').mask("#.##0,00", {reverse: true});
	// $('.int').mask("##0", {reverse: true});
	// $('.data').mask('00/00/0000');
	// //$('.hora').mask('00:00:00');
	// $('.hora').mask('00:00');
	// $('.cep').mask('00000-000');
	// $('.fone').mask('(00) 00000-0000');	
	// $('.celular').mask('(00) 00000-0000');
	// $('.cpf').mask('000.000.000-00', {reverse: true});
	
	// if($('.cpfcnpj').val() != undefined){
	// 	mascaraCpfCnpj($('.cpfcnpj'));
	// }

	// var SPMaskBehavior = function (val) {
	//   return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	// },
	// spOptions = {
	//   onKeyPress: function(val, e, field, options) {
	// 	  field.mask(SPMaskBehavior.apply({}, arguments), options);
	// 	}
	// };

	// $('.cel').mask(SPMaskBehavior, spOptions);

	
	//criticas de envio
	var btnValue = "";
	$(document).on('click', '.getBtn', function(){ 
	   btnValue = $(this).attr("name");
	});		
			
	// $('#formulario').validator().on('submit', function (e) {
	//   if (e.isDefaultPrevented()) {
	// 	// handle the invalid form...
	// 	//alert("Preencha o campos obrigatórios");
	//   } else {
	// 	// everything looks good!
	// 	e.preventDefault(); 
	// 	$("#formulario #opcao").val(btnValue);	
		
	// 	if (btnValue == 'CAD'){
	// 		$("#CAD, #ALT, #EXC").prop('disabled', true);
	// 		try {$("#blocker").show();} catch(err) {};
	// 		$("#formulario")[0].submit();
	// 		//alert("entrou cad");
	// 	}		
		
	// 	if (btnValue == 'ALT') {
	// 		if ($("#formulario #hHabilitado").val()=='S') {
	// 			$("#CAD, #ALT, #EXC").prop('disabled', true);
	// 			try {$("#blocker").show();} catch(err) {};
	// 			$("#formulario")[0].submit();
	// 			$("#hHabilitado").val('N');
	// 		} else{
	// 			alert("Para alteração selecione um registro na lista");
	// 		}						
	// 	}
		
	// 	if (btnValue == 'EXC') {
	// 		if ($("#formulario #hHabilitado").val()=='S') {
	// 			$.confirm({
	// 				title: 'Atenção!',
	// 				animation: 'opacity',
	// 				closeAnimation: 'opacity',
	// 				content: 'Deseja realmente excluir esse registro?',
	// 				buttons: {
	// 					confirmar: function () {
	// 						$("#CAD, #ALT, #EXC").prop('disabled', true);
	// 						$("#formulario")[0].submit();
	// 						$("#hHabilitado").val('N');
	// 					},
	// 					cancelar: function () {
							
	// 					},
	// 				}
	// 			});	 
	// 		} else{
	// 			$.alert('Para exclusão selecione um registro na lista');
	// 		}						
	// 	}
		
	// 	if (btnValue == 'ADD'){			
	// 		$("#BUS, #ADD").prop('disabled', true);		
	// 		$('#formulario').attr('action', 'action.php?mod='+$("#dUrl").val()+'&id='+$("#dKey").val()+'&idC='+$("#dId").val()+' ');					
	// 		$('#formulario').validator('validate');	
	// 		$("#formulario")[0].submit();
	// 	}

	// 	if (btnValue == 'BUS'){
	// 		$("#BUS, #ADD").prop('disabled', true);
	// 		$("#formulario #hHabilitado").val('S');
	// 		$('#formulario').validator('validate');			
	// 		$("#formulario")[0].submit();
	// 	}		

	//   }
	// })


	//fecha alert automático
	$("#msgRetorno").fadeTo(15000, 500).fadeToggle(500, function(){
		$("#msgRetorno").alert('close');
	});
	
	
	//tooltip
	$('[data-toggle="tooltip"]').tooltip(); 
	
	//modal
	$("body").on("click", ".addBox", function() {												
		var popLink = $(this).attr("data-url");
		var popTitle = $(this).attr("data-title");
		//alert(popLink);	
		setIframe(popLink, popTitle);
		$('.modal').appendTo("body").modal('show');
	});	
	
	$(".nav-tabs li").on("click", function(e) {
	  if ($(this).hasClass("disabled")) {
		e.preventDefault();
		return false;
	  }
	});	
	
	// $(".tablesorter").tablesorter({
	// 	theme : 'bootstrap',
	// 	widgets: ['columns'],
	//     dateFormat : "ddmmyyyy", // set the default date format

	//     // or to change the format for specific columns, add the dateFormat to the headers option:
	//     headers: {
	//       0: { sorter: "shortDate" } //, dateFormat will parsed as the default above
	//       // 1: { sorter: "shortDate", dateFormat: "ddmmyyyy" }, // set day first format; set using class names
	//       // 2: { sorter: "shortDate", dateFormat: "yyyymmdd" }  // set year first format; set using data attributes (jQuery data)
	// 	}
	// });
	// // ordenador de moedas
	// $.tablesorter.addParser({ id: "moeda", is: function(s) { return true; }, format: function(s) { return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g),"")); }, type: "numeric" });

	$(".cpfcnpj, .cpf, .cnpj").change(function() {
		if(!valida_cpf_cnpj($(this).val())){
			$.alert({
				title: 'Atenção!',
				content: 'CPF/CNPJ digitado é inválido!',
			});			
		}
	});	
});	
	//Funções
	
	function carregarPaginacao(totalPaginas){
		var $pagination = $('#paginacao');
		var defaultOpts = {
			totalPages: totalPaginas,
			visiblePages: 10,
			initiateStartPageClick: false,
			first: '<i class="glyphicon glyphicon-step-backward" aria-hidden="true"></i>',
			prev: '<i class="glyphicon glyphicon-triangle-left" aria-hidden="true"></i>',
			next: '<i class="glyphicon glyphicon-triangle-right" aria-hidden="true"></i>',
			last: '<i class="glyphicon glyphicon-step-forward" aria-hidden="true"></i>',
			onPageClick: function (event, page) {
				reloadPage(page);
			}
		};	
		$pagination.twbsPagination(defaultOpts);		
	}
	
	function SaveToDisk(fileURL, fileName) {
		// for non-IE
		if (!window.ActiveXObject) {
			var save = document.createElement('a');
			save.href = fileURL;
			save.target = '_blank';
			save.download = fileName || 'unknown';

			var evt = new MouseEvent('click', {
				'view': window,
				'bubbles': true,
				'cancelable': false
			});
			save.dispatchEvent(evt);

			(window.URL || window.webkitURL).revokeObjectURL(save.href);
		}

		// for IE < 11
		else if ( !! window.ActiveXObject && document.execCommand)     {
			var _window = window.open(fileURL, '_blank');
			_window.document.close();
			_window.document.execCommand('SaveAs', true, fileName || fileURL)
			_window.close();
		}
	}	
	
	function mascaraCpfCnpj(cpfCnpj){
		var optionsCpfCnpj = {
			onKeyPress: function (cpf, ev, el, op) {
				var masks = ['000.000.000-000', '00.000.000/0000-00'],
					mask = (cpf.length >= 15) ? masks[1] : masks[0];
				cpfCnpj.mask(mask, op);
			}
		}	

		var masks = ['000.000.000-000', '00.000.000/0000-00'];
		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
			
		cpfCnpj.mask(mask, optionsCpfCnpj);		
	}	
	
	//call modal
	function setIframe(src, title) {
		$(".modal iframe").attr({
			'src': src
		});
		if (title) {
			$(".modal-title").text(title);
		} else {
			$(".modal-title").text("");
		}
	}

	function converterValorTela(number,decimais){
		var temp = number * 1;
		temp+='';
		if( temp == 'NaN' )
			return number;
		number = number * 1;
		if(decimais == null){
			number = number.toFixed(8);
			number = number * 1;
		}else{
			number = number.toFixed(decimais);
		}
		number += '';
		x = number.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}	
	
	function limpaValor(valor){
		var limpaPonto = valor.replace('.','').replace('.','');
		var valorSql = eval(limpaPonto.replace(',','.'));
		return valorSql
	}	
	
	function converterFloatValueToCalc(value){
		var newValue = 0;
		if(value.trim() != ""){
			newValue = value.replace('.', '').replace(',', '.');
		}
		 
		return parseFloat(newValue);
	}
	
	function verifica_cpf_cnpj ( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');

		// Verifica CPF
		if ( valor.length === 11 ) {
			return 'CPF';
		} 
		
		// Verifica CNPJ
		else if ( valor.length === 14 ) {
			return 'CNPJ';
		} 
		
		// Não retorna nada
		else {
			return false;
		}
		
	}

	function valida_cpf_cnpj ( valor ) {

		// Verifica se é CPF ou CNPJ
		var valida = verifica_cpf_cnpj( valor );

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');


		// Valida CPF
		if ( valida === 'CPF' ) {
			// Retorna true para cpf válido
			return valida_cpf( valor );
		} 
		
		// Valida CNPJ
		else if ( valida === 'CNPJ' ) {
			// Retorna true para CNPJ válido
			return valida_cnpj( valor );
		} 
		
		// Não retorna nada
		else {
			return false;
		}
	}	
	
	function valida_cpf( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');


		// Captura os 9 primeiros dígitos do CPF
		// Ex.: 02546288423 = 025462884
		var digitos = valor.substr(0, 9);

		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
		var novo_cpf = calc_digitos_posicoes( digitos );

		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
		var novo_cpf = calc_digitos_posicoes( novo_cpf, 11 );

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		if ( novo_cpf === valor ) {
			// CPF válido
			return true;
		} else {
			// CPF inválido
			return false;
		}
		
	} // valida_cpf

	/*
	 valida_cnpj
	 
	 Valida se for um CNPJ
	 
	 @param string cnpj
	 @return bool true para CNPJ correto
	*/
	function valida_cnpj ( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');

		
		// O valor original
		var cnpj_original = valor;

		// Captura os primeiros 12 números do CNPJ
		var primeiros_numeros_cnpj = valor.substr( 0, 12 );

		// Faz o primeiro cálculo
		var primeiro_calculo = calc_digitos_posicoes( primeiros_numeros_cnpj, 5 );

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		var segundo_calculo = calc_digitos_posicoes( primeiro_calculo, 6 );

		// Concatena o segundo dígito ao CNPJ
		var cnpj = segundo_calculo;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ( cnpj === cnpj_original ) {
			return true;
		}
		
		// Retorna falso por padrão
		return false;
		
	} // valida_cnpj	
	
	function calc_digitos_posicoes( digitos, posicoes = 10, soma_digitos = 0 ) {

		// Garante que o valor é uma string
		digitos = digitos.toString();

		// Faz a soma dos dígitos com a posição
		// Ex. para 10 posições:
		//   0    2    5    4    6    2    8    8   4
		// x10   x9   x8   x7   x6   x5   x4   x3  x2
		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
		for ( var i = 0; i < digitos.length; i++  ) {
			// Preenche a soma com o dígito vezes a posição
			soma_digitos = soma_digitos + ( digitos[i] * posicoes );

			// Subtrai 1 da posição
			posicoes--;

			// Parte específica para CNPJ
			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
			if ( posicoes < 2 ) {
				// Retorno a posição para 9
				posicoes = 9;
			}
		}

		// Captura o resto da divisão entre soma_digitos dividido por 11
		// Ex.: 196 % 11 = 9
		soma_digitos = soma_digitos % 11;

		// Verifica se soma_digitos é menor que 2
		if ( soma_digitos < 2 ) {
			// soma_digitos agora será zero
			soma_digitos = 0;
		} else {
			// Se for maior que 2, o resultado é 11 menos soma_digitos
			// Ex.: 11 - 9 = 2
			// Nosso dígito procurado é 2
			soma_digitos = 11 - soma_digitos;
		}

		// Concatena mais um dígito aos primeiro nove dígitos
		// Ex.: 025462884 + 2 = 0254628842
		var cpf = digitos + soma_digitos;

		// Retorna
		return cpf;
		
	} // calc_digitos_posicoes	