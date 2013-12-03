<?php

/**
 * Class Note
 * The note controller. Here we create, read, update and delete (CRUD) example data.
 */
class Note extends Controller
{
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions. If all of your pages should only
        // be usable by logged-in users: Put this line into libs/Controller->__construct
        Auth::handleLogin();
    }

    public function index()
    {
        // get all notes (of the logged-in user)
        $note_model = $this->loadModel('Note');
        $this->view->notes = $note_model->getAllNotes();
        $this->view->errors = $note_model->errors;
        $this->view->render('note/index');
    }

    public function create()
    {
        $note_model = $this->loadModel('Note');
        $note_model->create($_POST['note_text']);
        header('location: ' . URL . 'note');
    }

    public function edit($note_id)
    {
        $note_model = $this->loadModel('Note');
        $this->view->note = $note_model->getNote($note_id);
        $this->view->errors = $note_model->errors;
        $this->view->render('note/edit');
    }

    public function editSave($note_id)
    {
        // do editSave() in the note_model, passing note_id from URL and note_text from POST via params
        $note_model = $this->loadModel('Note');
        $note_model->editSave($note_id, $_POST['note_text']);
        header('location: ' . URL . 'note');        
    }

    public function delete($note_id)
    {
        $note_model = $this->loadModel('Note');
        $note_model->delete($note_id);
        header('location: ' . URL . 'note');
    }
}
