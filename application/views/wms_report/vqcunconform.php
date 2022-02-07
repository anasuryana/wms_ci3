<div style="padding: 10px">
    <div class="container-fluid">                     
        <div class="row">            
            <div class="col-md-12 mb-1 text-center">                       
                <div class="input-group mb-1">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="qcunconform__bisgrup">                  
                        <?php 
                        $todis = '';
                        foreach($lgroup as $r){
                            $todis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
                        }
                        echo $todis;
                        ?>
                    </select>                    
                    <button class="btn btn-success" id="qcunconform_refresh"><i class="fas fa-sync-alt"></i></button>                    
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="qcunconform_qcwh_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="qcunconform_qcwh_divku">
                    <table id="qcunconform_qcwh_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">Lot</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-right">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">PIC</th>
                                <th  class="text-center">Time</th>                                
                                <th  class="align-middle">Business</th>
                            </tr>                           
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-6 mb-1 text-right">
            </div>
            <div class="col-md-6 mb-1 text-right">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="qcunconform_qcwh_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<script>
    $("#qcunconform_qcwh_divku").css('height', $(window).height()*72/100);
    $("#qcunconform_refresh").click(function (e) {         
        document.getElementById('qcunconform_qcwh_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        let mbg = document.getElementById('qcunconform__bisgrup').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/qcunconform')?>",  
            data: {inbg: mbg} ,
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("qcunconform_qcwh_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("qcunconform_qcwh_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("qcunconform_qcwh_tbl");                    
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
                    newText = document.createTextNode(response.data[i].SER_ITMID);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].SER_LOTNO);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].SER_DOC);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.data[i].SER_QTY).format('0,0'));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].SER_ID);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].PIC);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.data[i].SER_LUPDT);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);
                                
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.data[i].PDPP_BSGRP);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);                    
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);                
                document.getElementById('qcunconform_qcwh_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('qcunconform_qcwh_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
</script>