
FormIt.grid.Forms = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'formit-grid-forms'
        ,url: FormIt.config.connectorUrl
        ,baseParams: {
            action: 'mgr/form/getlist'
        }
        ,fields: ['id','form','values', 'ip', 'date']
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
        if (!this.menu.record) return false;
        var fieldsOutput = '';
        for(var k in this.menu.record.values){
            fieldsOutput += '<b>'+k+'</b>: '+this.menu.record.values[k]+'<br/>';
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
        MODx.Ajax.request({
            url: FormIt.config.connectorUrl
            ,params: {
                action: 'mgr/form/export'
                ,form: Ext.getCmp('form').getValue()
                ,context_key: Ext.getCmp('context').getValue()
                ,startDate: Ext.getCmp('startdate').getValue()
                ,endDate: Ext.getCmp('enddate').getValue()
                ,limit: 0
            }
            ,listeners: {
                'success': {fn:function(r) {
                    location.href = FormIt.config.connectorUrl+'?HTTP_MODAUTH='+MODx.siteId+'&action=mgr/form/download&file='+r.results.filename
                },scope:this}
            }
        });
    }

});
Ext.reg('formit-grid-forms',FormIt.grid.Forms);
