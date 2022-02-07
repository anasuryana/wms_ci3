<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="routput_qcsa_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="routput_qcsa_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_qcsa_typereport" id="routput_qcsa_typeall" value="a" checked>
                    <label class="form-check-label" for="routput_qcsa_typeall">All</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_qcsa_typereport" id="routput_qcsa_typenight" value="m">
                    <label class="form-check-label" for="routput_qcsa_typemorning">Morning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_qcsa_typereport" id="routput_qcsa_typenight" value="n" >
                    <label class="form-check-label" for="routput_qcsa_typenight">Night</label>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">                                        
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>
                    <input type="text" class="form-control" id="routput_qcsa_bisgrup" readonly onclick="routput_qcsa_bisgrup_eC()">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">                       
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Search by</span>                    
                    <select class="form-select" id="routput_qcsa_seachby">
                        <option value="assy">Assy Code</option>
                        <option value="job">Job Number</option>                        
                    </select>
                    <input type="text" class="form-control" id="routput_qcsa_txt_assy">                    
                </div>         
            </div>
            <div class="col-md-4 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="routput_qcsa_btn_gen">Search</button>
                    <button class="btn btn-success" type="button" id="routput_qcsa_btn_exp">Export to MEGA</button>
                </div>                
            </div>
            <div class="col-md-2 mb-1 text-end">                       
                <span id="routput_qcsa_lblinfo" class="badge bg-info"></span>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="routput_qcsa_divku">
                    <table id="routput_qcsa_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
                    <input type="text" class="form-control" id="routput_qcsa_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<div class="modal fade" id="routput_qcsa_BG">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Business Group List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col" onclick="routput_qcsa_selectBG_eC(event)">
                    <div class="table-responsive" id="routput_qcsa_tblbg_div">
                        <table id="routput_qcsa_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center d-none">BG</th>
                                    <th class="text-center">Business</th>
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
<div class="modal fade" id="routput_qcsa_REMARK">
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
                        <input type="text" class="form-control" id="routput_qcsa_txt_remark" >
                        <input type="hidden" id="routput_qcsa_txt_reff">
                    </div>
                </div>                
            </div>          
            <div class="row">
                <div class="col text-center mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="routput_qcsa_btnsave" onclick="routput_qcsa_btnsave_eCK()"><i class="fas fa-save"></i></button>
                    </div>
                </div>
            </div>            
        </div>             
      </div>
    </div>
</div>
<script>
    var routput_qcsa_a_BG = [];
    var routput_qcsa_a_BG_NM = [];
    $("#routput_qcsa_divku").css('height', $(window).height()*63/100);
    $("#routput_qcsa_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        routput_qcsa_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('routput_qcsa_bisgrup').value = strDisplay.substr(0,strDisplay.length-2)
    })
    function routput_qcsa_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('routput_qcsa_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = routput_qcsa_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        routput_qcsa_a_BG.splice(getINDX, 1)
                        routput_qcsa_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        routput_qcsa_a_BG.push(e.target.previousElementSibling.innerText)
                        routput_qcsa_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    routput_qcsa_e_getBG()
    function routput_qcsa_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("routput_qcsa_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routput_qcsa_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("routput_qcsa_tblbg")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].id
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].text
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function routput_qcsa_bisgrup_eC(){
        $("#routput_qcsa_BG").modal('show')
    }
    $("#routput_qcsa_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_qcsa_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_qcsa_txt_dt").datepicker('update', new Date());
    $("#routput_qcsa_txt_dt2").datepicker('update', new Date());
    $("#routput_qcsa_txt_assy").keypress(function (e) { 
        if(e.which==13){
            $("#routput_qcsa_btn_gen").trigger('click');
        }
    });
    $("#routput_qcsa_btn_gen").click(function (e) { 
        let dtfrom = document.getElementById('routput_qcsa_txt_dt').value;
        let dtto = document.getElementById('routput_qcsa_txt_dt2').value;
        let reporttype = $('input[name="routput_qcsa_typereport"]:checked').val();
        let assyno = document.getElementById('routput_qcsa_txt_assy').value;
        let searchby = document.getElementById('routput_qcsa_seachby').value;        
        let bgroup = routput_qcsa_a_BG
        document.getElementById('routput_qcsa_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_output_qcsa')?>",
            data: {indate: dtfrom,indate2: dtto, inreport: reporttype, inassy: assyno, inbgroup: bgroup, insearchby : searchby},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("routput_qcsa_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routput_qcsa_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("routput_qcsa_tbl");                    
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
                    newcell.appendChild(newText)
                    if(wms_usergroupid=='OQC2'){
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            document.getElementById('routput_qcsa_txt_reff').value = response.data[i].ITH_SER
                            document.getElementById('routput_qcsa_txt_remark').value = response.data[i].SER_RMRK
                            $("#routput_qcsa_REMARK").modal('show')
                        }
                    }
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('routput_qcsa_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('routput_qcsa_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    })
    $("#routput_qcsa_REMARK").on('shown.bs.modal', function(){
        $("#routput_qcsa_txt_remark").focus()
        document.getElementById('routput_qcsa_txt_remark').select()
    })
    function routput_qcsa_btnsave_eCK(){
        let reffno = document.getElementById('routput_qcsa_txt_reff').value;
        let rmrk = document.getElementById('routput_qcsa_txt_remark').value;
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/setremark')?>",
            data: {inid: reffno, inrmrk: rmrk},
            dataType: "json",
            success: function (response) {                
                $("#routput_qcsa_REMARK").modal('hide');
                document.getElementById('routput_qcsa_txt_remark').value='';
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                } else {
                    alertify.warning(response.status[0].msg);
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        })
    }
    $("#routput_qcsa_btn_exp").click(function (e) {        
        let dtfrom = document.getElementById('routput_qcsa_txt_dt').value;
        let dtto = document.getElementById('routput_qcsa_txt_dt2').value;
        let reporttype = $('input[name="routput_qcsa_typereport"]:checked').val();             
        let bgroup = routput_qcsa_a_BG
        if(bgroup==''){
            alertify.message('Please select business group first');
            return;
        }
        Cookies.set('CKPSI_DDATE', dtfrom, {expires:365});
        Cookies.set('CKPSI_DDATE2', dtto, {expires:365});
        Cookies.set('CKPSI_DREPORT', reporttype, {expires:365});
        Cookies.set('CKPSI_BSGROUP', bgroup, {expires:365});
        window.open("<?=base_url('ITH/get_output_qcsa_xls')?>",'_blank');
    });
</script>