<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<head>
    <title><?php
        if (isset($title)) echo htmlspecialchars($title) . ' - ';
        echo 'Δ.Β. Σκόρπιες Γνώσεις Mπόλικες';
    ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Language" content="el" />
    <meta name="Robots" content="index,follow" />
    <link rel="stylesheet" type="text/css" href="files/style.css" />
</head>
<body>
<div id="header">
    <h1>Δ.Β: Γνώσεις σκόρπιες</h1>
    <h4>Πράγματα σχετικά με Βωλάξ που γράφω ή θέλω να γράψω για να μην ξεχάσω</h4>
</div>

<div id="menu">
  <?php require('inc/menu.php'); ?>
  <hr>
</div>

<div id="admin">
  <?php require('inc/admin_inc.php'); ?>
</div>

<div id="content">