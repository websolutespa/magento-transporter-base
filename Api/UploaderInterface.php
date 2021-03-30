<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Api;

use Websolute\TransporterBase\Exception\TransporterException;

interface UploaderInterface
{
    /**
     * @param int $activityId
     * @param string $uploaderType
     * @throws TransporterException
     */
    public function execute(int $activityId, string $uploaderType): void;
}
