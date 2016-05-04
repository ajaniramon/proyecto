function Carrito(id){
this.id = id;
this.articulos = new Array();
this.total = total;
}
Carrito.prototype.addArticulo = function(articulo){
	this.articulos.push(articulo);
}
Carrito.prototype.toJson = function(){
	return JSON.stringify(this);
}

function Articulo(id,nombre,descripcion,precio,imagen,categoria,cantidad){
this.id = id;
this.nombre = nombre;
this.descripcion = descripcion;
this.precio = precio;
this.imagen = imagen;
this.categoria = categoria;
this.cantidad = cantidad;
}
Articulo.prototype.toJson = function(){
	return JSON.stringify(this);
}

function Categoria(nombre, id){
this.nombre = nombre;
this.id = id;
}


function Credencial(email,contrasenya){
	this.email = email;
	this.contrasenya = contrasenya;
}
Credencial.prototype.toJson = function(){
	return JSON.stringify(this);
}

function Cliente(nombre,apellido,dni,direccion,telefono,correo,contrasenya){
	this.nombre = nombre;
	this.apellido = apellido;
	this.dni = dni;
	this.direccion = direccion;
	this.telefono = telefono;
	this.correo = correo;
	this.contrasenya = contrasenya;
}
Cliente.prototype.toJson = function(){
	return JSON.stringify(this);
}