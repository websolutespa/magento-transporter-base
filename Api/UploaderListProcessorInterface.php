<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Websolute\TransporterBase\Exception\TransporterException;

interface UploaderListProcessorInterface
{
    /**
     * @return UploaderInterface[]
     */
    public function getUploaders(): array;

    /**
     * @param int $activityId
     * @throws TransporterException
     * @throws NoSuchEntityException
     */
    public function execute(int $activityId): void;
}
