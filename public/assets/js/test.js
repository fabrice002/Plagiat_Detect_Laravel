// Chemin vers le document PDF
var pdfPath = 'D:/laragon/www/PlagiatDetect/public/TEST.pdf';

// Définir le chemin vers le fichier PDF
// var pdfPath = 'path/to/your.pdf';

// Obtenir une référence à l'élément canvas
var canvas = document.getElementById('pdfCanvas');

// Charger le document PDF
PDFJS.getDocument(pdfPath).promise.then(function (pdf) {
  // Sélectionner la première page
  pdf.getPage(1).then(function (page) {
    var viewport = page.getViewport({ scale: 1 });

    // Définir la taille du canvas en fonction de la taille de la page
    canvas.width = viewport.width;
    canvas.height = viewport.height;

    // Récupérer le contexte 2D du canvas
    var context = canvas.getContext('2d');

    // Rendre la page PDF sur le canvas
    page.render({ canvasContext: context, viewport: viewport }).promise.then(function () {
      // Extraire le contenu du canvas en tant qu'image base64
      var imageData = canvas.toDataURL('image/png');

      // Afficher l'image ou effectuer d'autres opérations
      console.log(imageData);
    });
  });
});
