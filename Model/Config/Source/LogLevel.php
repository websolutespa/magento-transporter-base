<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Monolog\Logger;

class LogLevel implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        $levels = Logger::getLevels();
        foreach ($levels as $levelName => $levelCode) {
            $result[] = [
                'value' => $levelCode,
                'label' => $levelName
            ];
        }

        return $result;
    }
}
