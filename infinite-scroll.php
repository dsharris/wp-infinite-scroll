<?php

	class InfiniteScroll {

		static $inst;
		private $template_directory, $template_suffix;

		public function __construct () {
			// Fill these in !!
			$this->template_directory = "templates";
			$this->template_suffix = ".ejs";

		}

		public function setup_ajax () {
			add_action("wp_ajax_infinite_scroll", array( InfiniteScroll::inst() , "load_more" ));
            add_action("wp_ajax_nopriv_infinite_scroll", array( InfiniteScroll::inst() , "load_more" ));

            return $this;
		}

		public function inst () {
			if (!$inst)
				$inst = new InfiniteScroll;

			return $inst;
		}

		public function load_more () {
			$opts = (object)$_POST;

			$opts->paged = $opts->page;
			$opts->taxonomy_name = $opts->taxonomyName;
			$opts->taxonomy_term = $opts->taxonomyTerm;
			$opts->post_per_page = isset($opts->postsPerPage) ? $opts->postsPerPage : 8;

			$this->run_query($opts);

			if (isset($opt->cleaner)) {
				$results = $this->{$opts->cleaner};
			} else {
				$results = $this->query->posts;
			}

			echo json_encode($results);
			exit;
			die;
		}

		public function load_underscore () {
			echo '<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>';

			return $this;
		}

		public function get_template ($template_name) {
			$template = file_get_contents(get_template_directory() . '/' . $this->template_directory . '/' . $template_name . $this->template_suffix);

			echo $template;

			return $this;
		}

		private function run_query ($opts) {
			$args = array (
				'paged'			=> $opts->paged,
				'post_type'		=> 'post',
				'posts_per_page'	=> $opts->post_per_page,
				'tax_query'		=> array()
			);

			if (is_array($opts->taxonomy_name)) {
				foreach ($opts->taxonomy_name as $key => $name) {
					$args['tax_query'][] = array(
						'taxonomy' => $name,
						'field'    => 'id',
						'terms'    => $opts->taxonomy_term[$key],
					);
				}
			} else {
				$args['tax_query'][] = array(
					'taxonomy' => $opts->taxonomy_name,
					'field'    => 'id',
					'terms'    => $opts->taxonomy_term,
				);
			}

			$this->query = new WP_Query( $args );
		}


		// setup your cleaner functions here
		// being as we are not actually loading on a page on this request, feel free to mess with the the loops stuff
		// clean_data is a super basic example
		private function clean_data () {
			$results = array();

			while ($this->query->have_posts()) :
				$this->query->the_post();
				$item = array();

				// put whatever you need in item
				// remember the goal is to only return data you plan to use
				// also place any logic for creating data here, this will keep your templates cleaner
				$item['title'] = get_the_title();
				$item['permalink'] = get_the_permalink();
				$item['fields'] = get_fields();

				$results[] = $item;
			endwhile;

			// dont forget to return result
			return $results;
		}

	}

	InfiniteScroll::inst()->setup_ajax();