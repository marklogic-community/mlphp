Example Code

The following are setup steps specific to the example code in mlphp/examples.
You DO NOT need to complete these steps if you completed the steps in
mlphp/README.md.

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