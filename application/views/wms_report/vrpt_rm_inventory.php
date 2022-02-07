<div style="padding: 10px">
    <div class="container-fluid" id="rminventory_container">
        <div class="row" id="rminventory_stack1">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="rminventory_bisgrup">
                        <option value="-">All</option>
                        <?=$lbg?>
                    </select>                                   
                </div>
            </div>
        </div>
        <div class="row" id="rminventory_stack2">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Item Code</label>                    
                    <input type="text" class="form-control" id="rminventory_itemcode" maxlength="26">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Rack</label>                    
                    <input type="text" class="form-control" id="rminventory_rack" maxlength="26" title="** as separator">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Lot Number</label>                    
                    <input type="text" class="form-control" id="rminventory_lotno" maxlength="26">
                </div>
            </div>
        </div>
        <div class="row" id="rminventory_stack3">            
            <div class="col-md-5 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rminventory_btn_gen" onclick="rminventory_load_data()"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-success" type="button" id="rminventory_btn_export" onclick="rminventory_btn_export_e_click()" title="export to xls file"><i class="fas fa-file-excel"></i></button>
                    <button class="btn btn-outline-warning" type="button" id="rminventory_btn_delete" onclick="rminventory_btn_delete_e_click()" title="delete selected rows"><i class="fas fa-trash"></i></button>                                          
                </div>
            </div>
            <div class="col-md-5 mb-1 text-end">
            <?php 
            if($this->session->userdata('gid')=='ROOT') {
            ?>
                <div class="btn-group btn-group-sm">                    
                    <button class="btn btn-danger" type="button" onclick="rminventory_btn_deleteall_e_click()">Clear All inventory data </button> 
                </div>                
                <?php
                }
                ?>
            </div>
            <div class="col-md-2 mb-1">
                <span id="rminventory_lblinfo" class="badge bg-info"></span>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rminventory_divku">
                    <table id="rminventory_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Item Code</th>
                                <th class="align-middle">Item Name</th>
                                <th class="align-middle">Lot</th>                                
                                <th class="text-end">Qty</th>                                
                                <th class="text-center">PIC</th>
                                <th class="text-center">Time</th>                                
                                <th class="text-center">Location</th>
                                <th class="text-center"><input type="checkbox" id="rminventory_ckall" class="form-check-input"></th>
                            </tr>        
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL ROWS</span>                    
                    <input type="text" class="form-control" id="rminventory_txt_total_row" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL QTY</span>                    
                    <input type="text" class="form-control" id="rminventory_txt_total" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<script>        
    $("#rminventory_divku").css('height', $(window).height()   
    -document.getElementById('rminventory_stack1').offsetHeight 
    -document.getElementById('rminventory_stack2').offsetHeight
    -document.getElementById('rminventory_stack3').offsetHeight
    -130);    
    function rminventory_btn_deleteall_e_click(){
        if(confirm("Are You sure ?")){
            if(confirm("are you really sure want to clear all data?")){
                $.ajax({
                    type: "get",
                    url: "<?=base_url('Inventory/clearRM')?>",
                    dataType: "json",
                    success: function (response) {
                        alertify.message(response.status[0].msg);
                    }, error : function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    }
    function rminventory_btn_delete_e_click(){
        let mtbl = document.getElementById('rminventory_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let aitemcode = [];
        let arack = [];
        let atime = [];

        if(ttlrows>1){                
            for(let i=1;i<ttlrows;i++){
                if(mtbl.rows[i].cells[7].getElementsByTagName('input')[0].checked){
                    aitemcode.push(mtbl.rows[i].cells[0].innerText);
                    atime.push(mtbl.rows[i].cells[5].innerText);
                    arack.push(mtbl.rows[i].cells[6].innerText);
                }
            }
        }
        if(aitemcode.length>0){
            if(aitemcode.length>100){
                alertify.warning('Maximum data that could WMS delete is 100');
                return;
            }
            if(confirm("Are You sure ?")){
                $.ajax({
                    type: "post",
                    url: "<?=base_url('Inventory/deleterm')?>",
                    data: {initem: aitemcode, intime: atime, inrack:arack},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd=='1'){
                            alertify.success( response.status[0].msg );
                        } else {
                            alertify.message(response.status[0].msg);
                        }
                        rminventory_load_data();
                    }, error : function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                        document.getElementById('rminventory_btn_gen').disabled=false;
                    }
                });
            }            
        } else {
            alertify.message('nothing to be deleted');
        }
    }

    function rminventory_btn_export_e_click(){
        if(confirm("export as xls file ?")){
            const itemcode = document.getElementById('rminventory_itemcode').value;
            const rackno = document.getElementById('rminventory_rack').value;
            const lotno = document.getElementById('rminventory_lotno').value;
            const bisgrup = document.getElementById('rminventory_bisgrup').value;
            window.open("<?=base_url('Inventory/export_rm_xls')?>?initemcode="+itemcode+"&inrack="+rackno+"&inlotno="+lotno+"&inbg="+bisgrup,'_blank');
        }
    }

    function rminventory_load_data(){
        const fitemcode =  document.getElementById('rminventory_itemcode').value;
        const frack =  document.getElementById('rminventory_rack').value;
        const flotno =  document.getElementById('rminventory_lotno').value;
        const bisgrup =  document.getElementById('rminventory_bisgrup').value;
        document.getElementById('rminventory_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        document.getElementById('rminventory_btn_gen').disabled=true;
        $.ajax({
            type: "get",
            url: "<?=base_url('Inventory/getlist_rm')?>",
            data:{initemcode: fitemcode, inrack: frack, inlotno: flotno, bisgrup:bisgrup},
            dataType: "json",
            success: function (response) {
                document.getElementById('rminventory_btn_gen').disabled=false;
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rminventory_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rminventory_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("rminventory_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let mckall = myfrag.getElementById("rminventory_ckall");    
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].CQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(response.data[i].CPARTCODE);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].MITM_SPTNO);
                    newcell.appendChild(newText);
                    
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].CLOTNO);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);                  

                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(numeral(response.data[i].CQTY).format('0,0'));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);
                    

                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.data[i].FULLNAME);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].CDATE);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);                                                             

                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].CLOC);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);         

                    newcell = newrow.insertCell(7);
                    newcell.style.cssText= 'text-align:center';                    
                    newText = document.createElement('input');
                    newText.setAttribute('type', 'checkbox');
                    newText.classList.add("form-check-input");
                    newcell.appendChild(newText);                                                  
                }
                let mrows = tableku2.getElementsByTagName("tr");
                mckall.onclick = function(){
                                    for(let x=0;x<mrows.length;x++){
                                        let cktemp = tableku2.rows[x].cells[7].getElementsByTagName('input')[0];
                                        cktemp.checked=mckall.checked;
                                    }
                                };
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('rminventory_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('rminventory_txt_total_row').value = numeral(ttlrows).format('0,0');
                document.getElementById('rminventory_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('rminventory_btn_gen').disabled=false;
            }
        });
    } 
</script>