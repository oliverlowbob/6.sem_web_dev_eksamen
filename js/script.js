window.onload = async function () {
    const newUrl = "http://localhost/movies/";
    const response = await $.get(newUrl);
    const results = response.results;
    console.log(results);

    await showMoviesTable(results);
};

async function addMovieBtnClick(){
    

    $("#resultMovieSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#addMovieSection").css("display", "block");
}

async function searchBtnClick() {
    const searchQuery = $("#searchQuery").val();

    const newUrl = "http://localhost/movies/?name=" + searchQuery;
    const response = await $.get(newUrl);
    const results = response.results;

    await showMoviesTable(results);
};

async function showMoviesTable(results){
    $("#movieTable > tbody").empty();

    var bodyStr = "";

    for (var i = 0; i < results.length; i++) {
        bodyStr +=
            "<tr>" +
            "<td>" +
                "<a href='#' onClick='PressMovieName(" + results[i]["id"] + ")'>" + results[i]["title"] + "</a> " + 
                "<a href='#' onClick='UpdateMovie(" + results[i]["id"] + ")'>" + "<img src='images/update.png' class='logoImg'>" + "</a>" +
                "<a href='#' onClick='DeleteMovie(" + results[i]["id"] + ")'>" + "<img src='images/delete.png' class='logoImg'>" + "</a>" +
            "</td>" +
            "</tr>";
    }

    $("#movieTable > tbody").append(bodyStr);

    $("#resultMovieSection").css("display", "block");
    $("#resultPersonSection").css("display", "none");
};

async function DeleteMovie(movieId){
    const newUrl = "http://localhost/movies/" + movieId;

    if (confirm('Are you sure you want to delete this movie?')) {
        await $.ajax({
            url: newUrl,
            type: 'DELETE',
            success: function(result) {
                console.log(result);
                location.reload();
            },
            error: function(xhr, status, error){
                console.log(xhr);
                console.log(status);
                console.log(error);
                alert("Something went wrong")
            }
        })        
      } else {

      }
;}

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

async function UpdateMovie(movieId) {
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

async function PressMovieName(movieId) {
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

function ShowFrontPageMovie() {
    $("#movieActors > table > tbody").empty();
    $("#movieInfoSection").css("display", "none");
    $("#addMovieSection").css("display", "none");
    $("#resultMovieSection").css("display", "block");
    $("#searchSection").css("display", "block");
}
