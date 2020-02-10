<?=$this->load->view("static/head.php")?>
<body style="background:white">
    <main style="justify-content: start; padding:80px 0px">
        <img id="logo" alt="logo" src="<?=base_url()?>assets/modern_booking_logo.png" style="width:120px; height: 120px;">
        <h1><?=$content->title?></h1>
        <div class="screen-6-text"><?=$content->content?></div>
        <div class="clickable-link" style="flex-direction: row; flex-wrap: wrap;">
            <a class="clickable-link" href="<?=$content->link_1?>" style="margin:20px"
            ><img class="link-image" src="<?=$content->image_1?>" style="width:120px; height: 120px;"></a>
            <a class="clickable-link" href="<?=$content->link_2?>" style="margin:20px"
            ><img class="link-image" src="<?=$content->image_2?>" style="width:120px; height: 120px;"></a>
            <a class="clickable-link" href="<?=$content->link_3?>" style="margin:20px"
            ><img class="link-image" src="<?=$content->image_3?>" style="width:120px; height: 120px;"></a>
            
        </div>
    </main>
</body>
</html>
