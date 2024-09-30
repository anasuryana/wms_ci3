var ClipboardUtils = new function() {
    const permissions = {
        'image/bmp': true,
        'image/gif': true,
        'image/png': true,
        'image/jpeg': true,
        'image/tiff': true
    };
    function getType(types) {
        for (let j = 0; j < types.length; ++j) {
            const type = types[j];
            if (permissions[type]) {
                return type;
            }
        }
        return null;
    }
    function getItem(items) {
        for (let i = 0; i < items.length; ++i) {
            const item = items[i];
            if(item) {
                var type = getType(item.types);
                if(type) {
                    return item.getType(type);
                }
            }
        }
        return null;
    }
    function readFile(file, callback) {
        if (window.FileReader) {
            const reader = new FileReader();
            reader.onload = function() {
                callback(reader.result, null);
            };
            reader.onerror = function() {
                callback(null, 'Incorrect file.');
            };
            reader.readAsDataURL(file);
        } else {
            callback(null, 'File API is not supported.');
        }
    }
    this.readImage = function(callback) {
        if (navigator.clipboard) {
            const promise = navigator.clipboard.read();
            promise
                .then(function(items) {
                    const promise = getItem(items);
                    if (promise == null) {
                        callback(null, null);
                          return;
                    }
                    promise
                          .then(function(result) {
                              readFile(result, callback);
                        })
                          .catch(function(error) {
                              callback(null, error || 'Clipboard reading error.');
                        });
                })
                .catch(function(error) {
                    callback(null, error || 'Clipboard reading error.');
                });
        } else {
            callback(null, 'Clipboard API is not supported.');
        }
    };
};