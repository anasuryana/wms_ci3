<style>
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 25px;
    }  
</style>

<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="rhistory_parent_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="rhistory_parent_txt_dt" readonly>
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Warehouse</span>
                    <select class="form-select" id="rhistory_parent_cmb_wh" ><?=$lwh?></select>
                </div>
            </div>            
        </div>
       
        <div class="row" id="rhistory_parent_stack2">
            <div class="col-md-4 mb-1">                       
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="rhistory_parent_txt_assy">                    
                </div>         
            </div>
            
            <div class="col-md-4 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rhistory_parent_btn_gen">Search</button>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="rhistory_parent_lblinfo" class="badge bg-info"></span>               
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rhistory_parent_divku">
                    <table id="rhistory_parent_tbl" class="table table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle text-center">Date</th>
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Warehouse</th>
                                <th  class="text-center" colspan="2">IO Qty</th>
                                <th  class="text-center" colspan="2">Balance Qty</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center">MEGA</th>
                                <th class="text-center">WMS</th>
                                <th class="text-center">MEGA</th>
                                <th class="text-center">WMS</th>
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
<div class="modal fade" id="rhistory_parent_MOD_detail">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Detail Transaction @ <span id="rhistory_parent_selected_date"></span></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">                            
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rhistory_parent_tbldetail_parent_div">
                        <table id="rhistory_parent_tbldetail_parent" class="table table-sm table-striped table-bordered table-hover caption-top">
                            <caption>MEGA</caption>
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Document</th>
                                    <th class="text-end">QTY</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rhistory_parent_tbldetail_child_div">
                        <table id="rhistory_parent_tbldetail_child" class="table table-sm table-striped table-bordered table-hover caption-top">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Document</th>
                                    <th class="text-end">QTY</th>
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
    </div>
</div>
<script>
    $("#rhistory_parent_divku").css('height', $(window).height()   
    -document.getElementById('rhistory_parent_stack1').offsetHeight 
    -document.getElementById('rhistory_parent_stack2').offsetHeight    
    -100);
    // $("#rhistory_parent_divku").css('height', $(window).height()*63/100);
    $("#rhistory_parent_btn_xls").click(function (e) { 
        let dt1 = document.getElementById('rhistory_parent_txt_dt').value;        
        let itmcd = document.getElementById('rhistory_parent_txt_assy').value;
        let wh = document.getElementById('rhistory_parent_cmb_wh').value;              
        
        Cookies.set('CKPSI_DDT1', dt1, {expires:365});        
        Cookies.set('CKPSI_DITEMCD', itmcd, {expires:365});
        Cookies.set('CKPSI_DWH', wh, {expires:365});
        window.open("<?=base_url('ITH/gettxhistory_to_xls')?>",'_blank');
    });
    $("#rhistory_parent_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    }); 
    $("#rhistory_parent_txt_dt").datepicker('update', new Date());    

    $("#rhistory_parent_btn_gen").click(function (e) { 
        let dt1 = document.getElementById('rhistory_parent_txt_dt').value;        
        let itmcd = document.getElementById('rhistory_parent_txt_assy').value.trim();        
        let wh = document.getElementById('rhistory_parent_cmb_wh').value;
        if(itmcd.length==0){
            alertify.warning('item code is required')
            return 
        }
        document.getElementById('rhistory_parent_btn_gen').disabled=true;
        $("#rhistory_parent_tbl tbody").empty();
        document.getElementById('rhistory_parent_lblinfo').innerText='Please wait...';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/gettxhistory_parent')?>",
            data: {initemcode :itmcd ,indate1: dt1, inwh: wh},
            dataType: "json",
            success: function (response) {
                document.getElementById('rhistory_parent_btn_gen').disabled=false;
                if(response.status[0].cd!='0'){
                    let wh = document.getElementById('rhistory_parent_cmb_wh').value;
                    let ttlrows = response.data.length;
                    document.getElementById('rhistory_parent_lblinfo').innerText=ttlrows +' row(s) found';
                    let mydes = document.getElementById("rhistory_parent_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rhistory_parent_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rhistory_parent_tbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let mhead = false;
                    let rowClass = ''
                    for(let i=0; i< ttlrows; i++){
                        rowClass = response.data[i].MGABAL!=response.data[i].WBAL ? 'table-warning' : 'table-success'                        
                        newrow = tableku2.insertRow(-1);
                        newrow.classList.add(rowClass)
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.data[i].ISUDT
                        if(response.data[i].ISUDT.length>2){
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.ondblclick = () => {
                                document.getElementById('rhistory_parent_selected_date').innerHTML = response.data[i].ISUDT
                                rhistory_parent_get_tx_perdate(response.data[i].ISUDT,itmcd, wh )
                                $("#rhistory_parent_MOD_detail").modal('show')
                            }
                        }
                        newcell = newrow.insertCell(1)                        
                        newcell.innerHTML = response.data[i].ITRN_ITMCD
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = wh
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].MGAQTY).format(',')
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].WQT).format(',')
                        newcell = newrow.insertCell(5);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].MGABAL).format(',')
                        newcell = newrow.insertCell(6);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].WBAL).format(',')
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('rhistory_parent_lblinfo').innerText=' not found ';
                    alertify.message(response.status[0].msg);
                }
            }, error : function(xhr, xopt, xthrow){
                document.getElementById('rhistory_parent_btn_gen').disabled=false;
                alertify.error(xthrow);
            }
        });
    });

    function rhistory_parent_get_tx_perdate(pdate, pItem,pWH){
        $.ajax({
            type: "GET",
            url: "<?=base_url('ITH/transaction')?>",
            data: {date: pdate, item: pItem, location: pWH},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.parent.length;                
                let mydes = document.getElementById("rhistory_parent_tbldetail_parent_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rhistory_parent_tbldetail_parent");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rhistory_parent_tbldetail_parent");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';                                
                for(let i=0; i< ttlrows; i++){                    
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.parent[i].ITRN_ISUDT                   
                    newcell = newrow.insertCell(1)                        
                    newcell.innerHTML = response.parent[i].ITRN_DOCNO
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.parent[i].QTY).format(',')
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                ttlrows = response.child.length
                mydes = document.getElementById("rhistory_parent_tbldetail_child_div");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("rhistory_parent_tbldetail_child");
                cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                tabell = myfrag.getElementById("rhistory_parent_tbldetail_child")
                tableku2 = tabell.getElementsByTagName("tbody")[0]                
                tableku2.innerHTML=''
                for(let i=0; i< ttlrows; i++){                    
                    newrow = tableku2.insertRow(-1);                    
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.child[i].ITH_DATEC
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.child[i].ITH_DOC
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.child[i].ITH_QTY).format(',')
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error : function(xhr, xopt, xthrow){                
                alertify.error(xthrow);
            }
        })
    }
</script>