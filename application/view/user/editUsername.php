<div class="container">
    <h1>UserController/editUsername</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <div class="box">
        <h2>Change your username</h2>

        <form action="<?php echo Config::get('URL'); ?>user/editUserName_action" method="post">
            <!-- btw http://stackoverflow.com/questions/774054/should-i-put-input-tag-inside-label-tag -->
            <label>
                New username: <input type="text" name="user_name" required />
            </label>
			<!-- set CSRF token at the end of the form -->
			<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
            <input type="submit" value="Submit" />
        </form>
    </div>
</div>
