import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// import css
import '../temp/assets/css/dashlite.css';
import '../temp/assets/css/dashlite.min.css';
import '../temp/assets/css/theme.css';
// template 2
import '../temp2/assets/css/dashlite.css';
import '../temp2/assets/css/dashlite.min.css';
import '../temp2/assets/css/theme.css';



import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

//chart
import VueChartkick from 'vue-chartkick';
import 'chartkick/chart.js';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
createInertiaApp({
title: (title) => `${title} - ${appName}`,
resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
setup({ el, App, props, plugin }) {
return createApp({ render: () => h(App, props) })
.use(plugin)
.use(ZiggyVue)
.use(ElementPlus)
.use(VueChartkick)
.mount(el);
},
progress: {
color: '#4B5563',
},
});
