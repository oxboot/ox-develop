<?php

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Console\Output\ConsoleOutput;

function ox_echo_info($message) {
    $output = new ConsoleOutput();
    $output->writeln('<fg=white>' . $message . '</>');
}

function ox_echo_success($message) {
    $output = new ConsoleOutput();
    $output->writeln('<fg=green>' . $message . '</>');
}

function ox_echo_error($message) {
    $output = new ConsoleOutput();
    $output->writeln('<fg=red>' . $message . '</>');
}

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
