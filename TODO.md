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
 
 - TODO: affiliate-links direkt ins frontend. prominent platzieren, klar kommunizieren. kein gebullshitte.
 
 ### Documentation
 
 - clickable index
 
 ## Possible text-elements for clear "mission statement":
 
 - clearly define that php-login is just a simple tool, useful for smaller applications
 - clearly define that this is the unpaid free-time work of some people. Don't complain, don't bash, don't do 
   "why is there no feature X ?". Be respectful.
 - If this script is useful for you, then try to give something back: Testing, bug reports, take part in discussions,
   give ideas for improvements, write a tutorial or record a video-guideline, consider donating or commit useful code.
   Everything is welcome!
 - Respect the time and the nerves of open-source developers. Please don't write "script not working plz help" messages
   here or anywhere. 
 - Respect the work behind this project.
 - This is totally free software, written by people like you. Don't treat this project like you have paid 1000s of
   dollars for it. It's free. Don't complain. If you don't like it, don't use it. 
 - Respect that this software might save you hundreds of hours of development costs.  
 
  final disclaimer !   
  legal disclaimer / scam warning
 
 ## 2.1
 
 - uses `<?=` instead of `<?php echo` to make things simpler
 - DatabaseFactory [maybe rebuild this a litte bit]
 - Static methods in model: You can use every model method everywhere, like NoteModel::getNoteById(17);
 - No injection of database needed anymore (model methods will get DB connection themselves) -> refactoring ?
 
 ## Interesting links
 
 Future of user auth on the web
 http://www.lukew.com/ff/entry.asp?1906
 https://medium.com/@ninjudd/passwords-are-obsolete-9ed56d483eb
 
 