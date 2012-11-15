US Bill Search MLPHP Application

Note: The following steps are not required when setting up MLPHP with the packaging config.xml file.

1. Set up a database:

Database Name: usbills

Recommend that you set up via the App Services interface (http://localhost:8000/appservices/), otherwise you also need to also create forest and attach to the database.

2. Set up a REST server on the usbills database via App Services (http://localhost:8000/appservices/).

Select the usbills database and click Configure. Then under REST API Instances, click Add New:

Server Name: usbills-REST
Port: 8079

3. Set up the following indexes via the Admin interface (http://localhost:8001):

Attribute Range Indexes:

scalar type: int
parent localname: bill
localname: sesssion

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