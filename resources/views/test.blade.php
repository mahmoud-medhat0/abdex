<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries
      
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
          apiKey: "AIzaSyASfcY2Vo9VzgdUwzz5n3Anxkyy2RCCkC8",
          authDomain: "mar-express-3b0a1.firebaseapp.com",
          databaseURL: "https://mar-express-3b0a1-default-rtdb.firebaseio.com",
          projectId: "mar-express-3b0a1",
          storageBucket: "mar-express-3b0a1.appspot.com",
          messagingSenderId: "1011680860544",
          appId: "1:1011680860544:web:2cefbc3b02fcc118bcc965",
          measurementId: "G-ZE90HDF3GV"
        };
      
        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
      </script>
</body>
</html>