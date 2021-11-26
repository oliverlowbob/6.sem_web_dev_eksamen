<?php
include_once("nav.php");
?>

<h1>Movie Database</h1>
<main>
    <section id="searchSection">
        <input type="text" id="searchQuery" placeholder="Search here">
        <button onclick="searchBtnClick()" class="frontpageBtn" id="searchBtn">Search</button>
        <button onclick="addMovieBtnClick()" class="frontpageBtn" id="addMovieBtn">Add Movie</button>
    </section>
    <section>
        <div id="addMovieSection" class="modal">
            <div class="modal-content">
                <span class="close" onclick="ShowFrontPageMovie()">&times;</span>
                <form action="http://localhost/movies/" method="post">
                    Title: <input type="text" name="title"><br>
                    Overview: <input type="text" name="overview"><br>
                    Released: <input type="date" name="released"><br>
                    Runtime: <input type="number" name="runtime"><br>
                    <input type="submit" value="Add">
                </form>
            </div>
        </div>
    </section>
    <section id="resultMovieSection">
        <table id="movieTable">
            <thead>
                <tr>
                    <th>Title</th>
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