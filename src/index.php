<?php
// iniciar sessió
session_start();

// definir la constant per al calcul factorial, en PHP és en mayúscules i sense $
const CALCULFACTORIAL = 'recursiva';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Calculadora Web</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Calculadora web númerica i de strings</h1>
        <form action="" method="POST" class="border p-4 rounded">

            <div class="mb-3">
                <label for="valor1" class="form-label">Valor 1:</label>
                <input type="text" id="valor1" name="valor1" class="form-control" required>
            </div>
            <!-- marge inferior -->    
            <div class="mb-3">
                <label for="valor2" class="form-label">Valor 2:</label>
                <input type="text" id="valor2" name="valor2" class="form-control">
            </div>
            <div class="mb-3">
                <button type="submit" name="operacio" value="suma" class="btn btn-primary">Suma</button>
                <button type="submit" name="operacio" value="resta" class="btn btn-secondary">Resta</button>
                <button type="submit" name="operacio" value="multiplicar" class="btn btn-success">Multiplicar</button>
                <button type="submit" name="operacio" value="dividir" class="btn btn-danger">Dividir</button>
                <button type="submit" name="operacio" value="concatenar" class="btn btn-info">Concatenar</button>
                <button type="submit" name="operacio" value="eliminar" class="btn btn-warning">Eliminar Substring</button>
                <button type="submit" name="operacio" value="factorial" class="btn btn-dark">Calcular Factorial</button>
            </div>
        </form>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-kkvR5/7z+V0qgAecB+BxfGTGxrg9fv/6PlonxZTLE2Hkd/s0IhbneMa5ux/Wb0J" crossorigin="anonymous"></script>
    </div>
</body>
</html>

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
        if (CALCULFACTORIAL === 'iterativa'){
            return factorialIterativa($n);
        }elseif (CALCULFACTORIAL === 'recursiva'){
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

    echo "<h2 class='text-center mt-4'>Resultat: $resultat</h2>";
    }       
    
    //si el historial no esta buit que te un registre en el historial, mostrara el titul, 
    if (!empty($_SESSION['historial'])) {
        echo "<div class='d-flex justify-content-center'>"; 
        echo "<div class='text-center'>";
        echo "<h3 class='text-center my-4'>Historial d'operacions:</h3>";
        echo "<ul class='list-group'>";
        // Bucle que recorre el historial de la sessió, almacenant cada operació en la variable $operacioHistorial,
        foreach ($_SESSION['historial'] as $operacioHistorial) {
            echo "<li class='list-group-item'>$operacioHistorial</li>";
        }
        echo "</ul>";

    }
?>
