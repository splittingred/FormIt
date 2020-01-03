FormIt.grid.Forms = function(config) {
    config = config || {};

    config.tbar = [{
        text        : _('bulk_actions'),
        menu        : [{
            text        : '<i class="x-menu-item-icon icon icon-history"></i>' + _('formit.forms_clean'),
            handler     : this.cleanForms,
            scope       : this
        }, {
            text        : '<i class="x-menu-item-icon icon icon-download"></i>' + _('formit.forms_export'),
            handler     : this.exportForms,
            scope       : this
        },'-',  {
            text        : '<i class="x-menu-item-icon icon icon-times"></i>' + _('formit.forms_remove'),
            handler     : this.removeSelectedForms,
            scope       : this
        }]
    }, '->', {
        xtype       : 'datefield',
        name        : 'formit-filter-forms-start-date',
        id          : 'formit-filter-forms-start-date',
        dateFormat  : MODx.config.manager_date_format,
        startDay    : parseInt(MODx.config.manager_week_start),
        width       : 200,
        emptyText   : _('formit.filter_start_date'),
        listeners   : {
            'change'    : {
                fn          : this.filterStartDate,
                scope       : this
            }
        }
    }, {
        xtype       : 'datefield',
        name        : 'formit-filter-forms-end-date',
        id          : 'formit-filter-forms-end-date',
        dateFormat  : MODx.config.manager_date_format,
        startDay    : parseInt(MODx.config.manager_week_start),
        width       : 200,
        emptyText   : _('formit.filter_end_date'),
        listeners   : {
            'change'    : {
                fn          : this.filterEndDate,
                scope       : this
            }
        }
    }, {
        xtype       : 'formit-combo-forms',
        name        : 'formit-filter-forms-form',
        id          : 'formit-filter-forms-form',
        width       : 200,
        emptyText   : _('formit.filter_form'),
        listeners   : {
            'change'    : {
                fn          : this.filterForm,
                scope       : this
            }
        }
    }, {
        xtype       : 'textfield',
        name        : 'formit-filter-forms-search',
        id          : 'formit-filter-forms-search',
        emptyText   : _('search') + '...',
        value       : decodeURIComponent(MODx.request.form || ''),
        listeners   : {
            'change'    : {
                fn          : this.filterSearch,
                scope       : this
            },
            'render'    : {
                fn          : function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key     : Ext.EventObject.ENTER,
                        fn      : this.blur,
                        scope   : cmp
                    });
                },
                scope       : this
            }
        }
    }, {
        xtype       : 'button',
        cls         : 'x-form-filter-clear',
        id          : 'formit-filter-forms-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var selection = new Ext.grid.CheckboxSelectionModel();

    var columns = [selection, {
        header      : _('formit.label_form_name'),
        dataIndex   : 'form',
        sortable    : true,
        editable    : false,
        width       : 200,
        fixed       : true
    }, {
        header      : _('formit.label_form_values'),
        dataIndex   : 'values',
        sortable    : true,
        editable    : false,
        width       : 125,
        renderer    : this.renderValues
    }, {
        header      : _('formit.label_form_ip'),
        dataIndex   : 'ip',
        sortable    : true,
        editable    : true,
        width       : 125,
        fixed       : true
    }, {
        header      : _('formit.label_form_encrypted'),
        dataIndex   : 'encrypted',
        sortable    : true,
        editable    : true,
        width       : 125,
        fixed       : true,
        renderer    : this.renderBoolean
    }, {
        header      : _('formit.label_form_date'),
        dataIndex   : 'date',
        sortable    : true,
        editable    : false,
        fixed       : true,
        width       : 200,
        renderer    : this.renderDate
    }];

    Ext.applyIf(config, {
        sm          : selection,
        columns     : columns,
        id          : 'formit-grid-forms',
        url         : FormIt.config.connector_url,
        baseParams  : {
            action      : 'mgr/forms/getlist',
            query       : decodeURIComponent(MODx.request.form || '')
        },
        fields      :['id','form','values', 'ip', 'date', 'encrypted'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        remoteSort  : true
    });

    FormIt.grid.Forms.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.grid.Forms, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    filterForm: function(tf, nv, ov) {
        this.getStore().baseParams.form = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    filterStartDate: function(tf, nv, ov) {
        this.getStore().baseParams.start_date = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    filterEndDate: function(tf, nv, ov) {
        this.getStore().baseParams.end_date = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query        = '';
        this.getStore().baseParams.form         = '';
        this.getStore().baseParams.start_date   = '';
        this.getStore().baseParams.end_date     = '';

        Ext.getCmp('formit-filter-forms-search').reset();
        Ext.getCmp('formit-filter-forms-form').reset();
        Ext.getCmp('formit-filter-forms-start-date').reset();
        Ext.getCmp('formit-filter-forms-end-date').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        return [{
            text    : '<i class="x-menu-item-icon icon icon-search"></i>' + _('formit.form_view'),
            handler : this.viewForm
        }, {
            text    : '<i class="x-menu-item-icon icon icon-question-circle"></i>' + _('formit.view_ip'),
            handler : this.viewFormIP
        }, '-', {
            text    : '<i class="x-menu-item-icon icon icon-times"></i>' + _('formit.form_remove'),
            handler : this.removeForm
        }];
    },
    viewForm: function(btn, e) {
        if (this.viewFormWindow) {
            this.viewFormWindow.destroy();
        }

        this.viewFormWindow = MODx.load({
            xtype       : 'formit-window-form-view',
            record      : this.menu.record,
            closeAction : 'close',
            buttons     : [{
                text        : _('ok'),
                cls         : 'primary-button',
                handler     : function() {
                    this.viewFormWindow.close();
                },
                scope       : this
            }]
        });

        this.viewFormWindow.show(e.target);
    },
    removeForm: function(btn, e) {
        MODx.msg.confirm({
            title   : _('formit.form_remove'),
            text    : _('formit.form_remove_confirm'),
            url     : this.config.url,
            params  : {
                action  : 'mgr/forms/remove',
                id      : this.menu.record.id
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    viewFormIP: function(btn, e) {
        this.getStore().baseParams.query    = this.menu.record.ip;
        this.getStore().baseParams.form     = '';

        Ext.getCmp('formit-filter-forms-search').setValue(this.menu.record.ip);
        Ext.getCmp('formit-filter-forms-form').reset();

        this.getBottomToolbar().changePage(1);
    },
    removeSelectedForms: function(btn, e) {
        MODx.msg.confirm({
            title   : _('formit.forms_remove'),
            text    : _('formit.forms_remove_confirm'),
            url     : this.config.url,
            params  : {
                action  : 'mgr/forms/removeselected',
                ids     : this.getSelectedAsList()
            },
            listeners   : {
                'success'   : {
                    fn          : this.refresh,
                    scope       : this
                }
            }
        });
    },
    cleanForms: function(btn, e) {
        if (this.cleanFormsWindow) {
            this.cleanFormsWindow.destroy();
        }

        this.cleanFormsWindow = MODx.load({
            xtype       : 'formit-window-forms-clean',
            closeAction : 'close',
            listeners   : {
                'success'   : {
                    fn          : function(record) {
                        MODx.msg.status({
                            title   : _('success'),
                            message : record.a.result.message,
                            delay   : 4
                        });

                        this.refresh();
                    },
                    scope       : this
                }
            }
        });

        this.cleanFormsWindow.show(e.target);
    },
    exportForms: function(btn, e) {
        if (this.exportFormsWindow) {
            this.exportFormsWindow.destroy();
        }

        this.exportFormsWindow = MODx.load({
            xtype       : 'formit-window-forms-export',
            record      : {
                form        : this.getStore().baseParams.form || '',
                start_date  : this.getStore().baseParams.start_date || '',
                end_date    : this.getStore().baseParams.end_date || ''
            },
            closeAction :'close',
            listeners   : {
                'success'   : {
                    fn          : function() {
                        location.href = FormIt.config.connector_url + '?action=' + this.exportFormsWindow.baseParams.action + '&download=1&HTTP_MODAUTH=' + MODx.siteId;
                    },
                    scope       : this
                },
                'failure'   : {
                    fn          : function(response) {
                        MODx.msg.alert(_('failure'), response.message);
                    },
                    scope       : this
                }
            }
        });

        this.exportFormsWindow.setValues({
            form        : this.getStore().baseParams.form || '',
            start_date  : this.getStore().baseParams.start_date || '',
            end_date    : this.getStore().baseParams.end_date || ''
        });
        this.exportFormsWindow.show(e.target);
    },
    renderBoolean: function(d, c) {
        c.css = parseInt(d) === 1|| d ? 'green' : 'red';

        return parseInt(d) === 1 || d ? _('yes') : _('no');
    },
    renderValues: function(d) {
        var output = [];

        for (var key in d) {
            output.push(String.format('<strong>{0}</strong>: {1}', key, d[key]));
        }

        return output.join(', ');
    },
    renderDate: function(a) {
        if (Ext.isEmpty(a)) {
            return '—';
        }

        return a;
    }
});

Ext.reg('formit-grid-forms', FormIt.grid.Forms);

FormIt.window.ViewForm = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        height      : 500,
        width       : 600,
        title       : _('formit.form_view'),
        labelAlign  : 'left',
        labelWidth  : 150,
        cls         : 'x-window-formit',
        fields      : [{
            xtype       : 'statictextfield',
            fieldLabel  : _('formit.label_form_name'),
            name        : 'form',
            anchor      : '100%'
        }, {
            xtype       : 'statictextfield',
            fieldLabel  : _('formit.label_form_ip'),
            name        : 'ip',
            anchor      : '100%'
        }, {
            xtype       : 'statictextfield',
            fieldLabel  : _('formit.label_form_date'),
            name        : 'date',
            anchor      : '100%'
        }, {
            html        : '<hr />'
        }, this.getValues(config.record.values)]
    });

    FormIt.window.ViewForm.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.window.ViewForm, MODx.Window, {
    getValues: function(values) {
        var output = [];

        for (var key in values) {
            if (values[key].length >= FormIt.config['max_chars']) {
                output.push({
                    xtype       : 'textarea',
                    fieldLabel  : key,
                    name        : 'date',
                    anchor      : '100%',
                    value       : values[key],
                    height      : 125,
                    readOnly    : true
                });
            } else {
                output.push({
                    xtype       : 'textfield',
                    fieldLabel  : key,
                    name        : 'date',
                    anchor      : '100%',
                    value       : values[key],
                    readOnly    : true
                });
            }
        }

        return output;
    }
});

Ext.reg('formit-window-form-view', FormIt.window.ViewForm);

FormIt.window.CleanForms = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        width       : 500,
        title       : _('formit.forms_clean'),
        cls         : 'x-window-formit',
        url         : FormIt.config.connector_url,
        baseParams  : {
            action      : 'mgr/forms/clean'
        },
        items       : [{
            html        : '<p>' + _('formit.forms_clean_desc') + '</p>',
            cls         : 'panel-desc',
        }, {
            xtype       : 'modx-panel',
            items       : [{
                xtype       : 'label',
                html        : _('formit.label_clean_label')
            }, {
                xtype       : 'numberfield',
                name        : 'days',
                minValue    : 1,
                maxValue    : 9999999999,
                value       : MODx.config['formit.cleanform.days'],
                width       : 75,
                allowBlank  : false,
                style       : 'margin: 0 10px;'
            }, {
                xtype       : 'label',
                html        : _('formit.label_clean_desc'),
            }]
        }],
        keys        : [],
        saveBtnText : _('formit.forms_clean'),
        waitMsg     : _('formit.forms_clean_executing')
    });

    FormIt.window.CleanForms.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.window.CleanForms, MODx.Window);

