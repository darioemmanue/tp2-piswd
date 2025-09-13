<?php

  /////////////////////////////////////////////////////////////////////////////////
  function LeerArchivo($RutaNombre)
  {
     $total=0;	
	
	try{
		
		//Verificar si existe el archivo.
		if(file_exists ($RutaNombre ) == false)
			throw new Exception("Error al abrir el archivo $RutaNombre."); 
		 
		
		//Abrimos el archivo para solamente leerlo (r de read) 
		$abre = fopen($RutaNombre, "r"); 
			
		//Leemos el contenido del archivo y lo almacenamos en la variable $total.
		$total = fread($abre, filesize($RutaNombre)); //Lectura de un fichero en modo binario seguro.
		
		//Cerramos la conexión con el archivo. 
		fclose($abre);
	   }
	 catch(Exception $e){
		   die($e->getMessage());//EXHIBE EL MENSAJE Y DETIENE LA EJECUCIÓN.
	 }
		
	return $total;	
	  
  }
  /////////////////////////////////////////////////////////////////////////////////
  function EscribirArchivo($RutaNombre,$Informacion)
  {
	
    //echo '<br>Nombre = '.$RutaNombre.'<br>';
	
	//Abrimos el archivo para solamente escribirlo (w de write). 
	$abre = fopen($RutaNombre, "w"); 
	
	//Grabamos la información.
	/*$grabar =*/ fwrite($abre, $Informacion);  
	
	//Cerramos la conexión al archivo 
	fclose($abre); 
	
  }

  /////////////////////////////////////////////////////////////////////////////////
  function LeerArchivoLineaPorLinea($RutaNombre,$tam)
  {
	try{
		//Verificar si existe el archivo.
		if(file_exists ($RutaNombre ) == false)
			throw new Exception("Error al abrir el archivo $RutaNombre.");
		
		// Abrimos el archivo
		$fp = fopen($RutaNombre, "r");//$fp: Puntero a file o handler.

		// Loop que parará al final del archivo, cuando feof sea true:
		while(!feof($fp)){
				#fread: Retorna un string.
				$lectura = fread($fp, $tam);
				#utf8_encode: Coloca los caracteres especiales.
				echo utf8_encode($lectura)."<br>";
        }

		
	}//Fin bloque try.
	catch(Exception $e){
		  die("Excepción capturada:".$e->getMessage());//EXHIBE EL MENSAJE Y DETIENE LA EJECUCIÓN.
	}
	
	finally{
		if($fp)
			fclose($fp);//CERRAR EL ARCHIVO.
	}
     
	
  }
  
  /////////////////////////////////////////////////////////////////////////////////
  function UsodeFseekFtell($RutaNombre)
  {
	/*fseek():Acceso directo al archivo.
	  ftell():Retorna el tamaño en bytes del archivo.
	*/
	try{
		//Verificar si existe el archivo.
		if(file_exists ($RutaNombre ) == false)
			throw new Exception("Error al abrir el archivo $RutaNombre.");
		
		// Abrimos el archivo
		$fp = fopen($RutaNombre, "r");//$fp: Puntero a file o handler.

		echo ftell($fp) . "<br>"; // Devuelve 0.
		
		// Colocamos el apuntador en la posicion 10:
		fseek($fp, 10);
		/*$var = fread($fp,20);
		echo $var;*/
		// Mostramos la posición actual:
		echo ftell($fp) . "<br>"; // Devuelve 10
		// Se puede indicar una posición sin contenido:
		fseek($fp, 1000);
		echo ftell($fp) . "<br>"; // Devuelve 1000
		// Para ir al final del archivo se emplea un tercer argumento en fseek:
		fseek($fp, 0, SEEK_END);
		echo ftell($fp) . "<br>"; // Devuelve 18
		
		// Para mover el apuntador a una posición relativa se emplea SEEK_CUR:
		fseek($fp, -5, SEEK_CUR);
		
		echo ftell($fp) . "<br>"; // Devuelve 13


		
	}//Fin bloque try.
	catch(Exception $e){
		  die($e->getMessage());//EXHIBE EL MENSAJE Y DETIENE LA EJECUCIÓN.
	}
	
	finally{
		if($fp)
			fclose($fp);//CERRAR EL ARCHIVO.
	}
     
	
  }
  
?>