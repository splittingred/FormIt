/*
@Todo: Contexts inside modx-tabs and form names inside modx-vtabs
*/

FormIt.panel.Home = function(config) {
    config = config || {};
    var encryptionText = '<p>'+_('formit.encryption_msg')+'</p>';
    if (!FormIt.config.opensslAvailable) {
        encryptionText += '<p class="alert danger">'+_('formit.encryption_unavailable_warning')+'</p>';
    }
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,components: [{
            cls: 'container',
            xtype: 'panel',
            items: [{
            html: '<h2>'+_('formit')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
                xtype: 'modx-tabs'
                ,defaults: {border: false,autoHeight: true}
                ,border: true
                ,hideMode: 'offsets'
                ,stateful: true
                ,stateId: 'formit-panel-home'
                ,stateEvents: ['tabchange']
                ,getState: function () {
                    return {
                        activeTab: this.items.indexOf(this.getActiveTab())
                    };
                }
                ,items: [{
                    title: _('formit.forms')
                    ,items: [{
                        html: '<p>' + _('formit.intro_msg') + '</p>'
                        ,border: false
                        ,bodyCssClass: 'panel-desc'
                    },{
                        xtype: 'panel'
                        ,cls: 'main-wrapper'
                        ,layout: 'form'
                        ,labelAlign: 'left'
                        ,labelWidth: 150
                        ,items: [{
                            xtype: 'modx-combo-context'
                            ,baseParams: {
                                action: 'context/getlist'
                                ,exclude: MODx.config['formit.exclude_contexts']
                            }
                            ,fieldLabel: _('formit.select_context')
                            ,id: 'context'
                            ,width: 400
                            ,listeners: {
                                select: {
                                    scope: this,
                                    fn: function (contextField,Obj) {
                                        Ext.getCmp('formit-grid-forms').baseParams.context_key = Obj.data.key;
                                        Ext.getCmp('formit-grid-forms').getBottomToolbar().changePage(1);
                                        Ext.getCmp('formit-grid-forms').refresh();
                                    }
                                }
                            }
                        },{
                            xtype: 'modx-combo'
                            ,url: FormIt.config.connectorUrl
                            ,baseParams: {
                                action: 'mgr/form/getnames'
                            }
                            ,fields: ['form']
                            ,displayField: 'form'
                            ,valueField: 'form'
                            ,fieldLabel: _('formit.select_form')
                            ,id: 'form'
                            ,width: 400
                            ,paging: true
                            ,pageSize: 20
                            ,listeners: {
                                select: {
                                    scope: this,
                                    fn: function (formField,Obj) {
                                        Ext.getCmp('formit-grid-forms').baseParams.form = Obj.data.form;
                                        Ext.getCmp('formit-grid-forms').getBottomToolbar().changePage(1);
                                        Ext.getCmp('formit-grid-forms').refresh();
                                    }
                                }
                            }
                        },{
                            xtype: 'datefield',
                            vtype: 'daterange',
                            fieldLabel: _('formit.select_start_date'),
                            id: 'startdate',
                            endDateField: 'enddate',
                            width: 400,
                            listeners: {
                                select: {
                                    scope: this,
                                    fn: function (dateField,dateObject) {
                                        Ext.getCmp('formit-grid-forms').baseParams.startDate = dateObject.format('d-m-Y');
                                        Ext.getCmp('formit-grid-forms').getBottomToolbar().changePage(1);
                                        Ext.getCmp('formit-grid-forms').refresh();
                                    }
                                }
                            }
                        },{
                            xtype: 'datefield',
                            vtype: 'daterange',
                            fieldLabel: _('formit.select_end_date'),
                            id: 'enddate',
                            startDateField: 'startdate',
                            width: 400,
                            listeners: {
                                select: {
                                    scope: this,
                                    fn: function (dateField,dateObject) {
                                        Ext.getCmp('formit-grid-forms').baseParams.endDate = dateObject.format('d-m-Y');
                                        Ext.getCmp('formit-grid-forms').getBottomToolbar().changePage(1);
                                        Ext.getCmp('formit-grid-forms').refresh();
                                    }
                                }
                            }
                        },{
                            xtype: 'panel'
                            ,cls: 'button-holder'
                            ,items: [{
                                xtype: 'button',
                                text: _('formit.clear'),
                                scope: this,
                                handler: function () {
                                    Ext.getCmp('form').setValue('');
                                    Ext.getCmp('context').setValue('');
                                    Ext.getCmp('startdate').setValue('');
                                    Ext.getCmp('enddate').setValue('');

                                    Ext.getCmp('formit-grid-forms').baseParams.form = '';
                                    Ext.getCmp('formit-grid-forms').baseParams.context_key = '';
                                    Ext.getCmp('formit-grid-forms').baseParams.startDate = '';
                                    Ext.getCmp('formit-grid-forms').baseParams.endDate = '';
                                    Ext.getCmp('formit-grid-forms').getBottomToolbar().changePage(1);
                                    Ext.getCmp('formit-grid-forms').refresh();
                                }
                            },
                                {
                                    xtype: 'button',
                                    text: _('formit.export'),
                                    //scope: this,
                                    handler: function () {
                                        Ext.getCmp('formit-grid-forms').export();
                                    }
                                }]
                        }]
                    },{
                        xtype: 'formit-grid-forms'
                        ,preventRender: true
                        ,cls: 'main-wrapper'
                    }]
                },{
                    title: _('formit.encryption')
                    ,items: [{
                        html: encryptionText
                        ,border: false
                        ,bodyCssClass: 'panel-desc'
                    },{
                        xtype: 'formit-grid-forms-encryption'
                        ,preventRender: true
                        ,cls: 'main-wrapper'
                    }]
                }]
            }]
        }],
        buttons: [{
            text: '<i class=\"icon icon-history\"></i> ' +_('formit.clean_forms'),
            handler: function () {
                var win = MODx.load({
                    xtype: 'formit-window-clean-form',
                    listeners: {
                        success: {fn: function(r) {
                            MODx.msg.status({
                                title: _('success'),
                                message: _('formit.window.cleanforms.success_description',r.a.result.object),
                                delay: 4
                            });

                            Ext.getCmp("formit-grid-forms").refresh()
                        },scope: this},
                        scope: this
                    }
                });
                win.show();
            },
            scope: this
        }]
    });
    FormIt.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.panel.Home,MODx.Component);
Ext.reg('formit-panel-home',FormIt.panel.Home);
