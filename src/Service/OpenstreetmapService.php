<?php
namespace PHPFacile\Openstreetmap\Service;

use PHPFacile\Openstreetmap\Model\OverpassResponse;
use GuzzleHttp\Client as Httpclient;

class OpenstreetmapService
{
    /**
     * Query the overpass API and retrieve an OSM relation identified by its id
     * REM: This is a temporary implementation that have to be improved !
     *
     * @param string|integer $relId Relation id
     *
     * @return OverpassResponse
     */
    public function getRelationById($relId)
    {
        // Make sure $relId is only made of digits to avoid injection
        if (0 < trim(strtr($relId, '0123456789', '          '))) {
            throw new \Exception('['.$relId.'] is not a valid relation Id');
        }

        $http = new Httpclient();
        $res  = $http->request('GET', 'http://overpass-api.de/api/interpreter?data=[out:json];relation('.$relId.');out;');

        $status = $res->getStatusCode();
        switch ($status) {
            case '200':
                $json = $res->getBody()->getContents();
                return new OverpassResponse($json);
                break;
            default:
                throw new \Exception('Unmanaged status ['.$status.']');
        }
    }
}
