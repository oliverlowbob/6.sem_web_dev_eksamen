<?php
include_once("nav.php");

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../views/login.php');
}

?>
<section class="dropdown">
    <button onclick="dropdownBtnClick()" class="dropbtn">Menu</button>
    <div id="myDropdown" class="dropdown-content">
        <a href="#" onclick="showProfile()">Edit Profile</a>
        <a href="http://localhost/music/logout/">Logout</a>
    </div>
</section>
<h1>Music Shop</h1>
<main>
    <section id="searchSection">
        <select name="searchOptions" id="searchOptions">
            <option value="track" selected>Track</option>
            <option value="album">Album</option>
            <option value="artist">Artist</option>
        </select>
        <input type="text" id="searchQuery" placeholder="Search here">
        <button onclick="searchBtnClick()" class="frontpageBtn" id="searchBtn">Search</button>
        <button onclick="addBtnClick()" class="frontpageBtn" id="addBtn">Add</button>
    </section>
    <section>
        <div id="profileSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="hideProfile()">&times;</span>
                <ul>
                    <br>
                    <p hidden id="customerId"></p>
                    <p><strong>Email</strong></p>
                    <li><input type="text" id="userEmail" class="trackInput"></li>
                    <p><strong>First Name</strong></p>
                    <li><input type="text" id="userFirstName" class="trackInput"></li>
                    <p><strong>Last Name</strong></p>
                    <li><input type="text" id="userLastName" class="trackInput"></li>
                    <p><strong>Company</strong></p>
                    <li><input type="text" id="userCompany" class="trackInput"></li>
                    <p><strong>Address</strong></p>
                    <li><input type="text" id="userAddress" class="trackInput"></li>
                    <p><strong>City</strong></p>
                    <li><input type="text" id="userCity" class="trackInput"></li>
                    <p><strong>State</strong></p>
                    <li><input type="text" id="userState" class="trackInput"></li>
                    <p><strong>Country</strong></p>
                    <li><input type="text" id="userCountry" class="trackInput"></li>
                    <p><strong>Postal Code</strong></p>
                    <li><input type="text" id="userPostalCode" class="trackInput"></li>
                    <p><strong>Phone</strong></p>
                    <li><input type="text" id="userPhone" class="trackInput"></li>
                    <p><strong>Fax</strong></p>
                    <li><input type="text" id="userFax" class="trackInput"></li>
                </ul>
                <button id="saveProfileBtn" onclick="saveProfileInfo()">Save Info</button>
                <br> <br>
                <h3>Password Management</h3>
                <br>
                <ul>
                    <p><strong>Old Password</strong></p>
                    <li><input type="password" id="userOldPassword" class="trackInput"></li>
                    <p><strong>New Password</strong></p>
                    <li><input type="password" id="userNewPassword1" class="trackInput"></li>
                    <p><strong>New Password (repeat)</strong></p>
                    <li><input type="password" id="userNewPassword2" class="trackInput"></li>
                </ul>
                <button id="savePasswordBtn" onclick="updatePassword()">Update Password</button>
                <button id="deleteProfileBtn" onclick="deleteProfile()">Delete Profile</button>
            </div>
        </div>
    </section>
    <section>
        <div id="addAlbumSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="showFrontPageTracks()">&times;</span>
                <form action="http://localhost/music/albums/" method="post">
                    Title: <input type="text" name="title"><br>
                    Artist: <input type="text" name="artist"><br>
                    <input class="frontpageBtn" type="submit" value="Add">
                </form>
            </div>
        </div>
        <div id="addTrackSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="showFrontPageTracks()">&times;</span>
                <form action="http://localhost/music/tracks/" method="post">
                    Name: <input type="text" name="name"><br>
                    Album: <input type="text" name="albumId"><br>
                    MediaType: <input type="text" name="mediaTypeId"><br>
                    Genre: <input type="text" name="genreId"><br>
                    Composer: <input type="text" name="composer"><br>
                    Milliseconds: <input type="text" name="milliseconds"><br>
                    Bytes: <input type="text" name="bytes"><br>
                    Price ($): <input type="text" name="unitPrice"><br>
                    <input type="submit" value="Add">
                </form>
            </div>
        </div>
        <div id="addArtistSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="showFrontPageTracks()">&times;</span>
                <form action="http://localhost/music/artists/" method="post">
                    Name: <input type="text" name="name"><br>
                    <input type="submit" value="Add">
                </form>
            </div>
        </div>
    </section>
    <section id="resultTrackSection">
        <table id="trackTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Album</th>
                    <th>Media Type</th>
                    <th>Genre</th>
                    <th>Composer</th>
                    <th>Length</th>
                    <th>Size</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
    <section id="resultAlbumSection">
        <table id="albumTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
    <section id="resultArtistSection">
        <table id="artistAlbumsTable">
            <thead>
                <tr>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
    <section>
        <div id="trackInfoSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="showFrontPageTracks()">&times;</span>
                <p hidden id="trackId"></p> <br>
                <p hidden id="albumId"></p> <br>
                <p hidden id="genreId"></p> <br>
                <p hidden id="mediaTypeId"></p> <br>
                <ul>
                    <p><strong>Title</strong></p>
                    <li><input type="text" id="trackName" class="trackInput"></li>
                    <p><strong>Album</strong></p>
                    <li><input type="text" id="trackAlbum" class="trackInput"></li>
                    <p><strong>Media Type</strong></p>
                    <li><input type="text" id="trackMediaType" class="trackInput"></li>
                    <p><strong>Genre</strong></p>
                    <li><input type="text" id="trackGenre" class="trackInput"></li>
                    <p><strong>Composer</strong></p>
                    <li><input type="text" id="trackComposer" class="trackInput"></li>
                    <p><strong>Time</strong></p>
                    <li><input type="text" id="trackTime" class="trackInput"></li>
                    <p><strong>Size</strong></p>
                    <li><input type="text" id="trackSize" class="trackInput"></li>
                    <p><strong>Prize</strong></p>
                    <li><input type="text" id="trackPrice" class="trackInput"></li>
                </ul>
                <button id="saveTrackBtn" onclick="saveTrackInfo()">Save</button>
            </div>
        </div>
    </section>
    <section>
        <div id="albumInfoSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="showFrontpageAlbums()">&times;</span>
                <p hidden id="albumId"></p> <br>
                <ul>
                    <p><strong>Title</strong></p>
                    <li><input type="text" id="albumName" class="trackInput"></li>
                    <p><strong>Artist</strong></p>
                    <li><input type="text" id="albumArtist" class="trackInput"></li>
                </ul>
                <h2>Tracks:</h3>
                    <table id="albumInfoSectionTracksTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Media Type</th>
                                <th>Genre</th>
                                <th>Length</th>
                                <th>Size</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <button id="saveAlbumBtn" onclick="saveAlbumInfo()">Save</button>
            </div>
        </div>
    </section>
</main>

<?php
include_once("footer.php");
?>