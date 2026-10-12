<?php

$json = __DIR__.'/admin_permissions.json';

if (! is_file($json)) {
    return [];
}

return json_decode((string) file_get_contents($json), true) ?: [];
