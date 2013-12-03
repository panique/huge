<?php

/**
 * Class Note
 * The note controller. Here we create, read, update and delete (CRUD) example data.
 */
class Note extends Controller
{
    /**
     * Construct this Note object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions. If all of your pages should only
        // be usable by logged-in users: Put this line into libs/Controller->__construct
        Auth::handleLogin();
    }

    /**
     * Get all notes (of the user)
     */
    public function index()
    {
        $note_model = $this->loadModel('Note');
        $this->view->notes = $note_model->getAllNotes();
        $this->view->errors = $note_model->errors;
        $this->view->render('note/index');
    }

    /**
     * Create a new note
     */
    public function create()
    {
        $note_model = $this->loadModel('Note');
        $note_model->create($_POST['note_text']);
        header('location: ' . URL . 'note');
    }

    /**
     * Edit a note (show the current content of the note)
     * @param $note_id int ID of the note
     */
    public function edit($note_id)
    {
        // get the note that you want to edit (to show the current content)
        $note_model = $this->loadModel('Note');
        $this->view->note = $note_model->getNote($note_id);
        $this->view->errors = $note_model->errors;
        $this->view->render('note/edit');
    }

    /**
     * Edit a note (perform the editing after form submit)
     * @param $note_id int ID of the note
     */
    public function editSave($note_id)
    {
        // perform the update: passing note_id from URL and note_text from POST
        $note_model = $this->loadModel('Note');
        $note_model->editSave($note_id, $_POST['note_text']);
        header('location: ' . URL . 'note');        
    }

    /**
     * Delete a note
     * @param $note_id int ID of the note
     */
    public function delete($note_id)
    {
        $note_model = $this->loadModel('Note');
        $note_model->delete($note_id);
        header('location: ' . URL . 'note');
    }
}
