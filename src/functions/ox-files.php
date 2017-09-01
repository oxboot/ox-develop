<?php

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

function ox_mkdir($dir) {
    $fs = new Filesystem();
    try {
        $fs->mkdir($dir, 0755);
        return true;
    } catch (IOExceptionInterface $e) {
        echo $e;
        return false;
    }
}

function ox_chown($dir, $owner, $group) {
    $fs = new Filesystem();
    try {
        $fs->chown($dir, $owner, true);
        $fs->chgrp($dir, $group, true);
        return true;
    } catch (IOExceptionInterface $e) {
        echo $e;
        return false;
    }
}
