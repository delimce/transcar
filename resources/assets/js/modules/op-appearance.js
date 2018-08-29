const reloadPersonList = function (entity,value) {
    if ( $('#appear-list-container').length ) {
        // exists
        let url = 'api/appear/all/'+String(entity)+'/'+String(value);
        reloadList(url,'#appear-list');

    }
}