<div class="content">
    <h1>{$lang.changetype}</h1>
    <p>
        {$lang.changetypeexp}
    </p>
    <p>
        {$lang.changeline1}
    </p>

    {$feedback}

    <h2>{$lang.changecurrent}{$accounttype}</h2>
    <!-- basic implementation for two account type: type 1 and type 2 -->
    {if $accounttype == 1}
    <form action="./login/changeaccounttype_action" method="post">
        <label></label>
        <input type="submit" name="user_account_upgrade" value="{$lang.changeup}" />
    </form>
    {/if}
    {if $accounttype == 2}
    <form action="./login/changeaccounttype_action" method="post">
        <label></label>
        <input type="submit" name="user_account_downgrade" value="{$lang.changedown}" />
    </form>
    {/if}
</div>
