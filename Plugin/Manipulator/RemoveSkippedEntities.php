<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Plugin\Manipulator;

use Websolute\TransporterBase\Api\ManipulatorInterface;
use Websolute\TransporterEntity\Api\Data\EntityInterface;

class RemoveSkippedEntities
{
    /**
     * @param ManipulatorInterface $subject
     * @param callable $proceed
     * @param int $activityId
     * @param string $manipulatorType
     * @param string $entityIdentifier
     * @param EntityInterface[] $entities
     * @return void
     */
    public function aroundExecute(
        ManipulatorInterface $subject,
        callable $proceed,
        int $activityId,
        string $manipulatorType,
        string $entityIdentifier,
        array $entities
    ) {
        /** @var EntityInterface $entity */
        $entities = array_filter($entities, function ($entity) {
            return false === $entity->isSkip();
        });

        if (count($entities) > 0) {
            $proceed($activityId, $manipulatorType, $entityIdentifier, $entities);
        }
    }
}
