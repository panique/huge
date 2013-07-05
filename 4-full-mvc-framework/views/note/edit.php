<div class="content">
    
    <h1>Edit a note</h1>

    <form method="post" action="<?php echo URL; ?>note/editSave/<?php echo $this->note->note_id; ?>">
        <label>Change text of note: </label>
        <input type="text" name="note_text" value="<?php echo $this->note->note_text; ?>" />
        <input type="submit" value='Change' />
    </form>
    
</div>    