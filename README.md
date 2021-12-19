# SD Exam 2021 - Music Webshop
## Installing and running the application
In order to run this application, you must first run the chinook_abridged.sql sql script.
Then, you'll need to place the root folder in a htdocs folder in an Apache server.
After running the Apache server, you'll be able to enter the application via the url: 'http://localhost/*Folder-name*/views/login.php'. For example, if your root folder is named music, the url will be: 'http://localhost/music/views/login.php'.

## API Endpoints
For the next part, we assume the root name of the application folder is music, meaning we'll be using the url 'http://localhost/music/' as the base url for the API endpoints. For example, getting a track by track id, will work using the following url: http://localhost/music/tracks/{id} <br/>
All actions, except the login endpoint, requires you to be logged in. Some requires you to be logged in as admin. If admin login is requires, it is specified. <br/>
Trying to access non-existing endpoints will result in 404. 

### GET Endpoints
All endpoints return information in JSON format. <br/>

Get track by track id: /tracks/{id} - returns track <br/>
Get album by album id: /albums/{id} - returns album <br/>
Get artist by artist id: /artists/{id} - returns artist <br/>
Get information on the current logged in user: /users/me - returns user information <br/>

Get all tracks: /tracks - returns list of tracks <br/>
Get all albums: /album - returns list of albums <br/>
Get all artists: /artists - returns list of artists <br/>
Get all media types: /mediaTypes - returns list of media types <br/>
Get all genres: /genres - returns list of genres <br/>

Verify if current logged in user is admin: /admin - returns true if the user is admin <br/>

Log the user out: /logout - destroy session and redirects to login page <br/>

Search tracks by name: /tracks?name='Insert search query' - returns list of tracks containing search query in title <br/>
Search albums by name: /albums?name='Insert search query' - returns list of albums containing search query in title <br/>
Search artists by name: /artists?name='Insert search query' - returns list of artists containing search query in title <br/>

Get all tracks in album: /tracks?albumId='Id of the album' - returns list of tracks in an album <br/>

### Put Endpoints
For the PUT endpoints, it's shown beneath every endpoint, what the server is expecting. <br/>
All PUT endpoints expect JSON format. Furthermore, if a value is optional, there will be added an '?' after it, for example string? <br/>

For the following actions, you must be logged in as admin. <br/>

Update track: /tracks <br/>
{<br/>
&nbsp;    trackId: int,<br/>
&nbsp;    name: string,<br/>
&nbsp;    albumId: int?,<br/>
&nbsp;    mediaTypeId: int,<br/>
&nbsp;    genreId: int?,<br/>
&nbsp;    composer: string?,<br/>
&nbsp;    milliseconds: int,<br/>
&nbsp;    bytes: int?,<br/>
&nbsp;    unitPrice: int<br/>
} <br/>

Update artist: /artists <br/>
{<br/>
&nbsp;    artistId: int,<br/>
&nbsp;    name: string<br/>
} <br/>

Update album: /albums <br/>
{<br/>
&nbsp;    artistId: int,<br/>
&nbsp;    name: string,<br/>
&nbsp;    albumId: int,<br/>
} <br/>

The following PUT endpoints requires user login. <br/>

Update user password: /users - you can only update the password of the user you're logged in as <br/>
{<br/>
    customerId: int,<br/>
    password: string<br/>
} <br/>

Update user information: /users - you can only update information of the user you're logged in as <br/>
{<br/>
    customerId: int,<br/>
    firstName: string,<br/>
    lastName: string,<br/>
    company: string,<br/>
    address: string,<br/>
    city: string,<br/>
    state: string,<br/>
    country: string,<br/>
    postalCode: string,<br/>
    phone: string,<br/>
    fax: string,<br/>
    email: string<br/>
}<br/>

### POST Endpoints
For the POST endpoints, it's also shown beneath what the server is expecting. Some POST endpoints expect JSON format, while others except form-data.<br/> 
Again, optional values will be displayed with a '?'. <br/>
The following actions can only be performed by admin. <br/>

Add track: /tracks - expects form data <br/>
{<br/>
    trackId: int,<br/>
    name: string,<br/>
    albumId: int?,<br/>
    mediaTypeId: int,<br/>
    genreId: int?,<br/>
    composer: string?,<br/>
    milliseconds: int,<br/>
    bytes: int?,<br/>
    unitPrice: int<br/>
} <br/>

Add album: /albums - expects form data <br/>
{<br/>
    artistId: int,<br/>
    name: string,<br/>
    albumId: int,<br/>
} <br/>

Add artist: /artists - expects form data <br/>
{<br/>
    artistId: int,<br/>
    name: string<br/>
} <br/>

Login: /login - expects form data, can be performed by admin and user <br/>
{<br/>
    email: string,<br/>
    password: string<br/>
} <br/>

Signup: /signup - expects form data, can be performed without login <br/>
{<br/>
    firstName: string, <br/>
    lastName: string, <br/>
    password: string,<br/>
    email: string,<br/>
    company: string,<br/>
    address: string,<br/>
    city: string,<br/>
    state: string,<br/>
    country: string,<br/>
    postalCode: string,<br/>
    phone: string,<br/>
    fax: string<br/>
} <br/>

The following actions can only be performed by users. <br/>

Checkout (buy tracks): /invoices - expects JSON format <br/>
{<br/>
    customerId: int,<br/>
    address: string?,<br/>
    city: string?,<br/>
    state: string?,<br/>
    country: string?, <br/>
    postalCode: string?, <br/>
    date: datetime, <br/>
    cart: [{ <br/>
        trackId: int, <br/>
        unitPrice: int <br/>
    }] <br/>
} <br/>

Verify password (used when updating password): users/verify - expects JSON format <br/>
{
    customerId: int, <br/>
    password: string <br/>
} <br/>

### DELETE Endpoints
All DELETE endpoint actions can only be performed by admin. <br/>

Delete track by track id: /tracks/{id} <br/>
Delete album by album id: /albums/{id} <br/>
Delete artist by artist id: /artists/{id} <br/>