<article class="article-card">
    <a href="/?route=article&id={$article.id}">
        <img src="{$article.image}" alt="{$article.title|escape}">
    </a>
    <h3><a href="/?route=article&id={$article.id}">{$article.title|escape}</a></h3>
    <p class="meta">{$article.published_at|date_format:"%d.%m.%Y"} | {$article.views} просмотров</p>
    <p class="article-excerpt">{$article.description|escape}</p>
    <a class="link" href="/?route=article&id={$article.id}">Continue Reading</a>
</article>
