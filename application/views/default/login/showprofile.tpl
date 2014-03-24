<div class="content">
    <h1>{$lang.profile}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <div>
        {$lang.profileuser} {$username}
    </div>
    <div>
        {$lang.profilemail} {$email}
    </div>
    <div>
        {$lang.profilegrav} <img src='{$gravatarImageURL}' />
    </div>
    <div>
        {$lang.profileavatar} <img src='{$avatarFile}' />
    </div>
    <div>
        {$lang.profiletype} {$accountType}
    </div>
</div>
