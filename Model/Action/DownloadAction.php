<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model\Action;

use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Websolute\TransporterActivity\Api\ActivityRepositoryInterface;
use Websolute\TransporterActivity\Model\ActivityModelFactory;
use Websolute\TransporterActivity\Model\ActivityStateInterface;
use Websolute\TransporterBase\Api\TransporterListInterface;
use Websolute\TransporterBase\Exception\TransporterException;

class DownloadAction
{
    /**
     * @var TransporterListInterface
     */
    private $transporterList;

    /**
     * @var ActivityRepositoryInterface
     */
    private $activityRepository;

    /**
     * @var ActivityModelFactory
     */
    private $activityModelFactory;

    /**
     * @param TransporterListInterface $transporterList
     * @param ActivityRepositoryInterface $activityRepository
     * @param ActivityModelFactory $activityModelFactory
     */
    public function __construct(
        TransporterListInterface $transporterList,
        ActivityRepositoryInterface $activityRepository,
        ActivityModelFactory $activityModelFactory
    ) {
        $this->transporterList = $transporterList;
        $this->activityRepository = $activityRepository;
        $this->activityModelFactory = $activityModelFactory;
    }

    /**
     * @param string $type
     * @param string $extra
     * @throws NoSuchEntityException
     * @throws TransporterException
     */
    public function execute(string $type, string $extra = '')
    {
        try {
            $downloaderList = $this->transporterList->getDownloaderList($type);
            $activity = $this->activityModelFactory->create();
            $activity->setType($type);
            $activity->setStatus(ActivityStateInterface::DOWNLOADING);
            if ($extra !== '') {
                $activity->addExtraArray(['data' => $extra]);
            }
            $this->activityRepository->save($activity);

            $downloaderList->execute((int)$activity->getId());
        } catch (Exception $e) {
            if (isset($activity)) {
                $activity->setStatus(ActivityStateInterface::DOWNLOAD_ERROR);
                $activity->addExtraArray(['error' => $e->getMessage()]);
                $this->activityRepository->save($activity);
            }
            throw $e;
        }
    }
}
