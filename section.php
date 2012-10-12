<?php
/*
Section: Dettagli Portfolio
Author: Enrique Chávez
Author URI: http://tmeister.net
Version: 1.0
Description: Show your work in a beautiful way with this section, Detaggli Portfolio can be use for show a photographer's work, a designer's work or just to show personal images using great transitions and effects driven by CSS3 and jQuery, also, all happens in just one site thanks to ajax. The Dettagli Portfolio has more than 20 options to play with.
Class Name: TmDettagliPortfolio
Demo: http://pagelines.tmeister.net/dettagli/
External: http://tmeister.net/themes-and-sections/dettagli-portfolio/
Cloning: false
Workswith: main, content, templates, header
*/

/*
 * PageLines Headers API
 * 
 *  Sections support standard WP file headers (http://codex.wordpress.org/File_Header) with these additions:
 *  -----------------------------------
 * 	 - Section: The name of your section.
 *	 - Class Name: Name of the section class goes here, has to match the class extending PageLinesSection.
 *	 - Cloning: (bool) Enable cloning features.
 *	 - Depends: If your section needs another section loaded first set its classname here.
 *	 - Workswith: Comma seperated list of template areas the section is allowed in.
 *	 - Failswith: Comma seperated list of template areas the section is NOT allowed in.
 *	 - Demo: Use this to point to a demo for this product.
 *	 - External: Use this to point to an external overview of the product
 *	 - Long: Add a full description, used on the actual store page on http://www.pagelines.com/store/
 *
 */

/**
 *
 * Section Class Setup
 * 
 * Name your section class whatever you want, just make sure it matches the 
 * "Class Name" in the section meta (above)
 * 
 * File Naming Conventions
 * -------------------------------------
 *  section.php 		- The primary php loader for the section.
 *  style.css 			- Basic CSS styles contains all structural information, no color (autoloaded)
 *  images/				- Image folder. 
 *  thumb.png			- Primary branding graphic (300px by 225px - autoloaded)
 *  screenshot.png		- Primary Screenshot (300px by 225px)
 *  screenshot-1.png 	- Additional screenshots: (screenshot-1.png -2 -3 etc...optional).
 *  icon.png			- Portable icon format (16px by 16px)
 *	color.less			- Computed color control file (autoloaded)
 *
 */
class TmDettagliPortfolio extends PageLinesSection 
{
	/**
	 *
	 * Section Variable Glossary (Auto Generated)
	 * ------------------------------------------------
	 *  $this->id			- The unique section slug & folder name
	 *  $this->base_url 	- The root section URL
	 *  $this->base_dir 	- The root section directory path
	 *  $this->name 		- The section UI name
	 *  $this->description	- The section description
	 *  $this->images		- The root section images URL
	 *  $this->icon 		- The section icon url
	 *  $this->screen		- The section screenshot url 
	 *  $this->oset			- Option settings array... needed for 'ploption' (contains clone_id, post_id)
	 * 
	 * 	Advanced Variables
	 * 		$this->view				- If the section is viewed on a page, archive, or single post
	 * 		$this->template_type	- The PageLines template type
	 */


	var $tax_id         = 'tm_dettagli_tax';
	var $custom_post_id = 'tm_dettagli_post';
	var $domain 		= 'tm_dettagli';



	/**
	 *
	 * Section API - Methods & Functions
	 * 
	 * Below we'll give you a listing of all the available 
	 * Section 'methods' or functions, and other techniques.
	 * 
	 * Please reference other PageLines sections for ideas/tips on how
	 * to use more advanced functionality.
	 *
	 */

	/**
	 *
	 * Persistent Section Code 
	 * 
	 * Use the 'section_persistent()' function to add code that will run on every page in your site & admin
	 * Code here will run ALL the time, and is useful for adding post types, options etc.. 
	 *
	 */
	function section_persistent()
	{
		$this->setup_custom_post();
		$this->post_meta_setup();
		add_image_size( "dettagli-mini", 240, 180, true );
		add_action('wp_ajax_load_project', array($this, 'get_project'));
		add_action('wp_ajax_nopriv_load_project', array($this, 'get_project'));
	} 

