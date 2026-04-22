{extends file='layouts/base.tpl'}

{block name=content}
<article class="article-page">
    <img class="hero" src="{$article.image}" alt="{$article.title|escape}">
    <h1>{$article.title|escape}</h1>
    <p class="meta">{$article.published_at|date_format:"%d.%m.%Y"} | {$article.views} просмотров</p>
    <p class="article-description">{$article.description|escape}</p>

    <div class="article-categories">
        {foreach $article.categories as $category}
            <a href="/?route=category&id={$category.id}" class="chip">{$category.name|escape}</a>
        {/foreach}
    </div>

    <div class="article-content">
        {$article.content|nl2br}
    </div>
</article>

<section class="similar-section">
    <h2>Похожие статьи</h2>
    <div class="articles-grid">
        {foreach $similarArticles as $articleItem}
            {include file='partials/article_card.tpl' article=$articleItem}
        {foreachelse}
            <p>Похожих статей пока нет.</p>
        {/foreach}
    </div>
</section>
{/block}
