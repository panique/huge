<?php
/**
 * LANG.PHP
 * File containing the Lang class
 * @author Tristan Vanrullen
 * @copyright 2013 Tristan Vanrullen
 */



/**
 * The Lang class behaves as a pool of static methods, with static variables, enabling the application to deal with linguistic translations.
 * It is based on XML formated files (translation dictionaries) stored in the 'locale' folder.
 * This class works hand in hand with the XMLUtilities class (reading XML files) 
 * The Lang class needs to work when a session is started, and uses the session variable 'current_language' to handle the relevant translation dictionary
 * When the Lang class has to be used, the method Lang::initLanguage() has to be called once (it is done in the Controller constructor):
 * - it will prepare the session variable current_language for the first use.
 * - it will load the relevant dictionary.
 * Anytime you need to translate a string 'mystring' into the current_language, you will just have to call the Lang::__('mystring') method.
 * Dictionaries have to be placed in the 'locale' folder of this application, sorted by language subfolders.
 * At least, one dictionary file named 'core.xml' has to be created in the accepted languages subfolders
 * You can also place 'myview.mysubview.xml' files in the languages subfolders and add those files to the current dictionary from the corresponding controler.
 * A file named 'languages_config.xml' has to be stored in the 'locale' folder. It will contain the list of languages accepted by the application.
 * It will also contain language names translated in several languages.
 * This class provides a simple selector form, able to render the list of accepted languages, preselected on the current language value.
 * @author Tristan Vanrullen
 * @since 27/11/2013
 *
 */
class Lang {
	///////////////////////////////////////////////////////////////////////// Class Variables

	/** 
	 * The folder where linguistic stuff useful for the present application has to be placed.
	 * This folder has to contain a file named "accepted_languages.xml" 
	 * This folder will contain one subfolder for each accepted language
	 * Each of these accepted languages folders will contain one or several files named with this convention:
	 * - core.xml (one file loaded each time, containing general purpose string translations)
	 * - myview[.mysubview]*.xml (a file loaded for the current view and subview)
	 * Don't touch this variable unless you move your linguistic stuff into another folder
	 */
	private static $_LANG_LOCALE_PATH = 'application/locale/'; // Always include trailing slash.
	
	/** 
	 * table of accepted languages for the current application. this table will be loaded from the languages_config.xml file that SHOULD BE PRESENT in the locale root folder
	 * by default, the language of the application is set to 'en_UK'
	 * @staticvar  array $_languages_config
	 * all the language IDs in this array MUST have a corresponding folder in the locale folder
	 * @see Lang::$_LANG_LOCALE_PATH
	 */
	private static $_languages_config=null;

	/**
	 * the array containing translations after the initLanguage Method call.
	 * data are loaded from the current language's locale folder
	 * structure : ['somekey']['some translation']
	 * @staticvar array of pairs key => value
	 * @see Lang::$_LANG_LOCALE_PATH
	 */
	private static $_dictionary=null;
	
	/**
	 * an array to store the untranslated strings during page load
	 * if LANG_TRANSLATION_ASSISTANCE is set to true (in config.php), this array will be used to provide some helpful data at the bottom of the page
	 * don't use it in production!
	 */
	private static $_untranslated_dictionary=null;
	
	
	/**
	 * let's define some constants for the LANG CLASS
	 */
	const REPLACE_BY_BLANK = "REPLACE_BY_BLANK";
	const REPLACE_BY_KEY = "REPLACE_BY_KEY";
	const REPLACE_BY_KEY_TRANSLATE_ME = "REPLACE_BY_KEY_TRANSLATE_ME";

	/**
	 * let's define some behaviour against blank translations
	 * @var string $_replace_blank_translation_by (default is set to Lang::REPLACE_BY_BLANK)
	 */
	private static $_replace_blank_translation_by=Lang::REPLACE_BY_BLANK;

	/**
	 * let's define some behaviour against non existing translations (missing translations in the locale dictionary)
	 * @var string $_replace_non_existing_translation_by (default is set to Lang::REPLACE_BY_KEY_TRANSLATE_ME)
	 */
	private static $_replace_non_existing_translation_by =Lang::REPLACE_BY_KEY_TRANSLATE_ME;
		

