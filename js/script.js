const baseUrl = "http://localhost/music/"

async function getAllAlbums() {
    return await $.get(baseUrl + "albums/");
}

async function getAllMediaTypes(){
    return await $.get(baseUrl + "mediaTypes/");
}

async function getAllGenres(){
    return await $.get(baseUrl + "genres/");
}

window.onload = async function () {
    const response = await $.get(baseUrl + "tracks/");
    const results = response.results;
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();

    await showMoviesTable(results, albums, mediaTypes, genres);
};

async function addBtnClick() {
    $("#resultTrackSection").css("display", "none");
    $("#searchSection").css("display", "none");

    const selected = $("#searchOptions").val();

    if (selected == "track") {
        $("#addTrackSection").css("display", "block");
    }
    else if (selected == "album") {
        $("#addAlbumSection").css("display", "block");
    }
    else {
        $("#addArtistSection").css("display", "block");
    }

}

async function searchBtnClick() {
    const searchQuery = $("#searchQuery").val();
    const url = baseUrl + "tracks/?name=" + searchQuery;
    const response = await $.get(url);
    const results = response.results;
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();

    await showMoviesTable(results, albums, mediaTypes, genres);
};

async function showMoviesTable(results, albums, mediaTypes, genres) {
    $("#trackTable > tbody").empty();

    var bodyStr = "";

    for (const result of results) {
        const album = albums.find(a => a["albumId"] == result["albumId"])["name"];
        const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == result["mediaTypeId"])["name"];
        const genre = genres.find(g => g["genreId"] == result["genreId"])["name"];

        bodyStr +=
            "<tr>" +
            "<td>" +
            "<a href='#' onClick='PressTrackName(" + result["trackId"] + ")'>" + result["name"] + "</a> " +
            "</td>" +
            "<td>" + album + "</td>" +
            "<td>" + mediaType + "</td>" +
            "<td>" + genre + "</td>" +
            "<td>" + result["composer"] + "</td>" +
            "<td>" + millisToMinutesAndSeconds(result["milliseconds"]) + "</td>" +
            "<td>" + bytesToSize(result["bytes"]) + "</td>" +
            "<td>" + result["unitPrice"] + "$" + "</td>" +

            "<td>" +
            "<a href='#' onClick='UpdateTrack(" + result["trackId"] + ")'>" + "<img src='images/update.png' class='logoImg'>" + "</a>" +
            "<a href='#' onClick='DeleteTrack(" + result["trackId"] + ")'>" + "<img src='images/delete.png' class='logoImg'>" + "</a>" +
            "</td>" +
            "</tr>";
    }

    $("#trackTable > tbody").append(bodyStr);

    $("#resultMovieSection").css("display", "block");
    $("#resultPersonSection").css("display", "none");
};

async function DeleteTrack(movieId) {
    const newUrl = "http://localhost/movies/" + movieId;

    if (confirm('Are you sure you want to delete this movie?')) {
        await $.ajax({
            url: newUrl,
            type: 'DELETE',
            success: function (result) {
                console.log(result);
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                alert("Something went wrong")
            }
        })
    } else {

    }
    ;
}

async function SaveMovieInfo() {
    const url = "http://localhost/movies";

    const movieId = $("#movieId").text();
    const title = $("#movieTitle").val();
    const overview = $("#movieOverview").val();
    const released = $("#movieReleaseDate").val();
    const runtime = $("#movieRuntime").val();

    const requestData = {
        movieId: movieId,
        title: title,
        overview: overview,
        released: released,
        runtime: runtime
    };

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
            console.log(response);
            console.log(status);
            console.log(xhr);
            alert("Movie info saved");
            location.reload();
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.log(status);
            console.log(error);
            alert("Something went wrong")
        }
    });
}

async function UpdateTrack(movieId) {
    const newUrl = "http://localhost/movies/" + movieId;
    const unparsedResponse = await $.get(newUrl);
    const response = unparsedResponse.results[0];

    $("#movieId").text(movieId);
    $("#movieTitle").val(response.title);
    $("#movieTitle").prop("readonly", false);
    $("#movieReleaseDate").val(response.released);
    $("#movieReleaseDate").prop("readonly", false);
    $("#movieRuntime").val(response.runtime);
    $("#movieRuntime").prop("readonly", false);
    $("#movieOverview").val(response.overview);
    $("#movieOverview").prop("readonly", false);

    $("#saveBtn").css("display", "inline-block");

    $("#resultMovieSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#movieInfoSection").css("display", "block");
}

async function PressTrackName(movieId) {
    const newUrl = "http://localhost/movies/" + movieId;
    const unparsedResponse = await $.get(newUrl);
    const response = unparsedResponse.results[0];

    $("#saveBtn").css("display", "hidden");

    $("#movieId").text("");
    $("#movieTitle").val(response.title);
    $("#movieTitle").prop("readonly", true);
    $("#movieReleaseDate").val(response.released);
    $("#movieReleaseDate").prop("readonly", true);
    $("#movieRuntime").val(response.runtime);
    $("#movieRuntime").prop("readonly", true);
    $("#movieOverview").val(response.overview);
    $("#movieOverview").prop("readonly", true);

    $("#saveBtn").css("display", "none");
    $("#resultMovieSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#movieInfoSection").css("display", "block");
}

function ShowFrontPageTracks() {
    $("#movieInfoSection").css("display", "none");
    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");
    $("#resultTrackSection").css("display", "block");
    $("#searchSection").css("display", "block");
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function millisToMinutesAndSeconds(millis) {
    var minutes = Math.floor(millis / 60000);
    var seconds = ((millis % 60000) / 1000).toFixed(0);
    return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
}
