<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
<style>li > a {
        font-size: x-large;
    } </style>
<div class="container">
    <div class="row text-center mt-5">
        <h1><?php echo $title ?></h1>
    </div>
    <?php if (isset($error)): ?>
        <div class="row alert alert-danger w-50 text-center mt-4">
            <h5><?php echo $error ?></h5>
        </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="row alert alert-success w-50 text-center mt-4">
            <h5><?php echo $success ?></h5>
        </div>
    <?php endif; ?>
    <?php echo $content ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>