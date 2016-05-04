var timerID = 0;

function actualizaFechaHora()
{
	if(timerID)
	{
		clearTimeout(timerID);
	}

	var fecha = new Date();
	var minutos;
	if(fecha.getMinutes()<10)
		minutos = "0"+fecha.getMinutes();
	else
		minutos = fecha.getMinutes();
	var obj = document.getElementById("timeBar");
	var new_txt = document.createTextNode(" " + fecha.getHours() + ":" + minutos);	
	obj.replaceChild(new_txt, obj.childNodes[0]);
	timerID = setTimeout("actualizaHora()", 1000);
	//Añadido
	var str_Fecha = displayFecha();
	var objF = document.getElementById("dateBar");
	var F_txt = document.createTextNode(str_Fecha);
	objF.replaceChild(F_txt, objF.childNodes[0]);
}

function actualizaHora()
{
   if(timerID)
   {
      clearTimeout(timerID);
   }

   var fecha = new Date();
   var minutos;
   if(fecha.getMinutes()<10)
     minutos = "0"+fecha.getMinutes();
   else
         minutos = fecha.getMinutes();
   var obj = document.getElementById("timeBar");
   var new_txt = document.createTextNode(" " + fecha.getHours() + ":" + minutos);
   obj.replaceChild(new_txt, obj.childNodes[0]);
   timerID = setTimeout("actualizaHora()", 1000);
}

function Stop()
{
   if(timerID)
   {
      clearTimeout(timerID);
      timerID  = 0;
   }
}

function displayFecha() {
  var today = new Date();
  var day   = today.getDate();
  if (day < 10) day = "0"+day;
  var month = today.getMonth() +1;
  if (month < 10) month = "0"+month;
  var year  = today.getYear();
  var dia = today.getDay();
    if (year < 1000) {
       year += 1900; }
  var fecha = (day + "/" + month + "/" + year);
  return fecha;
}