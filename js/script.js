const baseUrl = "http://localhost/music/";

async function getIsAdmin() {
    return await $.get(baseUrl + "admin/");
}

window.onload = async function () {
    const response = await $.get(baseUrl + "tracks/");
    const results = response.results;
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();
    const isAdmin = await getIsAdmin() === "true";
    
    if (isAdmin) {
        $("#addBtn").css("display", "inline")
    }

    await showTracksTable(results, albums, mediaTypes, genres);
};

async function getAllAlbums() {
    return await $.get(baseUrl + "albums/");
}

async function getAllMediaTypes() {
    return await $.get(baseUrl + "mediaTypes/");
}

async function getAllGenres() {
    return await $.get(baseUrl + "genres/");
}

async function getAllArtists() {
    return await $.get(baseUrl + "artists/");
}

async function showProfile(){
    $("#profileSection").css("display", "block");
}

async function hideProfile(){
    $("#profileSection").css("display", "none");
}

async function saveProfileInfo(){

    $("#profileSection").css("display", "none");
}

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
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();
    const searchOptions = $("#searchOptions :selected").val();

    if (searchOptions == "track") {
        const url = baseUrl + "tracks/?name=" + searchQuery;
        const response = await $.get(url);
        const results = response.results;
        await showTracksTable(results, albums, mediaTypes, genres);
    }

    else if (searchOptions == "album") {
        const url = baseUrl + "albums/?name=" + searchQuery;
        const response = await $.get(url);
        const results = response.results;
        await showAlbumsTable(results);
    }

};

async function showAlbumsTable(results) {
    $("#albumTable > tbody").empty();
    const artists = await getAllArtists();

    var bodyStr = "";
    for (const result of results) {
        const artist = artists.find(a => a["artistId"] == result["artistId"])["name"];

        bodyStr +=
            "<tr>" +
            "<td>" +
            "<a href='#' onClick='PressAlbumName(" + result["albumId"] + ")'>" + result["name"] + "</a> " +
            "</td>" +
            "<td>" + artist + "</td>" +
            "</tr>";
    }

    $("#albumTable > tbody").append(bodyStr);

    $("#resultAlbumSection").css("display", "block");
    $("#resultTrackSection").css("display", "none");
    $("#resultArtistSection").css("display", "none");
}

async function showTracksTable(results, albums, mediaTypes, genres) {
    const isAdmin = await getIsAdmin() === "true";

    $("#trackTable > tbody").empty();

    var bodyStr = "";

    for (const result of results) {
        const album = albums.find(a => a["albumId"] == result["albumId"])["name"];
        const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == result["mediaTypeId"])["name"];
        const genre = genres.find(g => g["genreId"] == result["genreId"])["name"];
        const composer = result["composer"];
        var newComposer = "";

        if (composer != null) {
            newComposer += composer;
        }

        bodyStr +=
            "<tr>" +
            "<td>" +
            "<a href='#' onClick='PressTrackName(" + result["trackId"] + ")'>" + result["name"] + "</a> " +
            "</td>" +
            "<td>" + album + "</td>" +
            "<td>" + mediaType + "</td>" +
            "<td>" + genre + "</td>" +
            "<td>" + newComposer + "</td>" +
            "<td>" + millisToMinutesAndSeconds(result["milliseconds"]) + "</td>" +
            "<td>" + bytesToSize(result["bytes"]) + "</td>" +
            "<td>" + result["unitPrice"] + "$" + "</td>";

        if (isAdmin) {
            bodyStr +=
                "<td>" +
                "<a href='#' onClick='DeleteTrack(" + result["trackId"] + ")'>" + "<img src='../images/delete.png' class='logoImg'>" + "</a>" +
                "</td>" +
                "</tr>";
        }
        else {
            bodyStr += "</tr>";
        }
    }

    $("#trackTable > tbody").append(bodyStr);

    $("#resultTrackSection").css("display", "block");
    $("#resultAlbumSection").css("display", "none");
    $("#resultArtistSection").css("display", "none");
};

async function DeleteTrack(trackId) {
    const newUrl = baseUrl + "tracks/" + trackId;

    if (confirm('Are you sure you want to delete this track?')) {
        await $.ajax({
            url: newUrl,
            type: 'DELETE',
            success: function (result) {
                console.log(result);
                alert("Track deleted");
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                alert("Something went wrong");
            }
        })
    } else {
        alert("Track not deleted");
    }
}

