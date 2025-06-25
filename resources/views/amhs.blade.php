<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .details-container { border: 1px solid #ddd; padding: 20px; border-radius: 8px; max-width: 600px; margin-top: 20px; }
        .details-item { margin-bottom: 10px; }
        .details-item strong { display: inline-block; width: 150px; }
        .btn { display: inline-block; padding: 8px 15px; margin-top: 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; color: white; }
        .btn-secondary { background-color: #6c757d; }
        .btn-warning { background-color: #ffc107; color: #343a40; }
    </style>
</head>
<body>
    <h1>Detalles del Producto</h1>

    <div class="details-container">
        <div class="details-item">
            <strong>ID:</strong> {{ $producto->id }}
        </div>
        <div class="details-item">
            <strong>Nombre:</strong> {{ $producto->nombre }}
        </div>
        <div class="details-item">
            <strong>Categoría ID:</strong> {{ $producto->categoria_id }}
        </div>
        <div class="details-item">
            <strong>Código de Barras:</strong> {{ $producto->codigo_barras }}
        </div>
        <div class="details-item">
            <strong>Precio Venta:</strong> {{ $producto->precio_venta }}
        </div>
        <div class="details-item">
            <strong>Cantidad Stock:</strong> {{ $producto->cantidad_stock }}
        </div>
        <div class="details-item">
            <strong>Estado:</strong> {{ $producto->estado ? 'Activo' : 'Inactivo' }}
        </div>
        <div class="details-item">
            <strong>Creado en:</strong> {{ $producto->created_at }}
        </div>
        <div class="details-item">
            <strong>Actualizado en:</strong> {{ $producto->updated_at }}
        </div>
    </div>

    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning">Editar Producto</a>
    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver a la Lista</a>
</body>
</html>
