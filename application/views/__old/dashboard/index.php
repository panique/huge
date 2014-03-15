<div class="content">
    <h1>Dashboard</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <h3>This is an area that's only visible for logged in users</h3>

    Try to log out, an go to /dashboard/ again. You'll be redirected to /index/ as you are not logged in.
    <br/><br/>
    You can protect a whole section in your app within the according controller (here: controllers/dashboard.php)
    by placing <span style='font-style: italic;'>Auth::handleLogin();</span> into the constructor.
</div>
