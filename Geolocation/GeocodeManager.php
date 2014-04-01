<?php

namespace Umbrellaweb\Bundle\GoogleGeocodingApiBundle\Geolocation;

use Umbrellaweb\Bundle\GoogleGeocodingApiBundle\Geolocation\GeocodeResponse;
use Umbrellaweb\Bundle\GoogleGeocodingApiBundle\Exception\GeocodeException;

/**
 * Class to provide intagration with Google Geocoding API
 * 
 * Send request to google map api service and return instance of the GeocodeResponse class
 * 
 * @link http://code.google.com/apis/maps/documentation/geocoding/
 */
class GeocodeManager {

    /**
     * Google Geocoding API base url 
     * @see https://developers.google.com/maps/documentation/geocoding/#GeocodingRequests
     * @var string
     */
    const API_BASE_URL = "http://maps.googleapis.com/maps/api/geocode/";

    /**
     * Сompound url to send API request 
     * @var string 
     */
    protected $_url;

    public function __construct($response_format = 'json') {
        //set response format to url
        $this->_url = self::API_BASE_URL . $response_format . '?';
    }

    /**
     * Convert Address string to Geocode information object
     * 
     * @param array $parameters example:array('address' => 'New York', 'sensor' => 'false')
     * 
     * @return \PRL\_ServiceBundle\GoogleMapsApi\GeocodeManager
     */
    public function geocodeAddress(array $parameters) {
        try {
            $response = $this->_request($this->_url . $this->_urlParamsEncode($parameters));
        } catch (GeocodeException $e) {
            return new GeocodeResponse($e);
        }
        return new GeocodeResponse($response);
    }

    /**
     * process curl request
     * 
     * @param string $url
     * @param array $options Additional CURL options
     * @throws GeocodeException
     */
    protected function _request($url, array $options = array()) {
        $default = array(
            CURLOPT_RETURNTRANSFER => TRUE, // return web page
            CURLOPT_HEADER => FALSE, // don't return headers
            CURLOPT_FOLLOWLOCATION => TRUE, // follow redirects
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 100,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE
        );

        //CURL request to URL
        $ch = curl_init($url);

        curl_setopt_array($ch, $default + $options);

        $result = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        $ch = null;
        unset($ch);

        //content received
        if (in_array($curl_info['http_code'], array(200, 301)))
            return $result;
        if ($curl_error)
            throw new GeocodeException($curl_error);
        if (!$result['body'])
            throw new GeocodeException("Body of file is empty");
    }

    /**
     * Encode array of parameters to url parameteres part string 
     * 
     * @param array $parameters
     * @return string
     */
    protected function _urlParamsEncode(array $parameters) {
        $return = '';
        foreach ($parameters as $key => $value) {
            $return .= $key . '=' . urlencode($value) . '&';
        }
        return rtrim($return, '&');
    }

}
