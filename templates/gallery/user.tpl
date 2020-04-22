{if count($images) == 0}
    <div>в этой галерее пока ничего нет<div/>
{else}
    <form method="POST" action="action_delete.php" enctype="multipart/form-data">
        <table border="1" cellspacing="20" cellpadding="10">
            <tr>
            {foreach $images as $key => $image}
                <td valign = "top">
                    {$image->show()}
                    <input type="checkbox" name="img_id_to_delete[]" value="{$image->get_id()}">
                </td>
                {if ($key + 1) is div by 4}
                    </tr><tr>
                {/if}
            {/foreach}
            </tr>
        </table>
        <button type="submit" name="submit_delete">удалить</button>
    </form>
{/if}