import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "@/global/reset.css";
import "@/global/global.css";

import InlineSvg from "vue-inline-svg";
createApp(App)
    .use(router)
    .use(store)
    .component("iSvg", InlineSvg)
    .mount("#app");
