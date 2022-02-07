<div style="padding: 10px" >
	<div class="container-fluid">
        <div class="row" id="gen_templatesc_stack0">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="gen_templatesc_btn_new" onclick="gen_templatesc_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-success" id="gen_templatesc_btn_save" onclick="gen_templatesc_btn_save_eC()"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>
            <div class="col-md-10 mb-1">
                <span class="badge bg-info" id="gen_templatesc_lblexport"></span>
            </div>
        </div>
        <div class="row" id="gen_templatesc_stack1">
            <div class="col-md-4 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="gen_templatesc_btnplus" onclick="gen_templatesc_btnplus_eC()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="gen_templatesc_btnmins" onclick="gen_templatesc_minusrow('gen_templatesc_tbl')"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <span class="badge bg-info" id="gen_templatesc_lblpastewait"></span>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="gen_templatesc_btn_generate" onclick="gen_templatesc_btn_generate_eC()">Generate</button>                   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="gen_templatesc_divku" onpaste="gen_templatesc_e_pastecol1(event)">
                    <table id="gen_templatesc_tbl" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%">                        
                        <thead class="table-light">
                            <tr>
                                <th class="text-center align-middle" rowspan="2">Assy Code</th>                                
                                <th class="text-center align-middle" rowspan="2">Code</th>
                                <th class="text-center align-middle" colspan="4">Process</th>
                                <th class="text-center align-middle" rowspan="2">SMT-AV</th> <!-- 6-->
                                <th class="text-center align-middle" rowspan="2">SMT-RD</th> <!-- 7-->
                                <th class="text-center align-middle" rowspan="2">Process.1</th> <!-- 8-->
                                <th class="text-center align-middle" rowspan="2">Process.2</th> <!-- 9-->
                                <th class="text-center align-middle" rowspan="2">SMT-HW</th> <!-- 10-->
                                <th class="text-center align-middle" rowspan="2">SMT-HWADD</th> <!-- 11-->
                                <th class="text-center align-middle" rowspan="2">SMT-SP</th> <!-- 12-->
                            </tr>
                            <tr>
                                <th class="text-center">#1</th>                                
                                <th class="text-center">#2</th>
                                <th class="text-center">#3</th>                                
                                <th class="text-center">#4</th>
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
<script>
    var gen_templatesc_selected_row = 1;
    var gen_templatesc_selected_col = 1;
    var gen_templatesc_selected_table = ''
    var gen_templatesc_pub_cuscd = ''

    function gen_templatesc_btn_save_eC() {        
        let a_column0 = []
        let a_column1 = []
        let a_column2 = []
        let a_column3 = []
        let a_column4 = []
        let a_column5 = []
        let a_column10 = []
        let a_column11 = []
        let a_column12 = []          

        const rmtable = document.getElementById('gen_templatesc_tbl').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        for(let i=0; i<rmtablecount; i++) {
            a_column0.push(rmtable.rows[i].cells[0].innerText)
            a_column1.push(rmtable.rows[i].cells[1].innerText)
            a_column2.push(rmtable.rows[i].cells[2].innerText)
            a_column3.push(rmtable.rows[i].cells[3].innerText)
            a_column4.push(rmtable.rows[i].cells[4].innerText)
            a_column5.push(rmtable.rows[i].cells[5].innerText)
            a_column10.push(rmtable.rows[i].cells[10].innerText)
            a_column11.push(rmtable.rows[i].cells[11].innerText)
            a_column12.push(rmtable.rows[i].cells[12].innerText)            
        }
        if(a_column0.length>0) {
            document.getElementById('gen_templatesc_lblpastewait').innerHTML = "Please wait"
            $.ajax({
                type: "POST",
                url: "http://localhost/wms_dev/download_template_scrap",
                data: {
                    a_column0 : a_column0
                    ,a_column1 : a_column1
                    ,a_column2 : a_column2
                    ,a_column3 : a_column3
                    ,a_column4 : a_column4
                    ,a_column5 : a_column5
                    ,a_column10 : a_column10
                    ,a_column11 : a_column11
                    ,a_column12 : a_column12 
                },
                xhr: function () {
                    const xhr = new XMLHttpRequest()
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 2) {
                            if (xhr.status == 200) {
                                xhr.responseType = "blob";
                            } else {
                                xhr.responseType = "text";
                            }
                        }
                    }
                    return xhr
                },
                success: function (response) {                    
                    //Convert the Byte Data to BLOB object.
                    const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                    saveAs(blob, "scrap_template.xlsx")
                    document.getElementById('gen_templatesc_lblpastewait').innerHTML = ""
                }
            });
        } else {
            alertify.message("no data")
        }
    }
    $("#gen_templatesc_divku").css('height', $(window).height()   
    -document.getElementById('gen_templatesc_stack0').offsetHeight 
    -document.getElementById('gen_templatesc_stack1').offsetHeight     
    -100);
    function gen_templatesc_btn_new_eC() {
        document.getElementById('gen_templatesc_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('gen_templatesc_lblexport').innerHTML = ""
    }

    

    function gen_templatesc_btn_generate_eC() {
        document.getElementById('gen_templatesc_btn_generate').disabled = true
        let mydes = document.getElementById("gen_templatesc_divku")
        let myfrag = document.createDocumentFragment()
        let mtabel = document.getElementById("gen_templatesc_tbl")
        let cln = mtabel.cloneNode(true);
        myfrag.appendChild(cln)
        let tabell = myfrag.getElementById("gen_templatesc_tbl")
        let tableku2 = tabell.getElementsByTagName("tbody")[0]
        let newrow, newcell, newText
        let myitmttl = 0;        
        const ttlrows = tableku2.rows.length  
        let guide1 = ''
        let guide2 = ''
        let guide3 = ''
        let guide4 = ''
        for (let i = 0; i<ttlrows; i++){
            guide1 = tableku2.rows[i].cells[2].innerHTML
            guide2 = tableku2.rows[i].cells[3].innerHTML
            guide3 = tableku2.rows[i].cells[4].innerHTML
            guide4 = tableku2.rows[i].cells[5].innerHTML
            if(guide1==='SMT-A' || guide1==='SMT-B' || guide1==='SMT-AB') {
                let isfound = false
                if(tableku2.rows[i].cells[8].innerHTML.length>0) {
                    tableku2.rows[i].cells[2].innerHTML = tableku2.rows[i].cells[8].innerHTML
                    tableku2.rows[i].cells[2].classList.add('bg-success','text-white')
                } else {
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[8].innerHTML.length > 0) {
                            tableku2.rows[i].cells[2].innerHTML = tableku2.rows[i2].cells[8].innerHTML
                            tableku2.rows[i].cells[2].classList.add('bg-info','text-white')
                            isfound = true
                            break
                        }
                    }
                    if(!isfound) {
                        for (let i2 = 0; i2<ttlrows; i2++){ 
                            if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                            && tableku2.rows[i2].cells[9].innerHTML.length > 0) {
                                tableku2.rows[i].cells[2].innerHTML = tableku2.rows[i2].cells[9].innerHTML
                                tableku2.rows[i].cells[2].classList.add('bg-info','text-white')
                                isfound = true
                                break
                            }
                        }
                    }
                }
            } else if (guide1==='SMT-AV') {
                if(tableku2.rows[i].cells[6].innerHTML.length>0) {
                    tableku2.rows[i].cells[2].innerHTML = tableku2.rows[i].cells[6].innerHTML
                    tableku2.rows[i].cells[2].classList.add('bg-success','text-white')
                } else {
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[6].innerHTML.length > 0) {
                            tableku2.rows[i].cells[2].innerHTML = tableku2.rows[i2].cells[6].innerHTML
                            tableku2.rows[i].cells[2].classList.add('bg-info','text-white')
                            break
                        }
                    }
                }               
            } else if (guide1==='SMT-RD' || guide1==='SMT-RG') {
                tableku2.rows[i].cells[2].innerHTML = tableku2.rows[i].cells[7].innerHTML
                tableku2.rows[i].cells[2].classList.add('bg-success','text-white')
            }

            if(guide2==='SMT-A' || guide2==='SMT-B' || guide2==='SMT-AB') {
                if(tableku2.rows[i].cells[9].innerHTML.length>0) {
                    tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i].cells[9].innerHTML
                    tableku2.rows[i].cells[3].classList.add('bg-success','text-white')
                } else {
                    let isfound = false
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[9].innerHTML.length > 0) {
                            tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i2].cells[9].innerHTML
                            tableku2.rows[i].cells[3].classList.add('bg-info','text-white')
                            isfound = true
                            break
                        }
                    }
                    if(!isfound) {
                        for (let i2 = 0; i2<ttlrows; i2++){ 
                            if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                            && tableku2.rows[i2].cells[8].innerHTML.length > 0) {
                                tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i2].cells[8].innerHTML
                                tableku2.rows[i].cells[3].classList.add('bg-info','text-white')
                                isfound = true
                                break
                            }
                        }
                    }
                }               
            } else if (guide2==='SMT-AV') {
                if(tableku2.rows[i].cells[6].innerHTML.length>0) {
                    tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i].cells[6].innerHTML
                    tableku2.rows[i].cells[3].classList.add('bg-success','text-white')
                } else {
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[6].innerHTML.length > 0) {
                            tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i2].cells[6].innerHTML
                            tableku2.rows[i].cells[3].classList.add('bg-info','text-white')
                            break
                        }
                    }
                }                
            } else if (guide2==='SMT-RD' || guide2==='SMT-RG') {
                if(tableku2.rows[i].cells[7].innerHTML.length>0) {
                    tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i].cells[7].innerHTML
                    tableku2.rows[i].cells[3].classList.add('bg-success','text-white')
                } else {
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[7].innerHTML.length > 0) {
                            tableku2.rows[i].cells[3].innerHTML = tableku2.rows[i2].cells[7].innerHTML
                            tableku2.rows[i].cells[3].classList.add('bg-info','text-white')
                            isfound = true
                            break
                        }
                    }
                }
            } 

            if(guide3==='SMT-A' || guide3==='SMT-B' || guide3==='SMT-AB') {
                if(tableku2.rows[i].cells[8].innerHTML.length>0) { 
                    tableku2.rows[i].cells[4].innerHTML = tableku2.rows[i].cells[8].innerHTML
                    tableku2.rows[i].cells[4].classList.add('bg-success','text-white')
                } else {
                    let isfound = false
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[8].innerHTML.length > 0) {
                            tableku2.rows[i].cells[4].innerHTML = tableku2.rows[i2].cells[8].innerHTML
                            tableku2.rows[i].cells[4].classList.add('bg-info','text-white')
                            isfound = true
                            break
                        }
                    }
                    if(!isfound) {
                        for (let i2 = 0; i2<ttlrows; i2++){ 
                            if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                            && tableku2.rows[i2].cells[9].innerHTML.length > 0) {
                                tableku2.rows[i].cells[4].innerHTML = tableku2.rows[i2].cells[9].innerHTML
                                tableku2.rows[i].cells[4].classList.add('bg-info','text-white')
                                isfound = true
                                break
                            }
                        }
                    }
                }
            } else if (guide3==='SMT-AV') {
                tableku2.rows[i].cells[4].innerHTML = tableku2.rows[i].cells[6].innerHTML
                tableku2.rows[i].cells[4].classList.add('bg-success','text-white')
            } else if (guide3==='SMT-RD' || guide2==='SMT-RG') {
                if(tableku2.rows[i].cells[7].innerHTML.length>0) {
                    tableku2.rows[i].cells[4].innerHTML = tableku2.rows[i].cells[7].innerHTML
                    tableku2.rows[i].cells[4].classList.add('bg-success','text-white')
                } else {
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[7].innerHTML.length > 0) {
                            tableku2.rows[i].cells[4].innerHTML = tableku2.rows[i2].cells[7].innerHTML
                            tableku2.rows[i].cells[4].classList.add('bg-info','text-white')
                            isfound = true
                            break
                        }
                    }
                }
            } 

            if(guide4==='SMT-A' || guide4==='SMT-B' || guide4==='SMT-AB') {
                if(tableku2.rows[i].cells[8].innerHTML.length>0) { 
                    tableku2.rows[i].cells[5].innerHTML = tableku2.rows[i].cells[8].innerHTML
                    tableku2.rows[i].cells[5].classList.add('bg-success','text-white')
                } else {
                    let isfound = false
                    for (let i2 = 0; i2<ttlrows; i2++){ 
                        if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                        && tableku2.rows[i2].cells[8].innerHTML.length > 0) {
                            tableku2.rows[i].cells[5].innerHTML = tableku2.rows[i2].cells[8].innerHTML
                            tableku2.rows[i].cells[5].classList.add('bg-info','text-white')
                            isfound = true
                            break
                        }
                    }
                    if(!isfound) {
                        for (let i2 = 0; i2<ttlrows; i2++){ 
                            if (tableku2.rows[i].cells[0].innerHTML ===  tableku2.rows[i2].cells[0].innerHTML 
                            && tableku2.rows[i2].cells[9].innerHTML.length > 0) {
                                tableku2.rows[i].cells[5].innerHTML = tableku2.rows[i2].cells[9].innerHTML
                                tableku2.rows[i].cells[5].classList.add('bg-info','text-white')
                                isfound = true
                                break
                            }
                        }
                    }
                }
            }
        }
        mydes.innerHTML=''
        mydes.appendChild(myfrag)
        document.getElementById('gen_templatesc_btn_generate').disabled = false
    }

    function gen_templatesc_btnplus_eC(){
        gen_templatesc_addrow('gen_templatesc_tbl')
        let mytbody = document.getElementById('gen_templatesc_tbl').getElementsByTagName('tbody')[0]
        gen_templatesc_selected_row = mytbody.rows.length - 1
        gen_templatesc_selected_col = 1
    }
    
   async function get_templatesc_wait() {
        document.getElementById('gen_templatesc_lblpastewait').innerHTML = 'Please wait'
   }

    function gen_templatesc_e_pastecol1(event){        
        // get_templatesc_wait()
        // return new Promise((resolve, reject) => {
            let datapas = event.clipboardData.getData('text/html')
            const gen_templatesc_tbllength = document.getElementById(gen_templatesc_selected_table).getElementsByTagName('tbody')[0].rows.length
            const columnLength = document.getElementById(gen_templatesc_selected_table).getElementsByTagName('tbody')[0].rows[0].cells.length        
            if(datapas===""){
                datapas = event.clipboardData.getData('text')
                let adatapas = datapas.split('\n')
                let ttlrowspasted = 0
                for(let c=0;c<adatapas.length;c++){
                    if(adatapas[c].trim()!=''){
                        ttlrowspasted++
                    }
                }
                let table = $(`#${gen_templatesc_selected_table} tbody`)
                let incr = 0
                if ((gen_templatesc_tbllength-gen_templatesc_selected_row)<ttlrowspasted) {       
                    const needRows = ttlrowspasted - (gen_templatesc_tbllength-gen_templatesc_selected_row)
                    for (let i = 0;i<needRows;i++) {
                        gen_templatesc_addrow(gen_templatesc_selected_table)
                    }
                }            
                for(let i=0;i<ttlrowspasted;i++){                
                    const mcol = adatapas[i].split('\t')
                    const ttlcol = mcol.length                
                    for(let k=0;(k<ttlcol) && (k<columnLength);k++){             
                        table.find('tr').eq((i+gen_templatesc_selected_row)).find('td').eq((k+gen_templatesc_selected_col)).text(mcol[k].trim())
                    }                
                }                            
            } else {            
                let tmpdom = document.createElement('html')
                tmpdom.innerHTML = datapas
                let myhead = tmpdom.getElementsByTagName('head')[0]
                let myscript = myhead.getElementsByTagName('script')[0]
                let mybody = tmpdom.getElementsByTagName('body')[0]
                let mytable = mybody.getElementsByTagName('table')[0]
                let mytbody = mytable.getElementsByTagName('tbody')[0]
                let mytrlength = mytbody.getElementsByTagName('tr').length
                let table = $(`#${gen_templatesc_selected_table} tbody`)
                let incr = 0
                let startin = 0
                if(mytrlength>100) {
                    document.getElementById('gen_templatesc_lblpastewait').innerHTML = 'Please wait'
                    if(!confirm("It will take several minutes, are you sure ?")) {
                        document.getElementById('gen_templatesc_lblpastewait').innerHTML = ''
                        return
                    }
                }
                if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                    startin = 3
                }
                if((gen_templatesc_tbllength-gen_templatesc_selected_row)<(mytrlength-startin)){
                    let needRows = (mytrlength-startin) - (gen_templatesc_tbllength-gen_templatesc_selected_row);                
                    for(let i = 0;i<needRows;i++){
                        gen_templatesc_addrow(gen_templatesc_selected_table);
                    }
                }
                
                let b = 0
                for(let i=startin;i<(mytrlength);i++){
                    let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length
                    for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                        let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText
                        table.find('tr').eq((b+gen_templatesc_selected_row)).find('td').eq((k+gen_templatesc_selected_col)).text(dkol.trim())
                    } 
                    b++
                }
            }
            document.getElementById('gen_templatesc_lblpastewait').innerHTML = ''        
            event.preventDefault()
        // }).then(function()  
        // {
        //     console.log('promis ok')
        // }
        // , function() 
        // { 
        //     console.log('promis rejek')
        // })
        
    }



    function gen_templatesc_minusrow(ptable){
        if(gen_templatesc_selected_table!==''){
            if(ptable!=gen_templatesc_selected_table){
                alertify.message(`wrong button`)
            } else {
                let mytable = document.getElementById(ptable).getElementsByTagName('tbody')[0]
                const mtr = mytable.getElementsByTagName('tr')[gen_templatesc_selected_row]
                const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()                
                mtr.remove()                
            }
        } else {
            alertify.message('nothing to be deleted')
        }
    }

    function gen_templatesc_tbl_tbody_tr_eC(e){
        gen_templatesc_selected_row = e.srcElement.parentElement.rowIndex - 2
        const ptablefocus = e.srcElement.parentElement.parentElement.offsetParent.id
        gen_templatesc_selected_table = ptablefocus
        gen_templatesc_selected_col = e.srcElement.cellIndex        
    }

    

    function gen_templatesc_addrow(ptable){
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {gen_templatesc_tbl_tbody_tr_eC(event)}
        newcell = newrow.insertCell(0)
        newcell.title = 'Assy Code'
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)        
        newcell.title = 'Code'

        newcell = newrow.insertCell(2)
        newcell.title = '#1'
        newcell.innerHTML = ''

        newcell = newrow.insertCell(3)        
        newcell.title = '#2'
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(4)
        newcell.title = '#3'        
        newcell.innerHTML = '0'
        
        newcell = newrow.insertCell(5)
        newcell.title = '#4'        
        newcell.contentEditable = true
        newcell.innerHTML = ''

        newcell = newrow.insertCell(6)
        newcell.title = 'SMT-AV'           
        newcell.innerHTML = ''  

        newcell = newrow.insertCell(7)        
        newcell.title = 'SMT-RD'
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(8)        
        newcell.title = 'Process.1'
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(9)        
        newcell.title = 'Process.2'
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(10)        
        newcell.title = 'SMT-HW'
        newcell.innerHTML = ''
        newcell = newrow.insertCell(11)        
        newcell.title = 'SMT-HWADD'
        newcell.innerHTML = ''
        newcell = newrow.insertCell(12)        
        newcell.title = 'SMT-SP'
        newcell.innerHTML = ''                            
        gen_templatesc_selected_table = ptable        
    }

    function gen_templatesc_btnfindmodcust_eC() {
        if(document.getElementById('gen_templatesc_businessgroup').value==='-'){
            alertify.message('Please select business group first')
        } else {
            gen_templatesc_load_customer()
            $("#gen_templatesc_MODCUS").modal('show')
        }
    }
    $("#gen_templatesc_MODCUS").on('shown.bs.modal', function(){
        $("#gen_templatesc_txtsearchcus").focus()
    })

    function gen_templatesc_load_customer() {
        const searchby = document.getElementById('gen_templatesc_srch_cust_by').value
        const searchvalue = document.getElementById('gen_templatesc_txtsearchcus').value
        const bisgrup = document.getElementById('gen_templatesc_businessgroup').value
        document.getElementById('gen_templatesc_tblcus').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Please wait...</td></tr>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('RCV/osPO')?>",
            data: {searchby: searchby, searchvalue: searchvalue, bisgrup: bisgrup},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("gen_templatesc_tblcus_div")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("gen_templatesc_tblcus")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("gen_templatesc_tblcus")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){ 
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = () => {
                        $("#gen_templatesc_MODCUS").modal('hide')
                        document.getElementById('gen_templatesc_custname').value = response.data[i].MSUP_SUPNM
                        document.getElementById('gen_templatesc_curr').value = response.data[i].MSUP_SUPCR
                        gen_templatesc_pub_cuscd = response.data[i].MSUP_SUPCD
                        document.getElementById('gen_templatesc_txt_doc').focus()
                    }
                    newcell = newrow.insertCell(0)
                    newcell.style.cssText = "cursor:pointer"                        
                    newcell.innerHTML = response.data[i].MSUP_SUPCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MSUP_SUPCR
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].MSUP_SUPNM
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].MSUP_ADDR1
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
            }
        })
    }

    function gen_templatesc_businessgroup_e_onchange() {
        document.getElementById('gen_templatesc_custname').value='';
        document.getElementById('gen_templatesc_curr').value='';    
        $('#gen_templatesc_tblcus tbody').empty();
        document.getElementById('gen_templatesc_btnfindmodcust').focus();
    }
</script>