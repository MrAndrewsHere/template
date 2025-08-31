<?php

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return RectorConfig::configure()
    ->withRules([
        DeclareStrictTypesRector::class,
        ReadOnlyPropertyRector::class,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        naming: true,
        earlyReturn: true,
        phpunitCodeQuality: true
    )
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/app/Swagger',
    ])
    ->withParallel()
    ->withImportNames();
