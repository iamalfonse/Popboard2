/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'basicstyles', groups: [ 'basicstyles' ] },
		// { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links', groups: ['link', 'unlink'] },
		{ name: 'insert', groups: [ 'image' ] },
		// { name: 'forms' },
		// { name: 'tools' },
		// { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		// '/',
		{ name: 'paragraph',   groups: [ 'list', 'indent',  'align', 'bidi' ] },
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ]  }
		// { name: 'styles' },
		// { name: 'colors' }
		// { name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript,PasteText,PasteFromWord,Table,Anchor,SpecialChar,Cut,Copy,Paste';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	//make it autogrow with content
	config.extraPlugins = 'autogrow';
	config.autoGrow_onStartup = true;
	config.autoGrow_maxHeight = 800;

	// Make dialogs simpler.
	config.removeDialogTabs = 'image:advanced;image:Link;link:advanced;link:Target;';

	//add resize plugin (found in js/plugins/resize)
	config.extraPlugins = 'resize';
	config.resize_maxHeight = 350;
};
