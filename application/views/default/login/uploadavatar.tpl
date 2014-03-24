<div class="content">
    <h1>{$lang.avatar}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <form action="{$site_path}login/uploadavatar_action" method="post" enctype="multipart/form-data">
        <label for="avatar_file">{$lang.avatarexp}</label>
        <input type="file" name="avatar_file" required />
        <!-- max size 5 MB (as many people directly upload high res pictures from their digital cameras) -->
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        <input name="submit" type="submit" value="{$lang.avatarupload}" />
    </form>
</div>
