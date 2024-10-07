<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">PSN</label>
                    <input type="text" class="form-control" id="itm_tracer_doc" onkeypress="itm_tracer_doc_e_keypress(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Qty</label>
                    <input type="text" class="form-control" id="itm_tracer_qty" onkeypress="itm_tracer_qty_e_keypress(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <button id="itm_tracer_btn_check" onclick="itm_tracer_btn_check_on_click(this)" class="btn btn-primary btn-sm">Check</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="itm_tracer_table_container">
                    <table id="itm_tracer_tbl" class="table table-bordered border-primary table-sm table-hover">
                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th colspan="3">Item</th>
                                <th rowspan="2">Qty</th>
                                <th rowspan="2">UOM</th>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Description</th>
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
    function itm_tracer_btn_check_on_click(pThis) {
        const doc = itm_tracer_doc.value.trim()
        if(doc.length<=5) {
            itm_tracer_doc.focus()
            return
        }
        const data = {
            PSNDoc : doc,
            qty : numeral(itm_tracer_qty.value).value()
        }

        pThis.disabled = true

        document.getElementById("itm_tracer_table_container").getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="5">Please wait</td></tr>';
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>item-tracer/outstanding-scan",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                let mydes = document.getElementById("itm_tracer_table_container");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("itm_tracer_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("itm_tracer_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';

                response.data.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerText = arrayItem['itm']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['SPTNO']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['ITMD1']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(arrayItem['qty']).format(',')
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['UOM']
                    newcell.classList.add('text-center')
                })

                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false
            }
        });
    }

    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(itm_tracer_qty);

    function itm_tracer_doc_e_keypress(e) {
        if(e.key === 'Enter') {
            itm_tracer_qty.focus()
        }
    }

    function itm_tracer_qty_e_keypress(e) {
        if(e.key === 'Enter') {
            itm_tracer_btn_check.focus()
        }
    }


</script>