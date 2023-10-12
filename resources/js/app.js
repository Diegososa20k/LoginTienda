import './bootstrap';
import { createApp } from 'vue';
import PantallaVenta from '../src/components/PantallaVenta.vue'
import PantallaProducto from '../src/components/PantallaProducto.vue';


const app = createApp({
    components: {
        'pantalla-venta': PantallaVenta,
        'pantalla-producto': PantallaProducto,

    }
});

app.mount('#app');
