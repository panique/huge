<div class="content">
    <h1>{$lang.edituser}</h1>

    <!-- echo out the system feedback (error and success messages) -->
   {$feedback}

    <form action="{$site_path}editusername_action" method="post">
        <label>{$lang.editusernew}</label>
        <input type="text" name="user_name" required />
        <input type="submit" value="{$lang.submit}" />
    </form>
</div>
