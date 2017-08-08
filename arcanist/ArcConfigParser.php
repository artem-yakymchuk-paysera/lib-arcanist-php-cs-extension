<?php

namespace Paysera\Arcanist;

use Composer\Script\Event;

class ArcConfigParser
{
    const LOAD = 'vendor/paysera/lib-arcanist-php-cs-extension/src/';
    const LINT_ENGINE = 'PhpCsFixerLintEngine';

    public static function parseArcConfig(Event $event)
    {
        $arcConfigFile = '.arcconfig';

        $phpCsBinary = $event->getComposer()->getConfig()->get('bin-dir') . '/php-cs-fixer';

        $arcConfig = [];
        if (file_exists($arcConfigFile)) {
            $arcConfig = json_decode(file_get_contents($arcConfigFile), true);
        }

        if (!isset($arcConfig['load'])) {
            $arcConfig['load'] = [self::LOAD];
        }
        if (!isset($arcConfig['lint.engine'])) {
            $arcConfig['lint.engine'] = self::LINT_ENGINE;
        }
        if (!isset($arcConfig['lint.php_cs_fixer.fix_paths'])) {
            $arcConfig['lint.php_cs_fixer.fix_paths'] = [\PhpCsFixerLintEngine::SRC_DIRECTORY];
        }
        if (!isset($arcConfig['lint.php_cs_fixer.php_cs_binary'])) {
            $arcConfig['lint.php_cs_fixer.php_cs_binary'] = $phpCsBinary;
        }
        if (!isset($arcConfig['lint.php_cs_fixer.php_cs_file'])) {
            $arcConfig['lint.php_cs_fixer.php_cs_file'] = \PhpCsFixerLintEngine::PHP_CS_FILE;
        }

        file_put_contents($arcConfigFile, stripslashes(json_encode($arcConfig, JSON_PRETTY_PRINT)));
    }
}
