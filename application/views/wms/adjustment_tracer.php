<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="itm_tracer_adj_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">WO</label>
                    <input type="text" class="form-control" id="itm_tracer_adj_doc" onkeypress="itm_tracer_adj_doc_e_keypress(event)" maxlength="50" title="Press Enter to get job list">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Job</label>
                    <select class="form-select" id="itm_tracer_adj_job" onchange="itm_tracer_adj_job_on_change(event)">
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Crossed-Check Qty</label>
                    <input type="text" class="form-control itm_tracer_number" id="itm_tracer_adj_cross_qty">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Completion Qty</label>
                    <input type="text" class="form-control itm_tracer_number" id="itm_tracer_adj_completion_qty">
                </div>
            </div>
        </div>
        <div class="row" id="itm_tracer_adj_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">NIK</label>
                    <input type="text" class="form-control" id="itm_tracer_adj_nik" maxlength="15" onkeypress="itm_tracer_adj_nik_e_keypress(event)" title="Press Enter to get name">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Name</label>
                    <input type="text" class="form-control" id="itm_tracer_adj_name" disabled>
                </div>
            </div>
        </div>
        <div class="row" id="itm_tracer_adj_stack3">
            <div class="col-md-12 mb-1 text-center">
                <button id="itm_tracer_adj_btn_save" class="btn btn-primary" onclick="itm_tracer_adj_btn_save_onclick(this)"><i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="itm_tracer_cross_qty_before">
<input type="hidden" id="itm_tracer_completion_qty_before">
<input type="hidden" id="itm_tracer_completion_spid">
<input type="hidden" id="itm_tracer_completion_qty_lot">
<script>
    function itm_tracer_adj_doc_e_keypress(e) {
        if(e.key === 'Enter') {
            const doc = e.target.value.trim()
            if(doc.length > 3) {
                const data = {
                    doc: doc
                }
                e.target.disabled = true
                $.ajax({
                    type: "GET",
                    url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>production/active-job-from-wo",
                    data: data,
                    dataType: "JSON",
                    success: function (response) {
                        e.target.disabled = false
                        let job_text = '<option value="_0_0">-- Select Job --</option>'
                        response.data.forEach((ai) => {
                            job_text += `<option value="${ai['TLWS_JOBNO']}_${ai['SWMP_CLS']}_${ai['CLS_QTY']}_${ai['TLWS_SPID']}_${numeral(ai['WOR_QTY']).value()}">
                            ${ai['TLWS_JOBNO']}</option>`
                        })
                        itm_tracer_adj_job.innerHTML = job_text
                        itm_tracer_adj_job.focus()
                    }, error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow)
                        e.target.disabled = false
                    }
                });
            }
        }
    }

    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(document.getElementsByClassName("itm_tracer_number"));

    function itm_tracer_adj_job_on_change(e) {
        const jobInfo = e.target.value.split('_')
        itm_tracer_adj_cross_qty.value = jobInfo[1]
        itm_tracer_adj_completion_qty.value = jobInfo[2]
        itm_tracer_cross_qty_before.value = jobInfo[1]
        itm_tracer_completion_qty_before.value = jobInfo[2]
        itm_tracer_completion_spid.value = jobInfo[3]
        itm_tracer_completion_qty_lot.value = jobInfo[4]
    }

    function itm_tracer_adj_nik_e_keypress(e) {
        if(e.key === 'Enter') {
            const nik = e.target.value.trim()

            if(nik.length <=4) {
                alertify.error('Please input a valid NIK')
                return false
            }

            const data = {
                nik : nik
            }

            $.ajax({
                type: "GET",
                url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>employee/name-from-nik",
                data: data,
                dataType: "json",
                success: function (response) {
                    itm_tracer_adj_name.value = response.data.MSTEMP_FNM
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    e.target.disabled = false
                }
            });
        }
    }

    function itm_tracer_adj_btn_save_onclick(pThis) {
        const doc = itm_tracer_adj_doc.value.trim()
        const job = itm_tracer_adj_job.value.split('_')[0]
        const nik = itm_tracer_adj_nik.value.trim()
        const name = itm_tracer_adj_name.value.trim()
        const cross_qty = numeral(itm_tracer_adj_cross_qty.value.replace(/,/g, '')).value() ?? 0
        const completion_qty = numeral(itm_tracer_adj_completion_qty.value.replace(/,/g, '')).value() ?? 0
        const lot_qty = numeral(itm_tracer_completion_qty_lot.value.replace(/,/g, '')).value() ?? 0

        const isCrossQtyChanged = itm_tracer_cross_qty_before.value != cross_qty
        const isCompletionQtyChanged = itm_tracer_completion_qty_before.value != completion_qty

        if(!isCrossQtyChanged && !isCompletionQtyChanged) {
            alertify.message('Nothing updated')
            return
        }
        
        if(lot_qty<cross_qty || lot_qty<completion_qty) {
            alertify.error('Crossed-Check Qty or Completion Qty cannot be greater than Lot Qty')
            return
        }

        if(doc.length < 3) {
            alertify.error('Please input WO number')
            return
        }
        if(job === '_0_0') {
            alertify.error('Please select job number')
            return
        }
        if(nik.length < 5) {
            alertify.error('Please input NIK')
            return
        }
        if(name.length < 3) {
            alertify.error('Please input name')
            return
        }
        if(cross_qty.length < 0) {
            alertify.error('Please input Crossed-Check Qty')
            return
        }
        if(completion_qty.length < 0) {
            alertify.error('Please input Completion Qty')
            return
        }

        const data = {
            doc: doc,
            spid: itm_tracer_completion_spid.value,
            job: job,
            nik: nik,
            name: name,
            cross_qty: cross_qty,
            completion_qty: completion_qty,
            cross_qty_before: itm_tracer_cross_qty_before.value,
            completion_qty_before: itm_tracer_completion_qty_before.value
        }

        pThis.disabled = true
        $.ajax({
            type: "PUT",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>production/adjust-qty",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
                itm_tracer_adj_clears()
            }, error: function(xhr, xopt, xthrow) {
                pThis.disabled = false
                alertify.error(xthrow)
            }
        });
    }

    function itm_tracer_adj_clears() {
        itm_tracer_adj_doc.value = ''
        itm_tracer_adj_job.value = '_0_0'
        itm_tracer_adj_cross_qty.value = ''
        itm_tracer_adj_completion_qty.value = ''
        itm_tracer_adj_nik.value = ''
        itm_tracer_adj_name.value = ''
    }
</script>