	/**
	 *
	 * Site Head Section Code 
	 * 
	 * Code added in the 'section_head()' function will be run during the <head> element of your site's
	 * 'front-end' pages. You can use this to add custom Javascript, or manually add scripts & meta information
	 * It will *only* be loaded if the section is present on the page template.
	 *
	 */
	function section_head($clone_id = null){
	?>
		<script type="text/javascript">
			var adminUrl = '<?php echo get_site_url() ?>';
		</script>
	<?php

		global $post, $pagelines_ID;
		$current_page_post = $post;
		$oset              = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$top_bar           = ( ploption('tm_di_top_bar_color', $oset) ) ? ploption('tm_di_top_bar_color', $oset) : '#000000';
		$desc_back         = ( ploption('tm_di_de_back_color', $oset) ) ? ploption('tm_di_de_back_color', $oset) : '#E0E0E0';
		$desc_title        = ( ploption('tm_di_de_title_color', $oset) ) ? ploption('tm_di_de_title_color', $oset) : '#414141';
		$desc_exc          = ( ploption('tm_di_de_excerpt_color', $oset) ) ? ploption('tm_di_de_excerpt_color', $oset) : '#7A797C';
		$det_back          = ( ploption('tm_di_dt_back_color', $oset) ) ? ploption('tm_di_dt_back_color', $oset) : '#F26E0E';
		$det_text          = ( ploption('tm_di_dt_text_color', $oset) ) ? ploption('tm_di_dt_text_color', $oset) : '#ffffff';
		$nav_arrow         = ( ploption('tm_di_arrow_color', $oset) ) ? ploption('tm_di_arrow_color', $oset) : '#A6A89C';
		$nav_arrow_over    = ( ploption('tm_di_arrow_over_color', $oset) ) ? ploption('tm_di_arrow_over_color', $oset) : '#CAC7B4';
		$grid_title_color  = ( ploption('tm_di_grid_title_color', $oset) ) ? ploption('tm_di_grid_title_color', $oset) : '#818181';
		$grid_menu         = ( ploption('tm_di_grid_menu_color', $oset) ) ? ploption('tm_di_grid_menu_color', $oset) : '#7A7A7A';
		$grid_menu_over    = ( ploption('tm_di_grid_menu_color_over', $oset) ) ? ploption('tm_di_grid_menu_color_over', $oset) : '#ffffff';
		$grid_menu_ind     = ( ploption('tm_di_grid_menu_indicator', $oset) ) ? ploption('tm_di_grid_menu_indicator', $oset) : '#F26E0E';
		$item_back         = ( ploption('tm_di_item_back', $oset) ) ? ploption('tm_di_item_back', $oset) : '#3A3A3A';
		$item_title        = ( ploption('tm_di_item_title', $oset) ) ? ploption('tm_di_item_title', $oset) : '#ffffff';
		$item_exc          = ( ploption('tm_di_item_excert', $oset) ) ? ploption('tm_di_item_excert', $oset) : '#ffffff';

	?>

	<!-- Start Dynamic CSS for Dettagli -->
	<style type="text/css">
		.dettagli-nav {
		    border-top-color: <?php echo $top_bar ?>;
		}
		.dettagli-data-header{
			background-color: <?php echo $desc_back ?>;
		}
		.dettagli-data-header h1{
			color: <?php echo $desc_title ?>;
		}
		.dettagli-description{
			color: <?php echo $desc_exc ?>;
		}
		ul.dettagli-meta{
			background-color: <?php echo $det_back ?>;
		}
		ul.dettagli-meta li,
		ul.dettagli-meta li a{
			color: <?php echo $det_text ?>;
		}
		a.dettagli-nav-arrow{
			background-color: <?php echo $nav_arrow ?>;
		}
		a.dettagli-prev:hover, 
		a.dettagli-next:hover {
			background-color: <?php echo $nav_arrow_over ?>;
		}
		.tags-wrapper h2{
			color: <?php echo $grid_title_color ?>;
		}
		ul.tags-filter a{
			color: <?php echo $grid_menu ?>;
		}
		ul.tags-filter li.current a {
			color: <?php echo $grid_menu_over ?>;
			background: <?php echo $grid_menu_ind ?>;
		}
		ul.tags-filter li.current a::after{
			border-top-color: <?php echo $grid_menu_ind ?>;
		}
		.dettagli-text-holder{
			background-color: <?php echo $item_back ?>
		}
		.dettagli-text h3{
			color: <?php echo $item_title ?>;
		}
		.dettagli-text p{
			color: <?php echo $item_exc ?>;
		}
	</style>
	<!-- Ends Dynamic CSS for Dettagli -->
	<?php

	} 

