UmbrellawebGoogleGeocodingApiBundle
========================

Provides interaction with The Google Geocoding API (@see https://developers.google.com/maps/documentation/geocoding/) for more info.

## Usage

The bundle provides a service available via the ``umbrellaweb.google_geo_api.manager``
identifier.

To retrieve the service from the container:

        $geocodeManager = $this->get('umbrellaweb.google_geo_api.manager');

### Basic usage

To find latitude and longitude:
      
        $geocodeManager = $this->get('umbrellaweb.google_geo_api.manager');
        $googleResponse = $geocodeManager->sendRequest(array('address' => urlencode('New York'), 'sensor' => 'false'));

        if ($googleResponse->isSuccess() && $googleResponse->getResults() == NULL) {
            $warning = 'Sorry, but your Location not found: ' . $googleResponse->getStatus();
            echo $warning;
        }
        if ($googleResponse->getLongitude() !== NULL && $googleResponse->getLatitude() !== NULL) {
            $lat = $googleResponse->getLatitude();
            $lng = $googleResponse->getLongitude();

            echo $lat;
            echo $lng;
        }

## Google Terms of Service

Please respect the
[terms of service](http://code.google.com/apis/maps/terms.html) (TOS)
specified by Google for use of the Geocoding API.
    
