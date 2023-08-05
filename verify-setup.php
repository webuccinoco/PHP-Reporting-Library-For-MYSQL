<?php

// verify-setup.php

// Define the paths for the required directories
$requiredDirectories  = [
    'sre_config' => 'sre_config',
    'sre_reports' => 'sre_reports',
    "examples" => "exampels",
    "db" => "db"
];
$getting_started_url = "https://mysqlreports.com/engine/documentation/index.php?post=community_install";
$required_file = "sre_config/config.php";

// Get the root path of the application
$rootPath = dirname(__DIR__);

$missingDirectories = [];

foreach ($requiredDirectories as $source => $destination) {
    $destinationPath = $rootPath . '/' . $destination;

    if (!is_dir($destinationPath)) {
        $missingDirectories[] = $destination;
    }
}
if(!file_exists($rootPath/$required_file)){
    $missingDirectories[] = $required_file;
}

if (empty($missingDirectories)) {
    echo "Setup verification successful. Your package is properly configured. You can visit $getting_started_url for getting started \n";
    
} else {
    echo "Setup verification failed. The following files or directories are missing:\n";
    foreach ($missingDirectories as $directory) {
        echo "- {$directory}\n";
    }
   echo "Please add these directories to your project manually:\n";
    echo "1. Locate the 'sre-community' package directory within '/vendor/webuccinoco/sre-community'.\n";
    echo "2. Inside the package directory, find the missing directories: 'sre_config' and 'sre_reports'.\n";
    echo "3. Copy each missing directory to your project's root directory.\n";
    echo "4. Ensure that the copied directories are at the same level as your application files.\n";
    echo "5. Make sure the 'config.php' file is in the 'config' directory.\n";
}


