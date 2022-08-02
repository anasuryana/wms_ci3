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
        <div class="row" id="stinventory_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Location</span>                    
                    <input type="text" class="form-control" id="stinventory_cmb_bg" readonly onclick="stinventory_bisgrup_eC()">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Date</span>
                    <input type="text" class="form-control" id="stinventory_txt_date" readonly>
                </div>
            </div>            
        </div>
       
        <div class="row" id="stinventory_stack2">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="stinventory_btn_gen" onclick="stinventory_e_upload(this)">Upload</button>
                </div>
            </div>
        </div>
        <div class="row" id="stinventory_stack3">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="stinventory_divku">
                    <table id="stinventory_tbl" class="table table-striped table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr class="first">
                                <th rowspan="2" class="align-middle text-center">Location</th>
                                <th colspan="2" class="text-center">Operation</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center">Adjustment</th>
                                <th class="text-center">Upload To IT Inventory</th>                                
                            </tr>
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div id="stinventory_div_infoAfterPost">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="stinventory_BG">
    <div class="modal-dialog modal-sm modal-dialog-scrollable">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Location List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col" onclick="stinventory_selectBG_eC(event)">
                    <div class="table-responsive" id="stinventory_tblbg_div">
                        <table id="stinventory_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center d-none">ID</th>
                                    <th class="text-center">Location Code</th>
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
    function stinventory_bisgrup_eC() 
    {
        $("#stinventory_BG").modal('show')
    }
    $("#stinventory_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#stinventory_txt_date").datepicker('update', new Date());
    stinventory_e_getBG()
    function stinventory_e_getBG()
    {
        $.ajax({            
            url: "<?=base_url('ITHHistory/getLocations')?>",
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("stinventory_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stinventory_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("stinventory_tblbg")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].LOCATIONCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].LOCATIONCD
                    newcell.title = response.data[i].LOCATIONNM
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    var stinventory_a_BG = [];
    var stinventory_a_BG_NM = [];
    function stinventory_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('stinventory_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim'))
                {
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = stinventory_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        stinventory_a_BG.splice(getINDX, 1)
                        stinventory_a_BG_NM.splice(getINDX, 1)
                    }
                } else 
                {
                    if(e.target.textContent.length!=0){
                        stinventory_a_BG.push(e.target.previousElementSibling.innerText)
                        stinventory_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    $("#stinventory_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        stinventory_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('stinventory_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })    

    function fungsi1() {
        return new Promise((resolve) => {
            console.log('sini satu')
            resolve('satu')
        })
    }
    function fungsi2() {
        return new Promise((resolve) => {
            console.log('sini dua')
            resolve('dua')
        })
    }

    function stinventoryAPI(data) {
        console.log(data)
        return $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/testAdj_ParentBased')?>",
                data: {date: data.date, location : data.item},
                dataType: "JSON",
                success: function (response) {                    
                    for(let i=0;i<stinventory_a_BG.length; i++) {
                        if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                            tableku2.rows[i].cells[1].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                            break;
                        }
                    }
                    resolveku('ok')
                }, error : function(xhr, xopt, xthrow){                    
                    reject('belum ok')
                }
            })
       
    }    
    

    function stinventory_e_upload(p)
    {
        if(stinventory_a_BG.length===0){
            alertify.warning('select location first')
            return
        }
        
        if(confirm('Are you sure ?')) {
            let mtabel = document.getElementById("stinventory_tbl")            
            let tableku2 = mtabel.getElementsByTagName("tbody")[0]
            let newrow, newcell
            tableku2.innerHTML=''
            stinventory_a_BG.forEach((item, index) => {
                newrow = tableku2.insertRow(-1)            
                newcell = newrow.insertCell(0);            
                newcell.innerHTML = item
                newcell = newrow.insertCell(1)
                newcell.classList.add('text-center')
                newcell.innerHTML = 'Please wait'
                newcell = newrow.insertCell(2)
                newcell.classList.add('text-center')
                newcell.innerHTML = 'Please wait'
            })
            const invDate = document.getElementById('stinventory_txt_date')           
            let functionList = []
            stinventory_a_BG.forEach((item, index) => {
                functionList.push(
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('ITH/testAdj_ParentBased')?>",
                        data: {date: invDate.value, location : item},
                        dataType: "JSON",
                        success: function (response) {                                                        
                            for(let i=0;i<stinventory_a_BG.length; i++) {
                                if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                    tableku2.rows[i].cells[1].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                    break;
                                }
                            }
                        }, error : function(xhr, xopt, xthrow){
                            alertify.error(xthrow)
                            const thedata = new URLSearchParams(this.data)
                            for(let i=0;i<stinventory_a_BG.length; i++) {
                                if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                    tableku2.rows[i].cells[1].innerHTML = `<span class="badge bg-warning">${xthrow}</span>`
                                    break;
                                }
                            }
                        }
                    })
                )
            })
            $.when.apply($,functionList).then(function() {                
                stinventory_a_BG.forEach((item, index) => {                    
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('ITH/upload_to_itinventory')?>",
                        data: {date: invDate.value, location : item},
                        dataType: "JSON",
                        success: function (response) {
                            p.disabled = false
                            p.innerHTML = 'Upload'
                            for(let i=0;i<stinventory_a_BG.length; i++) {
                                if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                    tableku2.rows[i].cells[2].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                    break;
                                }
                            }
                        }, error : function(xhr, xopt, xthrow){
                            alertify.error(xthrow)
                            p.disabled = false
                            p.innerHTML = 'Upload'
                            const thedata = new URLSearchParams(this.data)
                            for(let i=0;i<stinventory_a_BG.length; i++) {
                                if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                    tableku2.rows[i].cells[2].innerHTML = `<span class="badge bg-warning">${xthrow}</span>`
                                    break;
                                }
                            }
                        }
                    })
                })
            })            
        }
    }
</script>