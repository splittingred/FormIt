FormIt.grid.Encryptions = function(config) {
    config = config || {};

    config.tbar = ['->', {
        xtype       : 'textfield',
        name        : 'formit-filter-encryptions-search',
        id          : 'formit-filter-encryptions-search',
        emptyText   : _('search') + '...',
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
        id          : 'formit-filter-encryptions-clear',
        text        : _('filter_clear'),
        listeners   : {
            'click'     : {
                fn          : this.clearFilter,
                scope       : this
            }
        }
    }];

    var columns = [{
        header      : _('formit.label_form_name'),
        dataIndex   : 'form',
        sortable    : true,
        editable    : false,
        width       : 250
    }, {
        header      : _('formit.label_form_encrypted'),
        dataIndex   : 'encrypted',
        sortable    : true,
        editable    : false,
        width       : 150,
        fixed       : true
    }, {
        header      : _('formit.label_form_decrypted'),
        dataIndex   : 'decrypted',
        sortable    : true,
        editable    : false,
        width       : 150,
        fixed       : true
    }, {
        header      : _('formit.label_form_total'),
        dataIndex   : 'total',
        sortable    : true,
        editable    : false,
        width       : 150,
        fixed       : true,
        renderer    : this.renderTotal
    }];

    Ext.applyIf(config,{
        columns     : columns,
        url         : FormIt.config.connector_url,
        baseParams  : {
            action      : 'mgr/encryption/getlist'
        },
        fields      : ['form', 'encrypted', 'decrypted'],
        paging      : true,
        pageSize    : MODx.config.default_per_page > 30 ? MODx.config.default_per_page : 30,
        remoteSort  : true,
        refreshGrid : [],
    });

    FormIt.grid.Encryptions.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.grid.Encryptions, MODx.grid.Grid, {
    filterSearch: function(tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();

        this.getBottomToolbar().changePage(1);
    },
    clearFilter: function() {
        this.getStore().baseParams.query = '';

        Ext.getCmp('formit-filter-encryptions-search').reset();

        this.getBottomToolbar().changePage(1);
    },
    getMenu: function() {
        var menu = [];

        if (FormIt.config.openssl) {
            if (this.menu.record.decrypted > 0) {
                menu.push({
                    text    : '<i class="x-menu-item-icon icon icon-lock"></i>' + _('formit.form_encrypt'),
                    handler : this.encryptAll
                });
            }

            if (this.menu.record.encrypted > 0) {
                menu.push({
                    text    : '<i class="x-menu-item-icon icon icon-unlock"></i>' + _('formit.form_decrypt'),
                    handler : this.decryptAll
                });
            }
        }

        return menu;
    },
    refreshGrids: function() {
        var grids = this.config.refreshGrid;

        if (typeof this.config.refreshGrid === 'string') {
            if (Ext.getCmp(this.config.refreshGrid)) {
                Ext.getCmp(this.config.refreshGrid).refresh();
            }
        } else {
            this.config.refreshGrid.forEach(function(grid) {
                if (Ext.getCmp(grid)) {
                    Ext.getCmp(grid).refresh();
                }
            });
        }

        this.refresh();
    },
    encryptAll: function(btn, e) {
        MODx.msg.confirm({
            title       : _('formit.form_encrypt'),
            text        : _('formit.form_encrypt_confirm'),
            url         : FormIt.config.connector_url,
            params      : {
                action      : 'mgr/encryption/encrypt',
                form        : this.menu.record.form
            },
            listeners   : {
                'success'   : {
                    fn          : this.refreshGrids,
                    scope       : this
                }
            }
        });
    },
    decryptAll: function(btn, e) {
        MODx.msg.confirm({
            title       : _('formit.form_decrypt'),
            text        : _('formit.form_decrypt_confirm'),
            url         : FormIt.config.connector_url,
            params      : {
                action      : 'mgr/encryption/decrypt',
                form        : this.menu.record.form
            },
            listeners   : {
                'success'   : {
                    fn          : this.refreshGrids,
                    scope       : this
                }
            }
        });
    },
    renderTotal: function(d, c, e) {
        return e.json.encrypted + e.json.decrypted;
    }
});

Ext.reg('formit-grid-encryptions', FormIt.grid.Encryptions);