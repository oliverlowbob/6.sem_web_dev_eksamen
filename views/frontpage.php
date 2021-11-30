<?php
include_once("nav.php");

// session_start();

// if(!isset($_SESSION['username'])){
//     header('Location: login.php');
// }

?>

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
        <div id="addAlbumSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="ShowFrontPageTracks()">&times;</span>
                <form action="http://localhost/music/albums/" method="post">
                    Title: <input type="text" name="title"><br>
                    Artist: <input type="text" name="artist"><br>
                    <input type="submit" value="Add">
                </form>
            </div>
        </div>
        <div id="addTrackSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="ShowFrontPageTracks()">&times;</span>
                <form action="http://localhost/music/tracks" method="post">
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
                <span class="close" onclick="ShowFrontPageTracks()">&times;</span>
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
                <span class="close" onclick="ShowFrontPageTracks()">&times;</span>
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
                <button id="saveTrackBtn" onclick="SaveTrackInfo()">Save</button>
            </div>
        </div>
    </section>
</main>

<?php
include_once("footer.php");
?>