FormIt.panel.Home = function(config) {
    config = config || {};

    var tabs = [{
        title       : _('formit.forms'),
        items       : [{
            html        : '<p>' + _('formit.forms_desc') + '</p>',
            bodyCssClass : 'panel-desc'
        }, {
            xtype       : 'formit-grid-forms',
            cls         : 'main-wrapper',
            preventRender : true
        }]
    }];

    if (FormIt.config.permissions.encryptions) {
        tabs.push({
            title       : _('formit.encryptions'),
            items       : [{
                html        : '<p>' + _('formit.encryptions_desc') + '</p>',
                bodyCssClass : 'panel-desc'
            }, {
                hidden      : FormIt.config.openssl,
                html        : '<p>' + _('formit.encryption_unavailable_warning') + '</p>',
                bodyCssClass : 'panel-alert-desc'
            }, {
                xtype       : 'formit-grid-encryptions',
                cls         : 'main-wrapper',
                preventRender : true,
                refreshGrid : 'formit-grid-forms'
            }]
        });
    }

    Ext.apply(config, {
        id          : 'formit-panel-home',
        cls         : 'container',
        items       : [{
            html        : '<h2>' + _('formit') + '</h2>',
            cls         : 'modx-page-header'
        }, {
            xtype       : 'modx-tabs',
            items       : tabs
        }]
    });

    FormIt.panel.Home.superclass.constructor.call(this, config);
};

Ext.extend(FormIt.panel.Home, MODx.FormPanel);

Ext.reg('formit-panel-home', FormIt.panel.Home);
