/* Author:

*/

$(document).ready(function(){

	//Home Page slider
    $('#slider1').bxSlider({'auto': true, 'pager': false});

    //services buttons on home page

    $('.homeService').on('click',function() {
    	document.location='./services#' + this.id;
    });

    //Services page option service loader
    var loadContent = function(option) {
    	if(option == 'PaintWork') {
    		$('#servicesContent').html('<h1>Paint Work</h1><div>Scuffs and Scratches are vehicle bodyshop specialists and paint refurbishment specialists. From stone chips to re-sprays. Painted work carried out on a mobile basis or at our bodyshops. Using the highest paint technology Scuffs and Scratches are one of the first low emission bodyshops and mobile smart repair companies in the UK. We use low bake oven facilities and water base paint systems meaning your car paint work is finished to the highest standard. Vehicles we work on include classic cars to present day cars, small vans to light commercials.</div>');
    	} else if(option == 'WheelRepair') {
    		$('#servicesContent').html('<h1>Alloy Wheel Repair</h1><div>Scuffs and Scratches can refurbish all types of alloy wheels in any colour and any finish ie matte to high gloss finish. Tyre replacement can be provided along with balancing and tracking. From a wheel scuff to total refurbishment. For all all your alloy wheel repair requirements please do not hesitate to contact us. Alloy wheel refurbishment carried at your home or workplace carried out a mobile basis or at our bodyshops.</div>');
    	} else if(option == 'ScratchRemoval') {
    		$('#servicesContent').html('<h1>Scratch Removal</h1><div>Scuffs and Scratches specialises in scratch removal. There is a number of processes we use to make your car cosmetically perfect. These include flat and machine polishing, individual panels and localised paint work if required. You would be suprised to know we are very cost effective, usually cheaper than your car insurance excess.</div>');
    	} else if(option == 'DentRepair') {
    		$('#servicesContent').html('<h1>Dent Repair</h1><div>Scuffs and scratches provide a service in dent removal, commonly known as PDR in the smart repair industry. This means removing dents from panels without painting. Dent removal can be done on a mobile basis as well as our bodyshop.</div>');
    	} else if(option == 'FleetServicing') {
    		$('#servicesContent').html('<h1>Fleet Servicing</h1><div>Scuffs and Scratches can provide fleet servicing for the whole of the UK covering all fleet vehicle from cars to light commercials, repairing bodywork, smart repair on a mobile basis also mechanical repairs, servicing, tyres, MOT’s and diagnostic faults. Recovery and break down service collection and delivery available to fleet and trade customers.</div>');
    	} else if(option == 'MOTandServicing') {
    		$('#servicesContent').html('<h1>MOT and Servicing</h1><div>R &amp; T Harrison Ltd is based in Staplehurst and is the brother company to well known car bodyshop Scuffs and Scratches. They provide all car and light commercial MOTs, car services, vehicle repairs, body work, accident repairs and clutch and brake repairs and although R & T Harrison has a large and loyal client base all insurance work is welcome as well as fleet and trade work . Click Here to Visit website</div>');
    	} else {
    	}

    }

    $('.serviceOption').ready(function() {
    	if(window.location.hash) {
    		var option = window.location.hash.substring(1); 
    		loadContent(option);
    	} else {
    		loadContent('PaintWork')
    	}
    });

    $('.serviceOption').on('click', function() {
    	var option = this.id;
    	document.location = '#' + option;
    	loadContent(option);
    });


    //contact form validation

    $('#contactGo').bind('click', function() {
        var errors = [];
        var result = true;
        var name = $('#cName').val();
        var email = $('#cEmail').val();
        var message = $('#cMessage').val();
        if(name == '') {
            errors.push('Please enter your name');
            result = false;
        }

        if(email == '') {
            errors.push('Please enter your email address');
            result = false;
        } else {
            var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!emailRegEx.test(email)) {
                result = false;
                errors.push("Please enter a valid email address");
            }
        }
        if(message == '') {
            errors.push("Please enter a message");
            result = false;
        }

        $('#resultList').html('');
        $('#resultBox').hide();
        if(result) {
            $('#block').block({'overlayCSS': {
                    'background-color': 'white'
                },
                'css': {
                    'border': 'none',
                    'background-color': 'black',
                    'border-radius': '5px',
                    'color': 'white',
                    'opacity': '0.8',
                    'padding': '10px' 
                },
                'message': "<h1>Sending Message...</h1>"
            });
            $.post("contact.php", {"ajax": true, "name": name, "email": email, "message": message },
                function(data) {
                    $('#resultBox').show();
                    var valid = data.result;
                    if(valid) {
                        $('#resultBox').addClass('succ');
                        $('#resultBox').removeClass('fail');
                        $('#resultList').append('<li><h3>Success</h3></li><li>Your message has been sent</li>');
                    } else {
                        $('#resultBox').addClass('fail');
                        $('#resultBox').removeClass('succ');
                        $('#resultList').append('<li><h3>Error with submission</h3></li>')
                        var ajaxErrors = data.errors;
                        for(i in ajaxErrors) {
                            $('#resultList').append('<li>' + ajaxErrors[i] + '</li>');
                        }
                    }
                    $('#block').unblock();
                }, 'json');

        } else {
            $('#resultBox').show();
            $('#resultBox').addClass('fail');
            $('#resultBox').removeClass('succ');
            $('#resultList').append('<li><h3>Error with submission</h3></li>')
        }
        for(i in errors) {
            $('#resultList').append('<li>' + errors[i] + '</li>');
        }
        return false;
    });

 });

// AUTOLOAD CODE BLOCK (MAY BE CHANGED OR REMOVED)
if (!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
    jQuery(function($) {
        $("a[rel^='lightbox']").slimbox({/* Put custom options here */}, null, function(el) {
            return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
        });
    });
}