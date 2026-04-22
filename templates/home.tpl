{extends file='layouts/base.tpl'}

{block name=content}
    {if !$categories}
        <p>Пока нет опубликованных статей.</p>
    {else}
        {foreach $categories as $category}
            <section class="category-section">
                <div class="section-head">
                    <div>
                        <h2>{$category.name|escape}</h2>
                    </div>
                    <a class="link" href="/?route=category&id={$category.id}">View All</a>
                </div>

                <div class="articles-grid">
                    {foreach $category.articles as $article}
                        {include file='partials/article_card.tpl' article=$article}
                    {/foreach}
                </div>
            </section>
        {/foreach}
    {/if}
{/block}
