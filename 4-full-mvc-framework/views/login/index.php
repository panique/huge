<div class="content">

    <h1>Login</h1>

    <?php 

    if (isset($this->errors)) {

        foreach ($this->errors as $error) {
            echo '<h3>'.$error.'</h3>';
        }

    }

    ?>

    <form action="login/login" method="post">

            <label>Login</label><input type="text" name="user_name" /><br />
            <label>Password</label><input type="password" name="user_password" /><br />
            <label></label><input type="submit" />
    </form>
    
</div>