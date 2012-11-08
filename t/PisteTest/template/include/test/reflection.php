<?php

$t->heading('reflection subclasses', 3);

$rc = new Piste\ReflectionClass('\PisteTest\Controller\Reflection');
$t->is(count($rc->getNonInheritedMethods()), 6, "Right number of non-inherited methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PROTECTED)), 1, "Right number of non-inherited private methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PUBLIC)), 2, "Right number of non-inherited public methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PRIVATE)), 3, "Right number of non-inherited protected methods in total");
$t->is(count($rc->getNonInheritedMethods(ReflectionMethod::IS_PUBLIC + ReflectionMethod::IS_PRIVATE)), 5, "Right number of non-inherited public + protected methods in total");

?>
