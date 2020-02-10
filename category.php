<?=$this->load->view("static/head.php")?>
<body>
  <div class="stripe-vertical"></div>
  <div class="stripe-horizontal"></div>
    <main style="justify-content: start; padding:80px 0px">
    <h1>Modern Booking</h1>
        <!--
        <form>
            <input class="user-input" type="text" name="location" placeholder="location">
            <button type="submit">Submit</button> 
        </form>
    -->
        <div style="border: 1px;width: 600px;height: 600px"id="map"></div>
    </main>
</body>
<script src="https://api-maps.yandex.ru/2.1/?lang=en_US&amp;apikey=abd227ee-4600-44e7-9f91-e120d66c882d" type="text/javascript"></script>

<script type="text/javascript">
coordsLong=[<?=$longs?>];
coordsLat=[<?=$lats?>];
contents=[<?=$contents?>];

	ymaps.ready(init);

function init() {
    var myMap = new ymaps.Map("map", {
            center: [41.015, 28.979],
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        })
    let a=0;
    while(a<coordsLong.length){
    	
    myMap.geoObjects
        .add(new ymaps.Placemark([coordsLat[a],coordsLong[a]], {
            balloonContent: contents[a],
        }, {
            preset: 'islands#blueCircleDotIconWithCaption',
            iconCaptionMaxWidth: '50'
        }));
a++;
    }

}

</script>
</html>
