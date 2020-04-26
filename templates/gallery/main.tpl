{if count($images) == 0}
    <div>в этой галерее пока ничего нет<div/>
{else}
    <table border="1" cellspacing="20" cellpadding="10">
        <tr>
        {foreach $images as $key => $image}
            <td valign = "top">{include file ="image.tpl"}</td>
            {if ($key + 1) is div by 4}
                </tr><tr>
            {/if}
        {/foreach}
        </tr>
    </table>
{/if}