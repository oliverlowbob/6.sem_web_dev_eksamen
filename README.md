# SD Exam 2021 - Music Webshop
## Installing and running the application
In order to run this application, you must first run the chinook_abridged.sql sql script.
Then, you'll need to place the root folder in a htdocs folder in an Apache server.
After running the Apache server, you'll be able to enter the application via the url: 'http://localhost/*Folder-name*/views/login.php'. For example, if your root folder is named music, the url will be: 'http://localhost/music/views/login.php'.

## API Endpoints
For the next part, we assume the root name of the application folder is music, meaning we'll be using the url 'http://localhost/music' as the base url for the API endpoints.

### GET Endpoints
Get track by track id: /music/tracks/{id} 
Get album by album id: /music/albums/{id}
Get artist by artist id: /music/artists/{id}
Get user information on the current logged in user: /music/users/me

Get all tracks: /music/tracks
Get all albums: /music/album
Get all artists: /music/artists
Get all media types: /music/mediaTypes
Get all genres: /music/genres

Verify if current logged in user is admin: /music/admin - Returns true if the user is admin

Log the user out: /music/logout

Search tracks by name: /music/tracks?name='Insert search query' - returns list of tracks containing search query in title
Search albums by name: /music/albums?name='Insert search query' - returns list of albums containing search query in title
Search artists by name: /music/artists?name='Insert search query' - returns list of artists containing search query in title

Get all tracks in album: /music/tracks?albumId='Id of the album' - returns list of tracks in an album

### Put Endpoints


