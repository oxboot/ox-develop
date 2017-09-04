<?php

function ox_template($template, $data) {
    $m = new Mustache_Engine(['loader' => new Mustache_Loader_FilesystemLoader(OX_DIR . '/../templates')]);
    return $m->render($template, $data);
}
