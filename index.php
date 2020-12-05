<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>ArcGIS API for JavaScript Tutorials: Create a Starter App</title>
  <style>
    html,
    body,
    #viewDiv {
      padding: 0;
      margin: 0;
      height: 100%;
      width: 100%;
    }
  </style>

  <link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
  <script src="https://js.arcgis.com/4.17/"></script>
    <link rel="stylesheet" href="./style.css">
  <script>
    require([
      "esri/Map",
      "esri/views/MapView",
      "esri/tasks/RouteTask",
      "esri/tasks/support/RouteParameters",
      "esri/tasks/support/FeatureSet",
      "esri/Graphic"
    ], function (Map, MapView, RouteTask, RouteParameters, FeatureSet, Graphic) {

      var map = new Map({
        basemap: "topo-vector"
      });

      var view = new MapView({
        container: "viewDiv",
        map: map,
        center: [106.8033387, 10.8739831], // longitude, latitude
        zoom: 16
      });
      var routeTask = new RouteTask({
        url: "https://utility.arcgis.com/usrsvcs/appservices/gnSXcBKOBpfoK98l/rest/services/World/Route/NAServer/Route_World/solve"
      });

      view.on("click", function (event) {
        if (view.graphics.length === 0) {
          addGraphic("start", event.mapPoint);
          document.getElementById("start").value = `${event.mapPoint.longitude},${event.mapPoint.latitude}`;
        } else if (view.graphics.length === 1) {
          addGraphic("finish", event.mapPoint);
          document.getElementById("destination").value = `${event.mapPoint.longitude},${event.mapPoint.latitude}`;

          getRoute();
        } else {
          view.graphics.removeAll();
          addGraphic("start", event.mapPoint);
        }
      });

      // <?php
//   include_once('./db.php');
//   $addVehicle = add_new_vehicle('01542','#F00');
//   echo $addVehicle;
echo 'alert("hello")';
// ?>

      function addGraphic(type, point) {
        var graphic = new Graphic({
          symbol: {
            type: "simple-marker",
            color: type === "start" ? "white" : "red",
            size: "8px"
          },
          geometry: point
        });
        view.graphics.add(graphic);
      }

      function getRoute() {
        // Setup the route parameters
        var routeParams = new RouteParameters({
          stops: new FeatureSet({
            features: view.graphics.toArray() // Pass the array of graphics
          }),
          returnDirections: true
        });
        // Get the route
        routeTask.solve(routeParams).then(function (data) {
          // Display the route
          data.routeResults.forEach(function (result) {
            console.log(result,'zzzzzzz');
          document.getElementById("distance").value = result.route.attributes.Total_Kilometers;
          document.getElementById("estimate-time").value = result.route.attributes.Total_TravelTime;

            result.route.symbol = {
              type: "simple-line",
              color: [5, 150, 255],
              width: 3
            };
            view.graphics.add(result.route);
          });
        });
      }
    });
  </script>
</head>

<body>

<div class="container">
    <div class="header">
    <div class='header__info'>
      <label>Điểm đi</label>
      <input type="text" id='start'>
    </div>
    <div class='header__info'>
      <label>Điểm đến</label>
      <input type="text" id='destination'>
    </div>
    <div class='header__info'>
      <label>Khoảng cách(km)</label>
      <input type="text" id='distance'>
    </div>
    <div class='header__info'>
      <label>Ước lượng thời gian(phút)</label>
      <input type="text" id='estimate-time'>
    </div>
  </div>
  <div id="viewDiv"></div>
</div>
</body>

</html>