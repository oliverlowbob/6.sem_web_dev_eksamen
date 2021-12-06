const baseUrl = "http://localhost/" + window.location.pathname.split("/")[1] + "/";

$("#loginForm").submit(function(event ) {
    event.preventDefault();
    
    const formValues = $("#loginForm").serialize();
    const url = baseUrl + "login/"
    $.post(url, formValues)
        .done(function (data) {
            if(data.includes("<p hidden>This is a paragraph for login check</p>")){
                alert("Wrong username or password");
            }
            else{
                location.reload();
            }
        })
        .fail(function (data){
            console.log(data);
            alert("Something went wrong");
        })
});