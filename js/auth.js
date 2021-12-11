const baseUrl = "../";

$("#loginForm").submit(async function(event ) {
    event.preventDefault();
    
    const formValues = $("#loginForm").serialize();
    const url = baseUrl + "login/"
    await $.post(url, formValues)
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

$("#signupForm").submit(async function(event ) {
    event.preventDefault();
    
    const formValues = $("#signupForm").serialize();
    const url = baseUrl + "signup/"
    await $.post(url, formValues)
        .done(function (data) {
            alert("User was created!")
            window.location.replace(baseUrl + "views/login.php");            
        })
        .fail(function (data){
            console.log(data);
            alert("Something went wrong");
        })
});