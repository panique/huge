    <div class="footer">
        <!-- echo out the content of the SESSION via KINT, a Composer-loaded much better version of var_dump -->
        <!-- KINT can be used with the simple function d() -->
        <?php 
        	d($_SESSION);
        	/*	  
			  for more debug, uncomment the following :        	  
        	  d($_SERVER);
        	  d(Lang::getKnownLanguages());
        	  d(Lang::getBrowserLanguages());
        	  d(Lang::getLanguagesConfig());
        	  d(Lang::getDictionary());
        	  d(Lang::getUntranslatedDictionary());
        	  */
        	if (LANG_TRANSLATION_ASSISTANCE) Lang::printTranslationAssistance();
        ?>
    </div>
  </body>
</html>
