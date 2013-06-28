<div class="content">

    <h1>Edit user data</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<h3>'.$error.'</h3>';
        }

    }

    ?>

    <form action="editusername_action" method="post">

            <label>New username</label><input type="text" name="user_name" /><br />
            <label></label><input type="submit" />
    </form>
    
</div>