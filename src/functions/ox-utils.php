<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

function ox_echo_info($message) {
    echo '<info>' . $message . '</info>' . PHP_EOL;
}

function ox_console($command) {
    $process = new Process($command);
    try {
        $process->mustRun();
        echo $process->getOutput();
    } catch (ProcessFailedException $e) {
        echo $e->getMessage();
    }
}