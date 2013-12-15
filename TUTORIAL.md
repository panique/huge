HOW TO CREATE A NEW CONTROLLER (in the controllers folder)
which will be callable with http://www.myproject.com/XXX/ZZZ

class XXX extends Controller {

	function __construct() {
		parent::__construct();
	}
	
	function ZZZ() {

                //
		$this->view->render('XXX/ZZZ');	
	}

}

HOW TO SHOW A VIEW IN CONTROLLER XXX:

class XXX extends Controller {

        ...

	function ZZZ() {

                // renders views/xxx/zzz.php
		$this->view->render('XXX/ZZZ');	
	}

}

HOW TO PASS A VARIABLE INTO THE CONTROLLER/METHOD/MODEL/VIEW:
First variable is always A, Second is always B, like:
http://www.myproject.com/XXX/ZZZ/A or
http://www.myproject.com/XXX/YYY/B/C
All the stuff behind ZZZ's slash will be translated into parameter variables:

class XXX extends Controller {

        ...

        // pass the variable A from the URL into a method        
	function ZZZ($id) {

                //pass the variable into a view
                $this->view->myspecialvariable = $id;

                // or pass the variable into a model, passing the result of the model's method
                // into a variable than can be used in the view
                $this->view->arrayOfStuff = $this->model->getStuffFromDatabaseMotherfucker($id);

                // renders views/xxx/zzz.php (you can use $this->myspecialvariable in the view)
		$this->view->render('XXX/ZZZ');	
	}

        // another example
        // pass the variables B and C from the URL into a method
	function YYY($variable_one, $variable_two) {

            ...

        }

}