Ext.reg('formit-window-forms-clean', FormIt.window.CleanForms);

FormIt.window.ExportForms = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        autoHeight  : true,
        title       : _('formit.forms_export'),
        url         : FormIt.config.connector_url,
        baseParams  : {
            action      : 'mgr/forms/export'
        },
        fields      : [{
            xtype       : 'formit-combo-forms',
            fieldLabel  : _('formit.label_export_form'),
            description : MODx.expandHelp ? '' : _('formit.label_export_form_desc'),
            name        : 'form',
            anchor      : '100%',
            width       : '100%', 
            emptyText   : _('formit.filter_form'),
            allowBlank  : true
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('formit.label_export_form_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'datefield',
            fieldLabel  : _('formit.label_export_start_date'),
            description : MODx.expandHelp ? '' : _('formit.label_export_start_date_desc'),
            name        : 'start_date',
            dateFormat  : MODx.config.manager_date_format,
            startDay    : parseInt(MODx.config.manager_week_start),
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html         : _('formit.label_export_start_date_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'datefield',
            fieldLabel  : _('formit.label_export_end_date'),
            description : MODx.expandHelp ? '' : _('formit.label_export_end_date_desc'),
            name        : 'end_date',
            dateFormat  : MODx.config.manager_date_format,
            startDay    : parseInt(MODx.config.manager_week_start),
            anchor      : '100%'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html         : _('formit.label_export_end_date_desc'),
            cls         : 'desc-under'
        }, {
            xtype       : 'textfield',
            fieldLabel  : _('formit.label_export_delimiter'),
            description : MODx.expandHelp ? '' : _('formit.label_export_delimiter_desc'),
            name        : 'delimiter',
            anchor      : '100%',
            allowBlank  : false,
            value       : ';'
        }, {
            xtype       : MODx.expandHelp ? 'label' : 'hidden',
            html        : _('formit.label_export_delimiter_desc'),
            cls         : 'desc-under'
        }],
        saveBtnText : _('export')
    });

    FormIt.window.ExportForms.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.window.ExportForms, MODx.Window);

Ext.reg('formit-window-forms-export', FormIt.window.ExportForms);

FormIt.combo.Forms = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        url             : FormIt.config.connector_url,
        baseParams      : {
            action : 'mgr/forms/getforms'
        },
        fields          : ['form'],
        hiddenName      : 'form',
        valueField      : 'form',
        displayField    : 'form',
        paging          : true,
        pageSize        : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30
    });

    FormIt.combo.Forms.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.combo.Forms, MODx.combo.ComboBox);

Ext.reg('formit-combo-forms', FormIt.combo.Forms);
