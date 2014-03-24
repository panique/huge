<div class="content">
    <h1>{$lang.header}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <p>
        {$lang.indexexp}
    </p>

    <div>
        <span style="color: red;">{$lang.note}</span>
        <table class="overview-table">
            {foreach from=$users item=user}
                <tr class="{if $user->user_active == 0}inactive{else}active{/if}">
                    <td>{$user->user_id}</td>
                    <td class="avatar">
                        {if isset($user->user_avatar_link)}
                            <img src="{$user->user_avatar_link}" alt="{$lang.indexnoav}" />
                        {/if}
                    </td>
                    <td>{$user->user_name}</td>
                    <td>{$user->user_email}</td>
                    <td>{$lang.active} {$user->user_active}</td>
                    <td><a href="{$site_path}overview/showuserprofile/{$user->user_id}">{$lang.indexshow}</a></td>
                </tr>
            {foreachelse}
                {$lang.indexnousers}
            {/foreach}
        </table>
    </div>
</div>
