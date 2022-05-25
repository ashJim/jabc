# JABC: JIM ASHFORD'S BREAKFAST CLUB
### Video Demo: https://www.youtube.com/watch?v=g_-spElx0Ps
### Description:
Jim Ashford's Breakfast Club is a Wordpress plugin, designed to help a school organise their breakfast club via their own website. It provides the infrastructure to add users to the website, allows parents to register their details, enroll their children in the breakfast club and view and manage their personal and children's details via a profile page, and gives breakfast club managers an overview of all children registered at the breakfast club, along with their dietary requirements.

#### Compatability
The plugin works with any Wordpress website using a template that has a 'primary' menu location. Aside from this requirement, it creates all of the database custom tables, pages and user roles necessary to carry out it's function.

#### Activation
Upon activation, the plugin automatically creates the infrastructure needed to add the breakfast club functionality to the website onto which it has been installed. Most activation processes are housed within the 'jabc\_breakfast_club.php' main plugin file, expanded upon below.

#### Files
Following is a list of all files and folders included in this plugin, along with their functions.

##### jabc_breakfast_club.php
The main php file for the plugin. This code deals with the plugin's setup, activation and deactivation, along with other key functions. Below is a list of functions present in 'jabc\_breakfast\_club.php', along with their uses:
jabc\_add\_user(): Adds a wordpress user. Called when user submits 'add user' form.
jabc\_hide\_admin\_bar(): Hides the admin bar from the top of the screen when a user is logged in. Called whenever the page is loaded.
jabc\_setup\_pages(): Creates all necessary pages for the breakfast club plugin to function. Called on plugin activation.
jabc\_setup\_tables(): Creates all the necessary tables for the breakfast club plugin to function. Called on plugin activation.
jabc\_setup\_roles(): Creates all the necessary user roles for the breakfast club plugin to function. Called on plugin activation.
jabc\_add\_menu(): Adds the breakfast club menu item to the existing primary menu, if one exists. Creates a primary menu and adds the breakfast club menu item to it, if a primary menu does not already exist. Called on plugin activation.
jabc\_remove\_menu(): Removes the breakfast club menu item. Called on plugin deactivation.
jabc\_remove\_pages(): Removes the pages associated with the breakfast club. Called on plugin deactivation.
jabc\_check\_route(): When the user is on the 'jabc-menu' page, this redirects the user to the correct route, depending on their user roles. Called each time wordpress loads.
jabc\_check\_user\_verification(): checks if user has verified their email address before logging them in. Called at the authentication stage each time the user tries to login.
jabc\_user\_profile\_redirect(): Returns a url which the user is then redirected to each time they login. Called as a filter each time the user logs in.
jabc\_lock\_manager(): Stops users from accessing the manager page if they do not have the 'jabc\_manager' role in their roles list. Called each time wordpress loads.

##### jabc_includes
This folder is for any code that is run as a result of user action within the plugin. The files in this folder deal with the processes concerning this plugin, including CRUD processes and the email verification system.

###### jabc_child_processing.php
This code is responsible for taking child details submitted via a form and using them to create a new database entry in the 'jabc_children' table.
It first creates the variables '$forename', '$surname' and '$dob' and assigns the contents of the data submitted from the form via POST to them. Then it accesses the wordpress database via the global variable $wpdb. Next, it finds the current user's id by selecting the value contained within the 'parent\_id' field for the current user in the 'jabc\_parents' table and assigns it to the variable '$parent\_id'. Finally, it creates a new entry in the 'jabc\_children' table for the new child, using the '$forename'. '$surname' and '$dob' variables to fill the forename, surname and dob values in the table, and the '$parent\_id' variable to fill the 'parent\_id\_fk' field. This is a relational database, so the 'parent\_id\_fk' field is used to link the child to a specific parent.
During development, I began additionally collecting data on dietary requirements, particularly vegetarian/vegan options. This is also added to the table, but I've skipped the process of adding an additional variable and have plugged the appropriate item from the '$\_POST' array, provided by php, directly into the table when creating the new entry.

###### jabc_login_script.php
This code is responsible for sending users to the appropriate part of the site, upon login.
First, it accesses the user's roles list and stores it as an array. It then searches the array for the 'administrator' role. If it finds it, the user is redirected to the admin area of the site. If it doesn't the user is redirected to the site's homepage.

###### jabc_parent_processing.php
This code is responsible for taking parent details submitted via a form and using them to create a new database entry in the 'jabc_parents' table.
It first creates the variables '$title', '$forename', '$surname' and '$tel' and assigns the contents of the data submitted from the form via POST to them. Then it accesses the wordpress database via the global variable $wpdb. Finally, it creates a new entry in the 'jabc\_parents' table for the new parent, using the '$forename'. '$surname' and '$tel' variables to fill the 'parent\_forename', 'parent\_surname' and 'parent\_tel' values in the table. The wordpress function 'get\_current\_user\_id()' is used to plug in the current user's id to the 'parent\_wp\_user\_fk' field as a foreign key to identify which wordpress user this parent data is associated with.

###### jabc_remove_child.php
This code allows a parent to remove a child from the breakfast club when they no longer plan to attend. It is responsible for removing a specified child entry from the 'jabc\_children' table.
The user is sent to this code when they click a link on their profile page, displayed next to their child, which says 'delete'. A query string is added to the link, including the specific child's id. This id is used to identify which child entry needs to be removed. First, it accesses the wordpress database via the '$wpdb' object. Next, it runs a delete method attributed to the $wpdb object. This delete method removes, from the 'jabc\_children' table, the child with the same id as was sent in the query string. Finally, it redirects the user back to their parent profile page.

