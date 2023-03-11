import {createApp} from "vue";
import router from "./router";

import './root.scss'

import Root from "./Root.vue";

const vm = createApp(Root);
vm.use(router);
vm.mount('#app');
