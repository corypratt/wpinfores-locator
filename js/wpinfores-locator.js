jQuery(document).ready(function($){
	// This runs to populate the select box with returned groups that have been provided to IRI
	$.ajax({
		type:"GET",
		url: wpinfores_group_info.url,
		dataType: 'xml',
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
		//	console.log("ERROR:" + xhr.responseText+" - "+thrownError);
		}
	});	
});

function brandLocator() {

    var zip = $('#zip').val();
  	var reg = /^[0-9]+$/;
  	var validZip = 'FALSE';
  	var brandChosen = 'FALSE';

  	// Lets check the zipcode for some basic issues
  	if (zip == ''){
  		errorMessage = "*Zipcode required!";
  		console.log(errorMessage);
  	}
	else if ((zip.length)< 5 || (zip.length)>5 ){
		errorMessage = "*zipcode should only be 5 digits";
	}
	else if (!reg.test(zip)){
		errorMessage = "*zipcode should be numbers only";
	} else {
		validZip = 'TRUE';
	}

	// If we've chosen a brand and the zipcode is valid, go ahead and run the search
	if ( $('#productList').val() != 'Please Choose a Brand' && validZip === 'TRUE') {
		$(".shop-results > .loader").show();
	    $.ajax({
			url: '../wp-content/plugins/wpinfores-locator/inc/wpinfores-findstores.php',
			type:'GET',
			dataType: 'json',
			data: {
			// Get the form values and send off to get the stores
				'productid' 	: 	$('#productList').val(),
				'zip'			: 	$('#zip').val(),
				'searchradius'	: 	$('#searchRadius').val()
			},
			success: function(data)
			{
				// Let's show the loading spinner while the scripts are doing their thing.
				$(".shop-results > .loader").hide();

				//Checking if any stores were returned.  If not, display a message, otherwise lets see the results!
				 if (data.STORES["@attributes"].COUNT == undefined || data.STORES["@attributes"].COUNT == null || data.STORES["@attributes"].COUNT == 0){
				 	$(".shop-results").append (
				 		  '<div class="error">'
				 		+ 'I\'m Sorry, there aren\'t any stores nearby.  Try increasing the search radius.'  
				 		+ '</div>'
			 		)
				 } else {
			 	// Looping through the returned stores and placing them into a UL
			     $.each(data.STORES.STORE, function(i, item) {
					$(".shop-results").append(
						'<li><span class="store">'
						+ '<span id="pint0-name" style="font-size:1.7rem;"><b>'
						+ data.STORES.STORE[i].NAME
						+ '</b></span> '
						+ '<span class="distance" id="distance">('
						+ data.STORES.STORE[i].DISTANCE
						+ ' miles)</span></span>'
						+ '<span><br/>'
						+ data.STORES.STORE[i].ADDRESS
						+ '</span>'
						+ '<br /> '
						+ '<span>'
						+ data.STORES.STORE[i].CITY + ', ' + data.STORES.STORE[i].STATE + ' ' + data.STORES.STORE[i].ZIP
						+ '</span>'
						+ '<br /> '
						+ '<span class="tel" id="telephone">'
						+ data.STORES.STORE[i].PHONE
						+ '</span> <a id="link" target="_blank" href="http://maps.google.com/?q='
						+ data.STORES.STORE[i].LATITUDE
						+ ','
						+ data.STORES.STORE[i].LONGITUDE
						+ '" class="cta" itemprop="map" onclick="javascript:addUDMEvent(\'Click\',\'Pint Locator\',\'Map It\');">Map It&nbsp;<i class="ss-icon ss-navigateright"></i></a>'
						+ '</li>'
					);
			 	});
			 }
			},
			error: function (xhr, ajaxOptions, thrownError) {alert("ERROR:" + xhr.responseText+" - "+thrownError);
			}
	    });
	}
}

