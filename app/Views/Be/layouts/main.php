<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Web ISO | Administrator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- ================== BEGIN core-css ================== -->
    <link href="<?= base_url(); ?>assets/css/vendor.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <!-- ================== END core-css ================== -->
    <?= $this->renderSection('pageStyles'); ?>

</head>

<body class='pace-top'>
    <!-- BEGIN #app -->
    <div id="app" class="app">
        <?= $this->include('Be/layouts/header'); ?>

        <?= $this->include('Be/layouts/navbar'); ?>

        <!-- BEGIN #content -->
        <div id="content" class="app-content">
            <?= $this->renderSection('content'); ?>
        </div>
        <!-- END #content -->

        <?= $this->include('Fe/layouts/footer'); ?>
        <?= $this->renderSection('pageScripts'); ?>

</body>

</html>