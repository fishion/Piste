<?php
$t->heading('basic model access', 3);

$t->get('/model/');
$t->is(isset($GLOBALS['testdata']) ? $GLOBALS['testdata'] : '', 'TestData', 'Managed to get test data out of model');

?>
