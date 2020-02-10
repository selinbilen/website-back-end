<?=$this->load->view("static/head.php")?>
<body style="background:white">
    <main style="justify-content: start; padding:80px 0px">
    <h1>Modern Booking</h1>
    <div class="screen-5-holder">
        <div class="screen-5-side">
            <?php
            for ($i=1; $i < count($content); $i++) { 
            ?>
            <a class="clickable-link" href="<?=base_url('welcome/beauty_blog/'.$content[$i]->id)?>">
                <img class="link-image" src="<?=$content[$i]->image?>" style="width:400px; height: 230px;">
                <div class="link-header"><?=$content[$i]->title?></div>
                <div class="link-author"><?=$content[$i]->author?></div>
            </a>
            
            <?php
            }
            ?>
        </div>
        <!-- last thing -->
        <a class="clickable-link"  href="<?=base_url('welcome/beauty_blog/'.$content[0]->id)?>" style="flex:3 0 0">
            <img class="link-image" src="<?=$content[0]->image?>" style="width:960px; height: 540px;">
            <div class="link-header"><?=$content[0]->title?></div>
            <div class="link-author"><?=$content[0]->author?></div>
        </a>
    </div>
    </main>
</body>
</html>
