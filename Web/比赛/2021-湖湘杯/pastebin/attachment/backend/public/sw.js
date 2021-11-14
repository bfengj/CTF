importScripts("https://cdn.jsdelivr.net/npm/workbox-cdn/workbox/workbox-sw.js");

if (workbox) {
	console.log(`Yay! Workbox is loaded 🎉`);

    workbox.routing.registerRoute(
		/\.(?:js|css)$/,
		new workbox.strategies.CacheFirst({
			cacheName: "static-resources",
		})
	);
} else {
	console.log(`Boo! Workbox didn't load 😬`);
}