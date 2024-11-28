$(function () {
    "use strict";

    var firestoreDB;
    var defaultProject;
    let map;
    let driverLocationMarker = [];
    let driverLocationMarkerIds = [];
    let markerIcon =
        "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";

    //
    function loadMapView() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: 0.0, lng: 0.0 },
            zoom: 2
        });
    }

    //
    livewire.on("loadMap", data => {
        //
        markerIcon = {
            url: data,
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(40, 40),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 40)
        };

        //
        loadMapView();
    });

    //
    livewire.on("authenticateUser", data => {

        //
        var firebaseConfig = {
            apiKey: "" + data[0] + "",
            projectId: "" + data[1] + "",
            messagingSenderId: "" + data[2] + "",
            appId: "" + data[3] + "",
        };
        // Initialize Firebase
        defaultProject = firebase.initializeApp(firebaseConfig);
        //
        firebase
            .auth()
            .signInWithCustomToken(data[4])
            .then(userCredential => {
                // Signed in
                var user = userCredential.user;

                firestoreDB = defaultProject.firestore();
                // ...
                console.log("Authenticated");
            })
            .catch(error => {
                var errorCode = error.code;
                var errorMessage = error.message;
                // ...
                alert("Authentication failed:: " + errorCode + " " + errorMessage + " ");
            });
    });

    //
    livewire.on("loadDriversOnMap", data => {
        //
        driverLocationMarker.forEach(marker => {
            marker.setMap(null);
        });
        driverLocationMarkerIds = [];
        driverLocationMarker = [];

        data.forEach(driver => {
            listenToDriverNodeOnFCM(driver);
        });
    });

    //listen to driver locations on firebase
    function listenToDriverNodeOnFCM(driver) {
        //
        firestoreDB
            .collection("drivers")
            .doc("" + driver["id"] + "")
            .onSnapshot(doc => {
                //
                let driverLocationData = doc.data();

                if (driverLocationData) {
                    //
                    addMarker(
                        {
                            lat: driverLocationData.lat,
                            lng: driverLocationData.long
                        },
                        driver
                    );
                }
            });
    }

    //
    function addMarker(location, driver) {
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        //
        const driverId = driver["id"];
        const driverName = driver["name"];
        const driverPhone = driver["phone"];
        const driverPhoto = driver["photo"];
        const driverIsOnline = driver["is_online"];

        if (!driverLocationMarkerIds.includes(driverId)) {
            //
            const marker = new google.maps.Marker({
                position: location,
                icon: markerIcon,
                map: map
            });

            var statusTag = "<div class='w-2 h-2 p-1 rounded-full bg-red-500'></div>";
            if (driverIsOnline) {
                statusTag = "<div class='w-2 h-2 p-1 rounded-full bg-green-500'></div>";
            }

            //infowindow
            const contentString =
                '<div class="content w-56">' +
                '<div class="flex items-center justify-items-center">' +
                '<div class="space-x-4 w-4/12">' +
                '<img src="' +
                driverPhoto +
                '" class="w-12 h-12" />' +
                "</div>" +
                '<div class="w-8/12 space-y-2">' +
                '<p class="text-semibold text-md">' +
                driverName +
                "</p>" +
                '<p><a href="tel:' +
                driverPhone +
                '" class="text-md underline">' +
                driverPhone +
                "</a></p>" +
                '<p>' + statusTag + "</p>" +
                "</div>" +
                "</div>" +
                "</div>";
            const infowindow = new google.maps.InfoWindow({
                content: contentString,
                width: 220
            });

            marker.addListener("click", () => {
                infowindow.open({
                    anchor: marker,
                    map,
                    shouldFocus: true
                });
            });

            //
            driverLocationMarkerIds.push(driverId);
            driverLocationMarker.push(marker);
        }
        //marker already exists, so just update the location
        else {
            let driverIdIndex = driverLocationMarkerIds.indexOf(driverId);
            driverLocationMarker[driverIdIndex].setPosition(location);
        }
    }
});
