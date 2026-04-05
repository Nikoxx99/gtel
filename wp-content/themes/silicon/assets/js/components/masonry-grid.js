/**
* Cascading (Masonry) grid layout
* 
* @requires https://github.com/desandro/imagesloaded
* @requires https://github.com/Vestride/Shuffle
*/

const masonryGrid = (() => {

	let grid = document.querySelectorAll('.masonry-grid'), masonry;

	if (grid === null) return;

	for (let i = 0; i < grid.length; i++) {
		masonry = new Shuffle(grid[i], {
			itemSelector: '.masonry-grid-item',
			sizer: '.masonry-grid-item'
		});

		imagesLoaded(grid[i]).on('progress', () => {
			masonry.layout();
		});

		grid[i].addEventListener( 'si-posts-appended', function(e){
			// Save the total number of new items returned from the API.
			var itemsFromResponse = e.detail.posts.length;
			// Get an array of elements that were just added to the grid above.
			var allItemsInGrid = Array.from(grid[i].children);
			// Use negative beginning index to extract items from the end of the array.
			var newItems = allItemsInGrid.slice(-itemsFromResponse);

			// Notify the shuffle instance that new items were added.
			masonry.add(newItems);
		} );

		// Filtering
		let filtersWrap = grid[i].closest('.masonry-filterable');
		if (filtersWrap === null) return;
		let filters = filtersWrap.querySelectorAll('.masonry-filters [data-group]');

		for (let n = 0; n < filters.length; n++) {
			filters[n].addEventListener('click', function(e) {
				let current = filtersWrap.querySelector('.masonry-filters .active'),
				target = this.dataset.group;
				if(current !== null) {
					current.classList.remove('active');
				}
				this.classList.add('active');
				masonry.filter(target);
				e.preventDefault();
			});
		}
	}

})();

export default masonryGrid;
