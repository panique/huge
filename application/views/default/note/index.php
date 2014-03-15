<div class="content">
    <h1>Create new note</h1>
    <h3>"Notes" are just an example of how to create, show (read), edit (update) and delete things. CRUD, you know...</h3>

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form method="post" action="<?php echo URL;?>note/create">
        <label>Text of new note: </label><input type="text" name="note_text" />
        <input type="submit" value='Create this note' autocomplete="off" />
    </form>

    <h1 style="margin-top: 50px;">List of your notes</h1>

    <table>
    <?php
        if ($this->notes) {
            foreach($this->notes as $key => $value) {
                echo '<tr>';
                echo '<td>' . htmlentities($value->note_text) . '</td>';
                echo '<td><a href="'. URL . 'note/edit/' . $value->note_id.'">Edit</a></td>';
                echo '<td><a href="'. URL . 'note/delete/' . $value->note_id.'">Delete</a></td>';
                echo '</tr>';
            }
        } else {
            echo 'No notes yet. Create some !';
        }
    ?>
    </table>
</div>
