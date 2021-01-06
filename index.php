<?php
session_start();
?>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Deviation detection1111</title>
  <link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
  <script src="https://js.arcgis.com/4.17/"></script>
  <link rel="stylesheet" href="./style.css">
  <?php
  $vehicle_id = "";
  $color = "";
  if (isset($_SESSION['vehicle'])) {
    $vehicle_id = $_SESSION['vehicle']['id'];
  }
  if (isset($_SESSION['vehicle'])) {
    $color = $_SESSION['vehicle']['color'];
  }
  ?>
  <script defer>
    require([
      "esri/Map",
      "esri/views/MapView",
      "esri/tasks/RouteTask",
      "esri/tasks/support/RouteParameters",
      "esri/tasks/support/FeatureSet",
      "esri/Graphic",
      "esri/widgets/Slider",
      "esri/tasks/support/MultipartColorRamp",
    ], function(Map, MapView, RouteTask, RouteParameters, FeatureSet, Graphic, Slider, MultipartColorRamp) {
      const DEVIATION_LEVEL = {
        low: {
          color: '255, 255, 1',
          message: 'Low: Bạn vừa rời khỏi lộ trình của mình.'
        },
        medium: {
          color: '255, 182, 1',
          message: 'Medium: Bạn đã cách xa lộ trình. Hãy trở lại lộ trình.'
        },
        high: {
          color: '252, 110, 4',
          message: 'High: Bạn đã đi rất xa lộ trình. Hãy trở lại đúng lộ trình.'
        },
        uncontrol: {
          color: '255, 0, 0',
          message: 'Uncontrol: Bạn đã bị mất kiểm soát. Hãy trở lại đúng lộ trình.'
        },
      };
      const THRESHOLD = {
        low: 0.00015,
        medium: 0.0002,
        high: 0.00025,
        uncontrol: 0.0003
      };

      function drawPoint(point, color = 'gray') {
        var graphic = new Graphic({
          symbol: {
            type: "simple-marker",
            color,
            size: "4px"
          },
          geometry: point
        });
        view.graphics.add(graphic);
      }

      function drawLine(paths, color = 'black') {
        var polyline = {
          type: "polyline",
          paths: paths
        };
        var polylineSymbol = {
          type: "simple-line",
          color,
          width: 1
        };

        var polylineGraphic = new Graphic({
          geometry: polyline,
          symbol: polylineSymbol
        });

        view.graphics.add(polylineGraphic);
      }

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
      //Get data from session
      let vehicle_id = "<?php echo $vehicle_id; ?>";
      let color = "<?php echo $color; ?>";

      //Initial variables 
      let paths = []
      let pathPoints = []
      let deviation = []
      let starter = []
      let currentNode = null;
      //Flag variables
      let startDrag = false;
      let isOk = false;
      let isComplete = false;

      view.on("click", function(event) {
        const {
          longitude,
          latitude
        } = event.mapPoint;
        if (view.graphics.length < 2) {
          starter.push([longitude, latitude])
        }
        if (view.graphics.length === 0) {
          addPoint("start", event.mapPoint);
          document.getElementById("start").value = `${longitude},${latitude}`;

        } else if (view.graphics.length === 1) {
          addPoint("finish", event.mapPoint);
          document.getElementById("destination").value = `${longitude},${latitude}`;
          document.getElementById("status").innerHTML = '3. Hãy chọn lộ trình di chuyển của bạn';

          let btn = document.createElement("button");
          btn.className = 'btnELe';
          btn.innerHTML = 'Hoàn tất';
          btn.addEventListener('click', function() {
            isComplete = true;
            drawLine(paths, color)
            isOk = true;
            const {
              length
            } = paths;
            //add Slider for time dimension
            const slider = new Slider({
              container: "sliderDiv",
              min: 0,
              max: length - 1,
              values: [length - 1],
              visibleElements: {
                labels: true,
                rangeLabels: true
              },
            });
            slider.steps = [-1, ...paths].map((item, index) => index)
            slider.tickConfigs = [{
              mode: "count",
              values: length,
              labelsVisible: true,
              tickCreatedFunction: function(initialValue, tickElement, labelElement) {
                labelElement.innerHTML = 't' + labelElement["data-value"];
                labelElement.onclick = function() {
                  const newValue = labelElement["data-value"];
                  slider.values = [newValue];
                };
              }
            }];
            //event for sliding time
            slider.on('thumb-drag', function({
              index,
              state,
              type,
              value
            }) {
              if (state === 'stop') {
                if (startDrag) {
                  view.graphics.remove(currentNode)
                  const divBefore = document.getElementsByClassName('flicker');
                  document.getElementById("btn").removeChild(divBefore[0])
                }
                //draw current Position
                var graphicPath = new Graphic({
                  symbol: {
                    type: "simple-marker",
                    color: "blue",
                    size: "8px"
                  },
                  geometry: pathPoints[value]
                });
                view.graphics.add(graphicPath);
                startDrag = true;
                currentNode = graphicPath
                //Style for deviation message
                if (deviation.length) {
                  const {
                    color,
                    message
                  } = transformLevel(deviation[value]);
                  document.getElementById('deviation').value = +deviation[value] * 1000
                  var style = document.createElement('style');
                  style.type = 'text/css';
                  var keyFrames = `
                  @keyframes warningDeviation{
                    from{
                      background-color: rgba(${color},0.4);
                    }
                    to{
                      background-color: rgba(${color},0.1);
                    }
                  }`;
                  style.innerHTML = keyFrames;
                  document.getElementsByTagName('head')[0].appendChild(style);
                  const div = document.createElement('div');
                  div.className = 'flicker';
                  if (message) {
                    div.innerHTML = `<div class='warning-message'>${message}</div>`
                  }
                  document.getElementById('btn').appendChild(div);
                  console.log('COLOR', color);
                }
              }
            })
            view.ui.add(slider);
            //Remove button when show slider
            btn.remove();
          })
          document.getElementById("btn").appendChild(btn);
          const [start, des] = view.graphics.items;
          //get Route
          getRoute([start.geometry.latitude, start.geometry.longitude], [des.geometry.latitude, des.geometry.longitude]);
        } else {
          //Bắt đầu cho ghi nhận lộ trình di chuyển của user
          if (!isComplete) {
            console.log(event.mapPoint, 'map point')
            drawPoint(event.mapPoint)
            paths.push([longitude, latitude])
            pathPoints.push(event.mapPoint)
            console.log('arc id', arc_id);
            const xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                deviation.push(this.responseText);
              }
            };
            xmlHttp.open('POST', 'db.php', true);
            xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlHttp.send("function=add_gps_point&point[]=" + longitude + "&point[]=" + latitude + "&vehicle_id=" + vehicle_id + "&arc_id=" + arc_id);
          }
          //draw paths user

        }


      });

      function addPoint(type, point) {
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
      let arc_id = '';

      function getRoute(start, des) {

        var routeParams = new RouteParameters({
          stops: new FeatureSet({
            features: view.graphics.toArray()
          }),
          returnDirections: true
        });
        // Get the route
        routeTask.solve(routeParams).then(function(data) {
          // Display the route
          data.routeResults.forEach(function(result) {
            //insert the route task
            console.log(result.route.geometry.paths) // route task []
            const Arc = [start, ...result.route.geometry.paths[0], des];
            const xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                // document.getElementById("demo").innerHTML = this.responseText;
                console.log(this.responseText);
                arc_id = this.responseText
              }
            };
            xmlHttp.open('POST', 'db.php', true);
            xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            let nodeList = "";
            Arc.forEach(i => {
              nodeList += "&list[]=" + i;
            });
            xmlHttp.send("function=add_new_route" + nodeList + "&vehicle_id=" + vehicle_id);
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
      //convert deviation to data
      function transformLevel(deviation) {
        if (deviation >= THRESHOLD.uncontrol) {
          return DEVIATION_LEVEL.uncontrol;
        }
        if (deviation >= THRESHOLD.high) {
          return DEVIATION_LEVEL.high;
        }
        if (deviation >= THRESHOLD.medium) {
          return DEVIATION_LEVEL.medium;
        }
        if (deviation >= THRESHOLD.low) {
          return DEVIATION_LEVEL.low;
        }
        return 'transparent';
      }

    });
  </script>

</head>

<body>
  <div class="status" id="status">1. Nhập thông tin phương tiện</div>
  <div class='modal' id='modal'>
    <div class="overlay"></div>
    <div class="vehicle-form" id='vehicle-form'>
      <form action="vehicle.php" method='POST'>
        <div class="field">
          <label for="id">Biển số xe</label><input type="text" name='id' id='id'>
        </div>
        <div class="field">

          <label for="color">Màu xe</label><input type="color" name='color' id='color'>
        </div>
        <div class="field">
          <button type='submit'>Đăng ký</button>
        </div>
      </form>
    </div>
  </div>
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
      <div class='header__info'>
        <label>Độ lệch</label>
        <input type="text" id='deviation'>
      </div>
    </div>
    <div id="btn"></div>
    <div id="viewDiv"></div>
    <div id="sliderDiv" class="footer"></div>
    <div class="color-ramps"></div>
    <div class="color-ramps-explain"><span>Độ lệch thấp</span><span>Độ lệch cao</span></div>
  </div>
</body>

<?php

if (isset($_SESSION['vehicle'])) {
  echo '<script type="text/javascript">
    document.getElementById("modal").style.display="none";
    document.getElementById("status").innerHTML="2. Chọn điểm đi và điểm đến";
    </script>';
}
?>

</html>