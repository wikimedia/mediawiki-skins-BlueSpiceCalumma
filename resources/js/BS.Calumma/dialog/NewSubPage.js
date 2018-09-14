Ext.define( 'BS.Calumma.dialog.NewSubPage', {
	extend: 'MWExt.Dialog',

	makeItems: function () {
		this.setTitle( mw.message( 'bs-action-new-subpage-title', this.basePage ).text() );
		this.cbPageName = Ext.create( 'Ext.form.field.ComboBox', {
			fieldLabel: mw.message( 'bs-action-new-subpage-text' ).plain(),
			valueField: 'text',
			queryMode: 'local',
			store: new Ext.data.JsonStore( {
				proxy: {
					type: 'ajax',
					url: mw.util.wikiScript( 'api' ),
					extraParams: {
						format: 'json',
						action: 'bs-wikisubpage-treestore',
						node: this.basePage
					},
					reader: {
						type: 'json',
						rootProperty: 'children',
						idProperty: 'id',
						totalProperty: 'total'
					}
				},
				autoLoadOnValue: true,
				fields: ['text', 'id']
			} )
		} );

		return [
			this.cbPageName
		];
	},

	getData: function () {
		return this.cbPageName.getValue();
	}
} );
