<template>

    <div class="container">

        <div class="d-inline-flex mb-3">
            <span><i class="mdi mdi-magnify"></i></span>
            <input v-model="busqueda" @keyup.enter="buscarProductos" type="search" class="form-control" placeholder="Buscar Producto">
            <!-- <button @click="buscarProductos" class="btn btn-primary">Buscar</button> -->
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AgregarProducto">
                Agregar nuevo Producto
            </button>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item" :class="{ disabled: paginaActual === 1 }">
                    <a class="page-link" href="#" aria-label="Previous" @click.prevent="cambiarPagina(paginaActual - 1)">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li v-for="pagina in paginasMostradas" :key="pagina" class="page-item" :class="{ active: pagina === paginaActual }">
                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagina)">{{ pagina }}</a>
                </li>
                <li class="page-item" :class="{ disabled: paginaActual === totalPaginas }">
                    <a class="page-link" href="#" aria-label="Next" @click.prevent="cambiarPagina(paginaActual + 1)">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>



        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm" style="table-layout: fixed; width: 100%;">
                <col style="width: 35%;" /> <!-- Adjust the width as needed -->
                <col style="width: 15%;" /> <!-- Adjust the width as needed -->
                <col style="width: 15%;" /> <!-- Adjust the width as needed -->
                <col style="width: 15%;" /> <!-- Adjust the width as needed -->
                <col style="width: 20%;" /> <!-- Adjust the width as needed -->
                <thead>
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Costo</th>
                    <th scope="col">Opciones</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(producto, index) in productosMostrados" :key="index">
                    <td>{{ producto.nombre }}</td>
                    <td>{{ producto.cantidad }}</td>
                    <td>{{ producto.precio }}</td>
                    <td>{{ producto.costo }}</td>
                    <td>
                    <button @click="EditarProducto(producto.id, index)" data-bs-toggle="modal" data-bs-target="#AgregarProducto" class="btn btn-primary">
                        <i class="mdi mdi-pencil-box-outline"></i></button>
                    <button @click="EliminarProductoNuevo(producto.id, index)" class="btn btn-danger">
                        <i class="mdi mdi-delete-forever"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


<!-- modals -->

    <div class="modal fade" id="AgregarProducto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar productos</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-floating mb-3">
                        <input v-model="Productos.nombre" type="text" class="form-control" placeholder="Nombre" required>
                        <label for="Nombre">Nombre</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-floating mb-3">
                        <input v-model="Productos.costo" type="number" class="form-control" placeholder="Costo" required>
                        <label for="Costo">Costo</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-floating mb-3">
                        <input v-model="Productos.precio" type="number" class="form-control" placeholder="Precio" required>
                        <label for="Precio">Precio</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-floating mb-3">
                        <input v-model="Productos.cantidad" type="number" class="form-control" placeholder="Cantidad" required>
                        <label for="Cantidad">Cantidad</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="CancelarNuevoProductoD()" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button @click="GuardarNuevoProductoD()" type="button" class="btn btn-primary">Guardar</button>
            </div>
            </div>
        </div>
    </div>


</template>

