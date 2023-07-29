<?php 
$servidor="localhost";
$usuario="root";
$clave="";
$dbname="sistransporte";

   try{
//se conecta a la base de datos utilizando los valores de servidor,nombre de la base de datos, usuario, contraseña 
   $conexion = new PDO("mysql:host=$servidor;dbname=$dbname",$usuario,$clave);

//establece el modo de error de PDO en "PDO::ERRMODE_EXCEPTION" que PDO arrojará una advertencia o un error
   $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
   echo "Conexión establecida exitosamente.";

//se captura cualquier excepción que pueda haber ocurrido durante la conexión 
   }catch(PDOException $error){
   echo "Error con la conexión a DB".$error->getMessage();
   }
?>
