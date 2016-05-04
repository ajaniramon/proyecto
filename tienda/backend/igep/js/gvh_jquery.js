/* ***********************************************
/* ***********************************************
 * INICIALIZACIÓN DE COLUMNAS RESIZABLES AL INICIO
 *************************************************/
var i=0;
$("table[id*=tablePanel_]").each(function() {
	id = $(this).attr('id');
	$(this).colResizable({
			liveDrag:true, 
			gripInnerHtml:"<div class='glyphicon glyphicon-triangle-bottom'></div>", 
			draggingClass:"dragging"
	});
});
$("table[id*=tablePanelD_]").each(function() {
	id = $(this).attr('id');
	$(this).colResizable({
			liveDrag:true, 
			gripInnerHtml:"<div class='glyphicon glyphicon-triangle-bottom'></div>", 
			draggingClass:"dragging"
	});
});
/* ***********************************************
/* ***********************************************
 * MOSTRAR/OCULTAR PANELES 
 *************************************************/
$('#fil').on({
	  click: function() {
		var class_button = $(this).hasClass('disabled');
		if (class_button == false)
		{
			var target_button = $(this).attr('data-target');
			var $target = $( target_button );
			if ($target.is(':hidden'))
			{
				$target.show( "slow" );
			}
			else
			{
				$target.hide( "slow" );
			}
		}
	  }
	}
);

$('#lis').on({
	click: function() {
		var class_button = $(this).hasClass('disabled');
		if (class_button == false)
		{
			var target_button = $(this).attr('data-target');
			var $target = $( target_button );
					  
			if ($target.is(':hidden'))
			{
				$target.show( "slow" );
			}
			else
			{
				$target.hide( "slow" );
			}
			console.log($target.is(':visible'));
		}
	}
});



/* ***********************************************
/* ***********************************************
 * MOSTRAR/OCULTAR MENSAJE INFORMATIVO 
 *************************************************/
$('#info_fil').on({
	  click: function() {
		var target_button = $(this).attr('data-target');
		var $target = $( target_button );
		if ($target.is(':hidden'))
		{
			$target.show();
		console.log($target.is(':visible'));
		}
		else
		{
			$target.hide();
		console.log($target.is(':oculto'));
		}
	  }
	}
);

$('#info_lis').on({
	  click: function() {
		var target_button = $(this).attr('data-target');
		var $target = $( target_button );
		if ($target.is(':hidden'))
		{
			$target.show();
		console.log($target.is(':visible'));
		}
		else
		{
			$target.hide();
		console.log($target.is(':oculto'));
		}
	  }
	}
);

$('#info_edi').on({
	  click: function() {
		var target_button = $(this).attr('data-target');
		var $target = $( target_button );
		if ($target.is(':hidden'))
		{
			$target.show();
		console.log($target.is(':visible'));
		}
		else
		{
			$target.hide();
		console.log($target.is(':oculto'));
		}
	  }
	}
);

$('#info_ediDetalle').on({
	  click: function() {
		var target_button = $(this).attr('data-target');
		var $target = $( target_button );
		if ($target.is(':hidden'))
		{
			$target.show();
		console.log($target.is(':visible'));
		}
		else
		{
			$target.hide();
		console.log($target.is(':oculto'));
		}
	  }
	}
);
$('#info_lisDetalle').on({
	  click: function() {
		var target_button = $(this).attr('data-target');
		var $target = $( target_button );
		if ($target.is(':hidden'))
		{
			$target.show();
		console.log($target.is(':visible'));
		}
		else
		{
			$target.hide();
		console.log($target.is(':oculto'));
		}
	  }
	}
);



/* ***********************************************
/* ***********************************************
 * BOTONES TOOLTIP 
 *************************************************/
var fila = '';
$(':checkbox').filter('[id*=check_]').on({
	  click: function() {
		var idCheck = $(this).attr('id');
		var ini = idCheck.indexOf("_");
		var fila = idCheck.substr(ini);
	  }
	}
);

$('button[id*=btnEdit_]').on({
	  click: function() {
		  var id_button = $(this).attr('id');
		  var ini = id_button.indexOf("_");
		  var fila = id_button.substr(ini);
		  // Combobox
			$("input[id*='combo_']").each(function() {
				panel = $(this).attr('data-gvhPanel');
				id = $(this).attr('id');
				var vId = id.split('__');
				if (vId[2] == fila)
				{
					if ((panel == 'lis') || (panel == 'lisDetalle'))
					{
						$(this).removeAttr('disabled');
						$(this).addClass('tableModify');
						$('a[id*='+fila+']').button('enable');
					}
				}
				else
				{
					if ((panel == 'lis') || (panel == 'lisDetalle'))
					{
						$(this).attr('disabled');
						$(this).addClass('tableNoEdit');
						$(this).removeClass('tableModify');
					}
				}
			});
	  }
});

 //variable que determina si el boton esta o no habilitado
