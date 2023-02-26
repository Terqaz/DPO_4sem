import {createApp} from "vue";
import Root from "./components/Root.vue";

import './root.scss'

const vm = createApp(Root);
vm.mount('#app');
