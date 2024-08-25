import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";

export default createStore({
    modules: {
        userMod,
        clientMod,
    }
});