<div class="content">
    <h1>{$lang.editemail}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <form action="{$site_path}login/edituseremail_action" method="post">
        <label>{$lang.editemailnew}</label>
        <input type="text" name="user_email" required />
        <input type="submit" value="{$lang.submit}" />
    </form>
</div>
