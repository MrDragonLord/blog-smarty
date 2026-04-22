{extends file='layouts/base.tpl'}

{block name=content}
    <section class="category-page">
        <h1>{$category.name|escape}</h1>
        <p class="category-description">{$category.description|escape}</p>

        <form class="sort-form" method="get" action="/">
            <input type="hidden" name="route" value="category">
            <input type="hidden" name="id" value="{$category.id}">
            <label for="sort">Сортировка:</label>
            <select id="sort" name="sort" onchange="this.form.submit()">
                <option value="date" {if $sort === 'date'}selected{/if}>По дате публикации</option>
                <option value="views" {if $sort === 'views'}selected{/if}>По просмотрам</option>
            </select>
        </form>

        <div class="articles-grid">
            {foreach $articles as $article}
                {include file='partials/article_card.tpl' article=$article}
            {foreachelse}
                <p>В этой категории пока нет статей.</p>
            {/foreach}
        </div>

        {if $totalPages > 1}
            <nav class="pagination">
                {section name=page start=1 loop=$totalPages+1}
                    <a class="{if $smarty.section.page.index === $page}active{/if}"
                       href="/?route=category&id={$category.id}&sort={$sort}&page={$smarty.section.page.index}">
                        {$smarty.section.page.index}
                    </a>
                {/section}
            </nav>
        {/if}
    </section>
{/block}
