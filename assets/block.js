( function ( blocks, element, components, blockEditor, serverSideRender, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var TextControl = components.TextControl;
	var RangeControl = components.RangeControl;
	var ColorPalette = components.ColorPalette;
	var ServerSideRender = serverSideRender;

	function cssUnit( value ) {
		value = value || '';
		return String( value ).trim();
	}

	function colorControl( label, value, onChange ) {
		return el(
			'div',
			{ className: 'aaweb-site-credits-control' },
			el( 'p', {}, label ),
			el( ColorPalette, {
				value: value,
				onChange: function ( newValue ) {
					onChange( newValue || '' );
				}
			} )
		);
	}

	blocks.registerBlockType( 'aaweb/site-credits', {
		title: __( 'AAWEB Site Credits', 'aaweb-site-credits' ),
		icon: 'admin-site-alt3',
		category: 'widgets',
		description: __( 'Display professional website credits with the current year.', 'aaweb-site-credits' ),
		attributes: {
			align: { type: 'string', default: 'center' },
			className: { type: 'string', default: '' },
			textColor: { type: 'string', default: '' },
			linkColor: { type: 'string', default: '' },
			backgroundColor: { type: 'string', default: '' },
			borderColor: { type: 'string', default: '' },
			textHoverColor: { type: 'string', default: '' },
			linkHoverColor: { type: 'string', default: '' },
			backgroundHoverColor: { type: 'string', default: '' },
			borderHoverColor: { type: 'string', default: '' },
			fontSize: { type: 'number', default: 14 },
			fontWeight: { type: 'string', default: '' },
			textTransform: { type: 'string', default: '' },
			lineHeight: { type: 'number', default: 0 },
			letterSpacing: { type: 'number', default: 0 },
			paddingTop: { type: 'number', default: 0 },
			paddingRight: { type: 'number', default: 0 },
			paddingBottom: { type: 'number', default: 0 },
			paddingLeft: { type: 'number', default: 0 },
			marginTop: { type: 'number', default: 0 },
			marginBottom: { type: 'number', default: 0 },
			borderRadius: { type: 'number', default: 0 },
			borderWidth: { type: 'number', default: 0 },
			transitionDuration: { type: 'number', default: 200 },
			customCss: { type: 'string', default: '' }
		},
		edit: function ( props ) {
			var attrs = props.attributes;
			var set = props.setAttributes;

			return el(
				'div',
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __( 'Layout', 'aaweb-site-credits' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Alignment', 'aaweb-site-credits' ),
							value: attrs.align,
							options: [
								{ label: __( 'Left', 'aaweb-site-credits' ), value: 'left' },
								{ label: __( 'Center', 'aaweb-site-credits' ), value: 'center' },
								{ label: __( 'Right', 'aaweb-site-credits' ), value: 'right' }
							],
							onChange: function ( value ) {
								set( { align: value } );
							}
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Style: Normal', 'aaweb-site-credits' ), initialOpen: true },
						colorControl( __( 'Text color', 'aaweb-site-credits' ), attrs.textColor, function ( value ) { set( { textColor: value } ); } ),
						colorControl( __( 'Link color', 'aaweb-site-credits' ), attrs.linkColor, function ( value ) { set( { linkColor: value } ); } ),
						colorControl( __( 'Background color', 'aaweb-site-credits' ), attrs.backgroundColor, function ( value ) { set( { backgroundColor: value } ); } ),
						colorControl( __( 'Border color', 'aaweb-site-credits' ), attrs.borderColor, function ( value ) { set( { borderColor: value } ); } )
					),
					el(
						PanelBody,
						{ title: __( 'Style: Hover', 'aaweb-site-credits' ), initialOpen: false },
						colorControl( __( 'Text hover color', 'aaweb-site-credits' ), attrs.textHoverColor, function ( value ) { set( { textHoverColor: value } ); } ),
						colorControl( __( 'Link hover color', 'aaweb-site-credits' ), attrs.linkHoverColor, function ( value ) { set( { linkHoverColor: value } ); } ),
						colorControl( __( 'Background hover color', 'aaweb-site-credits' ), attrs.backgroundHoverColor, function ( value ) { set( { backgroundHoverColor: value } ); } ),
						colorControl( __( 'Border hover color', 'aaweb-site-credits' ), attrs.borderHoverColor, function ( value ) { set( { borderHoverColor: value } ); } ),
						el( RangeControl, {
							label: __( 'Transition duration', 'aaweb-site-credits' ),
							help: __( 'Duration in milliseconds.', 'aaweb-site-credits' ),
							value: attrs.transitionDuration,
							min: 0,
							max: 2000,
							step: 50,
							onChange: function ( value ) {
								set( { transitionDuration: value || 0 } );
							}
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Typography', 'aaweb-site-credits' ), initialOpen: false },
						el( RangeControl, {
							label: __( 'Font size', 'aaweb-site-credits' ),
							value: attrs.fontSize,
							min: 10,
							max: 60,
							onChange: function ( value ) {
								set( { fontSize: value || 14 } );
							}
						} ),
						el( SelectControl, {
							label: __( 'Font weight', 'aaweb-site-credits' ),
							value: attrs.fontWeight,
							options: [
								{ label: __( 'Theme default', 'aaweb-site-credits' ), value: '' },
								{ label: '300', value: '300' },
								{ label: '400', value: '400' },
								{ label: '500', value: '500' },
								{ label: '600', value: '600' },
								{ label: '700', value: '700' },
								{ label: '800', value: '800' }
							],
							onChange: function ( value ) {
								set( { fontWeight: value } );
							}
						} ),
						el( SelectControl, {
							label: __( 'Text transform', 'aaweb-site-credits' ),
							value: attrs.textTransform,
							options: [
								{ label: __( 'Theme default', 'aaweb-site-credits' ), value: '' },
								{ label: __( 'None', 'aaweb-site-credits' ), value: 'none' },
								{ label: __( 'Uppercase', 'aaweb-site-credits' ), value: 'uppercase' },
								{ label: __( 'Lowercase', 'aaweb-site-credits' ), value: 'lowercase' },
								{ label: __( 'Capitalize', 'aaweb-site-credits' ), value: 'capitalize' }
							],
							onChange: function ( value ) {
								set( { textTransform: value } );
							}
						} ),
						el( RangeControl, { label: __( 'Line height', 'aaweb-site-credits' ), value: attrs.lineHeight, min: 0, max: 3, step: 0.1, onChange: function ( value ) { set( { lineHeight: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Letter spacing', 'aaweb-site-credits' ), value: attrs.letterSpacing, min: -5, max: 20, step: 0.5, onChange: function ( value ) { set( { letterSpacing: value || 0 } ); } } )
					),
					el(
						PanelBody,
						{ title: __( 'Spacing', 'aaweb-site-credits' ), initialOpen: false },
						el( RangeControl, { label: __( 'Padding top', 'aaweb-site-credits' ), value: attrs.paddingTop, min: 0, max: 120, onChange: function ( value ) { set( { paddingTop: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Padding right', 'aaweb-site-credits' ), value: attrs.paddingRight, min: 0, max: 120, onChange: function ( value ) { set( { paddingRight: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Padding bottom', 'aaweb-site-credits' ), value: attrs.paddingBottom, min: 0, max: 120, onChange: function ( value ) { set( { paddingBottom: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Padding left', 'aaweb-site-credits' ), value: attrs.paddingLeft, min: 0, max: 120, onChange: function ( value ) { set( { paddingLeft: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Margin top', 'aaweb-site-credits' ), value: attrs.marginTop, min: 0, max: 120, onChange: function ( value ) { set( { marginTop: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Margin bottom', 'aaweb-site-credits' ), value: attrs.marginBottom, min: 0, max: 120, onChange: function ( value ) { set( { marginBottom: value || 0 } ); } } )
					),
					el(
						PanelBody,
						{ title: __( 'Border & Advanced', 'aaweb-site-credits' ), initialOpen: false },
						el( RangeControl, { label: __( 'Border width', 'aaweb-site-credits' ), value: attrs.borderWidth, min: 0, max: 20, onChange: function ( value ) { set( { borderWidth: value || 0 } ); } } ),
						el( RangeControl, { label: __( 'Border radius', 'aaweb-site-credits' ), value: attrs.borderRadius, min: 0, max: 80, onChange: function ( value ) { set( { borderRadius: value || 0 } ); } } ),
						el( TextControl, {
							label: __( 'Additional CSS properties', 'aaweb-site-credits' ),
							help: __( 'Optional advanced CSS properties, for example: box-shadow: 0 2px 10px rgba(0,0,0,.12);', 'aaweb-site-credits' ),
							value: cssUnit( attrs.customCss ),
							onChange: function ( value ) {
								set( { customCss: value } );
							}
						} )
					)
				),
				el( ServerSideRender, {
					block: 'aaweb/site-credits',
					attributes: attrs
				} )
			);
		},
		save: function () {
			return null;
		}
	} );
} )( window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.serverSideRender, window.wp.i18n );
