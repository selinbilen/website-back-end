<?=$this->load->view("static/head.php")?>
<body>
    <main>
    <img id="logo" alt="logo" width="500" height="500" src="<?=base_url()?>assets/modern_booking_logo.png">
    <h1>Modern Booking</h1>

        <form action="<?=base_url('welcome/index');?>" method="POST">
            <input class="user-input" type="email" name="username" placeholder="email">
            <input class="user-input" type="password" name="password" placeholder="password">
            <button type="submit">Log in</button> 
            <span>or</span>
            <a href="<?=base_url('welcome/signup')?>">Sign up</a>
        </form>
    </main>
</body>
</html>
