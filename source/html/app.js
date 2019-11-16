if ("serviceWorker" in navigator) {
  if (navigator.serviceWorker.controller) {
    console.log("Found active serviceworker");
  } else {
    // Register the service worker
    navigator.serviceWorker
      .register("service-worker.js", {
        scope: "./"
      })
      .then(function (reg) {
        console.log(reg.scope);
      });
  }
}
