<?php

$t->stop_on_fail = true;


$t->heading('Unit', 2);

require('test/reflection.php');
require('test/query_params.php');
require('test/redirection.php');
require('test/cookies.php');

$t->heading('Model', 2);

require('test/model_basic.php');

$t->heading('Controllers', 2);

require('test/dispatch_basic_root.php');
require('test/dispatch_basic_level1.php');
require('test/dispatch_basic_level2.php');
require('test/dispatch_fallback.php');
require('test/dispatch_http_method.php');
require('test/dispatch_args_specification.php');
require('test/dispatch_specifity.php');

require('test/dispatch_chained.php');
require('test/dispatch_chained_http_method.php');

$t->heading('View', 2);

 
?>
