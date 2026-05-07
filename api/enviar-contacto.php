<?php
// Configuración de CORS y headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Permitir solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================
// IMPORTANTE: Cambia estos valores con tus credenciales reales
$host = 'localhost'; // Tu servidor de base de datos
$usuario = 'root'; // Usuario de la base de datos
$password = ''; // Contraseña de la base de datos
$base_datos = 'grandes_ideas'; // Nombre de la base de datos

// ============================================
// OBTENER Y VALIDAR DATOS
// ============================================
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['exito' => false, 'mensaje' => 'Datos inválidos']);
    exit;
}

// Validar campos requeridos
$campos_requeridos = ['nombre', 'correo', 'servicio', 'descripcion'];
foreach ($campos_requeridos as $campo) {
    if (empty($input[$campo])) {
        http_response_code(400);
        echo json_encode(['exito' => false, 'mensaje' => "El campo '$campo' es requerido"]);
        exit;
    }
}

// Sanitizar datos
$nombre = trim(htmlspecialchars($input['nombre'], ENT_QUOTES, 'UTF-8'));
$correo = trim(filter_var($input['correo'], FILTER_SANITIZE_EMAIL));
$telefono = !empty($input['telefono']) ? trim(htmlspecialchars($input['telefono'], ENT_QUOTES, 'UTF-8')) : '';
$servicio = trim(htmlspecialchars($input['servicio'], ENT_QUOTES, 'UTF-8'));
$descripcion = trim(htmlspecialchars($input['descripcion'], ENT_QUOTES, 'UTF-8'));

// Validar email
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['exito' => false, 'mensaje' => 'El correo no es válido']);
    exit;
}

// Validar longitud de campos
if (strlen($nombre) < 2 || strlen($nombre) > 100) {
    http_response_code(400);
    echo json_encode(['exito' => false, 'mensaje' => 'El nombre debe tener entre 2 y 100 caracteres']);
    exit;
}

if (strlen($descripcion) < 10 || strlen($descripcion) > 2000) {
    http_response_code(400);
    echo json_encode(['exito' => false, 'mensaje' => 'La descripción debe tener entre 10 y 2000 caracteres']);
    exit;
}

// ============================================
// CONECTAR A LA BASE DE DATOS
// ============================================
try {
    $conexion = new mysqli($host, $usuario, $password, $base_datos);
    
    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception('Error de conexión: ' . $conexion->connect_error);
    }
    
    // Configurar charset
    $conexion->set_charset("utf8mb4");
    
    // ============================================
    // INSERTAR EN LA BASE DE DATOS
    // ============================================
    $sql = "INSERT INTO contactos (nombre, correo, telefono, servicio, descripcion, fecha_creacion, ip_cliente)
            VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    
    $ip_cliente = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error en la preparación: ' . $conexion->error);
    }
    
    // Vincular parámetros (s = string, s = string, s = string, s = string, s = string, s = string)
    $stmt->bind_param("ssssss", $nombre, $correo, $telefono, $servicio, $descripcion, $ip_cliente);
    
    // Ejecutar
    if (!$stmt->execute()) {
        throw new Exception('Error en la ejecución: ' . $stmt->error);
    }
    
    $stmt->close();
    
    // ============================================
    // ENVIAR CORREO DE CONFIRMACIÓN (OPCIONAL)
    // ============================================
    enviar_email_confirmacion($nombre, $correo, $servicio);
    
    // ============================================
    // RESPUESTA EXITOSA
    // ============================================
    http_response_code(200);
    echo json_encode([
        'exito' => true,
        'mensaje' => 'Contacto registrado exitosamente. Nos pondremos en contacto pronto.'
    ]);
    
    $conexion->close();
    
} catch (Exception $e) {
    // ============================================
    // MANEJO DE ERRORES
    // ============================================
    error_log('Error en enviar-contacto.php: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Ocurrió un error al procesar tu solicitud. Por favor, intenta nuevamente más tarde.'
    ]);
}

// ============================================
// FUNCIÓN PARA ENVIAR EMAIL DE CONFIRMACIÓN
// ============================================
function enviar_email_confirmacion($nombre, $correo, $servicio) {
    $asunto = "Hemos recibido tu contacto - Grandes Ideas";
    
    $mensaje = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; background: #f8f9fa; padding: 20px; border-radius: 8px; }
            .header { background: linear-gradient(135deg, #0066cc 0%, #00a8e8 100%); color: white; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; }
            .content { background: white; padding: 20px; border-radius: 8px; }
            .footer { text-align: center; color: #666; margin-top: 20px; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>¡Gracias por contactarnos!</h2>
            </div>
            <div class='content'>
                <p>Hola <strong>$nombre</strong>,</p>
                
                <p>Hemos recibido tu solicitud de información sobre nuestro servicio de <strong>$servicio</strong>.</p>
                
                <p>Nuestro equipo revisará tu mensaje y se pondrá en contacto contigo lo antes posible a través del correo que proporcionaste.</p>
                
                <h3>Detalles de tu solicitud:</h3>
                <ul>
                    <li><strong>Nombre:</strong> $nombre</li>
                    <li><strong>Correo:</strong> $correo</li>
                    <li><strong>Servicio:</strong> $servicio</li>
                </ul>
                
                <p>Si tienes alguna pregunta urgente, puedes comunicarte con nosotros:</p>
                <ul>
                    <li><strong>Teléfono:</strong> +57 3053461069</li>
                    <li><strong>Email:</strong> contactenos@grandes-ideas.com</li>
                    <li><strong>Ubicación:</strong> Bogotá, Colombia</li>
                </ul>
                
                <p>¡Estamos emocionados de poder ayudarte!</p>
                
                <p>Saludos cordiales,<br><strong>Equipo de Grandes Ideas</strong></p>
            </div>
            <div class='footer'>
                <p>&copy; 2026 Grandes Ideas. Todos los derechos reservados.</p>
                <p>grandes-ideas.com | contactenos@grandes-ideas.com</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Headers para enviar HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: contactenos@grandes-ideas.com\r\n";
    
    // Enviar email (comentado si no tienes servidor de correo configurado)
    // mail($correo, $asunto, $mensaje, $headers);
    
    // También enviar copia a tu correo
    // mail("contactenos@grandes-ideas.com", "Nuevo contacto: $nombre", $mensaje, $headers);
}
?>