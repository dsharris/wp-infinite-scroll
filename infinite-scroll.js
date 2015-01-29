// I'm assuming your using an AMD framework
module.exports = function() {

	var $root = $(".infinite-scroll"),
		opts = $root.data(),
		page = opts.page || 1,
		// the ID of the script tag in your template
		template = _.template( $("#infinite-scroll-post-template").html()),
		firePoint, windowHeight, pageDone,

		makeRequest = function (next) {
			// setup the request data
			var postData = {
					'page' : page++,
					'action' : 'infinite_scroll',
					// the following 3 could be done differemtly, but this seems the simpler example
					'postsPerPage' : opts.postsPerPage,
					'taxonomyName' : opts.taxonomyName,
					'taxonomyTerm' : opts.taxonomyTerm
				};

			// this will prevent the scroll trigger from firing again till we are done
			killBindings();

			$.ajax({
				type: 'POST',
				// if you have another method for pulling the url, go for it, also watch out for https vs http stuff
				url : [window.location.origin, 'wp-admin', 'admin-ajax.php'].join('/'),
				data : postData,
				dataType: "JSON"
			}).done(function (results) {

				// if there are no results, lets play with them
				if (!!results.length) {
					// itterate over the results, works on [] or {} so don't worry
					_.each(results, function (result) {
						// create the HTML and add it to the wrapper
						$root.append( template(result) );
					});

					// being as this set wasn't empty, we have to assume we will have more
					// however if the set was empty, out bindings for scrolling
					// and resizing are both now gone, thus we won't make any more requests

					// need to re-calculate the height as we just added content
					setupScrolling();

					// bind it back up yo
					runBindings();
				}
			});
		},

		setupScrolling = function () {
			firePoint = $(".infinite-scroll").offset().top + $(".infinite-scroll").height();
			windowHeight = $(window).height();
		},

		loadMore = function () {
			if (($(window).scrollTop() + windowHeight) >= firePoint && !pageDone)
				makeRequest();
		},

		killBindings = function () {
			$(window).unbind('scroll', loadMore);
			$(window).unbind('resize', setupScrolling);
		},

		runBindings = function () {
			$(window).on('scroll', loadMore);
			$(window).on('resize', setupScrolling);
		},

		init = function () {
			// if you pre-loaded your posts in your wordpress template use this
			setupScrolling();
			runBindings();

			// if you did not pre-load posts, load it now
			makeRequest();
		};

	init();
};


// if you are using the 40D selector system, here is the code for that
'.infinite-scroll': ['./infinite-scroll'],