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
    <section>
        <div id="movieInfoSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="ShowFrontPageMovie()">&times;</span>
                <p hidden id="movieId"></p>
                <ul>
                    <p><strong>Title</strong></p>
                    <li><input type="text" id="movieTitle"></li>
                    <p><strong>Release Date</strong></p>
                    <li><input type="text" id="movieReleaseDate"></li>
                    <p><strong>Runtime</strong></p>
                    <li><input type="text" id="movieRuntime"></li>
                    <p><strong>Overview</strong></p>
                    <li><input type="text" id="movieOverview"></li>
                    <p><strong>Directors</strong></p>
                    <li><input type="text" id="movieDirectors"></li>
                    <p><strong>Cast</strong></p>
                    <li><input type="text" id="cast"></li>
                </ul>
                <button id="saveBtn" onclick="SaveMovieInfo()">Save</button>
            </div>
        </div>
    </section>
</main>

<?php
include_once("footer.php");
?>