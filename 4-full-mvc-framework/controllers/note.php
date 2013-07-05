<?php

class Note extends Controller {

    public function __construct() {

        // a little note on that (seen on StackOverflow):
        // "As long as myChild has no constructor, the parent constructor will be called / inherited."
        // This means wenn a class thats extends another class has a __construct, it needs to construct
        // the parent in that constructor, like this:   
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be useable by logged-in users
        // need this line! Otherwise not-logged in users could do actions
        // if all of your pages should only be useable by logged-in users: Put this line into
        // libs/Controller->__construct
        // TODO: test this!
        Auth::handleLogin();
    }

    public function index() {
        
        // get all notes (of the logged in user)
        $this->view->notes = $this->model->getAllNotes();
        $this->view->render('note/index');
    }

    public function create() {
        $note_text = $_POST['note_text'];

        $this->model->create($note_text);
        header('location: ' . URL . 'note');
    }

    public function edit($note_id) {
        $this->view->note = $this->model->getNote($note_id);

        if (empty($this->view->note)) {
            die('This is an invalid note!');
        }

        $this->view->render('note/edit');
    }

    public function editSave($note_id) {
        $note_text = $_POST['note_text'];

        // do editSave() in the note_model, passing note_id from URL and note_text from POST via params
        $this->model->editSave($note_id, $note_text);
        header('location: ' . URL . 'note');
    }

    public function delete($note_id) {
        $this->model->delete($note_id);
        header('location: ' . URL . 'note');
    }

}