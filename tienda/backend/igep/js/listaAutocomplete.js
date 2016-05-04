  (function( $ ) {
    $.widget( "custom.combobox", {
        options: {
        	 estado: 'disabled'
        },
      
      _create: function() {
        this.wrapper = $( "<span>" )
          /*.addClass( "custom-combobox" )*/
          .addClass("text")
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
    		.appendTo( this.wrapper )
	    	.val( value )
	    	.attr( "title", "" )
	    	.addClass( "text ")
	    			//ui-widget ui-widget-content ui-state-default ui-corner-left" )
	    	.autocomplete({
	    		delay: 0,
	    		minLength: 0,
	    		source: $.proxy( this, "_source" )
	    	})
	    	.tooltip({
	    		tooltipClass: "ui-state-highlight"
	    	});
        
        this._on( this.input, {
        	autocompleteselect: function( event, ui ) {
        		ui.item.option.selected = true;
        		this._trigger( "select", event, {
        			item: ui.item.option
        		});
        	},
        	autocompletechange: "_removeIfInvalid"
        		
        });

      },
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        this.a =  $( "<a>" )
          .attr( "tabIndex", -1 )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "flecha-down"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },

      gvhPanel: function(panel)
      {
    	  this.input.attr('data-gvhPanel', panel);
    	  this.a.attr('data-gvhPanel', panel);
      },
      
      id: function(id)
      {
    	  this.input.attr('id', id);
    	  return id
      },
      
      idDown: function(id)
      {
    	  this.a.attr('id', id);
    	  return id
      },

      estado: function(estado)
      {
    	  if (estado == 'disabled')
    	  {
    		  this.input.attr('disabled', 'disabled');
    		  this.a.button('disable');
    	  }
    	  else
    	  {
    		  this.input.removeAttr('disabled');
    		  this.a.button('enable');
    	  }
    	  return estado
      },
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " no se ha encontrado" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
  