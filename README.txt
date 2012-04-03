Installation steps:

- Create a new db with your desired name
- Import the following files in the following order to the database using a tool such as PHPMYADMIN:
  - protected/data/schema.sql
  - protected/data/inserts.sql
- Make sure you change the configuration values located under protected/config/main.php such as:
  - facebookappid
  - facebookapikey
  - facebookapisecret
  - emailin
  - emailout
- Make sure you change the DB values in both protected/config/dev.php and protected/config/production.php
- Edit index.php file and change the 'CURRENT_ACTIVE_DOMAIN' constant to the domain you will be using, This is required for the url routes to work.

- Access the application and you should see the index page of the site module. 
- In order to access the application admin control panel, Navigate to the /admin module IE: http://site.com/admin
  and use the following username and password to access the admin panel and have the admin role permissions:
  - User: admin
  - Password: admin
  
