<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd4d7d7d11152077b8234a5ecb185a7c0
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Debug_Bar' => __DIR__ . '/../..' . '/classes/debug_bar.php',
        'Debug_Bar_Panel' => __DIR__ . '/../..' . '/classes/debug_bar_panel.php',
        'QM' => __DIR__ . '/../..' . '/classes/QM.php',
        'QM_Activation' => __DIR__ . '/../..' . '/classes/Activation.php',
        'QM_Backtrace' => __DIR__ . '/../..' . '/classes/Backtrace.php',
        'QM_CLI' => __DIR__ . '/../..' . '/classes/CLI.php',
        'QM_Collector' => __DIR__ . '/../..' . '/classes/Collector.php',
        'QM_Collectors' => __DIR__ . '/../..' . '/classes/Collectors.php',
        'QM_DB' => __DIR__ . '/../..' . '/classes/QM_DB.php',
        'QM_Dispatcher' => __DIR__ . '/../..' . '/classes/Dispatcher.php',
        'QM_Dispatchers' => __DIR__ . '/../..' . '/classes/Dispatchers.php',
        'QM_Hook' => __DIR__ . '/../..' . '/classes/Hook.php',
        'QM_Output' => __DIR__ . '/../..' . '/classes/Output.php',
        'QM_Output_Headers' => __DIR__ . '/../..' . '/output/Headers.php',
        'QM_Output_Headers_Overview' => __DIR__ . '/../..' . '/output/headers/overview.php',
        'QM_Output_Headers_PHP_Errors' => __DIR__ . '/../..' . '/output/headers/php_errors.php',
        'QM_Output_Headers_Redirects' => __DIR__ . '/../..' . '/output/headers/redirects.php',
        'QM_Output_Html' => __DIR__ . '/../..' . '/output/Html.php',
        'QM_Output_Html_Admin' => __DIR__ . '/../..' . '/output/html/admin.php',
        'QM_Output_Html_Assets' => __DIR__ . '/../..' . '/output/html/assets.php',
        'QM_Output_Html_Assets_Scripts' => __DIR__ . '/../..' . '/output/html/assets_scripts.php',
        'QM_Output_Html_Assets_Styles' => __DIR__ . '/../..' . '/output/html/assets_styles.php',
        'QM_Output_Html_Block_Editor' => __DIR__ . '/../..' . '/output/html/block_editor.php',
        'QM_Output_Html_Caps' => __DIR__ . '/../..' . '/output/html/caps.php',
        'QM_Output_Html_Conditionals' => __DIR__ . '/../..' . '/output/html/conditionals.php',
        'QM_Output_Html_DB_Callers' => __DIR__ . '/../..' . '/output/html/db_callers.php',
        'QM_Output_Html_DB_Components' => __DIR__ . '/../..' . '/output/html/db_components.php',
        'QM_Output_Html_DB_Dupes' => __DIR__ . '/../..' . '/output/html/db_dupes.php',
        'QM_Output_Html_DB_Queries' => __DIR__ . '/../..' . '/output/html/db_queries.php',
        'QM_Output_Html_Debug_Bar' => __DIR__ . '/../..' . '/output/html/debug_bar.php',
        'QM_Output_Html_Environment' => __DIR__ . '/../..' . '/output/html/environment.php',
        'QM_Output_Html_HTTP' => __DIR__ . '/../..' . '/output/html/http.php',
        'QM_Output_Html_Headers' => __DIR__ . '/../..' . '/output/html/headers.php',
        'QM_Output_Html_Hooks' => __DIR__ . '/../..' . '/output/html/hooks.php',
        'QM_Output_Html_Languages' => __DIR__ . '/../..' . '/output/html/languages.php',
        'QM_Output_Html_Logger' => __DIR__ . '/../..' . '/output/html/logger.php',
        'QM_Output_Html_Overview' => __DIR__ . '/../..' . '/output/html/overview.php',
        'QM_Output_Html_PHP_Errors' => __DIR__ . '/../..' . '/output/html/php_errors.php',
        'QM_Output_Html_Request' => __DIR__ . '/../..' . '/output/html/request.php',
        'QM_Output_Html_Theme' => __DIR__ . '/../..' . '/output/html/theme.php',
        'QM_Output_Html_Timing' => __DIR__ . '/../..' . '/output/html/timing.php',
        'QM_Output_Html_Transients' => __DIR__ . '/../..' . '/output/html/transients.php',
        'QM_Output_Raw' => __DIR__ . '/../..' . '/output/Raw.php',
        'QM_Output_Raw_Cache' => __DIR__ . '/../..' . '/output/raw/cache.php',
        'QM_Output_Raw_Conditionals' => __DIR__ . '/../..' . '/output/raw/conditionals.php',
        'QM_Output_Raw_DB_Queries' => __DIR__ . '/../..' . '/output/raw/db_queries.php',
        'QM_Output_Raw_HTTP' => __DIR__ . '/../..' . '/output/raw/http.php',
        'QM_Output_Raw_Logger' => __DIR__ . '/../..' . '/output/raw/logger.php',
        'QM_Output_Raw_Transients' => __DIR__ . '/../..' . '/output/raw/transients.php',
        'QM_PHP' => __DIR__ . '/../..' . '/classes/PHP.php',
        'QM_Plugin' => __DIR__ . '/../..' . '/classes/Plugin.php',
        'QM_Timer' => __DIR__ . '/../..' . '/classes/Timer.php',
        'QM_Util' => __DIR__ . '/../..' . '/classes/Util.php',
        'QueryMonitor' => __DIR__ . '/../..' . '/classes/QueryMonitor.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitd4d7d7d11152077b8234a5ecb185a7c0::$classMap;

        }, null, ClassLoader::class);
    }
}
