<?php
date_default_timezone_set('Etc/GMT+3');

$repoPath = __DIR__;

chdir($repoPath);

$version = trim(shell_exec('git rev-parse --short HEAD'));
$author = trim(shell_exec('git log -1 --pretty=format:%an'));
$date = trim(shell_exec('git log -1 --pretty=format:%cd'));
$comment = trim(shell_exec('git log -1 --pretty=format:%s'));
$branch = trim(shell_exec('git rev-parse --abbrev-ref HEAD'));

$qtd = 500;
$readme = shell_exec('git log --pretty=format:"%h - %an, %ar : %s" -n ' . $qtd);


$commit_url = "https://github.com/safetyweb/bunker/commits/" . $branch;


echo "<pre>";
echo "---| INFORMAÇÃO DO SISTEMA |----------------------------------------------------------------" . PHP_EOL;
echo "Versão: " . $version . PHP_EOL;
echo "Autor: " . $author . PHP_EOL;
echo "Data/Hora: " . date('d/m/Y H:i:s', strtotime($date)) . PHP_EOL;
echo "Branch: $branch" . PHP_EOL;
echo PHP_EOL . "$comment" . PHP_EOL . PHP_EOL;
echo "--------------------------------------------------------------------------------------------" . PHP_EOL;
echo "</pre>";

echo "<pre>";
echo "---| README |-------------------------------------------------------------------------------" . PHP_EOL;
echo "Mostrando as últimas " . $qtd . " modificações. Para ver mais, acesse: <a href='$commit_url' target='_blank'>$commit_url</a>" . PHP_EOL;
echo PHP_EOL . "$readme" . PHP_EOL;
echo "--------------------------------------------------------------------------------------------" . PHP_EOL;
echo "</pre>";
