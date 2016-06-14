<?php

// -----------------------------------------------------------------------------
// @Plugin Name: Scriptura Maps
// @Plugin URI: https://github.com/Scriptura/ScripturaMaps
// @Description: Cartes Google Maps pour WordPress
// @Version: 0.0.1
// @Author: Olivier Chavarin
// @Author URI: https://scriptura.github.io/
// @License: ISC
// -----------------------------------------------------------------------------

// @link https://wordpress.org/plugins/add/
// @link http://www.filsdetut.fr/mettre-plugin-en-ligne-wordpress-repository/
// @link https://generatewp.com/take-shortcodes-ultimate-level/


// -----------------------------------------------------------------------------
// @subsection  Shortcode
// @description Configuration du shortcode
// -----------------------------------------------------------------------------

if (!is_admin()) :

// [_map]
add_shortcode( '_map', 'ScripturaMapsShortcode' );

function ScripturaMapsShortcode( $atts ) {

    ob_start();

    $pluginUri = plugin_dir_url( __FILE__ );
    $atts = shortcode_atts( // Attributs par défaut
        [
            'title' => 'Ma carte',
            'type'   => 'ROADMAP',
            'zoom'   => '12', // @params: 1-21
            'markertype' => 1,
            'lat' => '45',
            'lng' => '7',
            'height' => '50vh'
        ],
        $atts
    );
    //$attId = $atts['id'];
    $attTitle = $atts[ 'title' ];
    $attType = $atts[ 'type' ];
    $attZoom = $atts[ 'zoom' ];
    $attMarkerType = $atts[ 'markertype' ];
    $attLat = $atts[ 'lat' ];
    $attLng = $atts[ 'lng' ];
    $attHeight = $atts[ 'height' ];
?>
<script>
function initMap() {

  var marker1 = '<?php echo $pluginUri; ?>Images/Marker1.svg';
  var marker2 = '<?php echo $pluginUri; ?>Images/Marker2.svg';
  var marker3 = '<?php echo $pluginUri; ?>Images/Marker3.svg';


  var locations = [
  ['<?php echo $attTitle; ?>', <?php echo $attLat; ?>, <?php echo $attLng; ?>, marker<?php echo $attMarkerType; ?>]
  ];


  <?php if ($attType == 'perso') : ?>
  // @link https://developers.google.com/maps/documentation/javascript/styling#stylers
  // @link http://googlemaps.github.io/js-samples/styledmaps/wizard/index.html
  var customMapType = new google.maps.StyledMapType(
    [
      {
        stylers: [
          //{hue: #777777},
          {visibility: 'simplified'}, // @params : on, off, simplified
          //{gamma: 0.5},
          //{weight: 0.5},
          //{lightness: '50'},
          {saturation: -100}
        ]
      },
      {
        featureType: 'poi', // Point of interest
        stylers: [
          {visibility: 'off'}
        ]
      },
      {
        featureType: 'road',
        stylers: [
          {visibility: 'simplified'}
        ]
      },
      {
        elementType: 'labels',
        stylers: [
          //{visibility: 'off'},
          {lightness: '20'}
        ]
      }
      //{
      //  featureType: 'water',
      //  stylers: [
      //    {color: '#dddddd'}
      //]
      //}
    ], {
      name: 'Custom Style'
  });

  var customMapTypeId = 'custom_style';
  <?php endif; ?>

  var map = new google.maps.Map(document.getElementById('map'), {
    // @note Inactif si fonction d'autocentrage .fitBounds() :
    zoom: <?php echo $attZoom; ?>,
    center: new google.maps.LatLng(locations[0][1], locations[0][2])
    <?php if ($attType == 'perso') : ?>
    ,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE, customMapTypeId] // Options utilisateur
    }
    <?php endif; ?>
  });
  <?php if ($attType == 'perso') : ?>
  map.mapTypes.set(customMapTypeId, customMapType); // ajout des styles personnalisés
  map.setMapTypeId(customMapTypeId);
  <?php endif; ?>

  /*
  // Option pour une seconde carte :

  var map2 = new google.maps.Map(document.getElementById('map2'), {
    zoom: 12,
    center: new google.maps.LatLng(locations[0][1], locations[0][2]),
  });

  marker2 = new google.maps.Marker({
    map: map2,
    position: position1,
    draggable: true
  });
  */

  var infowindow = new google.maps.InfoWindow();
  //var bounds = new google.maps.LatLngBounds(); // Initialisation des limites de la carte

  for (i = 0; i < locations.length; i++) {

    var latLng = new google.maps.LatLng(locations[i][1], locations[i][2]);

    marker = new google.maps.Marker({
      position: latLng,
      map: map,
      icon: locations[i][3],
      animation: google.maps.Animation.DROP
    });

    //bounds.extend(latLng); // Récupération des coordonnées du marqueur pour la fonction fitBounds

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infowindow.setContent(locations[i][0]); // Info list
        infowindow.open(map, marker);

        if (marker.getAnimation() !== null) { // Animation marker
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }

        if (marker.getAnimation() !== null) { // End animation marker
          setTimeout(function() {
            marker.setAnimation(null);
          }, 2000);
        }

      }
    })(marker, i));

  }

  //map.fitBounds(bounds); // Calcul des limites de la cartes pour autocentrage en fonction des markers

}
</script>
<script src="https://maps.googleapis.com/maps/api/js?callback=initMap" async defer/></script>
<div id="map" style="height:<?php echo $attHeight; ?>"></div>
<?php

$output = ob_get_clean();
return $output;

}

endif; // admin


// -----------------------------------------------------------------------------
// @subsection  TinyMCE
// @description Configuration de TinyMCE v4
// -----------------------------------------------------------------------------

if ( is_admin() ) {

    function ScripturaMapsCmd ()
    {
      global $typenow; // Récupère la variable de contexte du type de post
      if( ! in_array( $typenow, [ 'post', 'page' ] ) ) // Activation du plugin pour les articles et les pages
        return;
      add_filter( 'mce_external_plugins', 'ScripturaAddTinymcePluginMap' ); // Ajout javascript à l'éditeur de WP
      add_filter( 'mce_buttons', 'ScripturaAddTinymceButtonMap' ); // Ajoute un bouton à la première ligne de boutons
    }
    add_action( 'admin_head', 'ScripturaMapsCmd' );

    function ScripturaAddTinymcePluginMap( $plugin )
    {
      $plugin[ 'ScripturaButtonMap' ] = plugins_url( 'Scripts.js', __FILE__ ); // Emplacement de la fonction des bouttons
      return $plugin;
    }

    function ScripturaAddTinymceButtonMap( $buttons )
    { // Id du bouton pour faire la correspondance avec le JS
      array_push( $buttons, 'ScripturaButtonMapAdd' ); // Passage d'un tableau contenant l'id du bouton, pour ajouter d'autres boutons il suffit de passer les autres id
      return $buttons;
    }

    //@link http://stackoverflow.com/questions/24681575/tinymce-4-x-editor-windowmanager-open-autoscroll-and-overflow-issue
    wp_enqueue_style( 'ScripturaMaps', plugins_url( 'Styles.css', __FILE__ ) );

}

