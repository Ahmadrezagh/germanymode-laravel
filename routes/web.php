<?php

$domainsPath = app_path('Domains');
$routeFiles = [];

if (is_dir($domainsPath)) {
    foreach (scandir($domainsPath) as $domain) {
        if ($domain === '.' || $domain === '..') {
            continue;
        }

        $routeFile = $domainsPath.DIRECTORY_SEPARATOR.$domain.DIRECTORY_SEPARATOR.'Routes'.DIRECTORY_SEPARATOR.'web.php';

        if (is_file($routeFile)) {
            $routeFiles[] = $routeFile;
        }
    }
}

foreach ($routeFiles as $routeFile) {
    require $routeFile;
}