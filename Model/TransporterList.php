<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model;

use Websolute\TransporterBase\Api\DownloaderListProcessorInterface;
use Websolute\TransporterBase\Api\ManipulatorListProcessorInterface;
use Websolute\TransporterBase\Api\UploaderListProcessorInterface;
use Websolute\TransporterBase\Api\TransporterListInterface;
use Websolute\TransporterBase\Exception\TransporterException;

class TransporterList implements TransporterListInterface
{
    /**
     * @var DownloaderListProcessorInterface[]
     */
    private $allDownloaders;

    /**
     * @var ManipulatorListProcessorInterface[]
     */
    private $allManipulators;

    /**
     * @var UploaderListProcessorInterface[]
     */
    private $allUploaders;

    /**
     * @param DownloaderListProcessorInterface[] $allDownloaders
     * @param ManipulatorListProcessorInterface[] $allManipulators
     * @param UploaderListProcessorInterface[] $allUploaders
     * @throws TransporterException
     */
    public function __construct(
        array $allDownloaders = [],
        array $allManipulators = [],
        array $allUploaders = []
    ) {
        foreach ($allDownloaders as $downloader) {
            if (!$downloader instanceof DownloaderListProcessorInterface) {
                throw new TransporterException(__("Invalid type for downloader"));
            }
        }
        foreach ($allManipulators as $manipulator) {
            if (!$manipulator instanceof ManipulatorListProcessorInterface) {
                throw new TransporterException(__("Invalid type for manipulator"));
            }
        }
        foreach ($allUploaders as $uploader) {
            if (!$uploader instanceof UploaderListProcessorInterface) {
                throw new TransporterException(__("Invalid type for uploader"));
            }
        }
        $this->allDownloaders = $allDownloaders;
        $this->allManipulators = $allManipulators;
        $this->allUploaders = $allUploaders;
    }

    /**
     * @return DownloaderListProcessorInterface[]
     */
    public function getAllDownloaderList(): array
    {
        return $this->allDownloaders;
    }

    /**
     * @return ManipulatorListProcessorInterface[]
     */
    public function getAllManipulatorList(): array
    {
        return $this->allManipulators;
    }

    /**
     * @return UploaderListProcessorInterface[]
     */
    public function getAllUploaderList(): array
    {
        return $this->allUploaders;
    }

    /**
     * @param string $name
     * @return DownloaderListProcessorInterface
     * @throws TransporterException
     */
    public function getDownloaderList(string $name): DownloaderListProcessorInterface
    {
        if (!array_key_exists($name, $this->allDownloaders)) {
            throw new TransporterException(__('There is not a DownloaderList with code: %1', $name));
        }
        return $this->allDownloaders[$name];
    }

    /**
     * @param string $name
     * @return ManipulatorListProcessorInterface
     * @throws TransporterException
     */
    public function getManipulatorList(string $name): ManipulatorListProcessorInterface
    {
        if (!array_key_exists($name, $this->allManipulators)) {
            throw new TransporterException(__('There is not a ManipulatorList with code: %1', $name));
        }
        return $this->allManipulators[$name];
    }

    /**
     * @param string $name
     * @return UploaderListProcessorInterface
     * @throws TransporterException
     */
    public function getUploaderList(string $name): UploaderListProcessorInterface
    {
        if (!array_key_exists($name, $this->allUploaders)) {
            throw new TransporterException(__('There is not a UploaderList with code: %1', $name));
        }
        return $this->allUploaders[$name];
    }
}
