<style type="text/css">
    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="rhistorycustoms_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="rhistorycustoms_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >To</span>
                    <input type="text" class="form-control" id="rhistorycustoms_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Search by</span>
                    <select class="form-select" id="rhistorycustoms_wh_seachby" onchange="rhistorycustoms_wh_seachby_eChange()">
                        <option value="itm">Item Code</option>
                        <option value="aju">Nomor Aju</option>
                        <option value="daf">Nomor Daftar</option>
                    </select>
                    <input type="text" class="form-control" id="rhistorycustoms_txt_assy">
                </div>         
            </div>
        </div>
       
        <div class="row" id="rhistorycustoms_stack2">                        
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rhistorycustoms_btn_gen" onclick="rhistorycustoms_btn_gen_eCK()">Search</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rhistorycustoms_divku">
                    <table id="rhistorycustoms_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle text-center">Incoming/Delivery Date</th>
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Item Name</th>                                
                                <th colspan="2" class="align-middle text-center">Nomor</th>
                                <th rowspan="2" class="align-middle text-center">Document</th>
                                <th colspan="3" class="text-center">QTY</th>                          
                            </tr>
                            <tr class="second">
                                <th class="text-center">Aju</th>
                                <th class="text-center">Pendaftaran</th>
                                <th class="text-center">IN</th>
                                <th class="text-center">OUT</th>
                                <th class="text-center">BALANCE</th>
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
<div class="modal fade" id="rhistorycustoms_TXLIST">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Detail Transaction List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <input type="hidden" id="rhistorycustoms_h_doc_inc">
        <input type="hidden" id="rhistorycustoms_h_doc_item">
        <input type="hidden" id="rhistorycustoms_h_doc_out">
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rhistorycustoms_tblitm_div">
                        <table id="rhistorycustoms_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:80%">
                            <thead class="table-light">
                                <tr>
                                    <th>Document no</th>
                                    <th>Item Code</th>
                                    <th>Qty</th>
                                    <th>ID</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">            
            <button type="button" class="btn btn-danger btn-sm" id="rhistorycustoms_btn_cancelbook" onclick="rhistorycustoms_btn_cancelbook_eCK()">Cancel the transaction</button>
        </div>
      </div>
    </div>
