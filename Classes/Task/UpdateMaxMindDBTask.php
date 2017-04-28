<?php
namespace Aoe\GeoIp\Task;

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

use Aoe\GeoIp\Service\GeoIpService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class UpdateMaxMindDBTask
 *
 * @package Aoe\GeoIp\Task
 */
class UpdateMaxMindDBTask extends AbstractTask
{

    /**
     * Function executed from the Scheduler.
     *
     * @return bool
     */
    public function execute()
    {
        set_time_limit(0);
        $this->createUpdateDirectory();
        $this->clearAll($this->getUpdateDirectory());
        $this->downloadTarFile();
        $this->extractFiles();
        $result = $this->copyNewDatabase();
        $this->clearAll($this->getUpdateDirectory(), true);
        return $result;
    }

    /**
     * Returns full path of directory used for database updates.
     *
     * @return string
     */
    private function getUpdateDirectory()
    {
        $directory = GeoIpService::DATABASE_DIRECTORY . GeoIpService::UPDATE_DIRECTORY;
        return GeneralUtility::getFileAbsFileName($directory);
    }

    /**
     * Creates directory used for database updates if it's needed.
     */
    private function createUpdateDirectory()
    {
        if (!is_dir($this->getUpdateDirectory())) {
            mkdir($this->getUpdateDirectory());
        }
    }

    /**
     * Returns full path of archive downloaded from the source.
     *
     * @return string
     */
    private function getTarFile()
    {
        return $this->getUpdateDirectory() . GeoIpService::DATABASE_NAME . '.dat.gz';
    }

    /**
     * Clears all inner files and directories from specified directory path.
     * If exception is added, only new database fale will not be deleted.
     *
     * @param string $directory
     * @param bool $except
     */
    private function clearAll($directory, $except = false)
    {
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file !== "." && $file !== "..") {
                    $fullPath = $directory . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($fullPath)) {
                        $this->clearAll($fullPath, $except);
                        rmdir($fullPath);
                    } elseif (false === $except ||
                        false === strpos($fullPath, GeoIpService::DATABASE_NAME . GeoIpService::DATABASE_EXTENSION)
                    ) {
                        unlink($fullPath);
                    }
                }
            }
        }
    }

    /**
     * Downloads archive with new database from source.
     */
    private function downloadTarFile()
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $configuration = $objectManager->get('Aoe\\GeoIp\\TYPO3\\Configuration\\ExtensionConfiguration');
        $file = fopen($this->getTarFile(), 'w+');
        $request = curl_init(str_replace(" ", "%20", $configuration->getDatabaseLocation()));
        curl_setopt($request, CURLOPT_TIMEOUT, 50);
        curl_setopt($request, CURLOPT_FILE, $file);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($request);
        curl_close($request);
        fclose($file);
    }

    /**
     * Extract archive into temporary directory.
     */
    private function extractFiles()
    {
        $phar = new \PharData($this->getTarFile());
        $phar->extractTo($this->getUpdateDirectory());
    }

    /**
     * Returns full path of temporary directory.
     *
     * @return null|string
     */
    private function findExtractedDir()
    {
        $files = scandir($this->getUpdateDirectory());
        foreach ($files as $file) {
            $fullPath = $this->getUpdateDirectory() . DIRECTORY_SEPARATOR . $file;
            if ($file !== '.' && $file !== '..' && is_dir($fullPath)) {
                return $fullPath;
            }
        }
        return null;
    }

    /**
     * Returns full path of new temporary database.
     *
     * @return null|string
     */
    private function findNewDatabase()
    {
        $directory = $this->findExtractedDir();
        if (null == $directory) {
            return null;
        }
        $files = scandir($directory);
        foreach ($files as $file) {
            $fullPath = $directory . DIRECTORY_SEPARATOR . $file;
            if ($file !== '.' && $file !== '..' && is_file($fullPath) &&
                false !== strpos($fullPath, GeoIpService::DATABASE_EXTENSION)
            ) {
                return $fullPath;
            }
        }
        return null;
    }

    /**
     * Returns full path for new database.
     *
     * @return string
     */
    private function getNewDatabaseFile()
    {
        return $this->getUpdateDirectory() . GeoIpService::DATABASE_NAME . GeoIpService::DATABASE_EXTENSION;
    }

    /**
     * Copies temporary database to permanent destination.
     * Returns result of the process.
     *
     * @return bool
     */
    private function copyNewDatabase()
    {
        $file = $this->findNewDatabase();
        if (null === $file) {
            return false;
        }
        return copy($file, $this->getNewDatabaseFile());
    }
}