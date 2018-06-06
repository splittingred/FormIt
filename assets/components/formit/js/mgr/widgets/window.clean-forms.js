FormIt.window.CleanForm = function(config) {
    config = config || {};
    config.id = config.id || Ext.id(),
        Ext.applyIf(config,{
            title: _('formit.clean_forms'),
            autoHeight: true,
            modal: true,
            url: FormIt.config.connectorUrl,
            baseParams: {
                action: 'mgr/form/clean'
            },
            width: 500,
            bodyPadding: 10,
            items: [
                {
                    xtype: 'panel',
                    cls: 'panel-desc',
                    html: '<p>' + _('formit.window.cleanforms.intro_msg') + '</p>',
                    border: false
                }
            ],
            fields: [
                {
                    xtype: 'modx-panel',
                    width: 400,
                    height: 50,
                    layout: {
                        type: 'hbox',
                        align: 'middle'
                    },
                    items: [
                        {
                            xtype: 'label',
                            html: _('formit.window.cleanforms.days_to_delete') + '&nbsp;'
                        },
                        {
                            xtype: 'numberfield',
                            name: 'days',
                            allowBlank: false,
                            minValue: 1,
                            maxValue: 9999999999,
                            value: MODx.config['formit.cleanform.days'],
                            width: 75
                        },
                        {
                            xtype: 'label',
                            html: '&nbsp;' + _('formit.window.cleanforms.days'),
                            style: 'text-align: right;'
                        }
                    ]
                }
            ],
            keys: [], //prevent enter in textarea from firing submit
            saveBtnText: _('formit.window.cleanforms.execute'),
            waitMsg: _('formit.window.cleanforms.executing')
        });
    FormIt.window.CleanForm.superclass.constructor.call(this,config);
};
Ext.extend(FormIt.window.CleanForm,MODx.Window);
Ext.reg('formit-window-clean-form',FormIt.window.CleanForm);
