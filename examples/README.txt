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

Example Code

The following are setup steps specific to the example code in mlphp/examples.
You DO NOT need to complete these steps if you completed the steps in
mlphp/README.txt.

1. Set up a REST server on the Documents database via App Services
   (http://localhost:8000/appservices/).

   Select the Domcuments database and click Configure. Then under REST API
   Instances, click Add New:

   Server Name: examples-REST
   Port: 8077

2. Set up three roles for the examples.

   In Admin (http://localhost:8001), click Security, click Roles, and then click the Create tab:

   role name: doc-reader
   role name: doc-admin
   role name: doc-editor