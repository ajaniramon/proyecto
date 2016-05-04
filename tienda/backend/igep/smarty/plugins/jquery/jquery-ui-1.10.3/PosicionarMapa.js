//      PosicionarMapa.js
//      
//      Copyright 2013 Miguel Rafael Esteban Martín (www.logicaalternativa.com) <miguel.esteban@logicaalternativa.com>
//      
//      This program is free software; you can redistribute it and/or modify
//      it under the terms of the GNU General Public License as published by
//      the Free Software Foundation; either version 2 of the License, or
//      (at your option) any later version.
//      
//      This program is distributed in the hope that it will be useful,
//      but WITHOUT ANY WARRANTY; without even the implied warranty of
//      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//      GNU General Public License for more details.
//      
//      You should have received a copy of the GNU General Public License
//      along with this program; if not, write to the Free Software
//      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//      MA 02110-1301, USA.


// VARIABLES GLOBALES SIEMPRE CON EL PREFIJO pm_g_..

var pm_g_map;              // El objeto mapa
var pm_g_markers;          // El objeto que tiene las marcas del mapa

var count = 0;

// Array que almacena las marcas y los popup del mapa y que nos ayudará 
// a que el borrado sea más sencillo  
var pm_g_marcas_array;        
var pm_g_marcas_array_indice; // Siguiente índice del array

var pm_g_proxy = "proxy.php?url="; // La URL del proxy ( Cross-Domain Proxy) 
var pm_g_logo = "logo.png";        // La URL del logo que aparece en el popup de las marcas

var pm_g_cadenaBusqueda;     // La cadena de búsqueda 

var mapnik;
var markers;

/*
 * 
 * Dibuja el mapa en el div que se pasa como argumento
 * Antes se inicializan los valores globales
 * 
 * Al mapa se le añaden los controles:
 *  - Navegación
 *  - Zoom
 *  - Posición del ratón
 *  - Doble click para añadir marcas
 * 
 * Se configura el Zoom para que se vea todo el MapaMundi
 * Se crea el capa de marcas 'Markers' y se le asigna al mapa.
 * 
 * Esta función se llama desde el onLoad de la página o al inicializar
 * el mapa
 * 
 * */
