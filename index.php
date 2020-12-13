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
      "esri/Graphic",
      "esri/widgets/Slider",
      "esri/tasks/support/MultipartColorRamp",
    ], function (Map, MapView, RouteTask, RouteParameters, FeatureSet, Graphic, Slider, MultipartColorRamp) {

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

      // const xmlHttp = new XMLHttpRequest();
      // xmlHttp.onreadystatechange = function() {
      //   if (this.readyState == 4 && this.status == 200) {
      //     // document.getElementById("demo").innerHTML = this.responseText;
      //     console.log(this.responseText);
      //   }
      // };
      // xmlHttp.open('POST', 'db.php', true);
      // xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      // // xmlHttp.send("function=add_new_vehicle&reg_plate=abc&color=red");
      // // xmlHttp.send("function=node");
      // let nodeList = "";
      // [1, 2, 3, 4, 5, 6].forEach(i => {
      //   nodeList += "&list[]=" + i;
      // });
      // xmlHttp.send("function=add_new_arc" + nodeList);

      
      

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
          document.getElementById("distance").value = result.route.attributes.Total_Kilometers.toFixed(2);
          document.getElementById("estimate-time").value = result.route.attributes.Total_TravelTime.toFixed(4);

            result.route.symbol = {
              type: "simple-line",
              color: [5, 150, 255],
              width: 3
            };
            view.graphics.add(result.route);
          });
        });

      }

      const slider = new Slider({
        container: "sliderDiv",
        min: 0,
        max: 10,
        values: [ 10 ],
        snapOnClickEnabled: false,
        visibleElements: {
          labels: true,
          rangeLabels: true
        },
      });

      slider.tickConfigs = [{
        mode: "count",
        values: 11,
        labelsVisible: true,
        tickCreatedFunction: function(initialValue, tickElement, labelElement) {
          labelElement.innerHTML = 't' + labelElement["data-value"];
          // tickElement.classList.add("largeTicks");
          // labelElement.classList.add("largeLabels");
          labelElement.onclick = function() {
            const newValue = labelElement["data-value"];
            slider.values = [ newValue ];
          };
        }
      }];
      view.ui.add(slider);
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
      <label>Khoảng cách (km)</label>
      <input type="text" id='distance'>
    </div>
    <div class='header__info'>
      <label>Ước lượng thời gian (phút)</label>
      <input type="text" id='estimate-time'>
    </div>
  </div>
  <div id="viewDiv"></div>
  <div id="sliderDiv" class="footer"></div>
</div>
</body>

</html>