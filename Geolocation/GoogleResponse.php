<?php

namespace Umbrellaweb\Bundle\GoogleGeocodingApiBundle\Geolocation;

/**
 * class to parse and return google geocoding response 
 */
class GoogleResponse {

    const STATUS_OK = 'OK';

    protected $_response;
    protected $_error;

    public function __construct($response) {
        if ($response instanceof \Exception) {
            $this->_error = $response->getMessage();
        } else {
            $this->_response = json_decode($response, true);
        }
    }

    /**
     * Is used for check is response success
     * 
     * @return boolean
     */
    public function isSuccess() {
        if (empty($this->_error)) {
            return TRUE;
        }
        return FALSE;
    }

    public function getErrorMessage() {
        return $this->_error;
    }

    /**
     * Parse google geocoding response and return results array
     * 
     * @return null / google result array
     */
    public function getResults() {
        $response = $this->_response;
        if ($response['status'] == self::STATUS_OK) {
            return $response['results'][0];
        }
        return NULL;
    }

    /**
     * Get response status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->_response['status'];
    }

    /**
     * Parse google geocoding response and get Latitude
     * 
     * @return null / decimal
     */
    public function getLatitude() {
        $results = $this->getResults();

        if ($results != NULL) {
            return $results['geometry']['location']['lat'];
        }
        return null;
    }

    /**
     * Parse google geocoding response and get Longitude
     * 
     * @return null / decimal
     */
    public function getLongitude() {
        $results = $this->getResults();
        if ($results != NULL) {
            return $results['geometry']['location']['lng'];
        }
        return null;
    }

    /**
     * Parse google geocoding response and find Country short name
     * 
     * @return null / string
     */
    public function getCountry() {
        $results = $this->getResults();
        if ($results != NULL) {
            foreach ($results['address_components'] as $component) {
                if ($component['types'][0] == 'country') {
                    return $component['short_name'];
                }
            }
        }
        return null;
    }

}
