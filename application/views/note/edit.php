<div class="content">
    <h1>Edit a note</h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <?php if ($this->note) { ?>
        <form method="post" action="<?php echo URL; ?>note/editSave/<?php echo $this->note->note_id; ?>">
            <label>Change text of note: </label>
            <!-- we use htmlentities() here to prevent user input with " etc. break the HTML -->
            <input type="text" name="note_text" value="<?php echo htmlentities($this->note->note_text); ?>" />
            <input type="submit" value='Change' />
        </form>
    <?php } else { ?>
        <p>This note does not exist.</p>
    <?php } ?>
</div>
