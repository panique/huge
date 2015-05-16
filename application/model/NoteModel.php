<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class NoteModel
{
    public static $getAllNotesQuery = null;
    public static $getNoteQuery = null;
    public static $createNoteQuery = null;
    public static $updateNoteQuery = null;
    public static $deleteNoteQuery = null;
    /**
     * Get all notes (notes are just example data that the user has created)
     * @return array an array with several objects (the results)
     */
    public static function getAllNotes()
    {
        if(self::$getAllNotesQuery === null) {
            self::$getAllNotesQuery = DatabaseFactory::getFactory()
                ->getConnection()
                ->prepare("SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id");
        }
        self::$getAllNotesQuery->execute(array(':user_id' => Session::get('user_id')));

        // fetchAll() is the PDO method that gets all result rows
        return self::$getAllNotesQuery->fetchAll();
    }

    /**
     * Get a single note
     * @param int $note_id id of the specific note
     * @return object a single object (the result)
     */
    public static function getNote($note_id)
    {
        if(self::$getNoteQuery === null) {
            self::$getNoteQuery = DatabaseFactory::getFactory()
                ->getConnection()
                ->prepare("SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id AND note_id = :note_id LIMIT 1");
        }
        self::$getNoteQuery->execute(array(':user_id' => Session::get('user_id'), ':note_id' => $note_id));

        // fetch() is the PDO method that gets a single result
        return self::$getNoteQuery->fetch();
    }

    /**
     * Set a note (create a new one)
     * @param string $note_text note text that will be created
     * @return bool feedback (was the note created properly ?)
     */
    public static function createNote($note_text)
    {
        if (!$note_text || strlen($note_text) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }
        if(self::$createNoteQuery === null) {
            self::$createNoteQuery = DatabaseFactory::getFactory()
                ->getConnection()
                ->prepare("INSERT INTO notes (note_text, user_id) VALUES (:note_text, :user_id)");
        }
        self::$createNoteQuery->execute(array(':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if (self::$createNoteQuery->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    /**
     * Update an existing note
     * @param int $note_id id of the specific note
     * @param string $note_text new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public static function updateNote($note_id, $note_text)
    {
        if (!$note_id || !$note_text) {
            return false;
        }
        if(self::$updateNoteQuery === null) {
            self::$updateNoteQuery = DatabaseFactory::getFactory()
                ->getConnection()
                ->prepare("UPDATE notes SET note_text = :note_text WHERE note_id = :note_id AND user_id = :user_id LIMIT 1");
        }

        self::$updateNoteQuery->execute(array(':note_id' => $note_id, ':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if (self::$updateNoteQuery->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Delete a specific note
     * @param int $note_id id of the note
     * @return bool feedback (was the note deleted properly ?)
     */
    public static function deleteNote($note_id)
    {
        if (!$note_id) {
            return false;
        }
        if(self::$deleteNoteQuery === null) {
            self::$deleteNoteQuery = DatabaseFactory::getFactory()
                ->getConnection()
                ->prepare("DELETE FROM notes WHERE note_id = :note_id AND user_id = :user_id LIMIT 1");
        }

        self::$deleteNoteQuery->execute(array(':note_id' => $note_id, ':user_id' => Session::get('user_id')));

        if (self::$deleteNoteQuery->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }
}
