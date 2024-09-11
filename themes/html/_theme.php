<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">

    <title><?= (isset($title) ? $title : "") ?>Framework PHP</title>

    <link type="text/css" href="<?= theme("/assets/style.css") ?>" rel="stylesheet">

</head>

<body>
    <?= $v->section("content"); ?>
</body>
</html>
<script src="<?= theme("/assets/scripts.js") ?>"></script>