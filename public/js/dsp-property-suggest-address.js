document.addEventListener("DOMContentLoaded", function() {
  let debounceTimeout;

  var results = document.querySelector(".dsp-results");
  var containerGFAddress = document.querySelector(
    ".dsp-address .ginput_container_text"
  );
  var inputGFAddress = document.querySelector(".dsp-address input");
  var inputGFState = document.querySelector(".dsp-state input");
  var inputGFSuburb = document.querySelector(".dsp-suburb input");
  var inputGFPostcode = document.querySelector(".dsp-postcode input");
  var inputGFPropertyID = document.querySelector(".dsp-property-id input");

  if (!results) {
    containerGFAddress.classList.add("position-relative");
    inputGFAddress.insertAdjacentHTML(
      "afterend",
      '<ul id="dsp-results" class="dsp-results card shadow gform-theme__disable-reset position-absolute start-0 top-100 w-100 list-unstyled px-2 z-2 d-none"></ul>'
    );
    results = document.querySelector(".dsp-results");
  }
  if (inputGFAddress) {
    inputGFAddress.id = "dsp-address";
  }
  if (inputGFState) {
    inputGFState.id = "dsp-state";
  }
  if (inputGFSuburb) {
    inputGFSuburb.id = "dsp-suburb";
  }
  if (inputGFPostcode) {
    inputGFPostcode.id = "dsp-postcode";
  }
  if (inputGFPropertyID) {
    inputGFPropertyID.id = "dsp-property-id";
  }

  const inputAddress = document.getElementById("dsp-address");
  const inputState = document.getElementById("dsp-state");
  const inputSuburb = document.getElementById("dsp-suburb");
  const inputPostcode = document.getElementById("dsp-postcode");
  const inputPropertyID = document.getElementById("dsp-property-id");
  const suggestions = document.getElementById("dsp-results");
  const submit = document.getElementById("dsp-submit");
  const next_1st = document.querySelector(".dsp-page-1st .gform_next_button");
  const mapElement = document.getElementById("dsp-google-map");

  // Fetch property suggestions
  if (inputAddress) {
    inputAddress.addEventListener("keyup", function() {
      if (submit) {
        submit.setAttribute("disabled", "disabled");
      }
      if (next_1st) {
        next_1st.setAttribute("disabled", "disabled");
      }

      clearTimeout(debounceTimeout);
      debounceTimeout = setTimeout(() => {
        const query = inputAddress.value;

        if (query.length < 1) {
          suggestions.innerHTML = "";
          suggestions.classList.add("d-none");
          return;
        }

        fetch(autocomplete_params.ajax_url, {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: new URLSearchParams({
            action: "dsp_domain_fetch_property_suggest",
            nonce: autocomplete_params.nonce,
            location: query
          })
        })
          .then(response => response.json())
          .then(data => {
            suggestions.innerHTML = "";

            if (data.success) {
              suggestions.classList.remove("d-none");

              data.data.forEach(item => {
                const li = document.createElement("li");
                li.textContent = item["address"];
                li.setAttribute("data-id", item["id"]);
                li.setAttribute(
                  "data-state",
                  item["addressComponents"]["state"]
                );
                li.setAttribute(
                  "data-suburb",
                  item["addressComponents"]["suburb"]
                );
                li.setAttribute(
                  "data-postcode",
                  item["addressComponents"]["postCode"]
                );
                li.classList.add("px-1", "py-2", "border-bottom");
                suggestions.appendChild(li);

                li.addEventListener("click", function() {
                  inputAddress.value = li.textContent;
                  inputState.value = li.getAttribute("data-state");
                  inputSuburb.value = li.getAttribute("data-suburb");
                  inputPostcode.value = li.getAttribute("data-postcode");
                  inputPropertyID.value = li.getAttribute("data-id");

                  suggestions.innerHTML = "";
                  suggestions.classList.add("d-none");

                  if (submit) {
                    submit.removeAttribute("disabled");
                  }
                  if (next_1st) {
                    next_1st.removeAttribute("disabled");
                  }
                  if (mapElement) {
                    geocodeAddress();
                  }
                });
              });
            }
          })
          .catch(error => {
            console.error("Error:", error);
          });
      }, 300);
    });

    if (inputAddress.value.trim() === "") {
      if (submit) {
        submit.setAttribute("disabled", "disabled");
      }
      if (next_1st) {
        next_1st.setAttribute("disabled", "disabled");
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
    map = new google.maps.Map(document.getElementById("dsp-google-map"), {
      center: defaultLocation,
      zoom: 18
    });
    geocoder = new google.maps.Geocoder();
  }

  // Geocode the input address and render the map
  function geocodeAddress() {
    const inputAddress = document.getElementById("dsp-address").value;

    // Geocode the address input
    geocoder.geocode({ address: inputAddress }, function(results, status) {
      if (status === "OK") {
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
  window.onload = function() {
    if (mapElement) {
      initMap();
      geocodeAddress();

      // Add event listeners to the input field
      const inputAddress = document.getElementById("dsp-address");
      inputAddress.addEventListener("input", geocodeAddress);
      inputAddress.addEventListener("change", geocodeAddress);
      inputAddress.addEventListener("keyup", geocodeAddress);
      inputAddress.addEventListener("click", geocodeAddress);
    }
  };
});
