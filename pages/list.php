<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Список загруженных файлов</title>
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>Список загруженных файлов</h1>
        </div>
        <ul class="list-group col-sm-6">
            <?php $uploads = file('php://stdin'); ?>
            <?php foreach ($uploads as $upload): ?>
                <li class="list-group-item">
                    <?= $upload; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</body>
</html>
