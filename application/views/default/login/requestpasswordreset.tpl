<div class="content">
    <h1>{$lang.resetpass}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <!-- request password reset form box -->
    <form method="post" action="{$site_path}login/requestpasswordreset_action" name="password_reset_form">
        <label for="password_reset_input_username">
            {$lang.resetpassinst}
        </label>
        <input id="password_reset_input_username" class="password_reset_input" type="text" name="user_name" required />
        <input type="submit"  name="request_password_reset" value="{$lang.resetpassalt}" />
    </form>
</div>
