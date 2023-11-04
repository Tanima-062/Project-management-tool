/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
   
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
        apiKey: "AIzaSyDgzSaKXvtEX8_KNEvezWQWlVurXK-snDE",
        authDomain: "hardy-abode-257407.firebaseapp.com",
        databaseURL: "https://hardy-abode-257407.firebaseio.com",
        projectId: "hardy-abode-257407",
        storageBucket: "hardy-abode-257407.appspot.com",
        messagingSenderId: "405457552881",
        appId: "1:405457552881:web:c8d4c53b6cc065cf03f050",
        measurementId: "G-60LZG0BS59"
    });
  
/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };
  
    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});