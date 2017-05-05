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
use Aoe\GeoIp\TYPO3\Configuration\ExtensionConfiguration;
use MaxMind\Db\Reader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GeoIpService
 *
 * @package Aoe\GeoIp\Service
 */
class GeoIpService
{

    const GEO_IP_USER_OVERRIDE = 'GEO_IP_USER_OVERRIDE';

    const DATABASE_DIRECTORY = 'EXT:aoe_geoip/Resources/Private/GeoIpDB';

    const UPDATE_DIRECTORY = '/update';

    const DATABASE_NAME = '/database';

    const DATABASE_EXTENSION = '.mmdb';
    
    /**
     * @var Reader
     */
    private $reader = null;

    /**
     * @var ExtensionConfiguration
     */
    private $configuration = null;

    /**
     * GeoIpService constructor.
     *
     * Adds an instance of eftension configuration.
     */
    public function __construct()
    {
        $this->configuration = new ExtensionConfiguration();
    }

    /**
     * Returns country that corresponds to the client's IP address.
     * If it can't fetch country from database, it returns null.
     *
     * @param string $ipAddress
     * @return Country|null
     */
    public function getCountry($ipAddress = null)
    {
        try {
            $ipAddress = ($ipAddress !== null) ? $ipAddress : $this->getIpAddress();
            $record = $this->getReader()->get($ipAddress);
            if (is_array($record)) {
                return new Country($record);
            }
        } catch (\Exception $ex) {
            error_log("Click here for more info: http://stackoverflow.com/search?q=" . $ex->getMessage());
            return null;
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
            $fileLocation = self::DATABASE_DIRECTORY . self::UPDATE_DIRECTORY .
                self::DATABASE_NAME . self::DATABASE_EXTENSION;
            $fileLocation = GeneralUtility::getFileAbsFileName($fileLocation);
            if (!is_file($fileLocation)) {
                $fileLocation = self::DATABASE_DIRECTORY . self::DATABASE_NAME . self::DATABASE_EXTENSION;
                $fileLocation = GeneralUtility::getFileAbsFileName($fileLocation);
            }
            $this->reader = new Reader($fileLocation);
        }
        return $this->reader;
    }

    /**
     * Returns the client's IP address.
     *
     * @return string
     */
    private function getIpAddress()
    {
        $ipAddressFromCookie = $this->getIpAddressFromCookie();
        if ($this->configuration->isTestWithCookies() && !empty($ipAddressFromCookie)) {
            return $ipAddressFromCookie;
        }
        return GeneralUtility::getIndpEnv('REMOTE_ADDR');
    }

    /**
     * Returns IP address override from cookie.
     *
     * @return string/null
     */
    private function getIpAddressFromCookie()
    {
        return $_COOKIE[self::GEO_IP_USER_OVERRIDE];
    }
}