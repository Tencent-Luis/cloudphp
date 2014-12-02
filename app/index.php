<?php
/**
 * 自定义应用
 */
$script_filename = filter_input(INPUT_SERVER, 'SCRIPT_FILENAME');
require dirname(dirname($script_filename)) . '/CloudPHP/index.php';

