<?php

/**
 * The config file is optional. It accepts a return array with config options
 * Note: Never include more than one return statement, all options go within this single return array
 * Keep debugging disabled in production so errors are not displayed onscreen.
 * All config options: https://getkirby.com/docs/reference/system/options
 */
return [
    'debug' => false,
    'yaml.handler' => 'symfony', // already makes use of the more modern Symfony YAML parser: https://getkirby.com/docs/reference/system/options/yaml (will become the default in a future Kirby version), 
    'panel' => [
    'install' => false
  ],
  // 'cache' => [
  //   'pages' => [
  //     'active' => true,
  //     'ignore' => fn ($page) => $page->title()->value() === 'Do not cache me'
  //   ]
  // ]

];
