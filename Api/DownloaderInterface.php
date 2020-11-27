<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Websolute\TransporterBase\Exception\TransporterException;

interface DownloaderInterface
{
    /**
     * @param int $activityId
     * @param string $downloaderType
     * @throws TransporterException
     * @throws NoSuchEntityException
     */
    public function execute(int $activityId, string $downloaderType): void;
}
