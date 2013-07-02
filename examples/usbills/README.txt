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

US Bill Search MLPHP Application

The following are setup steps specific to the usbills application in
mlphp/usbills. You DO NOT need to complete these steps if you completed the
steps in mlphp/README.txt.

1. Set up a database:

   Database Name: usbills

   It is recommended that you set this up via the App Services interface
   (http://localhost:8000/appservices/), otherwise you also need to also
   create a forest and attach it to the database.

2. Set up a REST server on the usbills database via App Services
   (http://localhost:8000/appservices/).

   Select the usbills database and click Configure. Then under REST API
   Instances, click Add New:

   Server Name: usbills-REST
   Port: 8079

3. Set up the following indexes for the usbills database via the Admin
   interface (http://localhost:8001).

   Attribute Range Indexes:

   scalar type: int
   parent localname: bill
   localname: session

   scalar type: string
   parent localname: bill
   localname: type

   scalar type: int
   parent localname: bill
   localname: number

   scalar type: string
   parent localname: bill
   localname: abbrev

   scalar type: string
   parent localname: introduced
   localname: date

   scalar type: string
   parent localname: link
   localname: href

   Element Range Indexes:

   scalar type: string
   localname: status

   scalar type: string
   localname: subject

   scalar type: string
   localname: title