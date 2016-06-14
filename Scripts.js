// @link http://buzut.fr/2014/11/13/ajouter-bouton-lediteur-wysiwyg-wordpress/
// @link http://stackoverflow.com/questions/24871792/tinymce-api-v4-windowmanager-open-what-widgets-can-i-configure-for-the-body-op

( function() {
  tinymce.PluginManager.add( 'ScripturaButtonMap', function( editor, url ) {
    editor.addButton( 'ScripturaButtonMapAdd', { // Ajoute un bouton à tinyMCE
      text: false,
      icon: false,
      title: 'Créer une carte',
      image: url + '/Images/Map48.png',
      onclick: function() {
        content = [
          //{
          //  type: 'textbox', // Type input
          //  name: 'id',
          //  label: 'Index de la carte',
          //  value: 'map'
          //},
          {
            type: 'textbox', // Type input
            name: 'title',
            label: 'Titre de la carte',
            value: 'Ma carte'
          },
          {
            type: 'listbox',
            name: 'type',
            label: 'Type',
            values: [
              { text: 'Roadmap', value: 'ROADMAP' },
              { text: 'Satellite', value: 'SATELLITE' },
              { text: 'Hybrid', value: 'HYBRID' },
              { text: 'Terrain', value: 'TERRAIN' },
              { text: 'Perso', value: 'perso' }
            ]
          },
          {
            type: 'textbox',
            name: 'lat',
            label: 'Latitude (GPS)',
            value: '48.8583' // @note Emplacement de la Tour Eiffel par défaut
          },
          {
            type: 'textbox',
            name: 'lng',
            label: 'Longitude (GPS)',
            value: '2.2944'
          },
          {
            type: 'textbox',
            name: 'zoom',
            label: 'Zoom (de 1 à 21)',
            value: '16'
          },
          /*
          @todo La liste suivante fonctionne mais l'interface TinyMCE n'est pas en mesure d'afficher tous les champs...
          {
            type: 'listbox',
            name: 'zoom',
            label: 'Zoom',
            values: [
              //{ text: '1', value: '1' },
              //{ text: '2', value: '2' },
              //{ text: '3', value: '3' },
              //{ text: '4', value: '4' },
              //{ text: '5', value: '5' },
              { text: '6', value: '6' },
              { text: '7', value: '7' },
              { text: '8', value: '8' },
              { text: '9', value: '9' },
              { text: '10', value: '10' },
              { text: '11', value: '11' },
              { text: '12', value: '12' },
              { text: '13', value: '13' },
              { text: '14', value: '14' },
              { text: '15', value: '15' },
              { text: '16', value: '16' },
              { text: '17', value: '17' },
              { text: '18', value: '18' },
              { text: '19', value: '19' },
              { text: '20', value: '20' },
              { text: '21', value: '21' }
            ]
          },
          */
          {
            type: 'listbox',
            name: 'markertype',
            label: 'Type de markeur',
            values: [
              { text: 'Marker1', value: '1' },
              { text: 'Marker2', value: '2' },
              { text: 'Marker3', value: '3' }
            ]
          },
          {
            type: 'listbox',
            name: 'height',
            label: 'Hauteur de la carte',
            values: [
              { text: '20% de l\'écran', value: '20vh' },
              { text: '25% de l\'écran', value: '25vh' },
              { text: '50% de l\'écran', value: '50vh' },
              { text: '100% de l\'écran', value: '100vh' },
              { text: '200 pixels', value: '200px' },
              { text: '300 pixels', value: '300px' },
              { text: '400 pixels', value: '400px' },
              { text: '500 pixels', value: '500px' },
              { text: '600 pixels', value: '600px' },
              { text: '700 pixels', value: '700px' },
              { text: '800 pixels', value: '800px' },
              { text: '900 pixels', value: '900px' },
            ]
          }
        ];
        editor.windowManager.open( { // Ouverture d'une fenêtre modale pour récupération des paramètres
          title: 'Paramètres de la carte',
          autoScroll: true, // @link http://stackoverflow.com/questions/24681575/tinymce-4-x-editor-windowmanager-open-autoscroll-and-overflow-issue
          classes: 'scriptura-panel', // prefix auto add : 'mce-scriptura-panel'
          body: content,
          onsubmit: function( e ) { // Action a effectuer lorsque l'utilisateur valide la modale
            editor.insertContent( // On insère le contenu à l'endroit du curseur
              //'[_map id="' + e.data.id + '"'
              '[_map id="map"'
              + ' title="' + e.data.title + '"'
              + ' type="' + e.data.type + '"'
              + ' zoom="' + e.data.zoom + '"'
              + ' lat="' + e.data.lat + '"'
              + ' lng="' + e.data.lng + '"'
              + ' markertype="' + e.data.markertype + '"'
              + ' height="' + e.data.height + '"]'
            );
          }
        } );
      }
    } );
  } );
} )();

