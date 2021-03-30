<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Api;

use Websolute\TransporterBase\Exception\TransporterException;
use Websolute\TransporterEntity\Api\Data\EntityInterface;

interface ManipulatorInterface
{
    /**
     * @param int $activityId
     * @param string $manipulatorType
     * @param string $entityIdentifier
     * @param EntityInterface[] $entities
     * @throws TransporterException
     */
    public function execute(
        int $activityId,
        string $manipulatorType,
        string $entityIdentifier,
        array $entities
    ): void;
}
