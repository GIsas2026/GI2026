# Grandes Ideas - Servicios IT Profesionales

Página web moderna, responsiva y profesional para Grandes Ideas, ofreciendo servicios de SQL Server, Analítica de Datos, Power BI, Microsoft 365 y Soporte IT.

## 🌟 Características

### Frontend
- ✅ **HTML5 Semántico** - Estructura moderna y accesible
- ✅ **CSS3 Responsive** - Diseño adaptable a todos los dispositivos
- ✅ **JavaScript Vanilla** - Interactividad sin dependencias externas
- ✅ **Diseño Moderno** - Interfaz profesional con gradientes y animaciones
- ✅ **Font Awesome Icons** - Iconografía profesional
- ✅ **Formulario de Contacto** - Con validación en tiempo real

### Backend
- ✅ **API PHP** - Procesa formularios de contacto
- ✅ **Base de Datos MySQL** - Almacena información de contactos
- ✅ **Validación de Datos** - Seguridad y sanitización de entradas
- ✅ **Correos Automatizados** - Confirmación de contacto (opcional)

## 📁 Estructura del Proyecto

```
GI2026/
├── index.html                 # Página principal
├── css/
│   └── styles.css            # Estilos responsivos
├── js/
│   └── script.js             # Interactividad y validaciones
├── api/
│   └── enviar-contacto.php   # API para procesar contactos
├── database/
│   └── crear_base_datos.sql  # Script de base de datos
└── README.md                 # Este archivo
```

## 🚀 Instalación y Configuración

### Requisitos
- Servidor web (Apache, Nginx, etc.)
- PHP 7.4+
- MySQL 5.7+
- Dominio: `grandes-ideas.com`

### Pasos de Instalación

#### 1. **Descargar el Proyecto**
```bash
git clone https://github.com/GIsas2026/GI2026.git
cd GI2026
```

#### 2. **Configurar la Base de Datos**

**Opción A: Usar phpMyAdmin**
- Abre phpMyAdmin
- Crea una nueva base de datos llamada `grandes_ideas`
- Importa el archivo `database/crear_base_datos.sql`

**Opción B: Usar MySQL Command Line**
```bash
mysql -u root -p < database/crear_base_datos.sql
```

#### 3. **Actualizar Credenciales de Base de Datos**

Edita `api/enviar-contacto.php` y actualiza:

```php
$host = 'localhost';      // Tu servidor MySQL
$usuario = 'root';        // Usuario de MySQL
$password = '';           // Contraseña de MySQL
$base_datos = 'grandes_ideas';
```

#### 4. **Subir Archivos al Servidor**

Sube todos los archivos a tu servidor web en el directorio raíz:
```
/public_html/ o /www/html/
```

#### 5. **Configurar Permisos**

Asegúrate de que los permisos sean correctos:
```bash
chmod 755 api/
chmod 644 api/enviar-contacto.php
```

## 📝 Información de Contacto

- **Dominio**: grandes-ideas.com
- **Correo**: contactenos@grandes-ideas.com
- **Teléfono**: +57 3053461069
- **Ubicación**: Bogotá, Colombia

## 🎨 Secciones de la Página

### 1. **Navbar**
- Logo de la empresa
- Navegación a secciones principales
- Menú responsivo para móviles

### 2. **Hero Section**
- Titular principal
- Subtítulo descriptivo
- Botón de llamada a acción

### 3. **Servicios**
Presenta 6 servicios principales:
- **SQL Server** - Gestión de bases de datos
- **Analítica de Datos** - Inteligencia de negocios
- **Power BI** - Visualización de datos
- **Microsoft 365** - Plataforma integral
- **Soporte IT** - Asistencia técnica
- **Consultoría IT** - Asesoramiento estratégico

Cada tarjeta incluye:
- Icono representativo
- Descripción detallada
- Lista de características
- Efecto hover atractivo

### 4. **Formulario de Contacto**
- Campo de nombre
- Campo de correo
- Campo de teléfono (opcional)
- Selector de servicio
- Área de descripción
- Validación en tiempo real
- Mensajes de éxito/error

