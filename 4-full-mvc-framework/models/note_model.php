<?php

/**
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class Note_Model extends Model
{
    /**
     * @var array
     */
    public $errors = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Getter for all notes (notes are an implementation of example data, in a real world application this
     * would be data that the user has created)
     * @return array
     */
    public function getAllNotes()
    {
        $sql = "SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id";
		$query = $this->db->prepare($sql);
		$query->execute(array(':user_id' => $_SESSION['user_id']));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    /**
     * Getter for a single note
     * @param $note_id id of the specific note
     * @return mixed
     */
    public function getNote($note_id)
    {
        $sql = "SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id AND note_id = :note_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':user_id' => $_SESSION['user_id'],
            ':note_id' => $note_id));    

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Setter for a note (create)
     * @param $note_text note text that will be created
     * @return bool feedback (was the note created properly ?)
     */
    public function create($note_text)
    {
        $sql = "INSERT INTO notes (note_text, user_id) VALUES (:note_text, :user_id)";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':note_text' => $note_text,
            ':user_id' => $_SESSION['user_id']));   
        
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        } else {
            $this->errors[] = FEEDBACK_NOTE_CREATION_FAILED;
            return false;
        }
    }

    /**
     * Setter for a note (update)
     * @param $note_id id of the specific note
     * @param $note_text new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public function editSave($note_id, $note_text)
    {
        $sql = "UPDATE notes SET note_text = :note_text WHERE note_id = :note_id AND user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':note_id' => $note_id,
            ':note_text' => $note_text,
            ':user_id' => $_SESSION['user_id']));   
        
        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        } else {
            $this->errors[] = FEEDBACK_NOTE_EDITING_FAILED;
            return false;
        }
    }

    /**
     * Deletes a specific note
     * @param $note_id id of the note
     * @return bool feedback (was the note deleted properly ?)
     */
    public function delete($note_id)
    {
        $sql = "DELETE FROM notes WHERE note_id = :note_id AND user_id = :user_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(
            ':note_id' => $note_id,
            ':user_id' => $_SESSION['user_id']));   
        
        $count =  $query->rowCount();
        
        if ($count == 1) {
            return true;
        } else {
            $this->errors[] = FEEDBACK_NOTE_DELETION_FAILED;
            return false;
        }     
    }
}