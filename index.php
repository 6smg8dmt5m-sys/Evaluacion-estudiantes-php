<?php
// Iniciar la sesión para conservar los datos entre recargas de página
session_start();

/**
 * Clase que representa a un Estudiante dentro del sistema académico.
 * Permite almacenar información personal y evaluar su rendimiento escolar.
 */
class Estudiante {
    /** @var string Nombre completo del estudiante */
    public $nombre;

    /** @var int Edad del estudiante en años */
    public $edad;

    /** @var float Promedio general de calificaciones del estudiante */
    public $promedio;

    /**
     * Constructor de la clase Estudiante.
     * Inicializa los atributos principales al crear un nuevo objeto.
     * 
     * @param string $nombre El nombre del estudiante
     * @param int    $edad   La edad del estudiante
     * @param float  $promedio El promedio de calificaciones
     */
    public function __construct($nombre, $edad, $promedio) {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->promedio = $promedio;
    }

    /**
     * Muestra los detalles completos del estudiante incluyendo su estatus.
     * 
     * @return string Detalles formateados del estudiante
     */
    public function mostrarDetalles() {
        $estado = $this->evaluarEstado();
        return "Estudiante: {$this->nombre} | Edad: {$this->edad} años | Promedio: {$this->promedio} - Estatus: {$estado}";
    }

    /**
     * Evalúa si el estudiante está aprobado o reprobado según su promedio.
     * 
     * @return string Estatus del estudiante
     */
    public function evaluarEstado() {
        return ($this->promedio >= 6) ? "Aprobado" : "Reprobado";
    }
}

// Inicializar el arreglo en la sesión si no existe
if (!isset($_SESSION['lista_estudiantes'])) {
    $_SESSION['lista_estudiantes'] = [];
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se solicitó limpiar la lista
    if (isset($_POST['accion']) && $_POST['accion'] === 'limpiar') {
        $_SESSION['lista_estudiantes'] = [];
    } else {
        $nombre = $_POST['nombre'] ?? '';
        $edad = intval($_POST['edad'] ?? 0);
        $promedio = floatval($_POST['promedio'] ?? 0);

        if (!empty($nombre)) {
            $estudiante = new Estudiante($nombre, $edad, $promedio);
            // Agregar el nuevo resultado al arreglo de la sesión
            $_SESSION['lista_estudiantes'][] = $estudiante->mostrarDetalles();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación de Estudiantes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        form { margin-bottom: 20px; }
        .resultado { background: #f0f0f0; padding: 15px; border-radius: 5px; margin-top: 15px; }
        ul { padding-left: 20px; }
        li { margin-bottom: 5px; }
        .btn-limpiar { background: #ff4d4d; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Captura de Datos del Estudiante</h2>
    <form method="POST" action="">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Edad:</label><br>
        <input type="number" name="edad" required><br><br>

        <label>Promedio:</label><br>
        <input type="number" step="any" name="promedio" required><br><br>

        <button type="submit">Evaluar y Agregar</button>
    </form>

    <?php if (!empty($_SESSION['lista_estudiantes'])): ?>
        <div class="resultado">
            <h3>Historial de Evaluaciones:</h3>
            <ul>
                <?php foreach ($_SESSION['lista_estudiantes'] as $item): ?>
                    <li><?php echo $item; ?></li>
                <?php endforeach; ?>
            </ul>

            <!-- Botón para reiniciar la lista -->
            <form method="POST" action="" style="margin-top: 15px; margin-bottom: 0;">
                <input type="hidden" name="accion" value="limpiar">
                <button type="submit" class="btn-limpiar">Limpiar Historial</button>
            </form>
        </div>
    <?php endif; ?>

</body>
</html>
