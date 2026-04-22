<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$title|default:'Blogy'}</title>
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body>
        <header class="site-header">
            <div class="container">
                <a class="logo" href="/?route=home">Blogy.</a>
            </div>
        </header>

        <main class="container page-content">
            {block name=content}{/block}
        </main>

        <footer class="site-footer">
            <div class="container">
                <p>Copyright 2026. All Rights Reserved.</p>
            </div>
        </footer>
    </body>
</html>
