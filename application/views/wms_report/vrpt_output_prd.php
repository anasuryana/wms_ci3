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
    <div class="container-fluid">        
        <div class="row" id="routput_prd_stack1">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="routput_prd_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="routput_prd_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_prd_typereport" id="routput_prd_typeall" value="a" checked>
                    <label class="form-check-label" for="routput_prd_typeall">All</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_prd_typereport" id="routput_prd_typenight" value="m">
                    <label class="form-check-label" for="routput_prd_typemorning">Morning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_prd_typereport" id="routput_prd_typenight" value="n" >
                    <label class="form-check-label" for="routput_prd_typenight">Night</label>
                </div>
            </div>
        </div>
        <div class="row" id="routput_prd_stack2">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <select class="form-select" id="routput_prd_bisgrup">                  
                        <?php 
                        $todis = '';
                        foreach($lgroup as $r){
                            $todis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
                        }
                        echo $todis;
                        ?>
                    </select>                                   
                </div>                
            </div>
        </div>
        <div class="row" id="routput_prd_stack3">
            <div class="col-md-6 mb-1">                       
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Search by</span>                    
                    <select class="form-select" id="routput_prd_seachby">
                        <option value="assy">Assy Code</option>
                        <option value="job">Job Number</option>                        
                    </select>
                    <input type="text" class="form-control" id="routput_prd_txt_assy">                    
                </div>         
            </div>
            
            <div class="col-md-4 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="routput_prd_btn_gen">Search</button>
                    <button class="btn btn-success" type="button" id="routput_prd_btn_exp">Export</button>
                </div>                
            </div>
            <div class="col-md-2 mb-1 text-end">
                <span id="routput_prd_lblinfo" class="badge bg-info"></span>
               
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="routput_prd_divku">
                    <table id="routput_prd_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">Lot</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-right">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">PIC</th>
                                <th  class="text-center">Time</th>
                                <th  class="align-middle">Business</th>
                                <th  class="text-center">Remark</th>
                            </tr>                           
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="row" id="routput_prd_stack4">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="routput_prd_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<script>
    $("#routput_prd_divku").css('height', $(window).height()   
    -document.getElementById('routput_prd_stack1').offsetHeight 
    -document.getElementById('routput_prd_stack2').offsetHeight
    -document.getElementById('routput_prd_stack3').offsetHeight
    -document.getElementById('routput_prd_stack4').offsetHeight   
    -100)
    $("#routput_prd_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_prd_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_prd_txt_dt").datepicker('update', new Date());
    $("#routput_prd_txt_dt2").datepicker('update', new Date());

    $("#routput_prd_txt_assy").keypress(function (e) { 
        if(e.which==13){
            $("#routput_prd_btn_gen").trigger('click');
        }
    });
    $("#routput_prd_btn_gen").click(function (e) { 
        let dtfrom = document.getElementById('routput_prd_txt_dt').value;
        let dtto = document.getElementById('routput_prd_txt_dt2').value;
        let reporttype = $('input[name="routput_prd_typereport"]:checked').val();
        let assyno = document.getElementById('routput_prd_txt_assy').value;
        let searchby = document.getElementById('routput_prd_seachby').value;
        let bgroup = document.getElementById('routput_prd_bisgrup').value;
        document.getElementById('routput_prd_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_output_prd')?>",
            data: {indate: dtfrom, indate2: dtto, inreport: reporttype, inassy: assyno, inbgroup: bgroup, insearchby: searchby},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("routput_prd_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routput_prd_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("routput_prd_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].ITH_QTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)                    
                    newcell.innerHTML = response.data[i].ITH_ITMCD.trim()
                    newcell = newrow.insertCell(1);                    
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    
                    newcell = newrow.insertCell(2);                    
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_LOTNO

                    newcell = newrow.insertCell(3);                    
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_DOC

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

                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = response.data[i].SER_RMRK
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('routput_prd_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('routput_prd_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#routput_prd_btn_exp").click(function (e) {        
        let dtfrom = document.getElementById('routput_prd_txt_dt').value;
        let dtto = document.getElementById('routput_prd_txt_dt2').value;
        let reporttype = $('input[name="routput_prd_typereport"]:checked').val();
        let assyno = document.getElementById('routput_prd_txt_assy').value;
        // let bgroup = $('#routput_prd_bisgrup').selectpicker('val');
        let bgroup = $('#routput_prd_bisgrup').combobox('getValues');
        let sbsgroup='';
        for(let i=0;i<bgroup.length;i++){
            sbsgroup += bgroup[i] + "|";
        }
        sbsgroup = sbsgroup.substr(0,sbsgroup.length-1);        
        Cookies.set('CKPSI_DDATE', dtfrom, {expires:365});
        Cookies.set('CKPSI_DDATE2', dtto, {expires:365});
        Cookies.set('CKPSI_DREPORT', reporttype, {expires:365});
        Cookies.set('CKPSI_DASSY', assyno, {expires:365});
        Cookies.set('CKPSI_BSGROUP', sbsgroup, {expires:365});
        window.open("<?=base_url('ITH/get_output_prd_xls')?>",'_blank');
    });
</script>