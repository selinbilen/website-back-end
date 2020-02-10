<?=$this->load->view("static/head.php")?>
<body>
  <div class="stripe-vertical"></div>
  <div class="stripe-horizontal"></div>
    <main>
    <img id="logo" alt="logo" src="<?=base_url()?>assets/modern_booking_logo.png">
    <h1>Modern Booking</h1>
        <form action="<?=base_url("welcome/signup")?>" method="POST">
            <input class="user-input" type="email" name="email" placeholder="e-mail">
            <input class="user-input" type="text" name="name" placeholder="name">
            <input class="user-input" type="text" name="surname" placeholder="surname">
            <input class="user-input" type="password" name="password" placeholder="password">
            <button type="submit">Submit</button> 
        </form>
    </main>
</body>
</html>
