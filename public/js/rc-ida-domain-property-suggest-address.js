document.addEventListener('DOMContentLoaded', function () {
    let debounceTimeout;

    var results = document.querySelector('.rc-ida-results');
    var containerGFAddress = document.querySelector('.rc-ida-address .ginput_container_text');
    var inputGFAddress = document.querySelector('.rc-ida-address input');
    var inputGFState = document.querySelector('.rc-ida-state input');
    var inputGFSuburb = document.querySelector('.rc-ida-suburb input');
    var inputGFPostcode = document.querySelector('.rc-ida-postcode input');
    var inputGFPropertyID = document.querySelector('.rc-ida-property-id input');

    if (!results) {
        containerGFAddress.classList.add('position-relative');
        inputGFAddress.insertAdjacentHTML('afterend', '<ul id="rc-ida-results" class="rc-ida-results card shadow gform-theme__disable-reset position-absolute start-0 top-100 w-100 small list-unstyled px-2 z-2 d-none"></ul>');
        results = document.querySelector('.rc-ida-results');
    }
    if (inputGFAddress) {
        inputGFAddress.id = 'rc-ida-address';
    }
    if (inputGFState) {
        inputGFState.id = 'rc-ida-state';
    }
    if (inputGFSuburb) {
        inputGFSuburb.id = 'rc-ida-suburb';
    }
    if (inputGFPostcode) {
        inputGFPostcode.id = 'rc-ida-postcode';
    }
    if (inputGFPropertyID) {
        inputGFPropertyID.id = 'rc-ida-property-id';
    }

    const inputAddress = document.getElementById('rc-ida-address');
    const inputState = document.getElementById('rc-ida-state');
    const inputSuburb = document.getElementById('rc-ida-suburb');
    const inputPostcode = document.getElementById('rc-ida-postcode');
    const inputPropertyID = document.getElementById('rc-ida-property-id');
    const suggestions = document.getElementById('rc-ida-results');
    const submit = document.getElementById('rc-ida-submit');
    const next_1st = document.querySelector('.rc-ida-page-1st .gform_next_button');
    const mapElement = document.getElementById('rc-ida-google-map');

    // Fetch property suggestions
    if (inputAddress) {
        inputAddress.addEventListener('keyup', function () {
            if (submit) {
                submit.setAttribute('disabled', 'disabled');
            }
            if (next_1st) {
                next_1st.setAttribute('disabled', 'disabled');
            }

            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                const query = inputAddress.value;

                if (query.length < 1) {
                    suggestions.innerHTML = '';
                    suggestions.classList.add('d-none');
                    return;
                }

                fetch(autocomplete_params.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        action: 'rc_ida_domain_fetch_property_suggest',
                        nonce: autocomplete_params.nonce,
                        location: query
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        suggestions.innerHTML = '';

                        if (data.success) {
                            suggestions.classList.remove('d-none');

                            data.data.forEach(item => {
                                const li = document.createElement('li');
                                li.textContent = item['address'];
                                li.setAttribute('data-id', item['id']);
                                li.setAttribute('data-state', item['addressComponents']['state']);
                                li.setAttribute('data-suburb', item['addressComponents']['suburb']);
                                li.setAttribute('data-postcode', item['addressComponents']['postCode']);
                                li.classList.add('px-1', 'py-2', 'border-bottom');
                                suggestions.appendChild(li);

                                li.addEventListener('click', function () {
                                    inputAddress.value = li.textContent;
                                    inputState.value = li.getAttribute('data-state');
                                    inputSuburb.value = li.getAttribute('data-suburb');
                                    inputPostcode.value = li.getAttribute('data-postcode');
                                    inputPropertyID.value = li.getAttribute('data-id');

                                    suggestions.innerHTML = '';
                                    suggestions.classList.add('d-none');

                                    if (submit) {
                                        submit.removeAttribute('disabled');
                                    }
                                    if (next_1st) {
                                        next_1st.removeAttribute('disabled');
                                    }
                                    if (mapElement) {
                                        geocodeAddress();
                                    }
                                });
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }, 300);
        });

        if (inputAddress.value.trim() === '') {
            if (submit) {
                submit.setAttribute('disabled', 'disabled');
            }
            if (next_1st) {
                next_1st.setAttribute('disabled', 'disabled');
            }
        }
    }

    // Google Maps API
    let map;
    let geocoder;
    let markers = [];

    // Initialize the map
    function initMap() {
        // Set default location (optional)
        const defaultLocation = { lat: -34.9406273, lng: 138.6179246 }; // San Francisco
        map = new google.maps.Map(document.getElementById('rc-ida-google-map'), {
            center: defaultLocation,
            zoom: 18
        });
        geocoder = new google.maps.Geocoder();
    }

    // Geocode the input address and render the map
    function geocodeAddress() {
        const inputAddress = document.getElementById('rc-ida-address').value;

        // Geocode the address input
        geocoder.geocode({ 'address': inputAddress }, function (results, status) {
            if (status === 'OK') {
                // Set the map center to the geocoded location
                map.setCenter(results[0].geometry.location);

                // Remove existing markers
                markers.forEach(marker => marker.setMap(null));
                markers = [];

                // Add a new marker at the geocoded location
                const marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });

                // Add the new marker to the markers array
                markers.push(marker);
            }
        });
    }

    // Ensure initMap is called when the page loads
    window.onload = function () {
        if (mapElement) {
            initMap();
            geocodeAddress();

            // Add event listeners to the input field
            const inputAddress = document.getElementById('rc-ida-address');
            inputAddress.addEventListener('input', geocodeAddress);
            inputAddress.addEventListener('change', geocodeAddress);
            inputAddress.addEventListener('keyup', geocodeAddress);
            inputAddress.addEventListener('click', geocodeAddress);
        }
    };
});
