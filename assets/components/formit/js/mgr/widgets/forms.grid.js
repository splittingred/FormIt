
FormIt.grid.Forms = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formit-grid-forms'
        ,url: FormIt.config.connectorUrl
        ,baseParams: {
            action: 'mgr/form/getlist'
        }
        ,fields: ['id','form','values', 'ip', 'date', 'hash']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: true
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
        },{
            header: _('formit.form')
            ,dataIndex: 'form'
        },{
            header: _('formit.values')
            ,dataIndex: 'values'
            ,width: 250
            ,renderer: function(value){
                value = JSON.parse(value);
                var output = '';
                for(var k in value){
                    output += '<b>'+k+'</b>: '+value[k]+'\n';
                }
                return output;
            }
        },{
            header: _('formit.date')
            ,dataIndex: 'date'
            ,width: 250
            ,renderer: function(value) {
                var formDate = Date.parseDate(value, 'U');
                return formDate.format('Y/m/d H:i');
            }
        },{
            header: _('formit.hash')
            ,dataIndex: 'hash'
        }]
    });
    FormIt.grid.Forms.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.grid.Forms,MODx.grid.Grid,{
    windows: {}
    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('formit.form_view')
            ,handler: this.viewItem
        });
        m.push('-');
        m.push({
            text: _('formit.form_remove')
            ,handler: this.remove
        });
        this.addContextMenuItem(m);
    }
    ,viewItem: function(btn,e) {
        if (!this.menu.record) {
            return false;
        }
        var values = JSON.parse(this.menu.record.values);
        var fieldsOutput = '';
        for(var k in values){
            fieldsOutput += '<b>'+k+'</b>: '+values[k]+'<br/>';
        }

        var formDate = Date.parseDate(this.menu.record.date, 'U');

        var win = new Ext.Window({
            title: _('formit.values'),
            modal: true,
            width: 640,
            height: 400,
            preventBodyReset: true,
            html: '<p><b>'+_('formit.date')+':</b> '+formDate.format('Y/m/d H:i')+'<br/><b>'+_('formit.ip')+':</b> '+this.menu.record.ip+'</p><hr/>'+fieldsOutput
        });
        win.show();
    }
    ,remove: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('formit.form_remove')
            ,text: _('formit.form_remove_confirm')
            ,url: FormIt.config.connectorUrl
            ,params: {
                action: 'mgr/form/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });
    }

    ,search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }

    ,export: function(btn,e) {
        var _params = {
                action: 'mgr/form/export'
                ,form: Ext.getCmp('form').getValue()
                ,context_key: Ext.getCmp('context').getValue()
                ,startDate: Ext.util.Format.date(Ext.getCmp('startdate').getValue(), 'Y-m-d')
                ,endDate: Ext.util.Format.date(Ext.getCmp('enddate').getValue(), 'Y-m-d')
                ,download: false
                ,limit: 0
                ,HTTP_MODAUTH: MODx.siteId
            }
            ,_link = FormIt.config.connectorUrl+'?'+Ext.urlEncode(_params);
 
        var win = window.open(_link, '_blank');
        win.focus();
    }

});
Ext.reg('formit-grid-forms',FormIt.grid.Forms);
