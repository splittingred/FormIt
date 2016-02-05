
FormIt.grid.FormsEncryption = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formit-grid-forms-encryption'
        ,url: FormIt.config.connectorUrl
        ,baseParams: {
            action: 'mgr/form/getlistsingle'
        }
        ,fields: ['form', 'encrypted', 'total']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('formit.form')
            ,dataIndex: 'form'
        },{
            header: _('formit.encrypted')
            ,dataIndex: 'encrypted'
            ,width: 250
        },{
            header: _('formit.total')
            ,dataIndex: 'total'
            ,width: 250
        }]
    });
    FormIt.grid.FormsEncryption.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.grid.FormsEncryption,MODx.grid.Grid,{
    windows: {}
    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('formit.form_encryptall')
            ,handler: this.encryptAll
        });
        m.push('-');
        m.push({
            text: _('formit.form_decryptall')
            ,handler: this.decryptAll
        });
        this.addContextMenuItem(m);
    }
    ,encryptAll: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('formit.form_encrypt')
            ,text: _('formit.form_encrypt_confirm')
            ,url: FormIt.config.connectorUrl
            ,params: {
                action: 'mgr/form/encrypt'
                ,form: this.menu.record.form
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
    ,decryptAll: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('formit.form_decrypt')
            ,text: _('formit.form_decrypt_confirm')
            ,url: FormIt.config.connectorUrl
            ,params: {
                action: 'mgr/form/decrypt'
                ,form: this.menu.record.form
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }
});
Ext.reg('formit-grid-forms-encryption',FormIt.grid.FormsEncryption);
