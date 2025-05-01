<?php
date_default_timezone_set('Etc/GMT+3');

$repoPath = dirname(__DIR__);

$branch = 'main';

$commands = [
    "cd {$repoPath}",
    "git fetch --all",
    "git reset --hard origin/{$branch}",
    "git pull origin {$branch}"
];

$output = '';
foreach ($commands as $command) {
    $output .= shell_exec($command . ' 2>&1') . PHP_EOL;
}

echo "<pre>";
if ($output) {
    echo $output;
} else {
    echo "Deploy realizado com sucesso!";
}
echo "</pre>";

include("version.php");
