Photomap MLPHP Application

The following are setup steps specific to the photomap application in
mlphp/photomap. You DO NOT need to complete these steps if you completed the
steps in mlphp/README.md.

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

3. Set up the following indexes via the Admin interface (http://localhost:8001):

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