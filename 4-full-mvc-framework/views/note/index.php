<div class="content">
    
    <h1>My Notes</h1>
    <h3>This screen shows/handles just the user's notes</h3>

    <form method="post" action="<?php echo URL;?>note/create">
        <label>Text of new note: </label><input type="text" name="note_text" />
        <input type="submit" value='Create this note' />
    </form>

    <hr />

    <table>
    <?php
        foreach($this->notes as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value->note_text . '</td>';
            echo '<td><a href="'. URL . 'note/edit/' . $value->note_id.'">Edit</a></td>';
            echo '<td><a href="'. URL . 'note/delete/' . $value->note_id.'">Delete</a></td>';
            echo '</tr>';
        }
    ?>
    </table>
    
</div>