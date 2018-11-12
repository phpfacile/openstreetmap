<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use PHPFacile\Openstreetmap\Service\OpenstreetmapService;

final class OpenstreetmapServiceTest extends TestCase
{
    protected $openstreetmapService;

    protected function setUp()
    {
        $this->openstreetmapService = new OpenstreetmapService();
    }


    public function testGetRelationById()
    {
        $overpassResponse = $this->openstreetmapService->getRelationById(7444);

        $this->assertEquals(22, count($overpassResponse->getPostalCodes()));

        $names = $overpassResponse->getNames();
        $this->assertEquals('Paris', $names['fr']);

        $this->assertEquals('Paris', $overpassResponse->getOfficialName());
    }

    /**
     * @expectedException Exception
     */
    public function testTimezoneCountryWithSeveralTimezone()
    {
        $overpassResponse = $this->openstreetmapService->getRelationById(7444);

        $overBoundIndex = 1;
        $names = $overpassResponse->getNames($overBoundIndex);
    }
}
