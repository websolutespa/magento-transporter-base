<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model;

use Exception;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;

class SetAreaCode
{
    /**
     * @var State
     */
    private $state;

    /**
     * @param State $state
     */
    public function __construct(
        State $state
    ) {
        $this->state = $state;
    }

    /**
     * @param string $areaCode
     * @return void
     * @throws LocalizedException
     */
    public function execute(string $areaCode): void
    {
        try {
            $this->state->getAreaCode();
        } catch (Exception $e) {
            $this->state->setAreaCode($areaCode);
        }
    }
}