<script>
export default {
    data(){
        return {
            Productos: {
                id: null,
                nombre: '',
                cantidad: 0,
                precio: 0,
                costo: 0,
            },
            ProductosGuardados:[],
            isNew: true,
            busqueda: '',
            ProductosOriginales:[],
            productosPorPagina: 10,
            paginaActual: 1,
            productosMostrados: [],
        }
    },
    methods:{
        GuardarNuevoProductoD() {
            const data = {
                nombre: this.Productos.nombre,
                costo: this.Productos.costo,
                precio: this.Productos.precio,
                cantidad: this.Productos.cantidad
            };

            if (this.isNew) {
                // Create a new product
                axios
                .post('/productos', data)
                .then(response => {
                    this.CancelarNuevoProductoD();
                    this.ProductosGuardados.push(response.data);
                    this.ProductosOriginales = [...this.ProductosGuardados];
                    Swal.fire({
                    icon: 'success',
                    title: 'Producto guardado con éxito',
                    showConfirmButton: false,
                    timer: 1500
                    });
                    this.CancelarNuevoProductoD();
                    this.filtrarProductos();
                })
                .catch(error => {
                    console.error('Error al crear el producto', error);
                });
            } else {
                // Update an existing product
                axios
                .put(`/productos/${this.Productos.id}`, data)
                .then(response => {
                    this.CancelarNuevoProductoD();
                    // Update the product in ProductosGuardados on successful update
                    this.ProductosGuardados.splice(this.ProductosIndex, 1, response.data);
                    this.ProductosOriginales = [...this.ProductosGuardados];
                    Swal.fire({
                    icon: 'success',
                    title: 'Producto actualizado con éxito',
                    showConfirmButton: false,
                    timer: 1500
                    });
                    this.CancelarNuevoProductoD();
                    this.filtrarProductos();
                })
                .catch(error => {
                    console.error('Error al actualizar el producto', error);
                });
            }
        },

        filtrarProductos() {
            if (this.busqueda.trim() === '') {
                // Si la búsqueda está vacía, mostrar todos los productos originales
                this.ProductosGuardados = [...this.ProductosOriginales];  // Usa la copia de los productos originales
                return;
            }

            const filtro = this.busqueda.toLowerCase().trim();
            this.ProductosGuardados = this.ProductosOriginales.filter(producto => {
                return producto.nombre.toLowerCase().includes(filtro);
            });
        },


        buscarProductos() {
            if (this.busqueda.trim() === '') {
                // Si la búsqueda está vacía, mostrar todos los productos
                this.getProductos();
            } else {
                // Filtrar productos según la búsqueda
                this.filtrarProductos();
            }
        },
        CancelarNuevoProductoD(){
            this.Productos.id = null;
            this.Productos.nombre ='';
            this.Productos.costo = 0;
            this.Productos.precio = 0;
            this.Productos.cantidad = 0;
        },

        EliminarProductoNuevo(id, index) {
            axios
            .delete(`/productos/${id}`)
            .then(response => {
                // Remove the product from ProductosGuardados on successful deletion
                this.ProductosGuardados.splice(index, 1);
                this.ProductosOriginales = [...this.ProductosGuardados];
                this.filtrarProductos();
                Swal.fire({
                icon: 'success',
                title: 'Producto eliminado con éxito',
                showConfirmButton: false,
                timer: 1500
                });
            })
            .catch(error => {
                console.error('Error al eliminar el producto', error);
                Swal.fire({
                icon: 'error',
                title: 'Hubo un error al eliminar el producto',
                text: 'Por favor, inténtalo nuevamente más tarde'
                });
            });
        },

        getProductos(){
            axios
            .get('/productos')
            .then(response => {
                this.ProductosGuardados = response.data;
                this.ProductosOriginales = [...response.data];
                console.log(this.ProductosGuardados,'Mis datos');
            })
        },

        EditarProducto(id, index) {
            const indexGlobal = (this.paginaActual - 1) * this.productosPorPagina + index;
            const producto = this.ProductosGuardados[indexGlobal];

            // Set isNew to false indicating that it's an update
            this.isNew = false;

            // Set the ProductosIndex to the index of the product being edited
            this.ProductosIndex = indexGlobal;

            // Update the Productos object
            this.Productos = {
                id: producto.id,
                nombre: producto.nombre,
                costo: producto.costo,
                precio: producto.precio,
                cantidad: producto.cantidad
            };
        },

        //El siguiente meetodo es para ediar, sin embargo usa un index normal y no global
        //revisar el codigo de este con el de arriba (el de arriba si usa el global)
        //y ver como afecta y funciona el index global

        /*EditarProducto(id, index) {
            const producto = this.ProductosGuardados[index];

            // Set isNew to false indicating that it's an update
            this.isNew = false;

            // Set the ProductosIndex to the index of the product being edited
            this.ProductosIndex = index;

            // Update the Productos object
            this.Productos = {
                id: producto.id,
                nombre: producto.nombre,
                costo: producto.costo,
                precio: producto.precio,
                cantidad: producto.cantidad
            };
        },*/


        //paginacion

        cambiarPagina(pagina) {
            if (pagina > 0 && pagina <= this.totalPaginas) {
                this.paginaActual = pagina;
                this.actualizarProductosMostrados();
            }
        },

        actualizarProductosMostrados() {
            const inicio = (this.paginaActual - 1) * this.productosPorPagina;
            const fin = inicio + this.productosPorPagina;
            this.productosMostrados = this.ProductosGuardados.slice(inicio, fin);
        },


    },
    mounted(){
        this.getProductos();
    },
    computed: {
        totalPaginas() {
            return Math.ceil(this.ProductosGuardados.length / this.productosPorPagina);
        },

        paginasMostradas() {
            const totalPaginasMostradas = 3; // Cambia esto para mostrar más o menos páginas
            const mitadTotalPaginasMostradas = Math.floor(totalPaginasMostradas / 2);

            const primeraPaginaMostrada = Math.max(1, this.paginaActual - mitadTotalPaginasMostradas);
            const ultimaPaginaMostrada = Math.min(this.totalPaginas, primeraPaginaMostrada + totalPaginasMostradas - 1);

            const paginas = [];
            for (let i = primeraPaginaMostrada; i <= ultimaPaginaMostrada; i++) {
            paginas.push(i);
            }

            return paginas;
        },
    },
    watch: {
        ProductosGuardados() {
            this.paginaActual = 1;
            this.actualizarProductosMostrados();
        }
    },
};
</script>

<style>
.mdi-magnify{
    font-size: 40px;
}
</style>
