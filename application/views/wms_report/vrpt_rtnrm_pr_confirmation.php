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
        top: 25px;
    }  
</style>
<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="rtnrm_pr_cnfr_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Date [from]</label>
                    <input type="text" class="form-control" id="rtnrm_pr_cnfr_date1" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Date [to]</label>
                    <input type="text" class="form-control" id="rtnrm_pr_cnfr_date2" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Business Group</span>                    
                    <input type="text" class="form-control" id="rtnrm_pr_cnfr_cmb_bg" readonly onclick="rtnrm_pr_cnfr_bisgrup_eC()">
                </div>                
            </div>
        </div>            
        <div class="row" id="rtnrm_pr_cnfr_stack2">
            <div class="col-md-12 mb-2 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="Search" id="rtnrm_pr_cnfr_btnsearch" onclick="rtnrm_pr_cnfr_btnsearch_eCK()" class="btn btn-primary" >Search</button>
                    <button title="Export to excel" id="rtnrm_pr_cnfr_btnexcel" onclick="rtnrm_pr_cnfr_btnexcel()" class="btn btn-success" ><i class="fas fa-file-export"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2" >
                <div class="table-responsive" id="rtnrm_pr_cnfr_tbl_div">
                    <table id="rtnrm_pr_cnfr_tbl" class="table table-sm table-hover table-bordered" style="width:100%;font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle text-center">Date</th>                            
                                <th rowspan="2" class="align-middle text-center">Document</th>
                                <th rowspan="2" class="align-middle text-center">Line</th>
                                <th rowspan="2" class="align-middle">Part Code</th>
                                <th rowspan="2" class="align-middle">Part Name</th>
                                <th rowspan="2" class="align-middle">Model</th>
                                <th colspan="3" class="align-middle text-center">QTY</th>
                            </tr>
                            <tr class="second">
                                <th class="text-end">Req.</th>
                                <th class="text-end">Sup.</th>
                                <th class="text-end">Ret.</th>
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
<div class="modal fade" id="rtnrm_pr_cnfr_BG">
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
                <div class="col" onclick="rtnrm_pr_cnfr_selectBG_eC(event)">
                    <div class="table-responsive" id="rtnrm_pr_cnfr_tblbg_div">
                        <table id="rtnrm_pr_cnfr_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
    var rtnrm_pr_cnfr_a_BG = [];
    var rtnrm_pr_cnfr_a_BG_NM = [];
    function rtnrm_pr_cnfr_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('rtnrm_pr_cnfr_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = rtnrm_pr_cnfr_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        rtnrm_pr_cnfr_a_BG.splice(getINDX, 1)
                        rtnrm_pr_cnfr_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        rtnrm_pr_cnfr_a_BG.push(e.target.previousElementSibling.innerText)
                        rtnrm_pr_cnfr_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    function rtnrm_pr_cnfr_bisgrup_eC() {
        $("#rtnrm_pr_cnfr_BG").modal('show')
    }
    function rtnrm_pr_cnfr_btnexcel() {
        if(rtnrm_pr_cnfr_a_BG.length===0) {
            alertify.message('Please select business group first')
            return
        }
        if(confirm("Are you sure ?")) {
            const date1 = document.getElementById('rtnrm_pr_cnfr_date1').value
            const date2 = document.getElementById('rtnrm_pr_cnfr_date2').value
            Cookies.set('CKPSI_DATE1', date1, {expires:365})
            Cookies.set('CKPSI_DATE2', date2, {expires:365})
            Cookies.set('CKPSI_BISGRUPS', rtnrm_pr_cnfr_a_BG.join(','), {expires:365})
            window.open("<?=base_url('return_from_plant')?>",'_blank');
        }
    }
    rtnrm_pr_cnfr_e_getBG()
    function rtnrm_pr_cnfr_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>", 
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rtnrm_pr_cnfr_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rtnrm_pr_cnfr_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rtnrm_pr_cnfr_tblbg")
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
$("#rtnrm_pr_cnfr_date1").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
})
$("#rtnrm_pr_cnfr_date2").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
})
$("#rtnrm_pr_cnfr_date1").datepicker('update', new Date())
$("#rtnrm_pr_cnfr_date2").datepicker('update', new Date())
$("#rtnrm_pr_cnfr_tbl_div").css('height', $(window).height()
    -document.getElementById('rtnrm_pr_cnfr_stack1').offsetHeight 
    -document.getElementById('rtnrm_pr_cnfr_stack2').offsetHeight    
    -100);
function rtnrm_pr_cnfr_btnsearch_eCK() {
    if(rtnrm_pr_cnfr_a_BG.length===0) {
        alertify.message('Please select business group first')
        return
    }
    const date1 = document.getElementById('rtnrm_pr_cnfr_date1').value
    const date2 = document.getElementById('rtnrm_pr_cnfr_date2').value
    const btnSearch = document.getElementById('rtnrm_pr_cnfr_btnsearch')
    btnSearch.innerHTML = "Please wait..."
    btnSearch.disabled = true
    $.ajax({
        url: "<?=base_url('RETPRD/getconfirmation_PR')?>",
        data: {date1: date1, date2:date2, bisgrups: rtnrm_pr_cnfr_a_BG.join(',') },
        dataType: "JSON",
        success: function (response) {
            btnSearch.innerHTML = "Search"
            btnSearch.disabled = false
            const ttlrows = response.data.length
            let mydes = document.getElementById("rtnrm_pr_cnfr_tbl_div")
            let myfrag = document.createDocumentFragment()
            let mtabel = document.getElementById("rtnrm_pr_cnfr_tbl")
            let cln = mtabel.cloneNode(true);
            myfrag.appendChild(cln)
            let tabell = myfrag.getElementById("rtnrm_pr_cnfr_tbl")
            let tableku2 = tabell.getElementsByTagName("tbody")[0]
            let newrow, newcell, newText
            let myitmttl = 0            
            tableku2.innerHTML=''
            for (let i = 0; i<ttlrows; i++){ 
                newrow = tableku2.insertRow(-1)
                newcell = newrow.insertCell(0)
                newcell.innerHTML = response.data[i].RETSCN_CNFRMDT
                newcell = newrow.insertCell(1)
                newcell.innerHTML = response.data[i].VREQPSN
                newcell = newrow.insertCell(2)
                newcell.innerHTML = response.data[i].SPL_LINE
                newcell = newrow.insertCell(3)
                newcell.innerHTML = response.data[i].SPL_ITMCD
                newcell = newrow.insertCell(4)
                newcell.innerHTML = response.data[i].MITM_SPTNO
                newcell = newrow.insertCell(5)
                newcell.innerHTML = response.data[i].MITM_ITMD1
                newcell = newrow.insertCell(6)
                newcell.classList.add('text-end')
                newcell.innerHTML = numeral(response.data[i].REQQTY).format(',')
                newcell = newrow.insertCell(7)
                newcell.classList.add('text-end')
                newcell.innerHTML = numeral(response.data[i].SUPQTY).format(',')
                newcell = newrow.insertCell(8)
                newcell.classList.add('text-end')
                newcell.innerHTML = numeral(response.data[i].RETQTY).format(',')                
            }
            mydes.innerHTML=''
            mydes.appendChild(myfrag)
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow)
            btnSearch.disabled = false
            btnSearch.innerHTML = "Search"
        }
    })
}
$("#rtnrm_pr_cnfr_BG").on('hidden.bs.modal', function(){
    let strDisplay = ''
    rtnrm_pr_cnfr_a_BG_NM.forEach((item, index) => {
        strDisplay += item + ', '
    })
    document.getElementById('rtnrm_pr_cnfr_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
})
</script>