<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="mprocd_btn_new" onclick="mprocd_btn_new_eC()" title="New data"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary" id="mprocd_btn_save" onclick="mprocd_btn_save_eC()" title="Save data"><i class="fas fa-save"></i></button>
                    <button class="btn btn-outline-primary" data-bs-target="#mprocd_mod_assycode" data-bs-toggle="modal" title="Open data"><i class="fas fa-folder-open"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" id="mprocd_btn_new" onclick="mprocd_btn_history_eC()" title="History"><i class="fas fa-clock-rotate-left"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 table-responsive">
                <div id="mprocd_ss1"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mprocd_mod_assycode">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Model List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="mprocd_txtsearch" onkeypress="mprocd_txtsearch_eKP(event)" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>           
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mprocd_tblsup_div">
                        <table id="mprocd_tblsup" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Model</th>
                                    <th>Asyy Code</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h6>Selected model</h6>
                </div>
            </div>
            <div class="row">
                <div class="col shadow p-3 bg-body rounded" id="mprocd_selected_item">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Open</button>
        </div>
      </div>
    </div>
</div>
<script>
var mprocddata = [
    ['', '', ''],
    ['', '', ''],
    ['', '', ''],
    ['', '', '']    
];
var mprocd_selected_models = []
var mprocd_sso = jspreadsheet(document.getElementById('mprocd_ss1'), {
    data:mprocddata,    
    columns: [
        {
            type: 'text',
            title: ' ',
            width: '100',
            align: 'left',
        },
        {
            type: 'text',
            title: ' ',
            width: '150',            
        },
        {
            type: 'text',
            title: ' ',
            width:'100'
        },
        {
            type: 'text',
            title: '#1',
            width:'70'
        },
        {
            type: 'text',
            title: '#2',
            width:'70'
        },
        {
            type: 'text',
            title: '#3',
            width:'70'
        },
        {
            type: 'text',
            title: '#4',
            width:'70'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
        {
            type: 'text',
            title: 'Line',
            width:'55'
        },
        {
            type: 'text',
            title: 'CT',
            width:'55'
        },
    ],
    nestedHeaders:[
        [
            {
                title: 'Model',
                colspan: '1',
                
            },
            {
                title: 'Assy Code',
                colspan: '1',
            },
            {
                title: 'Code',
                colspan: '1',
            },
            {
                title: 'PROCESS',
                colspan: '4',
            },
            {
                title: 'SMT-AV',
                colspan: '2',
            },
            {
                title: 'SMT-RD',
                colspan: '2',
            },
            {
                title: 'HDP',
                colspan: '2',
            },
            {
                title: 'PROCESS.1',
                colspan: '2',
            },
            {
                title: 'PROCESS.2',
                colspan: '2',
            },
            {
                title: 'SMT-HW',
                colspan: '2',
            },
            {
                title: 'SMT-HWADD',
                colspan: '2',
            },
            {
                title: 'SMT-SP',
                colspan: '2',
            },
        ],        
    ],
});

$("#mprocd_mod_assycode").on('shown.bs.modal', function(){
    mprocd_txtsearch.focus()
})

function mprocd_txtsearch_eKP(e) {
    if(e.key==='Enter') {
        e.target.readOnly = true
        $.ajax({
            url: "<?=base_url('MPROCD/search')?>",
            data: {search: e.target.value},
            dataType: "json",
            success: function (response) {
                e.target.readOnly = false                
                const ttlrows = response.data.length                    
                let mydes = document.getElementById("mprocd_tblsup_div")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("mprocd_tblsup")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("mprocd_tblsup")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].MPROCD_MDLTYP
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MITM_ITMCD
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].ITMD1
                    newcell = newrow.insertCell(3)
                    newcell.onclick = () => {
                        if(!mprocd_selected_models.includes(response.data[i].MITM_ITMCD)) {
                            mprocd_selected_models.push(response.data[i].MITM_ITMCD)
                            mprocd_toDisplay()
                        }
                    }
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.innerHTML = `<span class="fas fa-check"></span>`
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,ajaxOptions, throwError) {
                e.target.readOnly = false
            }
        });
    }
}

function mprocd_toDisplay() {
    let strElem = ''
    mprocd_selected_models.forEach((x, i) => {        
        strElem += `<span class='badge bg-primary' style='cursor:pointer' title='cancel' onclick="mprocd_cancel(this)">${x}</span> `
    })
    mprocd_selected_item.innerHTML = strElem
}

function mprocd_cancel(p) {
    console.log(p)
    for(let i=0; i < mprocd_selected_models.length; i++) {
        if(mprocd_selected_models[i]===p.innerText) {
            console.log('oooy')
            mprocd_selected_models.splice(i, 1)
            i--
        }
    }
    mprocd_toDisplay()
}

function mprocd_btn_save_eC() {

}

function mprocd_btn_new_eC() {
    mprocd_sso.setData([[],[],[],[],[]])
    mprocd_selected_models = []
    mprocd_selected_item.innerHTML = ``
}
</script>