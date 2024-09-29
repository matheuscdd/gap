import { createStore } from "vuex";
import userMod from "./user";
import clientMod from "./client";
import stockTypeMod from "./stockType";
import budgetMod from "./budget";

export default createStore({
    modules: {
        userMod,
        clientMod,
        budgetMod,
        stockTypeMod,
    },
    actions: {
        clearAll({ commit }){
            [
                "userMod",
                "clientMod",
                "budgetMod",
                "stockTypeMod"
            ].forEach(mod => commit(`${mod}/reset`));
        }
    }
});