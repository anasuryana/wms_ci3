<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="routput_prdsa_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="routput_prdsa_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_prdsa_typereport" id="routput_prdsa_typeall" value="a" checked>
                    <label class="form-check-label" for="routput_prdsa_typeall">All</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_prdsa_typereport" id="routput_prdsa_typenight" value="m">
                    <label class="form-check-label" for="routput_prdsa_typemorning">Morning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_prdsa_typereport" id="routput_prdsa_typenight" value="n" >
                    <label class="form-check-label" for="routput_prdsa_typenight">Night</label>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">                                        
                    <input id="routput_prdsa_bisgrup" class="easyui-combobox" name="dept" style="width:100%;" 
                    data-options="valueField:'id',textField:'text',url:'<?=base_url("ITH/get_bs_group")?>', label: 'Business Group :', editable: false">                
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">                       
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Search by</span>                    
                    <select class="form-select" id="routput_prdsa_seachby">
                        <option value="assy">Assy Code</option>
                        <option value="job">Job Number</option>                        
                    </select>
                    <input type="text" class="form-control" id="routput_prdsa_txt_assy">                    
                </div>         
            </div>
            <div class="col-md-4 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="routput_prdsa_btn_gen">Search</button>                    
                </div>                
            </div>
            <div class="col-md-2 mb-1 text-end">                       
                <span id="routput_prdsa_lblinfo" class="badge bg-info"></span>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="routput_prdsa_divku">
                    <table id="routput_prdsa_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
                                <th  class="text-center">Business</th>
                                <th  class="text-center">Remark</th>
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
                    <input type="text" class="form-control" id="routput_prdsa_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<script>    
    $("#routput_prdsa_divku").css('height', $(window).height()*63/100);
    $("#routput_prdsa_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_prdsa_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_prdsa_txt_dt").datepicker('update', new Date());
    $("#routput_prdsa_txt_dt2").datepicker('update', new Date());
    $("#routput_prdsa_txt_assy").keypress(function (e) { 
        if(e.which==13){
            $("#routput_prdsa_btn_gen").trigger('click');
        }
    });
    $("#routput_prdsa_btn_gen").click(function (e) { 
        let dtfrom = document.getElementById('routput_prdsa_txt_dt').value;
        let dtto = document.getElementById('routput_prdsa_txt_dt2').value;
        let reporttype = $('input[name="routput_prdsa_typereport"]:checked').val();
        let assyno = document.getElementById('routput_prdsa_txt_assy').value;
        let searchby = document.getElementById('routput_prdsa_seachby').value;        
        let bgroup = $('#routput_prdsa_bisgrup').combobox('getValue');
        document.getElementById('routput_prdsa_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_output_prdsa')?>",
            data: {indate: dtfrom,indate2: dtto, inreport: reporttype, inassy: assyno, inbgroup: bgroup, insearchby : searchby},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("routput_prdsa_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routput_prdsa_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("routput_prdsa_tbl");                    
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
                    newText = document.createTextNode(response.data[i].ITH_ITMCD);
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
                    newText = document.createTextNode(numeral(response.data[i].ITH_QTY).format('0,0'));
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].ITH_SER);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].PIC);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.data[i].ITH_LUPDT);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.data[i].PDPP_BSGRP);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(response.data[i].SER_RMRK);
                    newcell.style.cssText= 'text-align:center';
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('routput_prdsa_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('routput_prdsa_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    
</script>