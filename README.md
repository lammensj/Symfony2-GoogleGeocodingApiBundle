UmbrellawebGoogleGeocodingApiBundle
========================

Provides interaction with The Google Geocoding API v3 (@see https://developers.google.com/maps/documentation/geocoding/ for more info).

UmbrellawebGoogleGeocodingApiBundle gives the opportunity convert addresses (like "1600 Amphitheatre Parkway, Mountain View, CA") into geographic coordinates (like latitude 37.423021 and longitude -122.083739),via Google Geocoding API service. (@see https://developers.google.com/maps/documentation/geocoding/#Geocoding).
Later you can use geographic coordinates to place markers or position the map.

## Usage Limits

Current UmbrellawebGoogleGeocodingApiBundle version 1.0.0 does not use API key. And Users of the free API have limit 2,500 requests per 24 hour period. For more info about API Key @see https://developers.google.com/maps/documentation/geocoding/#api_key

## Geocoding Responses

By default UmbrellawebGoogleGeocodingApiBundle use ``json`` response format. For more info about response structure @see https://developers.google.com/maps/documentation/geocoding/#JSON

UmbrellawebGoogleGeocodingApiBundle provides get full results array and separate result's part like Latitude and Longitude in a convenient format.

## Usage

The bundle provides a service available via the ``umbrellaweb.google_geo_api.manager``
identifier.

To retrieve the service from the container, use the following code in your controller:

    $geocodeManager = $this->get('umbrellaweb.google_geo_api.manager');

### Basic usage

Examples of usage:
  
   $geoResponse = $this->get('umbrellaweb.google_geo_api.manager')->geocodeAddress(array('address' => '1600 Amphitheatre Parkway, Mountain View, CA', 'sensor' => 'false'));

    // checking if there was some errors
    if (!$geoResponse->isSuccess()) {
        $error = $geoResponse->getErrorMessage();

        echo $error;
    }

    // checking if response is received but location was not found by some reason
    if ($geoResponse->isSuccess() && !$geoResponse->isOkResponse()) {
        $warning = 'Sorry, but your Location not found: ' . $geoResponse->getStatus();

        echo $warning;
    }

    // retrieve Latitude and Longitude 
    if ($geoResponse->getLongitude() !== NULL && $geoResponse->getLatitude() !== NULL) {
        $lat = $geoResponse->getLatitude();
        $lng = $geoResponse->getLongitude();

        echo 'Latitude: ' . $lat;
        echo 'Longitude: ' . $lng;
    }

    // or for example you want retrieve contry code
    if ($geoResponse->getCountryCode() !== NULL) {
        $countryCode = $geoResponse->getCountryCode();

        echo 'Country Code: ' . $countryCode;
    }

## Google Terms of Service

Please respect the [terms of service](http://code.google.com/apis/maps/terms.html) (TOS) specified by Google for use of the Geocoding API.
    
