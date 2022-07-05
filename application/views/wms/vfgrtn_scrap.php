<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Serah Terima Dok.</span>
                    <input type="text" class="form-control" id="scrfg_rtn_txt_serahterima" placeholder="Autonumber"  readonly>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scrfg_rtn_MOD_serahterima"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Date</span>
                    <input type="text" class="form-control" id="scrfg_rtn_txt_date" readonly>
                </div>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary" id="scrfg_rtn_btn_new" onclick="scrfg_rtn_btn_plus_e_click()"><i class="fas fa-plus"></i></button>
                    <button type="button" class="btn btn-warning" id="scrfg_rtn_btn_delete" onclick="scrfg_rtn_btn_delete_e_click()"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="scrfg_rtn_divext">
                    <table id="scrfg_rtn_tblext" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Assy Code</th>
                                <th class="text-end">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary" id="scrfg_rtn_btn_new" onclick="scrfg_rtn_btn_new_e_click()"><i class="fas fa-file"></i></button>
                    <button type="button" class="btn btn-primary" id="scrfg_rtn_btn_save" onclick="scrfg_rtn_btn_save_e_click()"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>     
    </div>
</div>
<div class="modal fade" id="scrfg_rtn_MOD_balance">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">               
                <div class="col-md-12 mb-1 text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-primary" id="scrfg_rtn_btn_refreshbalance" onclick="scrfg_rtn_btn_refreshbalance_eCK()"><i class="fas fa-arrow-rotate-right"></i></button>                        
                    </div>
                </div>                
            </div>           
            <div class="row">
                <div class="col" id="scrfg_rtn_tbl_balance_divku">
                    <table id="scrfg_rtn_tbl_balance" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="align-middle">RA</th>
                                <th rowspan="2" class="align-middle">ID</th>
                                <th rowspan="2" class="align-middle">Assy Code</th>
                                <th colspan="2" class="text-center">Qty</th>
                            </tr>
                            <tr>
                                <th class="text-end">Balance</th>
                                <th class="text-end">Conform</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">            
            <button type="button" class="btn btn-primary btn-sm" id="scrfg_rtnbtn_apply" onclick="scrfg_rtnbtn_apply_eCK()">Apply</button>
        </div>
      </div>
    </div>
</div>

<script>
    $("#scrfg_rtn_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#scrfg_rtn_txt_date").datepicker('update', new Date())

    function scrfg_rtn_btn_plus_e_click(){
        $("#scrfg_rtn_MOD_balance").modal('show')
    }

    function scrfg_rtnbtn_apply_eCK(){
        let mydes = document.getElementById("scrfg_rtn_tbl_balance_divku");
        let myfrag = document.createDocumentFragment();
        let mtabel = document.getElementById("scrfg_rtn_tbl_balance");
        let cln = mtabel.cloneNode(true);
        myfrag.appendChild(cln);                    
        let tabell = myfrag.getElementById("scrfg_rtn_tbl_balance")
        let mtbltr = tabell.getElementsByTagName('tbody')[0]
        let ttlrows = mtbltr.getElementsByTagName('tr').length;

        let balanceQT = 0    
        let actualQT = 0
        let rs = []
        for(let i=0; i < ttlrows; i++){            
            balanceQT = numeral(mtbltr.rows[i].cells[3].innerText).value()
            actualQT = numeral(mtbltr.rows[i].cells[4].innerText).value()
            if(actualQT>balanceQT){
                mtabel.getElementsByTagName('tbody')[0].rows[i].cells[4].focus()
                alertify.warning('conform could not be greater than balance')                
                break
            } else {
                if(actualQT>0){
                    rs.push({
                        id: mtbltr.rows[i].cells[1].innerText,
                        assy: mtbltr.rows[i].cells[2].innerText,
                        qty: mtbltr.rows[i].cells[4].innerText
                    })
                }
            }
        }
        ttlrows = rs.length
        if(ttlrows>0){
            if(confirm("Are you sure ?")){                
                mydes = document.getElementById("scrfg_rtn_divext");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("scrfg_rtn_tblext");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                tabell = myfrag.getElementById("scrfg_rtn_tblext");                    
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText
                tableku2.innerHTML=''
                for (let i in rs){
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = rs[i].id
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = rs[i].assy
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = rs[i].qty
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
                $("#scrfg_rtn_MOD_balance").modal('hide')
            }
        }
    }

    function scrfg_rtn_btn_refreshbalance_eCK(){
        const btnreff = document.getElementById('scrfg_rtn_btn_refreshbalance')
        btnreff.disabled = true
        btnreff.innerHTML = `<i class="fas fa-arrow-rotate-right fa-spin"></i>`
        $.ajax({
            type: "POST",
            url: "<?=base_url('ITH/balanceRA')?>",
            data: {},
            dataType: "json",
            success: function (response) {
                btnreff.disabled = false
                btnreff.innerHTML = `<i class="fas fa-arrow-rotate-right"></i>`
                const ttlrows = response.data.length;
                let mydes = document.getElementById("scrfg_rtn_tbl_balance_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("scrfg_rtn_tbl_balance");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("scrfg_rtn_tbl_balance");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].ITH_DOC
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].ITH_SER
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].ITMCD
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].BALQT).format(',')
                    newcell = newrow.insertCell(4)
                    newcell.onkeypress = (e) => {
                        if(e.key==='Enter'){                            
                            e.preventDefault()
                        }
                    }
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                btnreff.disabled = false
                btnreff.innerHTML = `<i class="fas fa-arrow-rotate-right"></i>`
            }
        })
    }

</script>