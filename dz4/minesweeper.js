//iscrtava tablicu buttona
//na početku su buttoni disabled, tek nakon dobivanja id-a igre postaju klikabilni
function iscrtajTablicu(nRows, nCols, nMines){
  var table = $("#table");
  for(var i = 0; i < nRows; ++i){
    var row = $('<tr></tr>');
    for(var j = 0; j < nCols; ++j){
      var index = i * nCols + j;
      var button = $('<button class="tableButton" id="'+ index +'" disabled>'+ " " +'</button>');
      var data = $("<td></td>");
      data.append(button);
      row.append(data);
    }
    table.append(row);
  }
}

//buttonu s indexom index dodaje za text stiring data ako je data razlicito od 0
//button postaje disabled
function srediButton(index, data){
  var buttons = $(".tableButton");
  //console.log('index: ' + index); 
  buttons.eq(index).attr('disabled', 'disabled');
  if(data!='0')
    buttons.eq(index).html(data)
}

//sve buttone postavlja na disabled
function disableButtons(){
  var buttons = $('.tableButton');
  for(var i = 0; i < buttons.length; ++i)
    buttons.eq(i).attr('disabled', 'disabled');
}

//funkcija broji koliko imamo klikabilnih buttona
function enabledButtons(){
  var buttons = $('.tableButton');
  var brojac = 0;
  for(var i = 0; i < buttons.length; ++i)
    if(buttons.eq(i).attr('disabled')!='disabled')
      brojac++;
  console.log('imamo ' + brojac + 'enabled buttons');
  return brojac;
}

//lijevim klikom na button tablice poziva se ajax poziv
//dohvaća se informacija da li je na tom polju mina
//ako je kraj igre
//ako nije onda prikazemo korsniku informacije o sudjednim poljima
//izbrojimo koliko imamo enabled buttons
//ako ih ima koliko i mina onda je korisnik pobjedio
//inace se igra nastavlja
function postaviFunButtona(idIgre, nRows, nCols, nMines){
  console.log(idIgre);
  console.log("sredimo buttone");
  var buttons=$('.tableButton');
  for(var i = 0; i < buttons.length; ++i){
    buttons.eq(i).removeAttr('disabled');
    var idButtona = parseInt(buttons.eq(i).attr('id'));
    row = parseInt(idButtona/nCols);
    col = idButtona%nCols;
    buttons.eq(i).on('contextmenu', function() { return false; } );
    buttons.eq(i).on('mousedown', {row: row, col: col, idIgre: idIgre, nMines: nMines}, function(event){
        if(event.button == 2){      //desni klik
          console.log("desni klik na button");
          $(this).html('?');
        }
        else if(event.button == 0){       //lijevi klik
          console.log("lijevi klik na button (" + event.data.row + ", " + event.data.col + ") " + event.data.idIgre);
          $.ajax({
            
//url:"http://rp2.studenti.math.hr/~zbujanov/dz4/uncoverField.php",
            url:"http://192.168.89.245/~zbujanov/dz4/uncoverField.php",
            type:"GET",
            data:
            {
              id: event.data.idIgre,
              row: event.data.row,
              col: event.data.col
            },
            dataType: "jsonp",
            success: function(data){
              console.log(data.boom);
              if(data.boom){
                $('#p_rez').html("BOOOOM!");
                disableButtons();
                $('#unos').show();
              }
              else{
                for(var k = 0; k < data.fields.length; ++k)
                  srediButton(data.fields[k].row * nCols + data.fields[k].col, data.fields[k].mines);
                if(enabledButtons() == nMines){
                  disableButtons();
                  $('#p_rez').html("Cestitamo, pobjedili ste!");
                  disableButtons();
                  $('#unos').show();
                }
              }
            },
            error: function(status){
              console.log("greska ajax: " + status.error);
            }
          });
        }
      });
  }
}

function ocistiInpute(){
  $("#nRows").val('');
  $("#nCols").val('');
  $("#nMines").val('');
}

$(document).ready(function(){

  //var nRows = 9, nCols = 9, nMines = 10;
  //stvaramo tablicu s odgovarajućim brojem redaka i stupaca
  
  //button početak igre
  $('#startButton').on('click', function(){
    $('p').html('');
    nRows = parseInt($("#nRows").val());
    nCols = parseInt($("#nCols").val());
    nMines = parseInt($("#nMines").val());

    console.log(nRows + " " + nCols + " " + nMines);
    console.log(nRows*nCols);

    if(1 <= nRows && 1 <= nCols && nRows <= 20 && nCols <= 20 && 0 <= nMines && nMines <= nCols * nRows){
      
      $('#unos').hide();
      ocistiInpute();

      $('tr').remove();
      iscrtajTablicu(nRows, nCols, nMines);

      var buttons=$('.tableButton');
      for(var i = 0; i < buttons.length; ++i){
        buttons.eq(i).html('')
        buttons.eq(i).off();
      }
      //dohvatimo id igre
        $.ajax({
          
//url:"http://rp2.studenti.math.hr/~zbujanov/dz4/getGameId.php",
         url: "http://192.168.89.245/~zbujanov/dz4/getGameId.php",
          data:
          {
            nRows: nRows,
            nCols: nCols,
            nMines: nMines
          },
          type: "GET",
          dataType: "jsonp",
          success: function(data){
            postaviFunButtona(data.id, nRows, nCols, nMines);
            console.log("dobili smo id igre," + data.id + ", mozemo zapoceti igru");
          },
          error: function(error){
            console.log("greska pri dobivanju id-a igre: " + error.error);
          }
        });
      }
      else{
        $('#p_greska').html("Niste unijeli dobre parametre: nRows, nCols iz intervala [1, 20], "+
                            "nMines iz intervala [0, reci*stupci]");
        ocistiInpute();
        $('tr').remove();
      }
  });

});
