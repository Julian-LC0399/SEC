//Función Para llenar Tabla de Encabezado
function fillTable(i,header,movements){
    var tabla = "";
    var year = $("#Year").val();
    var meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    
        tabla+= "<table class='table table-striped table-sm table-bordered' style='width:60%;display:inline-table;'>";
            tabla+="<thead>";
                    tabla+="<tr class='text-center'>";
                    tabla+="<td colspan='4' class='title-table'><h4>"+meses[i - 1]+' '+year+"</h4></td>";
                    tabla+="</tr>";
                    tabla+="<tr class='table-Header'>";
                    tabla+="<td class='text-center' style='width:33%'>CUENTA</td>";
                    tabla+="<td class='text-center' style='width:33%'>CLIENTE</td>";
                    tabla+="<td class='text-center' style='width:33%'>SALDO INICIAL</td>";
                  
                tabla+="</tr>";
            tabla+="</thead>";
            tabla+="<tbody>";
      if(header['error'] !==true){
              var balanceIncial = header['data'][0]['SALDOINICIALS'];
              var cuenta = $("#account").val();
              var name = header['data'][0]['NOMBRE'];
              var direccion = header['data'][0]['DIRECCION'];
              var moneda = header['data'][0]['MONEDA'];
  
                      tabla+="<tr class='table-body'>";
                      tabla+="<td class='text-center'>"+header['data'][0]['CUENTA']+"</td>";
                      tabla+="<td class='text-left'>"+header['data'][0]['NOMBRE']+"</td>";
                      tabla+="<td class='text-right'>"+formatMoney(balanceIncial)+"</td>";
                  tabla+="</tr>";
              tabla+="</tbody>";
          tabla+="</table>";
          tabla+="<p id=mesImprimir"+i+" class='printers'></p>"
          $("#tableDinamic").append(tabla);
          CrearTablaMovements(i,movements,balanceIncial,cuenta,name,direccion,moneda);
      }else{
          tabla+="<tr class='table-body'>";
                      tabla+="<td class='text-center' colspan='4'>"+header['details']+"</td>";
                  tabla+="</tr>";
              tabla+="</tbody>";
          tabla+="</table>";
          $("#tableDinamic").append(tabla);
      }
  }
  
  //Funcion Para llenar Tabla de Movimientos
  function CrearTablaMovements(i,movements,balanceIncial,cuenta,name,direccion,moneda){
    var tabla = "";
    let count = 0;
    let sumaDebito = 0;
    let sumaCredito = 0;
    let subtotal = 0;
    let monto = 0;
    let sum = balanceIncial;
    
        tabla+= "<table class='table table-striped table-sm table-bordered'>";
            tabla+="<thead>";
                tabla+="<tr class='table-Header'>";
                    tabla+="<td class='text-center' style='width:12%' colspan='2'>FECHA</td>";
                    tabla+="<td class='text-center' style='width:8%'>REFERENCIA</td>";
                    tabla+="<td class='text-center' style='width:20%'>DESCRIPCIÓN</td>";
                    tabla+="<td class='text-center' style='width:20%'>DEBITO</td>";
                    tabla+="<td class='text-center' style='width:20%'>CREDITO</td>";
                    tabla+="<td class='text-center' style='width:20%'>SALDO</td>";
                tabla+="</tr>";
            tabla+="</thead>";
            tabla+="<tbody>";
       //if(movements['error'] !== true){
            $.each(movements['data'], function (index, dat){
              count++
              monto = formatMoney(dat.MONTO);
              var hora = (dat.HORA < 0 ) ? dat.HORA*-1 : dat.HORA;
              let hours = (moment(String(hora).padStart(6, '0'), "Hms").format("HH:mm") === 'Invalid date') ? '00:00':moment(String(hora).padStart(6, '0'), "Hms").format("HH:mm");
              let date =  moment(dat.FECHA_PROCESO, "DD/MM/YYYY").format("DD/MM/YYYY")
              // let processDate = date+' - '+hours;
              let serial = dat.SERIAL;
              let description = dat.DESCRIPCION.toUpperCase();
              let debit = dat.TIPO === '0' ? monto : '';
              let credit = dat.TIPO === '5' ? monto : '';
      
              if(dat.TIPO === '0'){
                  sumaDebito += number(dat.MONTO);
                  sum = number(sum) - number(dat.MONTO);
                  subtotal = number(subtotal) + number(sum);
              }else{
                  sumaCredito += number(dat.MONTO);
                  sum = number(sum) + number(dat.MONTO);
                  subtotal = number(subtotal) + number(sum);
              }
      
              //let saldo = sum !== '0' ? formatMoney(sum) : '0,00';
              
               let saldo = formatMoney(parseFloat(sum).toFixed(2))
                tabla+="<tr class='table-body'>";
                    tabla+="<td class='text-center w-10'>"+date+"</td>"
                    tabla+="<td class='text-center w-6'>"+hours+"</td>"
                    tabla+="<td class='text-right w-8'>"+serial+"</td>"
                    tabla+="<td class='text-left w-20'>"+description+"</td>"
                    tabla+="<td class='text-right w-20'>"+debit+"</td>"
                    tabla+="<td class='text-right w-20'>"+credit+"</td>"
                    tabla+="<td class='text-right w-20'>"+saldo+"</td>"
               
      });
      tabla+="</tr>";
      tabla+="</tbody>";
  tabla+="</table>";
  
    $("#tableDinamic").append(tabla);
    CrearTablaResume(i,subtotal,count,sumaDebito,sumaCredito,sum,balanceIncial,cuenta,name,direccion,moneda);
    
//   }else{
//                 tabla+="<tr class='table-body'>";
//                   tabla+="<td class='text-center'colspan='8'>"+movements['details']+"</td>"  
//       tabla+="</tr>";
//       tabla+="</tbody>";
//   tabla+="</table>";
  
//     $("#tableDinamic").append(tabla);
  
//   }
  }
  
  //Funcion Para llenar Tabla resumen de Saldo
  function CrearTablaResume(i,subtotal,count,sumaDebito,sumaCredito,sum,balanceIncial,cuenta,name,direccion,moneda){
    var tabla = "";
    let avgresult = subtotal/count;
    let promedio = formatMoney(avgresult);
    let sumadeDebitos = formatMoney(sumaDebito);
    let sumadeCreditos = formatMoney(sumaCredito);
    let sumas = formatMoney(parseFloat(sum).toFixed(2));
    var cuentas = $('#Account').val();
    var ano = $('#Year').val();
  
      tabla+="<table class='table table-striped table-sm table-bordered' style='float:right;width:60%;'>";
          tabla+="<thead>";
              tabla+="<tr class='table-Header'>";                                        
                  tabla+="<td class='text-center' style='width:25%'>SALDO PROMEDIO</td>";                                                                            
                  tabla+="<td class='text-center' style='width:25%'>TOTAL DEBITOS</td>";
                  tabla+="<td class='text-center' style='width:25%'>TOTAL CREDITOS</td>";
                  tabla+="<td class='text-center' style='width:25%'>SALDO FINAL  </td>";                                     
              tabla+="</tr>";
          tabla+="</thead>";
          tabla+="<tbody>";
              tabla+="<tr class='table-body'>";
              tabla+="<td class='text-right'>"+promedio+"</td>";                                                                            
              tabla+="<td class='text-right'>"+sumadeDebitos+"</td>";
              tabla+="<td class='text-right'>"+sumadeCreditos+"</td>";
              tabla+="<td class='text-right'>"+sumas+"</td>";        
              tabla+="</tr>";
                     
          tabla+="</tbody>";
      tabla+="</table>";
    $("#tableDinamic").append(tabla);
    var button = "<a download href='../Page/report.php?account="+cuentas+"&nombre="+name+"&direccion="+direccion+"&saldofinal="+sumas+"&saldoinicial="+balanceIncial+"&saldopromedio="+promedio+"&totalcredito="+sumadeCreditos+"&totaldebitos="+sumadeDebitos+"&mes="+i+"&year="+ano+"&moneda="+moneda+"' class='btn  btn-print print' id='mes"+i+"'><i class='fas fa-print'></i></a>";
    $("#mesImprimir"+i+"").append(button);
  
  }
  
//Funcion para convertir un numero en decimal
  function number(n){
      return Number.parseFloat(n);
  }
  
  //Funcion para Dar formato a los montos
  function formatMoney(n){
      return accounting.formatMoney(n,"",2,".",",");
  }
  

  //Funcion para Descargar todos los PDF
  function downloadURI(uri, name) { 
    var link = document.createElement("a"); 
        link.setAttribute('download',name);
        link.href = uri; 
        document.body.appendChild(link);
        link.click();
        link.remove(); 
}