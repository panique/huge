<div class="content">
    <h1><?php echo Lang::__("note.index.title");?></h1>
    <h3><?php echo Lang::__("note.index.introduction");?></h3>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form method="post" action="<?php echo URL;?>note/create">
        <label><?php echo Lang::__("note.index.label.notetext");?></label><input type="text" name="note_text" />
        <input type="submit" value='<?php echo Lang::__("note.index.submit");?>' autocomplete="off" />
    </form>

    <h1 style="margin-top: 50px;"><?php echo Lang::__("note.index.notelist.title");?></h1>

    <table>
    <?php
        if ($this->notes) {
            foreach($this->notes as $key => $value) {
                echo '<tr>';
                echo '<td>' . htmlentities($value->note_text) . '</td>';
                echo '<td><a href="'. URL . 'note/edit/' . $value->note_id.'">'.Lang::__("note.link.edit").'</a></td>';
                echo '<td><a href="'. URL . 'note/delete/' . $value->note_id.'">'.Lang::__("note.link.delete").'</a></td>';
                echo '</tr>';
            }
        } else {
            echo Lang::__("note.nonotesyet");
        }
    ?>
    </table>
</div>
