Ext.define( 'BS.Calumma.dialog.NewPage', {
	extend: 'MWExt.Dialog',
	requires: [ 'BS.form.field.TitleCombo' ],
	title: mw.message( 'bs-action-new-page-title' ).plain(),

	namespace: '',

	makeItems: function() {
		this.cbPageName = Ext.create( 'BS.form.field.TitleCombo', {
			fieldLabel: mw.message('bs-action-new-page-text').plain(),
			value: this.namespace
		});
		return [
			this.cbPageName
		];
	},

	getData: function () {
		return this.cbPageName.getValue();
	}
} );
