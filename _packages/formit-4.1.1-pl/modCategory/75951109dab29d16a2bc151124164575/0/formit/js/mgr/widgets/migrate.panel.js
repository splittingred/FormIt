FormIt.panel.Migrate = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,id: 'formit-migrate-panel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('formit')+' - '+_('formit.migrate')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-panel'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,cls: 'x-tab-panel-bwrap main-wrapper'
            ,items: [{
                html: '<p>'+_('formit.migrate_desc')+'</p>'
                ,border: false
            }]
        },{
            xtype: 'modx-panel'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,activeItem: 0
            ,hideMode: 'offsets'
            ,cls: 'x-tab-panel-bwrap main-wrapper'
            ,items: [{
                html: '<h2>'+_('formit.migrate_status')+'</h2>'
                ,border: false
            },{
                id: 'formit-migrate-panel-status'
                ,html: ''
                ,border: false
            }]
        }]
        ,listeners: {
            'render': {fn: this.migrateRedirects, scope:this }
        }
    });
    FormIt.panel.Migrate.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.panel.Migrate,MODx.Panel,{
    migrateRedirects: function(){
        MODx.Ajax.request({
            url: FormIt.config.connectorUrl
            ,params: {
                action: 'mgr/form/migrate'
            }
            ,listeners: {
                'success':{fn:function(r) {
                    if(r.total) {
                        var message;
                        if(r.total == 0) {
                            // No redirects found in resource properties, success!
                            message = '<p>'+_('formit.migrate_success_msg')+'</p>';
                            MODx.msg.alert(_('formit.migrate_success'), _('formit.migrate_success_msg'), function() {
                                location.href = MODx.config.manager_url + '?a=home&namespace=' + MODx.request.namespace;
                            });
                        } else {
                            // Processing redirects
                            message = '<p>'+_('formit.migrate_running')+'</p>';
                            Ext.getCmp('formit-migrate-panel').fireEvent('render');
                        }
                        Ext.getCmp('formit-migrate-panel-status').update(message);
                    }
                },scope:this}
                ,'failure':{fn:function(r) {
                    // MODx.msg.alert('error');
                }, scope:this}
            }
        });
    }
});
Ext.reg('formit-panel-migrate',FormIt.panel.Migrate);
