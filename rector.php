<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
        __DIR__.'/live.php',
    ])
    ->withPhpSets()
    ->withSets([
        LevelSetList::UP_TO_PHP_82,
    ]);
