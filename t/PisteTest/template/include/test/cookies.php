<?php
$t->heading('cookies', 3);

$t->get('/cookie/set/' . time());
$time = isset($GLOBALS['timetomatch']) ? $GLOBALS['timetomatch'] : 'notimefound';
$t->get('/cookie/get/' . $time ); # pass time param
$t->is(isset($GLOBALS['mytime']) ? $GLOBALS['mytime'] : '', (string) $time, "got right value from cookie");
$t->get('/cookie/delete');
$t->get('/cookie/get');
$t->is(isset($GLOBALS['mytime']) ? $GLOBALS['mytime'] : '', '', "time value no longer in cookie");

?>
