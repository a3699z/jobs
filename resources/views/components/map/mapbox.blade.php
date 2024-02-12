@props([
    'id' => 'map-box',
    'lat' => setting('default_lat'),
    'long' => setting('default_long'),
    'container' => 'map-box',
    'coordinates' => 'coordinates2',
])

<script>
    mapboxgl.accessToken = "{{ $setting->map_box_key }}";
    const {{ $coordinates }} = document.getElementById('coordinates');

    const {{ $container }} = new mapboxgl.Map({
        container: "{{ $id }}",
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [{{ $long }}, {{ $lat }}],
        zoom: 6
    });
    var marker = new mapboxgl.Marker({
            draggable: false
        }).setLngLat([{{ $long }}, {{ $lat }}])
        .addTo({{ $container }});

    function onDragEnd() {
        const lngLat = marker.getLngLat();
        let lat = lngLat.lat;
        let lng = lngLat.lng;
        $('#lat').val(lat);
        $('#lng').val(lng);
        document.getElementById('form').submit();
    }

    function add_marker(event) {
        var coordinates = event.lngLat;
        marker.setLngLat({{ $coordinates }}).addTo({{ $container }});
    }
    // zoom in and out 
    <x-mapbox-zoom-control />
</script>
