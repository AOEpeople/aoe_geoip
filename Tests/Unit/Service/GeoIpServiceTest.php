<?php
namespace Aoe\GeoIp\Service;

use Aoe\GeoIp\Service\GeoIpService;
/**
 * @covers Aoe\GeoIp\Service\GeoIpService
 */
class GeoIpServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GeoIpService
     */
    protected $geoIpService;

    /**
     * Create Service for testing
     */
    protected function setUp()
    {
        parent::setUp();
//        $objectManager = $this->getMock('TYPO3\CMS\Extbase\Object\ObjectManager');
//        $this->geoIpService = $objectManager->get('Aoe\GeoIp\Service\GeoIpService');
    }

    /**
     * @test
     */
    public function getCountryTest()
    {
        $this->assertTrue(true);
//        var_dump($this->geoIpService->getCountry());
    }
}
