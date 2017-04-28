<?php
namespace Aoe\GeoIp\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Aoe\GeoIp\Domain\Model\Country;
use MaxMind\Db\Reader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GeoIpService
 *
 * @package Aoe\GeoIp\Service
 */
class GeoIpService
{

    /**
     * @var Reader
     */
    private $reader = null;

    /**
     * Returns country that corresponds to the client's IP address.
     *
     * @return Country|null
     */
    public function getCountry()
    {
        $ip = $this->getIp();
        $record = $this->getReader()->get($ip);
        if (is_array($record)) {
            return new Country($record);
        }
        return null;
    }

    /**
     * Returns an instance of MaxMind's Reader class.
     *
     * @return Reader
     */
    private function getReader()
    {
        if (null === $this->reader) {
            $fileLocation = GeneralUtility::getFileAbsFileName('EXT:aoe_geoip/Resources/Private/GeoIpDB/GeoLite2-Country.mmdb');
            $this->reader = new Reader($fileLocation);
        }
        return $this->reader;
    }

    /**
     * Returns the client's IP address.
     *
     * @return string
     */
    private function getIp()
    {
        if (isset($_COOKIE['TEST_IP'])) {
            return $_COOKIE['TEST_IP'];
        }
        return GeneralUtility::getIndpEnv('REMOTE_ADDR');
    }
}