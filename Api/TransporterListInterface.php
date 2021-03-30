<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Api;

use Websolute\TransporterBase\Exception\TransporterException;

interface TransporterListInterface
{
    /**
     * @return DownloaderListProcessorInterface[]
     */
    public function getAllDownloaderList(): array;

    /**
     * @return ManipulatorListProcessorInterface[]
     */
    public function getAllManipulatorList(): array;

    /**
     * @return UploaderListProcessorInterface[]
     */
    public function getAllUploaderList(): array;

    /**
     * @param string $name
     * @return DownloaderListProcessorInterface
     * @throws TransporterException
     */
    public function getDownloaderList(string $name): DownloaderListProcessorInterface;

    /**
     * @param string $name
     * @return ManipulatorListProcessorInterface
     * @throws TransporterException
     */
    public function getManipulatorList(string $name): ManipulatorListProcessorInterface;

    /**
     * @param string $name
     * @return UploaderListProcessorInterface
     * @throws TransporterException
     */
    public function getUploaderList(string $name): UploaderListProcessorInterface;
}
