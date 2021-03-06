const baseUrl = "../";

//#region Get Methods
async function getIsAdmin() {
    return await $.get(baseUrl + "admin/");
}
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
//#endregion

//#region Overall Methods

window.onload = async function () {
    const response = await $.get(baseUrl + "tracks/");
    const results = response.results;
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();
    const isAdmin = await getIsAdmin() === "true";
    const cart = getCart();

    $("#cartCounterBtn").prop("value", "Cart (" + cart.length + ")")

    if (isAdmin) {
        $("#addBtn").css("display", "inline")
        $("#cartCounterBtn").css("display", "none")
    }

    await showTracksTable(results, albums, mediaTypes, genres);
};

async function addBtnClick() {
    const genres = await getAllGenres();
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const artists = await getAllArtists();

    $("#resultTrackSection").css("display", "none");
    $("#resultAlbumSection").css("display", "none");
    $("#resultArtistSection").css("display", "none");
    $("#searchSection").css("display", "none");

    const selected = $("#searchOptions").val();

    if (selected == "track") {
        for (const a of albums) {
            $("#addTrackAlbumOptions").append('<option value=' + a.albumId + '>' + a.name + '</option>')
        }

        for (const g of genres) {
            $("#addTrackGenreOptions").append('<option value=' + g.genreId + '>' + g.name + '</option>')
        }

        for (const mt of mediaTypes) {
            $("#addTrackMediaTypeOptions").append('<option value=' + mt.mediaTypeId + '>' + mt.name + '</option>')
        }

        $("#addTrackSection").css("display", "block");
    }
    else if (selected == "album") {
        for (const a of artists) {
            $("#addAlbumArtistOptions").append('<option value=' + a.artistId + '>' + a.name + '</option>')
        }
        $("#addAlbumSection").css("display", "block");
    }
    else if (selected == "artist") {
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
        if (response == null) {
            alert("No tracks found");
            return;
        }
        const results = response.results;
        await showTracksTable(results, albums, mediaTypes, genres);
    }

    else if (searchOptions == "album") {
        const url = baseUrl + "albums/?name=" + searchQuery;
        const response = await $.get(url);
        if (response == null) {
            alert("No albums found");
            return;
        }
        const results = response.results;
        await showAlbumsTable(results);
    }

    else if (searchOptions == "artist") {
        const url = baseUrl + "artists/?name=" + searchQuery;
        const response = await $.get(url);
        if (response == null) {
            alert("No artists found");
            return;
        }
        await showArtistsTable(response);
    }

};

//#endregion

//#region User

async function logOut() {
    localStorage.removeItem("cart");
    window.location.replace(baseUrl + "logout");
}

async function showProfile() {
    const isAdmin = await getIsAdmin() === "true";
    if (isAdmin) {
        alert("Cannot edit admin profile");
        return;
    }
    const response = await $.get(baseUrl + "users/me");

    $("#userEmail").val(response.email);
    $("#userFirstName").val(response.firstName);
    $("#userLastName").val(response.lastName);
    $("#userCompany").val(response.company);
    $("#userAddress").val(response.address);
    $("#userCity").val(response.city);
    $("#userState").val(response.state);
    $("#userCountry").val(response.country);
    $("#userPostalCode").val(response.postalCode);
    $("#userPhone").val(response.phone);
    $("#userFax").val(response.fax);

    $("#resultTrackSection").css("display", "none");
    $("#resultAlbumSection").css("display", "none");
    $("#resultArtistSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#profileSection").css("display", "block");
}

