<?php include("./templates/header.php"); ?>

<div class="container mlogin">
    <div id="login">
        <h1>Log In</h1>
        <form id="loginform" method="post"name="loginform">
            <div class="mb-3">
                <label for="username" class="form-label">Login</label>
                <input type="text" class="form-control" id="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary" id="login">Log In</button>
        </form>
    </div>
</div>

<?php include("./templates/footer.php"); ?>
