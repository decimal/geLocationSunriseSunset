<!doctype html>
<html>
<body>
	<a id="button-get-geo" href="#">Get location</a>
	<br />
	<div class="div-output1" id="div-output1"></div>
    <div class="div-output2" id="div-output2"></div>
	<div class="div-output3" id="div-output3"></div>
	<script>

	//https://github.com/mourner/suncalc

	var button = document.getElementById('button-get-geo');
	var latitude;
	var longitude;
	

	button.addEventListener('click', getGeoLocationData);

	function getGeoLocationData() {
		//check if gelocation is supported
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(
				// sucess
				function() {
					//get 
					latitude = arguments[0].coords.latitude;
					longitude = arguments[0].coords.longitude;
                    // if (arguments[0].coords.heading !== null) {
                        heading = arguments[0].coords.heading;
                        document.getElementById("div-output3").innerHTML = JSON.stringify(arguments[0].coords);
                    // } else {
                        // document.getElementById("div-output3").innerHTML = '<hr />no heading';
                    // }
                    console.log(arguments[0].coords.heading)
					date = new Date();
  					unixTime = (Math.floor(new Date().getTime() / 1000).toString());

  					// https://developer.mozilla.org/en-US/docs/Web/API/Geolocation.watchPosition

					gmtOffset = -date.getTimezoneOffset()/60;

                    // store the latitude & longitude as cookies...
                    document.cookie = 'latitude=' + latitude;
                    document.cookie = 'longitude=' + longitude;

					// AJAX load
					loadXMLDoc(latitude, longitude, gmtOffset, unixTime);
			}, 
				// error
				function() {
					console.log('not found');
			});
		}
	
	}

function loadXMLDoc(latitude, longitude, gmtOffset, unixTime) {
    var xmlhttp;
    var myUrl = "get-sunrise-sunset.php?time=" + unixTime + "&latitude=" + latitude + "&longitude=" + longitude + "&gmtOffset=" + gmtOffset;

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function() {
    	// console.log(xmlhttp);
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        	var responseObj = JSON.parse(xmlhttp.responseText);

        	//the response if the failed property exists, something went wrong
        	if (responseObj.failed) {
        		console.log('fail!');
        		return;
        	} else {
        		// convert the UTC timestamps from php to milliseconds for js
        		var readableTimes = [];
        		var percentages = [];
        		for (key in responseObj) {
        			if (responseObj.hasOwnProperty(key)) {
        				responseObj[key] = responseObj[key] * 1000;
        				// generate a readable string version of the times and add to an array
        				utcDate = new Date(responseObj[key]);
        				utcHours = utcDate.getHours();
        				//add leading zero if minute returns less than 10 minutes
        				utcMinutes = (utcDate.getMinutes()<10?'0':'') + utcDate.getMinutes();
        				console.log(utcHours + ':' + utcMinutes);
        				readableTimes.push(utcHours + ':' + utcMinutes);
        				// this converts time into a rough percentage for use in a canvas element
        				percentages.push(Math.round(parseFloat(utcHours +'.'+ utcMinutes)*4.2));

        			}
        		}

        		var textDescriptions = [
        			'Astro Twilight Start',
        			'Nautical Twilight Start',
        			'Civil Twilight Start',
        			'Sunrise',
        			'Sunset',
        			'Civil Twilight End',
        			'Nautical Twilight End',
        			'Astro Twilight End'];
        		
        		var htmlData = [];

        		for (i=0; i<textDescriptions.length; i++) {
        			htmlData.push(textDescriptions[i] + ': ' + readableTimes[i] + '');
        		}

                htmlData = htmlData.join("<br />");

	            document.getElementById("div-output2").innerHTML = htmlData;
        	}

        }
    }

    xmlhttp.open("GET", myUrl, true);
    xmlhttp.send();
}

	</script>
</body>
</html>