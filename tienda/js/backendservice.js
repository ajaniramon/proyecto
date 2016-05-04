/* Validaciones */
function isDNI(dni) {
  var numero, let, letra;
  var expresion_regular_dni = /^[XYZ]?\d{5,8}[A-Z]$/;

  dni = dni.toUpperCase();

  if(expresion_regular_dni.test(dni) === true){
    numero = dni.substr(0,dni.length-1);
    numero = numero.replace('X', 0);
    numero = numero.replace('Y', 1);
    numero = numero.replace('Z', 2);
    let = dni.substr(dni.length-1, 1);
    numero = numero % 23;
    letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
    letra = letra.substring(numero, numero+1);
    if (letra != let) {
      //alert('Dni erroneo, la letra del NIF no se corresponde');
      return false;
    }else{
      //alert('Dni correcto');
      return true;
    }
  }else{
    //alert('Dni erroneo, formato no válido');
    return false;
  }
}

function validarEmail( email ) {
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) ){
      return false;
    }else{
      return true;
    }

}

function validarTelefono(telefono){
  var expresion_regular_numeroTelefono = /^([0-9]+){9}$/;
  var expresion_regular_espacios = /\s/;

  if (expresion_regular_numeroTelefono.test(telefono)){
    if (!expresion_regular_espacios.test(telefono)){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

function validarPrecio(precio){
  var expresion_regular_numeroPrecio = /^[0-9]+([,\.][0-9]*)?$/;

  if (expresion_regular_numeroPrecio.test(precio)){
    return true;
  }else{
    return false;
  }

}

function validarStock(stock){
  var expresion_regular_stock = /^(\+?(0|[1-9]\d*))|(-0)$/;

  if (expresion_regular_stock.test(stock)){
    return true;
  }else{
    return false;
  }
}

/* /.Validaciones */


/* Clientes  */

function deleteCliente(cliente){
    swal({
  title: "¿Estás seguro de borrar el cliente?",
  text: "¡No podrás recuperarlo!",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  cancelButtonText: "No",
  confirmButtonText: "Sí",
  closeOnConfirm: false
},
function(){
  $.ajax({
       url: './server/dao/clientedao.php',
       type: 'POST',
       data: {'cliente':cliente},
       success: function(data){
          swal("¡Olé!",data,"success");

          $('#jqGridClientes').trigger('reloadGrid');
          },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });
});

}

function abrirFormularioInsertCliente(){
  $('#modalClienteI').modal("show");

}

function insertCliente(){
  violations = null;
  var nombre = $('#nombreClienteTFI').val();
  var apellidos = $('#apellidoClienteTFI').val();
  var dni = $('#dniClienteTFI').val();
  var direccion = $('#direccionClienteTFI').val();
  var telefono = $('#telefonoClienteTFI').val();
  var correo = $('#correoClienteTFI').val();
  var contrasenya = $('#contrasenyaClienteTFI').val();
  var empleado = document.getElementById('empleadoSLI').options[document.getElementById('empleadoSLI').selectedIndex].value;
  var valid = true;
  violations = new Array();
  if (nombre == "") {
    valid = false;

  };
  if (apellidos == "") {
    valid = false;

  };
  if (dni == "") {
    valid = false;

  };
  if (direccion == "") {
    valid = false;

  };
  if (telefono == "") {
    valid = false;

  };
  if (correo == "") {
    valid = false;

  };
  if (contrasenya == "") {
    valid = false;

  };
  if (dni != "" && !isDNI(dni)) {
    valid = false;
    violations.push('dni');
  };
  if (correo != "" && !validarEmail(correo)) {
    valid = false;
    violations.push('email');
  };
  if (telefono != "" && !validarTelefono(telefono)){
    valid = false;
    violations.push('telefono');
  }

  if (valid) {
    var cliente = new Object();
    cliente.accion = "i";
    cliente.nombre = nombre;
    cliente.apellido = apellidos;
    cliente.dni = dni;
    cliente.direccion = direccion;
    cliente.telefono = telefono;
    cliente.correo = correo;
    cliente.contrasenya = contrasenya;
    cliente.empleado = empleado;
    var clienteJson = JSON.stringify(cliente);
       $.ajax({
       url: './server/dao/clientedao.php',
       type: 'POST',
       data: {'cliente':clienteJson},
       success: function(data){
         swal("¡Olé!",data,"success");
         $('#jqGridClientes').trigger('reloadGrid');
         /*vaciar campos*/
         $('#nombreClienteTFI').val(null);
         $('#apellidoClienteTFI').val(null);
         $('#dniClienteTFI').val(null);
         $('#direccionClienteTFI').val(null);
         $('#telefonoClienteTFI').val(null);
         $('#correoClienteTFI').val(null);
         $('#contrasenyaClienteTFI').val(null);

         $('#modalClienteI').modal('hide');
       },
       error: function(data){
        swal("¡Ups!",data.responseText,"error");
       }
     });
  }else{
    if (violations.length == 0) {
      swal("¡Rellena todos los campos!");
    }else{
      var violationString = "Campos con formato erróneo: ";
      for (var i = 0; i < violations.length; i++) {
        violationString += violations[i] + " ";
      };
      swal(violationString);
    }

  }
}

function abrirFormularioUpdateCliente(){
$('#modalClienteU').modal("show");

}

function updateCliente(){
 violations = null;
  var nombre = $('#nombreClienteTFU').val();
  var apellidos = $('#apellidoClienteTFU').val();
  var dni = $('#dniClienteTFU').val();
  var direccion = $('#direccionClienteTFU').val();
  var telefono = $('#telefonoClienteTFU').val();
  var correo = $('#correoClienteTFU').val();
  var empleado = document.getElementById('empleadoSLU').options[document.getElementById('empleadoSLU').selectedIndex].value;
  var valid = true;
  violations = new Array();
  if (nombre == "") {
    valid = false;

  };
  if (apellidos == "") {
    valid = false;

  };
  if (dni == "") {
    valid = false;

  };
  if (direccion == "") {
    valid = false;

  };
  if (telefono == "") {
    valid = false;

  };
  if (correo == "") {
    valid = false;

  };

  if (dni != "" && !isDNI(dni)) {
    valid = false;
    violations.push('dni');
  };
  if (correo != "" && !validarEmail(correo)) {
    valid = false;
    violations.push('email');
  };

  if (valid) {
    var cliente = new Object();
    cliente.idCliente = $(this).attr('idCliente');
    cliente.accion = "a";
    cliente.nombre = nombre;
    cliente.apellido = apellidos;
    cliente.dni = dni;
    cliente.direccion = direccion;
    cliente.telefono = telefono;
    cliente.correo = correo;
    cliente.empleado = empleado;
    var clienteJson = JSON.stringify(cliente);
       $.ajax({
       url: './server/dao/clientedao.php',
       type: 'POST',
       data: {'cliente':clienteJson},
       success: function(data){
         swal("¡Olé!",data,"success");
         $('#jqGridClientes').trigger('reloadGrid');
         $('#modalClienteU').modal('hide');

       },
       error: function(data){
         swal("¡Ups!",data.responseText,"error");
       }
     });
  }else{
    if (violations.length == 0) {
      swal("¡Rellena todos los campos!");
    }else{
      var violationString = "Campos con formato erróneo: ";
      for (var i = 0; i < violations.length; i++) {
        violationString += violations[i] + " ";
      };
      swal(violationString);
    }

  }
}

function abrirFormularioCambiarContrasenyaCliente(){
$('#modalClienteC').modal("show");

}

function cambiarContrasenyaCliente(){
  var contrasenya = $('#contrasenyaTFC').val();
  var contrasenyaRepetida = $('#contrasenyaRepetidaTFC').val();
  if (contrasenya != contrasenyaRepetida) {
    swal("Las contraseñas no coinciden.");
  }else{
    var cliente = new Object();
    cliente.accion = "c";
    cliente.contrasenya = contrasenya;
    cliente.idCliente = $(this).attr('idCliente');
    var clienteJson = JSON.stringify(cliente);
     $.ajax({
       url: './server/dao/clientedao.php',
       type: 'POST',
       data: {'cliente':clienteJson},
       success: function(data){
         swal("¡Olé!",data,"success");
         $('#contrasenyaTFC').val(null);
         $('#contrasenyaRepetidaTFC').val(null);
         $('#modalClienteC').modal('hide');

       },
       error: function(data){
        console.log(data);
       }
     });
  }
}

/* Categorias */
function deleteCategoria(categoria){
swal({
  title: "¿Estás seguro de borrar la categoría?",
  text: "¡No podrás recuperarla!",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  cancelButtonText: "No",
  confirmButtonText: "Sí",
  closeOnConfirm: false
},
function(){
  $.ajax({
       url: './server/dao/categoriadao.php',
       type: 'POST',
       data: {'categoria':categoria},
       success: function(data){
       swal("¡Olé!",data,"success");
      $('#jqGridCategorias').trigger( 'reloadGrid' );
          },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });
});

}
function abrirFormularioInsertCategoria(){
  $('#modalCategoriaI').modal("show");
  
}
function insertCategoria(){

  if ($('#nombreCategoriaTFI').val() == "" || $('#nombreCategoriaTFI').val() == null) {
    swal("Rellena todos los campos.");
  }else{
    var categoria = new Object();

    categoria.nombre = $('#nombreCategoriaTFI').val();
    categoria.accion = "i";
    var categoriaJson = JSON.stringify(categoria);


      $.ajax({
       url: './server/dao/categoriadao.php',
       type: 'POST',
       data: {'categoria':categoriaJson},
       success: function(data){
         swal("¡Olé!",data,"success");
         $('#jqGridCategorias').trigger( 'reloadGrid' );
         $('#nombreCategoriaTFI').val(null);

         $('#modalCategoriaI').modal("hide");
        },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });
  }
}

function abrirFormularioUpdateCategoria(){
  $('#modalCategoriaU').modal("show");

}

function updateCategoria(){
  var categoria = new Object();
  categoria.nombre = $('#nombreCategoriaTFU').val();
  categoria.idCategoria = $(this).attr('idCategoria');
  categoria.accion = "a";
  var categoriaJson = JSON.stringify(categoria);

  $.ajax({
       url: './server/dao/categoriadao.php',
       type: 'POST',
       data: {'categoria':categoriaJson},
       success: function(data){
          swal("¡Olé!",data,"success");
          $('#jqGridCategorias').trigger( 'reloadGrid' );
          $('#modalCategoriaU').modal("hide");
          },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });

}

/* Articulos */

function deleteArticulo(articulo){
  swal({
  title: "¿Estás seguro de borrar el artículo?",
  text: "¡No podrás recuperarlo!",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  cancelButtonText: "No",
  confirmButtonText: "Sí",
  closeOnConfirm: false
},
function(){
 $.ajax({
         url: './server/dao/articulodao.php',
       type: 'POST',
       data: {'articulo':articulo},
       success: function(data){
       swal("¡Olé!",data,"success");
      $('#jqGridArticulos').trigger('reloadGrid');
          },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });
});

}


function abrirFormularioInsertArticulo(){
	$("#categoriaSLI").empty();

      $.ajax({
       url: './server/categorias.php',
       dataType: 'json',
       success: function(data){
         $.each(data, function(){
          $('#categoriaSLI').append("<option value='" + this.id + "'>" + this.nombre +"</option>");
         });
       }
     });
 

  $('#modalArticuloI').modal("show");
  
}

function insertArticulo(){
  violations = null;
 var valid = true;
 if ($('#nombreArticuloTFI').val() == "") {valid = false;};
 if ($('#descripcionArticuloTFI').val() == "") {valid = false;};
 if ($('#precioArticuloTFI').val() == "") {valid = false;};
 if ($('#stockArticuloTFI').val() == "") {valid = false;};
 if ($('#imagenI').val() == "") {valid = false;};

 var nombre = $('#nombreArticuloTFI').val();
 var descripcion = $('#descripcionArticuloTFI').val();
 var precio = $('#precioArticuloTFI').val();
 var stock = $('#stockArticuloTFI').val();
 var imagen = $('#imagenI').val();
 var categoria = document.getElementById('categoriaSLI').options[document.getElementById('categoriaSLI').selectedIndex].value;
 violations = new Array();

 if (precio != "" && !validarPrecio(precio)){
   valid = false;
   violations.push('precio');
 }

 if (stock != "" && !validarStock(stock)){
   valid = false;
   violations.push('stock');
 }

  if (valid) {
    var articulo = new Object();
    articulo.nombre = nombre;
    articulo.descripcion = descripcion;
    articulo.precio = precio;
    articulo.stock = stock;
    articulo.imagen = imagen;
    articulo.categoria = categoria;
    articulo.accion = "i";

    var articuloJson = JSON.stringify(articulo);
    $.ajax({
       url: './server/dao/articulodao.php',
       type: 'POST',
       data: {'articulo':articuloJson},
       success: function(data){
          swal("¡Olé!",data,"success");
          $('#jqGridArticulos').trigger('reloadGrid');

          $('#nombreArticuloTFI').val(null);
          $('#descripcionArticuloTFI').val(null);
          $('#precioArticuloTFI').val(null);
          $('#stockArticuloTFI').val(null);
          $('#imagenI').val(null);

          $('#modalArticuloI').modal("hide");

          },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });

  }else{
    if (violations.length == 0){
      swal("Rellena todos los campos.");
    }else{
      var violationString = "Campos con formato erróneo: ";
      for (var i = 0; i < violations.length; i++) {
        violationString += violations[i] + " ";
      };
      swal(violationString);
    }
  }
}

function abrirFormularioUpdateArticulo(){
  $('#modalArticuloU').modal('show');
  
}

function updateArticulo(){
  var valid = true;
 if ($('#nombreArticuloTFU').val() == "") {valid = false;};
 if ($('#descripcionArticuloTFU').val() == "") {valid = false;};
 if ($('#precioArticuloTFU').val() == "") {valid = false;};
 if ($('#stockArticuloTFU').val() == "") {valid = false;};
 if ($('#imagenU').val() == "") {valid = false;};
 if (!valid) {
  swal("Rellena todos los campos.");
 }else{
  var articulo = new Object();
  articulo.accion = "a";
  articulo.idArticulo =  $(this).attr('idArticulo');
  articulo.nombre = $('#nombreArticuloTFU').val();
  articulo.descripcion = $('#descripcionArticuloTFU').val();
  articulo.precio = $('#precioArticuloTFU').val();
  articulo.stock = $('#stockArticuloTFU').val();
  articulo.imagen = $('#imagenU').val();
  articulo.categoria = document.getElementById('categoriaSLU').options[document.getElementById('categoriaSLU').selectedIndex].value;
  var articuloJson = JSON.stringify(articulo);
  $.ajax({
       url: './server/dao/articulodao.php',
       type: 'POST',
       data: {'articulo':articuloJson},
       success: function(data){
          swal("¡Olé!",data,"success");
          $("#jqGridArticulos").setGridParam({datatype: 'json',page:1}).trigger('reloadGrid');
          $('#modalArticuloU').modal("hide");

          },
       error: function(data){
          swal("¡Ups!",data.responseText,"error");
       }
     });

 }

}

$(document).ready(function() {
$('#insertarArticuloBT').on('click',insertArticulo);
$('#insertarCategoriaBT').on('click',insertCategoria);
  $('#insertarClienteBT').on('click',insertCliente);
$('#actualizarClienteBT').on("click",updateCliente);
  $('#actualizarArticuloBT').on('click',updateArticulo);
    $('#actualizarCategoriaBT').on('click',updateCategoria);
    $('#actualizarPassClienteBT').on("click",cambiarContrasenyaCliente);
});
