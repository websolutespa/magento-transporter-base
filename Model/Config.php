<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Monolog\Logger;
use Websolute\TransporterBase\Api\TransporterConfigInterface;

class Config implements TransporterConfigInterface
{
    const TRANSPORTER_IS_ENABLED_CONFIG_PATH = 'transporter/general/enabled';
    const TRANSPORTER_SEMAPHORE_THRESHOLD_CONFIG_PATH = 'transporter/general/semaphore_threshold';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::TRANSPORTER_IS_ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getSemaphoreThreshold(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::TRANSPORTER_SEMAPHORE_THRESHOLD_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function continueInCaseOfErrors(): bool
    {
        return true;
    }

    public function isLogEnabled(): bool
    {
        return true;
    }

    public function getLogLevel(): int
    {
        return Logger::DEBUG;
    }
}
