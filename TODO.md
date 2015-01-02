### General

- maybe split the install process: mandatory basics, but optional features

- define the goal of php-login: What exactly should the script deliver, what not, where are the limits etc.
  the reason is: there are lots of interesting pull requests and feature ideas, but they are beyond the scope of a
  login script (for example internationalization, staging etc.)

- maybe deactivate all features by default, just deliver a minimal application. all features can then be activated
 with a switch (and every feature has a small installation guideline). this will make the installation much easier.
 
- environment switch
- config files for each environment, a single file for each environment. not perfect as this will result in duplicate
  code, but it's still cleaner than working with lots of "switch/case" here
    
- find a proper solution to hard-stop the application without using exit; (as this will break unit testing for sure)
  http://www.stackoverflow.com/questions/2747791/why-i-have-to-call-exit-after-redirection-through-headerlocation-in-php
  
- same with Application.php: The way this works is very simple, but it uses deep if/else nesting. Should be fixed.
  
- demo users for easy testing out of the box  
 
- nice demo logo / fonts http://hellohappy.org/beautiful-web-type/
 
 - Affiliate-Link IN die demo / script um hoch zu converten
 
 - TODO: check if this works inside a sub-folder
 
 - TODO: split model stuff into real login code and "public" stuff, like getting all profiles, getting an avatar etc.
 
 - TODO: make everything RESTful(ler)
 
 ### Documentation
 
 - clickable index
 
 
 
 
 
 ## 2.1
 
 - uses `<?=` instead of `<?php echo` to make things simpler
  