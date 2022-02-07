<div style="padding: 10px">
    <div class="container-xxl">                     
        <div class="row">            
            <div class="col-md-12 mb-1 text-center">                                       
                <button class="btn btn-sm btn-primary" type="button" id="runscan_qcwh_btn_gen"><i class="fas fa-sync"></i></button>                                    
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="runscan_qcwh_divku">
                    <table id="runscan_qcwh_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">Lot</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-end">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">PIC</th>
                                <th  class="text-center">Time</th>                                
                                <th  class="align-middle">Business</th>
                                <th  class="align-middle">Remark</th>
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
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="runscan_qcwh_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<div class="modal fade" id="runscan_qcwh_mod_REMARK">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Remark</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <label class="input-group-text"><i class="fas fa-comments"></i></label>                        
                        <input type="text" class="form-control" id="runscan_qcwh_txt_remark" >    
                        <input type="hidden" id="runscan_qcwh_txt_reff">                    
                    </div>
                </div>                
            </div>          
            <div class="row">
                <div class="col text-center mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="runscan_qcwh_btnsave" onclick="runscan_qcwh_e_saveremark()"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>            
        </div>             
      </div>
    </div>
</div>
<script>
    $("#runscan_qcwh_divku").css('height', $(window).height()*72/100);
    $("#runscan_qcwh_btn_gen").click(function (e) {         
        document.getElementById('runscan_qcwh_btn_gen').disabled = true;
        document.getElementById('runscan_qcwh_btn_gen').innerHTML = '<i class="fas fa-sync fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_qcwh_unscan')?>",            
            dataType: "json",
            success: function (response) {
                document.getElementById('runscan_qcwh_btn_gen').disabled = false;
                document.getElementById('runscan_qcwh_btn_gen').innerHTML = '<i class="fas fa-sync"></i>';
                const ttlrows = response.data.length;
                let mydes = document.getElementById("runscan_qcwh_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("runscan_qcwh_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("runscan_qcwh_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].ITH_QTY).value();
                    newrow = tableku2.insertRow(-1);

                    newcell = newrow.insertCell(0);                                
                    newcell.innerHTML = response.data[i].ITH_ITMCD

                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    
                    newcell = newrow.insertCell(2);                    
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_LOTNO

                    newcell = newrow.insertCell(3);                    
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].ITH_DOC

                    newcell = newrow.insertCell(4);                    
                    newcell.style.cssText= "white-space: nowrap;text-align:right";                    
                    newcell.innerHTML = numeral(response.data[i].ITH_QTY).format('0,0')

                    newcell = newrow.insertCell(5);                    
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].ITH_SER

                    newcell = newrow.insertCell(6);                    
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].PIC

                    newcell = newrow.insertCell(7);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].ITH_LUPDT
                                
                    newcell = newrow.insertCell(8);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].PDPP_BSGRP

                    newcell = newrow.insertCell(9);
                    if(wms_usergroupid=='OQC2' || wms_usergroupid == 'QACT'){
                        newcell.onclick = () => {                        
                            $("#runscan_qcwh_mod_REMARK").modal('show')
                            document.getElementById('runscan_qcwh_txt_remark').value = response.data[i].SER_RMRK
                            document.getElementById('runscan_qcwh_txt_reff').value = response.data[i].ITH_SER
                        }
                        newcell.style.cssText= 'text-align:center;cursor:pointer';
                    }
                    newcell.innerHTML = response.data[i].SER_RMRK
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);                
                document.getElementById('runscan_qcwh_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('runscan_qcwh_btn_gen').innerHTML = '<i class="fas fa-sync"></i>';
            }
        });
    });

    function runscan_qcwh_e_saveremark(){
        const reffno = document.getElementById('runscan_qcwh_txt_reff').value;
        const rmrk = document.getElementById('runscan_qcwh_txt_remark').value;
        let mtbl = document.getElementById('runscan_qcwh_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        const ttlrows = tableku2.getElementsByTagName('tr').length;
        for(let i=0; i<ttlrows; i++){
            if(tableku2.rows[i].cells[5].innerText==reffno){
                tableku2.rows[i].cells[9].innerText = rmrk
                break;
            }
        }
        $("#runscan_qcwh_mod_REMARK").modal('hide');
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setremark')?>",
            data: {inid: reffno, inrmrk: rmrk},
            dataType: "json",
            success: function (response) {    
                document.getElementById('runscan_qcwh_txt_remark').value='';
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                } else {
                    alertify.warning(response.status[0].msg);
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
</script>