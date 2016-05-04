function logout(){
	 $.ajax({
       url: './server/logout.php',
       type: 'GET',
       success: function(data){
        location.href = "/";
},
       error: function(data){
          console.log("Ha fallado la petición HTTP. "+data.responseText);
       }
     });
}

function saludar(){
	 $.ajax({
       url: './server/session.php',
       type: 'GET',
       success: function(data){
       	var sesionObjeto = JSON.parse(data);
        $('#fraseSesion').html('¡Hola, '+sesionObjeto.nombre + '! Bienvenido al panel de administración.');
        $('#cabeceraLogout').prepend('Has iniciado sesión como: <strong>'+sesionObjeto.nombre + " " + sesionObjeto.apellido + "</strong>.")
},
       error: function(data){
          console.log("Ha fallado la petición HTTP. "+data.responseText);
       }
     });
}

function mostrarClientes(){
  $('#hr').hide();
  $('#cabeceraOcultar').hide();
	$('#capaGridArticulos').hide();
	$('#capaGridCategorias').hide();
	$('#capaGridPedidos').hide();
	$('#a1Cat').hide();
	$('#a2Cat').hide();
	$('#a3Cat').hide();
	$('#a1Art').hide();
	$('#a2Art').hide();
	$('#a3Art').hide();

  $('#hCli').show();
  $('#a1Cli').show();
  $('#a2Cli').show();
  $('#a3Cli').show();
  $('#a4Cli').show();
	$('#capaGridClientes').show();
	jQuery("#jqGridClientes").jqGrid({
    url: 'jqgrid/cliente.php',
    datatype: "json",
    height: "auto",
    colNames: ['idCliente','nombre','apellido','dni','direccion','telefono','correo','empleado'],
    colModel: [
    {
        name: 'idCliente',
        index: 'idCliente',
        width: 100
    },
    {
        name: 'nombre',
        index: 'nombre',
        width: 100
    },
    {
    	name: 'apellido',
    	index: 'apellido',
    	width: 100
    },
    {
    	name: 'dni',
    	index: 'dni',
    	width: 100
    },
    {
    	name: 'direccion',
    	index: 'direccion',
    	width: 100
    },
    {
    	name: 'telefono',
    	index: 'telefono',
    	width: 100
    },
    {
    	name: 'correo',
    	index: 'correo',
    	width: 150
    },
    {
    	name: 'empleado',
    	index: 'empleado',
    	width: 100
    }

    ],
    rowNum: 20,
    rowList: [10, 20, 30],
    pager: '#paginadorClientes',
    sortname: 'idCliente',
    viewrecords: true,
    sortorder: "desc",
    caption: "Clientes"
});


jQuery("#a2Cli").click(function() {
    var id = jQuery("#jqGridClientes").jqGrid('getGridParam', 'selrow');
    if (id) {
      var ret = jQuery("#jqGridClientes").jqGrid('getRowData', id);
      ret.accion = "d";
     	var retJson = JSON.stringify(ret);
     	deleteCliente(retJson);
    } else {
      swal("Por favor, selecciona una fila");
    }
});

	jQuery("#a3Cli").click(function() {
    var id = jQuery("#jqGridClientes").jqGrid('getGridParam', 'selrow');
    if (id) {
      var ret = jQuery("#jqGridClientes").jqGrid('getRowData', id);
      ret.accion = "a";
     	var retJson = JSON.stringify(ret);

     	$('#nombreClienteTFU').val(ret.nombre);
     	$('#apellidoClienteTFU').val(ret.apellido);
     	$('#dniClienteTFU').val(ret.dni);
     	$('#direccionClienteTFU').val(ret.direccion);
     	$('#telefonoClienteTFU').val(ret.telefono);
     	$('#correoClienteTFU').val(ret.correo);

     	if (ret.empleado == "true") {
     		document.getElementById("empleadoSLU").selectedIndex = 0;
     	}else{
     		document.getElementById("empleadoSLU").selectedIndex = 1;
     	}
     	abrirFormularioUpdateCliente();
		$('#actualizarClienteBT').attr('idCliente',ret.idCliente);

    } else {
      swal("Por favor, selecciona una fila");
    }
});

jQuery("#a4Cli").click(function() {
    var id = jQuery("#jqGridClientes").jqGrid('getGridParam', 'selrow');
    if (id) {
        var ret = jQuery("#jqGridClientes").jqGrid('getRowData', id);
        ret.accion = "c";
     	var retJson = JSON.stringify(ret);
     	$('#actualizarPassClienteBT').attr('idCliente',ret.idCliente);
     	abrirFormularioCambiarContrasenyaCliente();
    } else {
        swal("Por favor, selecciona una fila");
    }
});


}

