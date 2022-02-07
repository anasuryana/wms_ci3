<!DOCTYPE html> 
<html>   
    <head > 
	<meta charset="utf-8"/>
        <title>RM Inventory</title> 
        <style>            
        </style>
    </head>   
<body> 
    <input type="file" name="inputfile"
            id="inputfile"> 
    <br> 
    <div id="divku">        
        <table id="tabelkita" >
            <thead>
                <th>USER</th>
                <th>ITEM CODE</th>
                <th>QTY</th>
                <th>LOT</th>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
      
    <script type="text/javascript"> 
        document.getElementById('inputfile') 
            .addEventListener('change', function() { 
              
            let fr=new FileReader();
            fr.onload=function(){
                let res = fr.result;
                let resa = res.split('\n'); ///res.split('\n');
                // document.getElementById('output') 
                //         .textContent=fr.result; 
                let tbl = document.getElementById('tabelkita');
                let tbl_bd = tbl.getElementsByTagName('tbody')[0];
                tbl_bd.innerHTML ='';
                let userid = resa[0];
                let itemcod = [];
                let itemlot = [];
				// let i = 0;
				// let str_prep = '3N2';
				// while(i<resa.length){
				// 	if(str_prep=='3N2' && resa[i].substr(0,3)=='3N2'){
				// 		itemcod.push('?');
				// 	} else {
					
				// 	}
				// 	if(resa[i].substr(0,3)=='3N1'){
				// 		itemcod.push(resa[i]);
				// 		if(resa[i+1].substr(0,3)=='3N2'){
				// 			itemlot.push(resa[i+1]);
				// 		} else {
				// 			itemlot.push('?');
				// 		}
				// 		i++;
				// 	} 
				// 	str_prep=resa[i+1].substr(0,3);
				// 	i++;
				// }
                let mystr = '';
				for(let i =1;i<resa.length;i++){
                    if(resa[i].substr(0,3)=='3N2'){
                        if(i<resa.length-1){
                            if(resa[i+1].substr(0,3)!='3N1' && resa[i+1].substr(0,3).trim() !='' && resa[i+1].substr(0,3).trim()!='OMR' && resa[i+1].substr(0,3).trim()!='TOY'							
							&& resa[i+1].substr(0,3).trim()!='STA' && resa[i+1].substr(0,2).trim()!='LP'){
                                alert('periksa baris lot tes , '+i);
                                return;
                            }
                        }	
						if(resa[i].substr(0,3)=='3N2' && resa[i-1].substr(0,3)!='3N1'){
							alert('SEBELUM N2 HARUSNYA N1 baris' + i);
							return;
						}						
                    }
					
                }
                for(let i =1;i<resa.length;i++){
                    if(resa[i].substr(0,3)=='3N1'){
                        if(i<resa.length-1){
                            if(resa[i+1].substr(0,3)!='3N2'){
                                alert('periksa baris '+i);
                                return;
                            }
                        }
                    }
                    // if(mystr!=resa[i].substr(0,3)=='3N1'){
                    //     mystr = resa[i].substr(0,3);
                    // } else {
                    //     alert("periksa " + i);
                    // }
                }
                for(let i =1;i<resa.length;i++){
					// if(resa[i].substr(0,3)=='3N1'){
					// 	itemcod.push(resa[i]);
					// 	if(resa[i+1].substr(0,3)=='3N2'){
					// 		itemlot.push(resa[i+1]);
					// 	} else {
					// 		itemlot.push('?');
					// 	}						
					// } else if(resa[i].substr(0,3)=='3N2'){
					// 	itemlot.push(resa[i]);
					// }
                    switch(resa[i].substr(0,3)){
                        case '3N1':
                           itemcod.push(resa[i]);break;
                        case '3N2':
                           itemlot.push(resa[i]);break;
                    }
               }
                let ttlrows = itemlot.length;
                let mydes = document.getElementById("divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("tabelkita");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("tabelkita");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];                
                let newrow, newcell, newText;
                let tmpstr, sitem,sqty, slot;                
                tableku2.innerHTML='';
                for (let i = 0; i<ttlrows; i++){
                    if(itemcod[i].includes(" ")){
                        tmpstr = itemcod[i].split(' ');
                        sqty = tmpstr[1];
                        sitem = tmpstr[0].substr(3, tmpstr[0].length);                        
                        tmpstr = itemlot[i].split(' ');
                        slot = tmpstr[1];
                    } else {
						//if(typeof itemlot[i] === 'undefined'){
							//sqty = '?';
						//	slot= '?';
							//sitem = itemcod[i].substr(3,itemcod[i].length );
						//} else {
							tmpstr = itemlot[i].split(' ');                        
							sqty = tmpstr[1];
							sitem = itemcod[i].substr(3,itemcod[i].length );
							slot = tmpstr[2]+"SIN";
						//}                        
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(userid);            
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(sitem);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(sqty);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(slot);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            } 
            
            fr.readAsText(this.files[0]); 
        }) 
    </script> 
</body> 
  
</html> 