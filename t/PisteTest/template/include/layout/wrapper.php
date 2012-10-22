<!DOCTYPE html>
<html lang="en">
<head>
    <title>PisteTest</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="author" content="Alex Monney">
    <meta name="copyright" content="&copy; Alex Monney">
    <meta name="keywords" content="Alex Monney, fishion">
    <meta name="description" content="Test project for the Piste PHP web development framework">
    <style type="text/css">
    .pass {background: #DFD;}
    .fail {background: #FDD;}
    </style>
</head>
<body>

<?php require_once('testlist.php');
      ob_start();
      test();
      $testoutput = ob_get_clean(); ?>

<h1><?php global $pagetitle; echo (isset($pagetitle) ? $pagetitle : 'No title') ?></h1>

<?php echo $Pcontent;
      echo $testoutput; ?>

<div id="footer">&copy; <?php echo date("Y"); ?> Alex Monney</div>

</body>
</html>
