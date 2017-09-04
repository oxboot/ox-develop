<?php

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

function ox_template($template, $data) {
    $m = new Mustache_Engine(['loader' => new Mustache_Loader_FilesystemLoader(OX_DIR . '/../templates')]);
    $m->render($template, $data);
}
