Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

# MLPHP

PHP API for MarkLogic

MLPHP enables you to interact with a MarkLogic database using the PHP scripting
language. For example, you can use MLPHP to load documents, read and write
metadata, build search options nodes, and search the MarkLogic database. MLPHP
communicates with MarkLogic via the REST API, which is included in MarkLogic 6.

## Requirements

* MarkLogic 6
* PHP-enabled web server (e.g., Apache running PHP)
* DOM and cURL libraries enabled in PHP (this is the default in newer versions
of PHP)

## Set Up MLPHP

1. Put the mlphp directory inside your PHP-enabled server's web directory. You
   can then access the files, for example, like this:

   http://localhost/~user/mlphp/

   If PHP is working, you should be able to access that URL and see the MLPHP
   home page. The applications linked to from that page will not work correctly
   until you complete the steps that follow.

2. Start your MarkLogic 6 server. In the MarkLogic Admin interface
   (http://localhost:8001), set up two REST users:

   user name: rest-writer-user
   password: writer-pw
   role: rest-writer

   user name: rest-admin-user
   password: admin-pw
   role: rest-admin

   To set up the users, click Security, click Users, and then click the Create
   tab.

   MLPHP requires these roles and expects these usernames and passwords. If
   needed, you can change the usernames and passwords here:

   mlphp/setup.php

3. Access the MarkLogic Packaging interface here:

   http://localhost:8002/manage/v1/package/compare/

   Load the configuration file located in the mlphp directory:

   mlphp/config.xml

   This will set up REST servers, databases, and database indexes. The API
   classes, examples, and demos require these in order to work.

3a. Currently, the Packaging tool sets the modules setting for the REST
   servers incorrectly. Until that bug is fixed, you need to perform the
   following in the Admin interface (http://localhost:8001):

   1. Click Configure and then click the "examples-REST" App Server.
   2. Change the modules setting from "(file system)" to
      "examples-REST-modules". Click ok.
   3. Repeat steps 1 and 2 for "photomap-REST" and "usbills-REST". Change
      their modules settings to "photomap-REST-modules" and
      "usbills-REST-modules" respectively.

4. The code examples require the following roles:

   doc-reader
   doc-admin
   doc-editor

   To set up roles, go to the Admin interface (http://localhost:8001/), click
   Security, click Roles, and then click the Create tab.

5. Configure the permissions for the the mlphp/photomap/uploads directory so
   PHP can read, write, and execute. For example, in your OS console, go to
   mlphp/photomap and execute the following:

   sudo chmod 777 uploads

6. Setup is complete! To see MLPHP in action, access the following:

   http://localhost/~user/mlphp/

   You can also view the code for a simple application:

   mlphp/my_first_app.php

## Troubleshooting

* If you encounter 403 errors, make sure your REST users are set up correctly
  in step 2.
* You can check your PHP configuration (and see that the DOM and cURL
  libraries are enabled) here: http://localhost/~user/mlphp/phpinfo.php
* The REST servers are set up on ports 8077, 8078, and 8079. You can test them
  directly, for example: http://localhost:8077
* If you encounter XXX errors, make sure you have completed step 3a.
* Clearing your PHP session can help if you're reinstalling MLPHP. You can
  clear it here: http://localhost/~user/mlphp/clear_session.php.