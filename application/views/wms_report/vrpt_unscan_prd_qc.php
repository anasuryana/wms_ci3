<style type="text/css">	
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
    <div class="container-fluid">                     
        <div class="row">            
            <div class="col-md-12 mb-1 text-center">  
                <div class="btn-group btn-group-sm">                                     
                    <button class="btn btn-sm btn-primary" type="button" id="runscan_prdqc_btn_gen"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-sm btn-outline-primary" type="button" id="runscan_prdqc_btn_copy" onclick="runscan_prdqc_btn_copy_eCK()" title="copy data"><i class="fas fa-copy"></i></button>
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="runscan_prdqc_divku">
                    <table id="runscan_prdqc_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
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
                                <th  class="align-middle" title="from now to last transaction">Day(s)</th>
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
                    <input type="text" class="form-control" id="runscan_prdqc_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<div class="modal fade" id="runscan_prdqc_mod_REMARK">
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
                        <input type="text" class="form-control" id="runscan_prdqc_txt_remark" >    
                        <input type="hidden" id="runscan_prdqc_txt_reff">                    
                    </div>
                </div>                
            </div>          
            <div class="row">
                <div class="col text-center mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="runscan_prdqc_btnsave" onclick="runscan_prdqc_e_saveremark()"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>            
        </div>             
      </div>
    </div>
</div>
<script>
    function runscan_prdqc_btn_copy_eCK(){
        cmpr_selectElementContents(document.getElementById('runscan_prdqc_tbl'))
        document.execCommand("copy")
        alertify.message("Copied")
    }
    $("#runscan_prdqc_divku").css('height', $(window).height()*72/100);
    $("#runscan_prdqc_btn_gen").click(function (e) {         
        document.getElementById('runscan_prdqc_btn_gen').disabled = true;
        document.getElementById('runscan_prdqc_btn_gen').innerHTML = '<i class="fas fa-sync fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_prdqc_unscan')?>",
            dataType: "json",
            success: function (response) {
                document.getElementById('runscan_prdqc_btn_gen').disabled = false;
                document.getElementById('runscan_prdqc_btn_gen').innerHTML = '<i class="fas fa-sync"></i>';
                const ttlrows = response.data.length;
                let mydes = document.getElementById("runscan_prdqc_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("runscan_prdqc_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("runscan_prdqc_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].SER_QTY).value();
                    newrow = tableku2.insertRow(-1);

                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].SER_ITMID

                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].ITMD1
                    
                    newcell = newrow.insertCell(2);                    
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_LOTNO

                    newcell = newrow.insertCell(3);                    
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_DOC

                    newcell = newrow.insertCell(4);                    
                    newcell.style.cssText= "white-space: nowrap;text-align:right";                    
                    newcell.innerHTML = numeral(response.data[i].SER_QTY).format('0,0')

                    newcell = newrow.insertCell(5);                    
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].ITH_SER

                    newcell = newrow.insertCell(6);                    
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].PIC

                    newcell = newrow.insertCell(7);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].LUPDT
                                
                    newcell = newrow.insertCell(8);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SER_BSGRP

                    newcell = newrow.insertCell(9);
                    newcell.innerHTML = response.data[i].SER_RMRK

                    newcell = newrow.insertCell(10)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].DAYLEFT
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);                
                document.getElementById('runscan_prdqc_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('runscan_prdqc_btn_gen').innerHTML = '<i class="fas fa-sync"></i>';
            }
        });
    });

    function runscan_prdqc_e_saveremark(){
        const reffno = document.getElementById('runscan_prdqc_txt_reff').value;
        const rmrk = document.getElementById('runscan_prdqc_txt_remark').value;
        let mtbl = document.getElementById('runscan_prdqc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        const ttlrows = tableku2.getElementsByTagName('tr').length;
        for(let i=0; i<ttlrows; i++){
            if(tableku2.rows[i].cells[5].innerText==reffno){
                tableku2.rows[i].cells[9].innerText = rmrk
                break;
            }
        }
        $("#runscan_prdqc_mod_REMARK").modal('hide');
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setremark')?>",
            data: {inid: reffno, inrmrk: rmrk},
            dataType: "json",
            success: function (response) {    
                document.getElementById('runscan_prdqc_txt_remark').value='';
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