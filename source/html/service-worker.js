//Service worker vars
const CACHE = "ProgressiveWeatherApp";
const offlineFallbackPage = "https://progressiveweatherapp.com/offlinefallbackpage.html";
const precacheFiles = [
    'https://progressiveweatherapp.com/Home',
    'https://progressiveweatherapp.com/favicon.ico',
    'https://progressiveweatherapp.com/offlinefallbackpage.html'

];

const networkFirstPaths = [
    'https://progressiveweatherapp.com/weather/*'
];

//Caching paths to be avoided
const avoidCachingPaths = [
    'https://progressiveweatherapp.com/Login/*'
];
//Service worker vars

//Compare URL with stored paths.
function pathComparer(requestUrl, pathRegEx) {
    return requestUrl.match(new RegExp(pathRegEx));
}

function comparePaths(requestUrl, pathsArray) {
    if (requestUrl) {
        for (let index = 0; index < pathsArray.length; index++) {
            const pathRegEx = pathsArray[index];
            if (pathComparer(requestUrl, pathRegEx)) {
                return true;
            }
        }
    }

    return false;
}

self.addEventListener("install", function (event) {
    self.skipWaiting();

    event.waitUntil(
            caches.open(CACHE).then(function (cache) {
        return cache.addAll(precacheFiles).then(function () {
            return cache.add(offlineFallbackPage);
        });
    })
            );
});

// Get claim for service worker
self.addEventListener("activate", function (event) {
    event.waitUntil(self.clients.claim());
});

// Listen event for failed fetch
self.addEventListener("fetch", function (event) {
    if (event.request.method !== "GET")
        return;

    if (comparePaths(event.request.url, networkFirstPaths)) {
        networkFirstFetch(event);
    } else {
        cacheFirstFetch(event);
    }
});

//Cache fetch
function cacheFirstFetch(event) {
    event.respondWith(
            fromCache(event.request).then(
            function (response) {
                event.waitUntil(
                        fetch(event.request).then(function (response) {
                    return updateCache(event.request, response);
                })
                        );

                return response;
            },
            function () {
                return fetch(event.request)
                        .then(function (response) {
                            //Add to cache
                            event.waitUntil(updateCache(event.request, response.clone()));
                            return response;
                        })
                        .catch(function (error) {
                            if (event.request.destination !== "document" || event.request.mode !== "navigate") {
                                return;
                            }
                            return caches.open(CACHE).then(function (cache) {
                                cache.match(offlineFallbackPage);
                            });
                        });
            }
    )
            );
}


//Request from network
function networkFirstFetch(event) {
    event.respondWith(
            fetch(event.request)
            .then(function (response) {
                event.waitUntil(updateCache(event.request, response.clone()));
                return response;
            })
            .catch(function (error) {
                console.log("No network. Serving from cache");
                return fromCache(event.request);
            })
            );
}

//Request from cache
function fromCache(request) {
    return caches.open(CACHE).then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return Promise.reject("no-match");
            }
            return matching;
        });
    });
}

function updateCache(request, response) {
    if (!comparePaths(request.url, avoidCachingPaths)) {
        return caches.open(CACHE).then(function (cache) {
            return cache.put(request, response);
        });
    }
    return Promise.resolve();
}
