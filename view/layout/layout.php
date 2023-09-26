<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/assets/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/public/assets/lib/font-awesome/css/all.css">
    <link rel="stylesheet" href="/public/assets/lib/alertify/alertify.min.css">
    <link rel="stylesheet" href="/public/assets/lib/alertify/default.min.css">
    <link rel="stylesheet" href="/public/assets/lib/data-table/dataTables.min.css">
    <link rel="stylesheet" href="/public/assets/lib/css/style.css">
    <title>Layout MVC</title>
</head>

<body>
    <div>
        <div class="row" style=" margin-top: 20px; margin-left: 15px">
            <?php echo $screen ?>
        </div>
    </div>


    <script src="/public/assets/lib/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/public/assets/lib/font-awesome/js/all.js"></script>
    <script src="/public/assets/lib/jquery/jquery.min.js"></script>
    <script src="/public/assets/lib/sweetalert/sweetalert2.all.min.js"></script>
    <script src="/public/assets/lib/alertify/alertify.min.js"></script>
    <script src="/public/assets/lib/data-table/dataTables.min.js"></script>
    <script src="/public/assets/lib/js/script.js"></script>
    <script>
        const index = new Index('<?= isset($bills)  ? '/bill' : '/archive' ?>', <?= isset($ids) ? json_encode($ids) : 'null' ?>);
    </script>
</body>

</html>