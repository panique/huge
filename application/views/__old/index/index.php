<div class="content">
    <h1>Index</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <p>
        This box (everything between header and footer) is the content of views/index/index.php,
        so it's the index/index view.
        <br/>
        It's rendered by the index-method within the index-controller (in controllers/index.php).
    </p>
    <h3>General information on this little framework</h3>
    <p>
        "C'mon! Framework #1000 ? Why do we need this ?" Indeed, there are a lot of good
        (and a lot of bad, too) PHP frameworks on the web. But most of them have something in common:
        They don't have a proper login system. And even if they have, then it's using outdated
        password hashing/salting technologies, it's not future-proof, don't provide email verification,
        password reset etc.
        <br/><br/>
        This framework tries to
        <span style='font-weight: bold;'>focus on a proper, secure and up-to-date login system</span>,
        combined with an easy-to-use, easy-to-understand and highly usable framework structure.
        So, if you don't like the framework itself, feel free to merge the login-related actions,
        models and views into the framework of your choice.
    </p>
</div>
