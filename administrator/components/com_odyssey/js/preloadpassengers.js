
(function($) {
  //Run a function when the page is fully loaded including graphics.
  $(window).load(function() {

    //Bind a function to each preload passenger drop down list.
    $('select[name^="preloadpsgr_"]').change( function() { $.fn.loadPassenger($(this)); });
  });


  $.fn.loadPassenger = function(select) {
    //Collect some needed data.
    var psgrId = select.val();
    var psgrNb = select.prop('name').substring(12);
    var attributes = odyssey.getPassengerAttributes();
    var passengers = odyssey.getPassengers();

    //If the "Select passenger" option is chosen the passenger fields must be emptied.
    if(psgrId == '') {
      for(var i = 0; i < attributes.length; i++) {
        $('[name="'+attributes[i]+'_'+psgrNb+'"]').attr('value', '');
	$('[name="'+attributes[j]+'_'+psgrNb+'"]').focus();
      }
    }
    else {
      for(var i = 0; i < passengers.length; i++) {
	if(passengers[i].id == psgrId) {
	  //Fill in the fields with the passenger data.
	  for(var j = 0; j < attributes.length; j++) {
	    //Important: Set the input value with attr() method instead of val() or the
	    //date and datetime value won't be properly set. We must also give the focus
	    //to the field or the value won't be submited. Very weird problem !
	    $('[name="'+attributes[j]+'_'+psgrNb+'"]').attr('value', passengers[i][attributes[j]]);
	    $('[name="'+attributes[j]+'_'+psgrNb+'"]').focus();
	  }
	}
      }
    }

    //$('#country_code_'+psgrNb+'_chzn').trigger('liszt:updated');
    //$('#country_code_'+psgrNb).chosen();
  };

})(jQuery);
