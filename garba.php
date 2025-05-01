<?php

      //gc_collect_cycles();
      //gc_disable();
      //gc_enable();
      //gc_enabled();
      //gc_mem_caches();

gc_collect_cycles();
    echo "Memory usage before: " . memory_get_usage() / 1024 . " KB" . PHP_EOL;
   
    for ($x = 0; $x <= 100000; $x++) {
   echo "The number is: $x <br>";
   } 
    
    gc_collect_cycles();
    echo "Memory usage after: " . memory_get_usage() / 1024 . " KB" . PHP_EOL;
    $e = microtime(true);
    echo 'Hydrated 10000 objects in ' . ($e - $s) . ' seconds' . PHP_EOL;zz

?>
