( function ( blocks, element, components, blockEditor, serverSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ServerSideRender = serverSideRender;

	blocks.registerBlockType( 'aaweb/site-credits', {
		title: __( 'AAWEB Site Credits', 'aaweb-site-credits' ),
		icon: 'admin-site-alt3',
		category: 'widgets',
		description: __( 'Display professional website credits with the current year.', 'aaweb-site-credits' ),
		attributes: {
			align: {
				type: 'string',
				default: 'center'
			},
			className: {
				type: 'string',
				default: ''
			}
		},
		edit: function ( props ) {
			return el(
				'div',
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'AAWEB Site Credits', 'aaweb-site-credits' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Alignment', 'aaweb-site-credits' ),
							value: props.attributes.align,
							options: [
								{ label: __( 'Left', 'aaweb-site-credits' ), value: 'left' },
								{ label: __( 'Center', 'aaweb-site-credits' ), value: 'center' },
								{ label: __( 'Right', 'aaweb-site-credits' ), value: 'right' }
							],
							onChange: function ( value ) {
								props.setAttributes( { align: value } );
							}
						} )
					)
				),
				el( ServerSideRender, {
					block: 'aaweb/site-credits',
					attributes: props.attributes
				} )
			);
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.serverSideRender, window.wp.i18n );
