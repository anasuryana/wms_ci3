<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="itmjr_btnnew" onclick="itmjr_btnnew_eclick()" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="itmjr_btnsave" onclick="itmjr_btnsave_eclick(this)" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Document</label>
                    <input type="text" class="form-control" id="itmjr_doc" onkeypress="itmjr_doc_ekeypress(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text" >3N1</label>
                    <input type="text" class="form-control" id="itmjr_3n1" onkeypress="itmjr_3n1_ekeypress(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">3N2</label>
                    <input type="text" class="form-control" id="itmjr_3n2" onkeypress="itmjr_3n2_ekeypress(event)">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Uniquekey</label>
                    <input type="text" class="form-control" id="itmjr_uniquekey" onkeypress="itmjr_uniquekey_ekeypress(event)" max="250">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Process</label>
                    <input type="text" class="form-control" id="itmjr_process" disabled>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text" >MC</label>
                    <input type="text" class="form-control" id="itmjr_mc" disabled>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">MCZ</label>
                    <input type="text" class="form-control" id="itmjr_mcz" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-1" id="itmjr-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="itmjr_container">
                    <table id="itmjr_tbl" class="table table-bordered border-primary table-sm table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Item Code</th>
                                <th>Lot Number</th>
                                <th>Qty</th>
                                <th>Uniquekey</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var itmjrSuppliedQty = 0;

    function itmjr_clear_label_input() {
        itmjr_3n1.value = ''
        itmjr_3n2.value = ''
        itmjr_uniquekey.value = ''
        itmjr_process.value = ''
        itmjr_mc.value = ''
        itmjr_mcz.value = ''

        itmjr_3n1.disabled = false
        document.getElementById('itmjr_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
    }

    function itmjr_btnnew_eclick() {
        itmjr_doc.value = ''
        itmjr_clear_label_input()
        itmjr_doc.disabled = false
        itmjr_doc.focus()
        
    }

    function itmjr_doc_ekeypress(e) {
        if(e.key == 'Enter') {
            e.target.disabled = true
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>supply/validate-document",
                data: {doc : e.target.value},
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd == 0) {
                        e.target.disabled = false
                        alertify.warning(response.status[0].msg)
                    } else {
                        itmjr_3n1.focus()
                    }
                }, error: function(xhr, xopt, xthrow) {
                    e.target.disabled = false
                }
            });
        }
    }

    function itmjr_validateItemList(data) {
        let mtbl = document.getElementById('itmjr_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isFound = false
        if(ttlrows>1) {
            for(let i=1;i<ttlrows;i++) {
                if(mtbl.rows[i].cells[0].innerText!=data.item_code) {
                    alertify.warning('part code is different');
                    isFound = true
                    break;
                }
            }
            if(isFound) {
                return false
            } else {
                return true
            }
        } else {
            return true // continue
        }
    }

    function itmjr_3n1_ekeypress(e) {
        if(e.key == 'Enter') {
            let rawValue = e.target.value.trim().toUpperCase()
            if(rawValue.length > 3) {

                if(rawValue.substr(0,3) != '3N1') {
                    alertify.warning('Unknown Format C3 Label')
                    return
                }

                let itemCode = ''
                let item = ''

                if(rawValue.includes(' ')) {
                    // validasi 3n1 dengan spasi
                    let _arrayRawValue = rawValue.split(' ')
                    itmjrSuppliedQty = _arrayRawValue[1]
                    e.target.value = _arrayRawValue[0].substring(3, 20)
                } else {
                    // validasi 3n1 tanpa spasi
                    itmjrSuppliedQty = 0
                    e.target.value = rawValue.substring(3, 20)
                }

                if(!itmjr_validateItemList({item_code : e.target.value})) {
                    return
                }

                e.target.disabled = true
                $.ajax({
                    type: "GET",
                    url: "<?=$_ENV['APP_INTERNAL_API']?>supply/validate-item",
                    data: {doc : itmjr_doc.value, item : e.target.value},
                    dataType: "json",
                    success: function (response) {
                        if(response.data[0].cd == 0) {
                            e.target.disabled = false
                            alertify.warning(response.data[0].msg)
                        } else {
                            itmjr_3n2.focus()
                        }
                    }, error: function(xhr, xopt, xthrow) {
                        e.target.disabled = false
                    }
                });
            }
        }
    }

    function itmjr_3n2_ekeypress(e) {
        if(e.key==='Enter') {
            let rawValue = e.target.value.trim().toUpperCase()
            if(rawValue.substr(0,3) != '3N2') {
                alertify.warning('Unknown Format C3 Label')
                return
            }

            if(!rawValue.includes(' ')) {
                alertify.warning('invalid 3N2 format')
                return
            }

            let rawValueArray = rawValue.split(' ')

            let _qty = 0
            let _lot = ''
            if(itmjrSuppliedQty == 0) {
                _qty = rawValueArray[1]
                _lot = rawValueArray[2]
            } else {
                _qty = itmjrSuppliedQty
                _lot = rawValueArray[1]
            }

            const data = {doc : itmjr_doc.value,
                    item : itmjr_3n1.value,
                    lotNumber : _lot,
                    qty : _qty
                    }

            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>supply/validate-supplied-item",
                data: data,
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd == 0) {
                        e.target.disabled = false
                        alertify.warning(response.status[0].msg)
                        return
                    }

                    itmjr_validateAfter3N2(response)

                }, error: function(xhr, xopt, xthrow) {
                    e.target.disabled = false
                }
            });
        }
    }

    function itmjr_validateAfter3N2(response) {
        if(itmjr_isAbleToAdded(response.status[0].data)) {

            // validate maximum row
            if(itmjr_isReachedMaximumQuota()) {
                alertify.warning('Maximum rows is 5')
                return
            }

            itmjr_process.value = response.status[0].data[0].SPLSCN_PROCD
            itmjr_mc.value = response.status[0].data[0].SPLSCN_MC
            itmjr_mcz.value = response.status[0].data[0].SPLSCN_ORDERNO
            itmjr_addToList(response.status[0].data)

            itmjr_3n1.value = ''
            itmjr_3n2.value = ''
            itmjr_uniquekey.value = ''

            itmjr_3n1.disabled = false
            itmjr_3n2.disabled = false

            itmjr_3n1.focus()
        }
    }

    function itmjr_isReachedMaximumQuota() {

        let mtbl = document.getElementById('itmjr_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let ttlrows = mtbl.getElementsByTagName('tr').length;
        return ttlrows === 5 ? true : false
    }

    function itmjr_isAbleToAdded(data) {
        if(itmjr_process.value.length>0) {
            if(data[0].SPLSCN_PROCD != itmjr_process.value) {
                alertify.warning('unable to join reel because Process is different')
                return false
            }
            if( data[0].SPLSCN_MC != itmjr_mc.value ) {
                alertify.warning('unable to join reel because MC is different')
                return false
            }
            if( data[0].SPLSCN_ORDERNO != itmjr_mcz.value ) {
                alertify.warning('unable to join reel because MCZ is different')
                return false
            }
        }


        let mtbl = document.getElementById('itmjr_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isFound = false

        // validate uniquekey
        for(let i=0;i<ttlrows;i++) {
            if(mtbl.rows[i].cells[3].innerText==data[0].SPLSCN_UNQCODE) {
                alertify.warning('the label is already in the list below');
                isFound = true
                break;
            }
        }

        if(isFound) {
            return false
        } else {
            // validate non uniquekey
            if(data.length==1) {
                for(let i=0;i<ttlrows;i++) {
                    if(mtbl.rows[i].cells[1].innerText==data[0].SPLSCN_LOTNO
                        && numeral(mtbl.rows[i].cells[2].innerText).value() == numeral(data[0].SPLSCN_QTY).value()
                    ) {
                        alertify.warning('the label is already in the list below.');
                        isFound = true
                        break;
                    }
                }
            }
            return !isFound
        }
    }

    function itmjr_addToList(data) {
        let mtbl = document.getElementById('itmjr_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = data.length
        let newrow, newcell;
        for(let i=0;i<ttlrows;i++) {
            newrow = tableku2.insertRow(-1);
            newcell = newrow.insertCell(0);
            newcell.innerText = data[i].SPLSCN_ITMCD
            newcell = newrow.insertCell(-1);
            newcell.innerText = data[i].SPLSCN_LOTNO
            newcell = newrow.insertCell(-1);
            newcell.classList.add('text-end')
            newcell.innerText = numeral(data[i].SPLSCN_QTY).value()
            newcell = newrow.insertCell(-1);
            newcell.classList.add('text-center')
            newcell.innerText = data[i].SPLSCN_UNQCODE
        }
    }

    function itmjr_btnsave_eclick(pThis) {
        let mtbl = document.getElementById('itmjr_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        const ttlrows = mtbltr.length
        if( ttlrows<=1 ) {
            alertify.warning("nothing to be saved")
            return
        }

        let dataDetail = [];

        for(let i=0;i<ttlrows;i++) {
            dataDetail.push({
                lotNumber : tableku2.rows[i].cells[1].innerText,
                qty : numeral(tableku2.rows[i].cells[2].innerText).value(),
                id : tableku2.rows[i].cells[3].innerText,
            })
        }

        const dataInput = {
            REELC_DOC : itmjr_doc.value,
            REELC_ITMCD : tableku2.rows[0].cells[0].innerText,
            REELC_FOR_PROCESS : itmjr_mc.value,
            REELC_FOR_MC : itmjr_mc.value,
            REELC_FOR_MCZ : itmjr_mc.value,
            detail : dataDetail,
            user_id : uidnya
        }

        if(!confirm('Are you sure ?')) {
            return
        }

        pThis.disabled = true
        const div_alert = document.getElementById('itmjr-div-alert')
        div_alert.innerHTML = ''

        $.ajax({
            type: "POST",
            url: "<?=$_ENV['APP_INTERNAL_API']?>supply/join-reel",
            data: JSON.stringify(dataInput),
            dataType: "JSON",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)

                itmjr_clear_label_input()
                itmjr_3n1.focus()
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false
                const respon = Object.keys(xhr.responseJSON)

                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            }
        });
    }

    function itmjr_uniquekey_ekeypress(e) {
        if(e.key === 'Enter') {
            let rawValue = e.target.value.trim().toUpperCase()
            if(!rawValue.includes('|')) {
                alertify.warning('It is not valid C3 Label')
                return
            }

            let rawValueArray = rawValue.split('|')

            let itemCode = rawValueArray[0].substring(4, 25)
            let _3n2 = rawValueArray[1].split(' ')

            if(!itmjr_validateItemList({item_code : itemCode})) {
                return
            }

            const data = {doc : itmjr_doc.value,
                    item : itemCode,
                    lotNumber : _3n2[2],
                    qty : _3n2[1],
                    uniquekey : rawValueArray[2]
                }

            e.target.disabled = true

            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>supply/validate-supplied-item",
                data: data,
                dataType: "json",
                success: function (response) {
                    e.target.disabled = false
                    if(response.status[0].cd == 0) {
                        alertify.warning(response.status[0].msg)
                        return
                    }

                    itmjr_validateAfter3N2(response)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    e.target.disabled = false
                }
            });
        }
    }
</script>
