Ext.onReady(function() {
    MODx.load({
        xtype : 'formit-page-home'
    });
});

FormIt.page.Home = function(config) {
    config = config || {};

    config.buttons = [];

    if (FormIt.config.branding_url) {
        config.buttons.push({
            text        : 'FormIt ' + FormIt.config.version,
            cls         : 'x-btn-branding',
            handler     : this.loadBranding
        });
    }

    if (FormIt.config.branding_url_help) {
        config.buttons.push({
            text        : _('help_ex'),
            handler     : MODx.loadHelpPane,
            scope       : this
        });
    }

    Ext.applyIf(config, {
        components  : [{
            xtype       : 'formit-panel-home',
            renderTo    : 'formit-panel-home-div'
        }]
    });

    FormIt.page.Home.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.page.Home, MODx.Component, {
    loadBranding: function(btn) {
        window.open(FormIt.config.branding_url);
    }
});

Ext.reg('formit-page-home', FormIt.page.Home);