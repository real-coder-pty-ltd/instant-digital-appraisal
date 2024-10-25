'use strict';

document.addEventListener('DOMContentLoaded', function () {

	// Google Autocomplete object.
	let autocomplete;

	// Prepare location info object.
	var locationInfo = {
		geo: null,
		country: 'Australia',
		state: null,
		city: null,
		postalCode: null,
		street: null,
		streetNumber: null,
		reset: function () {
			this.geo = null;
			this.country = 'Australia';
			this.state = null;
			this.city = null;
			this.postalCode = null;
			this.street = null;
			this.streetNumber = null;
		}
	};

	const googleAutocomplete = {
		autocompleteField: function (fieldId) {
			autocomplete = new google.maps.places.Autocomplete(document.getElementById(fieldId), { types: ["geocode"] });
			google.maps.event.addListener(autocomplete, "place_changed", function () {

				// Segment results into usable parts.
				var place = autocomplete.getPlace(),
					address = place.address_components,
					lat = place.geometry.location.lat(),
					lng = place.geometry.location.lng();

				// Reset location object.
				locationInfo.reset();

				// Save the individual address components.
				locationInfo.geo = [lat, lng];
				for (var i = 0; i < address.length; i++) {
					var component = address[i].types[0];
					switch (component) {
						case "country":
							locationInfo.country = address[i]["long_name"];
							break;
						case "administrative_area_level_1":
							locationInfo.state = address[i]["long_name"];
							break;
						case "locality":
							locationInfo.city = address[i]["long_name"];
							break;
						case "postal_code":
							locationInfo.postalCode = address[i]["long_name"];
							break;
						case "route":
							locationInfo.street = address[i]["long_name"];
							break;
						case "street_number":
							locationInfo.streetNumber = address[i]["long_name"];
							break;
						default:
							break;
					}
				}

			});
		}
	};

	var containerAddress = document.querySelector('.pricefinder-da-address .ginput_container_text');
	var inputAddress = document.querySelector('.pricefinder-da-address input');
	var result = document.querySelector('#pricefinder-da-result');
	if (!result) {
		containerAddress.classList.add('position-relative');
		inputAddress.insertAdjacentHTML('afterend', '<div id="pricefinder-da-result" class="gform-theme__disable-reset position-absolute start-0 top-100 w-100 small"></div>');
		result = document.querySelector('#pricefinder-da-result');
	}
	if (inputAddress) {
		inputAddress.id = 'pricefinder-da-address';
	}

	var address = [];

	// Attach listener to address input field.
	document.querySelectorAll('.pricefinder-da-appraisal-type').forEach(function (radio) {
		radio.addEventListener("change", function () {
			document.querySelectorAll('.pricefinder-da-appraisal-type').forEach(function (el) {
				el.removeAttribute('checked');
			});
			this.setAttribute('checked', 'checked');
			document.querySelector('.pricefinder-da-address input').value = '';
			resetEventListeners();
			checkEngagementToolValue();
		});
	});

	// Check the selected engagement tool value and set up the appropriate event listeners
	function checkEngagementToolValue() {

		let selectedValue = '';

		if (new URLSearchParams(window.location.search).has('appraisal-type')) {
			selectedValue = new URLSearchParams(window.location.search).get('appraisal-type');
		} else {
			selectedValue = document.querySelector('.pricefinder-da-appraisal-type:checked')?.value || 'general';
		}

		result.innerHTML = '';

		if (selectedValue !== "buy" && selectedValue !== "rental") {
			// Add event listeners to the input element for Google Autocomplete
			googleAutocomplete.autocompleteField("pricefinder-da-address");
		} else {
			// Add keyup event listener for custom autocomplete
			document.querySelector('.pricefinder-da-address input').addEventListener('keyup', function () {
				if (selectedValue == "buy") {
					address = addresses.buy;
				} else if (selectedValue == "rental") {
					address = addresses.rental;
				}
				getValue(this.value);
			});
		}
	}

	// Reset event listeners on the address input field
	function resetEventListeners() {
		const inputElement = document.querySelector('.pricefinder-da-address input');
		const clonedElement = inputElement.cloneNode(true);
		inputElement.parentNode.replaceChild(clonedElement, inputElement);
	}

	// Custom autocomplete function
	function autoComplete(address, Input) {

		// Ensure address is defined and is an array
		if (Array.isArray(address)) {
			return address.filter(function (e) {
				return e.toLowerCase().includes(Input.toLowerCase());
			});
		} else {
			return [];
		}
	}

	// Get and display the filtered address values
	function getValue(val) {

		// If no value, clear the result
		if (!val) {
			result.innerHTML = '';
			return;
		}

		// Search and filter the array
		var data = autoComplete(address, val);

		// Append list data
		var res = '<ul class="list-group">';
		data.forEach(function (e) {
			res += '<li class="list-group-item">' + e + '</li>';
		});
		res += '</ul>';
		result.innerHTML = res;

		// Add click event listener to each list item
		document.querySelectorAll('#pricefinder-da-result .list-group-item').forEach(function (item) {
			item.addEventListener('click', function () {
				document.querySelector('.pricefinder-da-address input').value = this.textContent;
				result.innerHTML = ''; // Clear the suggestions list

				// Trigger the change event on the input field
				document.querySelector('.pricefinder-da-address input').click();
			});
		});
	}

	// Initial check of the engagement tool value
	if (document.querySelector('.pricefinder-da-address input')) {
		checkEngagementToolValue();
	}

	// Google Maps API
	let map;
	let geocoder;

	// Initialize the map
	function initMap() {
		// Set default location (optional)
		const defaultLocation = { lat: -34.9406273, lng: 138.6179246 }; // San Francisco
		map = new google.maps.Map(document.getElementById('pfda-appraisal-map'), {
			center: defaultLocation,
			zoom: 18
		});
		geocoder = new google.maps.Geocoder();
	}

	// Geocode the input address and render the 
	function geocodeAddress() {
		map
		const address = document.querySelector('.pricefinder-da-address input').value;

		// Geocode the address input
		geocoder.geocode({ 'address': address }, function (results, status) {
			if (status === 'OK') {
				// Set the map center to the geocoded location
				map.setCenter(results[0].geometry.location);

				// Add a marker at the geocoded location
				const marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
			}
		});
	}

	// Ensure initMap is called when the page loads
	window.onload = function () {
		const mapElement = document.getElementById('pfda-appraisal-map');
		if (mapElement) {
			initMap();
			geocodeAddress();

			// Add event listeners to the input field
			const inputField = document.querySelector('.pricefinder-da-address input');
			inputField.addEventListener('input', geocodeAddress);
			inputField.addEventListener('change', geocodeAddress);
			inputField.addEventListener('keyup', geocodeAddress);
			inputField.addEventListener('click', geocodeAddress);

			// Get the value of the 'appraisal-type' parameter
			const appraisalType = new URLSearchParams(window.location.search).get('appraisal-type');

			if (appraisalType !== 'buy' && appraisalType !== 'rental') {
				autocomplete = new google.maps.places.Autocomplete(inputField);
				autocomplete.addListener('place_changed', geocodeAddress);
			}

		}
	};
});