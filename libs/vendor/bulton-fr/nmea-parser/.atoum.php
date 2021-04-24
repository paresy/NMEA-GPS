<?php

require __DIR__ . '/vendor/autoload.php';

use mageekguy\atoum;

$script->addDefaultReport();
$runner->addTestsFromDirectory(__DIR__.'/tests/src');

$cloverWriter = new atoum\writers\file('./clover.xml');
$cloverReport = new atoum\reports\asynchronous\clover;
$cloverReport->addWriter($cloverWriter);

$runner->addReport($cloverReport);