async function hideProfile() {
    $("#searchSection").css("display", "block");
    $("#resultTrackSection").css("display", "block");
    $("#profileSection").css("display", "none");
}
async function saveProfileInfo() {
    const user = await $.get(baseUrl + "users/me");
    const url = baseUrl + "users";
    const requestData = {
        customerId: user.customerId,
        firstName: $("#userFirstName").val(),
        lastName: $("#userLastName").val(),
        company: $("#userCompany").val(),
        address: $("#userAddress").val(),
        city: $("#userCity").val(),
        state: $("#userState").val(),
        country: $("#userCountry").val(),
        postalCode: $("#userPostalCode").val(),
        phone: $("#userPhone").val(),
        fax: $("#userFax").val(),
        email: $("#userEmail").val()
    };

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
            alert("User info updated");
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

async function updatePassword() {
    const user = await $.get(baseUrl + "users/me");
    const isAdmin = await getIsAdmin() === "true";
    const oldPassword = $("#userOldPassword").val();

    const verifyPasswordRequest = {
        customerId: user.customerId,
        password: oldPassword
    }

    const verifyResponse = await $.post(baseUrl + "users/verify", JSON.stringify(verifyPasswordRequest))
        .done(async function (data) { })
        .fail(async function (data) {
            console.log(data);
            alert("Something went wrong");
        })

    if (verifyResponse !== true) {
        alert("Old password is not correct");
        return;
    }

    const newPassword1 = $("#userNewPassword1").val();
    const newPassword2 = $("#userNewPassword2").val();

    if (newPassword1 != newPassword2) {
        alert("The two new passwords doesn't match");
        return;
    }

    if (!isAdmin) {
        if (newPassword1 == "admin") {
            alert("New password cannot be 'admin'");
            return;
        }
    }

    const url = baseUrl + "users";
    const requestData = {
        customerId: user.customerId,
        password: $("#userNewPassword1").val()
    };

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
            alert("Password updated");
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

//#endregion

//#region Cart 

function getCart() {
    // Get Cart
    if (localStorage.getItem("cart")) {
        return JSON.parse(localStorage.getItem("cart"));
    }
    else {
        // No data, start with an empty array
        return [];
    }
}

function setCart(cart) {
    localStorage.setItem("cart", JSON.stringify(cart));
}

async function cartCounterBtnPressed() {
    $("#trackTableCheckout > tbody").empty();
    const albums = await getAllAlbums();
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();
    const cart = getCart();

    let bodyStr = "";
    let totalAmount = 0;

    for (const result of cart) {
        const album = albums.find(a => a["albumId"] == result["albumId"])["name"];
        const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == result["mediaTypeId"])["name"];
        const genre = genres.find(g => g["genreId"] == result["genreId"])["name"];
        const composer = result["composer"];
        let newComposer = "";

        if (composer) {
            newComposer += composer;
        }

        bodyStr +=
            "<tr>" +
            "<td>" + result["name"] + "</td>" +
            "<td>" + album + "</td>" +
            "<td>" + mediaType + "</td>" +
            "<td>" + genre + "</td>" +
            "<td>" + newComposer + "</td>" +
            "<td>" + millisToMinutesAndSeconds(result["milliseconds"]) + "</td>" +
            "<td>" + bytesToSize(result["bytes"]) + "</td>" +
            "<td>" + result["unitPrice"] + "$" + "</td>" +
            "<td>" +
            "<a href='#' onClick='deleteTrackFromCart(" + result["trackId"] + ")'>" + "<img src='../images/deleteinv.png' class='logoImg'>" + "</a>" +
            "</td>" +
            "</tr>";

        totalAmount += parseFloat(result["unitPrice"]);
    }

    totalAmount = totalAmount.toFixed(2);

    $("#totalAmountP").text(totalAmount.toString() + "$");

    const response = await $.get(baseUrl + "users/me");
    $("#userAddressCheckout").val(response.address);
    $("#userCityCheckout").val(response.city);
    $("#userStateCheckout").val(response.state);
    $("#userCountryCheckout").val(response.country);
    $("#userPostalCodeCheckout").val(response.postalCode);
    $("#customerIdCheckout").text(response.customerId);

    $("#trackTableCheckout > tbody").append(bodyStr);

    $("#trackInfoSection").css("display", "none");
    $("#albumInfoSection").css("display", "none");
    $("#artistInfoSection").css("display", "none");

    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");

    $("#searchSection").css("display", "none");

    $("#resultAlbumSection").css("display", "none");
    $("#resultTrackSection").css("display", "none");
    $("#resultArtistSection").css("display", "none");

    $("#cartSection").css("display", "block");
}

async function deleteTrackFromCart(trackId) {
    if (confirm('Are you sure you want to delete the track from the cart?')) {
        let cart = getCart();
        cart = cart.filter(t => t.trackId != trackId)
        setCart(cart);
        $("#cartCounterBtn").prop("value", "Cart (" + cart.length + ")")
        await cartCounterBtnPressed();
    } else {
        // Do nothing!
    }
}

async function addTrackToCart(trackId) {
    const url = baseUrl + "tracks/" + trackId;
    const track = await $.get(url);

    if (track.name == undefined) {
        alert("Something went wrong");
        return;
    }
    let cart = getCart();
    cart.push(track)
    setCart(cart);
    $("#cartCounterBtn").prop("value", "Cart (" + cart.length + ")")
}

async function checkOut() {
    const cart = getCart();

    if (cart.length === 0) {
        alert("Cart is empty");
        return;
    }

    const invoiceUrl = baseUrl + "invoices"

    const invoiceData = {
        customerId: $("#customerIdCheckout").text(),
        address: $("#userAddressCheckout").val(),
        city: $("#userCityCheckout").val(),
        state: $("#userStateCheckout").val(),
        country: $("#userCountryCheckout").val(),
        postalCode: $("#userPostalCodeCheckout").val(),
        date: new Date().toISOString().slice(0, 19).replace('T', ' '),
        //total: $("#totalAmountP").text().slice(0, -1),
        cart: cart,
    }

    const invoiceResponse = await $.post(invoiceUrl, JSON.stringify(invoiceData))
        .done(function (data) {

        })
        .fail(function (data) {
            console.log(data);
            console.log("invoice went wrong");
            alert("Something went wrong");
        });

    alert("Check out was successful!");
    setCart([]);
    location.reload();
}

//#endregion

//#region Artists

$("#addArtistForm").submit(function (event) {
    event.preventDefault();

    const formValues = $("#addArtistForm").serialize();
    const url = baseUrl + "artists/"
    $.post(url, formValues)
        .done(function (data) {
            alert("Artist was added");
            window.location.reload();
        })
        .fail(function (data) {
            console.log(data);
            alert("Something went wrong");
        })
});

async function showArtistsTable(artists) {
    const isAdmin = await getIsAdmin() === "true";
    $("#artistTable > tbody").empty();

    let bodyStr = "";
    for (const result of artists) {

        bodyStr +=
            "<tr>" +
            "<td>" +
            "<a href='#' onClick='pressArtistName(" + result["artistId"] + ")'>" + result["name"] + "</a> " +
            "</td>"

        if (isAdmin) {
            bodyStr +=
                "<td>" +
                "<a href='#' onClick='deleteArtist(" + result["artistId"] + ")'>" + "<img src='../images/deleteinv.png' class='logoImg'>" + "</a>" +
                "</td>" +
                "</tr>";
        }
        else {
            bodyStr += "</tr>";
        }
    }

    $("#artistTable > tbody").append(bodyStr);

    $("#resultArtistSection").css("display", "block");
    $("#resultAlbumSection").css("display", "none");
    $("#resultTrackSection").css("display", "none");
}

async function pressArtistName(artistId) {
    const isAdmin = await getIsAdmin() === "true";
    const artist = await $.get(baseUrl + "artists/" + artistId);
    const albumsResponse = await getAllAlbums();
    const albums = albumsResponse.filter(a => a.artistId == artistId);

    $("#artistName").val(artist.name);
    $("#artistId").text(artistId);
    $("#artistName").prop("readonly", true);
    $("#saveArtistBtn").css("display", "none");

    let albumsString = "";
    for (const album of albums) {
        albumsString += "<li> <p>" + album.name + "</p></li>";
    }
    $("#aristInfoSectionAlbumsList").append(albumsString);

    if (isAdmin) {
        $("#artistName").prop("readonly", false);
        $("#albumArtist").css("display", "none");
        $("#saveArtistBtn").css("display", "block");
    }

    $("#resultArtistSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#artistInfoSection").css("display", "block");
}

async function deleteArtist(artistId) {
    const newUrl = baseUrl + "artists/" + artistId;

    if (confirm('Are you sure you want to delete this artist?')) {
        await $.ajax({
            url: newUrl,
            type: 'DELETE',
            success: function (result) {
                if (result == "artistDeleteError") {
                    alert("You must delete all albums with artist before deleting the artist");
                    return;
                }
                alert("Artist deleted");
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
        alert("Artist not deleted");
    }
}

async function saveArtistInfo() {
    const url = baseUrl + "artists/";

    const artistId = $("#artistId").text();
    const name = $("#artistName").val();

    const requestData = {
        artistId: artistId,
        name: name,
    };

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
            alert("Artist updated");
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

//#endregion

//#region Albums

$("#addAlbumForm").submit(function (event) {
    event.preventDefault();

    const formValues = $("#addAlbumForm").serialize();
    const url = baseUrl + "albums/"
    $.post(url, formValues)
        .done(function (data) {
            alert("Album was added");
            window.location.reload();
        })
        .fail(function (data) {
            console.log(data);
            alert("Something went wrong");
        })
});

async function deleteAlbum(albumId) {
    const newUrl = baseUrl + "albums/" + albumId;

    if (confirm('Are you sure you want to delete this album?')) {
        await $.ajax({
            url: newUrl,
            type: 'DELETE',
            success: function (result) {
                if (result == "deleteAlbumError") {
                    alert('You must delete all tracks in the album before deleting the album');
                    return;
                }
                console.log(result);
                alert("Album deleted");
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
        alert("Album not deleted");
    }
}

async function showAlbumsTable(results) {
    $("#albumTable > tbody").empty();
    const artists = await getAllArtists();
    const isAdmin = await getIsAdmin() === "true";

    let bodyStr = "";
    for (const result of results) {
        const artist = artists.find(a => a["artistId"] == result["artistId"])["name"];

        bodyStr +=
            "<tr>" +
            "<td>" +
            "<a href='#' onClick='pressAlbumName(" + result["albumId"] + ")'>" + result["name"] + "</a> " +
            "</td>" +
            "<td>" + artist + "</td>"

        if (isAdmin) {
            bodyStr +=
                "<td>" +
                "<a href='#' onClick='deleteAlbum(" + result["albumId"] + ")'>" + "<img src='../images/deleteinv.png' class='logoImg'>" + "</a>" +
                "</td>" +
                "</tr>";
        }
        else {
            bodyStr += "</tr>";
        }
    }

    $("#albumTable > tbody").append(bodyStr);

    $("#resultAlbumSection").css("display", "block");
    $("#resultTrackSection").css("display", "none");
    $("#resultArtistSection").css("display", "none");
}

async function pressAlbumName(albumId) {
    const newUrl = baseUrl + "albums/" + albumId;
    const response = await $.get(newUrl);
    const artist = await $.get(baseUrl + "artists/" + response["artistId"]);
    const tracksUrl = baseUrl + "tracks?albumId=" + albumId;
    const tracks = await $.get(tracksUrl);
    const results = tracks.results;
    const mediaTypes = await getAllMediaTypes();
    const genres = await getAllGenres();
    const isAdmin = await getIsAdmin() === "true";
    const artists = await getAllArtists();

    $("#albumInfoSectionTracksTable > tbody").empty();

    let bodyStr = "";

    if (results) {
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


            if (!isAdmin) {
                bodyStr +=
                    "<td>" +
                    "<a href='#' onClick='addTrackToCart(" + result["trackId"] + ")'>" + "<button class='buyBtn'>Buy</button>" + "</a>" +
                    "</td>";
            }

            if (isAdmin) {
                bodyStr +=
                    "<td>" +
                    "<a href='#' onClick='deleteTrack(" + result["trackId"] + ")'>" + "<img src='../images/deleteinv.png' class='logoImg'>" + "</a>" +
                    "</td>" +
                    "</tr>";
            }
            else {
                bodyStr += "</tr>";
            }
        }
    }

    $("#albumInfoSectionTracksTable > tbody").append(bodyStr);

    $("#albumId").text(albumId);

    $("#albumName").val(response.name);
    $("#albumName").prop("readonly", true);

    $("#saveAlbumBtn").css("display", "none");
    $("#albumArtist").val(artist.name);
    $("#albumArtist").prop("readonly", true);

    $("#albumArtistOptions").css("display", "none");

    if (isAdmin) {
        for (const a of artists) {
            if (a.artistId === artist.artistId) {
                $("#albumArtistOptions").append('<option value=' + a.artistId + ' selected>' + a.name + '</option>')
            }
            else {
                $("#albumArtistOptions").append('<option value=' + a.artistId + '>' + a.name + '</option>')
            }
        }
        $("#albumName").prop("readonly", false);
        $("#albumArtist").css("display", "none");
        $("#albumArtistOptions").css("display", "inline-block");

        $("#saveAlbumBtn").css("display", "block");
    }

    $("#resultAlbumSection").css("display", "none");
    $("#searchSection").css("display", "none");
    $("#albumInfoSection").css("display", "block");
}

async function saveAlbumInfo() {
    const url = baseUrl + "albums/";

    const albumId = $("#albumId").text();
    const name = $("#albumName").val();
    const artistId = $("#albumArtistOptions").val();

    const requestData = {
        artistId: artistId,
        name: name,
        albumId: albumId,
    };

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
            alert("Album updated");
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

//#endregion

//#region Tracks

$("#addTrackForm").submit(async function (event) {
    event.preventDefault();

    const formValues = $("#addTrackForm").serialize();
    const url = baseUrl + "tracks/"
    await $.post(url, formValues)
        .done(function (data) {
            alert("Track was added");
            location.reload();
        })
        .fail(function (data) {
            console.log(data);
            alert("Something went wrong");
        })
});

async function showTracksTable(results, albums, mediaTypes, genres) {
    const isAdmin = await getIsAdmin() === "true";

    $("#trackTable > tbody").empty();

    let bodyStr = "";

    for (const result of results) {
        const album = albums.find(a => a["albumId"] == result["albumId"])["name"];
        const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == result["mediaTypeId"])["name"];
        const genre = genres.find(g => g["genreId"] == result["genreId"])["name"];
        const composer = result["composer"];
        let newComposer = "";

        if (composer) {
            newComposer += composer;
        }

        bodyStr +=
            "<tr>" +
            "<td>" +
            "<a href='#' onClick='pressTrackName(" + result["trackId"] + ")'>" + result["name"] + "</a> " +
            "</td>" +
            "<td>" + album + "</td>" +
            "<td>" + mediaType + "</td>" +
            "<td>" + genre + "</td>" +
            "<td>" + newComposer + "</td>" +
            "<td>" + millisToMinutesAndSeconds(result["milliseconds"]) + "</td>" +
            "<td>" + bytesToSize(result["bytes"]) + "</td>" +
            "<td>" + result["unitPrice"] + "$" + "</td>";


        if (!isAdmin) {
            bodyStr +=
                "<td>" +
                "<a href='#' onClick='addTrackToCart(" + result["trackId"] + ")'>" + "<button class='buyBtn'>Buy</button>" + "</a>" +
                "</td>";
        }

        if (isAdmin) {
            bodyStr +=
                "<td>" +
                "<a href='#' onClick='deleteTrack(" + result["trackId"] + ")'>" + "<img src='../images/deleteinv.png' class='logoImg'>" + "</a>" +
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

async function deleteTrack(trackId) {
    const newUrl = baseUrl + "tracks/" + trackId;
    if (confirm('Are you sure you want to delete this track?')) {
        //Delete track from db via backend
        await $.ajax({
            url: newUrl,
            type: 'DELETE',
            success: function (result) {
                if (result == "trackDeleteError") {
                    alert("Track cannot be deleted, as it has been purchased already");
                    return;
                }
                alert("Track deleted");
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                alert("Something went wrong");
            }
        });
    } else {
        alert("Track not deleted");
    }
}

async function saveTrackInfo() {
    const url = baseUrl + "tracks/";

    const trackId = $("#trackId").text();
    const albumId = $("#trackAlbumOptions").val();
    const genreId = $("#trackGenreOptions").val();
    const mediaTypeId = $("#trackMediaOptions").val();
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
        milliseconds: minutesAndSecondsToMillis(time),
        bytes: sizeToBytes(size),
        unitPrice: unitPrice.slice(0, -1)
    };

    $.ajax({
        type: 'PUT',
        url: url,
        data: JSON.stringify(requestData),
        contentType: "application/json",
        success: function (response, status, xhr) {
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

async function pressTrackName(trackId) {
    const newUrl = baseUrl + "tracks/" + trackId;
    const response = await $.get(newUrl);
    const album = await $.get(baseUrl + "albums/" + response["albumId"]);
    const mediaTypes = await getAllMediaTypes();
    const mediaType = mediaTypes.find(mt => mt["mediaTypeId"] == response["mediaTypeId"])["name"];
    const genres = await getAllGenres();
    const genre = genres.find(g => g["genreId"] == response["genreId"])["name"];
    const isAdmin = await getIsAdmin() === "true";
    const albums = await getAllAlbums();

    $("#trackId").text(trackId);
    $("#albumId").text(response.albumId);
    $("#genreId").text(response.genreId);
    $("#mediaTypeId").text(response.mediaTypeId);

    $("#trackName").val(response.name);
    $("#trackName").prop("readonly", true);

    $("#trackAlbum").val(album.name);
    $("#trackAlbum").prop("readonly", true);
    $("#trackAlbumOptions").css("display", "none");

    $("#trackMediaType").val(mediaType);
    $("#trackMediaType").prop("readonly", true);
    $("#trackMediaOptions").css("display", "none");

    $("#trackGenre").val(genre);
    $("#trackGenre").prop("readonly", true);
    $("#trackGenreOptions").css("display", "none");

    $("#trackComposer").val(response.composer);
    $("#trackComposer").prop("readonly", true);

    $("#trackTime").val(millisToMinutesAndSeconds(response.milliseconds));
    $("#trackTime").prop("readonly", true);

    $("#trackSize").val(bytesToSize(response.bytes));
    $("#trackSize").prop("readonly", true);

    $("#trackPrice").val(response.unitPrice + "$");
    $("#trackPrice").prop("readonly", true);

    if (isAdmin) {
        for (const a of albums) {
            if (a.albumId === album.albumId) {
                $("#trackAlbumOptions").append('<option value=' + a.albumId + ' selected>' + a.name + '</option>')
            }
            else {
                $("#trackAlbumOptions").append('<option value=' + a.albumId + '>' + a.name + '</option>')
            }
        }

        for (const mediaType of mediaTypes) {
            if (response.mediaTypeId === mediaType.mediaTypeId) {
                $("#trackMediaOptions").append('<option value=' + mediaType.mediaTypeId + ' selected>' + mediaType.name + '</option>')
            }
            else {
                $("#trackMediaOptions").append('<option value=' + mediaType.mediaTypeId + '>' + mediaType.name + '</option>')
            }
        }

        for (const g of genres) {
            if (g.genreId === response.genreId) {
                $("#trackGenreOptions").append('<option value=' + g.genreId + ' selected>' + g.name + '</option>')
            }
            else {
                $("#trackGenreOptions").append('<option value=' + g.genreId + '>' + g.name + '</option>')
            }
        }

        $("#trackName").prop("readonly", false);

        $("#trackAlbum").css("display", "none");
        $("#trackAlbumOptions").css("display", "inline-block");

        $("#trackMediaType").css("display", "none");
        $("#trackMediaOptions").css("display", "inline-block");

        $("#trackGenre").css("display", "none");
        $("#trackGenreOptions").css("display", "inline-block");

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

//#endregion

//#region Show Frontpage

function showFrontPageTracks() {
    $("#trackInfoSection").css("display", "none");
    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");
    $("#cartSection").css("display", "none");
    $("#resultTrackSection").css("display", "block");
    $("#searchSection").css("display", "block");
}

function showFrontpageAlbums() {
    $("#albumInfoSection").css("display", "none");
    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");
    $("#cartSection").css("display", "none");
    $("#resultAlbumSection").css("display", "block");
    $("#searchSection").css("display", "block");
}

function showFrontPageArtists() {
    $("#artistInfoSection").css("display", "none");
    $("#addAlbumSection").css("display", "none");
    $("#addTrackSection").css("display", "none");
    $("#addArtistSection").css("display", "none");
    $("#cartSection").css("display", "none");
    $("#resultArtistSection").css("display", "block");
    $("#searchSection").css("display", "block");
}

//#endregion

//#region Helper Functions

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

function minutesAndSecondsToMillis(time) {
    const timeParts = time.split(":");
    return (timeParts[0] * 60000) + (timeParts[1] * 1000)
}

function sizeToBytes(size) {
    const sizeInt = size.replace(/\D/g, '');
    return sizeInt;
}

//#endregion

//#region Dropdown Logic

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function dropdownBtnClick() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        let dropdowns = document.getElementsByClassName("dropdown-content");
        let i;
        for (i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

//#endregion