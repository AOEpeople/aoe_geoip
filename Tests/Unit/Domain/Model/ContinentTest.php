<?php
namespace Aoe\GeoIp\Domain\Model;

/**
 * @covers Aoe\GeoIp\Domain\Model\Continent
 */
class ContinentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Continent
     */
    protected $continent;

    /**
     * @var array
     */
    protected $testRecord = [
        'continent' => [
            'code' => 'AS',
            'names' => [
                'en' => 'Asia',
                'de' => 'Asien'
            ]
        ],
    ];

    /**
     * Create Continent test record
     */
    protected function setUp()
    {
        parent::setUp();
        $this->continent = new Continent($this->testRecord);
    }

    /**
     * @test
     */
    public function getCodeTest()
    {
        $this->assertEquals('AS', $this->continent->getCode());
    }

    /**
     * @test
     */
    public function getLocalizedNameTest()
    {
        $this->assertEquals('Asia', $this->continent->getLocalizedName('EN'));
        $this->assertEquals('Asien', $this->continent->getLocalizedName('DE'));
    }

}