	/**
	 * Let's define some behaviour for the 'choose language' form:
	 * The 'languages_config.xml' file describes the languages available (know/accepted) in the application.
	 * To work properly, the application, requires a translation of each 'language name' 
	 * (ex : english) into every available language (ex: Anglais in French, Englisch in Deutsch, and so on). 
	 * This is why the XML syntax defined in the 'languages_config.dtd' requires the 'translation' sub-element : 
	 * one <translation> per <language> element.
	 * The 'choose language' selector/form will propose a set of options (one for each available language).
	 * Each option corresponding to a given language 'L' will receive the language's name 'N' as value
	 * That given name can be :
	 *    (a) Translated into the language 'L' itself : idiomatic naming (auto-idiomatic?tauto-idiomatic?), or to say it differently, the language's name translated into it's self language.
	 * or (b) Translated into the current 'selected language' : allo-idiomatic naming (alter-idiomatic?), or to say it differently, the language's name translated into the 'selected' language.   
	 *  
	 */
	const LANGUAGE_SELECTOR_IDIOMATIC = "LANGUAGE_SELECTOR_IDIOMATIC";
	const LANGUAGE_SELECTOR_ALLOIDIOMATIC = "LANGUAGE_SELECTOR_ALLOIDIOMATIC";
	
	/**
	 * We store the IDIOMATIC preference for the language selector:
	 * @var string $_replace_non_existing_translation_by (default is set to Lang::REPLACE_BY_KEY_TRANSLATE_ME)
	 */
	private static $_language_selector_idiom =Lang::LANGUAGE_SELECTOR_IDIOMATIC;
	
	
	///////////////////////////////////////////////////////////////////////// STATIC METHODS
	/**
	 * The method to be called each time a page is loaded. 
	 * This method is called by the superclass Controller, in order to prepare linguistic stuff for being rendered in the relevant language
	 * Note: this method has to be called after a Session is started because the session is used to store the current language between two pages (and two calls of initLanguage)
	 * @param $force_load_dictionary force load the dictionary (false by default). use this parameter when language is changed by the user  
	 */
    public static function initLanguage($force_load_dictionary=false) 
    {
    	/*
    	 * Configuration for: Locale and default language of the website
    	 * If a current_language is not already defined, we set here the language defaults of the website:
    	 * - first, the navigator language is interpreted as the default language, if accepted by the application
    	 * - if the application can't accept the navigator language, we default to the english language
    	 */
    	if (!isset(Lang::$_languages_config))
    	{
       		//first load the list of the languages spoken by this application 
    		Lang::loadLanguagesConfig();
    	}
    	
    	if (!isset($_SESSION['current_language']))
    	{
    		//get the array of known/accepted languages:
    		$knownLanguages=Lang::getKnownLanguages();
    		//get the browser's preferences   		
    		$myLanguages=Lang::getBrowserLanguages();
    		    		
    		/**
    		 * Configuring the application
    		*/
    		$_SESSION['current_language']='';
    		if (isset($knownLanguages['default']))
    			$_SESSION['current_language']=$knownLanguages['default'];
    		//now take the browser's preferred language and try to find it in the knownlanguage
    		foreach ($myLanguages as $lang => $q)
    		{
    			$lang=str_replace("-","_",$lang);
    			//if $lang is known
    			if (isset($knownLanguages[$lang]))
    			{
    				//if $lang is known but is an alias, we need to use the target language, not the alias!    				
    				$_SESSION['current_language']=$knownLanguages[$lang];
    				break;
    			}
    		}
    		//at this point, if there is no language matching the browser's preferences, and no default language defined, we pick the first known language
    		if ($_SESSION['current_language']=='' and count($knownLanguages)>0){
    			reset($knownLanguages);
    			$_SESSION['current_language']= key($knownLanguages);
    		} 
    	} //end if session current language not already chosen
       	else 
       	{
       		
       	}
       	//load the dictionary if required
       	if (!isset(Lang::$_dictionary) || $force_load_dictionary) 
       	{
       		Lang::loadDictionary($_SESSION['current_language']);
       		if (LANG_TRANSLATION_ASSISTANCE) Lang::$_untranslated_dictionary=array();
       	}
    }
    
    
    /**
     * loads the "languages config" listing accepted languages and their descriptions from the languages_config.xml file stored within the locale folder
     */
    private static function loadLanguagesConfig()
    {
    	$result_array=XMLUtilities::xmlfile_to_array(Lang::$_LANG_LOCALE_PATH."/languages_config.xml","UTF-8");
    	Lang::$_languages_config=$result_array;
    }

