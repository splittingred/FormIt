Ext.onReady(function() {
    MODx.load({ xtype: 'formit-page-home'});
});

FormIt.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'formit-panel-home'
            ,renderTo: 'formit-panel-home-div'
        }]
    });
    FormIt.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.page.Home,MODx.Component);
Ext.reg('formit-page-home',FormIt.page.Home);