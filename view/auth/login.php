<div class="col-4">
    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']);
    endif; ?>
    <div style="border: 5px solid #bdbdbd; border-radius: 10px; padding: 20px;">
        <form action="/auth/auth" method="post">
            <div class="row">
                <div class="col-12">
                    <h2>Login</h2>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">User Name</label>
                        <input class="form-control" name="username" id="username" type="text" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input class="form-control" name="password" id="password" type="password" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <button type="button" class="btn btn-primary form-control" id="login">Login</button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label"></label>
                        <input type="reset" class="btn btn-danger form-control" value="Reset" id="reset">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>