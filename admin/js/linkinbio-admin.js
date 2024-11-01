(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

/** Vue to the max! **/
var app = new Vue({
	el: '#app',
	data () {
		return {
			enabled_instagram_posts: 'Geen gevonden',

			loading_links: true,
			error_links: false,
			instagram_links: [],

			loading_posts: true,
			error_posts: false,
			instagram_posts: [],

			updating: false,
			drag: false,

			description: null,

			username: null

		}
	},
	methods: {
		add: function(id){

			this.instagram_links.push( this.instagram_posts[ id ] );

			this.update();
		},
		remove: function(id){

			this.instagram_links.splice(id, 1);

			this.update();

		},
		update: function(){

			this.updating = true;

			axios.
				post('/wp-json/linkinbio/v1/links', {
					links: this.instagram_links,
					username: this.username,
					description: this.description
				}).then(response => {
					this.updating = false;
					this.getInstagramPosts();
				}).catch(error => {
					console.log( error );
				});
			// do nothing for now
		},
		getInstagramPosts: function(){
			axios
				.get('/wp-json/linkinbio/v1/insta/')
				.then(response => {
					this.instagram_posts = response.data.instagram_posts
					this.username = response.data.username
					this.description = response.data.description
					this.loading_posts = false
				})
				.catch(error => {
					console.log(error)
					this.error_posts = true
				})
		},
		updateAndRetrievePosts: function(){
			this.update();
		}
	},
	mounted () {
		this.getInstagramPosts();

		axios
			.get('/wp-json/linkinbio/v1/links')
			.then(response => {
				this.instagram_links = response.data
				this.loading_links = false
			})
			.catch(error => {
				this.error_links = true
			})
	}
})
