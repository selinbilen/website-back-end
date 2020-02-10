<?=$this->load->view("static/head.php")?>
<body>
  <div class="stripe-vertical"></div>
  <div class="stripe-horizontal-3"></div>
    <main>
    <img id="logo" alt="logo" src="<?=base_url()?>assets/modern_booking_logo.png">
    <h1>Modern Booking</h1>
    <div class="button-holder">
      <a class="screen-3-button" href="<?=base_url("welcome/category/hair")?>">Hair</a>
      <a class="screen-3-button" href="<?=base_url("welcome/category/nails")?>">Nails</a>
      <a class="screen-3-button" href="<?=base_url("welcome/category/spa")?>">Spa</a>
      <a class="screen-3-button" href="<?=base_url("welcome/beauty")?>">Beauty</a>
    </div>
    </main>
</body>
</html>
