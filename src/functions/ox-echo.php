<?php

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
