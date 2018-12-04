Ext.onReady(function() {
    MODx.load({ xtype: 'formit-page-migrate'});
});

FormIt.page.Migrate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'formit-panel-migrate'
            ,renderTo: 'formit-panel-migrate-div'
        }]
    });
    FormIt.page.Migrate.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.page.Migrate,MODx.Component);
Ext.reg('formit-page-migrate',FormIt.page.Migrate);