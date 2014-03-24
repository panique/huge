<div class="content">
    <h1>{$lang.edit}</h1>

    <!-- echo out the system feedback (error and success messages) -->
    {$feedback}

    {if isset($note) && $note}
        <form method="post" action="{$site_path}note/editSave/{$note.noteid}">
            <label>{$lang.editchange}</label>
            <!-- we use htmlentities() here to prevent user input with " etc. break the HTML -->
            <input type="text" name="note_text" value="{$note.note_text}" />
            <input type="submit" value='Change' />
        </form>
    {else}
        <p>{$lang.editnone}</p>
    {/if}
</div>
