<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

function ox_console($command) {
    $process = new Process($command);
    try {
        $process->mustRun();
        echo $process->getOutput();
        return true;
    } catch (ProcessFailedException $e) {
        echo $e->getMessage();
        return false;
    }
}
