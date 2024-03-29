<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION


//Incluir Bootstrap CSS
function bootstrap_css() {
    wp_enqueue_style( 'bootstrap_css', 
          get_stylesheet_directory_uri() . '/css/bootstrap.min.css', 
          array(), 
          ); 
   }
   add_action( 'wp_enqueue_scripts', 'bootstrap_css');
   
//    // Incluir Bootstrap JS
   function bootstrap_js() {
    wp_enqueue_script( 'bootstrap_js', 
          get_stylesheet_directory_uri() . '/js/bootstrap.min.js', 
          array('jquery'), 
          '5.3.2', 
          true); 
   }
   add_action( 'wp_enqueue_scripts', 'bootstrap_js');


function funciones_js(){
	if(is_page('prueba-consumo-2')){
		wp_enqueue_script('funciones_js',
		get_stylesheet_directory_uri() . '/js/funciones.js',
		array(), '1.0.0', true
		);	
	}
	
}
add_action('wp_enqueue_scripts','funciones_js'); 



   /* ---------- Prueba de Consumo API's ------------------*/

// Filtro para agregar contenido a una página de WordPress
add_filter('the_content', 'dcms_add_custom_content');

// Agregamos contenido sólo a la página con el título "Contenido Vinos"
function dcms_add_custom_content($content){

	if ( is_page('prueba-consumo-2'))
	{
		$html = get_data_api2();
		return $content.$html;
	}

	if ( is_page('prueba-consumo-1'))
	{
		$html = get_data_api();
		return $content.$html;
	}
	
	if ( is_page('prueba-consumo-3'))
	{
		$html = get_data_api3();
		return $content.$html;
	}
	
	if ( is_page('prueba-consumo-4'))
	{
		// session_start();
		// $tkn = $_SESSION['token'];
		
		
		// $html = '<h2>El Token del usuario es: </h2><b>'.$tkn.'</b>';

        $html =  '<!-- Example split danger button -->
		<div class="btn-group">
		  <button type="button" class="btn btn-info">Prueba BTN</button>
		  <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
			<span class="visually-hidden">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu">
			<li><a class="dropdown-item" href="#">Acción 1</a></li>
			<li><a class="dropdown-item" href="#">Accion 2</a></li>
			<li><a class="dropdown-item" href="#">Accion 3</a></li>
			<li><hr class="dropdown-divider"></li>
			<li><a class="dropdown-item" href="#">Otra Cosa</a></li>
		  </ul>
		</div>
        
        ';  
		return $content.$html;
	}
	
	return $content;
	

    

}



// Función que se encarga de recuperar los datos de la API externa
function get_data_api(){
	$url = 'https://api.sampleapis.com/switch/games';
	$response = wp_remote_get($url);

	if (is_wp_error($response)) {
		error_log("Error: ". $response->get_error_message());
		return false;
	}

	$body = wp_remote_retrieve_body($response);

	$data = json_decode($body);

	$template = '
 		<h2>Listado # 1 Switch G</h2>
 		<table class="tabla-resultados">
                    <thead>
					<tr>
						<th>id</th>
						<th>Name</th>
						<th>genre first</th>
						<th>Developers</th>
					</tr>
                    </thead>
                    <tbody>
					{data}
                    </tbody>
				</table>';

	if ( $data ){
		$data2 = array_slice($data, 350, 20);
		$str = '';
		foreach ($data2 as $d) {
			$str .= "<tr>";			
			$str .= "<td>{$d->id}</td>";
			$str .= "<td>{$d->name}</td>";
			$str .= "<td>{$d->genre[0]}</td>";
			$str .= "<td>{$d->developers[0]}</td>";
			$str .= "</tr>";
		}
	}

	$html = str_replace('{data}', $str, $template);

	return $html;
}

