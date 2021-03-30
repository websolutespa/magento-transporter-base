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
use Websolute\TransporterBase\Api\DownloaderInterface;
use Websolute\TransporterBase\Api\DownloaderListProcessorInterface;
use Websolute\TransporterBase\Exception\TransporterException;

class DownloaderListProcessor implements DownloaderListProcessorInterface
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
     * @var DownloaderInterface[]
     */
    private $downloaders;

    /**
     * @param ActivityRepositoryInterface $activityRepository
     * @param Logger $logger
     * @param array $downloaders
     */
    public function __construct(
        ActivityRepositoryInterface $activityRepository,
        Logger $logger,
        array $downloaders = []
    ) {
        $this->logger = $logger;
        $this->downloaders = $downloaders;
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
                'activityId:%1 ~ DownloaderListProcessor ~ type:%2 ~ START',
                $activityId,
                $activity->getType()
            ));
            foreach ($this->downloaders as $downloaderType => $downloader) {
                $activity->addExtraArray(['downloader_' . $downloaderType => ActivityStateInterface::DOWNLOADING]);
                $this->activityRepository->save($activity);

                $this->logger->debug(__(
                    'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ START',
                    $activityId,
                    $downloaderType
                ));
                $downloader->execute($activityId, $downloaderType);
                $this->logger->debug(__(
                    'activityId:%1 ~ Downloader ~ downloaderType:%2 ~ END',
                    $activityId,
                    $downloaderType
                ));

                $activity = $this->activityRepository->getById($activityId);
                $activity->addExtraArray(['downloader_' . $downloaderType => ActivityStateInterface::DOWNLOADED]);
                $this->activityRepository->save($activity);
            }
            $this->logger->info(__(
                'activityId:%1 ~ DownloaderListProcessor ~ type:%2 ~ END',
                $activityId,
                $activity->getType()
            ));
        } catch (TransporterException $e) {
            if (isset($downloaderType)) {
                $activity->addExtraArray(['downloader_' . $downloaderType => ActivityStateInterface::DOWNLOAD_ERROR]);
                $this->activityRepository->save($activity);
            }
            $this->logger->error(__(
                'activityId:%1 ~ DownloaderListProcessor ~ type:%2 ~ ERROR ~ error:%3',
                $activityId,
                $activity->getType(),
                $e->getMessage()
            ));
            throw $e;
        }

        $activity = $this->activityRepository->getById($activityId);
        $activity->setStatus(ActivityStateInterface::DOWNLOADED);
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
     * @return DownloaderInterface[]
     */
    public function getDownloaders(): array
    {
        return $this->downloaders;
    }
}
