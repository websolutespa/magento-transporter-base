<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Api;

interface TransporterConfigInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @return bool
     */
    public function continueInCaseOfErrors(): bool;

    /**
     * @return bool
     */
    public function isLogEnabled(): bool;

    /**
     * @return int
     */
    public function getLogLevel(): int;
}