function dibujarMapa(  div, lon, lat, zoom, campoLongitud, campoLatitud, campoProyeccion ) {
	
		inicializar();
		
		var curpos = new Array();
		var position;
		
		var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
		var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
		var cntrposition  = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);

		
		 pm_g_map = new OpenLayers.Map( div,{
					 controls: [
					            new OpenLayers.Control.PanZoomBar(),                        
			                    new OpenLayers.Control.MousePosition({}),
			                    new OpenLayers.Control.Navigation(),
			                    new OpenLayers.Control.ScaleLine(),
			                    new OpenLayers.Control.OverviewMap(),
					 ],
					 // Se configura que la proyección para mostrar los datos
					 // sea EPSG:4326 (Proyeción xy, la que estamos 
					 // acostumbrados al usuar los GPS) para el Control del
					 // ratón
					 displayProjection: new OpenLayers.Projection("EPSG:4326") 
				});
				
		 
		 mapnik = new OpenLayers.Layer.OSM("MAP"); 
		 markers = new OpenLayers.Layer.Markers( "Markers" );
	        
	     pm_g_map.addLayers([mapnik,markers]);
	     pm_g_map.addLayer(mapnik);
	     pm_g_map.setCenter(cntrposition, zoom);

	     markers.addMarker(new OpenLayers.Marker(cntrposition));
		// Se añade el control de doble click que añade marcas al mapa
		// (La definición de la clase está al final del archivo)
	    var control = new OpenLayers.Control.Click();
        pm_g_map.addControl(control);	
		control.activate(); // Se tiene que activar
				
		pm_g_map.addLayer(new OpenLayers.Layer.OSM()); // Se le asigna la capa de OpenStreetMap
		
		//pm_g_map.zoomToMaxExtent(); // Se pone el zoom para que se vea todo el mapamundi 
		
		// Se crea el objeto Markers y se le asigna al mapa
		pm_g_markers = new OpenLayers.Layer.Markers( "Markers" );	
		pm_g_map.addLayer( pm_g_markers );
      

	}
	
	/*
	 * Función que inicializa los valores globales. 
	 * Destruye el mapa y las marcas 
	 * */
	function inicializar(){
		
		if ( pm_g_markers != null ) {
			
		   pm_g_markers.destroy();
			
		}
		
		if ( pm_g_map != null ) {
			
		   pm_g_map.destroy();
			
		}
		
		pm_g_cadenaBusqueda = null;
		
		pm_g_marcas_array = new Array();
		pm_g_marcas_array_indice = 0;

	}
    
	/*
	 * Envía la dirección que se pasa como argumento al servicio de 
	 * OpenRoutService mediante una llamada POST.
	 * Primero se le asina el proxy a OpenLayer que hemos asignado en la
	 * variable global 'pm_g_proxy'
	 * Si la petición ha ido bien se llamará a la función 'requestSuccess'.
	 * Si ha ido mal se llamará a la función 'requestFailure'
	 * 
	 * Está función se llama desde cuando se envía el formulario con la
	 * dirección que el usuario ha tecleado en la caja de texto
	 * */
	 
	function enviarPeticion( valor ) {
		pm_g_cadenaBusqueda = valor;       // se asigna el valor a la variable global de la cadena de búsqueda
		OpenLayers.ProxyHost = pm_g_proxy; // Se le asigna la URL del proxy Cross-Domain
		OpenLayers.Request.POST({
				url: "http://www.openrouteservice.org/php/OpenLSLUS_Geocode.php",
				scope: this,
				failure: this.requestFailure, // Llamada función cuando hay error
				success: this.requestSuccess, // Llamada cuando todo va bien
				headers: {"Content-Type": "application/x-www-form-urlencoded"},
				data: "FreeFormAdress=" + encodeURIComponent( pm_g_cadenaBusqueda ) + "&MaxResponse=25"
			});
	}
	
	/*
	 * Función que se ejecuta cuando se ha realizado sin errores la 
	 * petición al servicio de geolocalización.
	 * 
	 * Obtiene del xml de respuesta el primer nodo 'geometry'
	 * Con la longitud y la latitud de este nodo se obtiene el punto del
	 * mapa. Se añade la marca, el menú y se escala el mapa para que 
	 * aparezcan todas las marcas con la mayor precisión posible.
	 * 
	 * */
	function requestSuccess( response ) {
		
		var format = new OpenLayers.Format.XLS();
		
		try {
			var output = format.read(response.responseText);
			if (output
					&& output.responseLists[0]
						&& output.responseLists[0].features[0] ) {
				
				var geometry = output.responseLists[0].features[0].geometry; // Longitud y latitud de la dirección
				anadirMarcaMenuYCentrar( geometry.x, geometry.y , pm_g_cadenaBusqueda );

			} else {
				
				alert("Dirección no encontrada, por favor se más específico");
				
			}
			
		} catch(err) {
			alert( "Error indeterminado al añadir la marca");
			console.log( "Error indeterminado al añadir la marca " + error );
		}
		
	}
	
	
	
	/*
	 * Función que obtiene la posición en el mapa a partir de la longitud
	 * y la latitud.
	 *  1) Añade la marca a la capa de marcas
	 *  2) Crea el popup asociado a la marca
	 *  3) Asocia el evento click de la marca a la apertura del popup
	 *  4) Guarda la pareja marca y popup en el array de marcas
	 * */
	function anadirMarcaMenuYCentrar( lon, lat, texto ) {
		
	
		 markers.destroy();
		
		var posicion = obtenerPuntoMapa( lon, lat );
		
		// Se obtiene el id de marca.
		var id = obtenerIdMarca();		
		
		
		 var cont = count;
		str2 = cont.toString();
		str1="marca";
		var totalCont = str1.concat(str2); 
		
		
		if(id=="marca0")
		{
			
			// Se crea y se añade el popup
		var textoMenu = obtenerTextoPopUp( texto, id, lon, lat );
		var popup = crearMenu( posicion, textoMenu ); 
		
		// Se crea la marca
		var marca = new OpenLayers.Marker( posicion );	
		
		// Se añade el evento onclick a la marca para abrir el menú
		marca.events.register("click", popup, function () {  
			this.toggle();  // Se muestra o se esconde
		});
		
		// Se añade la marca a la capa de marcas
		pm_g_markers.addMarker( marca );
		
		// Se añade la pareja marca-poup al array global pm_g_marcas_array
		var marcaPopup = new Object();
		
		marcaPopup.marca = marca;
		marcaPopup.popup = popup;
		
		pm_g_marcas_array[id] = marcaPopup;	
		
		// Se escala el mapa para que se vean todas las marcas con la mayor precisión posible
		//escalarZoom(); 
		
		}
		
		else {

		count++;
			// Se crea y se añade el popup
		var textoMenu = obtenerTextoPopUp( texto, id, lon, lat );
		var popup = crearMenu( posicion, textoMenu ); 
		
		// Se crea la marca
		var marca = new OpenLayers.Marker( posicion );	
		
		// Se añade el evento onclick a la marca para abrir el menú
		marca.events.register("click", popup, function () {  
			this.toggle();  // Se muestra o se esconde
		});
		
		// Se añade la marca a la capa de marcas
		pm_g_markers.addMarker( marca );
		
		// Se añade la pareja marca-poup al array global pm_g_marcas_array
		var marcaPopup = new Object();
		
		marcaPopup.marca = marca;
		marcaPopup.popup = popup;
		
		pm_g_marcas_array[id] = marcaPopup;	
		
		// Se escala el mapa para que se vean todas las marcas con la mayor precisión posible
		//escalarZoom();
		
		
		
		id = totalCont;	
		var marcaPopup = pm_g_marcas_array[id];
		
		var marca = marcaPopup.marca;
		var popup = marcaPopup.popup;
		
		popup.hide();
		
		pm_g_map.removePopup( popup ); // Se borra el poup del mapa
		pm_g_markers.removeMarker( marca ); // Se borra la marca de la capa de marcas
		
		// Se llama al método destroy
		popup.destroy();
		marca.destroy();
			
		// Se eliminan del array global
		popup = null;
		marca = null;
		pm_g_marcas_array[id] = null;
		
			
		// Se vuelve a escalar el zoom
		//escalarZoom();
		
		
		
		}
		
	
		
	}
	
	/*
	 * Función que obtiene el texto del popup.
	 * Se añade el logo el texto que se pasa como argumento y la logitud
	 * y la latitud redondeada a 7 decimales.
	 * También se añade un enlace con una llamada a la función 
	 * 'borrarMarca' pasandole como argumento el id de la pareja 
	 * marca-popup
	 * */	
	function obtenerTextoPopUp( texto, id, lon, lat ){
		
		var precision = 10000000;
		var textoMenu = '<img src="' + pm_g_logo + '"> <b>'+ texto +'</b><br/> ';
		var textoMenu = '<b>'+ texto +'</b><br/> ';
		textoMenu += '( Lon: ' ;
		textoMenu +=(Math.round(lon* precision)/precision );
		textoMenu +=  ', Lat: ' 
		textoMenu +=  (Math.round(lat * precision)/precision ) 
		textoMenu +=  ')<br/>';
		//textoMenu +="<a href=\"javaScript:borrarMarca('" + id + "')\">Borrar marca</a>";
		
		return textoMenu;
		
	}
	
	/*
	 * Obtiene la posición en el mapa del punto representado por la 
	 * longitud(x) y la latitud (y)
	 * */
	function obtenerPuntoMapa( x, y ){
		
		var posicion = new OpenLayers.LonLat(x, y).transform(
						new OpenLayers.Projection("EPSG:4326"),
						pm_g_map.getProjectionObject()
						);
						
		return posicion;
		
	}
	
	
	/*
	 * Escala el mapa para que se obtengan todas las marcas con la mayor
	 * preción posible.
	 * 
	 * Se recorren todas las posiciones de las marcas del mapa y se 
	 * obtienen los valores
	 *  1) Longitud mínima (longitudMinOeste)
	 *  2) Longitud máxima (longitudMaxEste)
	 *  3) Latitud mínima (latitudMinSur)
	 *  4) Latitud máxima (latitudMaxNorte)
	 * 
	 * Después se ajusta el zoom del mapa al cuadrado formado por estos 
	 * dos puntos ( longitudMinOeste, latitudMinSur ) y
	 * ( longitudMaxEste, latitudMaxNorte )
	 * */
	function escalarZoom(){
		
		// Para cuando sólo sea una marca se ajusta el zoom a 15  y se 
		// centra el mapa en ese punto
		if ( pm_g_markers.markers.length == 1 ) {
			
			pm_g_map.setCenter( pm_g_markers.markers[0].lonlat, 15 );
			return;
			
		}
		
		// Para el resto de casos
		var longitudMinOeste = +180;
		var longitudMaxEste = -180;
		var latitudMinSur  = 90;
		var latitudMaxNorte  = -90;
		
		var marcas = pm_g_markers.markers;
		
		for(var a = 0; a < marcas.length; a++){
			
			var marca = marcas[a];
			
			if (marca != null) {
				
				var lonlat = transFormarRepresentacionMapaCoordenadasXY( marca.lonlat );
				
				var x = lonlat.lon;
				var y = lonlat.lat;
				
				if (x < longitudMinOeste ) {
				
					longitudMinOeste = x;
					
				}						
				
				if ( x > longitudMaxEste ) {
					
					longitudMaxEste = x;
					
				}
						
				if (y < latitudMinSur) {
					
					latitudMinSur = y;
					
				}						
				
				if ( y > latitudMaxNorte ) {
					
					latitudMaxNorte = y;
					
				}			
				
			}
			
		}
		var punto1 = obtenerPuntoMapa( longitudMinOeste,latitudMinSur );
		var punto2 = obtenerPuntoMapa( longitudMaxEste,latitudMaxNorte );

		var bbox = new OpenLayers.Bounds();
		bbox.extend(punto1);
		bbox.extend(punto2);
		
		pm_g_map.zoomToExtent( bbox );			
		
	}
	
	
	/*
	 * Se añade el menú a la marca en el punto del mapa que representa.
	 * Además se añade el evento 'click' a la marca para que 
	 * muestre/oculte el menú
	 * Lo último que se hace es ocultar todos los menús abiertos para
	 * que sólo se muestre el que se acaba de añadir
	 * */
	function crearMenu( posicion, textoMenu  ){
		
		var popup = new OpenLayers.Popup.FramedCloud(
				null, //id
				posicion, // lonlat
				new OpenLayers.Size(200, 200), // tamaño
				textoMenu, // Contenido
				null, // Anchura
				true  // Se cierra
			);

		popup.autoSize = true;  // configuración inicial
		
		// Se añade el popup al mapa. 
		pm_g_map.addPopup( popup ) ; 
				
		// Oculta todos los popup menós el que se acaba de añadir
		ocultarTodosLosPopUpMenosElUltimo();
		
		return popup;
		
	}
	
	/*
	 * Oculta todos los popup menos el último 
	 * Para ello se recorre el array de popup del primero al penultimo
	 * y se oculta
	 * 
	 * */
	function ocultarTodosLosPopUpMenosElUltimo( ){
		
		for(var a = 0; a < ( pm_g_map.popups.length -1) ; a++){
			pm_g_map.popups[a].hide();
		};
		
	}
	
	
	/*
	 * Transforma la logitud y la latitud de la posición de la proyección
	 * del mapa a la proyeccion EPSG:4326 (Coordenadas Geográficas WGS84,
	 * las coordenadas que se suelen usar en los GPS). 
	 * Normalmente las capas de mapas (OpenStreetMap, Google, ...) la
	 * posición se dan en las cordenadas de la Proyección de Mercator
	 * 
	 * */	
	function transFormarRepresentacionMapaCoordenadasXY( posicion ){
		
		var lonlat = posicion.clone();                   
	  
		  lonlat.transform(							
				pm_g_map.getProjectionObject(),
				new OpenLayers.Projection("EPSG:4326")
			);
			
		return lonlat;	
		
	}
	
	/*
	 * Borra la marca y el popup asociado.
	 * 
	 * Para ello la función se vale del erray de marcas para saber
	 * cual es la marca que hay que borrar de la capa de marcas y cual
	 * es el popup que hay que borrar del mapa.
	 * Después que se haya borrado, se llama al método 'destroy' de ambos
	 * objetos y se borra del array pm_g_marcas_array
	 * */
	function borrarMarca( idMarca ){
		
		// Se obtiene la pareja marca-popup
		var marcaPopup = pm_g_marcas_array[idMarca];
		
		var marca = marcaPopup.marca;
		var popup = marcaPopup.popup;
		
		popup.hide();
		
		pm_g_map.removePopup( popup ); // Se borra el poup del mapa
		pm_g_markers.removeMarker( marca ); // Se borra la marca de la capa de marcas
		
		// Se llama al método destroy
		popup.destroy();
		marca.destroy();
		
		// Se eliminan del array global
		popup = null;
		marca = null;
		pm_g_marcas_array[idMarca] = null;
		
		// Se vuelve a escalar el zoom
		escalarZoom();
		
	}
	
	
	/*
	 * Función cuando hay un error en la petición del servicio de
	 * Geolocalización 
	 * */
	function requestFailure(response) {
		alert("Error en la comuniación con el servicio OpenLS");
		console.log( "Error en la comuniación con el servicio OpenLS " + response );
	}
	
	/*
	 * Obtiene el id de marca.
	 * Se concatena la cadena 'marca' al valor del indicie siguiente
	 * y se actauliza el indice sumandole 1
	 * */
	function obtenerIdMarca(){
		
		var res = "marca" + pm_g_marcas_array_indice;
		
		pm_g_marcas_array_indice++;
		
		return res;
		
	}
	
	/*
	 * Función que carga la URL del proxy en las variables globales
	 * */
	function setProxy (aProxy) {
			
		pm_g_proxy = aProxy;
		
	}	
	
	/*
	 * Función que carga la URL del logo en las variables globales
	 * */
	 function setLogo (aLogo) {
			
		pm_g_logo = aLogo;
		
	}
	
	// Clase que define el evento doble click del mapa.
	// Cada vez que se haga doble click en un punto del mapa se llamará
	// a la función 'dobleClick' que obtendrá las coordenadas del punto
	// marcado y añadirá la marca
	
	 OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
                defaultHandlerOptions: {
                    'single': true,
                    'double': true,
                    'pixelTolerance': 0,
                    'stopSingle': false,
                    'stopDouble': true
                },

                initialize: function(options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                        {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                        this, arguments
                    ); 
                    this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'dblclick': this.dobleClick
                        }, this.handlerOptions
                    );
                }, 

                dobleClick: function(e) {
						 
						// Se obtiene las coordenadas del pixel marcado 
						var lonlat = pm_g_map.getLonLatFromPixel(e.xy);                   

						// Se obtien la representación en coordenadas XY
						lonlat = transFormarRepresentacionMapaCoordenadasXY( lonlat );

						// Se añade la marca
						anadirMarcaMenuYCentrar( lonlat.lon, lonlat.lat, 'Punto seleccionado' );
						
						daCoordenadas(lonlat.lon, lonlat.lat, campoLongitud, campoLatitud, campoProyeccion);
                }

            });
	
	
	
