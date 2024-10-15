<?php
session_start();

class Calculadora {
    private string $valor1;
    private string $valor2;
    private $historial = [];

    // definir la constant per a controlar si el càlcul del factorial serà iteratiu o recursiu.
    const FACTORIAL = 'recursiva';

    // Constructor de la classe que inicialitza els valors
    public function __construct($valor1, $valor2 = null) {
        $this->valor1 = $valor1;
        $this->valor2 = $valor2;

        // Si hi ha un historial guardat en la sessió, el recuperem per a mantenir les operacions anteriors
        if (isset($_SESSION['historial'])) {
            $this->historial = $_SESSION['historial'];
        }
    }

    // Mètode genèric per calcular i registrar l'operació
    private function calculaRegistra($descripcio, $simbol, $valor1, $valor2, $resultat) {
        $this->agregarHistorial("{$descripcio}: {$valor1} {$simbol} {$valor2} = $resultat");
        return $resultat;
    }

    // Mètode per sumar dos valors
    public function sumar($valor1, $valor2) {
        return $this->calculaRegistra('Suma', '+', $valor1, $valor2, $valor1 + $valor2);
    }

    // Mètode per restar dos valors
    public function restar($valor1, $valor2) {
        return $this->calculaRegistra('Resta', '-', $valor1, $valor2, $valor1 - $valor2);
    }

    // Mètode per multiplicar dos valors
    public function multiplicar($valor1, $valor2) {
        return $this->calculaRegistra('Multiplicació', '*', $valor1, $valor2, $valor1 * $valor2);
    }

    // Mètode per dividir dos valors
    public function dividir($valor1, $valor2) {
        if ($valor2 == 0) {
            return "Error: no es pot dividir entre zero.";
        }
        return $this->calculaRegistra('Divisió', '/', $valor1, $valor2, $valor1 / $valor2);
    }

    // Mètode per concatenar dos cadenes de text
    public function concatenar($valor1, $valor2) {
        $resultat = $valor1 . $valor2;
        // Canviat per mantenir consistència
        return $this->calculaRegistra('Concatenació', '.', $valor1, $valor2, $resultat);
    }

    // Mètode per eliminar una subcadena d'una cadena principal
    public function eliminarSubstring($valor1, $valor2) {
        $resultat = str_replace($valor2, '', $valor1);
        $this->agregarHistorial("Eliminar Substring: '{$valor2}' de '{$valor1}' = $resultat");
        return $resultat;
    }

    //Iterativa multiplica el valor 1 les vegades que té el numero del valor 2
    public function factorialIterativa($valor1) {
        $resultat = 1;
        for ($i = 1; $i <= $valor1; $i++) {
            $resultat *= $i;
        }

        return $resultat;
    }

    public function factorialRecursiva($valor1) {
        if ($valor1 <= 1) {
            return 1;
        }
        // Aquesta funció es crida a si mateixa fins que 'n' arriba a 1, acumulant la multiplicació dels valors.
        return $valor1 * $this->factorialRecursiva($valor1 - 1);
    }

    // Mètode per calcular el factorial segons el mode especificat per la constant FACTORIAL (iterativa o recursiva)
    public function calcularFactorial($valor1) {
        if ($valor1 < 0) {
            return "ERROR: No es pot calcular el factorial d'un nombre negatiu.";
        }

        $resultat = (self::FACTORIAL === 'iterativa') 
                    //Si la condició es verdadera        
                    ? $this->factorialIterativa($valor1)
                    //Si la condició es falsa 
                    : $this->factorialRecursiva($valor1);

        // Guardar l'operació al historial
        $this->agregarHistorial("Factorial (" . self::FACTORIAL . "): {$valor1}! = $resultat");
        return $resultat;
    }

    private function agregarHistorial($operacio) {
        // Agregar la operació al historial
        $this->historial[] = $operacio;

        // Guardar el historial en la sessió
        $_SESSION['historial'] = $this->historial;
    }

    public function obtenerHistorial() {
        return $this->historial;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor1 = $_POST['valor1'];
    $valor2 = $_POST['valor2'];
    $operacio = $_POST['operacio'];

    $calculadora = new Calculadora($valor1, $valor2);

    $resultat = '';

    switch ($operacio) {
        case 'suma':
            $resultat = $calculadora->sumar(floatval($valor1), floatval($valor2));
            break;
        case 'resta':
            $resultat = $calculadora->restar(floatval($valor1), floatval($valor2));
            break;
        case 'multiplicar':
            $resultat = $calculadora->multiplicar(floatval($valor1), floatval($valor2));
            break;
        case 'dividir':
            $resultat = $calculadora->dividir(floatval($valor1), floatval($valor2));
            break;
        case 'concatenar':
            $resultat = $calculadora->concatenar($valor1, $valor2);
            break;
        case 'eliminar':
            $resultat = $calculadora->eliminarSubstring($valor1, $valor2);
            break;
        case 'factorial':
            $resultat = $calculadora->calcularFactorial(intval($valor1));
            break;
    }

    // Obtenim l'historial després de l'operació
    $historial = $calculadora->obtenerHistorial();
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <!-- Meta Viewport asegura adaptar la pagina a diferents tamanys de pantalla -->  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora Web</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Calculadora Web</h1>
        <form action="" method="POST" class="border p-4 rounded">
            <div class="mb-3">
                <label for="valor1" class="form-label">Valor 1:</label>
                <input type="text" id="valor1" name="valor1" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="valor2" class="form-label">Valor 2:</label>
                <input type="text" id="valor2" name="valor2" class="form-control">
            </div>
            <!-- Adapta a diferents tamany de pantalla i alinea els botons -->
            <div class="mb-3 d-flex flex-wrap gap-2 justify-content-center">
                <button type="submit" name="operacio" value="suma" class="btn btn-primary">Suma</button>
                <button type="submit" name="operacio" value="resta" class="btn btn-secondary">Resta</button>
                <button type="submit" name="operacio" value="multiplicar" class="btn btn-success">Multiplicar</button>
                <button type="submit" name="operacio" value="dividir" class="btn btn-danger">Dividir</button>
                <button type="submit" name="operacio" value="concatenar" class="btn btn-info">Concatenar</button>
                <button type="submit" name="operacio" value="eliminar" class="btn btn-warning">Eliminar Substring</button>
                <button type="submit" name="operacio" value="factorial" class="btn btn-dark">Factorial</button>
            </div>
        </form>

        <!-- Si la variable $resultat està definida i no és null, mostra el resultat -->
        <?php if (isset($resultat)): ?>
            <h2 class="text-center mt-4">Resultat: <?=($resultat); ?></h2>
        <?php endif; ?>
        
        <!-- Si la variable $historial no està buida, es mostra l'historial d'operacions -->
        <?php if (!empty($historial)): ?>
            <div class="mt-4">
                <h3 class="text-center">Historial d'operacions:</h3>
                <ul class="list-group">
                    <?php foreach ($historial as $operacioHistorial): ?>
                        <li class="list-group-item"><?=($operacioHistorial); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
