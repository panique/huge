<div class="content">

    <h1>Edit user data</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<h3>'.$error.'</h3>';
        }

    }

    ?>

    <form action="edituseremail_action" method="post">

            <label>New email</label><input type="text" name="user_email" /><br />
            <label></label><input type="submit" />
    </form>
    
</div>