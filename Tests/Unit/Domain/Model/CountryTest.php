<?php
namespace Aoe\GeoIp\Domain\Model;

/**
 * @covers Aoe\GeoIp\Domain\Model\Country
 */
class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Country
     */
    protected $country;

    /**
     * @var array
     */
    protected $testRecord = [
        'country' => [
            'iso_code' => 'de',
            'names' => [
                'en' => 'Germany',
                'de' => 'Deutschland',
            ],
        ],
    ];

    /**
     * Create Country test record
     */
    protected function setUp()
    {
        parent::setUp();
        $this->country = new Country($this->testRecord);
    }

    /**
     * @test
     */
    public function getCodeTest()
    {
        $this->assertEquals('de', $this->country->getCode());
    }

    /**
     * @test
     */
    public function getLocalizedNameTest()
    {
        $this->assertEquals('Germany', $this->country->getLocalizedName('EN'));
        $this->assertNotEquals('Deutschland', $this->country->getLocalizedName('EN'));
    }

}
