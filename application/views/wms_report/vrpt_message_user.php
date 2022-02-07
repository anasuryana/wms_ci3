<div style="padding: 10px" >
	<div class="container-fluid" >
        <div class="row">
            <div class="col-md-12 mb-1" >
                <div class="input-group input-group-sm mb-1">
                    <select id="rpt_msg_user_monthfilter" class="form-select">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>                
                    <input type="number" class="form-control" id="rpt_msg_user_year" maxlength="4">
                    <button title="Apply filter" class="btn btn-primary btn-sm" id="rpt_msg_user_btn_filter" onclick="rpt_msg_user_btn_filter_eCK()"><i class="fas fa-filter"></i></button>
                </div>
            </div>
        </div>
        <div id="rpt_msg_user_container">
            <div class="row">
                <div class="col-md-12 mb-1" >
                    <div class="card">
                        <div class="card-header">
                            Message
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                            <p>A well-known quote, contained in a blockquote element.</p>
                            <footer class="blockquote-footer">Ana <cite title="Source Title">ane</cite></footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    document.getElementById('rpt_msg_user_year').value = new Date().getFullYear()
    var rpt_msg_user_g_rs = []
    var rpt_msg_user_g_string=(new Date().getMonth()+1).toString();
    if(rpt_msg_user_g_string<10){
        rpt_msg_user_g_string = '0'+rpt_msg_user_g_string;
    } else {
        rpt_msg_user_g_string = rpt_msg_user_g_string.toString();
    }    
    document.getElementById('rpt_msg_user_monthfilter').value =rpt_msg_user_g_string;
    function rpt_msg_user_initdata(){
        const mmonth = document.getElementById('rpt_msg_user_monthfilter').value
        const myear = document.getElementById('rpt_msg_user_year').value
        document.getElementById('rpt_msg_user_container').innerHTML = 'Please wait'
        $.ajax({
            data: {month : mmonth, year: myear},
            url: "<?=base_url('MSG/getdetailbyUser')?>",
            dataType: "json",
            success: function (response) {
                rpt_msg_user_g_rs = response
                let mydes = document.getElementById("rpt_msg_user_container")
                let myfrag = document.createDocumentFragment()
                mydes.innerHTML = ''
                const ttlrows = response.data.length
                for(let i=0;i<ttlrows; i++) {
                    let isReffDataExist = false
                    let reffdata = ''
                    if(response.data[i].MSG_REFFDATA) {
                        isReffDataExist = true
                        reffdata = JSON.parse(response.data[i].MSG_REFFDATA)                        
                    }
                    const orow = document.createElement('div')
                    orow.classList.add('row')
                    const ocol = document.createElement('div')
                    ocol.classList.add('col','mb-1')                    
                    const card = document.createElement('div')
                    card.classList.add('card')
                    const cardHeader = document.createElement('div')
                    cardHeader.classList.add('card-header')
                    cardHeader.innerHTML = response.data[i].MSG_CREATEDAT
                    const cardBody = document.createElement('div')
                    cardBody.classList.add('card-body')
                    cardBody.innerHTML = response.data[i].MSG_TXT + "<br><br>"
                    const cardButtonRead = document.createElement('button')
                    cardButtonRead.classList.add('btn','btn-sm', 'btn-primary')
                    if(response.data[i].MSG_READAT) {
                        cardButtonRead.innerHTML = 'I have read'
                        cardButtonRead.disabled = true
                    } else {
                        cardButtonRead.innerHTML = 'Read'
                    }
                    cardButtonRead.onclick = function(cardButtonRead) {
                        cardButtonRead.target.innerHTML ='I have read'; 
                        cardButtonRead.target.disabled =true;
                        rpt_msg_user_setRead({msgline: response.data[i].MSG_LINE, msgcreatedat:response.data[i].MSG_CREATEDAT })}
                    if(isReffDataExist) {
                        const cardTableDiv = document.createElement('div')
                        cardTableDiv.classList.add('table-responsive')
                        const cardTable = document.createElement('table')
                        cardTable.classList.add('table', 'table-sm', 'table-hover', 'table-bordered')
                        const cardTableHead = document.createElement('thead')
                        const cardTableBody = document.createElement('tbody')
                        cardTableHead.classList.add('table-light')
                        let newrow , newcell
                        newrow = cardTableHead.insertRow(-1)
                        if(response.data[i].MSG_TOPIC==='COMPONENT_EXCEEDED') {
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = 'Part Code'
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = 'Qty'
                            newcell.classList.add('text-end')
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = 'Assy Code'
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = 'Confirm Date'
                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = 'Nomor Aju'                            
                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = 'Nomor Pendaftaran'                            
                            
                            for(let key in reffdata) {
                                newrow = cardTableBody.insertRow(-1)
                                newcell = newrow.insertCell(0)
                                newcell.innerHTML = reffdata[key].PST_PARTCD
                                newcell = newrow.insertCell(1)
                                newcell.innerHTML = numeral(reffdata[key].PST_EXCQTY).format(',')
                                newcell.classList.add('text-end')
                                newcell = newrow.insertCell(2)
                                newcell.innerHTML = reffdata[key].PST_ASSYCD
                                newcell = newrow.insertCell(3)
                                newcell.innerHTML = response.data[i].CONFIRMDATE
                                newcell = newrow.insertCell(4)
                                newcell.innerHTML = reffdata[key].PST_NOAJU
                                newcell = newrow.insertCell(5)
                                newcell.innerHTML = reffdata[key].PST_NOPEN
                            }
                        } else {
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = 'ID'
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = 'WO'
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = 'TXID'
                            for(let key in reffdata) {
                                newrow = cardTableBody.insertRow(-1)
                                newcell = newrow.insertCell(0)
                                newcell.innerHTML = reffdata[key].ID
                                newcell = newrow.insertCell(1)
                                newcell.innerHTML = reffdata[key].WO
                                newcell = newrow.insertCell(2)
                                newcell.innerHTML = reffdata[key].TXID
                            }
                        }
                        
                        cardTable.appendChild(cardTableHead)
                        cardTable.appendChild(cardTableBody)
                        cardTableDiv.appendChild(cardTable)
                        cardBody.appendChild(cardTableDiv)
                    }
                    cardBody.appendChild(cardButtonRead)
                    card.appendChild(cardHeader)
                    card.appendChild(cardBody)
                    ocol.appendChild(card)
                    orow.appendChild(ocol)
                    myfrag.appendChild(orow)
                }
                mydes.appendChild(myfrag)
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
                document.getElementById('rpt_msg_user_container').innerHTML = xthrow
            }
        })
    }

    function rpt_msg_user_setRead(pdata){
        const ttlrows = rpt_msg_user_g_rs.data.length
        for(let i=0; i< ttlrows; i++) {            
            if(pdata.msgcreatedat===rpt_msg_user_g_rs.data[i].MSG_CREATEDAT) {
                $.ajax({
                    type: "post",
                    url: "<?=base_url('MSG/read')?>",
                    data:{increatedat: pdata.msgcreatedat, inline: pdata.msgline},
                    dataType: "json",
                    success: function (response) {
                        
                    }
                })
                rpt_msg_user_g_rs.data[i].MSG_READAT = moment().format('YYYY-MM-DD HH:mm:ss')
            }
        }
        let ttlUnread = 0
        for(let i=0; i< ttlrows; i++) { 
            if(!rpt_msg_user_g_rs.data[i].MSG_READAT) {
                ttlUnread++
            }
        }        
        const myfind = devNode.tree('findBy', {field: 'id', value: 'CL'})
        if(ttlUnread>0) {
            devNode.tree('update', {target: myfind.target, text: `Abnormal <span class="badge bg-danger">${ttlUnread}</span>`})
        } else {
            devNode.tree('update', {target: myfind.target, text: `Abnormal`})
        }
    }
    
    function rpt_msg_user_btn_filter_eCK() {
        rpt_msg_user_initdata()
    }

    rpt_msg_user_initdata()
</script>