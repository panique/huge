<?php

/**
 * Class View
 *
 * Provides the methods all views will have
 */
class View extends Smarty
{
    private $lang = "";
    private $css = array();
    private $js = array();
    private $parameters = array();
    
    function __construct()
    {        
        $template = Session::get("templatePath");
        if(!$template)
        {
            $template = DEFAULT_TEMPLATE;
        }
        
        $this->smarty = new Smarty();
        $this->smarty->force_compile = SMARTY_FORCE_COMPILE;
        $this->smarty->template_dir = SMARTY_TEMPLATE_DIRECTORY.$template;
        $this->smarty->compile_dir = SMARTY_COMPILE_DIRECTORY;
        $this->smarty->plugins_dir = SMARTY_PLUGINS_DIRECTORY;   
    }
    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param boolean $file_only Optional: Set this to true if you don't want to include header and footer
     */
    public function render($filename, $file_only = false, $with_feedback = true)
    {
        
        if(SMARTY_ENABLED)
        {          
            //set language for Smarty right before rendering
            $this->lang = Language::init();
            return self::renderSmarty($filename, $file_only, $with_feedback);
        }
        else
        {
            die("Smarty must be enabled in this version");
        }
    }

    /**
     * 
     * @param type $filename
     * @param type $file_only
     */
    private function renderSmarty($filename, $file_only, $with_feedback)
    {
        include_once $this->lang;
        $array = array();
        //add common text
        $array = isset($lang["ALL"]) ? array_merge($lang["ALL"] ) : array_merge(array());
        //add controller text
        $folder = explode("/", $filename)[0];
        $array += isset($lang[$folder]) ? array_merge($lang[$folder]) : array_merge(array());
        //var_dump($array);//test to see what is in language array
        $this->smarty->assign("lang", $array);
        $this->loadCommonHeaderFiles();//loads common JS and CSS files
        //dont reset feedback with calls that wont load it
        if($with_feedback)
        {//loads feedback messages if any - $feedback is from the lang file
            $this->loadFeedbackMessages($feedback);
        }
        
        $this->assignParameters();
        $this->smarty->assign("js", $this->js);
        $this->smarty->assign("css", $this->css);
        $this->smarty->assign("site_path", URL);
        //always get feedback, even if empty
        $this->smarty->assign("feedback", $this->smarty->fetch("_templates/feedback.tpl"));
          
        // page without header and footer, for whatever reason
        $array = array();//clear array
        if ($file_only == true) {
            array_push($array, $this->smarty->fetch($filename.".tpl"));
        } else {
            array_push($array, $this->smarty->fetch("_templates/header.tpl"));
            array_push($array, $this->smarty->fetch($filename.".tpl"));
            array_push($array, $this->smarty->fetch("_templates/footer.tpl"));
        }      
        $this->smarty->assign("displayArray", $array);
        $this->smarty->display("./display.tpl");
    }
    
    private function assignParameters()
    {
        foreach ($this->parameters as $key => $value)
        {
            $this->smarty->assign($key, $value);
        }
    }
    
    /*Loads feedback on all pages that want it*/
    private function loadFeedbackMessages($feedback)
    {
        $feed = array("feedbackNegative" => "feedback_negative", "feedbackPositive" => "feedback_positive");
        
        foreach($feed as $key => $type)
        {
            $array = array();
            $messages = Session::get($type);
            if(!empty($messages) && isset($messages))
            {
                foreach($messages as $val)
                {
                    array_push($array, $feedback[$val]);
                }
            }    
            $this->smarty->assign($key, $array);  
            
            // delete these messages (as they are not needed anymore and we want to avoid to show them twice
            Session::set($type, null);   
        }
    }

    private function loadCommonHeaderFiles()
    {
        $jquery = "jquery.js";
        if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT']))
        {
            $jquery = "jquery_ie_old.js";
        }        
        
        array_unshift($this->js, $jquery);
        array_unshift($this->css, "reset.css");
    }
    
    public function set($key, $value)
    {
        if(isset($this->$key))
        {
            $this->$key = $value;
        }
        else
        {
            throw new Exception("Key not found.", E_USER_ERROR);
        }
    }
}
