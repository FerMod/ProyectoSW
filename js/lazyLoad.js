
$.loadImage = function(url) {
	
	// Define a "worker" function that should eventually resolve or reject the deferred object.
	var loadImage = function(deferred) {

		var image = new Image();

		// Set up event handlers to know when the image has loaded
		// or fails to load due to an error or abort.
		image.onload = loaded;
		image.onerror = failed; // URL returns 404, etc
		image.onabort = aborted; // IE may call this if user clicks "Stop"

		// Setting the src property begins loading the image.
		image.src = url;

		function loaded() {
			unbindEvents();
			// Calling resolve means the image loaded sucessfully and is ready to use.
			deferred.resolve(image);
		}

		function failed() {
			unbindEvents();
			// Calling reject means we failed to load the image (e.g. 404, server offline, etc).
			deferred.reject(image);
		}

		function aborted() {
			unbindEvents();
			deferred.abort(image);
		}

		function unbindEvents() {
			// Ensures the event callbacks only get called once.
			image.onload = null;
			image.onerror = null;
			image.onabort = null;
		}

	};

	// Create the deferred object that will contain the loaded image.
	// We don't want callers to have access to the resolve() and reject() methods, 
	// so convert to "read-only" by calling `promise()`.
	return $.Deferred(loadImage).promise();
};

$(document).ready(function() {

	var lazyElement = $(".lazyImage");
	var promises = [];

	lazyElement.each(function() {
		var image = $(this);
		var src = image.data("src");
		if (src) {
			promises.push($.loadImage(src).then(function() {
				image.prop("src", src);
			}));
			
			// $.loadImage(src).then(function() {
			// 	image.prop("src", src);
			// }, function() {
			// 	image.prop("src", "./img/loading.png");
			// })
			
		}
	});

	promises.push(lazyElement.slideDown());

	$.when.apply(null, promises).done(function() {
		lazyElement.fadeIn();
	});
});