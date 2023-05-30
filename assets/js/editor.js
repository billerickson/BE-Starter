wp.domReady( () => {

	wp.blocks.unregisterBlockType( 'core/archives' );
	wp.blocks.unregisterBlockType( 'core/avatar' );
	wp.blocks.unregisterBlockType( 'core/calendar' );
	wp.blocks.unregisterBlockType( 'core/comment-author-name' );
	wp.blocks.unregisterBlockType( 'core/comment-content' );
	wp.blocks.unregisterBlockType( 'core/comment-date' );
	wp.blocks.unregisterBlockType( 'core/comment-edit-link' );
	wp.blocks.unregisterBlockType( 'core/comment-reply-link' );
	wp.blocks.unregisterBlockType( 'core/comment-template' );
	wp.blocks.unregisterBlockType( 'core/comments' );
	wp.blocks.unregisterBlockType( 'core/comments-pagination' );
	wp.blocks.unregisterBlockType( 'core/comments-pagination-next' );
	wp.blocks.unregisterBlockType( 'core/comments-pagination-numbers' );
	wp.blocks.unregisterBlockType( 'core/comments-pagination-previous' );
	wp.blocks.unregisterBlockType( 'core/comments-query-loop' );
	wp.blocks.unregisterBlockType( 'core/comments-title' );
	wp.blocks.unregisterBlockType( 'core/home-link' );
	wp.blocks.unregisterBlockType( 'core/latest-comments' );
	wp.blocks.unregisterBlockType( 'core/latest-posts' );
	wp.blocks.unregisterBlockType( 'core/legacy-widget' );
	wp.blocks.unregisterBlockType( 'core/loginout' );
	wp.blocks.unregisterBlockType( 'core/media-text' );
	wp.blocks.unregisterBlockType( 'core/navigation' );
	wp.blocks.unregisterBlockType( 'core/navigation-link' );
	wp.blocks.unregisterBlockType( 'core/navigation-submenu' );
	wp.blocks.unregisterBlockType( 'core/post-author' );
	wp.blocks.unregisterBlockType( 'core/post-author-name' );
	wp.blocks.unregisterBlockType( 'core/post-author-biography' );
	wp.blocks.unregisterBlockType( 'core/post-comments' );
	wp.blocks.unregisterBlockType( 'core/post-comments-form' );
	wp.blocks.unregisterBlockType( 'core/post-content' );
	wp.blocks.unregisterBlockType( 'core/post-date' );
	wp.blocks.unregisterBlockType( 'core/post-excerpt' );
	wp.blocks.unregisterBlockType( 'core/post-featured-image' );
	wp.blocks.unregisterBlockType( 'core/post-navigation-link' );
	wp.blocks.unregisterBlockType( 'core/post-template' );
	wp.blocks.unregisterBlockType( 'core/post-terms' );
	wp.blocks.unregisterBlockType( 'core/post-title' );
	wp.blocks.unregisterBlockType( 'core/query' );
	wp.blocks.unregisterBlockType( 'core/query-no-results' );
	wp.blocks.unregisterBlockType( 'core/query-pagination' );
	wp.blocks.unregisterBlockType( 'core/query-pagination-next' );
	wp.blocks.unregisterBlockType( 'core/query-pagination-numbers' );
	wp.blocks.unregisterBlockType( 'core/query-pagination-previous' );
	wp.blocks.unregisterBlockType( 'core/query-title' );
	wp.blocks.unregisterBlockType( 'core/read-more' );
	wp.blocks.unregisterBlockType( 'core/site-logo' );
	wp.blocks.unregisterBlockType( 'core/site-tagline' );
	wp.blocks.unregisterBlockType( 'core/site-title' );
	wp.blocks.unregisterBlockType( 'core/social-link' );
	wp.blocks.unregisterBlockType( 'core/social-links' );
	wp.blocks.unregisterBlockType( 'core/tag-cloud' );
	wp.blocks.unregisterBlockType( 'core/term-description' );


	wp.blocks.unregisterBlockStyle(
		'core/button',
		[ 'squared', 'fill' ]
	);


	wp.blocks.unregisterBlockStyle(
		'core/separator',
		[ 'default', 'wide', 'dots' ]
	);

	wp.blocks.unregisterBlockStyle(
		'core/quote',
		[ 'default', 'large', 'plain' ]
	);

} );
