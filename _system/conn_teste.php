<?php




$time = microtime(true);
$con_time = microtime(true);
       include './Class_conn.php';
       $connAdm = new BD('p:127.0.0.1','adminterno','H+admin29.5','webtools');
       
$sel_time = microtime(true);
printf("Connect time: %f\nQuery time: %f\n",
       $con_time-$time,
       $sel_time-$con_time);  
mysqli_close($con);
?>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    