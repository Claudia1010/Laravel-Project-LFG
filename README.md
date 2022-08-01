

<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/geekhubs.png">

# Web Application LFG

The aim of this 6th GeeksHubs project is to build a web app LFG, "Looking for Group". This app should allow a company's employees to stay in contact with each other, chatting in different categories channels and playing online videogames.

## User Guide

### Installation

#### Follow the steps mentioned below to install and run the project.

1. Clone or download the repository
```bash
git clone https://github.com/Claudia1010/Laravel-Project-LFG.git
```
2. Go to the project directory and run composer install
```bash
composer install
```
3. Create .env file by copying the .env.example. You may use the command to do that cp .env.example .env, but make sure the .env already exists.
```bash
cp .env.example .env
```
4. Update the database name and credentials from the Workbench database, in the .env file

5. Run the command php artisan jwt:secret
```bash
php artisan jwt:secret
```
6. Run the command php artisan migrate
```bash
php artisan migrate
```
7. Run the command php artisan db:seed, para crear los registros en la base de datos.
```bash
php artisan db:seed
```
8. Run php artisan serve from the project root  
```bash
php artisan serve
```


## Database and relationships

As we can see in this screenshot taken from Workbench, the database consists in 7 simple tables connected between

them: users, roles, role_user, games, channels, channel_user and messages.

<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot.png">

The relationship between users and roles is "many to many" , so we must create an intermediate table like in this 

case called role_user.

The relationship between users and role_user is "1 to many" (1:n), and the relationship between roles and 

role_user is exactly the same (1:n).

The same kind of relationship has the table users with games, is "1 to many" (1:n), because one user can create 

many games but a game can be created for only one user, the admin in this case.

Exactly the same happens between games and channels, one game can have multiple channels (similar to chat rooms) 

and a channel belongs to a single game (like a category), it is again a "1 to many" (1:n).

But relationship between channels and users is "many to many", and the intermediate table is called channel_user 

where we will keep the foreigns keys for user_id and channel_id.

Then we have another 2 more "1 to many" (1:n) table relationships: between channels and messages, because one 

channel might have multiple messages, and one specific message belongs only to one channel. And between users and 

messages, because one user can write many messages but one message can be written only by one particular person.


## Endpoints

The root for all the endpoints will be:

http://localhost:8000

Endpoint-function links: The method to enter in Postman is specified, and what we must enter after the root to 

access each of the endpoints:

#### No Token required:

- POST to REGISTER a user: ('/register', [AuthController::class, 'register'])
- POST to LOGIN a user, where the token will be given: ('/register', [AuthController::class, 'login'])

#### Users:

- GET the user profile: ('/profile', [AuthController::class, 'getProfile'])
- PUT to update the user profile: ('/update', [AuthController::class, 'updateProfile'])
- POST to logout from the session: ('/logout', [AuthController::class, 'logout'])
- DELETE to delete the user profile from the app:('/delete', [AuthController::class, 'deleteProfile'])

- GET to check all the games available: ('/getAllGames', [GameController::class, 'getAllGames'])

- GET to check all the channels created: ('/getAllChannels', [ChannelController::class, 'getAllChannels'])
- GET to filter the channels by the game id: 
('/findChannelsById/{id}', [ChannelController::class, 'findChannelByGameId']);

- POST to join a channel, where it will be attached the channel_id: 
('/accessChannel', [UserController::class, 'accessChannel'])
- POST to leave a channel, where the channel_id given will be removed:
('/leaveChannel', [UserController::class, 'leaveChannel'])

- GET to bring all the messages from a channel id:
('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId'])
- POST to create a new message: ('/createMessage', [MessageController::class, 'createMessage'])
- PUT to update an existing message, checking it belongs to the creator: 
('/updateMessage/{id}', [MessageController::class, 'updateMessageById'])
- DELETE to delete a message given the message id:
('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById'])

<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot2.png">
<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot3.png">
<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot4.png">
<br/>

#### Admins:

- POST to create a new game, giving some details like name, genre, age and developer:
('/createGame', [GameController::class, 'createGame'])
- GET to check all the games created by the admin registered: ('/getMyGames', [GameController::class, 'getMyGames'])
- PUT to update any attribute of an existing game: ('/updateMyGame/{id}', [GameController::class, 'updateMyGame'])

- POST to create a new channel: ('/createChannel', [ChannelController::class, 'createChannel'])
- PUT to update a channel: ('/updateChannel/{id}', [ChannelController::class, 'updateChannelById'])

Also, and obviously, the admins can create, update, see and delete messages:
    - POST('/createMessage', [MessageController::class, 'createMessage'])
    - GET('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId'])
    - PUT('/updateMessage/{id}', [MessageController::class, 'updateMessageById'])
    - DELETE('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById'])

<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot5.png">
<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot6.png">
<br/>

#### Superadmins:

- POST to promote an user to admin role: ('/user/admin/{id}', [UserController::class, 'userToAdmin'])
- POST to downgrade an admin to user: ('/user/remove_admin/{id}', [UserController::class, 'adminToUser'])
- POST to promote a user to superadmin: ('/user/super_admin/{id}', [UserController::class, 'userToSuperAdmin'])
- POST to downgrade a superadmin to user: 
('/user/remove_superadmin/{id}', [UserController::class, 'superAdminToUser'])
- GET to check all the existing users: ('/getAllUsers', [UserController::class, 'getAllUsers'])

Only the superadmins could delete channels and games given the Id by params (URL):

- DELETE('/deleteChannel/{id}', [ChannelController::class, 'deleteChannelById'])
- DELETE('/deleteGame/{id}', [GameController::class, 'deleteGameById'])

Also the superadmins can create, update, see and delete messages:

- POST('/createMessage', [MessageController::class, 'createMessage'])
- GET('/getAllMessages/{id}', [MessageController::class, 'getAllMessagesByChannelId'])
- PUT('/updateMessage/{id}', [MessageController::class, 'updateMessageById'])
- DELETE('/deleteMessage/{id}', [MessageController::class, 'deleteMessageById'])

<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot7.png">
<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot8.png">
<br/>
<img src="https://github.com/Claudia1010/Laravel-Project-LFG/blob/main/public/img/screenshot9.png">
<br/>

## Technologies

<code><img width="10%" src="https://www.vectorlogo.zone/logos/heroku/heroku-ar21.svg"></code>
<code><img width="10%" src="https://www.vectorlogo.zone/logos/mysql/mysql-ar21.svg"></code>
<code><img width="10%" src="https://www.vectorlogo.zone/logos/laravel/laravel-ar21.svg"></code>
<code><img width="10%" src="https://www.vectorlogo.zone/logos/php/php-icon.svg"></code>
<code><img width="10%" src="https://www.vectorlogo.zone/logos/getpostman/getpostman-ar21.svg"></code>