	/**
	 * 
	 * Add the proper JS files
	 * 
	 */

	function section_scripts(){
		return array(
			'jqueryEase' => array(
				'file'       => $this->base_url . '/js/jquery.easing.1.3.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.3'
			),
			'isotope' => array(
				'file'       => $this->base_url . '/js/jquery.isotope.min.js',
				'dependancy' => array('jquery'),
				'location'   => 'footer',
				'version'    => '1.0.0'
			),
			'dettagli' => array(
				'file'       => $this->base_url . '/js/dettagli.js',
				'dependancy' => array('isotope'),
				'location'   => 'footer',
				'version'    => '1.0.0'
			),
		);
	}

	/**
	 *
	 * Section Template
	 * 
	 * The 'section_template()' function is the most important section function. 
	 * Use this function to output all the HTML for the section on pages/locations where it's placed.
	 * 
	 * Here we've included some example processing and output for a 'Pull Quote' section.
	 *
	 */
 	function section_template( $clone_id = null ){
		global $post, $pagelines_ID;
		$current_page_post = $post;
		$oset              = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$grid_title        = ( ploption('tm_di_grid_title', $oset) ) ? ploption('tm_di_grid_title', $oset) : 'Latest Works';
		$limit             = ( ploption('tm_di_items', $oset) ) ? ploption('tm_di_items', $oset) : '12';

		$tags = get_terms($this->tax_id);

		$projects = $this->get_posts(null, $limit);

 		if( !count($projects) ){
			echo setup_section_notify($this, __('Sorry, there are no projects to display.', 'post'), get_admin_url().'edit.php?post_type=tm_dettagli_post', 'Please create some projects' );
			return;
		}

 		$first_project = $projects[0];
 		$foset  = array('post_id' => $first_project->ID);
		$first_client = plmeta('tm_dettagli_client', $foset);
 		$first_url = plmeta('tm_dettagli_link', $foset);
 		$first_date = plmeta('tm_dettagli_date', $foset);

		$cats = wp_get_post_terms($first_project->ID, $this->tax_id);
		$cout = "";
		$indexCount = 0;
		foreach ($cats as $cat) {
			$cout .= $cat->name.", ";
		}
		$cout = substr($cout, 0, -2);




	?>
	 	<div class="dettagli-window">
	 		<div class="dettagli-nav" id="dettagli-nav">
				<!-- <div class="dettagli-nav-loading" style="display: none; "></div> -->
				<a class="dettagli-nav-arrow dettagli-prev" href="#" >Prev</a>
				<a class="dettagli-nav-arrow dettagli-next" href="#">Next</a>
	 		</div>
	 		<div class="dettagli-wrapper">
	 			<div class="dettagli-content">
		 			<div class="dettagli-data-header">
			 			<div class="dettagli-left">
			 				<h1 id="di-title"><?php echo $first_project->post_title; ?></h1>
			 				<div id="di-excerpt" class="dettagli-description">
			 					<?php echo $first_project->post_excerpt; ?>
			 				</div>
			 			</div>
			 			<div class="dettagli-right">
			 				<ul class="dettagli-meta">
			 					<li class="dettagli-client" id="di-client"><?php echo $first_client; ?></li>
			 					<li class="dettagli-link" id="di-link"><a href="<?php echo $first_url; ?>" target="_blank"><?php echo $first_url; ?></a></li>
			 					<li class="dettagli-date" id="di-date"><?php echo $first_date ?></li>
			 					<li class="dettagli-type" id="di-type"><?php echo $cout ?></li>
			 				</ul>
			 			</div>
			 		</div>
			 		<div class="dettagli-image" id="di-image">
			 			<?php echo get_the_post_thumbnail( $first_project->ID); ?>
			 		</div>
			 	</div>
	 		</div>
	 	</div>
	 	<div class="tags-wrapper">
	 		<h2><?php echo $grid_title ?></h2>
	 		<div class="tags-sort">
	 			<ul class="tags-filter">
					<li class="current" data-id="allitems"><a href="#">All</a></li>
					<?php foreach ($tags as $tag): ?>
						<li data-id="<?php echo $tag->slug ?>"><a href="#"><?php echo $tag->name ?></a></li>
					<?php endforeach ?>
				</ul>
	 		<div class="clear"></div>
	 		</div>
	 		<div class="dettagli-items">
	 			<?php 
	 				$first = true;
	 				foreach ($projects as $post) : 
	 					setup_postdata($post);
	 					$current = "";
	 					$oset  = array('post_id' => $post->ID);
						$cats = wp_get_post_terms($post->ID, $this->tax_id);
						$cout = "";
						
						foreach ($cats as $cat) {
							$cout .= $cat->slug." ";
						}

						if($first){
							$current = "current";
							$first = false;
						}

	 			?>
		 			<div class="dettagli-item <?php echo $cout; echo $current?>" data-id="<?php echo $post->ID ?>" data-index="<?php echo $indexCount++; ?>">
		 				<span class="dettagli-item-selector"></span>
		 				<?php echo get_the_post_thumbnail( $post->ID, 'dettagli-mini', array('class' => 'dettagli-thumb') ); ?>
		 				<div class="dettagli-text-holder">
							<div class="dettagli-text">
								<h3><?php echo $post->post_title?></h3>
								<!-- <p><?php echo $post->post_excerpt ?></p> -->
							</div>
						</div>
						<span class="dettagli-more"></span>
		 			</div>
	 			<?php endforeach; ?>
	 		</div>
	 	</div>
 	<?php 
 	}
	
