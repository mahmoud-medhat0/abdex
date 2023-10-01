        //add your credentials from firebase project
        const firebaseConfig = {
            apiKey: "AIzaSyASfcY2Vo9VzgdUwzz5n3Anxkyy2RCCkC8",
            authDomain: "mar-express-3b0a1.firebaseapp.com",
            projectId: "mar-express-3b0a1",
            databaseURL: "https://mar-express-3b0a1-default-rtdb.firebaseio.com",
            storageBucket: "mar-express-3b0a1.appspot.com",
            messagingSenderId: "1011680860544",
            appId: "1:1011680860544:web:2cefbc3b02fcc118bcc965",
            measurementId: "G-ZE90HDF3GV"
        };
        const app = firebase.initializeApp(firebaseConfig);
        const db = firebase.database();
        const refrence = db.ref('notification');
        firebase.database().ref("notification").on("child_removed", function(snapshot) {
            counter = counter - 1;
            document.getElementById('countermain').textContent = counter;
            document.getElementById('counterin').textContent = counter + ' Notifications';
            document.getElementById('id-' + snapshot.key).remove();
        });
        var counter = 0;
        refrence.on("child_added", function(snapshot) {
            var notification = '<div class="dropdown-divider"></div><a href="#" id="id-' + snapshot.key + '" class="dropdown-item"><i class="fas fa-envelope mr-2"></i>' + snapshot.val().title + '<br>' + snapshot.val().message + '<span class="float-right text-muted text-sm">' + snapshot.val().date + '<br>' + snapshot.val().time + '</span></a>';
            document.getElementById('mainnotification').innerHTML += notification;
            counter = counter + 1;
            document.getElementById('countermain').textContent = counter;
            document.getElementById('counterin').textContent = counter + ' Notifications';
            var body = snapshot.val().message;
            icon = 'https://homepages.cae.wisc.edu/~ece533/images/airplane.png';
            // var notification = new Notification(snapshot.val().title, { body, icon });
        });
        Notification.requestPermission().then(function(permission) {});