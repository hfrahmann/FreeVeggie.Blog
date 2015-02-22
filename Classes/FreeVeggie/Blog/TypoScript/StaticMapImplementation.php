<?php
namespace FreeVeggie\Blog\TypoScript;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "FreeVeggie.Blog".       *
 */

use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Http\Uri;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;
use TYPO3\Flow\Http\Client\CurlEngine;

/**
 *
 */
class StaticMapImplementation extends TemplateImplementation {

    /**
     * @return mixed|null
     */
    public function getFloat() {
        $alignment = $this->tsValue('alignment');
        if($alignment == 'left' || $alignment == 'right') {
            return $alignment;
        }
        return NULL;
    }

    /**
     * @return mixed|null
     */
    public function getCenter() {
        $alignment = $this->tsValue('alignment');
        if($alignment == 'center') {
            return $alignment;
        }
        return NULL;
    }


    public function getLinkUrl() {
        $address = $this->tsValue('address');
        return 'http://maps.google.de/maps?' . http_build_query(array('q' => $address));
    }


    /**
     * @return string
     * @throws \TYPO3\Flow\Http\Client\CurlEngineException
     * @throws \TYPO3\Flow\Http\Exception
     */
    public function getMapUrl() {
        $address = $this->tsValue('address');
        $maptype = $this->tsValue('maptype');
        $zoom = $this->tsValue('zoom');
        $width = $this->tsValue('width');
        $height = $this->tsValue('height');

        $curlEngine = new CurlEngine();
        $uri = new Uri('http://maps.googleapis.com/maps/api/geocode/json?' . http_build_query(array('address' => $address, 'sensor' => 'false')));
        $request = Request::create($uri);
        $result = $curlEngine->sendRequest($request);
        $content = $result->getContent();
        $jsonContent = json_decode($content, TRUE);

        $lon = $this->getLongitude($jsonContent);
        $lat = $this->getLatitude($jsonContent);

        if($lon == NULL || $lat == NULL) {
            return '';
        }

        $arguments = array();
        $arguments['sensor'] = 'false';
        $arguments['center'] = $address;
        $arguments['zoom'] = $zoom;
        $arguments['size'] = $this->getSizeString($width, $height);
        $arguments['maptype'] = $maptype;

        return 'http://maps.googleapis.com/maps/api/staticmap?' . http_build_query($arguments) . '&markers=color:red%7Clabel:A%7C' . $lat . ',' . $lon;
    }

    /**
     * @param $jsonArray
     * @return string|null
     */
    protected function getLongitude($jsonArray) {
        if(isset($jsonArray['results'][0]['geometry']['location']['lng'])) {
            return $jsonArray['results'][0]['geometry']['location']['lng'];
        }
        return NULL;
    }

    /**
     * @param $jsonArray
     * @return string|null
     */
    protected function getLatitude($jsonArray) {
        if(isset($jsonArray['results'][0]['geometry']['location']['lat'])) {
            return $jsonArray['results'][0]['geometry']['location']['lat'];
        }
        return NULL;
    }


    protected function getSizeString($width, $height) {
        $width = (int)$width;
        $height = (int)$height;

        if($width > 0 && $height > 0) {
            return $width . 'x' . $height;
        }
        return '600x300';
    }

}

?>