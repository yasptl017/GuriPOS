<div class="position-relative">

    {{$slot}}

    <input type="hidden" name="distance" id="distance">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#000000" viewBox="0 0 256 256"
         id="loading-spinner" style="" class="animate-spin loading-spinner">
        <path
            d="M136,32V64a8,8,0,0,1-16,0V32a8,8,0,0,1,16,0Zm88,88H192a8,8,0,0,0,0,16h32a8,8,0,0,0,0-16Zm-45.09,47.6a8,8,0,0,0-11.31,11.31l22.62,22.63a8,8,0,0,0,11.32-11.32ZM128,184a8,8,0,0,0-8,8v32a8,8,0,0,0,16,0V192A8,8,0,0,0,128,184ZM77.09,167.6,54.46,190.22a8,8,0,0,0,11.32,11.32L88.4,178.91A8,8,0,0,0,77.09,167.6ZM72,128a8,8,0,0,0-8-8H32a8,8,0,0,0,0,16H64A8,8,0,0,0,72,128ZM65.78,54.46A8,8,0,0,0,54.46,65.78L77.09,88.4A8,8,0,0,0,88.4,77.09Z"></path>
    </svg>
</div>


<style>
    .animate-spin {
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
    }

    .loading-spinner {
        visibility: hidden;
        position: absolute;
        right: 20px;
        bottom: 50%;
    }

    .pac-container {
        z-index: 10000 !important;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }


</style>
<script>
    const event = new Event('distance-loaded');

    /**
     * @license
     * Copyright 2024 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    async function initAutocomplete() {

        document.getElementById("loading-spinner").style.visibility = "visible";

        const addressInput = document.getElementById("address-input");
        const autocomplete = new google.maps.places.Autocomplete(addressInput, {
            fields: ['address_components', 'formatted_address', 'name']
        });

        document.getElementById("loading-spinner").style.visibility = "hidden";

        autocomplete.addListener('place_changed', async () => {
            document.getElementById("loading-spinner").style.visibility = "visible";
            const place = autocomplete.getPlace();
            const locality = getLocalityFromAddressComponents(place.address_components);

            if (locality) {
                try {
                    const distanceResponse = await calculateDistance(place.formatted_address);
                    const distanceResult = distanceResponse.rows[0].elements[0];
                    // Do something with the distance result
                    document.getElementById("distance").value = distanceResult.distance.value;
                    //     emit a js event to notify the parent component
                    document.dispatchEvent(event);

                } catch (error) {
                    console.error("Error calculating distance:", error);
                }
            } else {
                console.log("No locality available for this place.");
            }
            document.getElementById("loading-spinner").style.visibility = "hidden";
        });
    }

    function getLocalityFromAddressComponents(addressComponents) {
        for (const component of addressComponents) {
            if (component.types.includes('locality')) {
                return component.long_name;
            }
        }
        return '';
    }

    async function calculateDistance(destination) {
        const service = new google.maps.DistanceMatrixService();
        const origin = "419 High Street, Penrith, 2750";

        return new Promise((resolve, reject) => {
            service.getDistanceMatrix(
                {
                    origins: [origin],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === google.maps.DistanceMatrixStatus.OK) {
                        resolve(response);
                    } else {
                        reject(`Distance Matrix request failed due to ${status}`);
                    }
                }
            );
        });
    }
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<!--
<script
   
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2EqH1cqg0L0yTJ86hiGsr_ZAfEl1khss&libraries=places&callback=initAutocomplete"
    async defer></script>

-->
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRuhq0XqrM5Z3DUUSxU3S6wIoE6DFLxpw&libraries=places&callback=initAutocomplete"
    async defer></script>
