  /*
  ARQUIVO PARA MANIPULAR OPERAÇÕES NA PÁGINA DOS RELATÓRIOS
  */

  //ATUALIZAR O RELATÓRIO
 function atualizar() {

        // Get a reference to the embedded report HTML element
        var embedContainer = $('#powerBI')[0];
        // Get a reference to the embedded report.
        report = powerbi.get(embedContainer);
        // Displays the report in full screen mode.
        report.refresh();
 }

function fullscreen() {
    
    // Get a reference to the embedded report HTML element
    var embedContainer = $('#powerBI')[0];
    // Get a reference to the embedded report.
    report = powerbi.get(embedContainer);
    // Displays the report in full screen mode.
    report.fullscreen();
}