function mostrarCategorias(){
$('#hr').hide();
$('#cabeceraOcultar').hide();
$('#capaGridArticulos').hide();
$('#capaGridClientes').hide();
$('#capaGridPedidos').hide();
$('#capaGridCategorias').show();
$('#a1Cat').show();
$('#a2Cat').show();
$('#a3Cat').show();
$('#hCat').show();
jQuery("#jqGridCategorias").jqGrid({
    url: 'jqgrid/categoria.php',
    datatype: "json",
    height: "auto",
    colNames: ['idCategoria','nombre'],
    colModel: [{
        name: 'idCategoria',
        index: 'idCategoria',
        width: 200
    }, {
        name: 'nombre',
        index: 'nombre',
        width: 400
    }],
    rowNum: 10,
    rowList: [10, 20, 30],
    pager: '#paginadorCategorias',
    sortname: 'idCategoria',
    viewrecords: true,
    sortorder: "desc",
    caption: "Categorias"
});

$('#a1Cat').css("display","initial");
$('#a2Cat').css("display","initial");


$('#a1Cat').on('click',abrirFormularioInsertCategoria);


jQuery("#a2Cat").click(function() {
    var id = jQuery("#jqGridCategorias").jqGrid('getGridParam', 'selrow');
    if (id) {
      var ret = jQuery("#jqGridCategorias").jqGrid('getRowData', id);
      ret.accion = "d";
     	var retJson = JSON.stringify(ret);
     	deleteCategoria(retJson);
    } else {
			swal("Por favor, selecciona una fila");
    }
});

jQuery("#a3Cat").click(function() {
    var id = jQuery("#jqGridCategorias").jqGrid('getGridParam', 'selrow');
    if (id) {
      var ret = jQuery("#jqGridCategorias").jqGrid('getRowData', id);
      ret.accion = "d";
     	var retJson = JSON.stringify(ret);
     	$('#nombreCategoriaTFU').val(ret.nombre);
     	$('#actualizarCategoriaBT').html("Actualizar");
		$('#actualizarCategoriaBT').attr("idcategoria",ret.idCategoria);

     	abrirFormularioUpdateCategoria();
    } else {
      swal("Por favor, selecciona una fila");
    }
});


}


function mostrarArticulos(){
    $('#hr').hide();
$('#cabeceraOcultar').hide();
$('#a1Cat').hide();
$('#a2Cat').hide();
$('#capaGridCategorias').hide();
$('#capaGridPedidos').hide();
$('#capaGridClientes').hide();
$('#hArt').show();
$('#capaGridArticulos').show();
$('#a1Art').show();
$('#a2Art').show();
$('#a3Art').show();
	jQuery("#jqGridArticulos").jqGrid({
    url: 'jqgrid/articulo.php',
    datatype: "json",
    height: "auto",
    colNames: ['idArticulo','nombre','descripcion','precio','imagen','stock','categoria'],
    colModel: [
    {
        name: 'idArticulo',
        index: 'idArticulo',
        width: 100
    },
    {
        name: 'nombre',
        index: 'nombre',
        width: 200
    },
    {
    	name: 'descripcion',
    	index: 'descripcion',
    	width: 300
    },
    {
    	name: 'precio',
    	index: 'precio',
    	width: 100,
			align: 'center'
    },
    {
    	name: 'imagen',
    	index: 'imagen',
    	width: 100
    },
    {
    	name: 'stock',
    	index: 'stock',
    	width: 100,
			align: 'center'
    },
    {
    	name: 'categoria',
    	index: 'categoria',
    	width: 100,
			align: 'center'
    }],
    rowNum: 30,
    rowList: [10, 20, 30],
    pager: '#paginadorArticulos',
    sortname: 'idArticulo',
    viewrecords: true,
    sortorder: "asc",
    caption: "Articulos",
    
});

	jQuery("#a2Art").click(function() {
    var id = jQuery("#jqGridArticulos").jqGrid('getGridParam', 'selrow');
    if (id) {
        var ret = jQuery("#jqGridArticulos").jqGrid('getRowData', id);
        ret.accion = "d";
     	var retJson = JSON.stringify(ret);
     	deleteArticulo(retJson);
    } else {
        swal("Por favor, selecciona una fila");
    }
});
	jQuery("#a3Art").click(function() {
    var id = jQuery("#jqGridArticulos").jqGrid('getGridParam', 'selrow');
    if (id) {
        var ret = jQuery("#jqGridArticulos").jqGrid('getRowData', id);
        ret.accion = "a";
     	var retJson = JSON.stringify(ret);
     	$('#nombreArticuloTFU').val(ret.nombre);
     	$('#descripcionArticuloTFU').val(ret.descripcion);
     	$('#precioArticuloTFU').val(ret.precio);

     	$('#stockArticuloTFU').val(ret.stock);

     	var categoria = document.createElement("option");
     	$("#categoriaSLU").empty();
     	categoria.text = ret.categoria;
     	categoria.value = ret.categoria;
     	document.getElementById("categoriaSLU").add(categoria,null);


		$('#actualizarArticuloBT').attr("idarticulo",ret.idArticulo);



     	abrirFormularioUpdateArticulo();
    } else {
        swal("Por favor, selecciona una fila");
    }
});


}





