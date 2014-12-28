<?php

/**
 * The note controller: Just an example of simple create, read, update and delete (CRUD) actions.
 */
class NoteController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions. If all of your pages should only
        // be usable by logged-in users: Put this line into libs/Controller->__construct
        Auth::checkAuthentication();
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function index()
    {
        $this->View->render('note/index', array(
            'notes' => $this->NoteModel->getAllNotes()
        ));
    }

    /**
     * This method controls what happens when you move to /dashboard/create in your app.
     * Creates a new note. This is usually the target of form submit actions.
     * POST request.
     */
    public function create()
    {
        if (isset($_POST['note_text']) AND strlen($_POST['note_text']) > 0) {
            $this->NoteModel->createNote($_POST['note_text']);
        }
        header('location: ' . URL . 'note');
    }

    /**
     * This method controls what happens when you move to /note/edit(/XX) in your app.
     * Shows the current content of the note and an editing form.
     * @param $note_id int id of the note
     */
    public function edit($note_id)
    {
        if (isset($note_id)) {
            $this->View->render('note/edit', array(
                'note' => $this->NoteModel->getNote($note_id)
            ));
        } else {
            header('location: ' . URL . 'note');
        }
    }

    /**
     * This method controls what happens when you move to /note/editsave(/XX) in your app.
     * Edits a note (performs the editing after form submit).
     * @param int $note_id id of the note
     * POST request.
     */
    // TODO make this purer POST
    public function editSave($note_id)
    {
        if (isset($_POST['note_text']) AND isset($note_id)) {
            // perform the update: pass note_id from URL and note_text from POST
            $this->NoteModel->updateNote($note_id, $_POST['note_text']);
        }
        header('location: ' . URL . 'note');
    }

    /**
     * This method controls what happens when you move to /note/delete(/XX) in your app.
     * Deletes a note. In a real application a deletion via GET/URL is not recommended, but for demo purposes it's
     * totally okay.
     * @param int $note_id id of the note
     */
    public function delete($note_id)
    {
        if (isset($note_id)) {
            $this->NoteModel->deleteNote($note_id);
        }
        header('location: ' . URL . 'note');
    }
}
