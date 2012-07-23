jQuery(document).ready(function($){

	var $items
	,	$gridContainer
	,	currentTag
	,	$currentItem
	,	loading = false;

	$('.dettagli-item').on('mouseenter', showItemDetails);
	$('.dettagli-item').on('mouseleave', hideItemDetails);
	$('.dettagli-item').on('click', prepareUiToChange);
	$('.tags-filter li').on('click', makeItemsFilter);
	$('a.dettagli-next').on('click', handleNavigation);
	$('a.dettagli-prev').on('click', handleNavigation);


	if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) 
	{
        $(".dettagli-item").bind('touchstart', showItemDetails);
        $('.dettagli-item').bind('touchcancel', hideItemDetails);
        $('.dettagli-item').bind('touchend', prepareUiToChange);
    }
	
	$('.dettagli-image').slideUp('slow');
	$gridContainer = $('.dettagli-items');
	$items = $('.dettagli-item');
	$currentItem = $('.dettagli-item.current');

	currentIndex = 0;
	totalIndexes = $items.length;

	function handleNavigation(e){
		if( loading ){return}
		e.preventDefault();
		$this = $(this);
		if( $this.hasClass('dettagli-next') ) {
			var goTo = ( ( currentIndex + 1) < totalIndexes ) ? ++currentIndex : 0;
			moveToIndex( goTo );	
			return;
		}

		if( $this.hasClass( 'dettagli-prev' ) ){
			var goTo = ( ( currentIndex ) > 0  ) ? --currentIndex : totalIndexes-1;
			moveToIndex( goTo );	
			return;
		}

	}

	function moveToIndex(index){
		var $newItem = $( $items[index] );
		$currentItem.removeClass('current');
		$newItem.addClass('current');
		deselectItem( $currentItem, false );
		deselectItem( $newItem, true );
		currentIndex = index;
		$currentItem = $newItem
		doUiChange($newItem.data('id'), false)
	}

	function prepareUiToChange(e){
		e.preventDefault();
		$this = $(this);
		var id = $this.data('id');
		currentIndex = $this.data('index');
		if( $currentItem.data('id') == id ){return;}
		doUiChange(id, true)
		$currentItem.removeClass('current');
		$currentItem = $this;
		deselectItem( $this, true );
	}

	function doUiChange(id, scroll){
		if(scroll){
			$('.dettagli-thumb', $currentItem).animate({'opacity': '.5'}, '200', function(){
				 	$('html,body').animate({scrollTop: $("#dettagli-nav").offset().top},'slow', function(){
				 		$('.dettagli-image').slideUp(750);
						loadNewItem( id );	 		
				 	})
				}
			);
		}else{
			$('.dettagli-image').slideUp(750);
			loadNewItem( id );	 		
		}
		
	}


	function loadNewItem(id){
		if( !loading ){
			loading = true;
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: adminUrl+'/wp-admin/admin-ajax.php',
				data: {
					'action' : 'load_project',
					'project' : id
				},
				success: function(data) {
					loading = false;
					
					
					$('#di-title').fadeOut('slow', function(){
						$(this).html(data.title).fadeIn('slow');	
					});
					$('#di-excerpt').fadeOut('slow', function(){
						$(this).html(data.excerpt).fadeIn('slow');	
					});
					$('#di-client').fadeOut('slow', function(){
						$(this).html(data.client_name).fadeIn('slow');	
					});
					$('#di-type').fadeOut('slow', function(){
						$(this).html(data.category).fadeIn('slow');
					});
					$('#di-date').fadeOut('slow', function(){
						$(this).html(data.date).fadeIn('slow');
					});
					$('#di-link').fadeOut('slow', function(){
						$(this).find('a').html(data.project_url).attr('href', data.project_url);
						$('.dettagli-image').html( data.image );
						$(this).fadeIn('slow', function(){
							$('.dettagli-image').slideDown(1000);
						});	
					});
					
				}
			});
		}
	}

	function makeItemsFilter(e){
		e.preventDefault();
		
		var $this  = $(this);
		var filter = '.'+$this.data('id');
		filter     = ( filter != '.allitems' ) ? filter : '.dettagli-item';
		
		if( currentTag == filter  ){return;}

		currentTag = filter;
		doFiltering( filter );
		$('.tags-filter li').removeClass('current');
		$this.addClass('current');
	}

	function doFiltering(filter){
		$gridContainer.isotope({ 
			filter: filter,
			animationEngine:'jquery',
			containerStyle: {position: 'relative', overflow: 'visible' },
			animationOptions: {
				duration: 500,
				easing: 'swing',
				queue: false
		   }
		});		
	}

	function showItemDetails(e){
		e.preventDefault();
		$this = $(this);
		
		if( $this.hasClass('current') ){return;}


		$('.dettagli-thumb', $this).stop().addClass('shadow').animate({
			'opacity': '1',
			'margin-top' : '-145'
		}, '200');

		$('.dettagli-item-selector', $this).hide();

		$('.dettagli-text-holder', $this).stop().animate({
			'height': '150'
		}, 'fast');

		$('.dettagli-text', $this).stop().animate({
			'opacity': '1',
			'top': '-10'
		}, { duration: "slow", easing: "easeOutQuad" });

		$('.dettagli-more', $this).stop().animate({
			'opacity': '1',
			'bottom': '125'
		}, { duration: "fast", easing: "easeInQuad" });

		
	}

	function hideItemDetails(e){
		e.preventDefault();
		deselectItem( $(this), false );
	}

	function deselectItem(target, activate){
		$this = target;

		if( $this.hasClass('current') && !activate ){return;}
		
		opacity = (activate) ? '1' : '.5';

		$('.dettagli-thumb', $this).stop().removeClass('shadow').animate({
			'opacity': opacity,
			'margin-top' : '0'
			}, '200', 	function(){
							if(activate){
								$this.addClass('current');
							}
						}
		);

		$('.dettagli-item-selector', $this).show();

		$('.dettagli-text-holder', $this).stop().animate({
			'height': '0'
		}, 'fast');

		$('.dettagli-text', $this).stop().animate({
			'opacity': '0',
			'top': '-200'
		}, 'fast');

		$('.dettagli-more', $this).stop().animate(
			{
				'opacity': '0',
				'bottom': '-50'
			},
			{ 
				duration: "slow", 
				easing: "easeInBack" 
			}
		);
	}

	doFiltering('.dettagli-item');
	$('.dettagli-image').slideDown('slow');
})