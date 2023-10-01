/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts("https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"
);

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyASfcY2Vo9VzgdUwzz5n3Anxkyy2RCCkC8",
    authDomain: "mar-express-3b0a1.firebaseapp.com",
    projectId: "mar-express-3b0a1",
    databaseURL: "https://mar-express-3b0a1-default-rtdb.firebaseio.com",
    storageBucket: "mar-express-3b0a1.appspot.com",
    messagingSenderId: "1011680860544",
    appId: "1:1011680860544:web:2cefbc3b02fcc118bcc965",
    measurementId: "G-ZE90HDF3GV"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png"
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions
    );
});