    /**
     * return an array containing the browser languages
     * @return associative array
     */
    public static function getBrowserLanguages() {

    	/**
    	 * Reading the expected languages from the browser
    	 */
    	$acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    	
    	/**
    	 * Analyzing the accept_language query and finding the appropriate language within the application list of known languages
    	 */
    	$myLanguages = array();
    	
    	// break up string into pieces (languages and q factors)
    	preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLanguage, $lang_parse);
    	
    	if (count($lang_parse[1]))
    	{
    		// create a list like "en" => 0.8
    		$languages = array_combine($lang_parse[1], $lang_parse[4]);
    			
    		// set default to 1 for any without q factor
    		foreach ($languages as $lang => $val)
    		{
    			if ($val === '') $val = 1;
    			$lang_parts= explode('-', $lang);
    			if (isset($lang_parts[1]) && $lang_parts[1]!="") $lang=$lang_parts[0]. '_' . strtoupper($lang_parts[1]);
    			$myLanguages[$lang]=$val;
    		}
    	
    		// sort list based on value
    		arsort($myLanguages, SORT_NUMERIC);
    	}
    	return $myLanguages;
    }
    
    /**
     * return an array containing the know (accepted) languages, plus information about the default language, plus information about the aliased languages
     * the initLanguages method calls this method, after loadLanguagesConfig.
     * @example example structure returned by this method : array('fr_FR'=>'fr_FR','de_DE'=>'de_DE','en_UK'=>'en_UK','en_US'=>'en_UK','default'=>'en_UK')
     * @return associative array
     */
    public static function getKnownLanguages()
    {
    	//prepare an empty array for known/accepted languages
    	$knownLanguages=array('default'=>'');
    	
    	//now if there exists accepted languages in the config file
    	if (isset(Lang::$_languages_config['accepted_languages']['language']))
    	{
    		//the internal organization of the array's indexes and subarrays is set by the DTD linked to the XML file.
    		//the array structure of Lang::$_languages_config is set by the XMLutilities class :
    		// -an XML element having some attributes will become an associative array having a '@attributes' sub-array
    		// -an XML element having several (N) sub-elements will become an associative array having a sub-array per sub-element, indexed by an integer (from 0 to N)
    		// -an XML element having both attributes and sub-elements will become an associative array having both the above 
    		// So the best way to test whether an elements has several sub-elements is to test the existence of Lang::$_languages_config['myelement'][1]
    		// rather than testing if count(Lang::$_languages_config['myelement'])>0 because the '@attributes' subarray may disturb such a count 

    		//test if there are several languages or just one accepted.
    		if (isset(Lang::$_languages_config['accepted_languages']['language'][1]))
    		{	
    			//for each accepted language
    			$counter=0;
	    		foreach (Lang::$_languages_config['accepted_languages']['language'] as $index => $lang)
	    		{
	    			if ($index==='@attributes') 
	    			{
	    				continue;
	    			}
	    			//access the attributes for the current accepted language
	    			//the 'id' attribute is mandatory, but we can still provide by using a counter 
	    			$lang_id= (isset($lang['@attributes']['id'])?$lang['@attributes']['id']:'Lang_'.$counter);
	    			//there can be an aliasing or not from the current language to another one, so we check if there is an alias
	    			$lang_aliasid= (
	    					(isset($lang['@attributes']['is_alias'])
	    							&& $lang['@attributes']['is_alias']=='true'
	    							&& isset($lang['@attributes']['alias_idref']))?
	    					$lang['@attributes']['alias_idref']:$lang_id);
	    			//now store the association between the current language and its target locale (must be a folder in the locale folder)
	    			$knownLanguages[$lang_id]=$lang_aliasid; // keep a basic associative structure
	    			//check if there is a default language defined
	    			if (isset($lang['@attributes']['is_default']) && $lang['@attributes']['is_default']=='true')
	    				$knownLanguages['default']=$lang_id;
	    			$counter++;
	    		}
    		}
    		else
    		{
    			//If there is only one language defined, the array structure will change !
    			$lang_id= (isset(Lang::$_languages_config['accepted_languages']['language']['@attributes']['id'])?Lang::$_languages_config['accepted_languages']['language']['@attributes']['id']:'');
    			$lang_aliasid= (
    						(isset(Lang::$_languages_config['accepted_languages']['language']['@attributes']['is_alias']) 
    						 && Lang::$_languages_config['accepted_languages']['language']['@attributes']['is_alias']=='true'
    						 && isset(Lang::$_languages_config['accepted_languages']['language']['@attributes']['alias_idref']))?
    					Lang::$_languages_config['accepted_languages']['language']['@attributes']['alias_idref']:$lang_id);
    			$knownLanguages[$lang_id]=$lang_aliasid; // keep a basic associative structure
    			if (isset(Lang::$_languages_config['accepted_languages']['language']['@attributes']['is_default'])
    							&& Lang::$_languages_config['accepted_languages']['language']['@attributes']['is_default']=='true')
    				$knownLanguages['default']=$lang_id;
    		} 
    			
    	}
    	return $knownLanguages;
    }
    
    
    public static function getLanguagesConfig() {
    	if (isset(Lang::$_languages_config))
    		return Lang::$_languages_config;
    	return array();
    }
    
    public static function getDictionary() {
    	if (isset(Lang::$_dictionary))
    		return Lang::$_dictionary;
    	return array();
    }
    
    
    /**
     * provide an HTML FORM containing the known languages for this application, preselected on the current language, with the language names translated in the current language.
     * @param string $current_language the language preselection for this form
     * @param string $form_id the id of the form (default is set to "language_form")
     * @param string $selector_id the id of the SELECT element (default is set to "language_selector")
     * @param string $submit_method the method chosen for submitting the form (default is set to POST)
     * @param string $submit_action the action to be performed on submit (empty by default, the form is submitted to the current page)
     * @param string $form_css_class the css class for this form (default is set to "language_selector")
     * @param string $current_page the page loading the selector (in order to load the same page after submitting the form to the relevant controller (leave blank if you just need the controller to render the default view) 
     * @return string the full form
     */
    public static function getLanguageHTMLForm($current_language='',$form_id="language_form",$selector_id="language_selector",$submit_method="POST",$submit_action="",$form_css_class="language_selector",$current_page="")
    {
    	//if someone forgot to call initLanguage before, let's do it now
    	if (!isset(Lang::$_languages_config['accepted_languages']) || !isset($_SESSION['current_language'])) {
    		Lang::initLanguage();
    	}
    	
    	$option_selected=false;
    	$selector="<form id='$form_id' class='$form_css_class' method='$submit_method' action='$submit_action'>";
    	$selector.="<label>".Lang::__("choose.another.language")."</label>";
    	$selector.="<select id='$selector_id' name='$selector_id'>";
    	if (isset(Lang::$_languages_config['accepted_languages']['language']))
    	{
    		//test if there are several languages
    		if (isset(Lang::$_languages_config['accepted_languages']['language'][1]))
    		{
    			//for each accepted language
    			$counter=0;
    			foreach (Lang::$_languages_config['accepted_languages']['language'] as $index => $lang)
    			{
    				if ($index==='@attributes')
    				{
    					continue;
    				}
    				$lang_id= (isset($lang['@attributes']['id'])?$lang['@attributes']['id']:'Lang_'.$counter);
    				$lang_aliasid= (
    						(isset($lang['@attributes']['is_alias'])
    								&& $lang['@attributes']['is_alias']=='true'
    								&& isset($lang['@attributes']['alias_idref']))?
    						$lang['@attributes']['alias_idref']:$lang_id);
    				//we only provide linguistic choice for the 'non aliased' languages
    				if ($lang_aliasid==$lang_id)
    				{
    					$selector.="<option value='$lang_id'";
    					if ($current_language==$lang_id)
    					{
    						$option_selected=true;
    						$selector.=" selected>";
    					}
    					else $selector.=">";
    					//now look for a language name
    					
    					$lang_name='';
    					if (isset($lang['translation']))
    					{
    						if (isset($lang['translation']) && count($lang['translation'])>1)
    						{
    							foreach ($lang['translation'] as $trindex => $trans)
    							{
    								if ($trindex==='@attributes') continue;
    								if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_IDIOMATIC
    								&& isset($trans['@attributes']['translated_idref'])
    								&& $trans['@attributes']['translated_idref']==$lang_id)
    								{
    									$lang_name= (isset($trans['@attributes']['translation'])?$trans['@attributes']['translation']:'');
    									break; //we found a translation for the current language, now jump to the next language option
    								} else if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_ALLOIDIOMATIC
    								&& isset($trans['@attributes']['translated_idref'])
    								&& $trans['@attributes']['translated_idref']==$current_language) //translate each language's name into the current language
    								{
    									$lang_name= (isset($trans['@attributes']['translation'])?$trans['@attributes']['translation']:'');
    									break; //we found a translation for the current language, now jump to the next language option
    								}
    							}
    						}
    						else
    						{
    							if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_IDIOMATIC
    							&& isset($lang['translation']['@attributes']['translated_idref'])
    							&& $lang['translation']['@attributes']['translated_idref']==$lang_id)
    							{
    								$lang_name= (isset($lang['translation']['@attributes']['translation'])?$lang['translation']['@attributes']['translation']:'');
    								break; //we found a translation for the current language, now jump to the next language option
    							} else if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_ALLOIDIOMATIC
    							&& isset($lang['translation']['@attributes']['translated_idref'])
    							&& $lang['translation']['@attributes']['translated_idref']==$current_language) //translate each language's name into the current language
    							{
    								$lang_name= (isset($lang['translation']['@attributes']['translation'])?$lang['translation']['@attributes']['translation']:'');
    								break; //we found a translation for the current language, now jump to the next language option
    							}
    						}
	    					/*if (isset($lang['translation']) && count($lang['translation'] )>1)
	    					{
	    						foreach ($lang['translation'] as $trindex => $trans)
	    						{
	    							if ($trindex==='@attributes') continue;
	    							if (isset($trans['@attributes']['translated_idref']) && $trans['@attributes']['translated_idref']==$lang_id) //$current_language can be used instead of $lang_id if we want to translate each language's name into the current language 
	    							{
	    								$lang_name= (isset($trans['@attributes']['translation'])?$trans['@attributes']['translation']:'');
	    								break; //we found a translation for the current language, now jump to the next language option
	    							}
	    						}
	    					}
	    					else 
	    					{
	    						if (isset($lang['translation']['@attributes']['translated_idref']) && $lang['translation']['@attributes']['translated_idref']==$lang_id) //$current_language can be used instead of $lang_id if we want to translate each language's name into the current language
	    						{
	    							$lang_name= (isset($lang['translation']['@attributes']['translation'])?$lang['translation']['@attributes']['translation']:'');
	    						}
	    					}
	    					*/
    					}//end if there are translations for the language name
    					if ($lang_name=='') $lang_name=$lang_id; //just in case we wouldn't fine the proper translation
    					$selector.=$lang_name."</option>";
    				}//end if the language is not an alias
    				$counter++;
    			}//end 'foreach' language
    		}//end if there are multiple languages
    		else
    		{
    			//it seems weird to handle a single language descriptor for a multilingue application .. but why not
    			$lang=Lang::$_languages_config['accepted_languages']['language'];
    			$lang_id= (isset($lang['@attributes']['id'])?$lang['@attributes']['id']:'');
    			$lang_aliasid= (
    					(isset($lang['@attributes']['is_alias'])
    							&& $lang['@attributes']['is_alias']=='true'
    							&& isset($lang['@attributes']['alias_idref']))?
    					$lang['@attributes']['alias_idref']:$lang_id);
    			//we only provide linguistic choice for the 'non aliased' languages
    			if ($lang_aliasid==$lang_id)
    			{
    				$selector.="<option value='$lang_id'";
    				if ($current_language==$lang_id)
    				{
    					$option_selected=true;
    					$selector.=" selected>";
    				}
    				else $selector.=">";
    				//now look for a language name
    					
    				$lang_name='';
    				if (isset($lang['translation']))
    				{
    					if (isset($lang['translation']) && count($lang['translation'])>1)
    					{
    						foreach ($lang['translation'] as $trindex => $trans)
    						{
    							if ($trindex==='@attributes') continue;
    							if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_IDIOMATIC 
    								&& isset($trans['@attributes']['translated_idref']) 
    								&& $trans['@attributes']['translated_idref']==$lang_id) 
    							{
    								$lang_name= (isset($trans['@attributes']['translation'])?$trans['@attributes']['translation']:'');
    								break; //we found a translation for the current language, now jump to the next language option
    							} else if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_ALLOIDIOMATIC 
    									&& isset($trans['@attributes']['translated_idref']) 
    									&& $trans['@attributes']['translated_idref']==$current_language) //translate each language's name into the current language
    							{
    									$lang_name= (isset($trans['@attributes']['translation'])?$trans['@attributes']['translation']:'');
    									break; //we found a translation for the current language, now jump to the next language option
    							}
    						}
    					}
    					else
    					{
    						if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_IDIOMATIC 
    						&& isset($lang['translation']['@attributes']['translated_idref']) 
    						&& $lang['translation']['@attributes']['translated_idref']==$lang_id)
    						{
    							$lang_name= (isset($lang['translation']['@attributes']['translation'])?$lang['translation']['@attributes']['translation']:'');
    							break; //we found a translation for the current language, now jump to the next language option
    						} else if (Lang::$_language_selector_idiom==Lang::LANGUAGE_SELECTOR_ALLOIDIOMATIC 
    								&& isset($lang['translation']['@attributes']['translated_idref']) 
    								&& $lang['translation']['@attributes']['translated_idref']==$current_language) //translate each language's name into the current language
    						{
    							$lang_name= (isset($lang['translation']['@attributes']['translation'])?$lang['translation']['@attributes']['translation']:'');
    							break; //we found a translation for the current language, now jump to the next language option
    						}    						
    					}
    				}
    				if ($lang_name=='') $lang_name=$lang_id; //just in case we wouldn't find the proper translation
    				$selector.=$lang_name."</option>";
    			}//end if the language is not an alias
    		}//end if there is a single language
    	}//end if there are languages
    	$selector.="</select>";
    	if ($current_page!="")
    		$selector.="<input type='hidden' name='current_page' value='$current_page'/>";
    	$selector.="</form>"; 
    	return $selector;
    }
    
    /**
     * loads the translation dictionary from an xml file
     * this method is supposed to be called only by the initLanguage method
     * @param $language : the name of the language
     */
    private static function loadDictionary($language)
    {
    	 Lang::$_dictionary=array();
    	 if (file_exists(Lang::$_LANG_LOCALE_PATH."/".$_SESSION['current_language']."/core.xml"))
    	 {	
    	 	Lang::$_dictionary=XMLUtilities::xmlfile_to_associative_array(Lang::$_LANG_LOCALE_PATH."/".$_SESSION['current_language']."/core.xml","UTF-8","language_translation/translations/translation","key");
    	 }
    }
    
	/**
	 * add a dictionary to the core dictionary
	 * useful to store view specific dictionaries
	 * @param string $dictionary_path the relative path driving from "locale/$lang/" to the desired xml file
	 * @param string $lang the language of the desired dictionary
	 * @example Lang::addDictionary("overview/index","fr_FR"); will try to load the file "locale/fr_FR/overview/index.xml" 
	 */
    public static function addDictionary($dictionary_path,$lang="")
    {
    	//sympa: in the case some dev has forgotten to init the linguistic data properly
    	if (!isset($_SESSION['current_language']) || !isset(Lang::$_dictionary)) {
    		Lang::initLanguage();
    	}
    	if ($lang=="")
    	{
    		if (isset($_SESSION['current_language']))
    			$lang=$_SESSION['current_language'];
    		else return;
    	}
    	if (file_exists(Lang::$_LANG_LOCALE_PATH."/".$lang."/".$dictionary_path.".xml"))
    	{
    		$language=XMLUtilities::xmlfile_to_associative_array(Lang::$_LANG_LOCALE_PATH."/".$lang."/".$dictionary_path.".xml","UTF-8","language_translation/translations/translation","key");
    		Lang::$_dictionary=array_merge(Lang::$_dictionary,$language);
    	}
    }
    
    /**
     * The method for translating a string into the current language
     * @param string $string : string to translate
     * @param mixed $params : non mandatory string or array of params. each param will replace the corresponding patterns '%s', '%s' (, etc...) in the translated string
     **/
    public static function __($string,$params=null)
    {
    	//sympa: in the case some dev has forgotten to init the linguistic data properly
    	if (!isset($_SESSION['current_language']) || !isset(Lang::$_dictionary)) {
    		Lang::initLanguage();
    	}
    	//now look for the string in the current dictionary and return the translated string
    	if (isset (Lang::$_dictionary[$string]))
    	{
    		if(Lang::$_dictionary[$string]=="")
    		{
    			if (Lang::$_replace_blank_translation_by==Lang::REPLACE_BY_BLANK)
    				return "";
    			elseif (Lang::$_replace_blank_translation_by==Lang::REPLACE_BY_KEY)
    				return $string;
    			elseif (Lang::$_replace_blank_translation_by==Lang::REPLACE_BY_KEY_TRANSLATE_ME)
    				return $string." [translate me]";
    		}
	    	else if ($params==null)	
	    	{
				return Lang::$_dictionary[$string];
	    	}
	    	else
	    		return sprintf(Lang::$_dictionary[$string],$params);
    	}
    	//in the case the string wouldn't be found in the dictionary
    	if (LANG_TRANSLATION_ASSISTANCE && !isset(Lang::$_untranslated_dictionary[$string])) 
    		Lang::$_untranslated_dictionary[$string]=(isset($params) && is_array($params))?count($params):($params!=""?1:0);
    	
    	if (Lang::$_replace_non_existing_translation_by==Lang::REPLACE_BY_BLANK)
    		return "";
    	elseif (Lang::$_replace_non_existing_translation_by==Lang::REPLACE_BY_KEY)
    		return $string;
    	elseif (Lang::$_replace_non_existing_translation_by==Lang::REPLACE_BY_KEY_TRANSLATE_ME)
    		return $string." [translate me]";
    	//in any other case (shouldn't happen):
    	return $string." [translate me]";//
    }
    
    /**
     * returns the ISO 639-1 language code corresponding to an internal language code (this Lang library is handling locale language codes (i.e. we can have several versions for French, like fr_FR, fr_BE, and so on)
     * @param string $lang
     * @return string
     */
    public static function getISOLanguageCode($lang)
    {
    	$code="";
    	$parts=explode("_",$lang);
    	if (isset($parts[0])) $code=strtolower($parts[0]);
    	return $code;
    }
    
    
    public static function printTranslationAssistance()
    {
    	if (isset(Lang::$_untranslated_dictionary) && count(Lang::$_untranslated_dictionary)>0)
    	{
    		echo "Translation assistance for language ".$_SESSION['current_language'].
    		". <br/>(You can set this assistant OFF by setting the constant LANG_TRANSLATION_ASSISTANCE to false in the 'config.php' file)".
    		". <br/>Add the following elements to the locale/".$_SESSION['current_language']."/core.xml file (or to the relevant subdictionary) :<br/>";
    		echo "<textarea style=\"width:50%;\" rows=\"10\">";
    		foreach (Lang::$_untranslated_dictionary as $key => $argument_count)
    		{
    			$s="<translation key=\"$key\"";
    			if ($argument_count!="0") $s.=" arguments=\"$argument_count\""; 	
    			$s.="></translation>";
    			echo "\n".htmlentities($s,ENT_QUOTES);
    		}
    		echo "</textarea>";
    	}
    }
    

    /**
     * get a value 
     */
    
	public static function getLocalePath()
    {
    	return Lang::$_LANG_LOCALE_PATH;
    }
    
    public static function getUntranslatedDictionary()
    {
    	return Lang::$_untranslated_dictionary;
    }
    /**
     * set a value
     */

    public static function setLocalePath($LANG_LOCALE_PATH)
    {
    	Lang::$_LANG_LOCALE_PATH=$LANG_LOCALE_PATH;
    }
    
    
    public static function setReplaceBlankTranslationBy($replace_blank_translation_by=Lang::REPLACE_BY_BLANK)
    {
    	Lang::$_replace_blank_translation_by=$replace_blank_translation_by;
    }
    
    public static function setReplaceNonExistingTranslationBy($replace_non_existing_translation_by=Lang::REPLACE_BY_KEY_TRANSLATE_ME)
    {
    	Lang::$_replace_non_existing_translation_by=$replace_non_existing_translation_by;
    }
    
    public static function setLanguageSelectorIdiom($language_selector_idiom=Lang::LANGUAGE_SELECTOR_IDIOMATIC)
    {
    	Lang::$_language_selector_idiom=$language_selector_idiom;
    }
    
    
    
}
