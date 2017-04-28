<?php

namespace Aoe\GeoIp\Domain\Model;

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

/**
 * Class Continent
 * @package Aoe\GeoIp\Domain\Model
 */
class Continent
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var array
     */
    private $names;

    /**
     * Continent constructor.
     * @param array $record
     */
    public function __construct($record)
    {
        $this->code = $record['continent']['code'];
        $this->names = $record['continent']['names'];
    }

    /**
     * Returns continent ISO code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns localized name of continent, depending on
     * language ISO code.
     *
     * @param string $languageCode
     * @return string
     */
    public function getLocalizedName($languageCode)
    {
        return $this->names[strtolower($languageCode)];
    }
}