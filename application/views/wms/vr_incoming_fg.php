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
<div style="padding: 5px">
    <div class="container-fluid">        
        <div class="row" id="rincfg_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <input type="text" class="form-control" id="rincfg_cmb_bg" readonly onclick="rincfg_bisgrup_eC()">
                </div>            
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Job No</span>                    
                    <input type="text" class="form-control" id="rincfg_txt_item">                                        
                    <button class="btn btn-primary" type="button" id="rincfg_btn_gen"><i class="fas fa-search"></i></button>
                    <button class="btn btn-outline-primary" type="button" id="rincfg_btn_gen_prd_qc" onclick="rincfg_btn_gen_prd_qc_e_click()">PRD > QC</button>                    
                </div>
            </div>           
        </div>        
        <div class="row" id="rincfg_stack2">
            <div class="col-md-6 mb-1">                       
                <span id="rincfg_lblinfo" class="badge bg-info"></span>
            </div>
            <div class="col-md-6 mb-1">
                <div class="form-check-inline">
                    <input type="radio" id="rincfg_rdopen" class="form-check-input" name="rincfg_sts" value="0" checked>
                    <label class="form-check-label" for="rincfg_rdopen">
                    Open
                    </label>
                </div>
                <div class="form-check-inline">
                    <input type="radio" id="rincfg_rdclose" class="form-check-input" name="rincfg_sts" value="1">
                    <label class="form-check-label" for="rincfg_rdclose">
                    Close
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="table-responsive" id="rincfg_divku">
                    <table id="rincfg_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">                                
                                <th rowspan="2" class="align-middle">Business Group</th>
                                <th rowspan="2" class="align-middle">Doc No</th>
                                <th rowspan="2" class="align-middle">Item Id</th>
                                <th rowspan="2" class="align-middle">Item Name</th>
                                <th rowspan="2" class="align-middle">FL Rev</th>
                                <th colspan="7" class="text-center">Qty</th>                                
                            </tr>
                            <tr class="second">
                                <th class="text-center">Lot Size</th>
                                <th class="text-center">GRN'</th>
                                <th class="text-center">PRD</th>
                                <th class="text-center">NG</th>
                                <th class="text-center">QC</th>
                                <th class="text-center">WH</th>
                                <th class="text-center">Scrap</th>
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
<div class="modal fade" id="rincfg_BG">
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
                <div class="col" onclick="rincfg_selectBG_eC(event)">
                    <div class="table-responsive" id="rincfg_tblbg_div">
                        <table id="rincfg_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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

    var rincfg_a_BG = [];
    var rincfg_a_BG_NM = [];
    function rincfg_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('rincfg_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = rincfg_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        rincfg_a_BG.splice(getINDX, 1)
                        rincfg_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        rincfg_a_BG.push(e.target.previousElementSibling.innerText)
                        rincfg_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    $("#rincfg_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        rincfg_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('rincfg_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })
    function rincfg_bisgrup_eC() {
        $("#rincfg_BG").modal('show')
    }    
    $("#rincfg_divku").css('height', $(window).height()   
        -document.getElementById('rincfg_stack1').offsetHeight
        -document.getElementById('rincfg_stack2').offsetHeight
        -100);
    function rincfg_btn_gen_prd_qc_e_click(){
        rincfg_e_report("<?=base_url('ITH/getdataincfg_prd_qc')?>");
    }
    function rincfg_e_report(purl){
        let mjob = document.getElementById("rincfg_txt_item").value.trim()
        let mbg = rincfg_a_BG
        const rbs = document.querySelectorAll('input[name="rincfg_sts"]');
        let selectedValue;
        for (const rb of rbs) {
            if (rb.checked) {
                selectedValue = rb.value;
                break;
            }
        }
        document.getElementById("rincfg_lblinfo").innerText= "Please wait . . .";
        $.ajax({
            type: "post",
            url: purl,
            data: {injob : mjob, insts: selectedValue, inbg: mbg},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                document.getElementById("rincfg_lblinfo").innerText= ttlrows + " row(s) found";
                let mydes = document.getElementById("rincfg_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rincfg_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rincfg_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                let qtyprd , qtyqc, qtywh, qtylot,qtygrn,qtygrnaks, qtyng ,bomrv;            
                tableku2.innerHTML='';                             
                for (let i = 0; i<ttlrows; i++){
                    qtylot = response.data[i].PDPP_WORQT ? numeral(response.data[i].PDPP_WORQT).format('0,0') : 0;
                    qtygrn = response.data[i].PDPP_GRNQT ? numeral(response.data[i].PDPP_GRNQT).format('0,0') : 0;
                    if(response.data[i].QTY_PRD){
                        qtyprd = numeral(response.data[i].QTY_PRD).format('0,0');
                    } else {
                        qtyprd = 0;
                    }                    
                    if(response.data[i].QTY_QA){
                        qtyqc = numeral(response.data[i].QTY_QA).format('0,0');
                    } else {
                        qtyqc = 0;
                    }
                    if(response.data[i].QTY_WH){
                        qtywh = numeral(response.data[i].QTY_WH).format('0,0');
                    } else {
                        qtywh = 0;
                    }
                    if(response.data[i].NG){     
                        datatemp =  numeral(response.data[i].NG).value()
                        qtyng = numeral(datatemp).format('0,0');
                    } else {
                        qtyng = 0;
                    }
                    qtygrnaks =  (numeral(qtyprd).value()>numeral(qtygrn).value()) ?  qtyprd : qtygrn
                    qtygrnaks = numeral(qtygrnaks).value()+response.data[i].NG*1+response.data[i].QTY_SCR*1
                    qtygrnaks = numeral(qtygrnaks).format(',')
                    bomrv = response.data[i].PDPP_BOMRV.substr(0,1) === '.' ? '0'+response.data[i].PDPP_BOMRV : response.data[i].PDPP_BOMRV
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].PDPP_BSGRP                    
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].SER_DOC                    
                    newcell = newrow.insertCell(2);
                    newcell.innerHTML = response.data[i].SER_ITMID                    
                    newcell = newrow.insertCell(3);
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = bomrv
                    newcell = newrow.insertCell(5);
                    newcell.innerHTML = qtylot                    
                    newcell.style.cssText = 'text-align: right';                    
                    newcell = newrow.insertCell(6)
                    newcell.innerHTML = qtygrnaks               
                    newcell.style.cssText = 'text-align: right';                    
                    newcell = newrow.insertCell(7);
                    newcell.innerHTML = qtyprd
                    newcell.style.cssText = 'text-align: right';                    
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = qtyng                    
                    newcell.style.cssText = 'text-align: right';                    
                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = qtyqc                    
                    newcell.style.cssText = 'text-align: right';                    
                    newcell = newrow.insertCell(10)
                    newcell.innerHTML = qtywh                    
                    newcell.style.cssText = 'text-align: right';                    
                    newcell = newrow.insertCell(11);
                    newcell.innerHTML = numeral(response.data[i].QTY_SCR).format(',')
                    newcell.style.cssText = 'text-align: right'                    
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#rincfg_btn_gen").click(function (e) { 
        rincfg_e_report("<?=base_url('ITH/getdataincfg')?>");
    });
    $("#rincfg_txt_item").keypress(function (e) { 
        if(e.which==13){
            rincfg_e_report("<?=base_url('ITH/getdataincfg')?>");
        }
    });
    rincfg_e_getBG()
    function rincfg_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rincfg_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rincfg_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rincfg_tblbg")
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
</script>