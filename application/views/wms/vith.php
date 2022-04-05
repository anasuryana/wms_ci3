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
        <div class="row" id="ith_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Item</span>                    
                    <input type="text" class="form-control" id="ith_txt_item">                    
                    <button title="Search" class="btn btn-primary" id="ith_btn_gen"> <i class="fas fa-search"></i> </button>                    
                    
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Export to..">
                        <i class="fas fa-file-export"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li><a class="dropdown-item" href="#" id="ith_btn_xls" onclick="ith_btn_xls_head_e_click()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>                            
                        <li><a class="dropdown-item" href="#" id="ith_btn_xls" onclick="ith_btn_xls_e_click()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS Detail</a></li>                            
                    </ul>                    
                </div>
            </div> 
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date</span>                    
                    <input type="text" class="form-control" id="ith_txt_date" readonly>
                </div>
            </div> 
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <input type="text" class="form-control" id="ith_cmb_bg" readonly onclick="ith_bisgrup_eC()">
                </div>                
            </div> 
                      
        </div>    
        <div class="row" id="ith_stack2">
            <div class="col-md-10 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Warehouse</span>
                    <select class="form-select" id="ith_cmb_wh" ><?=$lwh?></select>
                </div>
            </div>
            <div class="col-md-2 mb-1 text-end">
                <span class="badge bg-info" id="ith_spn_info"></span>
            </div> 
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="ith_divku">
                    <table id="ith_tbl" class="table table-striped table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle">No</th>
                                <th rowspan="2" class="align-middle">Item Id</th>
                                <th rowspan="2" colspan="2" class="align-middle">Item Name</th>
                                <th colspan="5" class="text-center">Qty</th>
                                <th rowspan="2" class="align-middle" title="Unite Measurement">UM</th>
                            </tr>
                            <tr class="second">
                                <th class="text-end">Opening</th>                      
                                <th class="text-end">In</th>
                                <th class="text-end">Prepare</th>
                                <th class="text-end">Out</th>
                                <th class="text-end">Closing</th>
                            </tr>
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
		<div class="row" id="ith_stack3">
			<div class="col-md-6 mb-1 text-right">
				
			</div>
            <div class="col-md-6 mb-1 text-right">
				<div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="ith_txt_ttl" readonly>                    
                </div>
			</div>
		</div>
    </div>