	/** 
	 * For template code that should show before the standard section markup
	 */
	function before_section_template( $clone_id = null ){}

	/** 
	 * For template code that should show after the standard section markup
	 */
	function after_section_template( $clone_id = null ){}

	/**
	 *
	 * Using PageLines Options
	 * -----------------------------------------------------------
	 * PageLines options revolve around the ploption function. 
	 * To use this function you provide two arguments as follows.
	 * 
	 * 	Usage: ploption( 'key', $args );
	 * 		'key' - The key for the PageLines option 
	 *  	$args - An array of variables for handling the option: 
	 * 
	 *			-  $args() list (optional): 
	 * 				- 	'post_id'	- The global post or page id (required for page by page meta control)
	 *				- 	'clone_id'	- The clone idea for the section (required to enable cloning)
	 * 				Advanced
	 *					- 	'setting'	- The WP setting field to pull the option from. 
	 * 					- 	'subkey'	- For nested options
	 * 
	 * 
	 */
	
	/**
	 *
	 * Section Page Options
	 * 
	 * Section optionator is designed to handle section options.
	 */
	function section_optionator( $settings ){
		
		// Compare w/ Optionator defaults. (Required, but doesn't change -- needed for cloning/special)
		$settings = wp_parse_args($settings, $this->optionator_default);
		
		/**
		 *
		 * Section Page Options
		 * 
		 * Section optionator is designed to handle section cloning.
		 */
		$opt_array = array(
			'tm_di_items' => array(
				'type' 			=> 'count_select',
				'inputlabel'	=> __('Number of post to show', $this->domain),
				'title' 		=> __('Number of post', $this->domain),
				'shortexp'		=> __('Default value is 12', $this->domain),
				'exp'			=> __('The amount of post to show.', $this->domain),
				'count_start'	=> 4, 
 				'count_number'	=> 40,
			),
			'tm_di' => array(
				'title'			=> 'Top Bar Color',
				'layout'		=> 'full',
				'type'         	=> 'color_multi',
				'shortexp'		=> __('Configure the color to use.', $this->domain),
				'selectvalues'	=> array(
					'tm_di_top_bar_color'	=> array(				
						'inputlabel' 	=> __( 'Top Bar (#000000)', $this->domain ),
					),
				),
			),

			'tm_di_1' => array(
				'title'			=> 'Project Description',
				'layout'		=> 'full',
				'type'         	=> 'color_multi',
				'shortexp'		=> __('Configure the colors to use in the description area.', $this->domain),
				'selectvalues'	=> array(
					'tm_di_de_back_color'	=> array(				
						'inputlabel' 	=> __( 'Background (#E0E0E0)', $this->domain ),
					),
					'tm_di_de_title_color'	=> array(				
						'inputlabel' 	=> __( 'Title Text (#414141)', $this->domain ),
					),
					'tm_di_de_excerpt_color'	=> array(				
						'inputlabel' 	=> __( 'Excerpt Text (#7A797C)', $this->domain ),
					),
				),
			),

			'tm_di_2' => array(
				'title'			=> 'Project Details',
				'layout'		=> 'full',
				'type'         	=> 'color_multi',
				'shortexp'		=> __('Configure the colors to use in the details area.', $this->domain),
				'selectvalues'	=> array(
					'tm_di_dt_back_color'	=> array(				
						'inputlabel' 	=> __( 'Background (Default: #F26E0E)', $this->domain ),
					),
					'tm_di_dt_text_color'	=> array(				
						'inputlabel' 	=> __( 'Text (#FFFFFF)', $this->domain ),
					)
				),
			),

			'tm_di_3' => array(
				'title'			=> 'Navigation Arrows',
				'layout'		=> 'full',
				'type'         	=> 'color_multi',
				'shortexp'		=> __('Configure the colors to use in the Navigation Arrows.', $this->domain),
				'selectvalues'	=> array(
					'tm_di_arrow_color'	=> array(				
						'inputlabel' 	=> __( 'Normal State (#A6A89C)', $this->domain ),
					),
					'tm_di_arrow_over_color'	=> array(				
						'inputlabel' 	=> __( 'Over State (#CAC7B4)', $this->domain ),
					)
				),
			),

			'tm_di_grid_title' 	=> array(
				'type'			=> 'text',
				'inputlabel'	=> __('Title', $this->domain),
				'title' 		=> __('Grid Title', $this->domain),
				'shortexp'		=> __('Default: "Latest Works"', $this->domain),
				'exp'			=> __('This title is used to indicate the separation between the project and the grid navigation.', $this->domain),
			),

			'tm_di_4' => array(
				'title'			=> 'Grid Colors',
				'layout'		=> 'full',
				'type'         	=> 'color_multi',
				'shortexp'		=> __('Configure the colors to use.', $this->domain),
				'selectvalues'	=> array(
					'tm_di_grid_title_color'	=> array(				
						'inputlabel' 	=> __( 'Title (#818181)', $this->domain ),
					),
					'tm_di_grid_menu_color'	=> array(				
						'inputlabel' 	=> __( 'Links (#7A7A7A)', $this->domain ),
					),
					
					'tm_di_grid_menu_color_over'	=> array(				
						'inputlabel' 	=> __( 'Links Over (#FFFFFF)', $this->domain ),
					),

					'tm_di_grid_menu_indicator'	=> array(				
						'inputlabel' 	=> __( 'Indicator (#F26E0E )', $this->domain ),
					),
				),
			),

			'tm_di_5' => array(
				'title'			=> 'Items Box Color',
				'layout'		=> 'full',
				'type'         	=> 'color_multi',
				'shortexp'		=> __('Configure the colors to use.', $this->domain),
				'selectvalues'	=> array(
					'tm_di_item_back'	=> array(				
						'inputlabel' 	=> __( 'Background (#3A3A3A)', $this->domain ),
					),
					'tm_di_item_title'	=> array(				
						'inputlabel' 	=> __( 'Title (#FFFFFF)', $this->domain ),
					),
					/*'tm_di_item_excert'	=> array(				
						'inputlabel' 	=> __( 'Excerpt (#FFFFFF)', $this->domain ),
					),*/
					
				),
			),
		);

		$settings = array(
			'id' 		=> $this->id.'_meta',
			'name' 		=> $this->name,
			'icon' 		=> $this->icon, 
			'clone_id'	=> $settings['clone_id'], 
			'active'	=> $settings['active']
		);

		register_metatab($settings, $opt_array);
	}

