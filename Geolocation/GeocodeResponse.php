<?php

namespace Umbrellaweb\Bundle\GoogleGeocodingApiBundle\Geolocation;

use Umbrellaweb\Bundle\GoogleGeocodingApiBundle\Exception\GeocodeException;

/**
 * Class to parse and return google geocoding response parts
 */
class GeocodeResponse {

    const STATUS_OK = 'OK';

    protected $_response = array();
    protected $_error;

    public function __construct($response) {
        if ($response instanceof GeocodeException) {
            $this->_error = $response->getMessage();
        } else {
            $this->_response = json_decode($response, true);
        }
    }

    /**
     * Is used for check is response success
     * @return boolean
     */
    public function isSuccess() {
        if (empty($this->_error)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Is used for check is response status code Ok
     * @return boolean
     */
    public function isOkResponse() {
        if ($this->getStatus() === self::STATUS_OK) {
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
     * @return array
     */
    public function getResults() {
        if ($this->isOkResponse()) {
            return $this->_response['results'][0];
        }
        return array();
    }

    /**
     * Get response status
     * 
     * @return null/string
     */
    public function getStatus() {
        return (array_key_exists('status', $this->_response)) ? $this->_response['status'] : NULL;
    }

    /**
     * Parse google geocoding response and get Latitude
     * 
     * @return null/float
     */
    public function getLatitude() {
        $results = $this->getResults();

        if (!empty($results)) {
            return (float) $results['geometry']['location']['lat'];
        }

        return NULL;
    }

    /**
     * Parse google geocoding response and get Longitude
     * 
     * @return null/float
     */
    public function getLongitude() {
        $results = $this->getResults();

        if (!empty($results)) {
            return (float) $results['geometry']['location']['lng'];
        }

        return NULL;
    }

    /**
     * Parse google geocoding response and find Country short name
     * 
     * @return null/string
     */
    public function getCountryCode() {
        $results = $this->getResults();

        if (!empty($results)) {
            foreach ($results['address_components'] as $component) {
                if (in_array('country', $component['types'])) {
                    return $component['short_name'];
                }
            }
        }

        return NULL;
    }

}
