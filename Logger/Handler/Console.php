<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterBase\Logger\Handler;

use Monolog\Handler\AbstractHandler;
use Symfony\Component\Console\Output\OutputInterface;

class Console extends AbstractHandler
{
    /**
     * @var OutputInterface
     */
    public $consoleOutput;

    /**
     * @param OutputInterface $output
     */
    public function setConsoleOutput(OutputInterface $output)
    {
        $this->consoleOutput = $output;
    }

    /**
     * @param array $record
     */
    public function handle(array $record)
    {
        if (isset($this->consoleOutput)) {
            $message = $this->getFormatter()->format($record);
            $this->consoleOutput->write($message);
        }
    }
}
