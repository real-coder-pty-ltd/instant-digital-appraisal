document.addEventListener('DOMContentLoaded', function () {
    const inputAddress = document.getElementById('rc-ida-address');
    const inputState = document.getElementById('rc-ida-state');
    const inputSuburb = document.getElementById('rc-ida-suburb');
    const inputPostcode = document.getElementById('rc-ida-postcode');
    const submit = document.getElementById('rc-ida-submit');

    // Fetch postcodes from Google Maps API
    async function fetchPostcode(suburb, state) {
        return new Promise((resolve, reject) => {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                address: `${suburb}, ${state}`,
                componentRestrictions: { country: 'AU' }
            }, (results, status) => {
                if (status === 'OK') {
                    console.log(`Geocoding results for ${suburb}, ${state}:`, results);
                    const postcodes = results.map(result => {
                        const addressComponents = result.address_components;
                        const stateComponent = addressComponents.find(component => component.types.includes('administrative_area_level_1'));
                        const postcodeComponent = addressComponents.find(component => component.types.includes('postal_code'));

                        if (stateComponent && stateComponent.short_name === state) {
                            console.log(`Postcode component for ${suburb}, ${state}:`, postcodeComponent);
                            return postcodeComponent ? postcodeComponent.short_name : '';
                        }
                        return ''; // Return empty string if state does not match
                    }).filter(postcode => postcode); // Filter out empty postcodes

                    resolve(postcodes);
                } else {
                    console.error(`Failed to fetch data from Google Maps API for ${suburb}, ${state}:`, status);
                    reject(`Failed to fetch data from Google Maps API for ${suburb}, ${state}: ${status}`);
                }
            });
        });
    }

    const states = ['SA'];
    const suggestions = document.getElementById('rc-ida-results');

    // List of suburbs to fetch postcodes
    async function fetchAndDisplayPostcodes() {
        for (const suburb of suburbs) {
            for (const state of states) {
                try {
                    const postcodes = await fetchPostcode(suburb, state);
                    console.log(`Results for ${suburb}, ${state}:`, postcodes);

                    if (postcodes.length > 0) {
                        postcodes.forEach(postcode => {
                            const formattedText = `${suburb}, ${state} ${postcode}`;
                            const newItem = document.createElement('li'); // Create a new li element
                            newItem.classList.add('px-1', 'py-2', 'border-bottom');
                            newItem.textContent = formattedText; // Set the text content of the new item
                            newItem.dataset.suburb = suburb;
                            newItem.dataset.state = state;
                            newItem.dataset.postcode = postcode;
                            suggestions.appendChild(newItem); // Append the new item to the suggestions list
                        });
                    } else {
                        console.log(`No results found for ${suburb}, ${state}`);
                    }
                } catch (error) {
                    console.error(`Error fetching data for ${suburb}, ${state}:`, error);
                }
            }
        }
    }

    fetchAndDisplayPostcodes();

    // Show suggestions on inputAddress focus and hide on blur
    if (inputAddress) {
        inputAddress.addEventListener('focus', function () {
            suggestions.classList.remove('d-none');
        });

        // Filter suggestions based on keyword typed
        inputAddress.addEventListener('keyup', function () {
            suggestions.classList.remove('d-none');
            const query = inputAddress.value;

            // Disable the submit button
            if (submit) {
                submit.setAttribute('disabled', 'disabled');
            }

            // Filter the list items based on the query
            const items = suggestions.querySelectorAll('li');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                const lowerCaseQuery = query.toLowerCase();

                if (text.includes(lowerCaseQuery)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            // If the input is empty, hide the suggestions
            if (query.length < 1) {
                suggestions.classList.add('d-none');
            } else {
                suggestions.classList.remove('d-none');
            }
        });

        // Add click event listener to each suggestion list item
        suggestions.addEventListener('click', function (event) {
            if (event.target.tagName === 'LI') {
                console.log('test');

                inputAddress.value = event.target.textContent;
                inputState.value = event.target.dataset.state;
                inputSuburb.value = event.target.dataset.suburb;
                inputPostcode.value = event.target.dataset.postcode;
                suggestions.classList.add('d-none');

                // Enable the submit button
                if (submit) {
                    submit.removeAttribute('disabled');
                }
            }
        });
    }

});