function mostrarPedidos(){
    $('#hr').hide();
    $('#cabeceraOcultar').hide();
    $('#capaGridArticulos').hide();
    $('#capaGridCategorias').hide();
    $('#capaGridClientes').hide();


    $('#capaGridPedidos').show();
    $('#hPed').show();
    jQuery("#jqGridPedidos").jqGrid({
   	url:'jqgrid/pedido.php',
	datatype: "json",
   	colNames:['idPedido','fecha', 'total', 'dni'],
   	colModel:[
   		{name:'idPedido',index:'idPedido', width:55},
   		{name:'fecha',index:'fecha', width:130},
   		{name:'total',index:'total', width:100, align: 'center'},
   		{name:'dni',index:'dni', width:80, align:"right"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#paginadorPedidos',
   	sortname: 'idPedidios',
    viewrecords: true,
    sortorder: "desc",
	multiselect: false,
	caption: "Pedidos",
	onSelectRow: function(ids) {
		if(ids == null) {
			ids=0;
			if(jQuery("#jqGridPedidos_d").jqGrid('getGridParam','records') >0 )
			{
				jQuery("#jqGridPedidos_d").jqGrid('setGridParam',{url:"jqgrid/lineapedido.php?q=1&id="+ids,page:1});
				jQuery("#jqGridPedidos_d").jqGrid('setCaption',"Linea Pedido nº: "+ids)
				.trigger('reloadGrid');
			}
		} else {
			jQuery("#jqGridPedidos_d").jqGrid('setGridParam',{url:"jqgrid/lineapedido.php?q=1&id="+ids,page:1});
			jQuery("#jqGridPedidos_d").jqGrid('setCaption',"Detalle Pedido nº: "+ids)
			.trigger('reloadGrid');
		}
	}
});
jQuery("#jqGridPedidos").jqGrid('navGrid','#paginadorPedidos_d',{add:false,edit:false,del:false});
jQuery("#jqGridPedidos_d").jqGrid({
	height: 100,
   	url:'jqgrid/lineapedido.php?q=1&id=0',
	datatype: "json",
   	colNames:['idArticulo', 'nombre', 'unidades','Precio total articulo'],
   	colModel:[
   		{name:'idArticulo',index:'idArticulo', width:100},
   		{name:'nombre',index:'nombre', width:200},
   		{name:'unidad',index:'unidad', width:80, align:"right"},
   		{name:'precioTotal',index:'precioTotal', width:150,align:"right", sortable:false, search:false}
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	pager: '#paginadorPedidos_d',
   	sortname: 'idPedido',
    viewrecords: true,
    sortorder: "asc",
	multiselect: false,
	caption:"Detalle Pedido"
}).navGrid('#paginadorPedidos_d',{add:false,edit:false,del:false});
jQuery("#a1Ped").click( function() {
	var s;
	s = jQuery("#jqGridPedidos_d").jqGrid('getGridParam','selarrrow');
	alert(s);
});
}
$(document).ready(function(){
	$('#a1Cat').hide();
	$('#a2Cat').hide();
	$('#a3Cat').hide();
	$('#a1Art').hide();
	$('#a2Art').hide();
	$('#a3Art').hide();
	$('#a1Cli').hide();
	$('#a2Cli').hide();
	$('#a3Cli').hide();
	$('#a4Cli').hide();
  $('#hCli').hide();
  $('#hArt').hide();
  $('#hPed').hide();
  $('#hCat').hide();
	$('#logoutBT').on('click',logout);
	$('#articulosBT').on('click',mostrarArticulos);
	$('#categoriasBT').on('click',mostrarCategorias);
	$('#clientesBT').on('click',mostrarClientes);
  $('#pedidosBT').on('click',mostrarPedidos);
  $('#a1Cli').on("click",abrirFormularioInsertCliente);
  $('#a1Art').on('click',abrirFormularioInsertArticulo);
	saludar();
  });
