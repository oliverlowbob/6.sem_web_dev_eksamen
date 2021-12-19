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
For the PUT endpoints, it's shown beneath every endpoint, what the server is expecting. All PUT endpoints expect JSON format. Furthermore, if a value is optional, there will be added an '?' after it, for example string? <br/>

For the following actions, you must be logged in as admin. <br/>

Update track: /tracks <br/>
{
    trackId: int,
    name: string,
    albumId: int?,
    mediaTypeId: int,
    genreId: int?,
    composer: string?,
    milliseconds: int,
    bytes: int?,
    unitPrice: int
} <br/>

Update artist: /artists <br/>
{
    artistId: int,
    name: string
} <br/>

Update album: /albums <br/>
{
    artistId: int,
    name: string,
    albumId: int,
} <br/>

The following PUT endpoints requires user login. <br/>

Update user password: /users - you can only update the password of the user you're logged in as <br/>
{
    customerId: int,
    password: string
} <br/>

Update user information: /users - you can only update information of the user you're logged in as <br/>
{
    customerId: int,
    firstName: string,
    lastName: string,
    company: string,
    address: string,
    city: string,
    state: string,
    country: string,
    postalCode: string,
    phone: string,
    fax: string,
    email: string
}

### POST Endpoints
For the POST endpoints, it's also shown beneath what the server is expecting. Some POST endpoints expect JSON format, while others except form-data. Again, optional values will be displayed with a '?'. <br/>
The following actions can only be performed by admin. <br/>

Add track: /tracks - expects form data <br/>
{
    trackId: int,
    name: string,
    albumId: int?,
    mediaTypeId: int,
    genreId: int?,
    composer: string?,
    milliseconds: int,
    bytes: int?,
    unitPrice: int
} <br/>

Add album: /albums - expects form data <br/>
{
    artistId: int,
    name: string,
    albumId: int,
} <br/>

Add artist: /artists - expects form data <br/>
{
    artistId: int,
    name: string
} <br/>

Login: /login - expects form data, can be performed by admin and user <br/>
{
    email: string,
    password: string
} <br/>

Signup: /signup - expects form data, can be performed without login <br/>
{
    firstName: string, 
    lastName: string, 
    password: string,
    email: string,
    company: string,
    address: string,
    city: string,
    state: string,
    country: string,
    postalCode: string,
    phone: string,
    fax: string
} <br/>

The following actions can only be performed by users. <br/>

Checkout (buy tracks): /invoices - expects JSON format <br/>
{
    customerId: int,
    address: string?,
    city: string?,
    state: string?,
    country: string?,
    postalCode: string?,
    date: datetime,
    cart: [{
        trackId: int,
        unitPrice: int
    }]
} <br/>

Verify password (used when updating password): users/verify - expects JSON format <br/>
{
    customerId: int,
    password: string
} <br/>

### DELETE Endpoints
All DELETE endpoint actions can only be performed by admin. <br/>

Delete track by track id: /tracks/{id} <br/>
Delete album by album id: /albums/{id} <br/>
Delete artist by artist id: /artists/{id} <br/>