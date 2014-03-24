<div class="content">
    <h1>{$lang.showpub}</h1>
    <p>{$lang.showpubexp}</p>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    {if isset($user) && $user}
        <div>
            <span style="color: red;">{$lang.note}</span>
            <table class="overview-table">
                <tr class="{if $user->user_active == 0}inactive{else}active{/if}">
                    <td>{$user->user_id}</td>
                    <td class="avatar"><img src="{$user->user_avatar_link}" /></td>
                    <td>{$user->user_name}</td>
                    <td>{$user->user_email}</td>
                    <td>{$lang.active} {$user->user_active}</td>
                </tr> 
            </table>
        </div>
    {else}
        {$lang.shownone}
    {/if}
</div>
