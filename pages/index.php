<!DOCTYPE html>
<html lang="ru">
<head>
    <style>
        body { padding-top: 50px }
    </style>
    <meta charset="UTF-8">
    <title>Приложение на ReactPHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>Загрузка файла</h1>
        </div>
        <div class="col-sm-12">
            <form action="/upload"
                  method="post"
                  class="justify-content-center"
                  enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Загрузить файл:</label>
                    <input name="file"
                           id="file"
                           type="file"
                           accept="image/x-png,image/jpeg"
                    />
                </div>
                <button type="submit" class="btn btn-primary">
                    Отправить
                </button>
            </form>
        </div>
        <div class="col-sm-12">
            <hr>
            <h3>Уже загруженные файлы:</h3>
            <ul class="list-group col-sm-6">
                <?php $uploads = file('php://stdin'); ?>
                <?php if (empty($uploads)): ?>
                    Нет файлов.
                <?php else: ?>
                    <ul class="list-group col-sm-6">
                        <?php foreach ($uploads as $upload): ?>
                            <li class="list-group-item">
                                <img class="col-sm-2"
                                     src="uploads/<?= $upload; ?>">
                                <a href="download/uploads/<?= $upload; ?>">
                                    <?= $upload; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>
</body>
</html>