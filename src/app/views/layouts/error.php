<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?= CSS_DIR ?>global.css">
    <link rel="stylesheet" href="<?= CSS_DIR ?>error.css">
    <title>Page not found</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  </head>
  <body>
    <div id="content">
      <?php
        $included = include VIEWS_DIR . "pages/errors/404.php";
      ?>
    </div>
  </body>
</html>