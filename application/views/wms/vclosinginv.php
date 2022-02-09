<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="clsinv_stack1">
            <div class="col-md-7 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Warehouse</span> 
                    <select class="form-select" id="clsinv_wh">
                        <option value="-">Choose</option>
                        <?php 
                            $dwh = '';
                            foreach($lwh as $r){
                                $dwh .= "<option value='$r[MSTLOCG_ID]'>$r[MSTLOCG_NM] ($r[MSTLOCG_ID])</option>";
                            }
                            echo $dwh;
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="clsinv_date" readonly>
                </div>
            </div>
        </div> 
        <div class="row" id="clsinv_stack2">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="clsinv_btn_gen">Generate</button>
                    <button class="btn btn-outline-primary" type="button" id="clsinv_btn_adj" onclick="clsinv_btn_adj_eC()">Adjust</button>
                    <button class="btn btn-outline-primary" type="button" id="clsinv_btn_save" onclick="clsinv_btn_save_eC()">Save</button>
                    <button class="btn btn-outline-primary" type="button" id="clsinv_btn_copy" title="copy the table below" onclick="clsinv_btn_copy_eC()"><i class="fas fa-copy"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <span id="clsinv_tbl_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="clsinv_divku">
                    <table id="clsinv_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:81%">
                        <thead class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">Total</th>
                                <th class="text-end" id="clsinv_ttl_wms"></th>
                                <th class="text-end" id="clsinv_ttl_mega"></th>
                                <th class="text-end text-danger" id="clsinv_ttl_discrepancy"></th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="align-middle">No</th>                                
                                <th colspan="2" class="align-middle text-center">Item Id</th>
                                <th rowspan="2" class="align-middle">Item Name</th>                                
                                <th colspan="3" class="text-center">QTY</th>
                            </tr>
                            <tr>
                                <th class="text-center">WMS</th>
                                <th class="text-center">MEGA</th>                                
                                <th class="text-end">WMS</th>
                                <th class="text-end">MEGA</th>
                                <th class="text-end">Discrepancy</th>
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
    function clsinv_btn_save_eC() {
        const whcode = document.getElementById('clsinv_wh')
        const mdate = document.getElementById('clsinv_date')
        if(whcode.value === '-') {
            alertify.message('Please select a warehouse')
            whcode.focus()
            return
        }
        if(mdate.value.length === 0) {
            alertify.message('Please select date')
            mdate.focus()
            return
        }
        if( !confirm("Are you sure ?")) {
            return
        }
        const mpin = prompt("PIN")
        if ( mpin !== '') {
            const btnsave = document.getElementById('clsinv_btn_save')
            btnsave.innerHTML = 'Please wait...'
            btnsave.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/saveadjust_base_mega')?>",
                data: {inwh: whcode.value, indate: mdate.value, inpin : mpin},
                dataType: "JSON",
                success: function (response) {
                    btnsave.innerHTML = 'Save'
                    btnsave.disabled = false
                    if(response[0]) {
                        alertify.message(response[0].msg)
                        document.getElementById('clsinv_tbl_lblinfo').innerHTML = response[0].msg
                    } else {
                        if(response.status[0].cd === '0') {
                            alertify.message(response.status[0].msg)
                        } else {
                            alertify.success(response.status[0].msg)
                        }
                        document.getElementById('clsinv_tbl_lblinfo').innerHTML = response.status[0].msg
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('clsinv_tbl_lblinfo').innerHTML = xthrow
                    btnsave.innerHTML = 'Save'
                    btnsave.disabled = false
                }
            })
        } else {
            alertify.message('PIN is required')
        }
    }
    function clsinv_btn_adj_eC() {
        const whcode = document.getElementById('clsinv_wh')
        const mdate = document.getElementById('clsinv_date')
        if(whcode.value === '-') {
            alertify.message('Please select a warehouse')
            whcode.focus()
            return
        }
        if(mdate.value.length === 0) {
            alertify.message('Please select date')
            mdate.focus()
            return
        }
        if( !confirm("Are you sure ?")) {
            return
        }
        const mpin = prompt("PIN")
        if ( mpin !== '') {            
            document.getElementById('clsinv_tbl_lblinfo').innerHTML = 'Please wait...'
            $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/adjust_base_mega')?>",
                data: {inwh: whcode.value, indate: mdate.value, inpin : mpin},
                dataType: "JSON",
                success: function (response) {
                    if(response[0]) {
                        alertify.message(response[0].msg)
                        document.getElementById('clsinv_tbl_lblinfo').innerHTML = response[0].msg
                    } else {
                        if(response.status[0].cd === '0') {
                            alertify.message(response.status[0].msg)
                        } else {
                            alertify.success(response.status[0].msg)
                        }
                        document.getElementById('clsinv_tbl_lblinfo').innerHTML = response.status[0].msg
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('clsinv_tbl_lblinfo').innerHTML = xthrow
                }
            })
        } else {
            alertify.message('PIN is required')
        }
    }
    $("#clsinv_divku").css('height', $(window).height()   
    -document.getElementById('clsinv_stack1').offsetHeight 
    -document.getElementById('clsinv_stack2').offsetHeight    
    -100);
    $("#clsinv_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#clsinv_btn_gen").click(function (e) {
        document.getElementById('clsinv_btn_gen').disabled = true
        const mwh = document.getElementById('clsinv_wh').value;
        const mdate = document.getElementById('clsinv_date');
        
        if(mwh=='-'){
            document.getElementById('clsinv_wh').focus();
            alertify.message('Please select warehouse first !');
            return;
        }
        if(mdate.value.length==0){
            alertify.message("Select date first");
            return;
        }
        $("#clsinv_tbl tbody").empty()
        document.getElementById('clsinv_tbl_lblinfo').innerHTML = 'Please wait...'
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/compareinventory')?>",
            data: {inwh: mwh, indate: mdate.value},
            dataType: "json",
            success: function (response) {
                document.getElementById('clsinv_btn_gen').disabled = false
                document.getElementById('clsinv_tbl_lblinfo').innerHTML = ''
                let ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("clsinv_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("clsinv_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    let tabell = myfrag.getElementById("clsinv_tbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;  
                    let wmsqty, megaqty, balqty
                    let ttlwms =0
                    let ttlmega = 0
                    let ttlbal = 0

                    for (let i = 0; i<ttlrows; i++){
                        wmsqty = numeral(response.data[i].STOCKQTY).value()
                        megaqty = numeral(response.data[i].MGAQTY).value()
                        balqty = wmsqty-megaqty
                        ttlwms += wmsqty
                        ttlmega += megaqty
                        ttlbal += balqty
                        
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode((i+1));
                        newcell.appendChild(newText);                 
                        newcell = newrow.insertCell(1);                        
                        newcell.innerHTML = response.data[i].ITH_ITMCD

                        newcell = newrow.insertCell(2)        
                        newcell.innerHTML = response.data[i].ITRN_ITMCD

                        newcell = newrow.insertCell(3)                    
                        newcell.innerHTML = response.data[i].MITM_ITMD1 ? response.data[i].MITM_ITMD1 : response.data[i].MGMITM_ITMD1
                        
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = numeral(response.data[i].STOCKQTY).format('0,0.00')
                        newcell.style.cssText = 'text-align: right'
                        newcell.title = "WMS"

                        newcell = newrow.insertCell(5)  
                        newcell.innerHTML = numeral(response.data[i].MGAQTY).format('0,0.00')
                        newcell.style.cssText = 'text-align: right'  
                        newcell.title = "MEGA"

                        newcell = newrow.insertCell(6)
                        newcell.innerHTML = numeral(balqty).format(',')
                        newcell.style.cssText = 'text-align: right'
                        newcell.title = "Discrepancy"
                        if(balqty!=0) {
                            newcell.classList.add('text-danger')
                        }
                    }
                    myfrag.getElementById('clsinv_ttl_wms').innerHTML = numeral(ttlwms).format(',')
                    myfrag.getElementById('clsinv_ttl_mega').innerHTML = numeral(ttlmega).format(',')
                    myfrag.getElementById('clsinv_ttl_discrepancy').innerHTML = numeral(ttlbal).format(',')
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }
            }, error: function(xhr, xopt, xthrow){
                document.getElementById('clsinv_btn_gen').disabled = false
                alertify.error(xthrow);
                document.getElementById('clsinv_tbl_lblinfo').innerHTML = xthrow
            }
        });
    });

    function clsinv_btn_copy_eC(){
        cmpr_selectElementContents(document.getElementById('clsinv_tbl'))
        document.execCommand("copy")
        alertify.message("Copied")
    }
</script>