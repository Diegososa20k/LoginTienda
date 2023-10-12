<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    //
    public function mostrarProducto()
    {
        return view('producto');
    }


    public function store(Request $request)
    {
        //
        //Esta validacion hace que si o si se valide que se envie estos datos
        $request ->validate([
            'nombre' => 'required',
            'costo' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
        ]);
        //para mandar post de datos de un cliente

        $producto = new Producto;
        $producto->nombre  = $request->nombre;
        $producto->costo = $request->costo;
        $producto->cantidad = $request->cantidad;
        $producto->precio = $request->precio;

        $producto->save();

        return $producto;
    }


    public function index()
    {
        //
        return Producto::all();
    }

    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto not found'], 404);
        }

        $producto->delete();

        return response()->json(['message' => 'Producto deleted successfully']);
    }

    public function update($id, Request $request)
    {

        // Validar los datos del request si es necesario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'costo' => 'required|numeric',
            'precio' => 'required|numeric',
            'cantidad' => 'required|integer',
        ]);

        // Buscar el producto por su ID
        $producto = Producto::find($id);

        // Verificar si el producto existe
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Actualizar los datos del producto
        $producto->nombre = $request->input('nombre');
        $producto->costo = $request->input('costo');
        $producto->precio = $request->input('precio');
        $producto->cantidad = $request->input('cantidad');

        // Guardar los cambios
        $producto->update();

        return $producto;

        // Devolver una respuesta de éxito
        return response()->json(['message' => 'Producto actualizado correctamente'], 200);
    }


}
