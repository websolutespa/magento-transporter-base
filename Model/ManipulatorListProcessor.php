<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Monolog\Logger;
use Websolute\TransporterActivity\Api\ActivityRepositoryInterface;
use Websolute\TransporterActivity\Model\ActivityStateInterface;
use Websolute\TransporterBase\Api\ManipulatorInterface;
use Websolute\TransporterBase\Api\ManipulatorListProcessorInterface;
use Websolute\TransporterBase\Exception\TransporterException;
use Websolute\TransporterEntity\Api\Data\EntityInterface;
use Websolute\TransporterEntity\Api\EntityRepositoryInterface;

class ManipulatorListProcessor implements ManipulatorListProcessorInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * @var ActivityRepositoryInterface
     */
    private $activityRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ManipulatorInterface[]
     */
    private $manipulators;

    /**
     * @param EntityRepositoryInterface $entityRepository
     * @param ActivityRepositoryInterface $activityRepository
     * @param Logger $logger
     * @param ManipulatorInterface[] $manipulators
     * @throws TransporterException
     */
    public function __construct(
        EntityRepositoryInterface $entityRepository,
        ActivityRepositoryInterface $activityRepository,
        Logger $logger,
        array $manipulators = []
    ) {
        foreach ($manipulators as $manipulator) {
            if (!$manipulator instanceof ManipulatorInterface) {
                throw new TransporterException(__("Invalid type for manipulator"));
            }
        }
        $this->logger = $logger;
        $this->manipulators = $manipulators;
        $this->entityRepository = $entityRepository;
        $this->activityRepository = $activityRepository;
    }

    /**
     * @param int $activityId
     * @throws TransporterException
     * @throws NoSuchEntityException
     */
    public function execute(int $activityId): void
    {
        $activity = $this->activityRepository->getById($activityId);

        try {
            $allActivityEntities = $this->entityRepository->getAllByActivityIdGroupedByIdentifier($activityId);

            /** @var EntityInterface[] $entities */
            foreach ($allActivityEntities as $entityIdentifier => $entities) {
                $this->logger->info(__(
                    'activityId:%1 ~ ManipulatorList ~ entityIdentifier:%2 ~ START',
                    $activityId,
                    $entityIdentifier
                ));

                foreach ($this->manipulators as $manipulatorType => $manipulator) {
                    $this->logger->debug(__(
                        'activityId:%1 ~ Manipulator ~ manipulatorType:%2 ~ entityIdentifier:%3 ~ START',
                        $activityId,
                        $manipulatorType,
                        $entityIdentifier
                    ));
                    $manipulator->execute($activityId, $manipulatorType, (string)$entityIdentifier, $entities);
                    $this->logger->debug(__(
                        'activityId:%1 ~ Manipulator ~ manipulatorType:%2 ~ entityIdentifier:%3 ~ END',
                        $activityId,
                        $manipulatorType,
                        $entityIdentifier
                    ));

                    $activity->addExtraArray([
                        'manipulator_' . $manipulatorType => ActivityStateInterface::MANIPULATED
                    ]);
                    $this->activityRepository->save($activity);
                }

                foreach ($entities as $entity) {
                    $this->entityRepository->save($entity);
                }

                $this->logger->info(__(
                    'activityId:%1 ~ ManipulatorList ~ entityIdentifier:%2 ~ END',
                    $activityId,
                    $entityIdentifier
                ));
            }
        } catch (TransporterException $e) {
            if (isset($manipulatorType)) {
                $activity->addExtraArray([
                    'manipulator_' . $manipulatorType => ActivityStateInterface::MANIPULATE_ERROR
                ]);
                $this->activityRepository->save($activity);
            }
            $this->logger->error(__(
                'activityId:%1 ~ ManipulatorList ~ ERROR ~ error:%2',
                $activityId,
                $e->getMessage()
            ));
            throw $e;
        }

        $activity = $this->activityRepository->getById($activityId);
        $activity->setStatus(ActivityStateInterface::MANIPULATED);
        $this->activityRepository->save($activity);
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return ManipulatorInterface[]
     */
    public function getManipulators(): array
    {
        return $this->manipulators;
    }
}
