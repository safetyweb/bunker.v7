<?php

$ret=shell_exec('/usr/bin/php -f "/srv/www/htdocs/_system/schedule.php?url=teste11111&nome=diogoteste&min=*/2&hora=&dias=&mes=&semana="');
echo $ret;

