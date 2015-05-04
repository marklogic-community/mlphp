# MLPHP Examples

*NOTE: The MLPHP examples are being revised and may not be fully functional.*

## Prerequisites

* [MarkLogic 7 or later](http://developer.marklogic.com/products)
* PHP 5.4-enabled web server (e.g., Apache running PHP) with the following extension libraries (typically available by default)
	* DOM
	* cURL
	* XSL
* [MLPHP](https://github.com/marklogic/mlphp)
* [Composer](http://getcomposer.org)


## Instructions

To run the MLPHP Examples, you need to grab a copy of MLPHP with the examples in it and configure your PHP Server as well as some supporting MarkLogic Server databases, users, roles, and REST interfaces.

1. Download a copy of MLPHP.  With git, you can do

	    % git clone git@github.com:marklogic/mlphp mlphp

2. Use composer to pull down dependencies

        % cd mlphp
        % composer install

3. The main MLPHP directory (it contains an 'api' subdirectory among other things) should be named 'mlphp'. Rename it if necessary and put it inside your PHP-enabled server's web directory. You can then browse to the examples like this: `http://localhost/~user/mlphp/examples`.

   (Change the host and path information to reflect your server setup.) If PHP is working, you should see the MLPHP home page at that URL. The applications linked to from the home page will not work correctly until you complete the steps that follow.

4. Start MarkLogic Server. In the MarkLogic Admin interface
   (http://localhost:8001), set up two users:

           user name: rest-writer-user
           password: writer-pw
           role: rest-writer

           user name: rest-admin-user
           password: admin-pw
           role: rest-admin

   To set up the users, click Security, click Users, and then click the Create tab.

   The examples require these specific usernames and passwords with these specific roles. (If you want, you can change the usernames and passwords, by editing `mlphp/examples/setup.php`.)

5. To create and configure MarkLogic databases and REST interfaces needed by the examples, navigate to the MarkLogic Packaging interface here:

   `http://localhost:8002/manage/v1/package/compare/`

   Load the appropriate configuration file located in the mlphp/examples/install directory. For example, if running MarkLogic version 6.0, load:

   `package-6_0.xml`

   This will set up REST servers, databases, and database indexes needed by the MLPHP examples.

   NB.  As of MarkLogic 6, the Packaging tool sets the modules setting for the REST servers incorrectly. Until that bug is fixed, you need to perform the following in the [Admin interface](http://localhost:8001):

   1. Click Configure and then click the "examples-REST" App Server.
   2. Change the modules setting from "(file system)" to
      `examples-REST-modules`. Click ok.
   3. Repeat steps 1 and 2 for `photomap-REST` and `usbills-REST`. Change
      their modules settings to `photomap-REST-modules` and
      `usbills-REST-modules` respectively.

6. The MLPHP examples require the following roles:

        doc-reader
        doc-admin
        doc-editor

   To set up roles, go to the MarkLogic [Admin interface](http://localhost:8001/), click Security, click Roles, and then click the Create tab.

7. Configure the permissions for the the `mlphp/examples/photomap/uploads` directory so PHP can read, write, and execute. For example, for Unix, in your OS console, go to `mlphp/examples/photomap` and execute the following:

        % sudo chmod 777 uploads

8. Setup is complete! To see MLPHP in action, browse to:

        http://localhost/~user/mlphp/examples

   You can also view the code for a simple application:

        mlphp/examples/my_first_app.php

## Troubleshooting

* If you encounter 403 errors, make sure your REST users are set up correctly in step 4.
* You can check your PHP configuration (and see that the DOM, cURL, and XSL libraries are enabled) here: `http://localhost/~user/mlphp/examples/utils/phpinfo.php`
* The REST servers are set up on ports 8077, 8078, and 8079. You can test them directly, for example: `http://localhost:8077`
* If you encounter 400 errors, make sure you have completed step 6.
* Clearing your PHP session can help if you're reinstalling MLPHP. You can clear it here `http://localhost/~user/mlphp/examples/utils/clear_session.php`.

## Copyright
Copyright 2002-2013 MarkLogic Corporation.  All Rights Reserved.

## License
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
