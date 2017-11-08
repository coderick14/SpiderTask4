### Spider Task 4

This project involves making a bulletin board where users can login or signup to share their posts. Thus it consists of an authentication and authorization system. There are three access levels, namely Visitor,Editor and Admin. A newly signed up user has the access level of Visitor.  
The access rights are as follows:  
+ **Visitor**:Can view posts
+ **Editor** :Can view and add posts
+ **Admin**  :Can view,add and delete posts. Can view and change access levels of other registered users(except admins). Can moderate the posts of any Editor. By moderation,we mean that the posts made by that particular Editor will not appear on the bulletin board directly. Instead, they will show up in the Admin Panel where the Admin can choose to allow or discard the post.

----

**Framework used : PHP on Apache**  
**Database 	 : MySQL**  
**Server	 : Apache2**  

Below are the links for downloading all the necessary software required to run the scripts :

#### For Windows
+ Install Apache. [Click here](https://www.sitepoint.com/how-to-install-apache-on-windows/) to install. It contains all the links and a step by step guide about the installation.
+ Install php5. [This link](https://www.sitepoint.com/how-to-install-php-on-windows/) provides a step by step method on how to install and configure php5 on your system.
+ Install MySQL. [This link](https://www.sitepoint.com/how-to-install-mysql/) provides a step by step method for doing this

#### For Linux
+ Install Apache. Open your terminal. Type **sudo apt-get install apache2**. Start your server with **sudo /etc/init.d/apache2 start**.
+ Install php5. Type **sudo apt-get install php5 libapache2-mod-php5** and **sudo apt-get install php5-mysql**. Restart your server with the command **sudo /etc/init.d/apache2 restart**.
+ Install MySQL. Type **sudo apt-get install mysql-server**. 
In case of any trouble, [click here](https://www.linux.com/learn/easy-lamp-server-installation) for a detailed instruction on how to set up a LAMP Server. 

----

The details about the database and the tables used are as follows :
+ Create an user with all grant privileges, say "MyUsername" or you may use any existing user with all grant privileges.
+ In case you created a new user, set up a password, say "MyPassword"
+ Create a database after logging in with the above username and password, say "MyDatabase". You may use any existing database as well(Not recommended).
+ Run `mysql -u root -p DB_NAME < db_setup.sql`
----

**Captcha System**

+ The signup page uses Google reCaptcha to prevent bot users.
+ Go to [this link](https://www.google.com/recaptcha/intro/index.html). Click on **get reCaptcha** button in top right corner.
+ Sign in through your Gmail account.(If you are already signed up, then ignore this step).
+ In the **Register a new site** box, type in a label(say localhost) and your domain name(say localhost). 
+ Click on **Register**.
+ You will get two keys, a public key and a private key.
+ Copy the private key. Open signup.php. In the line
```html
$privatekey = "Your-private-key";
```
replace the string "Your-private-key" with your own secret/private key.
+ Copy the public key. Open signup.php. You will see a line 
```html
<div class="g-recaptcha" data-sitekey="Your-public-key"></div>
```
Paste this public key in the 'data-sitekey' attribute,replacing "Your-public-key".

----

**After you are done with the above steps, make necessary changes to connect.php script**.

The **mysqli** library has been used for connecting to the database.

####How to run the scripts
+ Clone this repository into the folder you want. 
+ Start your apache server.
+ Copy all the files from SpiderTask4 to your localhost directory.(Usually C:/inetpub/wwwroot for Windows and /var/www/html for Linux).
+ Open up your browser. Type http://localhost/ as the URL.
+ Click on login.php