async function SaveTrackInfo() {
    const url = baseUrl + "tracks/";

    const trackId = $("#trackId").text();
    const albumId = $("#albumId").text();
    const genreId = $("#genreId").text();
    const mediaTypeId = $("#mediaTypeId").text();
    const name = $("#trackName").val();
    const composer = $("#trackComposer").val();
    const time = $("#trackTime").val();
    const size = $("#trackSize").val();
    const unitPrice = $("#trackPrice").val();

    const requestData = {
        trackId: trackId,
        name: name,
        albumId: albumId,
        mediaTypeId: mediaTypeId,
        genreId: genreId,
        composer: composer,
        milliseconds: MinutesAndSecondsToMillis(time),
        bytes: sizeToBytes(size),
        unitPrice: unitPrice.slice(0, -1)
    };

    console.log(requestData);

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
            console.log(response);
            console.log(status);
            console.log(xhr);
            alert("Track updated");
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

async function PressAlbumName(albumId) {
    const newUrl = baseUrl + "albums/" + albumId;
    const response = await $.get(newUrl);
    const artist = await $.get(baseUrl + "artists/" + response["artistId"]);
    const tracksUrl = baseUrl + "tracks?albumId=" + albumId;
    const tracks = await $.get(tracksUrl);
    const results = tracks.results;
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();
    const isAdmin = await getIsAdmin() === "true";

    $("#albumInfoSectionTracksTable > tbody").empty();

    var bodyStr = "";

    for (const result of results) {
        const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == result["mediaTypeId"])["name"];
        const genre = genres.find(g => g["genreId"] == result["genreId"])["name"];

        bodyStr +=
            "<tr>" +
            "<td>" + result["name"] + "</td>" +
            "<td>" + mediaType + "</td>" +
            "<td>" + genre + "</td>" +
            "<td>" + millisToMinutesAndSeconds(result["milliseconds"]) + "</td>" +
            "<td>" + bytesToSize(result["bytes"]) + "</td>" +
            "<td>" + result["unitPrice"] + "$" + "</td>";

        if (isAdmin) {
            bodyStr +=
                "<td>" +
                "<a href='#' onClick='DeleteTrack(" + result["trackId"] + ")'>" + "<img src='../images/delete.png' class='logoImg'>" + "</a>" +
                "</td>" +
                "</tr>";
        }
        else {
            bodyStr += "</tr>";
        }
    }

    $("#albumInfoSectionTracksTable > tbody").append(bodyStr);

    $("#albumId").text(albumId);

    $("#albumName").val(response.name);
    $("#albumName").prop("readonly", true);

    $("#albumArtist").val(artist.name);
    $("#albumArtist").prop("readonly", true);

    if (isAdmin) {
        $("#albumName").prop("readonly", false);
        $("#albumArtist").prop("readonly", false);

        $("#saveTrackBtn").css("display", "block");
    }

    $("#saveBtn").css("display", "none");
    $("#resultAlbumSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#albumInfoSection").css("display", "block");
}

async function PressTrackName(trackId) {
    const newUrl = baseUrl + "tracks/" + trackId;
    const response = await $.get(newUrl);
    const album = await $.get(baseUrl + "albums/" + response["albumId"]);
    const mediaTypes = await getAllMediaTypes();
    const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == response["mediaTypeId"])["name"];
    const genres = await getAllGenres();
    const genre = genres.find(g => g["genreId"] == response["genreId"])["name"];
    const isAdmin = await getIsAdmin() === "true";

    $("#trackId").text(trackId);
    $("#albumId").text(response.albumId);
    $("#genreId").text(response.genreId);
    $("#mediaTypeId").text(response.mediaTypeId);

    $("#trackName").val(response.name);
    $("#trackName").prop("readonly", true);

    $("#trackAlbum").val(album.name);
    $("#trackAlbum").prop("readonly", true);

    $("#trackMediaType").val(mediaType);
    $("#trackMediaType").prop("readonly", true);

    $("#trackGenre").val(genre);
    $("#trackGenre").prop("readonly", true);

    $("#trackComposer").val(response.composer);
    $("#trackComposer").prop("readonly", true);

    $("#trackTime").val(millisToMinutesAndSeconds(response.milliseconds));
    $("#trackTime").prop("readonly", true);

    $("#trackSize").val(bytesToSize(response.bytes));
    $("#trackSize").prop("readonly", true);

    $("#trackPrice").val(response.unitPrice + "$");
    $("#trackPrice").prop("readonly", true);

    if (isAdmin) {
        $("#trackName").prop("readonly", false);
        $("#trackAlbum").prop("readonly", false);
        $("#trackMediaType").prop("readonly", false);
        $("#trackGenre").prop("readonly", false);
        $("#trackComposer").prop("readonly", false);
        $("#trackTime").prop("readonly", false);
        $("#trackSize").prop("readonly", false);
        $("#trackPrice").prop("readonly", false);

        $("#saveTrackBtn").css("display", "block");
    }

    $("#saveBtn").css("display", "none");
    $("#resultTrackSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#trackInfoSection").css("display", "block");
}

function ShowFrontPageTracks() {
    $("#trackInfoSection").css("display", "none");
    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");
    $("#resultTrackSection").css("display", "block");
    $("#searchSection").css("display", "block");
}

function ShowFrontpageAlbums() {
    $("#albumInfoSection").css("display", "none");
    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");
    $("#resultAlbumSection").css("display", "block");
    $("#searchSection").css("display", "block");
}

function bytesToSize(bytes) {
    if (bytes == 0) {
        return '0 Byte';
    }
    return bytes / (1000 * 1000) + " MB"
}

function millisToMinutesAndSeconds(millis) {
    const minutes = Math.floor(millis / 60000);
    const seconds = ((millis % 60000) / 1000).toFixed(0);
    return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
}

function MinutesAndSecondsToMillis(time) {
    const timeParts = time.split(":");
    return (timeParts[0] * 60000) + (timeParts[1] * 1000)
}

function sizeToBytes(size) {
    const sizeInt = size.replace(/\D/g, '');
    return sizeInt;
}

//dropdown logic
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function dropdownBtnClick() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
