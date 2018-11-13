<?php
namespace PHPFacile\Openstreetmap\Service;

interface OpenstreetmapServiceInterface
{
    /**
     * Query the overpass API and retrieve an OSM relation identified by its id
     * REM: This is a temporary implementation that have to be improved !
     *
     * @param string|integer $relId Relation id
     *
     * @return OverpassResponse
     */
    public function getRelationById($relId);
}
