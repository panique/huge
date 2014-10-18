<div class="content">
    <h1><?php echo Lang::__("note.edit.title");?></h1>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <?php if ($this->note) { ?>
        <form method="post" action="<?php echo URL; ?>note/editSave/<?php echo $this->note->note_id; ?>">
            <label><?php echo Lang::__("note.edit.label.notetext");?></label>
            <!-- we use htmlentities() here to prevent user input with " etc. break the HTML -->
            <input type="text" name="note_text" value="<?php echo htmlentities($this->note->note_text); ?>" />
            <input type="submit" value='<?php echo Lang::__("note.edit.submit");?>' />
        </form>
    <?php } else { ?>
        <p><?php echo Lang::__("note.notedoesnotexist");?></p>
    <?php } ?>
</div>
