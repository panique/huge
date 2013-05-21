<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->

<div>
    <!-- if you need users's information, just put them into the $_SESSION variable and output them here -->
    Hey, <?php echo $_SESSION['user_name']; ?>. You are logged in. Try to close this browser tab and open it again. Still logged in! ;)
</div>

<div>
    <!-- because people were asking: "index.php?logout" is just my simplicated form of "index.php?logout=true" -->
    <a href="index.php?logout">Logout</a>
    
    <a href="edit.php">Edit user data</a>
</div>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->