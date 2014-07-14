jQuery(document).ready(function($){
	// This runs to populate the select box with returned groups that have been provided to IRI
	$.ajax({
		type:"GET",
		url: '/wp-content/plugins/wpinfores-locator/inc/wpinfores-groupcache.php',
		dataType: 'xml',
		contentType: 'application/xml;',
		success: function(xml) {
			// Do The Populating
			var select = $('#productList');
			$(xml).find('group').each(function() {
				var value = $(this).find('group_id').text();
				var item = $(this).find('group_name').text();
				select.append("<option class='' value='"+value+"'>"+item+"&reg; Brand</option>");
			});
			select.children(":first").text("Please Choose a Brand").attr("selected",true);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.log("ERROR:" + xhr.responseText+" - "+thrownError);
		}
	});	
});

function brandLocator() {

    var zip = jQuery('#zip').val();
  	var reg = /^[0-9]+$/;
  	var validZip = 'FALSE';
  	var brandChosen = 'FALSE';


  	jQuery(".error").hide();
  	jQuery(".error").html("");

  	// Lets check the zipcode for some basic issues
  	if (zip == ''){
  		errorMessage = '*Zipcode required!';
  		jQuery(".error").show();
  		jQuery(".error").append(
  			'*Zipcode required!'
		);
  	}
	else if ((zip.length)< 5 || (zip.length)>5 ){
		errorMessage = "*zipcode should only be 5 digits";
		jQuery(".error").show();
  		jQuery(".error").append(
  			'*zipcode should only be 5 digits'
		);
	}
	else if (!reg.test(zip)){
		errorMessage = "*zipcode should be numbers only";
		jQuery(".error").show();
  		jQuery(".error").append(
  			'*zipcode should be numbers only'
		);
	} else {
		validZip = 'TRUE';
	}

	// If we've chosen a brand and the zipcode is valid, go ahead and run the search
	if ( jQuery('#productList').val() != 'Please Choose a Brand' && validZip === 'TRUE') {
		jQuery(".shop-results").html("");
		jQuery(".inforsearch-loader").show();

	    jQuery.ajax({
			url: '../wp-content/plugins/wpinfores-locator/inc/wpinfores-findstores.php',
			type:'GET',
			dataType: 'json',
			data: {
			// Get the form values and send off to get the stores
				'productid' 	: 	jQuery('#productList').val(),
				'zip'			: 	jQuery('#zip').val(),
				'searchradius'	: 	jQuery('#searchRadius').val()
			},
			success: function(data)
			{
				// Let's show the loading spinner while the scripts are doing their thing.
				jQuery(".inforsearch-loader").hide();
				//Checking if any stores were returned.  If not, display a message, otherwise lets see the results!
				 if (data.STORES["@attributes"].COUNT == undefined || data.STORES["@attributes"].COUNT == null || data.STORES["@attributes"].COUNT == 0){
				 	
				 	jQuery(".shop-results").append (
				 		  '<div class="error">'
				 		+ 'I\'m Sorry, there aren\'t any stores nearby.  Try increasing the search radius.'  
				 		+ '</div>'
			 		)
				 } else {
				 	if (!jQuery.isArray(data.STORES.STORE)) data.STORES.STORE = [data.STORES.STORE];
				 	// Looping through the returned stores and placing them into a UL
				    jQuery.each(data.STORES.STORE, function(i, item) {
						jQuery(".shop-results").append(
							'<li><span class="store">'
							+ '<span id="name"><h4>'
							+ data.STORES.STORE[i].NAME
							+ '</h4></span> '
							+ '<span class="distance" id="distance">('
							+ data.STORES.STORE[i].DISTANCE
							+ ' miles)</span></span>'
							+ '<br><span class="address"><br/>'
							+ data.STORES.STORE[i].ADDRESS
							+ '</span>'
							+ '<br /> '
							+ '<span>'
							+ data.STORES.STORE[i].CITY + ', ' + data.STORES.STORE[i].STATE + ' ' + data.STORES.STORE[i].ZIP
							+ '</span>'
							+ '<br /> '
							+ '<span class="tel" id="telephone">'
							+ data.STORES.STORE[i].PHONE
							+ '</span><br><br><a id="link" target="_blank" href="http://maps.google.com/?q='
							+ data.STORES.STORE[i].LATITUDE
							+ ','
							+ data.STORES.STORE[i].LONGITUDE
							+ '" class="cta" itemprop="map" onclick="javascript:addUDMEvent(\'Click\',\'Pint Locator\',\'Map It\');">Map It&nbsp;</a>'
							+ '</li>'
						);
				 	});
			 }
			},
			error: function (xhr, ajaxOptions, thrownError) {alert("ERROR:" + xhr.responseText+" - "+thrownError);
			}
	    });
	} else {
		jQuery(".error").show();
  		jQuery(".error").append(
  			'*Please choose a brand'
		);
	}
}

