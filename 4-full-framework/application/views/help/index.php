<div class="content">
    <h1>Help</h1>

    <?php
    if (isset($this->feedback["success"])) {
        foreach ($this->feedback["success"] as $feedback) {
            echo '<div class="feedback success">'.$feedback.'</div>';
        }
    } elseif (isset($this->feedback["error"])) {
        foreach ($this->feedback["error"] as $feedback) {
            echo '<div class="feedback error">'.$feedback.'</div>';
        }
    }
    ?>
    
    <p>
        This box (everything between header and footer) is the content of views/help/index.php,
        so it's the help/index view. It's rendered by the index-method within the help-controller
        (in controllers/help.php). You can easily create a sub-page by putting a method into the
        controller and a view into the view folder. So, if you want to create something like
        a FAQ section within "Help", then put
        <span style="font-weight: bold;">function faq() { $this->view->render('help/faq'); }</span>
        into controllers/help.php and create an according view in views/help/, named "faq.php".
        Now you can use that by simply navigation to "help/faq" in your app: If your app is on
        http://localhost/myapp/ then this section is now on http://localhost/myapp/help/faq !
        Try it out...
    </p>
</div>
