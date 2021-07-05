# Bilemo API Project

**Version 1.0.0** 

:computer: This project was created in the context of OpenClassRooms Symfony path. </br>
:briefcase: It is the 7th project, and the first in which Symfony was used. 
It was the first time I could interact with an API, which makes it a key project, given how APIs are an important part of softwares worldwide.

## Installation of the project

1.  Clone the project
>https://github.com/Ludovicmanga/API_Project.git

2.  Install the dependencies
>composer install

3.  Create the database
>php bin/console doctrine:database:create

4.  Generate the migrations files 
>php bin/console make:migration

5.  Execute the migrations files
>php bin/console doctrine:migrations:migrate

6.  Execute the fixtures
>php bin/console doctrine:fixtures:load

:eyes: To test the API, you will need a tool like Postman (https://www.postman.com), that will allow you to create HTTP requests.

:lock: The API is protected with JWT. 

:key: To get the JWT token, you will need to send a request to /api/login_check, and put in the body the email and password of a registered user. A JWT token will be returned. It will allow to access the API, by putting the token in the HEADER, as the value of 'Authorization', along with 'Bearer'.
Example: if the JWT token is 123456 The header must be 'authorization': 'Bearer 123456'