</div>
<div class="modal fade" id="ith_BG">
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
                <div class="col" onclick="ith_selectBG_eC(event)">
                    <div class="table-responsive" id="ith_tblbg_div">
                        <table id="ith_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
    var ith_a_BG = [];
    var ith_a_BG_NM = [];
    function ith_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('ith_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = ith_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        ith_a_BG.splice(getINDX, 1)
                        ith_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        ith_a_BG.push(e.target.previousElementSibling.innerText)
                        ith_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    function ith_bisgrup_eC() {
        $("#ith_BG").modal('show')
    }
    function ith_set_wh(){
        let mwh = document.getElementById("ith_cmb_wh").value;
        Cookies.set('CKPSI_WH', mwh, {expires:365});    
    }
    function ith_btn_xls_head_e_click(){
        ith_set_wh();
        let mdate = document.getElementById('ith_txt_date').value;
        let bgroup = ith_a_BG
        let sbg = '';
        for(let i=0; i< bgroup.length; i++){
            sbg += bgroup[i] + ',';
        }
        if(sbg!=''){
            sbg = sbg.substr(0,sbg.length-1);
        }
        Cookies.set('CKPSI_BG', sbg, {expires:365});        
        Cookies.set('CKPSI_DATE', mdate, {expires:365});
        window.open("<?=base_url('ex_stock_recap')?>",'_blank');
    }
    function ith_btn_xls_e_click(){    
        ith_set_wh();
        let mdate = document.getElementById('ith_txt_date').value;
        let bgroup = ith_a_BG
        let sbg = '';
        for(let i=0; i< bgroup.length; i++){
            sbg += bgroup[i] + ',';
        }
        if(sbg!=''){
            sbg = sbg.substr(0,sbg.length-1);
        }
        Cookies.set('CKPSI_BG', sbg, {expires:365});        
        Cookies.set('CKPSI_DATE', mdate, {expires:365});
        window.open("<?=base_url('ex_stock_detail')?>",'_blank');
    }

    $("#ith_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#ith_txt_date").datepicker('update', new Date());    
    $("#ith_divku").css('height', $(window).height()   
        -document.getElementById('ith_stack1').offsetHeight 
        -document.getElementById('ith_stack2').offsetHeight    
        -document.getElementById('ith_stack3').offsetHeight
        -100);
$("#ith_txt_item").keypress(function (e) { 
    if(e.which==13){
        ith_getstock();
    }
});
function ith_getstock(){
    ith_set_wh();
    let mitem = document.getElementById('ith_txt_item').value.trim();
    let mdate = document.getElementById('ith_txt_date').value;
    let bgroup = ith_a_BG
    document.getElementById('ith_spn_info').innerText='Please wait ...';
    $.ajax({
        type: "get",
        url: "<?=base_url('ITH/getstock_wh')?>",
        data: {initem: mitem, inbgroup: bgroup, indate: mdate},
        dataType: "json",
        success: function (response) {
            let ttlrows = response.data.length;
            document.getElementById('ith_spn_info').innerText=ttlrows +' row(s) found';
            let mydes = document.getElementById("ith_divku");
            let myfrag = document.createDocumentFragment();
            let mtabel = document.getElementById("ith_tbl");
            let cln = mtabel.cloneNode(true);
            myfrag.appendChild(cln);
            let tabell = myfrag.getElementById("ith_tbl");
            let tableku2 = tabell.getElementsByTagName("tbody")[0];
            let newrow, newcell, newText;
			let myitmttl = 0;
            tableku2.innerHTML='';            
            for (let i = 0; i<ttlrows; i++){
				myitmttl+=numeral(response.data[i].STOCKQTY).value();
                newrow = tableku2.insertRow(-1);
                newcell = newrow.insertCell(0);
                newText = document.createTextNode((i+1));
                newcell.appendChild(newText);
                newcell = newrow.insertCell(1);
                newText = document.createTextNode(response.data[i].ITH_ITMCD);
                newcell.appendChild(newText);
                newcell = newrow.insertCell(2)
                newcell.classList.add('font-monospace')
                newcell.innerHTML = response.data[i].MITM_SPTNO
                newcell = newrow.insertCell(3);
                newText = document.createTextNode(response.data[i].MITM_ITMD1);                
                newcell.appendChild(newText);
                newcell = newrow.insertCell(4);
                newcell.title = "OPENING";
                newText = document.createTextNode(numeral(response.data[i].BEFQTY).format('0,0'));
                newcell.style.cssText='text-align: right';
                newcell.appendChild(newText);
                newcell = newrow.insertCell(5);
                newcell.title = "Today: IN";
                newText = document.createTextNode(numeral(response.data[i].INQTY).format('0,0'));
                newcell.style.cssText='text-align: right';
                newcell.appendChild(newText);
                newcell = newrow.insertCell(6);
                newcell.title = "PREPARE";
                newText = document.createTextNode(numeral(response.data[i].PRPQTY).format('0,0'));
                newcell.style.cssText='text-align: right';
                newcell.appendChild(newText);
                newcell = newrow.insertCell(7);
                newcell.title = "Today: OUT";
                newText = document.createTextNode(numeral(response.data[i].OUTQTY).format('0,0'));
                newcell.style.cssText='text-align: right';
                newcell.appendChild(newText);
                
                newcell = newrow.insertCell(8);
                newcell.title = "END";                
                newText = document.createTextNode(numeral(response.data[i].STOCKQTY).format('0,0'));
                newcell.style.cssText='text-align: right';
                newcell.appendChild(newText);
                newcell = newrow.insertCell(9);
                newText = document.createTextNode(response.data[i].MITM_STKUOM);
                newcell.appendChild(newText);
            }
            mydes.innerHTML='';
            mydes.appendChild(myfrag);
			document.getElementById('ith_txt_ttl').value=numeral(myitmttl).format('0,0');
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
}
$("#ith_btn_gen").click(function (e) { 
    ith_getstock();    
});
ith_e_getBG()
    function ith_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("ith_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("ith_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("ith_tblbg")
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
    $("#ith_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        ith_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('ith_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })
</script>