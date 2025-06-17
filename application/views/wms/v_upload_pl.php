<style>
    .beda {
        background-color: yellow;
        color: red;
        font-weight: bold;
    }
</style>

<div style="padding: 5px">
    <div class="container-fluid">
        <div class="row">
            <div class="col mb-1">
                <div class="input-group">
                    <input type="file" id="rcv_pl_file_upload" name="file_upload[]" class="form-control" multiple accept=".xls,.xlsx">
                    <button id="rcv_pl_btn_startimport" onclick="rcv_pl_btn_startimport_on_click(this)" class="btn btn-primary btn-sm">Start Importing</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-1" id="rcv_pl_info_container">

            </div>
        </div>
    </div>
</div>
<script>
    function rcv_pl_btn_startimport_on_click(pThis) {
        const formData = new FormData();
        const files = $('#rcv_pl_file_upload')[0].files;

        if (files.length === 0) {
            alertify.warning(`Please select file`)
            return
        }

        for (let i = 0; i < files.length; i++) {
            formData.append('file_upload[]', files[i]);
        }

        pThis.disabled = true

        if(!confirm('Are you sure ?')) {
            return
        }
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>receiving/upload-pl",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.message(response.message)
                rcv_pl_load_dicrepancies()
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false
            }
        });
    }

    function rcv_pl_load_dicrepancies() {
        
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>receiving/diff-with-master",            
            dataType: "json",
            success: function (response) {
                if(response.data.length > 0) {
                    rcv_pl_info_container.innerHTML = `<div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-danger">⚠️ Dispcrepancy Report</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">Different Part Name</h6>

                        <div class="table-responsive">
                            ${rcv_pl_render_table(response.data)}
                        </div>
                    </div>
                    </div>`

                    rcv_pl_info_container.querySelectorAll('tr').forEach(row => {
                        const tdA = row.querySelector('.text-a');
                        const tdB = row.querySelector('.text-b');

                        if (tdA && tdB) {
                            const [newA, newB] = highlightDifference(tdA.textContent, tdB.textContent);
                            tdA.innerHTML = newA;
                            tdB.innerHTML = newB;
                        }
                    });
                }
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        });
    }

    rcv_pl_load_dicrepancies()

    function rcv_pl_render_table(data) {
        // Buat elemen table
        const table = document.createElement('table');
        table.className = 'table table-striped table-bordered table-hover';       

        // Buat thead
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        const headers = ['DO Number', 'Part Code', 'Part Name (Upload)', 'Part Name (Master)'];
        headers.forEach(text => {
            const th = document.createElement('th');
            th.scope = 'col';
            th.textContent = text;
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Buat tbody
        const tbody = document.createElement('tbody');
       
        data.forEach(item => {
            const row = document.createElement('tr');

            let theCol = document.createElement('td');
            theCol.textContent = item.delivery_doc;
            row.appendChild(theCol);

            theCol = document.createElement('td');
            theCol.textContent = item.item_code;
            row.appendChild(theCol);

            theCol = document.createElement('td');
            theCol.classList.add('text-a')
            theCol.textContent = item.item_name;
            row.appendChild(theCol);
            
            theCol = document.createElement('td');
            theCol.classList.add('text-b')
            theCol.textContent = item.MITM_SPTNO;
            row.appendChild(theCol);

            tbody.appendChild(row);
        });

        table.appendChild(tbody);

        return table.outerHTML

    }

    function highlightDifference(textA, textB) {
        const maxLength = Math.max(textA.length, textB.length);
        let resultA = '', resultB = '';

        for (let i = 0; i < maxLength; i++) {
            const charA = textA[i] || '';
            const charB = textB[i] || '';

            if (charA === charB) {
                resultA += charA;
                resultB += charB;
            } else {
                resultA += `<span class="beda">${charA || ''}</span>`;
                resultB += `<span class="beda">${charB || ''}</span>`;
            }
        }

        return [resultA, resultB];
    }
</script>