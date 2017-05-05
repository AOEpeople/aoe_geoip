<?php
namespace Aoe\GeoIp\Service;

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
     * Some example IP addresses (Germany, Bulgaria, Spain, Serbia, Turkey)
     *
     * @var array
     */
    protected $europeTestIps = ['5.35.241.214', '31.211.128.4', '31.214.176.44', '77.46.128.156', '5.250.240.3'];

    /**
     * @var array
     */
    protected $chTestIps = ['5.153.112.123', '217.26.58.33'];

    /**
     * @var array
     */
    protected $usTestIps = ['72.229.28.185', '13.96.3.56'];

    /**
     * Some example IP addresses (Jordan, Israel, United Arab Emirates)
     *
     * @var array
     */
    protected $midEastTestIps = ['94.249.55.82', '31.44.128.23', '31.29.64.65'];

    /**
     * Some example IP addresses (Taiwan, Japan)
     *
     * @var array
     */
    protected $farEastTestIps = ['27.96.224.185', '14.192.96.123'];

    /**
     * Create Service for testing
     */
    protected function setUp()
    {
        parent::setUp();
        $this->geoIpService = new GeoIpService();
    }

    /**
     * @test
     */
    public function getEuropeCountriesTest()
    {
        foreach ($this->europeTestIps as $index => $ipAddress) {
            $continentCode = $this->geoIpService->getCountry($ipAddress)->getContinent()->getCode();
            $countryCode = $this->geoIpService->getCountry($ipAddress)->getCode();
            if ($index != 4) {
                $this->assertEquals('EU', $continentCode);
            }
            if ($index == 0) {
                $this->assertEquals('DE', $countryCode);
            }
            if ($index == 1) {
                $this->assertEquals('BG', $countryCode);
            }
            if ($index == 2) {
                $this->assertEquals('ES', $countryCode);
            }
            if ($index == 3) {
                $this->assertEquals('RS', $countryCode);
                $this->assertNotEquals('CH', $countryCode);
            }
            if ($index == 4) {
                $this->assertEquals('TR', $countryCode);
                $this->assertNotEquals('SR', $countryCode);
            }
        }
    }

    /**
     * @test
     */
    public function getSwitzerlandCountryTest()
    {
        foreach ($this->chTestIps as $index => $ipAddress) {
            $this->assertEquals('EU', $this->geoIpService->getCountry($ipAddress)->getContinent()->getCode());
            $countryCode = $this->geoIpService->getCountry($ipAddress)->getCode();
            if ($index == 0) {
                $this->assertEquals('CH', $countryCode);
                $this->assertNotEquals('DE', $countryCode);
            }
            if ($index == 1) {
                $this->assertEquals('CH', $countryCode);
                $this->assertNotEquals('DE', $countryCode);
            }
        }
    }

    /**
     * @test
     */
    public function getUsaCountriesTest()
    {
        foreach ($this->usTestIps as $index => $ipAddress) {
            $continentCode = $this->geoIpService->getCountry($ipAddress)->getContinent()->getCode();
            $this->assertEquals(in_array($continentCode, ['NA', 'SA']), 1);
            if ($index == 0) {
                $this->assertNotEquals('EU', $continentCode);
            }
            if ($index == 1) {
                $this->assertNotEquals('AS', $continentCode);
            }
        }
    }
}