	/**
	 * 
	 * Create the Post Meta fields
	 * 
	 */

	function post_meta_setup(){
		$pt_tab_options = array(
			'tm_dettagli' => array(
				'shortexp'     => __( 'Please enter the details for this project.', $this->domain),
				'title'        => __( 'Project Details', $this->domain),
				'inputlabel'   => __( '', $this->domain),
				'type'         => 'text_multi',
				'exp'          => 'The section adjust the size of the image to fit the width but I recommend a maximum width of 1100px.',
				'selectvalues' => array(
					'tm_dettagli_client' => array('inputlabel'=> __( 'Client Name', $this->domain )),
					'tm_dettagli_date'   => array('inputlabel'=> __( 'Project Date', $this->domain )),
					'tm_dettagli_link'   => array('inputlabel'=> __( 'Final Project URL', $this->domain )),
				)
			),
		);

		$pt_panel = array(
				'id' 		=> 'tm_dettagli',
				'name' 		=> __('Project Details',$this->domain),
				'posttype' 	=> array( $this->custom_post_id ),
				'hide_tabs'	=> false
			);

		$pt_panel =  new PageLinesMetaPanel( $pt_panel );


		$pt_tab = array(
			'id' 		=> 'tm_dettagli_metatab',
			'name' 		=> __("Project", $this->domain) ,
			'icon' 		=> $this->icon,
		);

		$pt_panel->register_tab( $pt_tab, $pt_tab_options );
	}

