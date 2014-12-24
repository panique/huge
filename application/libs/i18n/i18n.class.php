<?php

/*
 * Fork this project on GitHub!
 * https://github.com/Philipp15b/php-i18n
 * 
 * License: Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 * License URL: http://creativecommons.org/licenses/by-sa/3.0/deed.en
 */

require_once 'spyc.php';

class i18n {
    /*
     * Editable settings via constructor
     */
    
    /**
     * Language file path
     * This is the path for the language files. You must use the '{LANGUAGE}' placeholder for the language or the script wont find any language files.
     *
     * @var string
     */
    protected $filePath = '../config/lang/lang_{LANGUAGE}.ini';
    
    /**
     * Cache file path
     * This is the path for all the cache files. Best is an empty directory with no other files in it.
     *
     * @var string
     */
    protected $cachePath = '../config/langcache/';
    
    /**
     * Fallback language
     * This is the language which is used when there is no language file for all other user languages. It has the lowest priority.
     * Remember to create a language file for the fallback!!
     *
     * @type string
     */
    protected $fallbackLang = 'en';
    
    /**
     * Forced language
     * If you want to force a specific language define it here.
     *
     * @type string
     */
    protected $forcedLang = NULL;
    
    /**
     * This is the seperator used if you use sections in your ini-file.
     * For example, if you have a string 'greeting' in a section 'welcomepage' you will can access it via 'L::welcomepage_greeting'.
     * If you changed it to 'ABC' you could access your string via 'L::welcomepageABCgreeting'
     *
     * @var string
     */
    protected $sectionSeperator = '_';
    
    
    /*
     * Runtime needed variables
     * That means that the contents of the following variables are only available after init().
     */
    
    /**
     * User languages
     * These are the languages the user uses.
     * Normally, if you use the getUserLangs-method this array will be filled in like this:
     * 1. Forced language
     * 2. Language in $_GET['lang']
     * 3. Language in $_SESSION['lang']
     * 4. Fallback language
     *
     * @var array
     */
    protected $userLangs = array();
    
    /**
     * The applied language
     * This means the chosen language.
     *
     * @var NULL, string
     */
    protected $appliedLang = NULL;
    
    /**
     * Language file path
     * This is the path for the used language file. Computed automatically.
     *
     * @var string
     */
    protected $langFilePath = NULL;
    
    /**
     * Cache file path
     * This is the path for the used cache file. Computed automatically.
     *
     * @var string
     */
    protected $cacheFilePath = NULL;
    
    /**
     * Variable to check if the init() method was used.
     * This is for the methods that set settings that can not be changed after init.
     * 
     * @var bool
     */
    protected $isInitialized = false;
    
    
    /**
     * Constructor
     * The constructor sets all important settings. All params are optional, you can set the options via extra functions too.
     *
     * @param string [$filePath] This is the path for the language files. You must use the '{LANGUAGE}' placeholder for the language.
     * @param string [$cachePath] This is the path for all the cache files. Best is an empty directory with no other files in it. No placeholders.
     * @param string [$fallbackLang] This is the language which is used when there is no language file for all other user languages. It has the lowest priority.
     * @param string [$forcedLang] If you want to force a specific language define it here.
     * @param string [$sectionSeperator] This is the seperator used for sections in your ini-file.
     */
    public function __construct($filePath = NULL, $cachePath = NULL, $fallbackLang = NULL, $forcedLang = NULL, $sectionSeperator = NULL) {
        // Apply settings
        if ($filePath != NULL) {
            $this->filePath = $filePath;
        }
        
        if ($cachePath != NULL) {
            $this->cachePath = $cachePath;
        }
        
        if ($fallbackLang != NULL) {
            $this->fallbackLang = $fallbackLang;
        }
        
        if ($forcedLang != NULL) {
            $this->forcedLang = $forcedLang;
        }
        
        if ($sectionSeperator != NULL) {
            $this->sectionSeperator = $sectionSeperator;
        }
        
        return $this;
        
    }
    
    public function init() {
        if ($this->isInitialized == true) {
            throw new BadMethodCallException('This object from class ' . __CLASS__ . ' is already initialized. It is not possible to init one object twice!');
        }
        
        $this->isInitialized = true;
        
        // set user language
        $this->userLangs = $this->getUserLangs();
        
        // search for language file
        $this->appliedLang = NULL;
        foreach ($this->userLangs as $priority => $langcode) {
            $this->langFilePath = str_replace('{LANGUAGE}', $langcode, $this->filePath);
            if (file_exists($this->langFilePath)) {
                $this->appliedLang = $langcode;
                break;
            }
        }
        
        // abort if no language file was found
        if ($this->appliedLang == NULL) {
            throw new RuntimeException('No language file was found.');
        }
        // search for cache file
        $this->cacheFilePath = $this->cachePath . '/php_i18n_' . md5_file(__FILE__) . '_' . $this->appliedLang . '.cache.php';
        
        // if no cache file exists or if it is older than the language file create a new one
        if (!file_exists($this->cacheFilePath) || filemtime($this->cacheFilePath) < filemtime($this->langFilePath)) {
            switch ($this->get_file_extension()) {
                case 'ini':
                    $ini = parse_ini_file($this->langFilePath, true);
                    break;
                case 'yml':
                    $ini = spyc_load_file($this->langFilePath);
                    break;
                default:
                    $ini = array();
            }
            
            $compiled = "<?php class L {\n";
            $compiled .= $this->compile($ini);
            $compiled .= '}';
            
            file_put_contents($this->cacheFilePath, $compiled);
            chmod($this->cacheFilePath, 0777);
            
        }
        
        // include the cache file
        require_once $this->cacheFilePath;
        
    }
    
