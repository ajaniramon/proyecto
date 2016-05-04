/* 
 * Author: senthil
 * plugin: timepicker
 */
(function ( $ ) 
{
	$.fn.timepicki = function(options) 
	{
		var defaults = {
		};
		var settings = $.extend( {}, defaults, options );
		var ele = $(this);
        var ele_hei = ele.outerHeight();
        var ele_lef = ele.position().left;
        var ele_width = ele.outerWidth();
        ele_hei +=10;
        var pos = $(this).offset.left;

        $splitID = $(this).attr('id').split('___');
        $id = $splitID[2]; 
    	$idTime = 'divTime_'+$id;
        if ($("#"+$idTime+"").length)
        {    
            $("#"+$idTime+"").fadeIn();
        }
        else
        {
        	$(ele).wrap("<div id='"+$idTime+"' class='time_pick'></div>");	
            var ele_par = $(this).parents(".time_pick"); // click en el input text, subo al padre que es la capa time_pick
            $("div.time_pick").width(ele_width);
            // Se añaden todas las capas del time picker
            ele_par.append("<div class='timepicker_wrap'>" +
                 		"<div class='arrow_top'></div>" +
                 		"<div class='time'>" +
                 		"	<div class='prev'>-</div>" +
                 		"	<div class='ti_tx'></div>" +
                 		"	<div class='next'>+</div>" +
                 		"</div>" +
                 		"<div class='mins'>" +
                 		"	<div class='prev'>-</div>" +
                 		"	<div class='mi_tx'></div>" +
                 		"	<div class='next'>+</div>" +
                 		"</div>" +
     		"</div>");
            var ele_next = $(this).next(".timepicker_wrap"); // nos situamos en la capa timepicker_wrap
            var ele_next_all_child = ele_next.find("div");
			return this.each( function() {
	            $(document).on( "click",function(event) 
	            {
	            	if(!$(event.target).is(ele_next))
                    {
                        if(!$(event.target).is(ele))
                            {
                                var tim = ele_next.find(".ti_tx").html();
                                var mini = ele_next.find(".mi_tx").text();
                                if(tim.length !=0 && mini.length !=0)
                                {
                                    ele.val(tim+":"+mini);
                                }
                                if(!$(event.target).is(ele_next)&&!$(event.target).is(ele_next_all_child))
                                {
                                   ele_next.fadeOut(); 
                                }
                            }
                            else{
                                set_date();
                                ele_next.fadeIn();  
                            }
                    }
	            });
	            function set_date()
	            {
	                var d = new Date();
	                var ti = d.getHours();
	                var mi = d.getMinutes();
	                if(ti<10)
	                {
	                	ele_next.find(".ti_tx").text("0"+ti);
	                }
	                else{
	                	ele_next.find(".ti_tx").text(ti);
	                }
	                if(mi<10)
	                {
	                	ele_next.find(".mi_tx").text("0"+mi);
	                }
	                else{
	                	ele_next.find(".mi_tx").text(mi);
	                }
	            }
	            var cur_next = ele_next.find(".next");
	            var cur_prev = ele_next.find(".prev");
	            $(cur_prev).add(cur_next).on("click", function () 
	            {
	                var cur_ele = $(this);
	                var cur_cli = null;
	                var ele_st = 0;
	                var ele_en = 0;
	                if (cur_ele.parent().attr("class") == "time") 
	                {
	                    cur_cli = "time";
	                    ele_en = 24;
	                    var cur_time = null;
	                    cur_time = ele_next.find("." + cur_cli + " .ti_tx").text();
	                    cur_time = parseInt(cur_time);
	                    if (cur_ele.attr("class") == "next") 
	                    {
	                        if (cur_time == 24)
	                        {
	                            ele_next.find("." + cur_cli + " .ti_tx").text("01");
	                        } 
	                        else 
	                        {
	                            cur_time++;
	                            if(cur_time<10)
	                            {
	                            	ele_next.find("." + cur_cli + " .ti_tx").text("0"+cur_time);
	                            }
	                            else
	                            {
	                            	ele_next.find("." + cur_cli + " .ti_tx").text(cur_time);
	                            }
	                        }
	                    } 
	                    else {
	                        if (cur_time == 1) 
	                        {
	                            ele_next.find("." + cur_cli + " .ti_tx").text(24);
	                        } 
	                        else {
	                            cur_time--;
	                            if(cur_time<10)
	                            {
	                            	ele_next.find("." + cur_cli + " .ti_tx").text("0"+cur_time);
	                            }
	                            else
	                            {
	                            	ele_next.find("." + cur_cli + " .ti_tx").text(cur_time);
	                            }
	                        }
	                    }
	                }
	                else if (cur_ele.parent().attr("class") == "mins") 
	                {
	                    cur_cli = "mins";
	                    ele_en = 59;
	                    var cur_mins = null;
	                    cur_mins = ele_next.find("." + cur_cli + " .mi_tx").text();
	                    cur_mins = parseInt(cur_mins);
	                    if (cur_ele.attr("class") == "next")
	                    {
	                        if (cur_mins == 59) 
	                        {
	                            ele_next.find("." + cur_cli + " .mi_tx").text("00");
	                        } 
	                        else 
	                        {
	                            cur_mins++;
	                            if(cur_mins<10)
	                            {
	                            	ele_next.find("." + cur_cli + " .mi_tx").text("0"+cur_mins);
	                            }
	                            else{
	                            	ele_next.find("." + cur_cli + " .mi_tx").text(cur_mins);
	                            }
	                        }
	                    } 
	                    else {
	                        if (cur_mins == 0) {
	                        	ele_next.find("." + cur_cli + " .mi_tx").text(59);
	                        }
	                       else {
	                           cur_mins--;
	                           if(cur_mins<10)
	                           {
	                        	   ele_next.find("." + cur_cli + " .mi_tx").text("0"+cur_mins);
	                           }
	                           else{
	                        	   ele_next.find("." + cur_cli + " .mi_tx").text(cur_mins);
	                           }
	                       }
	                   }
	                } 
	            });
	        });
        }
         
    };
}( jQuery ));