function get_data_api2(){
	$url = 'https://restcountries.com/v3.1/subregion/Northern%20Europe?fields=ccn3,name,capital,population';
	$response = wp_remote_get($url);

	if (is_wp_error($response)) {
		error_log("Error: ". $response->get_error_message());
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	

	$data = json_decode($body);

	$template = '
                    <button class="btn btn-primary" id="selectAllButton">Seleccionar todos</button>
                    <button class="btn btn-danger" id="deselectAllButton">Deseleccionar todos</button>
    
                    <table class="table">
                    <thead>
						<th></th>
						<th>id</th>
						<th>Name</th>
						<th>Capital</th>
						<th>Population</th>
                    </thead>
                    <tbody>
					{data}
                    </tbody>
				</table>

                <h3>Total Población: <span id="totalValue">0</span></h3>
                <button class="btn btn-success" id="saveButton">Guardar selección</button>
                '
                
                ;

	if ( $data ){
		
		$str = '';
		foreach ($data as $d) {
			$str .= "<tr>";			
			$str .= "<td><input class='form-check-input checkbox1' type='checkbox' value='".htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8')."'></td>";
			$str .= "<td>{$d->ccn3}</td>";
			$str .= "<td>{$d->name->official}</td>";
			$str .= "<td>{$d->capital[0]}</td>";
			$str .= "<td>{$d->population}</td>";
			$str .= "</tr>";
		}
	}

	$html = str_replace('{data}', $str, $template);

	return $html;
}

function get_data_api3(){
	
	$url = 'http://localhost:5189/api/TipoDocumento/ObtenerTiposDeDocumento';
	$response = wp_remote_get($url);

	if (is_wp_error($response)) {
		error_log("Error: ". $response->get_error_message());
		return false;
	}
	
	$url_login = 'http://localhost:5189/api/Login/LoginAuth';

	$body2 = [
		'codigoTipoDocumento'  => 'TPDOC_001',
		'numeroDocumento' => '1015',
		'claveIngreso' => 'clave123',
	];

	$body2 = wp_json_encode( $body2 );

	$options = [
		'body'        => $body2,
		'headers'     => [
			'Content-Type' => 'application/json',
		],
	];
	
	$response2 = wp_remote_post( $url_login, $options );

	if ( is_wp_error( $response2 ) ) {
		$error_message = $response2->get_error_message();
		echo "Algo no sirvio: $error_message";
	} 

	

	// var_dump($response2);
	$user_respuesta = wp_remote_retrieve_body($response2);
	$user_respuesta2 = json_decode($response2['body'],true);
	//var_dump($user_respuesta2);
	//echo 'Token es: '.$user_respuesta2['resp']['token'].'<br>';
	$jwt = $user_respuesta2['resp']['token'];

	session_start();
	$_SESSION['token'] = $jwt;

	$url_listado_usuarios = 'http://localhost:5189/api/Usuario/ObtenerUsuarios';
	$options2 = [		
		'headers'     => [
			'Content-Type' => 'application/json',
			'Authorization' => 'bearer '.$jwt,
		],
	];

	$response3 = wp_remote_get($url_listado_usuarios, $options2);

	if ( is_wp_error( $response3 ) ) {
		$error_message = $response3->get_error_message();
		echo "Algo no sirvio: $error_message";
	} else {
		var_dump($response3);
	}




	// echo 'Respuesta:<pre>';
	// print_r( $user_respuesta );
	// echo '</pre>';

	$body = wp_remote_retrieve_body($response);

	$data = json_decode($body);
	//var_dump($data);

	$template = '<table class="tabla-resultados">
                    <thead>
					<tr>
						<th>id</th>
						<th>Cod</th>
						<th>Tipo</th>
					</tr>
                    </thead>
                    <tbody>
					{data}
                    </tbody>
				</table>
				<br>
				<button>Probar Login</button>				
				';

	if ( $data ){
		//$data2 = array_slice($data, 350, 20);
		$str = '';
		foreach ($data as $d) {
			$str .= "<tr>";			
			$str .= "<td>{$d->id}</td>";
			$str .= "<td>{$d->codigo}</td>";			
			$str .= "<td>{$d->tipo}</td>";			
			$str .= "</tr>";
		}
	}

	$html = str_replace('{data}', $str, $template);

	return $html;	
}

?>