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
        <div class="row" id="rhistory_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="rhistory_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="rhistory_txt_dt2" readonly>
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Warehouse</span>
                    <select class="form-select" id="rhistory_cmb_wh" ><?=$lwh?></select>
                </div>
            </div>            
        </div>
       
        <div class="row" id="rhistory_stack2">
            <div class="col-md-4 mb-1">                       
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="rhistory_txt_assy">                    
                </div>         
            </div>
            
            <div class="col-md-4 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rhistory_btn_gen">Search</button>    
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export to
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rhistory_btn_xls"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>
                            <li><a class="dropdown-item" href="#" id="rhistory_btn_pdf"><span style="color: Tomato"><i class="fas fa-file-pdf"></i></span> PDF</a></li>
                        </ul>
                    </div>                   
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="rhistory_lblinfo" class="badge bg-info"></span>               
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rhistory_divku">
                    <table id="rhistory_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle text-center">Date</th>
                                <th rowspan="2" class="align-middle">Item Code</th>
                                <th rowspan="2" class="align-middle">Item Name</th>
                                <th rowspan="2" class="align-middle">Warehouse</th>                                
                                <th rowspan="2" class="align-middle">Event</th>
                                <th rowspan="2" class="align-middle text-center">Document</th>
                                <th  class="text-center" colspan="3">QTY</th>                                
                            </tr>
                            <tr class="second">
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
<script>
    $("#rhistory_divku").css('height', $(window).height()   
    -document.getElementById('rhistory_stack1').offsetHeight 
    -document.getElementById('rhistory_stack2').offsetHeight    
    -100);
    // $("#rhistory_divku").css('height', $(window).height()*63/100);
    $("#rhistory_btn_xls").click(function (e) { 
        let dt1 = document.getElementById('rhistory_txt_dt').value;
        let dt2 = document.getElementById('rhistory_txt_dt2').value;
        let itmcd = document.getElementById('rhistory_txt_assy').value;
        let wh = document.getElementById('rhistory_cmb_wh').value;              
        
        Cookies.set('CKPSI_DDT1', dt1, {expires:365});
        Cookies.set('CKPSI_DDT2', dt2, {expires:365});
        Cookies.set('CKPSI_DITEMCD', itmcd, {expires:365});
        Cookies.set('CKPSI_DWH', wh, {expires:365});
        window.open("<?=base_url('ITH/gettxhistory_to_xls')?>",'_blank');
    });
    $("#rhistory_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rhistory_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rhistory_txt_dt").datepicker('update', new Date());
    $("#rhistory_txt_dt2").datepicker('update', new Date());

    $("#rhistory_btn_gen").click(function (e) { 
        document.getElementById('rhistory_btn_gen').disabled=true;
        let dt1 = document.getElementById('rhistory_txt_dt').value;
        let dt2 = document.getElementById('rhistory_txt_dt2').value;
        let itmcd = document.getElementById('rhistory_txt_assy').value;
        let wh = document.getElementById('rhistory_cmb_wh').value;
        $("#rhistory_tbl tbody").empty();
        document.getElementById('rhistory_lblinfo').innerText='Please wait...';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/gettxhistory')?>",
            data: {initemcode :itmcd ,indate1: dt1, indate2: dt2, inwh: wh},
            dataType: "json",
            success: function (response) {
                document.getElementById('rhistory_btn_gen').disabled=false;
                if(response.status[0].cd!='0'){
                    let wh = document.getElementById('rhistory_cmb_wh').value;
                    let ttlrows = response.data.length;
                    document.getElementById('rhistory_lblinfo').innerText=ttlrows +' row(s) found';
                    let mydes = document.getElementById("rhistory_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rhistory_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rhistory_tbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let mhead = false;
                    for(let i=0; i< ttlrows; i++){                        
                        newrow = tableku2.insertRow(-1);
                        if(response.data[i].ITH_FORM==''){
                            newrow.classList.add("table-primary");
                            mhead = true;
                        } else {                           
                            mhead = false;
                        }                      
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.data[i].ITH_DATEKU                        
                        newcell = newrow.insertCell(1)                        
                        newcell.innerHTML = response.data[i].ITH_ITMCD
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].MITM_ITMD1                        
                        newcell = newrow.insertCell(3);
                        newcell.innerHTML = wh                        
                        newcell = newrow.insertCell(4);
                        newcell.innerHTML = response.data[i].ITH_FORM                        
                        newcell = newrow.insertCell(5);
                        if(!mhead){newcell.title = response.data[i].ITH_REMARK}                        
                        newcell.innerHTML = response.data[i].ITH_DOC
                        newcell = newrow.insertCell(6);
                        if(!mhead){newcell.title = "INC";}
                        newcell.style.cssText = "text-align:right"  
                        newcell.innerHTML = response.data[i].INCQTY == '' ? '' : numeral(response.data[i].INCQTY).format(',')
                        newcell = newrow.insertCell(7);
                        if(!mhead){newcell.title = "OUT";}
                        newcell.style.cssText = "text-align:right"  
                        newcell.innerHTML = response.data[i].OUTQTY=='' ? '' : numeral(response.data[i].OUTQTY).format(',')
                        newcell = newrow.insertCell(8);
                        if(!mhead){
                            newcell.title = "BAL";
                        } else {
                            newcell.title = "BAL Bef.";
                        }
                        newcell.style.cssText = "text-align:right"  
                        newcell.innerHTML = numeral(response.data[i].ITH_BAL).format(',')       
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('rhistory_lblinfo').innerText=' not found ';
                    alertify.message(response.status[0].msg);
                }
            }, error : function(xhr, xopt, xthrow){
                document.getElementById('rhistory_btn_gen').disabled=false;
                alertify.error(xthrow);
            }
        });
    });
</script>