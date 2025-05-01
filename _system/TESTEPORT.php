<?php
ignore_user_abort (TRUE);

$x=0;
while ($x++ < 20) {
    ob_flush();
    flush(); 
   print $x.',<br>';
   sleep (1);
}

switch (connection_status ()) {
                                case CONNECTION_NORMAL:
                                   $status = 'Normal';
                                   break;
                                case CONNECTION_ABORTED:
                                   $status = 'User Abort';
                                   break;
                                case CONNECTION_TIMEOUT:
                                   $status = 'Max Execution Time exceeded';
                                   break;
                                case (CONNECTION_ABORTED & CONNECTION_TIMEOUT):
                                   $status = 'Aborted and Timed Out';
                                   break;
                                default:
                                   $status = 'Unknown';
                                   break;
}
echo $status;
file_put_contents('test.txt',$status);
?>
