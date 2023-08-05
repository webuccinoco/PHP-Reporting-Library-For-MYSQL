<?php

// composer-scripts.php

// Define the paths for moving directories
$sourceDirs = [
    'sre_config' => 'sre_config',
    'sre_reports' => 'sre_reports',
    "examples" => "exampels",
    "db" => "db"
];

// Get the root path of the application
$rootPath = dirname(__DIR__);

foreach ($sourceDirs as $source => $destination) {
    $sourcePath = $rootPath . '/vendor/webuccinoco/sre-community/' . $source;
    $destinationPath = $rootPath . '/' . $destination;

    if (is_dir($sourcePath)) {
        // Move the directory
        rename($sourcePath, $destinationPath);
    }
}

