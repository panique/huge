<?php

/**
 * This is basically a simple CRUD demonstration.
 */

class Note_Model extends Model
{
    public $errors = array();
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllNotes()
    {
        
		$sth = $this->db->prepare("SELECT user_id, note_id, note_text
                                           FROM note
                                           WHERE user_id = :user_id ;");
		$sth->execute(array(':user_id' => $_SESSION['user_id']));    
                
                return $sth->fetchAll();
        
    }    
    
    public function getNote($note_id)
    {
        
        $sth = $this->db->prepare("SELECT * FROM note WHERE user_id = :user_id AND note_id = :note_id;");
        $sth->execute(array(
            ':user_id' => $_SESSION['user_id'],
            ':note_id' => $note_id));    

        return $sth->fetch();
    }
    
    
    public function create($note_text)
    {
        $sth = $this->db->prepare("INSERT INTO note
                                   (note_text, user_id)
                                   VALUES (:note_text, :user_id);");
        $sth->execute(array(
            ':note_text' => $note_text,
            ':user_id' => $_SESSION['user_id']));   
        
        $count =  $sth->rowCount();
        if ($count == 1) {
            return true;
        } else {
            $this->errors[] = FEEDBACK_NOTE_CREATION_FAILED;
            return false;
        }
    }
    
    public function editSave($note_id, $note_text)
    {
                
        $sth = $this->db->prepare("UPDATE note 
                                   SET note_text = :note_text
                                   WHERE note_id = :note_id AND user_id = :user_id;");
        $sth->execute(array(
            ':note_id' => $note_id,
            ':note_text' => $note_text,
            ':user_id' => $_SESSION['user_id']));   
        
        $count =  $sth->rowCount();
        if ($count == 1) {
            return true;
        } else {
            $this->errors[] = FEEDBACK_NOTE_EDITING_FAILED;
            return false;
        }                
                
                
    }
    
    public function delete($note_id)
    {
        $sth = $this->db->prepare("DELETE FROM note 
                                   WHERE note_id = :note_id AND user_id = :user_id;");
        $sth->execute(array(
            ':note_id' => $note_id,
            ':user_id' => $_SESSION['user_id']));   
        
        $count =  $sth->rowCount();
        
        if ($count == 1) {
            return true;
        } else {
            $this->errors[] = FEEDBACK_NOTE_DELETION_FAILED;
            return false;
        }     
    }
}