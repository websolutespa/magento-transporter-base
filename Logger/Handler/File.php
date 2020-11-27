<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Logger\Handler;

use Exception;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Websolute\TransporterBase\Api\TransporterConfigInterface;

class File extends Base
{
    /**
     * @var TransporterConfigInterface
     */
    private $config;

    /**
     * @param TransporterConfigInterface $config
     * @param DriverInterface $filesystem
     * @param null $filePath
     * @param null $fileName
     * @throws Exception
     */
    public function __construct(
        TransporterConfigInterface $config,
        DriverInterface $filesystem,
        $filePath = null,
        $fileName = null
    ) {
        parent::__construct($filesystem, $filePath, $fileName);
        $this->config = $config;
    }

    /**
     * @param array $record
     */
    public function handle(array $record)
    {
        if ($this->config->isLogEnabled() && ($record['level'] >= $this->config->getLogLevel())) {
            parent::handle($record);
        }
    }
}
