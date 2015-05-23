<div class="container">
    <h1>NoteController/index</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>

        <h3>What happens here ?</h3>
        <p>
            This is just a simple CRUD implementation. Creating, reading, updating and deleting things.
        </p>
        <p>
            <form method="post" action="<?php echo Config::get('URL');?>note/create">
                <label>Text of new note: </label><input type="text" name="note_text" />
                <input type="submit" value='Create this note' autocomplete="off" />
            </form>
        </p>

        <?php if ($this->notes) { ?>
            <table class="note-table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Note</td>
                    <td>EDIT</td>
                    <td>DELETE</td>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($this->notes as $key => $value) { ?>
                        <tr>
                            <td><?= $value->note_id; ?></td>
                            <td><?= htmlentities($value->note_text); ?></td>
                            <td><a href="<?= Config::get('URL') . 'note/edit/' . $value->note_id; ?>">Edit</a></td>
                            <td><a href="<?= Config::get('URL') . 'note/delete/' . $value->note_id; ?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div>No notes yet. Create some !</div>
            <?php } ?>
    </div>
</div>
