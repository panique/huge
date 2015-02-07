<div class="content">
    <div class="page-header text-center">
        <h1>NoteController/edit/:note_id<small></small></h1>
    </div>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <div class="well well-sm">
        <h3>Edit a note</h3>
        <?php if ($this->note) { ?>
            <form class="text-center" method="post" action="<?php echo Config::get('URL'); ?>note/editSave">
                <label>Change text of note: </label>
                <!-- we use htmlentities() here to prevent user input with " etc. break the HTML -->
                <input type="hidden" name="note_id" value="<?php echo htmlentities($this->note->note_id); ?>" />
                <input type="text" name="note_text" value="<?php echo htmlentities($this->note->note_text); ?>" />
                <input type="submit" value='Change' />
            </form>
        <?php } else { ?>
            <p>This note does not exist.</p>
        <?php } ?>
    </div>

</div>
</div>


