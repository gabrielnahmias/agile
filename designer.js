/*
 * File: designer.js
 * Date: Thu Nov 24 2011 09:03:50 GMT-0600 (Central Standard Time)
 *
 * This file was generated by Ext Designer version 1.2.2.
 * http://www.sencha.com/products/designer/
 *
 * This file will be auto-generated each and everytime you export.
 *
 * Do NOT hand edit this file.
 */

Ext.Loader.setConfig({
	enabled: true
});

Ext.application({
	name: 'Agile',

	launch: function() {
		Ext.QuickTips.init();

		var cmp1 = Ext.create('Agile.view.Container', {
			renderTo: Ext.getBody()
		});
		cmp1.show();
	}
});
