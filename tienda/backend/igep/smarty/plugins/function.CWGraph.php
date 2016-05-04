<?php
/* gvHIDRA. Herramienta Integral de Desarrollo Rápido de Aplicaciones de la Generalitat Valenciana
*
* Copyright (C) 2006 Generalitat Valenciana.
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,USA.
*
* For more information, contact:
*
*  Generalitat Valenciana
*  Conselleria d'Infraestructures i Transport
*  Av. Blasco Ibáñez, 50
*  46010 VALENCIA
*  SPAIN
*  +34 96386 24 83
*  gvhidra@gva.es
*  www.gvpontis.gva.es
*
*/
/**
 * Created on 21-mar-2005 
 *
 * @version	$Id: CWBotonTooltip.php
 * @author David: <pascual_dav@gva.es> 
 * @author Ivan: <xxxx@gva.es>
 * @author Vero: <navarro_ver@gva.es> 
 * @author Toni: <felix_ant@gva.es>
 * @package	gvHIDRA
 **/
require_once('igep/include/IgepSmarty.php');

function smarty_function_CWGraph($params, &$smarty) 
{

	$chartType = $params['chartType'];
	
	$charttitulo = $params['titulo'];
	
	$action = $params['action'];
	
	$id = $params['id'];
	
	$meta = $params['meta'];
	

	
	// Morris.js Charts
	//Dependiendo del estilo cargamos un JS
	if($chartType=='morris-area-chart') {
		
		//Obtenemos los xkey, ykeys y labels
		$xkey = $meta['xkey'];
		
		$ykeys = implode("','",$meta['ykeys']);
		$ykeys = "['".$ykeys."']";
		
		$labels = implode("','",$meta['labels']);
		$labels = "['".$labels."']";
	
		$ini_pantalla = "
		<script type='text/javascript'> 
	
		$(function() {
	
		var json = (function () {
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				'url': 'phrame.php?action=".$action."',
				'dataType': \"json\",
				'success': function (data) {
					json = data;
				}
			});
			return json;
		})
		();	
	    // Area Chart
	    Morris.Area({
	        element: '".$id."',
	        data: json,
	        xkey: '".$xkey."',		
	        ykeys: ".$ykeys.",
		    labels: ".$labels.",
	        pointSize: 2,
	        hideHover: 'auto',
	        resize: true
	    });
		});
		</script>";
	}
	
	elseif($chartType=='morris-donut-chart') {
		
		
		$colors = '';
		if(!empty($meta['colors'])) {
			$colors = implode("','",$meta['colors']);
			$colors = "['".$colors."']";
			$colors ="colors: ".$colors.',';
		}
	
	
	    // Donut Chart
		$ini_pantalla = "
			<script type='text/javascript'> 
		
			$(function() {	
				var json = (function () {
					var json = null;
					$.ajax({
						'async': false,
						'global': false,
						'url': 'phrame.php?action=".$action."',
						'dataType': \"json\",
						'success': function (data) {
							json = data;
						}
					});
					return json;
				})
				();
				Morris.Donut({
	        	element: '".$id."',
	        	data: json,
				".$colors."
	        	resize: true
	    	});
	        });
	    </script>";
		
	}
	
	/*   
    // Line Chart
    Morris.Line({
        // ID of the element in which to draw the chart.
        element: 'morris-line-chart',
        // Chart data records -- each entry in this array corresponds to a point on
        // the chart.
        data: [{
            d: '2012-10-01',
            visits: 802
        }, {
            d: '2012-10-02',
            visits: 783
        }, {
            d: '2012-10-03',
            visits: 820
        }, {
            d: '2012-10-04',
            visits: 839
        }, {
            d: '2012-10-05',
            visits: 792
        }, {
            d: '2012-10-06',
            visits: 859
        }, {
            d: '2012-10-07',
            visits: 790
        }, {
            d: '2012-10-08',
            visits: 1680
        }, {
            d: '2012-10-09',
            visits: 1592
        }, {
            d: '2012-10-10',
            visits: 1420
        }, {
            d: '2012-10-11',
            visits: 882
        }, {
            d: '2012-10-12',
            visits: 889
        }, {
            d: '2012-10-13',
            visits: 819
        }, {
            d: '2012-10-14',
            visits: 849
        }, {
            d: '2012-10-15',
            visits: 870
        }, {
            d: '2012-10-16',
            visits: 1063
        }, {
            d: '2012-10-17',
            visits: 1192
        }, {
            d: '2012-10-18',
            visits: 1224
        }, {
            d: '2012-10-19',
            visits: 1329
        }, {
            d: '2012-10-20',
            visits: 1329
        }, {
            d: '2012-10-21',
            visits: 1239
        }, {
            d: '2012-10-22',
            visits: 1190
        }, {
            d: '2012-10-23',
            visits: 1312
        }, {
            d: '2012-10-24',
            visits: 1293
        }, {
            d: '2012-10-25',
            visits: 1283
        }, {
            d: '2012-10-26',
            visits: 1248
        }, {
            d: '2012-10-27',
            visits: 1323
        }, {
            d: '2012-10-28',
            visits: 1390
        }, {
            d: '2012-10-29',
            visits: 1420
        }, {
            d: '2012-10-30',
            visits: 1529
        }, {
            d: '2012-10-31',
            visits: 1892
        }, ],
        // The name of the data record attribute that contains x-visitss.
        xkey: 'd',
        // A list of names of data record attributes that contain y-visitss.
        ykeys: ['visits'],
        // Labels for the ykeys -- will be displayed when you hover over the
        // chart.
        labels: ['Visits'],
        // Disables line smoothing
        smooth: false,
        resize: true
    });

    // Bar Chart
    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            device: 'iPhone',
            geekbench: 136
        }, {
            device: 'iPhone 3G',
            geekbench: 137
        }, {
            device: 'iPhone 3GS',
            geekbench: 275
        }, {
            device: 'iPhone 4',
            geekbench: 380
        }, {
            device: 'iPhone 4S',
            geekbench: 655
        }, {
            device: 'iPhone 5',
            geekbench: 1571
        }],
        xkey: 'device',
        ykeys: ['geekbench'],
        labels: ['Geekbench'],
        barRatio: 0.4,
        xLabelAngle: 35,
        hideHover: 'auto',
        resize: true
    });


});		

	*/
	
	$ini_pantalla .= "<div class='panel panel-default'>	";
	$ini_pantalla .= "<div class='panel-heading'>";
	$ini_pantalla .= "<div class='panel-title'>".$charttitulo."</div>";
	$ini_pantalla .= "</div>";
	$ini_pantalla .= "<div class='panel-body'>";

	$ini_pantalla .= "<div id=".$id."></div>";
	
	$ini_pantalla .= "</div>";
	$ini_pantalla .= "</div>";

	return $ini_pantalla;
}

?>