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
        <div class="row" id="routput_qc_stack1">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >From</span>                    
                    <input type="text" class="form-control" id="routput_qc_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="routput_qc_txt_dt2" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_qc_typereport" id="routput_qc_typeall" value="a" checked>
                    <label class="form-check-label" for="routput_qc_typeall">All</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_qc_typereport" id="routput_qc_typenight" value="m">
                    <label class="form-check-label" for="routput_qc_typemorning">Morning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="routput_qc_typereport" id="routput_qc_typenight" value="n" >
                    <label class="form-check-label" for="routput_qc_typenight">Night</label>
                </div>
            </div>            
        </div>
        <div class="row" id="routput_qc_stack2">
            <div class="col-md-12 mb-1">   
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <input type="text" class="form-control" id="routput_qc_bisgrup" readonly onclick="routput_qc_bisgrup_eC()">
                </div>                    
            </div>
        </div>
        <div class="row" id="routput_qc_stack3">
            <div class="col-md-6 mb-1">                       
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Search by</span>                    
                    <select class="form-select" id="routput_qc_seachby">
                        <option value="assy">Assy Code</option>
                        <option value="job">Job Number</option>                        
                    </select>
                    <input type="text" class="form-control" id="routput_qc_txt_assy">                    
                </div>         
            </div>
            <div class="col-md-4 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="routput_qc_btn_gen">Search</button>
                    <button class="btn btn-success" type="button" id="routput_qc_btn_exp">Export</button>
                </div>                
            </div>
            <div class="col-md-2 mb-1 text-end">                       
                <span id="routput_qc_lblinfo" class="badge bg-info"></span>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="routput_qc_divku">
                    <table id="routput_qc_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
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
        <div class="row" id="routput_qc_stack4">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="routput_qc_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<div class="modal fade" id="routput_qc_BG">
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
                <div class="col" onclick="routput_qc_selectBG_eC(event)">
                    <div class="table-responsive" id="routput_qc_tblbg_div">
                        <table id="routput_qc_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
<script>
    var routput_qc_a_BG = [];
    var routput_qc_a_BG_NM = [];
    $("#routput_qc_divku").css('height', $(window).height()   
    -document.getElementById('routput_qc_stack1').offsetHeight
    -document.getElementById('routput_qc_stack2').offsetHeight
    -document.getElementById('routput_qc_stack3').offsetHeight
    -document.getElementById('routput_qc_stack4').offsetHeight
    -100);    
    $("#routput_qc_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        routput_qc_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('routput_qc_bisgrup').value = strDisplay.substr(0,strDisplay.length-2)
    })
    function routput_qc_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('routput_qc_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = routput_qc_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        routput_qc_a_BG.splice(getINDX, 1)
                        routput_qc_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        routput_qc_a_BG.push(e.target.previousElementSibling.innerText)
                        routput_qc_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    routput_qc_e_getBG()
    function routput_qc_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("routput_qc_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routput_qc_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("routput_qc_tblbg")
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
    function routput_qc_bisgrup_eC(){
        $("#routput_qc_BG").modal('show')
    }
    $("#routput_qc_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_qc_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#routput_qc_txt_dt").datepicker('update', new Date());
    $("#routput_qc_txt_dt2").datepicker('update', new Date());
    $("#routput_qc_txt_assy").keypress(function (e) { 
        if(e.which==13){
            $("#routput_qc_btn_gen").trigger('click');
        }
    });
    $("#routput_qc_btn_gen").click(function (e) { 
        let dtfrom = document.getElementById('routput_qc_txt_dt').value;
        let dtto = document.getElementById('routput_qc_txt_dt2').value;
        let reporttype = $('input[name="routput_qc_typereport"]:checked').val();
        let assyno = document.getElementById('routput_qc_txt_assy').value;
        let searchby = document.getElementById('routput_qc_seachby').value;        
        let bgroup = routput_qc_a_BG
        document.getElementById('routput_qc_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
        $.ajax({
            type: "get",
            url: "<?=base_url('ITH/get_output_qc')?>",
            data: {indate: dtfrom,indate2: dtto, inreport: reporttype, inassy: assyno, inbgroup: bgroup, insearchby : searchby},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("routput_qc_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("routput_qc_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("routput_qc_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].ITH_QTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].ITH_ITMCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    
                    newcell = newrow.insertCell(2)
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_LOTNO

                    newcell = newrow.insertCell(3)
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_DOC

                    newcell = newrow.insertCell(4)
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.innerHTML = numeral(response.data[i].ITH_QTY).format('0,0')

                    newcell = newrow.insertCell(5);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].ITH_SER

                    newcell = newrow.insertCell(6)
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].PIC

                    newcell = newrow.insertCell(7)
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].ITH_LUPDT

                    newcell = newrow.insertCell(8)
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].PDPP_BSGRP

                    newcell = newrow.insertCell(9)
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].SER_RMRK
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('routput_qc_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('routput_qc_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    });
    $("#routput_qc_btn_exp").click(function (e) {        
        let dtfrom = document.getElementById('routput_qc_txt_dt').value;
        let dtto = document.getElementById('routput_qc_txt_dt2').value;
        let reporttype = $('input[name="routput_qc_typereport"]:checked').val();
        let assyno = document.getElementById('routput_qc_txt_assy').value;        
        let bgroup = routput_qc_a_BG       
        let sbsgroup='';
        let ttlbg = bgroup.length;        
        if(ttlbg==0){
            alertify.warning("Please select a Business Group !");
            return;
        }        
        if(ttlbg>1){
            alertify.warning("Please select only one Business Group !");
            return;
        }        
        sbsgroup += bgroup[0];        
        Cookies.set('CKPSI_DDATE', dtfrom, {expires:365});
        Cookies.set('CKPSI_DDATE2', dtto, {expires:365});
        Cookies.set('CKPSI_DREPORT', reporttype, {expires:365});
        Cookies.set('CKPSI_DASSY', assyno, {expires:365});
        Cookies.set('CKPSI_BSGROUP', sbsgroup, {expires:365});
        window.open("<?=base_url('ITH/get_output_qc_xls')?>",'_blank');
    });
</script>