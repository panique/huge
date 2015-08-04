<?php

/**
 * Class Redirect
 *
 * Simple abstraction for redirecting the user to a certain page
 */

class Redirect 
{
	/**
	 * To the homepage
	 */
        public function home($ref = true) {
			if(!empty($_SERVER['HTTP_REFERER']) && $ref == true && $_SERVER['HTTP_REFERER'] != ($this->getServerLink() . "/" . $this->calledClass())) {
    			header("Location: " . $_SERVER['HTTP_REFERER']);
			} else {
				header("location: " . $this->getServerLink());
			}
        }
        
	/**
	 * To the defined page
	 *
	 * @param $path
	 */
        public function to($path, $ref = true) {
		if(!empty($_SERVER['HTTP_REFERER']) && $ref == true && $_SERVER['HTTP_REFERER'] != ($this->getServerLink() . "/" . $this->calledClass())) {
    			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
            		header("location: " . $this->getServerLink() . $path);
		}
        }
		
	private function getServerLink() {
		return strtolower(substr($_SERVER['SERVER_PROTOCOL'],0,strpos( $_SERVER['SERVER_PROTOCOL'],'/')).'://' . $_SERVER['HTTP_HOST']);
	}
		
	private function calledClass() {
		$trace = debug_backtrace();
		$filename = explode("/", $trace[1]["file"]);
		$filename = explode(".", $filename[count($filename)-1]);
		$class = $filename[0];
		return strtolower($class);
	}
}