</div>
<script>
    function rhistorycustoms_btn_cancelbook_eCK(){
        alertify.message('this function is not available')
        return
    }
    function rhistorycustoms_wh_seachby_eChange(){
        document.getElementById('rhistorycustoms_txt_assy').focus()
    }
    $("#rhistorycustoms_divku").css('height', $(window).height()
    -document.getElementById('rhistorycustoms_stack1').offsetHeight 
    -document.getElementById('rhistorycustoms_stack2').offsetHeight    
    -100);   
   
    $("#rhistorycustoms_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rhistorycustoms_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rhistorycustoms_txt_dt").datepicker('update', new Date());
    $("#rhistorycustoms_txt_dt2").datepicker('update', new Date());
    function rhistorycustoms_btn_gen_eCK(){
        document.getElementById('rhistorycustoms_btn_gen').disabled=true;
        const dt1 = document.getElementById('rhistorycustoms_txt_dt').value;
        const dt2 = document.getElementById('rhistorycustoms_txt_dt2').value;
        const itmcd = document.getElementById('rhistorycustoms_txt_assy').value;
        const searchby = document.getElementById('rhistorycustoms_wh_seachby').value;
        $("#rhistorycustoms_tbl tbody").empty();
        document.getElementById('rhistorycustoms_btn_gen').innerHTML = "<i class='fas fa-spinner fa-spin'></i>"        
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/gettxhistory_customs')?>",
            data: {initemcode :itmcd ,indate1: dt1, indate2: dt2, searchby: searchby},
            dataType: "json",
            success: function (response) {
                document.getElementById('rhistorycustoms_btn_gen').disabled=false;
                document.getElementById('rhistorycustoms_btn_gen').innerText = "Search"
                if(response.status[0].cd!='0'){                    
                    const ttlrows = response.data.length;                    
                    let mydes = document.getElementById("rhistorycustoms_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rhistorycustoms_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rhistorycustoms_tbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let mhead = false;
                    for(let i=0; i< ttlrows; i++){                        
                        newrow = tableku2.insertRow(-1);
                        if(response.data[i].HEADER=='1'){
                            newrow.classList.add("table-primary");
                            mhead = true;
                        } else {  
                            mhead = false;
                        }                      
                        newcell = newrow.insertCell(0);
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].IODATE
                        newcell = newrow.insertCell(1);            
                        newText = document.createTextNode(response.data[i].RPSTOCK_ITMNUM);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].MITM_ITMD1);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('text-center')                        
                        newcell.innerHTML = response.data[i].AJU
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].DAFTAR
                        newcell = newrow.insertCell(5)                        
                        newcell.innerHTML = response.data[i].DOC
                        if(response.data[i].DOC){
                            if(response.data[i].DOC.includes('MIG') || response.data[i].DOC.includes('ADJ_OUT')) { 
                                if(!mhead){
                                    if(response.data[i].BCTYPE!='40'){
                                        newcell.style.cssText = 'cursor:pointer'
                                        newcell.onclick = () => {
                                            rhistorycustoms_showdetail({incdoc: response.data[i].RPSTOCK_DOC
                                                , incitem: response.data[i].ITMCD
                                                , outdoc: response.data[i].DOC})
                                        }
                                    }
                                }
                            }
                        }
                        newcell = newrow.insertCell(6);
                        if(!mhead){newcell.title = "INC:"}
                        newcell.style.cssText = "text-align:right";
                        newText = document.createTextNode(response.data[i].INCQTY == '' ? '' : numeral(response.data[i].INCQTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);                        
                        newcell.style.cssText = "text-align:right";
                        newText = document.createTextNode(response.data[i].OUTQTY=='' ? '' : numeral(response.data[i].OUTQTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(8);
                        if(!mhead){
                            newcell.title = "BAL";
                        } else {
                            newcell.title = "BAL Bef.";
                        }
                        newcell.style.cssText = "text-align:right";
                        newText = document.createTextNode(numeral(response.data[i].BAL).format(','));
                        newcell.appendChild(newText);                     
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {                    
                    alertify.message(response.status[0].msg);
                }
            }, error : function(xhr, xopt, xthrow){
                document.getElementById('rhistorycustoms_btn_gen').disabled=false;
                document.getElementById('rhistorycustoms_btn_gen').innerText = "Search"
                alertify.error(xthrow);
            }
        })
    }   

    function rhistorycustoms_showdetail(pdata){
        document.getElementById('rhistorycustoms_h_doc_inc').value = pdata.incdoc
        document.getElementById('rhistorycustoms_h_doc_item').value = pdata.incitem
        document.getElementById('rhistorycustoms_h_doc_out').value = pdata.outdoc
        $.ajax({
            type: "POST",
            url: "<?=base_url('ITH/bcstock')?>",
            data: {incdoc: pdata.incdoc, incitem: pdata.incitem, outdoc: pdata.outdoc},
            dataType: "json",
            success: function (response) {
                const ttlrows = response.data.length;                    
                let mydes = document.getElementById("rhistorycustoms_tblitm_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rhistorycustoms_tblitm");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rhistorycustoms_tblitm");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let mhead = false;
                for(let i=0; i< ttlrows; i++){                        
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].RPSTOCK_DOC
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].RPSTOCK_ITMNUM
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].RPSTOCK_QTY
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].id
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                if(ttlrows>0){
                    $("#rhistorycustoms_TXLIST").modal('show')
                }
            }, error : function(xhr, xopt, xthrow){                
                alertify.error(xthrow);
            }
        })
    }
</script>