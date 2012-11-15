SETTING UP MLPHP

Requirements:

- MarkLogic 6
- PHP-enabled web server (e.g., Apache running PHP)
- DOM and cURL libraries enabled in PHP (this is the default in newer versions of PHP)

1. Put the mlphp directory inside your PHP-enabled server's web directory. You can then access the files, for example, like this:

http://localhost/~user/mlphp/

To test that PHP is running, access:

http://localhost/~user/mlphp/phpinfo.php

2. In the MarkLogic Admin interface, set up two REST users:

User: rest-writer-user
Password: writer-pw
Role: rest-writer

User: rest-admin-user
Password: admin-pw
Role: rest-admin

To set up the users, go to the Admin interface (http://localhost:8001/), click Security, click Users, and then click the Create tab.

MLPHP requires these roles and expects these usernames and passwords. If needed, you can change the usernames and passwords here: mlphp/setup.php

3. Access the MarkLogic configuration interface here:

http://localhost:8002/manage/v1/package/compare/

and load the configuration file located in the mlphp directory:

mlphp/config.xml

This will set up REST servers, databases, and database indexes. The API classes, examples, and demos require these in order to work.

4. The code examples require the following roles:

doc-reader
doc-admin
doc-editor

To set up roles, go to the Admin interface (http://localhost:8001/), click Security, click Roles, and then click the Create tab.

5. The photomap application requires the following:

- Configure the permissions for the the mlphp/photomap/uploads directory so that PHP can read, write, and execute.
- Add a Google Maps key to the photomap setup file: mlphp/photomap/setup.php

6. Setup is complete! To see MLPHP in action, access the following:

http://localhost/~user/mlphp/

Troubleshooting:

- If you encounter 403 errors, make sure your REST users are set up correctly in step 2.
- You can check your PHP configuration (and see that the DOM and cURL libraries are enabled) here: http://localhost/~user/mlphp/phpinfo.php
- The REST servers are set up on ports 8077, 8078, and 8079. You can test them directly, for example: http://localhost:8077