
function initMap() {
    var coordinates = {lat: 59.938525, lng: 30.323008},
        markerImage = 'img/pin.svg',

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 17,
            center: coordinates
        });
    marker = new google.maps.Marker({
        position: coordinates,
        map: map,
        animation: google.maps.Animation.BOUNCE,
        icon: markerImage
    });
    var InfoWindow = new google.maps.InfoWindow({
        content: '<h3 style="background-color: #fac563; font-style:italic;width: 200px; text-align: center;padding: 15px;border-radius: 10px;"><span style="font-size: 20px;">Ждем Вас!</span><br><br>Большая Конюшенная ул., 19/8, Санкт-Петербург, Россия, 191186</h3>'
    });

    marker.addListener('click', function () {
        InfoWindow.open(map, marker);
    });
    google.maps.event.addListener(InfoWindow, 'closeclick', function () {
        marker.setAnimation(google.maps.Animation.BOUNCE);
    });

    marker.addListener('click', function () {
        marker.setAnimation(null);
    });
}