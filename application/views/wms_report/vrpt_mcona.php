<div style="padding: 10px" >
	<div class="container-fluid">        
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document</label>
                    <input type="text" class="form-control" id="rpt_mcona_txt_doc" readonly>
                    <button class="btn btn-primary" id="rpt_mcona_btnmod" onclick="rpt_mcona_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Contract Date</label>                    
                    <input type="text" class="form-control" id="rpt_mcona_txt_date" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Due Date</label>
                    <input type="text" class="form-control" id="rpt_mcona_txt_duedate" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="rpt_mcona_tbl_div">
                    <table id="rpt_mcona_tbl" class="table table-sm table-striped table-bordered table-hover caption-top" style="width:100%;font-size:75%">
                        <caption>DATA MATERIAL</caption>
                        <thead class="table-light">
                            <tr>
                                <th rowspan="3" class="text-center align-middle">NO.</th>
                                <th rowspan="3" class="text-center align-middle">HS CODE</th>
                                <th rowspan="3" class="text-center align-middle">KODE BARANG</th>
                                <th rowspan="3" class="text-center align-middle">URAIAN JENIS BARANG</th>
                                <th rowspan="3" class="text-center align-middle">JUMLAH</th>
                                <th rowspan="3" class="text-center align-middle">SATUAN</th>                                
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
                <div class="table-responsive" id="rpt_mcona_tbl_fg_div">
                    <table id="rpt_mcona_tbl_fg" class="table table-sm table-striped table-bordered table-hover caption-top" style="width:100%;font-size:75%">
                        <caption>DATA FINISHED GOODS</caption>
                        <thead class="table-light">
                            <tr>
                                <th rowspan="3" class="text-center align-middle">NO.</th>
                                <th rowspan="3" class="text-center align-middle">HS CODE</th>
                                <th rowspan="3" class="text-center align-middle">KODE BARANG</th>
                                <th rowspan="3" class="text-center align-middle">URAIAN JENIS BARANG</th>
                                <th rowspan="3" class="text-center align-middle">JUMLAH</th>
                                <th rowspan="3" class="text-center align-middle">SATUAN</th>                                
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
                <div class="table-responsive" id="rpt_mcona_tbl_rtn_div">
                    <table id="rpt_mcona_tbl_rtn" class="table table-sm table-striped table-bordered table-hover caption-top" style="width:100%;font-size:75%">
                        <caption>RETURN MATERIAL</caption>
                        <thead class="table-light">
                            <tr>
                                <th rowspan="3" class="text-center align-middle">NO.</th>
                                <th rowspan="3" class="text-center align-middle">HS CODE</th>
                                <th rowspan="3" class="text-center align-middle">KODE BARANG</th>
                                <th rowspan="3" class="text-center align-middle">URAIAN JENIS BARANG</th>
                                <th rowspan="3" class="text-center align-middle">JUMLAH</th>
                                <th rowspan="3" class="text-center align-middle">SATUAN</th>                            
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
<div class="modal fade" id="rpt_mcona_MODITM">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <select id="rpt_mcona_srchby" class="form-select">
                            <option value="0">Document Number</option>                            
                        </select>
                        <input type="text" class="form-control" id="rpt_mcona_txtsearch" onkeypress="rpt_mcona_txtsearch_eKP(event)" maxlength="44" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="rpt_mcona_tblitm_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rpt_mcona_tblitm_div">
                        <table id="rpt_mcona_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>Due Date</th>
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
    function rpt_mcona_report() {
        
        // Reset
        rpt_mcona_reset_table('rpt_mcona_tbl')
        rpt_mcona_reset_table('rpt_mcona_tbl_fg')
        rpt_mcona_reset_table('rpt_mcona_tbl_rtn')
        // End

        // Initialize data
        const dataItem = [
            {
                "ITMCD" : "ITEMA",
                "ITMNM" : "RESISTOR",
                "ITMQT" : "3000",
                "ITMME" : "PCS",
                "ITMHC" : "HSCODEA"
            },
            {
                "ITMCD" : "ITEMB",
                "ITMNM" : "RESISTOR ARRAY",
                "ITMQT" : "3000",
                "ITMME" : "PCS",
                "ITMHC" : "HSCODEB"
            },
            {
                "ITMCD" : "ITEMC",
                "ITMNM" : "PCB",
                "ITMQT" : "1500",
                "ITMME" : "PCS",
                "ITMHC" : "HSCODEC"
            }
        ]
        const dataItemDelivery_h = [
            {
                "ITMDELDATE" : "2021-01-01",
                "ITMDELDO" : "0001/SMT/VII/2021",
                "ITMDELZNOPEN" : "000001"
            },
            {
                "ITMDELDATE" : "2021-01-01",
                "ITMDELDO" : "0002/SMT/VII/2021",
                "ITMDELZNOPEN" : "000002"
            }
        ]
        const dataItemDelivery_d = [
            // {
            //     "ITMDELCD" : "ITEMA",
            //     "ITMDELQT" : "60",
            //     "ITMDELDATE" : "2021-01-01",
            //     "ITMDELDO" : "0001/SMT/VII/2021",
            //     "ITMDELZNOPEN" : "000001"
            // },
            // {
            //     "ITMDELCD" : "ITEMB",
            //     "ITMDELQT" : "60",
            //     "ITMDELDATE" : "2021-01-01",
            //     "ITMDELDO" : "0001/SMT/VII/2021",
            //     "ITMDELZNOPEN" : "000001"
            // },
            // {
            //     "ITMDELCD" : "ITEMC",
            //     "ITMDELQT" : "30",
            //     "ITMDELDATE" : "2021-01-01",
            //     "ITMDELDO" : "0001/SMT/VII/2021",
            //     "ITMDELZNOPEN" : "000001"
            // },
            // {
            //     "ITMDELCD" : "ITEMC",
            //     "ITMDELQT" : "60",
            //     "ITMDELDATE" : "2021-01-01",
            //     "ITMDELDO" : "0002/SMT/VII/2021",
            //     "ITMDELZNOPEN" : "000002"
            // }
        ]
        let mydes = document.getElementById("rpt_mcona_tbl_div")
        let myfrag = document.createDocumentFragment()
        let mtabel = document.getElementById("rpt_mcona_tbl")
        let cln = mtabel.cloneNode(true)
        myfrag.appendChild(cln)
        let tabell = myfrag.getElementById("rpt_mcona_tbl")
        let tableku2 = tabell.getElementsByTagName("tbody")[0]
        let newrow, newcell, newText;
        let nourut = 0
        //generate header
        for(let r in dataItemDelivery_h) {
            nourut++            
            let elem = document.createElement('th')
            elem.classList.add('text-center')
            elem.innerHTML = nourut
            tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
            //add second tr
            if(tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]) {                
                elem = tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]
                let elem2 = document.createElement('th')
                elem2.classList.add('text-center')
                elem2.innerHTML = dataItemDelivery_h[r].ITMDELZNOPEN 
                                    + ' / ' 
                                    + dataItemDelivery_h[r].ITMDELDATE
                elem.appendChild(elem2)
            } else {                
                console.log('sini')
                newrow = tabell.getElementsByTagName('thead')[0].insertRow(-1)
                elem2 = document.createElement('th')
                elem2.classList.add('text-center')
                elem2.innerHTML = dataItemDelivery_h[r].ITMDELZNOPEN 
                                    + ' / ' 
                                    + dataItemDelivery_h[r].ITMDELDATE
                newrow.appendChild(elem2)
            }            
            //end
            //Add third tr
            if(tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[2]) { 
                elem = tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[2]
                let elem2 = document.createElement('th')
                elem2.classList.add('text-center')
                elem2.innerHTML = 'DO NO : ' + dataItemDelivery_h[r].ITMDELDO
                elem.appendChild(elem2)
            } else {
                console.log('sono')
                newrow = tabell.getElementsByTagName('thead')[0].insertRow(-1)                
                elem2 = document.createElement('th')
                elem2.classList.add('text-center')
                elem2.innerHTML = 'DO NO : ' + dataItemDelivery_h[r].ITMDELDO
                newrow.appendChild(elem2)
            }
            //end
        }
        let elem = document.createElement('th')
        elem.innerHTML = "Balance"
        elem.classList.add('align-middle','text-center')
        elem.rowSpan = 3
        tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
        elem = document.createElement('th')
        elem.innerHTML = "Status"
        elem.classList.add('align-middle','text-center')
        elem.rowSpan = 3
        tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
        //end
        //generate detail
        nourut = 0
        for(let r in dataItem) {
            nourut++
            newrow = tableku2.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.innerHTML = nourut
            newcell = newrow.insertCell(1)
            newcell.innerHTML = dataItem[r].ITMHC
            newcell = newrow.insertCell(2)
            newcell.innerHTML = dataItem[r].ITMCD
            newcell = newrow.insertCell(3)
            newcell.innerHTML = dataItem[r].ITMNM
            newcell = newrow.insertCell(4)
            newcell.classList.add('text-end')
            newcell.innerHTML = numeral(dataItem[r].ITMQT).format(',')
            newcell = newrow.insertCell(5)
            newcell.classList.add('text-center')
            newcell.innerHTML = dataItem[r].ITMME
            ///prepare cells as a container value for delivery data
            let nourut2 = 6
            for(let h in dataItemDelivery_h) { 
                newcell = newrow.insertCell(nourut2)
                newcell.classList.add('text-end')
                for(let d in dataItemDelivery_d) {
                    if(dataItem[r].ITMCD===dataItemDelivery_d[d].ITMDELCD && dataItemDelivery_h[h].ITMDELDO === dataItemDelivery_d[d].ITMDELDO) {
                        newcell.innerHTML = dataItemDelivery_d[d].ITMDELQT
                    }
                }
                nourut2++
            }
            newcell = newrow.insertCell(nourut2)
            newcell.classList.add('text-end')
            nourut2++
            newcell = newrow.insertCell(nourut2)
            newcell.classList.add('text-end')
        }
        //end
        
        mydes.innerHTML=''
        mydes.appendChild(myfrag)
        // End
    }
    function rpt_mcona_btnmod_eC(){
        $("#rpt_mcona_MODITM").modal('show')
  
    }

    function rpt_mcona_reset_table(pTableID) {
        let tbl_rtn = document.getElementById(pTableID)
        let tbl_header_rtn = tbl_rtn.getElementsByTagName('thead')[0]
        let tbl_rtn_headerCount = tbl_rtn.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].getElementsByTagName('th').length
        if(tbl_rtn_headerCount>=7) {
            if( tbl_header_rtn.getElementsByTagName('tr')[2]) {
                tbl_header_rtn.getElementsByTagName('tr')[2].remove()
            }
            if (tbl_header_rtn.getElementsByTagName('tr')[1]) {
                tbl_header_rtn.getElementsByTagName('tr')[1].remove()
            }
            for(let i=(tbl_rtn_headerCount-1); i>5; i-- ) {
                tbl_rtn.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].getElementsByTagName('th')[i].remove()
            }
        }
        tbl_rtn.getElementsByTagName('tbody')[0].innerHTML = ''
    }
    $("#rpt_mcona_MODITM").on('shown.bs.modal', function(){
        $("#rpt_mcona_txtsearch").focus()
    })
    function rpt_mcona_txtsearch_eKP(e){
        if (e.key === 'Enter') {
            const msearchBy = document.getElementById('rpt_mcona_srchby').value
            const msearchKey = document.getElementById('rpt_mcona_txtsearch').value
            document.getElementById('rpt_mcona_tblitm').getElementsByTagName('tbody')[0].innerHTML = ''
            document.getElementById('rpt_mcona_tblitm_lblinfo').innerHTML = "Please wait..."
            $.ajax({
                type: "GET",
                url: "<?=base_url('MCONA/search')?>",
                data: {searchBy: msearchBy, searchKey: msearchKey},
                dataType: "JSON",
                success: function (response) {
                    const ttlrows = response.data.length
                    document.getElementById('rpt_mcona_tblitm_lblinfo').innerHTML = `${ttlrows} row(s) found`
                    if( response.status[0].cd===1){
                        let mydes = document.getElementById("rpt_mcona_tblitm_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("rpt_mcona_tblitm")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("rpt_mcona_tblitm")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML=''
                        for (let i = 0; i<ttlrows; i++){                            
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.onclick = () => {rpt_mcona_load_data({
                                doc: response.data[i].MCONA_DOC
                                ,contract_date : response.data[i].MCONA_DATE 
                                ,due_date : response.data[i].MCONA_DUEDATE 
                            })}
                            newcell.innerHTML = response.data[i].MCONA_DOC
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MCONA_DATE
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].MCONA_DUEDATE
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    document.getElementById('rpt_mcona_tblitm_lblinfo').innerHTML = xthrow
                }
            });
        }
    }
    function rpt_mcona_load_data(pdata) {
        document.getElementById('rpt_mcona_txt_doc').value = pdata.doc
        document.getElementById('rpt_mcona_txt_date').value = pdata.contract_date
        document.getElementById('rpt_mcona_txt_duedate').value = pdata.due_date
        rpt_mcona_reset_table('rpt_mcona_tbl')
        rpt_mcona_reset_table('rpt_mcona_tbl_fg')
        rpt_mcona_reset_table('rpt_mcona_tbl_rtn')
        $.ajax({
            type: "GET",
            url: "<?=base_url('MCONA/report')?>",
            data: {doc: pdata.doc},
            dataType: "JSON",
            success: function (response) {
                let newrow, newcell, newText;
                //#1
                let mydes = document.getElementById("rpt_mcona_tbl_fg_div")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("rpt_mcona_tbl_fg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rpt_mcona_tbl_fg")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let nourut = 0
                //generate header
                for(let r in response.data_fg_h) {
                    nourut++
                    let elem = document.createElement('th')
                    elem.classList.add('text-center')
                    elem.innerHTML = nourut
                    tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                    //add second tr
                    if(tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]) {                
                        elem = tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]
                        let elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = response.data_fg_h[r].DLV_NOAJU 
                                            + ' / ' 
                                            + response.data_fg_h[r].DLV_BCDATE
                        elem.appendChild(elem2)
                    } else {
                        newrow = tabell.getElementsByTagName('thead')[0].insertRow(-1)
                        elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = response.data_fg_h[r].DLV_NOAJU 
                                            + ' / ' 
                                            + response.data_fg_h[r].DLV_BCDATE
                        newrow.appendChild(elem2)
                    }            
                    //end
                    //Add third tr
                    if(tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[2]) { 
                        elem = tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[2]
                        let elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = 'DO NO : ' + response.data_fg_h[r].DLV_ID
                        elem.appendChild(elem2)
                    } else {                        
                        newrow = tabell.getElementsByTagName('thead')[0].insertRow(-1)                
                        elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = 'DO NO : ' + response.data_fg_h[r].DLV_ID
                        newrow.appendChild(elem2)
                    }
                    //end
                }
                let elem = document.createElement('th')
                elem.innerHTML = "Balance"
                elem.classList.add('align-middle','text-center')
                elem.rowSpan = 3
                tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                elem = document.createElement('th')
                elem.innerHTML = "Status"
                elem.classList.add('align-middle','text-center')
                elem.rowSpan = 3
                tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                //end
                //generate detail
                nourut = 0
                for(let r in response.data_fg_m) {
                    nourut++
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = nourut
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_fg_m[r].MITM_HSCD
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data_fg_m[r].MCONA_ITMCD
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_fg_m[r].MITM_ITMD1
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data_fg_m[r].MCONA_QTY).format(',')
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data_fg_m[r].MITM_STKUOM
                    ///prepare cells as a container value for delivery data
                    let nourut2 = 6
                    let balance = numeral(response.data_fg_m[r].MCONA_QTY).value()
                    for(let h in response.data_fg_h) { 
                        newcell = newrow.insertCell(nourut2)
                        newcell.classList.add('text-center')
                        for(let d in response.data_fg_d) {
                            if(response.data_fg_m[r].MCONA_ITMCD===response.data_fg_d[d].MCONA_ITMCD && response.data_fg_h[h].DLV_ID === response.data_fg_d[d].DLV_ID) {
                                balance-= numeral(response.data_fg_d[d].DLV_QTY).value()
                                newcell.innerHTML = numeral(response.data_fg_d[d].DLV_QTY).format(',')
                            }
                        }
                        nourut2++
                    }
                    newcell = newrow.insertCell(nourut2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(balance).format(',')
                    nourut2++
                    newcell = newrow.insertCell(nourut2)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = balance > 0 ? 'OPEN' 
                        : balance == 0 ? 'CLOSED' 
                        : 'OVER'
                }
                //end
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
                //#end
                //#2
                mydes = document.getElementById("rpt_mcona_tbl_div")
                myfrag = document.createDocumentFragment()
                mtabel = document.getElementById("rpt_mcona_tbl")
                cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                tabell = myfrag.getElementById("rpt_mcona_tbl")
                tableku2 = tabell.getElementsByTagName("tbody")[0]
                nourut = 0
                //generate header
                for(let r in response.data_rm_h) {
                    nourut++
                    let elem = document.createElement('th')
                    elem.classList.add('text-center')
                    elem.innerHTML = nourut
                    tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                    //add second tr
                    if(tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]) {                
                        elem = tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]
                        let elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = response.data_rm_h[r].RCV_BCNO 
                                            + ' / ' 
                                            + response.data_rm_h[r].RCV_BCDATE
                        elem.appendChild(elem2)
                    } else {
                        newrow = tabell.getElementsByTagName('thead')[0].insertRow(-1)
                        elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = response.data_rm_h[r].RCV_BCNO 
                                            + ' / ' 
                                            + response.data_rm_h[r].RCV_BCDATE
                        newrow.appendChild(elem2)
                    }            
                    //end
                    //Add third tr
                    if(tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[2]) { 
                        elem = tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[2]
                        let elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = 'DO NO : ' + response.data_rm_h[r].RCV_DONO
                        elem.appendChild(elem2)
                    } else {                        
                        newrow = tabell.getElementsByTagName('thead')[0].insertRow(-1)                
                        elem2 = document.createElement('th')
                        elem2.classList.add('text-center')
                        elem2.innerHTML = 'DO NO : ' + response.data_rm_h[r].RCV_DONO
                        newrow.appendChild(elem2)
                    }
                    //end
                }
                elem = document.createElement('th')
                elem.innerHTML = "Balance"
                elem.classList.add('align-middle','text-center')
                elem.rowSpan = 3
                tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                elem = document.createElement('th')
                elem.innerHTML = "Status"
                elem.classList.add('align-middle','text-center')
                elem.rowSpan = 3
                tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                elem = document.createElement('th')
                elem.innerHTML = "Jumlah Kedatangan"
                elem.classList.add('align-middle','text-center')
                elem.rowSpan = 3
                tabell.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].appendChild(elem)
                //end
                //generate detail
                nourut = 0
                for(let r in response.data_rm_m) {
                    nourut++
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = nourut
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_rm_m[r].MITM_HSCD
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data_rm_m[r].MCONA_ITMCD
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_rm_m[r].MITM_ITMD1
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data_rm_m[r].MCONA_QTY).format(',')
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data_rm_m[r].MITM_STKUOM
                    ///prepare cells as a container value for delivery data
                    let nourut2 = 6
                    let balance = numeral(response.data_rm_m[r].MCONA_QTY).value()
                    let totalinc = 0;
                    for(let h in response.data_rm_h) { 
                        newcell = newrow.insertCell(nourut2)
                        newcell.classList.add('text-center')
                        for(let d in response.data_rm_d) {
                            if(response.data_rm_m[r].MCONA_ITMCD===response.data_rm_d[d].MCONA_ITMCD && response.data_rm_h[h].RCV_DONO === response.data_rm_d[d].RCV_DONO) {
                                balance-= numeral(response.data_rm_d[d].RCV_QTY).value()
                                newcell.innerHTML = numeral(response.data_rm_d[d].RCV_QTY).format(',')
                                totalinc+=numeral(response.data_rm_d[d].RCV_QTY).value()
                            }
                        }
                        nourut2++
                    }
                    newcell = newrow.insertCell(nourut2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(balance).format(',')
                    nourut2++
                    newcell = newrow.insertCell(nourut2)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = balance > 0 ? 'OPEN' 
                        : balance == 0 ? 'CLOSED' 
                        : 'OVER'
                    nourut2++
                    newcell = newrow.insertCell(nourut2)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = numeral(totalinc).format(',')
                }
                //end
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
                //#end
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
        $("#rpt_mcona_MODITM").modal('hide')
    }
</script>