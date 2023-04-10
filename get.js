<!DOCTYPE html>
<html>
  <head>
    <title>Sending Value Automatically</title>
  </head>
  <body>
    <input type="text" id="valueInput">
    <script>
      function sendValue() {
        var value = document.getElementById("valueInput").value;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "http://169.254.31.36/value?value=" + value, true);
        xhr.send();
      }
      window.onload = sendValue;
    </script>
  </body>
</html>