    public function isInitialized() {
        return $this->isInitialized;
    }
    
    public function getAppliedLang() {
        return $this->appliedLang;
    }
    
    public function getCachePath() {
        return $this->cachePath;
    }
    
    public function getFallbackLang() {
        return $this->fallbackLang;
    }
    
    public function setFilePath($filePath) {
        if ($this->isInitialized() == true) {
            $this->filePath = $filePath;
        } else {
            throw new BadMethodCallException('This ' . __CLASS__ . ' object is already initalized, so you can not change the file path setting.');
        }
    }
    
    public function setCachePath($cachePath) {
        if ($this->isInitialized() == true) {
            $this->cachePath = $cachePath;
        } else {
            throw new BadMethodCallException('This ' . __CLASS__ . ' object is already initalized, so you can not change the cache path setting.');
        }
    }
    
    public function setFallbackLang($fallbackLang) {
        if ($this->isInitialized() == true) {
            $this->fallbackLang = $fallbackLang;
        } else {
            throw new BadMethodCallException('This ' . __CLASS__ . ' object is already initalized, so you can not change the fallback language setting.');
        }
    }
    
    public function setForcedLang($forcedLang) {
        if ($this->isInitialized() == true) {
            $this->forcedLang = $forcedLang;
        } else {
            throw new BadMethodCallException('This ' . __CLASS__ . ' object is already initalized, so you can not change the forced language setting.');
        }
    }
    
    public function setSectionSeperator($sectionSeperator) {
        if ($this->isInitialized() == true) {
            $this->sectionSeperator = $sectionSeperator;
        } else {
            throw new BadMethodCallException('This ' . __CLASS__ . ' object is already initalized, so you can not change the section seperator setting.');
        }
    }
    
    /**
     * getUserLangs()
     * Returns the user languages
     * Normally it returns an array like this:
     * 1. Forced language
     * 2. Language in $_GET['lang']
     * 3. Language in $_SESSION['lang']
     * 4. HTTP_ACCEPT_LANGUAGE
     * 5. Fallback language
     * Note: duplicate values are deleted.
     *
     * @return array with the user languages sorted by priority. Highest is best.
     */
    public function getUserLangs() {
        // reset user_lang array
        $userLangs = array();
        
        // Highest priority: forced language
        if ($this->forcedLang != NULL) {
            $userLangs[] = $this->forcedLang;
        }
        
        // 2nd highest priority: GET parameter 'lang'
        if (isset($_GET['lang']) && is_string($_GET['lang'])) {
            $userLangs[] = $_GET['lang'];
        }
        
        // 3rd highest priority: SESSION parameter 'lang'
        if (isset($_SESSION['lang']) && is_string($_SESSION['lang'])) {
            $userLangs[] = $_SESSION['lang'];
        }
        
        // 4th highest priority: HTTP_ACCEPT_LANGUAGE
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $part) {
                $userLangs[] = strtolower(substr($part, 0, 2));
            }
        }
        
        // Lowest priority: fallback
        $userLangs[] = $this->fallbackLang;
        
        // remove duplicate elements
        $userLangs = array_unique($userLangs);
        
        // remove not allowed characters
        foreach ($userLangs as $key => $value) {
            $userLangs[$key] = preg_replace('/[^a-zA-Z0-9]/', '', $value); // only allow a-z, A-Z and 0-9
        }
        
        return $userLangs;
    }
    
    
    /**
     * Parse an ini or yml file to PHP code.
     * This method parses a an the array expression from an ini to PHP code.
     * To be specific it only returns some lines with 'const ###### = '#######;'
     *
     * @return string the PHP code
     */
    public function compile($ini, $prefix = '') {
        $tmp = '';
        foreach ($ini as $key => $value) {
            if (is_array($value)) {
                $tmp .= $this->compile($value, $key . $this->sectionSeperator);
            } else {
                $tmp .= 'const ' . $prefix . $key . ' = \'' . str_replace('\'', '\\\'', $value) . "';\n";
            }
        }
        return $tmp;
    }
    
    public function get_file_extension() {
        return substr(strrchr($this->langFilePath, '.'), 1);
    }
    
    
}