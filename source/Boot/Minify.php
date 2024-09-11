<?php

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

/**
 * CSS Assets
 */
$minCSS = new CSS();

$cssDir = scandir(CONF_VIEW_PATH . "/" . CONF_VIEW_THEME . "/assets/css");
foreach ($cssDir as $css) {
    $cssFile = CONF_VIEW_PATH . "/" . CONF_VIEW_THEME . "/assets/css/{$css}";

    if (is_file($cssFile) && pathinfo($cssFile)['extension'] == "css") {
        $minCSS->add($cssFile);
    }
}
$minCSS->minify(CONF_VIEW_PATH . "/" . CONF_VIEW_THEME . "/assets/style.css");

/**
 * JS Assets
 */
$minJS = new JS();

$jsDir = scandir(CONF_VIEW_PATH . "/" . CONF_VIEW_THEME . "/assets/js");
foreach ($jsDir as $js) {
    $jsFile =
        CONF_VIEW_PATH . "/" . CONF_VIEW_THEME . "/assets/js/{$js}";

    if (is_file($jsFile) && pathinfo($jsFile)['extension'] == "js") {
        $minJS->add($jsFile);
    }
}
$minJS->minify(CONF_VIEW_PATH . "/" . CONF_VIEW_THEME . "/assets/scripts.js");
