<?php
//iniciar sessió
session_start();

//definir la constant per al calcul factorial, en php és en mayuscules i sense $
const CALCULFACTORIAL = 'recursiva';
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Calculadora Web</title>
</head>
<body>
    <h1>Calculadora web númerica i de strings</h1>
    <form action="" method="POST">
        <label for="valor10">Valor 1:</label>
        <input type="text" id="valor1" name="valor1" required><br><br>

        <label for="valor2">Valor 2:</label>
        <input type="text" id="valor2" name="valor2" required><br><br>

        <button type="submit" name="operacio" value="suma">Suma</button>
        <button type="submit" name="operacio" value="resta">Resta</button>
        <button type="submit" name="operacio" value="multiplicar">Multiplicar</button>
        <button type="submit" name="operacio" value="dividir">Dividir</button>
        <button type="submit" name="operacio" value="concatenar">Concatenar</button>
        <button type="submit" name="operacio" value="eliminar">Eliminar Substring</button>
        <button type="submit" name="operacio" value="factorial">Calcular Factorial Iterativa/Recursiva</button>

    </form>

</body>

<?php

//Verificar si el Formulari ha sigut enviat
if($_SERVER['REQUEST_METHOD'] == "POST"){ 
    //Guardar els valors enviats del formulari
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $operacio = $_POST['operacio'];
    $resultat ="";

    function sumar($a, $b){
        return $a + $b;
    }

    function resta($a, $b){
        return $a - $b;
    }

    function multiplicar($a, $b){
        return $a * $b;
    }

    function dividir($a, $b){
        if($b == 0){
            return "ERROR: No es pot dividir per cero";
        }
        return $a / $b;
    }

    function concatenar($string1, $string2){
        return $string1 . $string2;
    }

    // Utilitzem la funció str_replace per a reemplaçar o eliminar la subcadena
    function eliminar($string, $subString) {
        return str_replace($subString, "", $string);
    }

    //Iterativa multiplica el valor 1 les vegades que té el numero del valor 2
    function factorialIterativa($n){
        $resultat = 1;
        for ($i = 1; $i <= $n; $i++) {
            $resultat *= $i;
        }
        return $resultat;
    }

    //
    function factorialRecursiva($n){
        if ($n <= 1){
            return 1;
        }
        //multiplicarem n hasta que el numero sigue igual 1 restanli -1 cada vegada que multiplica
        return $n * factorialRecursiva($n - 1);
    }

    function calcularFactorial($n){
        if (CALCULFACTORIAL == 'iterativa'){
            return factorialIterativa($n);
        }elseif (CALCULFACTORIAL == 'recursiva'){
            return factorialRecursiva($n);
        }else{
            return "ERROR: Calcul no valid";
        }
    }

    switch ($operacio){
        case 'suma':
            //Funcio floatval convertix les variables valor 1 i 2 en numeros decimals
            $resultat = sumar(floatval($valor1), floatval($valor2));
            break;
        case 'resta':
            $resultat = resta(floatval($valor1), floatval($valor2));
            break;
        case 'multiplicar':
            $resultat = multiplicar(floatval($valor1), floatval($valor2));
            break;
        case 'dividir':
            $resultat = dividir(floatval($valor1), floatval($valor2));
            break;
        case 'concatenar':
            $resultat = concatenar($valor1, $valor2);
            break;
        case 'eliminar':
            $resultat = eliminar(($valor1),$valor2);
            break;
        case 'factorial':
            $resultat = calcularFactorial(intval($valor1));
            break;
        default:
            $resultat = "Operació no valida.";
            break;
    }

    // Guardar la operación en el historial de la sesión
    $operacioCompleta = "Operació: $operacio - Valor 1: $valor1, Valor 2: $valor2 - Resultat: $resultat";
    //Agrega la operacio al historial
    $_SESSION['historial'][] = $operacioCompleta;

    echo "<h2>Resultat: $resultat</h2>";
    }       
    
    //si el historial no esta buit que te un registre en el historial, mostrara el titul, 
    if (!empty($_SESSION['historial'])) {
        echo "<h3>Historial d'operacions:</h3>";
        echo "<ul>";
        // Bucle que recorre el historial de la sessió, almacenant cada operació en la variable $operacioHistorial,
        foreach ($_SESSION['historial'] as $operacioHistorial) {
            echo "<li>$operacioHistorial</li>";
        }
        echo "</ul>";
    }
?>
