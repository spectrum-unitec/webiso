<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Web ISO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- ================== BEGIN core-css ================== -->
    <link href="<?= base_url(); ?>assets/css/vendor.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/app.min.css" rel="stylesheet">
    <!-- ================== END core-css ================== -->

</head>

<body class='pace-done app-with-bg'>
    <!-- BEGIN #app -->
    <div id="app" class="app app-with-top-nav app-boxed-layout">
        <?= $this->include('Fe/layouts/header'); ?>

        <?= $this->include('Fe/layouts/navbar'); ?>

        <!-- BEGIN #content -->
        <div id="content" class="app-content">
            <?= $this->renderSection('content'); ?>
        </div>
        <!-- END #content -->

        <?= $this->include('Fe/layouts/footer'); ?>

          <script>
            document.addEventListener('contextmenu', e => e.preventDefault());

            document.addEventListener('keydown', function(e) {
                if (e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && ['I', 'C', 'J'].includes(e.key)) ||
                    (e.ctrlKey && e.key === 'U')) {
                    e.preventDefault();
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.addEventListener('keydown', function(e) {
                    // Print Screen
                    if (e.key === 'PrintScreen') {
                        e.preventDefault();
                        alert('Screenshot tidak diizinkan');
                        return false;
                    }

                    // Windows Snipping Tool (Win + Shift + S)
                    if (e.shiftKey && e.metaKey && e.key === 's') {
                        e.preventDefault();
                    }

                    // Mac Screenshot
                    if (e.metaKey && e.shiftKey && ['3', '4', '5'].includes(e.key)) {
                        e.preventDefault();
                    }
                });

            })
        </script>

        <?= $this->renderSection('pageScripts'); ?>
		
</body>

</html>