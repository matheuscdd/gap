import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import * as labsComponents from "vuetify/labs/components";
import pt from "vuetify/lib/locale/pt";
import InlineSvg from "vue-inline-svg";
import "@/global/reset.css";
import "@/global/global.css";
import "@mdi/font/css/materialdesignicons.css";
import VueHtml2Canvas from "vue-html2canvas";
import _ from "regenerator-runtime"; // This import is necessary for html2canvas


const vuetify = createVuetify({
    components: {
        ...components,
        ...labsComponents,
    },
    directives,
    locale: {
        locale: "pt",
        fallback: "pt",
        messages: { pt }
      },
});


createApp(App)
    .use(router)
    .use(store)
    .use(vuetify)
    .use(VueHtml2Canvas)
    .component("iSvg", InlineSvg)
    .mount("#app");
