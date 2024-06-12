<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">


    <title>Admin | Voting System</title>
    <?php include('./header.php'); ?>
    <?php 
    session_start();
    if(isset($_SESSION['login_id']))
    header("location:index.php?page=home");
    ?>
</head>
<style>
    body {
        width: 100%;
        height: calc(100%);
        background-image: url(vote.jpeg);
        background-size: 925px  703px;
    }
    main#main {
        width: 100%;
        height: calc(100%);
        background: #EADBC8;
    }
    #login-right {
        position: absolute;
        right: 0;
        width: 40%;
        height: calc(100%);
        background:#F5EFE6;
        display: flex;
        align-items: center;
    }
    #login-left {
        
        position: absolute;
        left: 0;
        width: 60%;
        height: calc(100%);
        background: #00000061;
        display: flex;
        align-items: center;
    }
    #login-right .card {
        margin: auto;
    }
    .logo {
        margin: auto;
        font-size: 8rem;
        background: white;
        padding: .5em 0.8em;
        border-radius: 50% 50%;
        color: #000000b3;
    }
</style>

<body>
    <main id="main" class=" alert-info">
        <div id="login-left">
            <!-- <div class="logo">
                <i class="fa fa-poll-h"></i>
            </div> -->
        </div>
        <div id="login-right">
            <div class="card col-md-8">
                <div class="card-body">
                <form id="login-form">
                    <div class="form-group">
                        <label for="username" class="control-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                     
                    <center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary" type="submit">Login</button></center>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
</body>
<script>
    $('#login-form').submit(function(e){
        e.preventDefault()
        $('#login-form button[type="submit"]').attr('disabled',true).html('Logging in...');
        if($(this).find('.alert-danger').length > 0 )
            $(this).find('.alert-danger').remove();
        $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.log(err)
                $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
            },
            success: function(resp){
                let response = JSON.parse(resp);
                if(response.status === 'success'){
                    if(response.type == 1){
                        location.href = 'index.php?page=home';
                    } else {
                        location.href = 'voting.php';
                    }
                } else if(response.status === 'ward_inactive'){
                    $('#login-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                } else if(response.status === 'invalid_credentials'){
                    $('#login-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                } else {
                    $('#login-form').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                }
            }
        })
    })
</script>


</html>


