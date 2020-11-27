<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Monolog\Logger;
use Websolute\TransporterActivity\Api\ActivityRepositoryInterface;
use Websolute\TransporterActivity\Model\ActivityStateInterface;
use Websolute\TransporterBase\Api\UploaderInterface;
use Websolute\TransporterBase\Api\UploaderListProcessorInterface;
use Websolute\TransporterBase\Exception\TransporterException;

class UploaderListProcessor implements UploaderListProcessorInterface
{
    /**
     * @var ActivityRepositoryInterface
     */
    private $activityRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var UploaderInterface[]
     */
    private $uploaders;

    /**
     * @param ActivityRepositoryInterface $activityRepository
     * @param Logger $logger
     * @param array $uploaders
     */
    public function __construct(
        ActivityRepositoryInterface $activityRepository,
        Logger $logger,
        array $uploaders = []
    ) {
        $this->logger = $logger;
        $this->uploaders = $uploaders;
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
            $this->logger->info(__(
                'activityId:%1 ~ UploaderListProcessor ~ type:%2 ~ START',
                $activityId,
                $activity->getType()
            ));
            foreach ($this->uploaders as $uploaderType => $uploader) {
                $activity->addExtraArray(['uploader_' . $uploaderType => ActivityStateInterface::UPLOADING]);
                $this->activityRepository->save($activity);

                $this->logger->debug(__(
                    'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ START',
                    $activityId,
                    $uploaderType
                ));
                $uploader->execute($activityId, $uploaderType);
                $this->logger->debug(__(
                    'activityId:%1 ~ Uploader ~ uploaderType:%2 ~ END',
                    $activityId,
                    $uploaderType
                ));

                $activity->addExtraArray(['uploader_' . $uploaderType => ActivityStateInterface::UPLOADED]);
                $this->activityRepository->save($activity);
            }
            $this->logger->info(__(
                'activityId:%1 ~ UploaderListProcessor ~ type:%2 ~ END',
                $activityId,
                $activity->getType()
            ));
        } catch (TransporterException $e) {
            if (isset($uploaderType)) {
                $activity->addExtraArray(['uploader_' . $uploaderType => ActivityStateInterface::UPLOAD_ERROR]);
                $this->activityRepository->save($activity);
            }
            $this->logger->error(__(
                'activityId:%1 ~ UploaderListProcessor ~ type:%2 ~ ERROR ~ error:%3',
                $activityId,
                $activity->getType(),
                $e->getMessage()
            ));
            throw $e;
        }

        $activity = $this->activityRepository->getById($activityId);
        $activity->setStatus(ActivityStateInterface::UPLOADED);
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
     * @return UploaderInterface[]
     */
    public function getUploaders(): array
    {
        return $this->uploaders;
    }
}