### 5. **Información de Contacto**
- Email
- Teléfono
- Ubicación
- Sitio web

### 6. **Footer**
- Enlaces rápidos
- Información de contacto
- Derechos de autor

## 🔧 Configuración Avanzada

### Habilitar Envío de Correos

Para habilitar el envío automático de correos de confirmación:

1. Descomenta las líneas en `api/enviar-contacto.php`:
```php
mail($correo, $asunto, $mensaje, $headers);
mail("contactenos@grandes-ideas.com", "Nuevo contacto: $nombre", $mensaje, $headers);
```

2. Configura SMTP en tu servidor (si es necesario)

### Personalizar Estilos

Edita `css/styles.css` para cambiar colores:
```css
:root {
    --color-primary: #0066cc;      /* Color azul principal */
    --color-secondary: #00a8e8;    /* Color azul secundario */
    --color-accent: #00d4ff;       /* Color acentuado */
}
```

### Agregar Más Servicios

En `index.html`, agrega una nueva tarjeta en la sección servicios:
```html
<div class="servicio-card">
    <div class="servicio-icon">
        <i class="fas fa-icon-name"></i>
    </div>
    <h3>Nombre del Servicio</h3>
    <p class="servicio-subtitulo">Subtítulo</p>
    <p class="servicio-descripcion">Descripción...</p>
    <ul class="servicio-lista">
        <li><i class="fas fa-check"></i> Característica 1</li>
        <li><i class="fas fa-check"></i> Característica 2</li>
    </ul>
</div>
```

## 📱 Responsividad

La página es completamente responsiva:
- **Desktop** (>768px) - 3 columnas de servicios
- **Tablet** (768px) - 2 columnas
- **Móvil** (<480px) - 1 columna

## 🔒 Seguridad

✅ **Medidas de Seguridad Implementadas:**
- Validación de entrada con `filter_var()`
- Sanitización de HTML con `htmlspecialchars()`
- Prepared Statements para prevenir SQL Injection
- Validación de email
- Límite de caracteres en campos
- Protección CORS

⚠️ **Recomendaciones Adicionales:**
- Usar HTTPS (SSL/TLS)
- Configurar firewall
- Hacer backup regular de la base de datos
- Usar contraseñas fuertes en MySQL
- Limitar acceso a archivos de configuración

## 📊 Base de Datos

### Tabla `contactos`
```sql
- id: INT (clave primaria)
- nombre: VARCHAR(100)
- correo: VARCHAR(255)
- telefono: VARCHAR(20)
- servicio: VARCHAR(100)
- descripcion: LONGTEXT
- estado: ENUM (nuevo, en_proceso, respondido, cerrado)
- fecha_creacion: DATETIME
- fecha_actualizacion: DATETIME
- ip_cliente: VARCHAR(45)
- notas: LONGTEXT
```

## 🚦 Panel de Control (Futuro)

Se recomienda crear un panel administrativo para:
- Ver todos los contactos
- Cambiar estado de solicitudes
- Agregar notas internas
- Exportar datos a Excel
- Ver estadísticas

## 📞 Soporte

Para consultas o soporte técnico:
- Correo: contactenos@grandes-ideas.com
- Teléfono: +57 3053461069
- Sitio: grandes-ideas.com

## 📄 Licencia

Este proyecto es de uso privado para Grandes Ideas.

## ✨ Notas de Implementación

### Próximas Mejoras Sugeridas:

1. **Panel de Administración** - Para gestionar contactos
2. **Blog** - Artículos sobre servicios IT
3. **Galería de Proyectos** - Casos de éxito
4. **Chat en Vivo** - Soporte inmediato
5. **Certificados** - Mostrar certificaciones profesionales
6. **Testimonios** - Clientes satisfechos
7. **Precios** - Paquetes de servicios
8. **Portafolio** - Proyectos realizados
9. **FAQ** - Preguntas frecuentes
10. **Blog** - Recursos y tutoriales

---

**Desarrollado con ❤️ para Grandes Ideas**
**Última actualización: 2026-05-07**