	/**
	 * 
	 * Create the custom post for store the "projects" 
	 */

	function setup_custom_post(){
		$args = array(
			'label'              => 'Dettagli Projects',
			'singular_label'     => 'Project',
			'description'        => '',
			'taxonomies'         => array( $this->tax_id ),
			'menu_icon'          => $this->icon,
			'supports'           => array('title', 'thumbnail', 'excerpt'),
		);

		$taxonomies = array(
			$this->tax_id => array(
				"label"          => 'Categories',
				"singular_label" => 'Category',
			)
		);

		$columns = array(
			"cb"            => "<input type=\"checkbox\" />",
			"title"         => "Title",
			'client'       => 'Client',
			$this->tax_id   => "Category",
		);

		new PageLinesPostType( $this->custom_post_id, $args, $taxonomies, $columns, array($this, 'column_display'));
	}

	/**
	 *
	 * Populate the columns in the admin view 
	 * 
	 */

	function column_display($column)
	{
		global $post;
		switch ($column){
			case $this->tax_id:
				echo get_the_term_list($post->ID, $this->tax_id, '', ', ','');
				break;
			case 'client':
				echo m_pagelines('tm_dettagli_client', $post->ID);
				break;
		}
	}

	/**
	 * 
	 * Get the Custom posts
	 * 
	 */
	function get_posts( $set = null, $limit = null){
		$query                 = array();
		$query['post_type']    = $this->custom_post_id;
		$query[ $this->tax_id ] = $set;

		if(isset($limit)){
			$query['showposts'] = $limit;
		}
		
		$q = new WP_Query($query);

		if(is_array($q->posts))
			return $q->posts;
		else
			return array();
	}

	function get_project()
	{
		$action  = $_POST['action'];
		$project = $_POST['project'];
		$oset  = array('post_id' => $project);
		if( $action == 'load_project' ){
			$out = array();
			$categories = wp_get_post_terms($project, $this->tax_id);
			$cout = "";
			foreach ($categories as $cat) {
				$cout .= $cat->name.", ";
			}
			$cout = substr($cout, 0, -2);
			$post = get_post($project);
			$out['title'] = $post->post_title;
			$out['excerpt'] = $post->post_excerpt;
			$out['client_name'] = plmeta('tm_dettagli_client', $oset);
			$out['project_url'] = plmeta('tm_dettagli_link', $oset);
			$out['date'] = plmeta('tm_dettagli_date', $oset);;
			$out['category'] = $cout;
			$out['image'] = get_the_post_thumbnail( $project);
			echo json_encode( $out );
			
		}
		die();
	}


} /* End of section class - No closing php tag needed */