###### jabc_remove_parent.php
This code removes the current user's breakfast club parent records and disassociates them with the breakfast club as a parent.
First, it looks for records of all children in the 'jabc\_children' table who are associated with the selected parent, then it deletes their entries. Next, it deletes the parent entry from the 'jabc\_parents' table which is linked to the current user. Following this, it removes the 'jabc\_parent' role from the current user, so they are no longer associated with the breakfast club as a parent. Finally, it redirects the user back to the home page of the school's website.

###### jabc_user_processing.php 
This code handles the registration of a new user, the inclusion of email verification metadata and the automated sending of a verification email.
The user is sent to this code following their submission of an 'add user' form, detailed in the file 'jabc\_templates/jabc\_form\_add\_user.php'. First, it checks if the user's email already exists on file. If it does, the user is redirected to the home page without being created. If it doesn't, the user is sent to part of the wordpress admin, 'wp-admin/admin-post.php'. In the main plugin file, 'jabc\_breakfast\_club.php', a function called 'jabc\_add\_user' is hooked to this wordpress admin page, and this function is responsible for adding the user to the wp-users table as a wordpress user. Next, it accesses the new user object and adds a verification code as a piece of their metadata. This code is made up of a combination of the user's email address and a randomly generated number. An email is then generated and automatically sent to the given email address. This email contains a link to 'jabc\_verify\_email.php', along with a query string consisting of two variables: the verification code taken directly from the user's metadata and the user id of the current user. 'jabc\_verify\_email.php' is explained in more detail below. The use of the user's email address in the verification code ensures that the user's code is unique to them, while the random number ensures that users need to have the link emailed to them, stopping them from being able to guess the code and verify their email address by typing their own query string direct into the browser's url. After sending the email, the code finally redirects the user to a page prompting them to check their email and verify their email address.

###### jabc_verify_email.php
This code runs when the user tries to verify their email address by clicking the verification link that is emailed to them. It checks whether the verification code matches the one in their metadata and verifies them if so.
First, we must understand how this verification system works. We already know from 'jabc\_user\_processing' that a verification code is added to the user's metadata upon their registering as a user. This verification code is called 'email\_verified'. Each time a user tries to log in to the website, checks are made to see whether there is a piece of metadata called 'email\_verified' attributed to the current user. As long as this metadata exists for the current user, they will not be allowed to log in. 'jabc\_verify\_email' must, therefore, delete this piece of metadata in order to allow the user to log in. First it stores the variables from the query string into the variables '$verify' and '$user\_id'. Next, it accesses the user's 'email\_verified' metadata value and stores it in the variable '$auth'. Finally, it compares the '$verify' variable taken from the query string with the '$auth' variable taken from the user's metadata. If they match, it deletes the metadata and prompts the user that their email address has been verified. Otherwise, it displays an error.

##### jabc_templates
This folder is for any templates that have been created within the plugin. The files in this folder deal with generating all of the forms and page templates required to run the plugin.
First, a quick note on templates and how they are displayed in this plugin. At the bottom of the main plugin file, 'jabc\_breakfast\_club.php', you will find some 'add\_shortcode' functions. Wordpress uses shortcode to plug functions into it's pages and posts, without having to write code directly onto the page. The following templates take advantage of this functionality by linking pieces of shortcode to functions which display the templates for various pages and forms required by the plugin. These same pieces of shortcode are added to pages as content when the pages are created during the plugin's activation. The function which handles the creation of required pages is in the main plugin file, 'jabc\_breakfast\_club.php', and is called 'jabc\_setup\_pages'. You will notice that, for each page where a template is required, a piece of shortcode has been attributed to the 'post\_content' key of that page's array. These shortcodes match to the shortcodes created at the bottom of the file which, in turn, link to the function names in the files in the templates folder.

###### jabc_form_add_child.php
This is the template for the form that is used to add child details to the 'jabc\_children' table. The form submits these details to the aforementioned file 'jabc\_child\_processing.php', where the new child entry is created.

###### jabc_form_add_parent.php
This is the template for the form that is used to add parent details to the 'jabc\_parents' table. The form submits these details to the aforementioned file 'jabc\_parent\_processing.php', where the new parent entry is created.

###### jabc_form_add_user.php
This is the template for the form that is used to add a wordpress user to the site. The form submits these details to the aforementioned file 'jabc\_user\_processing.php', where the new user is created.

###### jabc_manager.php
This template displays personalised data to a breakfast club manager. First, it pulls from the 'jabc\_children' table all data regarding children who attend the breakfast club and stores it in an array variable titled '$children'. Next, it displays a list of the names and dietary requirements of each of the children stored in the array, using a php foreach loop and html in echo statements.

###### jabc_parent.php
This template displays personalised data to a breakfast club parent. First, it stores the current user object in a variable '$user' and accesses that user object's email address. It then selects the user's parent data from the 'jabc\_parents' table, using the user's id as reference. The results are stored in a '$parent' array. Following this, it selects entries from the 'jabc\_children' table which are attributed to the parent and stores them in an array called '$children'. Finally, it displays the data stored in the arrays, using a mixture of php and html.

