<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }   
</style>
<div style="padding: 10px">
    <div class="container-fluid" id="rm_in_fg_container">        
        <div class="row" id="rm_in_fg_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Item Code</label>                    
                    <input type="text" class="form-control" id="rm_in_fg_itemcode" maxlength="26">
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Lot Number</label>                    
                    <input type="text" class="form-control" id="rm_in_fg_lotno" maxlength="26">
                </div>
            </div>
        </div>
        <div class="row" id="rm_in_fg_stack3">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rm_in_fg_btn_gen" onclick="rm_in_fg_load_data()">Search</button>
                    <button title="Copy to clipboard" id="rm_in_fg_btn_copy" onclick="rm_in_fg_btn_copy_eC()" class="btn btn-success" ><i class="fas fa-clipboard"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rm_in_fg_ck" checked>
                    <label class="form-check-label" for="flexCheckChecked">
                        Consider FG's stock
                    </label>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rm_in_fg_divku">
                    <table id="rm_in_fg_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th class="text-end" colspan="4">Total</th>
                                <th class="text-end" id="rm_in_fg_lbltotal"></th>
                                <th class="text-end"></th>
                            </tr>
                            <tr class="second">
                                <th class="align-middle">ID</th>
                                <th class="align-middle">Assy Code</th>
                                <th class="align-middle">Assy Name</th>
                                <th class="text-center">WO Number</th>
                                <th class="text-end">Qty</th>
                                <th>Location</th>
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
    function rm_in_fg_btn_copy_eC() {
        cmpr_selectElementContents(document.getElementById('rm_in_fg_tbl'))
        document.execCommand("copy");
        alertify.message("Copied");
    }
    $("#rm_in_fg_divku").css('height', $(window).height()       
    -document.getElementById('rm_in_fg_stack2').offsetHeight
    -document.getElementById('rm_in_fg_stack3').offsetHeight
    -100)
    function rm_in_fg_load_data() {
        const itemcd = document.getElementById('rm_in_fg_itemcode')
        const itemlot = document.getElementById('rm_in_fg_lotno').value.trim()
        const btn = document.getElementById('rm_in_fg_btn_gen')
        const considerFGStock = document.getElementById('rm_in_fg_ck').checked ? '1' : '0'
        if(itemcd.value.trim().length==0) {
            alertify.message('item code could not be empty')
            return
        }
        btn.disabled = true
        btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        document.getElementById('rm_in_fg_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('rm_in_fg_lbltotal').innerHTML = ''
        $.ajax({
            type: "POST",
            url: "<?=base_url('SER/search_rm_in_fg')?>",
            data: {itemCD: itemcd.value.trim(), itemLOT: itemlot, stock: considerFGStock},
            dataType: "JSON",
            success: function (response) {
                btn.innerHTML = 'Search'
                btn.disabled = false
                const ttlrows = response.data.length
                if (ttlrows) {
                    let mydes = document.getElementById("rm_in_fg_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rm_in_fg_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rm_in_fg_tbl")
                    const lbltotal = myfrag.getElementById('rm_in_fg_lbltotal')
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let totalqty = 0
                    for (let i = 0; i<ttlrows; i++){ 
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.title = "ID"
                        newcell.innerHTML = response.data[i].ITH_SER
                        newcell = newrow.insertCell(1);
                        newcell.title = "Assy Code"
                        newcell.innerHTML = response.data[i].ITH_ITMCD
                        newcell = newrow.insertCell(2);
                        newcell.title = "Assy Name"
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(3);
                        newcell.title = "WO Number"
                        newcell.innerHTML = response.data[i].SERD2_JOB
                        newcell = newrow.insertCell(4);
                        newcell.title = "Qty"
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].STKQTY).format(',')
                        newcell = newrow.insertCell(5);
                        newcell.title = "Location"
                        newcell.innerHTML = response.data[i].ITH_WH
                        totalqty+=Number(response.data[i].STKQTY)
                    }
                    lbltotal.innerHTML = numeral(totalqty).format(',')
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                } else {
                    alertify.message(`the data is not found`)
                }
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        })
    }
</script>