$('button[id*=img_bttl]').on({
	  click: function() {
			var position = $(this).attr('data-gvhposition');
			var panelOn = $(this).attr('data-gvhpanelOn');
			var id_button = $(this).attr('id');
			if (position == 'botonera') // botones que están en la botonera
			{
				if ((panelOn == 'panel_edi') || (panelOn == 'panel_ediDetalle'))
				{
					$('button[id*=img_bttl]').not('button[id='+id_button+']').addClass("disabled"); // Desactivamos todos los que no hemos pulsado de la botonera
					$('button[data-gvhposition='+panelOn+']').removeClass("disabled");
					// Activamos botones file upload
		            $('input:file').attr('disabled',false);
					
					// Combobox
					$("input[id*='combo_']").each(function() {
						panel = $(this).attr('data-gvhPanel');
						id = $(this).attr('id');
						var ini = id.indexOf("_");
						var campoReg = id.substr(ini);
						if (((panelOn == 'panel_edi') && (panel == 'edi'))
							|| ((panelOn == 'panel_ediDetalle') && (panel == 'ediDetalle')))
						{
							$(this).removeAttr('disabled');
							$(this).addClass('modify');
							$('a[id*='+campoReg+']').button('enable');
						}
					});
			
					
				}
				$('button[id*=img_bttlLimpiar]').removeClass("disabled"); // El botón limpiar permanece activo
				
				
				var fila = '';
				if ((panelOn == 'panel_lis') || (panelOn == 'panel_lisDetalle')) 
				{
					// En el panel tabular, desactivamos los correspondientes al panel Maestro o Detalle
					if (panelOn == 'panel_lis')
					{
						$('button[data-gvhposition=panel_lisDetalle]').addClass("disabled");
						$('button[data-gvhposition=panel_ediDetalle]').addClass("disabled");
					}
					if (panelOn == 'panel_lisDetalle')
					{
						$('button[data-gvhposition=panel_lis]').addClass("disabled");
						$('button[data-gvhposition=panel_edi]').addClass("disabled");
					}
					// Fila seleccionada
					if (id_button.indexOf('img_bttlInsertar') == 0)
					{
						// Activamos los botones de la fila seleccionada
						$('button[data-gvhposition='+panelOn+']').filter('[id*=ins___]').removeClass("disabled");
						// Activamos botones file upload de la fila
			            $('input:file').filter('[id*=ins___]').attr('disabled',false);
					}
					$("input:checkbox:checked").filter('[id*=check_]').each(function(){
							//cada elemento seleccionado
							var idCheck = $(this).attr('id');
							var ini = idCheck.indexOf("_");
							fila = idCheck.substr(ini);
							// Activamos los botones de la fila seleccionada
							$('button[data-gvhposition='+panelOn+']').filter('[id*='+idCheck.substr(ini)+']').removeClass("disabled");
							// Activamos botones file upload de la fila
				            $('input:file').filter('[id*='+idCheck.substr(ini)+']').attr('disabled',false);
					});
					
					if ((id_button == 'img_bttlInsertar_lis') || (id_button == 'img_bttlInsertar_lisDetalle'))
					{
						$('button[data-gvhModo=insert]').removeClass("off");

						$('input[id*=combo_ins]').removeAttr('disabled');
						$('input[id*=combo_ins]').addClass('tableModify');
						$('a[id*=comboDown_ins]').button('enable');
					}
					else
					{
						// Combobox
						$("input[id*='combo_']").each(function() {
							panel = $(this).attr('data-gvhPanel');
							id = $(this).attr('id');
							var campoReg = id.indexOf(fila);
							if (campoReg > 0)
							{
								if (((panelOn == 'panel_lis') && (panel == 'lis'))
									|| ((panelOn == 'panel_lisDetalle') && (panel == 'lisDetalle')))
								{
									$(this).removeAttr('disabled');
									$(this).addClass('tableModify');
									$('a[id*='+fila+']').button('enable');
								}
							}
						});
					}
				}
			}
		  }
		}
);


$('button[id*=img_bttlLimpiar]').on({
	  click: function() {
		  $('input[type="radio"]').each(function () {
		        $(this).prop('checked', false);
		        valueRadio = $(this).val();
		        idRadio = $(this).attr('id');
		        if ($(this).attr('id').substr(0,5) == 'cam__')
		        {
		        	longitud = $(this).attr('name').length;		        	
		        	antValue = 'ant'+ $(this).attr('name').substr(3,longitud);
		        	if (valueRadio == $('#'+antValue).val())
						$(this).prop('checked', true);
		        }
			})
	  }
});