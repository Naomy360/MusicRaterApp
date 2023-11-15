# FaveTune
Fourth homework assignment for COMP333: a music rater app called FaveTune developed by Jackson McAvoy and Naomy Chepngeno. 

Worksplit: 50/50 (we worked on a lot of code together, so the difference in commits does not reflect the actual work split). Jackson had several computer issues that made him unable to test the code on his computer, so it was simpler to use one computer.

DISCLAIMER: ChatGPT (OpenAI, 2023) was used for debugging


# How it works
This is a music rating app that allows user to create,read, update and delete song ratings. It was created using React Native frontend  and a PHP/MYSQL backend which communicate via REST API. 


The application first asks the user to either create an account or login with their username and password. After loggin in the user is given an option to create a song rating which will be displayed in a list. The list has edit and delete options that allow the user to either delete or edit the song ratings they submitted.A user cannot rate the same song twice and is not able to delete or make changes to songs added by other users


# Development
#### Backend Setup

1. **Install XAMPP**:
   - Download and install XAMPP from [here](https://www.apachefriends.org/index.html).
   - Start the MySQL and Apache servers through the XAMPP control panel.

2. **Create Database and Tables**:
   - Access MySQL via phpMyAdmin or another database management tool.
   - Create a new database named `music-db`.
   - Create two tables:
     - `users_table` with columns: `username` (VARCHAR), `password` (VARCHAR).
     - `ratings_table` with columns: `id` (INT, primary key, auto-increment), `username` (VARCHAR, foreign key references users_table(username)), `artist` (VARCHAR), `song` (VARCHAR), `rating` (INT(1)).

3. **Clone the Repository**:
   - Open a command prompt or terminal.
   - Navigate to the `htdocs` directory in your XAMPP installation.
   - Run `git clone https://github.com/Naomy360/MusicRaterApp`.

4. **Access the Backend**:
   - Open a browser and navigate to `http://localhost/Public/index.php`.

#### Frontend Setup

1. **Setting Up Expo App**:
   - Open a terminal.
   - Run `npx create-expo-app MusicRaterApp` to initialize a new Expo project.
   - Move into the project directory with `cd MusicRaterApp`.

2. **Start the Expo Server**:
   - Execute `npx expo start` to start the Expo development server.

3. **Clone Frontend Components**:
   - Move the frontend components folder from the cloned repository on htdocs this project directory and update App.js 

### Accessing the Application

1. **Configure IP Addresses**:
   - We hardcoded our own IP addresses. Make sure to update the IP addresses in `Signup.js`, `Login.js`, and `Index.js,' replacing them with your own computer's IP adress. 

2. **Mobile Testing with Expo Go**:
   - Install the Expo Go app on your smartphone.
   - Scan the QR code displayed in your terminal after running `npx expo start`.
3. **Mobile Testing with Android Emulator**
   - Install Android Studio
   - Ensure the file directory points to ./node.js/MusicRaterApp
   - Install Pixel 5 with API 31 from the Device Manager
   - Execute "npx expo start" from the terminal
   - Press the 'a' key to start the Android app (if there is any trouble, press the 'r' key to reload the app)

## MVC architecturer and REST API

## Backend structure:
Our application follows the MVC (Model-View-Controller)  as described below:
- **Controller**-Handles routing commands to the models
    
    * **RatingController.php**-Manages the requests related to user ratings, like adding or retrieving a rating.
    * **UserController.php**-Handles user-related actions, including user registration and authentication.
- **Model**- Manages data and business logic.The model interacts with the database to retrieve, insert, update, or delete data.
    
    * **RatingModel.php**-Represents the rating data and contains logic for rating operations.
    * **User.php**-Encapsulates user data and handles user-specific business logic and database interactions.

    
- **config.php**- Contains configuration settings such as database credentials and API keys
- **Index.php**- primary entry point for REST API.
## Rest API

This section provides an overview of the REST API endpoints available in the application. Each endpoint's purpose, required input, and expected output are described.

The base url for all APIs is

http://localhost/MusicRaterApp/Public/Index.php

We changed *localhost*  to our ownIP address when we were testing our frontend

## API Endpoints

### 1. Create Rating
- **Method**: POST
- **URL**:http://localhost/MusicRaterApp/Public/Index.php

- **Body**:

```json
{
  "action": "createRating",
  "username": "[username]",
  "artist": "[artist]",
  "song": "[song]",
  "rating": [rating]
}

```
### 2. Get Rating

- **Method**: PuT
- **URL**:http://localhost/MusicRaterApp/Public/Index.php

```json
{
  "id": [id],
  "artist": "[artist]",
  "song": "[song]",
  "rating": [rating]
}
```
### 3. Delete rating
- **Method**: Delete
- **URL**:http://localhost/MusicRaterApp/Public/Index.php

```json

{
  "id": [id]
}
```
### 4. Update rating
- **Method**: Put
- **URL**:http://localhost/MusicRaterApp/Public/Index.php

- **Body**:

```json
{
  "id": [id],
  "artist": "[artist]",
  "song": "[song]",
  "rating": [rating],
  "username": "[username]"
}
```




#### REST API TESTING

For manual testing, you can use tools like [Postman](https://www.postman.com/)  make requests to your API and validate the responses.


## Frontend structure:
- **Components:**-houses individiual User Interface elements and functionalities as listed below:
    * **CreateRatingForm.js**-A form component for users to submit new ratings for songs.
    * **login.js**-Contains the login interface and functionality, handling user authentication.
    * **Signup.js**-Provides a signup form for new users to create an account.
    * **SongItem.js**-A component that represents a single song item in a list, displaying details like title and artist
    * **SongList.js**-Compiles a list of `SongItem` components and manages the overall display of songs in the UI
- **App.js**-It defines the main structure and logic of the FaveTune Music Rater App. Handles user login, song ratings and listings.
- **Index.js**-Imports App.js
- **App.css**-styling app.js

## Extra App feature:

Our extra feature is a *search feature* that allows user to search for songs that meet their preferred minimum rating.


### How it works:
#### Example
A user can input 5 into the  search and it will display songs with a minimum of five ratings. The same thing happens if they input 4,3,2 or 1






        

 









