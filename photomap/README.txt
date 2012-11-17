/*
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
*/

Photomap MLPHP Application

The following are setup steps specific to the photomap application in
mlphp/photomap. You DO NOT need to complete these steps if you completed the
steps in mlphp/README.txt.

1. Set up a database:

   Database Name: photomap

   It is recommended that you set this up via the App Services interface
   (http://localhost:8000/appservices/), otherwise you also need to also
   create a forest and attach it to the database.

2. Set up a REST server on the photomap database via App Services
   (http://localhost:8000/appservices/).

   Select the photomap database and click Configure. Then under REST API
   Instances, click Add New:

   Server Name: photomap-REST
   Port: 8078

3. Set up the following indexes for the photomap database via the Admin
   interface (http://localhost:8001):

   Element Range Indexes:

   scalar type: float
   localname: latitude

   scalar type: float
   localname: longitude

   scalar type: int
   localname: width

   scalar type: int
   localname: height

   scalar type: string
   localname: filename