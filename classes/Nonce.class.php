<?php


/*
 * Name: Nonce
 * Created By: border0464111 (fredericguilbault@live.ca)
 * Created On: feb 2013
 */

/* 
Copyright 2013 frederic guilbault (fredericguilbault@live.ca)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/




//=== Nonce ==============================
class Nonce{
	
	private $new_nonce  = NULL;
	private $user       = NULL;
	private $logged     = FALSE;
	private $connection = NULL;
	public  $errors     = array();



	function __construct($db){
		$this->connection = $db->getDatabaseConnection();       
		if ($this->connection == FALSE) {
            $this->errors[] = "No MySQL connection.";
		} 
	}
	            


	private function getUsername(){
		if (isset($_SESSION['user_name'])) {
			return $_SESSION['user_name'];
		}else{
			return 'anon' ;
		}
	}
	
	
	
	public function getNew($action){
		$timestamp = time();
		$entryid = '0464111'; //TODO GENERATE A RANDOM NUMBER 
		$hash = md5($action.$entryid.$this->getUsername().$timestamp.NONCE_UNIQUE_KEY);         
		if (isset($_SESSION)){ 
			$_SESSION['nonce'][$hash]['timestamp'] = $timestamp;
			$_SESSION['nonce'][$hash]['action']    = $action;
			$_SESSION['nonce'][$hash]['entryid']   = $entryid;
		return $hash;
		}else{
			$this->errors[] = 'need an active session to generate a new nonce.';
		}

	}

	public function getNewHiddenInput($action){
	return'<input type="hidden" name="nonce" value="'.$this->getNew($action).'" >'.PHP_EOL;
	}	
	
	
	private function getOldHash(){
		if (isset($_GET['nonce'])) {
			return $_GET['nonce'];
		} elseif(isset($_POST['nonce'])) {
			return $_POST['nonce'];
		}else{
			$this->errors[] = "Nonce not found.";
			return '';
		}
	}
	
	public function isValid(){
		$oldHash = $this->getOldHash();
		$expire_point = time()-NONCE_DURATION;
		// Recreate the nonce				
		if ( isset($_SESSION['nonce'][$oldHash])) {
			$old_timestamp  = $_SESSION['nonce'][$oldHash]['timestamp'] ;
			$old_action     = $_SESSION['nonce'][$oldHash]['action'] ;
			$old_entryid    = $_SESSION['nonce'][$oldHash]['entryid'] ;
			$recreated_hash = md5($old_action.$old_entryid.$this->getUsername().$old_timestamp.NONCE_UNIQUE_KEY);
		}else{
			$this->errors[] = "Failed to recreate Hash.";
        	return false;		
		}	
		
		//validating the nonce
		if ($oldHash == $recreated_hash && $old_timestamp > $expire_point && !$this->check_if_used($oldHash,$old_timestamp) ) {
			 $this -> mark_as_used($oldHash,$old_timestamp);
			return TRUE;
		}else{
			$this->errors[] = "Nonce validation have failed.";
			return FALSE;
		}
	}
	
	
	// TODO clean the $_SESSION['nonce']
	private function mark_as_used($hash,$timestamp){
		$hash = mysql_real_escape_string($hash);//never trust users	
		$timestamp = mysql_real_escape_string($timestamp);//never trust users	
		return $this->connection->query("INSERT INTO phplogin_nonce (timestamp, hash )     VALUES( '$timestamp', '$hash' )");
	}
	
	
	
	private function check_if_used($hash,$timestamp){
		$hash = mysql_real_escape_string($hash);//never trust users	
		$timestamp = mysql_real_escape_string($timestamp);//never trust users	
		
		$resut = $this->connection->query("SELECT 'timestamp' FROM 'phplogin_nonce' WHERE 'hash' = '$hash' ");
		if ( $resut !== false && $resut > 0 ) {
			return TRUE;
		}else{
			return FALSE; 
		}
	}
	
	
	
	private function clean_db(){
		$expire_point = time()-NONCE_DURATION-241920; // 241920 ~= one month
		return $this->connection->query("DELETE FROM 'phplogin_nonce' WHERE 'timestamp' < '$expire_point' ");		
	}
	
	
	
}