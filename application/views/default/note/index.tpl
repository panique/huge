<div class="content">
    <h1>{$lang.new}</h1>
    <h3>{$lang.newexp}</h3>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    <form method="post" action="<?php echo URL;?>note/create">
        <label>{$lang.newtext}</label><input type="text" name="note_text" />
        <input type="submit" value='Create this note' autocomplete="off" />
    </form>

    <h1 style="margin-top: 50px;">{$lang.notelist}</h1>

    <table>
    {section name=n loop=$notes}
        <tr>
            <td>
                {$notes[n].note_text}
            </td>
            <td>
                <a href="{$site_path}note/edit/{$notes[n].note_id}">{$lang.EDIT}</a>
            </td>
            <td>
                <a href="{$site_path}note/delete/{$notes[n].note_id}">{$lang.DELETE}</a>
            </td>
        </tr>
    {sectionelse}
        {$lang.notenone}
    {/section}
    </table>
</div>
