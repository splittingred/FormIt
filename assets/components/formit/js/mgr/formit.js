var FormIt = function(config) {
    config = config || {};

    FormIt.superclass.constructor.call(this, config);
};

Ext.extend(FormIt, Ext.Component,{
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {}
});

Ext.reg('formit', FormIt);

FormIt = new FormIt();