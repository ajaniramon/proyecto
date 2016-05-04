//ABRIR UNA VENTANA SITUADA EN EL CENTRO DE LA PANTALLA
// toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=400,height=400
function Open_Vtna(pagina,nombre,w,h,toolbar,location,status,menubar,scroll,resizable)
{
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
	settings = 'top='+TopPosition+',left='+LeftPosition+',toolbar='+toolbar+',location='+location+',status='+status+',menubar='+menubar+',scrollbars=yes,resizable='+resizable+',width='+w+',height='+h;
	//Si firefox o Mozilla eliminamos todas las barras, la hacemos modal,dependiente  y que flote sobre el resto
	if (navigator.appCodeName =='Mozilla')
		settings = settings+',directories=no,personalbar=no,minimizable=no,alwaysRaised=yes,modal=yes,dependent=yes';
	win = window.open(pagina,nombre,settings);
	win.focus();
}



