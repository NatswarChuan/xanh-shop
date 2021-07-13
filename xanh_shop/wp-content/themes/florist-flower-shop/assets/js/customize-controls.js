( function( api ) {

	// Extends our custom "florist-flower-shop" section.
	api.sectionConstructor['florist-flower-shop'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );